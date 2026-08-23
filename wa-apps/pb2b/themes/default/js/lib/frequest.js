(function($){

    $.fRequest = function(options = {}) {
        const viewMessage = (code, message) => {

            if (!config.showMessages)
                return;

            if (!message || (Array.isArray(message) && !message.length))
                return;

            if (code === 0)
                $.AlertManager.showError(message);

            else if (code === 1)
                $.AlertManager.showSuccess(message);
        };

        const setStatusRequestButton = (is_loading = false) => {
            if (config.button) {
                is_loading ?
                    $(config.button).addClass('loading').prop('disabled', true) :
                    $(config.button).removeClass('loading').prop('disabled', false);
            }
        }


        const config = {
            url: '',
            method: 'POST',
            data: {},
            dataType: 'json',

            processData: true,
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',

            button: null,
            showMessages: true,
            onSuccess: null,
            onError: null,

            onUploadProgress: null,

            ...options
        };

        if (config.data instanceof FormData) {
            config.processData = false;
            config.contentType = false;
        }



        return new Promise((resolve, reject) => {

            $.ajax({

                url: config.url,
                type: config.method,

                data: config.data,
                dataType: config.dataType,

                processData: config.processData,
                contentType: config.contentType,

                xhr: function () {
                    const xhr = new window.XMLHttpRequest();
                    if (xhr.upload && typeof config.onUploadProgress === "function") {
                        xhr.upload.addEventListener("progress", function (e) {
                            if (!e.lengthComputable) return;
                            config.onUploadProgress(e.loaded / e.total);

                        });
                    }
                    return xhr;
                },

                beforeSend() {
                    setStatusRequestButton(true);
                },

                complete() {
                    setStatusRequestButton();
                },

                success(reply) {

                    const reply_data = reply.data || {};
                    const result = reply_data.result ?? Number(!reply_data.error);

                    let callbackMessage;

                    if (result === 1) {

                        if (typeof config.onSuccess === 'function') {
                            callbackMessage = config.onSuccess(reply_data);
                        }

                        viewMessage(
                            result,
                            (callbackMessage === true || callbackMessage === undefined)
                                ? reply_data.message
                                : callbackMessage
                        );

                        resolve(reply_data);

                    } else {
                        if (typeof config.onError === 'function') {
                            callbackMessage = config.onError(reply_data);
                        }

                        viewMessage(
                            result,
                            (callbackMessage === true || callbackMessage === undefined)
                                ? reply_data.message
                                : callbackMessage
                        );

                        resolve(reply_data);
                    }
                },

                error(xhr) {
                    $.AlertManager.showError('Внутренняя ошибка');
                    setStatusRequestButton();
                    reject(xhr);
                }
            });
        });
    };
})(jQuery);