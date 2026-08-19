let initNavSidebar;
( function($) {

    var navSidebar = ( function($) {

        navSidebar = function(options) {
            var that = this;

            // DOM
            that.$wrapper = options["$wrapper"];

            // CONST
            that.tooltips = options["tooltips"];
            that.locales = options["locales"];
            that.urls = options["urls"];
            that.app_url = options["app_url"];
            that.localKey = "pharmab2b/"

            // DYNAMIC VARS
            that.$active_submenu_item = that.$wrapper.find("li.selected:first");
            that.states = {
                pinned: options["sidebar_menu_state"]
            };

           that.active_class = "closed";
           that.opened_class = "opened";

            // INIT
            that.init();
        };

        navSidebar.prototype.init = function() {
            let that = this,
                $document = $(document);

            that.setActive();
            that.initPin();

            if (that.states.pinned) {
                const $menu_toggle = that.$active_submenu_item.parent('ul').parent('li').find('.js-group-toggle');

                // Скрываем меню у остальных
                that.$wrapper.find(".js-group-toggle").each(function () {
                    that.groupExpand($(this), false);
                });

                // Показываем меню у каталога
                if ($menu_toggle.length) {
                    that.groupExpand($menu_toggle, true);
                }
            }

            // Открывашки
            that.$wrapper.on("click", ".js-group-toggle", function(event) {
                event.preventDefault();
                that.groupExpand($(this));
            });


            // Подсказки
            $.each(that.tooltips, function(i, tooltip) {
                $.wa.new.Tooltip(tooltip);
            });

            // Активный пункт при AJAX обновлнении контента
            $document.on("wa_loaded", loadWatcher);
            function loadWatcher(event) {
                var is_exist = $.contains(document, that.$wrapper[0]);
                if (is_exist) {
                    that.setActive(location.pathname + location.search + location.hash);
                } else {
                    $document.off("wa_loaded", loadWatcher);
                }
            }

            // Навешиваем обработчик на все ссылки в сайдбаре
            that.$wrapper.on('click', 'a.item', function(e) {
                // Если нужно, тут можно сделать проверку (например, на внешние ссылки)
                var $li = $(this).closest('li');
                that.setItem($li);
            });

            // Показываем готовый DOM
            that.$wrapper.css("visibility", "");

        };

        navSidebar.prototype.groupExpand = function ($toggle, show) {
            let that = this;
            let $group = $toggle.closest("li");

            show = (typeof show === "boolean" ? show : $group.hasClass(that.active_class));

            if (show) {
                $group.removeClass(that.active_class);
                $group.addClass(that.opened_class);
            } else {
                $group.addClass(that.active_class);
                $group.removeClass(that.opened_class);
            }
        }

        /**
         * @param {Object} $item
         * */
        navSidebar.prototype.setItem = function($item) {
            var that = this,
                active_menu_class = "selected",
                chosen_menu_class = "chosen";


            if (that.$active_submenu_item.length) {
                if (that.$active_submenu_item[0] === $item[0]) {
                    return false;
                }
                that.$active_submenu_item.removeClass(active_menu_class);
                that.$active_submenu_item.parent('ul').parent('li').removeClass(chosen_menu_class);
            }

            $item.parent('ul').parent('li').addClass(chosen_menu_class);
            that.$active_submenu_item = $item.addClass(active_menu_class);

        };

        navSidebar.prototype.specifyRuleCheck = function(href, relative_path) {
            let result = href === relative_path;
            const { hash, pathname, search } = location;

            if (!hash) {
                return result;
            }

            const hashTest = (pattern = '#') => new RegExp(pattern).test(hash);

            switch (href) {
                case pathname + '?action=products#/services/':
                    result = search === '?action=products' && hashTest('#\\/services\\/');
                    break;
                case pathname + '?action=reports':
                    result = search === '?action=reports' && hashTest('#sales');
                    break;
                case pathname + '?action=storefronts':
                    result = search === '?action=storefronts' && hashTest('#\\/design\\/theme');
                    break;
                case pathname + '?action=storefronts#/design/pages/':
                    result = search === '?action=storefronts' && hashTest('\\/pages\\/');
                    break;
                default:
                    break;
            }

            return result;
        }

        /**
         * @param {String?} uri
         * */
        navSidebar.prototype.setSidebarLink = function(uri) {
            var that = this,
                max_check = 3,
                count_check = 0;

            var isSettedLink = function(uri) {
                var $link = that.$wrapper.find("a.item[href='" + uri + "']");
                if ($link.length) {
                    that.setItem($link.closest("li"))
                    return true;
                }
                return false;
            };

            that.$wrapper.find("li.selected").removeClass("selected");
            that.$wrapper.find("li.chosen").removeClass("chosen");

            // one more try
            if (!isSettedLink(uri)) {
                var moreTry = function(uri) {
                    if (count_check === max_check || uri.indexOf('#/') !== -1) {
                        return;
                    }

                    var uri_array = uri.split('/').filter(str => !!str) || [];
                    if (uri_array.length < 3) {
                        return;
                    }

                    var new_uri;
                    var shortenURI = () => {
                        uri_array.pop();
                        return '/' + uri_array.join('/') + '/';
                    };

                    if (count_check > 0) {
                        new_uri = shortenURI();
                    } else {
                        var uri_without_query = uri.split('?')
                        uri_length = uri_without_query.length;
                        if (uri_length > 1) {
                            new_uri = uri_without_query[0];
                        } else if(uri_length) {
                            new_uri = shortenURI();
                        } else {
                            count_check = max_check;
                            return;
                        }
                    }

                    count_check += 1;

                    if (isSettedLink(new_uri)) {
                        count_check = max_check;
                    } else {
                        moreTry(new_uri);
                    }
                };
                moreTry(uri);
            }
        }

        navSidebar.prototype.initPin = function() {
            var that = this,
                $toggle = that.$wrapper.find(".js-toggle-sidebar"),
                $name = $toggle.find(".pharmab2b-name");

            var id = that.$wrapper.attr("id");
            var localValue = localStorage.getItem(that.localKey + id);
            if (localValue !== null){
                pin(Boolean(Number(localValue)))
            }
            else{
                pin(that.states.pinned);
            }

            $toggle.on("click", function(event) {
                event.preventDefault();
                pin(!that.states.pinned, true);
            });

            /**
             * @param {Boolean?} pin
             * @param {Boolean?} send_request
             * */
            function pin(pin, send_request) {
                var active_class = "is-pinned",
                    inactive_class = "is-unpinned",
                    disabled_class = "hover-is-disabled";

                pin = (typeof pin === "boolean" ? pin : !that.states.pinned);
                send_request = (typeof send_request === "boolean" ? send_request : false);

                that.$wrapper.addClass(disabled_class);

                if (pin) {
                    that.$wrapper.addClass(active_class);
                    that.$wrapper.removeClass(inactive_class);
                } else {
                    that.$wrapper.addClass(inactive_class);
                    that.$wrapper.removeClass(active_class);
                }
                window.parent.document.documentElement.style.setProperty('--main-sidebar-width', that.$wrapper.width() + 'px');

                // setTimeout( function() {
                //     that.$wrapper.removeClass(disabled_class);
                // }, 100);

                that.states.pinned = pin;

                var text = (that.states.pinned ? that.locales["unpin_menu"] : that.locales["pin_menu"]);
                $name.text(text);

                if (send_request) { request(pin); }

                $(function() {
                    that.signSidebarPinned('.js-main-content', pin);
                });
            }

            function request(pin) {
                var deferred = $.Deferred();
                var key = that.$wrapper.attr('id');
                var value = pin ? "1" : "0";

                if (that.urls["sidebar_menu_state"] === "local") {
                    localStorage.setItem(that.localKey + key, value);
                    return;
                }

                var data = {};
                data[key] = value;

                $.post(that.urls["sidebar_menu_state"], data, "json")
                    .always(function () {
                        deferred.resolve();
                    });

                return deferred.promise();
            }
        };

        /**
         * @param {String?} uri
         * */
        navSidebar.prototype.setActive = function(uri) {
            var that = this;

            if (uri) {
                that.setSidebarLink(uri);
            } else {
                var $links = that.$wrapper.find("a.item");
                relative_path = location.pathname,
                    location_search = location.search,
                    max_length = 0,
                    link_index = 0;

                $links.each(function (index) {
                    var $link = $(this),
                        href = $link.attr("href"),
                        href_length = href.length;

                    var is_absolute_coincidence = that.specifyRuleCheck(href, relative_path);
                    if (is_absolute_coincidence) {
                        link_index = index;
                        return false;

                    } else if (relative_path.indexOf(href) >= 0) {
                        if (href_length > max_length) {
                            max_length = href_length;
                            link_index = index;
                        }
                    } else if (location_search && href.includes(location_search)) {
                        link_index = index;
                    }
                });

                if (link_index || link_index === 0) {
                    $link = $links.eq(link_index);
                    $select_li = $link.closest("li");
                    that.setItem($select_li);


                    // Открывашки по наведению
                    $menu_toggle = $select_li.parent('ul').parent('li').find('.js-group-toggle');
                    if ($menu_toggle.length) {
                        that.$wrapper.off("mouseenter.menuToggle");
                        that.$wrapper.off("mouseleave.menuToggle");

                        that.$wrapper.on("mouseenter.menuToggle", function (event) {
                            if (!that.states.pinned) {
                                that.groupExpand($menu_toggle, true);
                            }
                        });

                        that.$wrapper.on("mouseleave.menuToggle", function (event) {
                            if (!that.states.pinned) {
                                that.groupExpand($menu_toggle, false);
                            }
                        });
                    }
                }
            }
        };

        navSidebar.prototype.signSidebarPinned = function(class_container, is_pin) {
            var container = $(class_container, document);
            if (!container.length) {
                return;
            }

            if (is_pin) {
                container.addClass('sidebar-pinned');
            } else {
                container.removeClass('sidebar-pinned');
            }
        }

        return navSidebar;

    })($);


    initNavSidebar = function(options) {
        return new navSidebar(options);
    };

})(jQuery);
