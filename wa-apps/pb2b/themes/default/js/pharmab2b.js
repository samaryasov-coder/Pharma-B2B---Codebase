(function($){
    let card_slider = new Swiper('.hero__card-slider', {
        loop: true,
        spaceBetween: 40,
        autoplay: {
            delay: 4000,
            disableOnInteraction: false,
            pauseOnMouseEnter: true,
        },
        pagination: {
            el: '.hero__card-slider_pagination',
            clickable: true,
            dynamicBullets: true,
        },
    });

    let automation_slider = new Swiper('.automation__slider', {
        loop: true,
        spaceBetween: 0,
        effect: 'fade',
        fadeEffect: {
            crossFade: true
        },
        navigation: {
            nextEl: '.automation__pagination-button-next',
            prevEl: '.automation__pagination-button-prev',
        },
    });

    $('.questions__item').click(function () {
        const $item = $(this);
        const $answer = $item.find('.questions__question-answer');

        if ($item.hasClass('active')) {
            $answer.slideUp(200, function() {
                $item.removeClass('active no-border');
                $item.next('.questions__item').removeClass('no-border');
            });
        } else {
            $('.questions__item.active').each(function() {
                const $other = $(this);
                $other.find('.questions__question-answer').slideUp(200);
                $other.removeClass('active no-border');
                $other.next('.questions__item').removeClass('no-border');
            });

            // открываем текущий
            $item.addClass('active no-border');
            $answer.slideDown(200);
            $item.next('.questions__item').addClass('no-border');
        }
    });


    function animateCounter($el, target, duration) {
        const startTime = performance.now();
        const easeOut = t => 1 - Math.pow(1 - t, 3);
        function update(now) {
            const elapsed = now - startTime;
            const progress = Math.min(elapsed / duration, 1);
            $el.text(Math.floor(target * easeOut(progress)));

            if (progress < 1) requestAnimationFrame(update);
            else $el.text(target);
        }
        requestAnimationFrame(update);
    }


    function isVisible($el) {
        const windowTop = $(window).scrollTop();
        const windowBottom = windowTop + $(window).height();
        const elTop = $el.offset().top;
        const elBottom = elTop + $el.height();
        return elBottom > windowTop && elTop < windowBottom;
    }


    const $numbers = $('.stat-number').each(function() {
        $(this).data('animated', false);
    });

    function checkCounters() {
        $numbers.each(function() {
            const $this = $(this);
            if (!$this.data('animated') && isVisible($this)) {
                $this.data('animated', true);
                animateCounter($this, parseInt($this.data('target')), 1600);
            }
        });
    }

    $(window).on('scroll load', checkCounters);




    /*_____________ APP ___________________*/


    $.App = {
        logoutAction: null,
        selectAction: null,

        init: function() {
            const self = this;
            this.logoutAction = '.js-logout';
            this.selectAction = '.js-select';

            // const observer = new MutationObserver(function (mutations) {
            //     mutations.forEach(function (mutation) {
            //         mutation.addedNodes.forEach(function (node) {
            //             if (node.nodeType === 1) {
            //                 self.applyPhoneMask(node);
            //             }
            //         });
            //     });
            // });
            // observer.observe(document.body, {
            //     childList: true,
            //     subtree: true
            // });

            this.applyPhoneMask(document);
            this.initLogout()
            this.initPasswordToggle();
            this.initSelectToggle();
            this.initToggleSwiper();
        },
        initLogout(){
            const self = this;

            $(document).on('click', self.logoutAction, function (e) {
                $.ajax({
                    url: `/logout/`,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                }).done(function(html) {
                    window.location.reload();
                }).fail(function() {
                    console.log('Ошибка выхода');
                });
            });
        },

        initPasswordToggle: function() {
            $(document).on('click', '.js-password-toggle', function() {
                const $input = $(this).closest('.input-box').find('input');
                const $icon = $(this).find('i');
                if (!$input.length)
                    return;
                const isPassword = $input.attr('type') === 'password';
                $input.attr('type', isPassword ? 'text' : 'password');
                $icon.toggleClass('fa-eye', !isPassword).toggleClass('fa-eye-slash', isPassword);
            });
        },

        initToggleSwiper: function (){
            $('.toggle[data-toggle-id]').each(function() {
                const $toggle = $(this);
                const toggleId = $toggle.data('toggle-id');
                const $switcher = $(`.switcher[data-toggle-id="${toggleId}"]`);

                if (!$switcher.length) return;
                const $slides = $switcher.children('[data-type]');

                let lastIndex = $slides.index($slides.filter('.active'));
                if (lastIndex === -1) lastIndex = 0;

                const $initial = $slides.eq(lastIndex);
                $initial.show().addClass('active').css('opacity', 1);

                $toggle.waToggle({
                    change: function(event, target) {
                        const $activeSpan = $(target);
                        const type = $activeSpan.data('type');

                        const $next = $slides.filter(`[data-type="${type}"]`);
                        const nextIndex = $slides.index($next);

                        if (nextIndex === lastIndex) return;

                        const $current = $slides.eq(lastIndex);
                        const direction = nextIndex > lastIndex ? -1 : 1;

                        anime({
                            targets: $current[0],
                            opacity: [1, 0],
                            translateX: [0, 200 * direction],
                            duration: 200,
                            easing: 'easeInQuad',
                            complete: () => {
                                $current.removeClass('active').hide();
                                $next.show().addClass('active');
                                $slides.each(function () {
                                    $(this).find('input').prop('disabled', !$(this).hasClass('active'));
                                });
                                anime({
                                    targets: $next[0],
                                    opacity: [0, 1],
                                    translateX: [-200 * direction, 0],
                                    duration: 400,
                                    easing: 'easeOutQuad'
                                });
                                lastIndex = nextIndex;
                            }
                        });
                    }
                });
            });
        },

        initSelectToggle: function (){
            const self = this;

            $.fn.select2.defaults.set("language", {
                noResults: function () {
                    return "Элементы не найдены";
                }
            });

            $(self.selectAction).select2({
                multiple: false,
                placeholder: 'Выберите',
                minimumResultsForSearch: Infinity,
                allowClear: false,
                hideSelected: false
            });
            $(self.selectAction).val(null).trigger('change');

        },

        applyPhoneMask: function (container) {
            $(container).find(".mask_phone").each(function () {
                if (!this.inputmask) {
                    $(this).inputmask("999-999-99-99", {
                        placeholder: " ",
                        showMaskOnHover: false,
                        showMaskOnFocus: false,
                        clearIncomplete: false,
                        jitMasking: true
                    });
                }
            });
        }

    };
})(jQuery);