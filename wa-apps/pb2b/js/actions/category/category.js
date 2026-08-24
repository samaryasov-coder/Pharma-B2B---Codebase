(function($) {
    $.extend($.pb2b, {
        menu: {
            id: null,
            caption: null,
            itemTree: null,
            itemTreeClass: null,
            itemAddIconClass: null,
            menuItems: null,
            menuItemClass: null,
            init: function() {
                this.caption = $('.js-menu-caption');
                this.id = this.caption.attr('data-id');
                this.itemTree = $('.js-menu-items');
                this.itemTreeClass = '.js-menu-items';
                this.itemAddIconClass = '.fhierarchical-menu-create';
                this.menuItemClass = '.js-popup-drawer';

                let self = this;
                self.initItemTree();
            },
            initItemTree: function() {
                let self = this;
                if(!self.itemTree.length) {
                    return;
                }
                $('body').off('click', self.itemAddIconClass).on('click', self.itemAddIconClass, function() {
                    let parent_id = $(this).attr('data-parent');
                    let payload = 'data[name]='+encodeURIComponent('Новый пункт')+'&data[parent_id]='+parent_id;
                    $.post('?module=category&action=save', payload, function(Json) {
                        if(Json.data.error) {
                            alert(Json.data.message);//TODO
                        } else {
                            Json.data.item.action = '?module=category&action=edit'
                            self.menuItems.addItem(Json.data.item);
                        }
                    }, 'JSON');
                    return false;
                });
                this.menuItems = self.itemTree.fHierarchical({
                    'treeClass': self.itemTreeClass,
                    'itemType': 'menu',
                    'itemUrl': '#/menu/edit/id='+self.id,
                    'newpositionClass': 'fhierarchical-newposition-menu',
                    'itemClass': 'fhierarchical-item-menu '+self.menuItemClass.substr(1),
                    'onItemMove': function(draggedId, parentId, beforeId) {
                        let before_condition = '';
                        if(beforeId !== null) {
                            before_condition = '&before_id='+beforeId;
                        }
                        $.post('?module=category&action=move', 'id='+draggedId+'&parent_id='+parentId+before_condition, function(Json) {
                            if(Json.data.error) {
                                $.waDialog.alert({
                                    title: 'Возникла ошибка',
                                    text: Json.data.message,
                                    button_title: 'Понятно',
                                    button_class: 'warning',
                                });
                                $.pb2b.action.dispatch();
                            }
                        }, 'json');
                    },
                    'onItemToggle': function(itemId, state) {
                        $.post('?module=category&action=setState', 'id='+itemId+'&state='+state, function(Json) {
                        }, 'json');
                        return false;
                    }
                });
            },
            renameMenuItem: function(item) {
                this.menuItems.renameItem(item);
                if(item.id == self.id) {
                    $('.wcms-menu-name[data-id="'+self.id+'"]').text(item.name);
                }
            }
        }
    });
    $.extend($.pb2b.action, {
        afterSubmit: function (data, form) {
            if (data.item.id) {
                let menu = $.pb2b.menu;
                let item = $(menu.menuItemClass+'[data-id="'+data.item.id+'"]');
                item.find('.name').text(data.item.name);
                form.find('.js-form-message').find('svg').hide();
                form.find('.js-form-message').find('.fa-check-circle').show();
                form.find('.js-form-message').find('.js-form-message-text').text(data.message ?? 'Данные сохранены');
                form.find('.js-form-message').removeClass('danger').addClass('success').show();
            }
        },
        categoryDelete: function (params) {
            $.post('?module=category&action=delete', params, function (Json) {
                if (Json.data.error) {
                    wa_pro_form.find('.js-form-message').find('svg').hide();
                    wa_pro_form.find('.js-form-message').find('.fa-skull').show();
                    wa_pro_form.find('.js-form-message').find('.js-form-message-text').text(Json.data.message);
                    wa_pro_form.find('.js-form-message').removeClass('success').addClass('danger').show();
                } else {
                    wa_pro_drawer.close();
                    $.pb2b.dispatch(1);
                }
            }, 'JSON');
        }
    });
})(jQuery);
$(document).ready(function() {
    $.pb2b.menu.init();
});