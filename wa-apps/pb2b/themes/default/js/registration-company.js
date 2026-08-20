(function ($) {
    $.RegistrationCompany = {

        currentStep: 0,
        $steps: null,
        $stages: null,

        init() {
            this.cache();
            this.initMasks();
            this.initRegister();
            this.bindEvents();
            this.initNavigation();
            this.goToStep(0);
        },

        cache() {
            this.$steps = $('.form-step');
            this.$stages = $('.step-stage');
        },

        collectData() {
            const data = {};

            this.$steps.find('input, select').each(function () {
                const name = $(this).attr('name');
                if (!name) return;

                if ($(this).attr('type') === 'checkbox') {
                    data[name] = $(this).is(':checked');
                } else {
                    data[name] = $(this).val();
                }
            });

            return data;
        },

        renderReview() {
            const $reviewStep = this.$steps.eq(this.currentStep);
            const $list = $reviewStep.find('.review-list');

            $list.empty();

            this.$steps.each((stepIndex, stepEl) => {
                const $step = $(stepEl);

                // шаг проверки пропускаем
                if ($step.is($reviewStep)) return;

                const stageTitle = this.$stages.eq(stepIndex).text().trim();
                if (!stageTitle) return;

                const fields = [];

                $step.find('.input-wrap').each(function () {
                    const $wrap = $(this);
                    const $input = $wrap.find('input, select');
                    const label = $wrap.find('.input-label').text().trim();

                    if (!$input.length || !label) return;

                    let value;
                    if ($input.attr('type') === 'checkbox') {
                        value = $input.is(':checked') ? 'Да' : 'Нет';
                    } else if ($input.is('select')) {
                        value = $input.find('option:selected').text();
                    } else {
                        value = $input.val();
                    }

                    if (!value) return;

                    fields.push({ label, value });
                });

                if (!fields.length) return;


                const $stageBlock = $(`
                    <div class="review-stage">
                        <h4 class="review-stage-title">${stageTitle}</h4>
                    </div>
                `);


                fields.forEach(({ label, value }) => {
                    $stageBlock.append(`
                        <div class="review-item">
                            <div class="review-label">${label}</div>
                            <div class="review-value">${value}</div>
                        </div>
                    `);
                });

                $list.append($stageBlock);
            });
        },

        initMasks() {
            $(".mask_phone").inputmask("+9 (999) 999-99-99", {
                placeholder: " ",
                showMaskOnHover: false,
                showMaskOnFocus: false,
                clearIncomplete: true,
                jitMasking: true
            });
        },

        initRegister() {
            this.$steps.each(function (i) {
                $(this).attr('data-step', i);
            });
        },

        bindEvents() {
            const self = this;

            $(document).on('input change', '.form-step .input-wrap.required input, .form-step .input-wrap.required select', function () {
                const $step = $(this).closest('.form-step');
                const index = $step.index();
                self.updateButtons(index);
            });

            $(document).on('click', '.js-next-step', function () {
                if ($(this).prop('disabled')) return;
                self.nextStep();
            });

            $(document).on('click', '.js-prev-step', function () {
                self.prevStep();
            });

            $(document).on('click', '.js-submit', function () {
                const data = self.collectData();

                $.ajax({
                    url: '/company-registration/submit/',
                    method: 'POST',
                    data: data,
                    success(response) {
                        if (response.status === 'ok' && response.data) {
                            if (response.data.error) {
                                self.showVaultBoyError(response.data.message);
                                return;
                            }
                            self.$stages.eq(self.$stages.length - 1).removeClass('active').addClass('success');
                            window.location.reload();

                        } else {
                            console.log('Кринжовый ответ сервера:', response);
                        }
                    },
                    error(xhr) {
                        console.log('Сервер умер:', xhr.responseText);
                    }
                });

            });
        },

        showVaultBoyCustom({ img, bg, text }){
            const html = `
                <div class="success-overlay" style="background: ${bg}">
                    <div class="success-content">
                        <div class="vault-boy" style="background-image: url('${img}');"></div>
                        <div class="success-text">${text}</div>
                    </div>
                </div>
            `;
            $('body').append(html);

            const $vault = $('.vault-boy');

            $vault.css({ transform: 'scale(0.3) rotate(0deg)', opacity: 0 });

            anime({
                targets: $vault[0],
                scale: [0.3, 1],
                rotate: [0, 720],
                opacity: [0, 1],
                duration: 1000,
                easing: 'easeOutExpo',
            });

            setTimeout(() => $('.success-overlay').fadeOut(500, function() { $(this).remove(); }), 2000);
        },

        showVaultBoyError(message = 'Ошибка регистрации') {
            this.showVaultBoyCustom({
                img: '/wa-apps/pb2b/themes/default/img/fail.png',
                bg: 'rgba(220,20,60,0.3)',
                text: message
            });
        },

        showVaultBoy(message = 'Регистрация завершена') {
            this.showVaultBoyCustom({
                img: '/wa-apps/pb2b/themes/default/img/good.png',
                bg: 'rgba(34,139,34,0.3)',
                text: message
            });
        },


    initNavigation() {
            const self = this;

            this.$stages.on('click', function () {
                const index = $(this).index();
                if (index <= self.currentStep) {
                    self.goToStep(index);
                }
            });
        },

        goToStep(index) {
            if (index < 0 || index >= this.$steps.length) return;

            this.currentStep = index;

            this.$steps.removeClass('active').eq(index).addClass('active');
            this.$stages.removeClass('active success').each(function (i) {
                if (i < index) $(this).addClass('success');
                if (i === index) $(this).addClass('active');
            });

            if (this.$steps.eq(index).find('.review').length) {
                this.renderReview();
            }

            this.updateButtons(index);
        },


        nextStep() {
            this.goToStep(this.currentStep + 1);
        },

        prevStep() {
            this.goToStep(this.currentStep - 1);
        },

        updateButtons(index) {
            const $step = this.$steps.eq(index);
            const $nextBtn = $step.find('.js-next-step, .js-finish');

            let allFilled = true;
            $step.find('.input-wrap.required input, .input-wrap.required select').each(function () {
                if (!$(this).val()) allFilled = false;
            });

            $nextBtn.prop('disabled', !allFilled);
        },
    };
})(jQuery);
