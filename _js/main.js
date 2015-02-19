jQuery.onLayoutChange = $.Callbacks('memory');

jQuery(function ($) {

    $(window).on('resize', function () {
        var state;

        function onresize() {
            var isMobile = $('header nav').css('position') == 'absolute';

            if (isMobile !== state) {
                $.onLayoutChange.fire(isMobile);
                state = isMobile;
            }
        }

        $(onresize);
        return onresize;
    } ());

    // onLayoutChange not supported in IE8, so...

    $('footer .wrapper').appendTo($('.gallery'));

    $.onLayoutChange.add(function (isMobile) {
        if (isMobile) {

            $('#logo').prependTo($('header'));
            $('header nav > ul:last-child > li:last-child').appendTo($('header nav > ul:first-child'));

            $('nav ul li div').removeClass('bg-purple inner-top-shadow dropdown experience');
            $('header nav').addClass('bg-purple inner-top-shadow');

            $('.nav-toggle').click(function () {
                $('header nav').toggleClass('drop');
                $('nav > ul > li > span').removeClass('expanded');
                $('nav > ul > li > div').hide();
            })
            // toggle sub-menus
            $('nav .plus').each(function (index, element) {
                $(element).click(function (index, element) {
                    $('nav span').not(this).removeClass('expanded');
                    $(this).next().addClass('expanded');
                    $(this).addClass('expanded');

                    $(this).nextAll().eq(2).slideDown();
                    $('nav .plus').not(this).nextAll().eq(2).slideUp();
                });
            });
            $('nav .minus').each(function (index, element) {
                $(element).click(function (index, element) {
                    $(this).prev().removeClass('expanded');
                    $(this).removeClass('expanded');

                    $(this).nextAll().eq(1).slideUp();
                });
            });

            // GALLERY
            // caption to bottom
            $('.caption').removeClass('caption-slide top right');
            // toggle expanded caption
            $('.caption > h2').each(function (index, element) {
                $(element).click(function (index, element) {
                    $(this).siblings('a').children('p').toggle();
                    $(this).parent().toggleClass('expanded');
                });
            });
            // pager to footer
            $('.gallery .wrapper').prependTo($('footer'));

        } else {

            /* -----------------------
            MEGA NAVIGATION MENU
            ----------------------- */
            function Timer(callback, delay) {
                this.callback = callback;
                this.delay = delay;
            }

            Timer.prototype = {
                start: function () {
                    var self = this, args = arguments;

                    this.cancel();
                    this.timerId = setTimeout(function () { self.callback.apply(self, args); }, this.delay);
                },
                cancel: function () {
                    if (this.timerId) {
                        clearTimeout(this.timerId);
                        delete this.timerId;
                    }
                },
                call: function () {
                    this.cancel();
                    this.callback.apply(this, arguments);
                }
            };


            function MegaDropDown(element) {
                this.element = $(element);

                this.showTimer = new Timer($.proxy(this, 'show'), 250);
                this.hideTimer = new Timer($.proxy(this, 'hide'), 400);

                $.onLayoutChange.add($.proxy(function (isMobile) {
                    this[isMobile ? 'deactivate' : 'activate']();
                }, this));
            }

            MegaDropDown.prototype = {
                activate: function () {
                    if ('ontouchstart' in document.documentElement) {
                        this.element.on('click.mega-dropdown', 'a.nav-btn', $.proxy(this, 'click'));
                    } else {
                        var events = {
                            'mouseenter.mega-dropdown': $.proxy(this, 'mouseenter'),
                            'mouseleave.mega-dropdown': $.proxy(this, 'mouseleave')
                        };

                        this.element.on(events, 'a.nav-btn', { show: true });
                        this.element.on(events, '.dropdown', { show: false });
                    }
                },
                deactivate: function () {
                    this.element.off('.mega-dropdown');
                    this.hide();
                },

                mouseenter: function (e) {
                    if (e.data.show) {
                        var method = this.activeItem || 'ontouchstart' in document.documentElement ? 'call' : 'start';
                        this.showTimer[method](e.currentTarget);
                    }

                    this.hideTimer.cancel();
                },
                mouseleave: function (e) {
                    this.showTimer.cancel();
                    this.hideTimer.start();
                },

                click: function (e) {
                    if (!this.activeItem || e.currentTarget !== this.activeItem[0]) {
                        e.preventDefault();
                        this.show(e.currentTarget);
                    }
                },
                hideOnBlur: function (e) {
                    if (!$(e.target).closest(this.element.add(this.dropdownContainer)).length) {
                        this.hide();
                        $(document).off('touchend', $.proxy(this, 'hideOnBlur'));
                    }
                },

                show: function (element) {
                    var animate = true;

                    if (this.activeItem) {
                        if (element === this.activeItem[0]) {
                            return;
                        }

                        animate = false;
                        this.hide();
                    }

                    element = $(element);

                    this.activeItem = element;
                    this.dropdown = element.next('.dropdown');

                    this.dropdown.find('img').attr('src', element.attr('data-image'));

                    $('.caption').addClass('right');

                    if (animate) {
                        var hideOnBlur = $.proxy(this, 'hideOnBlur');

                        this.dropdown.stop().show().css('top', -377).animate({ top: this.element.closest('nav').outerHeight() }, 250, function () {
                            $(document).on('touchend', hideOnBlur);
                        });
                    } else {
                        this.dropdown.show().css('top', this.element.closest('nav').outerHeight());
                    }

                    this.element.addClass('active');
                    element.addClass('active inner-top-shadow');
                },
                hide: function () {
                    this.hideTimer.cancel();

                    if (!this.activeItem) {
                        return;
                    }

                    this.element.removeClass('active');
                    // this.activeItem.append(this.dropdown);
                    this.dropdown.hide();
                    if (this.activeItem.hasClass('meganav') == true) {
                        this.activeItem.removeClass('inner-top-shadow');
                    } else {
                        this.activeItem.removeClass('active inner-top-shadow');
                    }
                    $('.caption').removeClass('right');

                    delete this.activeItem;
                    delete this.dropdown;
                }
            };

            new MegaDropDown('header nav > ul');

            // dropdown links update images
            $('.dropdown ul li a').each(function () {
                var src = $(this).attr('data-image');
                $(this).on('ontouchstart' in document.documentElement ? 'click' : 'mouseenter', function (e) {
                    var img = $(this).closest('.dropdown').find('img');
                    if (img.attr('src') != src) {
                        e.preventDefault();
                        img.attr('src', src);
                    }
                });
            });

            // set up pager images
            $('.pager a').each(function () {
                var src = $(this).attr('data-image');
                $(this).css('background-image', 'url(' + src + ')');
            });

            $('#logo').prependTo($('header nav > ul:first-child').next().children('li'));
            $('header nav > ul:first-child > li:nth-child(3)').appendTo($('header nav > ul:last-child'));

            // hack to fix header layout issue when resizing window
            $('header nav > ul:first-child').css('display', 'none');
            setTimeout(function () {
                $('header nav > ul:first-child').css('display', 'inline-block');
            }, 1);

            $('nav ul li div').addClass('bg-purple inner-top-shadow dropdown experience');
            $('header nav').removeClass('bg-purple inner-top-shadow');

            // GALLERY
            $('.caption').addClass('caption-slide top right');
            // TEARDOWN toggle expanded caption
            $('.caption > h2, nav .plus, nav .minus').off();
            // pager to footer
            $('footer .wrapper').appendTo($('.gallery'));
        }

    });


    /* -----------------------
    JUMBO GALLERY
    ----------------------- */
    $('.gallery').each(function () {
        // options
        var gallery = new Gallery(this, {
            slideSelector: '.slide',
            pager: '.gallery .pager',
            speed: 400,
            timeout: 6000,
            pauseOnHover: '.caption',
            autoPlay: true,
            transition: 'fade'
        });

        function translate(element, x, y, duration, fn) {
            var coords = [typeof x == 'number' ? x + 'px' : x, typeof y == 'number' ? y + 'px' : y].join(', '),
		transform = 'translate3d(' + coords + ', 0)';

            if (duration) {
                element.css('-webkit-transition', '-webkit-transform ' + duration + 'ms');
                element.one('webkitTransitionEnd', fn);
                element.css('-webkit-transform', transform);
            } else {
                element.css({ '-webkit-transition': '', '-webkit-transform': transform });
            }
        }


        new PanGestureRecognizer(this, {
            direction: 'horizontal',

            panstart: function (point) {
                this.startIndex = gallery.currentIndex;
                this.dragging = true;
                gallery.stop();
            },
            panmove: function (point, translation) {
                var slides = gallery.slides,
			pageWidth = gallery.element.outerWidth(),
			currentIndex = this.startIndex + Math.round(-translation.x / pageWidth),
			direction = (translation.x < 0 ? 1 : -1),
			nextIndex = (this.startIndex + direction) % slides.length;

                if (nextIndex < 0) nextIndex = slides.length + nextIndex;

                gallery.setCurrentIndex(currentIndex);

                var currentSlide = slides.eq(this.startIndex),
			nextSlide = slides.eq(nextIndex);

                var visibleSlides = this.visibleSlides = currentSlide.add(nextSlide);

                slides.hide();
                visibleSlides.show();

                currentSlide.css('left', 0);
                nextSlide.css('left', (direction * 100) + '%');

                translate(visibleSlides, translation.x, 0);
                translate(slides.not(visibleSlides), 0, 0);
            },
            panend: function (point, translation, velocity) {
                var width = gallery.element.outerWidth(),
			index = Math.round(-translation.x / width);

                if (Math.abs(velocity.x) >= 300) {
                    if (velocity.x < 0) {
                        index = Math.ceil(-translation.x / width);
                    } else {
                        index = Math.floor(-translation.x / width);
                    }

                    gallery.setCurrentIndex(this.startIndex + index);
                }

                this.dragging = false;

                translate(this.visibleSlides, -index * width, 0, 250, $.proxy(function () {
                    if (this.dragging) return;

                    translate(this.visibleSlides, 0, 0);
                    gallery.slides.css('left', 0).hide().eq(gallery.currentIndex).show();
                    gallery.start();
                }, this));
            },
            pancancel: function (point, translation) {

            }
        });

        // pager
        $('.pager a:eq(0)').addClass('active');
        $('.pager a').on('click', function (e) {
            e.preventDefault();
            gallery.show($(this).index());
            gallery.stop(this);
        });

        // caption
        $('.slide-00').next().show();
        $('.gallery').bind('beforeChange.gallery', function (e, index) {
            $('.caption').hide().eq(index).show();
            $('.pager a').removeClass('active').eq(index).addClass('active');
        });

        setTimeout(function () {
            $('.caption').removeClass('right');
        }, 300);

    });


    // controls
    // $('.gallery').gallery('stop');
    // $('.gallery').gallery('start');
    // $('.gallery').gallery('next');
    // $('.gallery').gallery('prev');

    // header ribbon
    $('#best_deals').hover(function () {
        $(this).attr('src', '_images/ribbon_best-deals_active.png');
    }, function () {
        $(this).attr('src', '_images/ribbon_best-deals.png');
    });

    $('.caption a').hover(function () {
        $(this).css('color', '#D4A0E4');
        $(this).parent().css('background', 'rgba(0,0,0,0.6)');
    }, function () {
        $(this).css('color', '#ffffff');
        $(this).parent().css('background', 'rgba(0,0,0,0.4)');
    });

});