(function($) {
    $.extend($.pb2b, {
        companyCategorySelect: {
            companyId: null,
            dialogSelector: null,
            tree: null,
            treeClass: null,
            menuItems: null,

            init: function() {
                this.dialogSelector = '.js-company-category-dialog';
                this.tree = $(this.dialogSelector + ' .js-company-category-tree');
                this.treeClass = this.dialogSelector + ' .js-company-category-tree';
                this.companyId = $(this.dialogSelector).data('company-id');

                this.initTree();
                this.initSave();
            },

            initTree: function() {
                var self = this;
                if (!self.tree.length) return;
                
                $('body')
                    .off('click', self.dialogSelector + ' a.fhierarchical-item')
                    .on('click', self.dialogSelector + ' a.fhierarchical-item', function(e) {
                        if ($(e.target).closest('.collapse-handler').length) return true;
                        if ($(e.target).is('input[type=checkbox]')) return true;

                        e.preventDefault();
                        var $a = $(this);
                        var $cb = $a.find('input.fhierarchical-item-checkbox').first();
                        if (!$cb.length) return false;
                        $cb.prop('checked', !$cb.prop('checked')).trigger('change');

                        return false;
                    });
                
                var syncing = false;

                $('body')
                    .off('change', self.dialogSelector + ' .fhierarchical-item-checkbox')
                    .on('change', self.dialogSelector + ' .fhierarchical-item-checkbox', function() {
                        if (syncing) return;
                        syncing = true;

                        var checkbox = $(this);

                        var dialog = checkbox.closest(self.dialogSelector);
                        var all = dialog.find('.fhierarchical-item-checkbox');

                        var checked = checkbox.prop('checked');
                        var selfLeft = parseInt(checkbox.data('left-key'), 10) || 0;
                        var selfRight = parseInt(checkbox.data('right-key'), 10) || 0;

                        self.setChildrenStateByNestedSet(all, selfLeft, selfRight, checked);
                        self.updateParents(all, checkbox);

                        syncing = false;
                    });
               
                self.menuItems = self.tree.fHierarchical({
                    treeClass: self.treeClass,
                    itemType: 'company-category',
                    itemUrl: '#',
                    newpositionClass: 'fhierarchical-newposition-company-category',
                    itemClass: 'fhierarchical-item-company-category',
                    onItemToggle: function(itemId, state) { return false; }
                });
            },

            setChildrenStateByNestedSet: function(all, selfLeft, selfRight, checked) {
                all.each(function () {
                    var cb = $(this);
                    var l = parseInt(cb.data('left-key'), 10) || 0;
                    var r = parseInt(cb.data('right-key'), 10) || 0;
                    if (l > selfLeft && r < selfRight) cb.prop('checked', checked);
                });
            },
            
            updateParents: function($all, $startCb) {
                var parentId = parseInt($startCb.data('parent-id'), 10) || 0;
            
                while (parentId > 0) {
                    var $parent = $all.filter('[data-category-id="' + parentId + '"]');
                    if (!$parent.length) break;
                   
                    var $children = $all.filter('[data-parent-id="' + parentId + '"]');
                    if ($children.length) {
                        var allChecked = ($children.filter(':checked').length === $children.length);
                        $parent.prop('checked', allChecked);
                    } else {
                        $parent.prop('checked', false);
                    }
            
                    parentId = parseInt($parent.data('parent-id'), 10) || 0;
                }
            },

            initSave: function() {
                var self = this;
                $('body')
                    .off('click', self.dialogSelector + ' .js-company-category-save')
                    .on('click', self.dialogSelector + ' .js-company-category-save', function() {

                        var $dialog = $(this).closest(self.dialogSelector);
                        var ids = [];
                        $dialog.find('.fhierarchical-item-checkbox:checked').each(function() {
                            var id = parseInt($(this).data('category-id'), 10);
                            if (id) ids.push(id);
                        });

                        ids = Array.from(new Set(ids)); 
                        
                        $.post('?module=company&action=categorySet', {
                            id: self.companyId,
                            ids: ids
                        }, function(jData) {
                            if (wa_pro_dialog) { wa_pro_dialog.close(); }
                            location.reload();
                        }, 'json');
                    });
            }
        }
    });
})(jQuery);

$(document).ready(function() {
    $.pb2b.companyCategorySelect.init();
});
