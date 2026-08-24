(function($) {
    var UploadPhotos = (function ($) {

        UploadPhotos = function(elem) {
            var that = this;

            // DOM
            that.component_id = Object.generateId(that);
            that.$body = $('body');
            that.$wrapper = $(elem);
            that.$file_input = that.$wrapper.find('[type="file"]');
            that.$upload_wrapper = that.$wrapper.find('.upload');
            that.photos_class = 'photos-container';
            that.photo_class = 'photo-container';
            that.remove_photo_class = 'photo-remove-icon';

            // INIT
            that.initClass();
        };

        UploadPhotos.prototype.initClass = function () {
            let that = this;

            that.$wrapper.addClass('box uploadbox');

            that.bindEvents();
        };

        UploadPhotos.prototype.bindEvents = function () {
            let that = this;

            that.$file_input.on('change', $.proxy(that.handleFiles, that));

            that.$body.on(`dragover.waUpload.${that.component_id}`, $.proxy(that.preventDefaults, that));
            that.$body.on(`drop.waUpload.${that.component_id}`, $.proxy(that.preventDefaults, that));

            that.$wrapper.on('dragover.waUpload, drop.waUpload', $.proxy(that.preventDefaults, that));
            that.$wrapper.on('dragenter.waUpload', $.proxy(that.highlight, that));
            that.$wrapper.on('dragleave.waUpload, drop.waUpload', $.proxy(that.unhighlight, that));
            that.$wrapper.on('drop.waUpload', $.proxy(that.handleDrop, that));
        }

        UploadPhotos.prototype.preventDefaults = function(e) {
            e.preventDefault();
        }

        UploadPhotos.prototype.highlight = function() {
            const that = this;
            that.$wrapper.addClass('highlighted');
        }

        UploadPhotos.prototype.unhighlight = function(e) {
            if ( e.currentTarget.contains(e.relatedTarget) ) {
                return;
            }

            const that = this;
            that.$wrapper.removeClass('highlighted');
        }

        UploadPhotos.prototype.handleDrop = function(e) {
            const that = this;
            that.addFiles(e.originalEvent.dataTransfer.files);
            that.handleFiles(that.files);
        }

        UploadPhotos.prototype.handleFiles = function(files) {
            const that = this;

            if (files.target) {
                that.addFiles(files.target.files);
            }

            that.renderFilesContainer();
        }

        UploadPhotos.prototype.getFiles = function() {
            return this.files;
        }

        UploadPhotos.prototype.renderFilesContainer = function(index, file) {
            const that = this;
            that.$upload_wrapper.find(`.${that.photos_class}`).remove();

            const $container = $(`<div class="${that.photos_class}" />`).prependTo(that.$upload_wrapper);
            for (let i = 0; i < that.files.length; i++) {
                const $photo = that.createImageWithControls(i, that.files[i]);
                $photo.attr('data-index', i);
                $container.append($photo);
            }

            $container.on('click', `.${that.remove_photo_class}`, function() {
                const $remove_icon = $(this);
                const $target = $remove_icon.closest(`.${that.photo_class}`);
                const target_index = $target.index();

                that.removePhoto(target_index);
                $target.remove();

                if (!that.files.length) {
                    $container.remove();
                }
            });

            $container.sortable({
                animation: 150,
                forceFallback: true,
                onEnd: () => {
                    const dt = new DataTransfer();
                    $container.children().each(function(i) {
                        dt.items.add(that.files[this.dataset.index]);
                        this.dataset.index = i;
                    });
                    that.files = dt.files;
                    that.$file_input[0].files = that.files;
                }
            });
        }

        UploadPhotos.prototype.addFiles = function(new_files) {
            if (!new_files?.length) return;
            const that = this;
            if (that.files?.length) {
                const dt = new DataTransfer();
                for (let i = 0; i < that.files.length; i++) {
                    dt.items.add(that.files[i]);
                }
                for (let i = 0; i < new_files.length; i++) {
                    dt.items.add(new_files[i]);
                }
                that.files = dt.files;
            } else {
                that.files = new_files;
            }
            that.$file_input[0].files = that.files;
        }

        UploadPhotos.prototype.createImageWithControls = function(index, file) {
            const that = this;
            const $img = $('<img draggable="false">');
            const data_url = URL.createObjectURL(file);
            $img.prop('src', data_url);
            const $img_wrapper = $(`
                <div class="${that.photo_class}">
                    <button type="button" class="${that.remove_photo_class} button circle small">
                        <i class="fas fa-times"></i>
                    </button>
                </div>`);

            $img_wrapper.append($img);

            return $img_wrapper;
        }

        UploadPhotos.prototype.removePhoto = function(target_index) {
            const that = this;
            const dt = new DataTransfer();
            const files = that.$file_input[0].files;
            for (let i = 0; i < files.length; i++) {
                if (i === target_index) {
                    const $img = that.$wrapper.find(`.${that.photo_class} img`).eq(target_index);
                    const data_url = $img.prop('src');
                    URL.revokeObjectURL(data_url);
                    continue;
                }
                dt.items.add(files[i]);
            }
            that.files = dt.files;
            that.$file_input[0].files = that.files;
        }

        UploadPhotos.prototype.unbindEvents = function() {
            const that = this;

            that.$body.off('.waUpload');
            that.$wrapper.off('.waUpload');
        }

        UploadPhotos.prototype.destroy = function() {
            var that = this;

            that.unbindEvents();
            that.$wrapper.removeData('waUpload');
        };

        if ( typeof Object.generateId == "undefined" ) {
            let id = 0;

            Object.generateId = function(o) {
                if ( typeof o.__uniqueid == "undefined" ) {
                    Object.defineProperty(o, "__uniqueid", {
                        value: ++id,
                        enumerable: false,
                        writable: false
                    });
                }

                return o.__uniqueid;
            };
        }

        return UploadPhotos;

    })($);

    var QuantityDropdown = (function($) {
        QuantityDropdown = function(options) {
            this.$section = options.$section;
            this.$dropdown = this.$section.find(".dropdown");
            this.$quatity_input = this.$dropdown.find(".js-quantity-input");
            this.$quatity_icon = this.$dropdown.find(".js-quantity-icon");
            this.$stock_inputs = this.$dropdown.find(".dropdown-body input[data-stock-id]");

            this.fullness_classes = ['text-red', 'text-orange', 'text-green'];
            this.stocks = options.stocks || {};
            this.virtual_stocks = Object.values(this.stocks).filter(s => s.is_virtual);

            // dynamics
            this.active_stock_quantity = Object.values(this.stocks).filter(s => !s.is_virtual)
                .reduce((acc, stock) => (acc[stock.id]='',acc), {});
            this.stock_funllness = Object.keys(this.stocks)
                .reduce((acc, stock_id) => (acc[stock_id]=2,acc), {});

            this.init();
        }

        QuantityDropdown.prototype.init = function() {
            this.initDropdown();
            this.initEvents();
        }

        QuantityDropdown.prototype.initDropdown = function() {
           this.$dropdown.waDropdown({
                hover: false,
                hide: false
            });
        }

        QuantityDropdown.prototype.initEvents = function() {
            const that = this;
            const $active_inputs = that.$stock_inputs.filter(function() {
                return !that.stocks[this.dataset.stockId].is_virtual;
            });

            const inputStockQuantity = function() {
                // this.value = this.value.replace(/(?!^)-/g, '');
                // this.value = this.value.replace(/[^\d-]/g, '');
                this.value = this.value.replace(/\D+/g, '');
                const data = that.getStockAndCount($(this));
                if (!data) return;
                that.updateStockState(data.stock, data.count);
            };
            $active_inputs.on('input', inputStockQuantity);

            const updateQuantityAndVirtualStocks = function() {
                that.updateQuantityToggle();
                that.updateVirtualStocks();
            };
            $active_inputs.on('change', updateQuantityAndVirtualStocks);
        }

        QuantityDropdown.prototype.updateStockState = function(stock, count) {
            const that = this;
            const $input = that.$stock_inputs.filter(`[data-stock-id="${stock.id}"]`);
            const $wrapper = $input.closest('.dropdown-item');
            const $icon = $wrapper.find('.icon');

            that.updateFunllnessState($icon, stock, count);

            if (stock.is_virtual) {
                $input.val(count);
            } else {
                that.active_stock_quantity[stock.id] = count;
            }
        };
        QuantityDropdown.prototype.updateFunllnessState = function($icon, stock, count) {
            const that = this;
            $icon.removeClass(that.fullness_classes);
            if (count !== '') {
                let { critical_count, low_count } = stock;
                critical_count = Number(critical_count);
                low_count = Number(low_count);
                count = Number(count);

                if (count <= critical_count) {
                    that.stock_funllness[stock.id] = 0;
                    $icon.addClass(that.fullness_classes[0]);
                    return;
                } else if (count <= low_count) {
                    that.stock_funllness[stock.id] = 1;
                    $icon.addClass(that.fullness_classes[1]);
                    return;
                }
            }

            that.stock_funllness[stock.id] = 2;
            $icon.addClass(that.fullness_classes[2]);
        };

        QuantityDropdown.prototype.updateVirtualStocks = function() {
            const that = this;
            that.virtual_stocks.forEach(stock => {
                const quantities = stock.substocks.map(stock_id => that.active_stock_quantity[stock_id]);
                const count = that.getQuantity(quantities);
                that.updateStockState(stock, count);
            });
        };

        QuantityDropdown.prototype.updateQuantityToggle = function() {
            const that = this;
            const quantity = that.getQuantity(that.active_stock_quantity);

            that.$quatity_input.val(quantity);

            that.$quatity_icon.removeClass(that.fullness_classes);
            if (quantity === '') {
                that.$quatity_icon.addClass(that.fullness_classes[2]);
            } else {
                const min_fullness = Math.min(...Object.values(that.stock_funllness));
                that.$quatity_icon.addClass(that.fullness_classes[min_fullness]);
            }
        };

        QuantityDropdown.prototype.getStockAndCount = function($input) {
            const that = this;
            const stock_id = $input.data('stock-id');
            const stock = that.stocks[stock_id];
            if (!stock) return;
            const count = $input.val();
            return { stock, count }
        };

        QuantityDropdown.prototype.getQuantity = function(quantities) {
            let result = '';
            quantities = Object.values(quantities);
            if (!quantities.some(q => q === '' || isNaN(Number(q)))) {
                result = quantities.reduce((acc, quantity) => {
                    return acc + Number(quantity);
                }, 0);
            }
            return result;
        };

        return QuantityDropdown;
    })($);

    var CreateProductDialog = ( function($) {
        CreateProductDialog = function (options) {
            this.$wrapper = options.$wrapper;
            this.$form = this.$wrapper.find('form');
            this.$submit_button = this.$wrapper.find('.js-submit');
            this.$errors = this.$wrapper.find('.js-error-message');

            this.wa_app_url = options.wa_app_url;
            this.skip_dialog_storage_key = options.skip_dialog_storage_key;
            this.urls = options.urls;
            this.templates = options.templates;
            this.stocks = options.stocks;

            this.uploadFiles = null;

            this.init();
        }

        CreateProductDialog.prototype.init = function() {
            this.initUploadPhotos();
            this.initExpandableTextarea();
            this.initStatusDropdown();
            this.initQuantityDropdown();
            this.initProductTypeDropdown();
            this.initCategoryDropdown();
            this.initSubmit();
            this.initTooltip();
            this.initClearErrorByFocus();
            this.initSkipDialog();

            this.focusProductName();
        };

        CreateProductDialog.prototype.initUploadPhotos= function() {
            const $drop_area = this.$wrapper.find(".drop-area");
            this.uploadFiles = new UploadPhotos($drop_area);
        };

        CreateProductDialog.prototype.initExpandableTextarea = function() {
            const $textarea = this.$form.find('textarea[name="product[name]"]');
            const lineHeight = $textarea[0].computedStyleMap().get('line-height').value;
            $textarea.on('keyup', () => {
                const { scrollHeight, offsetHeight } = $textarea[0];
                if (scrollHeight > offsetHeight) {
                    $textarea.height(`${offsetHeight + lineHeight}px`);
                }
            });
        };

        CreateProductDialog.prototype.initStatusDropdown = function() {
            const $section = $(".js-product-status-section"),
                $status_select = $section.find("#js-product-status-select"),
                $status_input = $status_select.find("input");

            $status_select.waDropdown({
                hover: false,
                items: ".dropdown-item",
                change: (event, target) => {
                    const status_id = $(target).data("id"),
                        status_ident = $(target).data("ident");

                    $section.attr("data-id", status_ident);
                    $status_input.val(status_id).trigger("change");
                }
            });
        };

        CreateProductDialog.prototype.initQuantityDropdown = function() {
            new QuantityDropdown({
                $section: this.$form.find('.s-qunatity-section'),
                stocks: this.stocks,
            });
        }

        CreateProductDialog.prototype.initProductTypeDropdown = function() {
            const that = this,
                $section = $(".js-product-type-section"),
                $select = $section.find("#js-product-type-select"),
                $link = $section.find(".js-setup-type-link"),
                $input = $select.find("input");

            $select.waDropdown({
                hover: false,
                items: ".dropdown-item",
                change: (event, target) => {
                    const id = $(target).data("id");
                    const href = $link.data('href').replace('%type_id%', id);
                    $link.attr('href', href);
                    $input.val(id).trigger("change");
                },
                open: (dropdown) => {
                    dropdown.$menu.find('input.js-field').val('');
                }
            });
        };

        CreateProductDialog.prototype.initCategoryDropdown = function() {
            const that = this,
                $section = that.$form.find(".js-category-section"),
                $select = $section.find("#js-product-main-category-select"),
                $remove_category_button = $section.find(".js-main-category-remove"),
                $toggle_button = $select.find(".dropdown-toggle"),
                $input = $select.find("input");

            const dropdown = $select.waDropdown({
                hover: false,
                items: ".dropdown-item",
                change: (event, target, dropdown) => {
                    const id = $(target).data("id");
                    $input.val(id).trigger("change");
                    $remove_category_button.show();
                },
                open: () => {
                    dropdown.$menu.find('input.js-field').val('');
                }
            }).data('dropdown');

            $remove_category_button.on('click', () => {
                dropdown.$menu.find('.selected').removeClass('selected');
                dropdown.setTitle($toggle_button.data('title'));
                $input.val(null).trigger("change");
                $remove_category_button.hide();
            });
        };

        CreateProductDialog.prototype.initSubmit = function() {
            const removeLoading = () => {
                this.$submit_button.find('.js-loading').remove();
            };

            let is_loading = false;
            this.$submit_button.on('click', (e) => {
                e.preventDefault();
                removeLoading();

                if (is_loading || !this.validate()) return;

                is_loading = true;
                this.$submit_button.prop('disabled', true);
                this.$submit_button.children().after(this.templates['loading']);

                this.submit(() => {
                    removeLoading();
                    this.$submit_button.prop('disabled', false);
                    is_loading = false;
                });
            })
        };

        CreateProductDialog.prototype.initTooltip = function() {
            this.$wrapper.find(".wa-tooltip").waTooltip({
                allowHTML: true,
                delay: 300,
                interactive: true
            });
        };

        CreateProductDialog.prototype.initClearErrorByFocus = function() {
            const that = this;
            that.$form.find(':input').on('focus change', function() {
                that.clearErrors($(this).closest('.field'));
            });
        };

        CreateProductDialog.prototype.initSkipDialog = function() {
            this.$wrapper.find('.js-skip-dialog').one('click', () => {
                localStorage.setItem(this.skip_dialog_storage_key, true);
                this.closeDialog();
            });
        };

        CreateProductDialog.prototype.submit = function(callback) {
            const payload = this.getFormData();
            return $.post(this.urls['create_product'], payload, (response) => {
                this.handleResponse(response, callback);
            }).catch(() => {
                callback();
            });
        };

        CreateProductDialog.prototype.submitPhotos = function(product_id) {
            const that = this;
            const deferred = $.Deferred();
            const files = that.uploadFiles.getFiles();
            if (!files?.length) {
                return deferred.resolve();
            }

            (async () => {
                for (const file of files) {
                    await that.submitPhoto(file, product_id);
                }
                deferred.resolve();
            })();

            return deferred;
        };

        CreateProductDialog.prototype.submitPhoto = function(files, product_id) {
            const formData = new FormData();
            formData.append("product_id", product_id);
            formData.append("files", files);

            return $.ajax({
                url: this.urls["upload_image"],
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST'
            });
        };

        CreateProductDialog.prototype.getFormData = function() {
            const data = this.$form.serializeArray();
            return data;
        };

        CreateProductDialog.prototype.validate = function() {
            this.clearErrors();

            let is_valid = true;
            const template = this.templates['error'];
            const $inputs = this.$form.find(':input[data-error]');
            $inputs.each(function() {
                const $input = $(this);
                const val = $input.val();
                if (!val || !val.trim()) {
                    is_valid = false;
                    const error_message = $input.data('error');
                    $input.addClass('state-error');
                    $input.after(template.replace('%content%', error_message));
                }
            });

            return is_valid;
        };

        CreateProductDialog.prototype.clearErrors = function($field) {
            $field = $field || this.$form;
            $field.find('.state-error').removeClass('state-error');
            $field.find('.state-error-hint').remove();
            this.$errors.empty();
        };

        CreateProductDialog.prototype.handleResponse = function(response, callback) {
            const that = this;
            if (response?.data?.id) {
                that.submitPhotos(response.data.id).always(() => {
                    callback();
                    const href = that.wa_app_url + that.urls['edit_product'].replace('%id%', response.data.id);
                    location.href = href;
                    that.closeDialog()
                });
            } else {
                callback();
                that.handleErrors(response);
            }
        };

        CreateProductDialog.prototype.handleErrors = function(response) {
            if (!response?.errors) return;
            response.errors.forEach(err => {
                if (err.name) {
                    this.addFieldError(err.name, err.text);
                } else {
                    const message = typeof err === 'string' ? err : err.text || '';
                    this.$errors.html(message);
                }
            });
        }

        CreateProductDialog.prototype.addFieldError = function(name, text) {
            const $input = this.$form.find(`[name="${name}"]`).addClass('state-error');
            const message = this.templates['error'].replace('%content%', text);
            $input.parent().append(message);
        }

        CreateProductDialog.prototype.closeDialog = function() {
            this.$wrapper.data('dialog').close();
        };

        CreateProductDialog.prototype.focusProductName = function() {
            setTimeout(() => {
                this.$form.find('[name="product[name]"]').focus();
            });
        };

        return CreateProductDialog;
    })($);

    $.wa_shop_products.init.initCreateProductDialog = (options) => {
        return new CreateProductDialog(options);
    };
})(jQuery);
