(function ($) {
    $.Cabinet.registerPage('prequal-form', {
        init: function (el) {
            const $root = $(el);
            if ($root.data('prequal-ready')) return;
            $root.data('prequal-ready', true);

            const $form = $root.find('.js-prequal-form');
            const $suppliers = $root.find('.js-prequal-suppliers');
            let id = Number(el.dataset.prequalId) || 0;
            let currentStep = 'access';
            let busy = false;

            $suppliers.select2({
                language: 'ru',
                placeholder: 'Выберите поставщиков',
                multiple: true,
                closeOnSelect: false,
                ajax: {
                    url: '/api/buyer/company/select/',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return { search: params.term || '', supplier: 1 };
                    },
                    processResults: function (reply) {
                        const data = reply.payload || reply.data || reply;
                        return { results: Array.isArray(data.results) ? data.results : [] };
                    }
                }
            });

            const showMessage = function (message, error) {
                $root.find('.js-prequal-message')
                    .prop('hidden', false)
                    .toggleClass('is-error', !!error)
                    .text(message);
            };

            const showStep = function (step, focus) {
                currentStep = step;
                $root.find('.js-prequal-panel').each(function () {
                    this.hidden = this.dataset.step !== step;
                });
                $root.find('.js-prequal-step').each(function () {
                    if (this.dataset.step === step) this.setAttribute('aria-current', 'step');
                    else this.removeAttribute('aria-current');
                });
                if (focus) $root.find('.js-prequal-panel[data-step="' + step + '"] h2').trigger('focus');
            };

            const save = async function (advance) {
                if (busy) return false;
                const suppliers = $suppliers.val() || [];
                const data = {
                    title: $form.find('[name="title"]').val(),
                    description: $form.find('[name="description"]').val(),
                    is_private: Number($form.find('[name="is_private"]:checked').val()),
                    invitations: suppliers.length ? suppliers : ''
                };
                busy = true;
                $root.find('button, input, textarea, select').prop('disabled', true);
                $root.attr('aria-busy', 'true');
                try {
                    const reply = await $.fRequest({
                        url: id ? '/api/buyer/prequal/save/' : '/api/buyer/prequal/create/',
                        data: { id: id, data: data },
                        showMessages: false
                    });
                    if (!document.contains(el)) return false;
                    if (reply.error || !reply.item || !(Number(reply.item.id) > 0)) {
                        showMessage(reply.message || 'Не удалось сохранить черновик. Повторите попытку.', true);
                        $root.find('.js-prequal-message').trigger('focus');
                        return false;
                    }
                    id = Number(reply.item.id);
                    el.dataset.prequalId = String(id);
                    $root.find('.js-prequal-title').text('Редактирование предварительной квалификации');
                    $root.find('.js-prequal-code').prop('hidden', false).text(reply.item.procedure_code);
                    $root.find('.js-prequal-save').text('Сохранить изменения');
                    if (advance) showStep('basic', true);
                    const url = '/cabinet/buyer/prequal/' + id + '/edit/'
                        + (currentStep === 'basic' ? '?step=basic' : '');
                    window.history.replaceState(window.history.state, '', url);
                    showMessage('Черновик сохранён', false);
                    return true;
                } catch (error) {
                    if (document.contains(el)) showMessage('Не удалось сохранить черновик. Повторите попытку.', true);
                    return false;
                } finally {
                    busy = false;
                    $root.find('button, input, textarea, select').prop('disabled', false);
                    $root.removeAttr('aria-busy');
                }
            };

            $form.on('submit.prequal', function (event) {
                event.preventDefault();
                save(false);
            });
            $root.on('click.prequal', '.js-prequal-next', function () { save(true); });
            $root.on('click.prequal', '.js-prequal-back', function () { showStep('access', true); });
            $root.on('click.prequal', '.js-prequal-step', function () {
                if (busy || this.dataset.step === currentStep) return;
                if (this.dataset.step === 'basic') save(true);
                else showStep('access', true);
            });
            $form.on('change.prequal', '[name="is_private"]', function () {
                $root.find('.js-prequal-suppliers-wrap').prop('hidden', this.value !== '1');
            });
            if (id && new URLSearchParams(window.location.search).get('step') === 'basic') {
                showStep('basic', false);
            }
        }
    });
})(jQuery);
