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
        };

        /** Кабинет: {status,message,payload}; legacy: {data:{...}} или плоский {error,message}. */
        const unwrapReply = (reply) => {
            if (!reply || typeof reply !== 'object') {
                return {};
            }

            let data = {};
            const payload = reply.payload;
            if (payload != null && typeof payload === 'object' && !Array.isArray(payload)) {
                data = Object.assign({}, payload);
            } else if (reply.data != null && typeof reply.data === 'object' && !Array.isArray(reply.data)) {
                data = Object.assign({}, reply.data);
            } else if (!Object.prototype.hasOwnProperty.call(reply, 'status')
                || !Object.prototype.hasOwnProperty.call(reply, 'payload')) {
                data = Object.assign({}, reply);
            }

            if (reply.message && (data.message == null || data.message === '')) {
                data.message = reply.message;
            }
            if (reply.status === 'error') {
                data.error = true;
            }

            return data;
        };

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

                    const reply_data = unwrapReply(reply);
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
                    let reply_data = { error: true, message: 'Внутренняя ошибка' };
                    try {
                        const parsed = JSON.parse(xhr.responseText || '');
                        reply_data = unwrapReply(parsed);
                        if (!reply_data.error) {
                            reply_data.error = true;
                        }
                        if (!reply_data.message) {
                            reply_data.message = 'Внутренняя ошибка';
                        }
                    } catch (e) {
                        // keep default
                    }

                    let callbackMessage;
                    if (typeof config.onError === 'function') {
                        callbackMessage = config.onError(reply_data);
                    }

                    viewMessage(
                        0,
                        (callbackMessage === true || callbackMessage === undefined)
                            ? reply_data.message
                            : callbackMessage
                    );

                    setStatusRequestButton();
                    resolve(reply_data);
                }
            });
        });
    };
})(jQuery);
