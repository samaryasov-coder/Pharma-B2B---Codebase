(function($){
    $.Auth = {
        Login: {
            $form: null,
            $helper: null,

            init: function() {
                this.$form = $('.auth-login');
                this.$helper = $('.help');

                this.bindEvents();
            },

            bindEvents: function (){
                const self = this;
                this.$form.fSend({
                    prepareForm: function(fData) {
                        const phone = fData.get('phone');
                        if (phone) fData.set('phone', '7' + phone.replace(/\D/g, ''));
                    },
                    onSuccess: function(reply) {
                        window.location.href = '/cabinet/';
                    },
                    onError: function (reply){
                        self.$helper.delay(200).fadeIn(400);
                    }
                });
            }
        },

        Register: {
            $form: null,

            init: function() {
                this.$form = $('.auth-register');

                this.bindEvents();
            },

            bindEvents: function (){
                this.$form.fSend({
                    prepareForm: function(fData) {
                        const phone = fData.get('phone');
                        if (phone) fData.set('phone', '7' + phone.replace(/\D/g, ''));
                    },
                    onSuccess: function(reply) {
                        window.location.href = '/auth/code?token='+reply.token;
                    }
                });
            }
        },

        Password:{
            $form: null,
            $password: null,
            $password_confirm: null,
            $rules: null,
            $submit: null,
            rules: {
                length:     v => v.length >= 8,
                uppercase:  v => /[A-Z]/.test(v),
                lowercase:  v => /[a-z]/.test(v),
                number:     v => /\d/.test(v),
                special:    v => /[!@#\-?]/.test(v),
            },

            init: function (){
                this.$form = $('.auth-password');
                this.$password = $('#password');
                this.$password_confirm = $('#password_confirmation');
                this.$rules = $('.auth-form__password-rules li');
                this.$submit = $('.auth-form__actions button[type="submit"]');

                this.bindEvents();
            },

            bindEvents: function (){
                const self = this;

                this.$password.on('input', function (){
                    const value = $(this).val();
                    self.$rules.each(function () {
                        const $li = $(this);

                        if (value && self.rules[$li.data('rule')](value))
                            $li.addClass('valid');
                        else
                            $li.removeClass('valid');
                    });
                    self.updateButtonState();
                });

                this.$password_confirm.on('input', function (){
                    const $confirmWrap = $(this).closest('.input-wrap');
                    $confirmWrap.removeClass('error');
                    $confirmWrap.find('.input-hint').text('');
                    self.updateButtonState();
                });

                this.$form.fSend({
                    prepareForm: function(formData, $form) {
                        const pass = self.$password.val();
                        const confirm = self.$password_confirm.val();

                        $('.input-wrap').removeClass('error');
                        $('.input-hint').text('');

                        if (pass !== confirm) {
                            const errors = {
                                password_confirmation: 'Пароли не совпадают'
                            };
                            $.each(errors, function(field, message) {
                                const $input = $form.find('[name="' + field + '"]');
                                const $wrap = $input.closest('.input-wrap');
                                const $hint = $wrap.find('.input-hint');

                                $wrap.addClass('error');
                                $hint.text(message);
                            });

                            return false;
                        }
                        return true;
                    },

                    onSuccess: function(reply) {
                        window.location.href = '/cabinet/';
                    }

                });
            },

            checkPasswordRules: function (value){
                return Object.values(this.rules).every(rule => rule(value));
            },

            updateButtonState: function() {
                const isValid = this.checkPasswordRules(this.$password.val()) && this.rules['length'](this.$password_confirm.val());
                this.$submit.prop('disabled', !isValid);
            },
        },

        Code: {
            $form: null,
            $inputs: null,
            $timerBlock: null,
            $timerValue: null,
            $resendBtn: null,
            timerId: null,
            TIMER_SECONDS: null,

            init: function() {
                this.$form = $('.auth-otp');
                this.$inputs =  $('.otp-input');
                this.$timerBlock = this.$form.find('[data-timer]');
                this.$timerValue = this.$form.find('[data-timer-value]');
                this.$resendBtn = this.$form.find('[data-resend]');

                this.TIMER_SECONDS = parseInt(this.$timerBlock.data('timer-seconds')) || 60;

                this.bindEvents();
                this.startTimer();
            },

            bindEvents: function (){
                const self = this;

                this.$form.fSend({
                    prepareForm: function(fData, $form) {
                        let code = '';
                        $form.find('.otp-input').each(function() {
                            code += $(this).val();
                        });

                        fData.set('code', code);
                        fData.set('token', self.getTokenUrl());
                    },
                    onSuccess: function(reply) {
                        window.location.href = '/auth/password/';
                    }
                });

                this.$inputs.on('input', function() { self.handleInput.call(self, this); })
                    .on('keydown', function(e) { self.handleBackspace.call(self, this, e); })
                    .on('paste', function(e) { self.handlePaste.call(self, this, e); });

                this.$resendBtn.on('click', function (e) {
                    e.preventDefault();
                    const token = self.getTokenUrl();
                    $.fRequest({
                        url: '/resend/',
                        data: { token },
                        button: self.$resendBtn,
                        onSuccess: (reply) => {
                            self.startTimer();
                            const token = reply.token;
                            if (!token) return;

                            const url = new URL(window.location.href);
                            url.searchParams.set('token', token);
                            window.history.replaceState({}, '', url);
                        }

                    });
                });
            },

            getTokenUrl: function (){
                return new URLSearchParams(location.search).get('token');
            },

            startTimer: function (seconds = this.TIMER_SECONDS){
                clearInterval(this.timerId);
                let remaining = seconds;
                this.$timerValue.text(remaining);

                this.$timerBlock.show();
                this.$resendBtn.hide();

                this.timerId = setInterval(()=> {
                    this.$timerValue.text(--remaining);

                    if (remaining <= 0) {
                        clearInterval(this.timerId);
                        this.$timerBlock.hide();
                        this.$resendBtn.show();
                    }
                }, 1000);
            },

            handleInput: function(inputElement) {
                const $this = $(inputElement);
                const value = $this.val().replace(/\D/g, '');
                $this.val(value);

                if (value.length === 1) {
                    $this.next('.otp-input').focus();
                }

                const allFilled = this.$inputs.toArray().every(input => $(input).val().length === 1);
                if (allFilled) {
                    this.$form.submit();
                }
            },

            handleBackspace: function(inputElement, e) {
                const $this = $(inputElement);
                if (e.key === 'Backspace' && !$this.val()) {
                    $this.prev('.otp-input').focus();
                }
            },

            handlePaste: function(inputElement, e) {
                e.preventDefault();
                const data = e.originalEvent.clipboardData.getData('text').replace(/\D/g, '');

                this.$inputs.each(function(i) {
                    $(this).val(data[i] || '');
                });

                const allFilled = this.$inputs.toArray().every(input => $(input).val().length === 1);
                if (allFilled) {
                    this.$form.submit();
                }

                this.$inputs.eq(Math.min(data.length, this.$inputs.length - 1)).focus();
            }
        },

        Recovery: {
            $form: null,

            init: function() {
                this.$form = $('.auth-recovery');

                this.bindEvents();
            },

            bindEvents: function (){
                this.$form.fSend({
                    prepareForm: function(fData) {
                        const phone = fData.get('phone');
                        if (phone) fData.set('phone', '7' + phone.replace(/\D/g, ''));
                    },
                    onSuccess: function(reply) {
                        window.location.href = '/auth/code?token='+reply.token;
                    }
                });
            }
        }
    };
})(jQuery);
