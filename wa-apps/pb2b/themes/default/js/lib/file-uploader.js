(function($){

    class FileUploader {
        constructor(dropzone) {
            this.isReplacing = false;
            this.$dropzone = $(dropzone);
            this.fieldName = this.$dropzone.data('name');

            this.$container = this.$dropzone.closest('.file-upload-container');

            if (!this.$container.find('.uploaded-files').length) {
                this.$container.append(`
                    <div class="uploaded-files hide">
                        <ul class="uploaded-list"></ul>
                    </div>
                `);
            }

            this.$uploaded = this.$container.find('.uploaded-files');
            this.$list = this.$container.find('.uploaded-list');

            this.file = null;

            this.initDropzone();
            this.bindInitialState();
        }

        initDropzone() {

            const self = this;

            this.dz = new Dropzone(this.$dropzone[0], {
                url: "/fake-url",
                autoProcessQueue: false,
                maxFiles: 1,
                clickable: true,

                previewTemplate: `
                    <div class="dz-preview dz-file-preview">
                        <div class="dz-details">
                            <span class="dz-filename"><span data-dz-name></span></span>
                            <span class="dz-size" data-dz-size></span>
                        </div>
                    </div>
                `
            });

            this.dz.on("addedfile", function(file) {
                if (this.isReplacing && this.file) {
                    this.dz.removeFile(this.file);
                    this.isReplacing = false;
                }

                self.setFile(file);
            });
        }

        bindInitialState() {
            const name = this.$dropzone.data('file-name');
            const size = this.$dropzone.data('file-size');

            if (!name) return;

            this.setFile({
                name,
                size,
                accepted: true
            }, false);
        }

        setFile(file, syncDropzone = true) {
            this.file = file;
            this.render();
        }

        render() {

            if (!this.file) {

                this.$uploaded.hide();
                this.$dropzone.show();
                return;
            }

            this.$dropzone.hide();
            this.$uploaded.show();

            const ext = (this.file.name || '').split('.').pop().toUpperCase();

            const $item = $(`
                <li class="file-item">
                    <span class="file-left">
                        <span class="file-ext icon square">${ext}</span>

                        <div class="file-info">
                            <span class="file-name">${this.file.name}</span>
                            <span class="file-size">
                                Файл загружен • ${(this.file.size / 1024).toFixed(0)} KB
                            </span>
                        </div>
                    </span>

                    <div class="file-actions">
                        <span class="file-replace button secondary">
                            <svg><use href="#icon-arrow-rounded-square"></use></svg>
                        </span>

                        <span class="file-remove button secondary">
                            <svg><use href="#icon-x-mark"></use></svg>
                        </span>
                    </div>
                </li>
            `);

            this.$list.html($item);

            // replace
            $item.find('.file-replace').on('click', () => {
                this.replace();
            });

            // remove
            $item.find('.file-remove').on('click', () => {
                this.clear(true);
            });
        }


        replace() {
            this.isReplacing = true;

            this.dz.hiddenFileInput.value = null;
            this.dz.hiddenFileInput.click();
        }

        clear(resetUI = true) {

            this.file = null;

            if (this.dz) {
                this.dz.removeAllFiles(true);
                this.dz.files = [];
                this.dz.hiddenFileInput.value = null;
            }

            this.$list.empty();

            if (resetUI) {
                this.$uploaded.hide();
                this.$dropzone.show();
            }
        }

        appendToForm(formData) {
            if (this.file) {
                formData.append(this.fieldName, this.file);
            }
        }

        updateProgress(percent) {

        }

        getFile() {
            return this.file;
        }
    }

    $.FileUploader = FileUploader;

})(jQuery);