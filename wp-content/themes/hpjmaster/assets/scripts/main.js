/* ========================================================================
 * DOM-based Routing
 * Based on http://goo.gl/EUTi53 by Paul Irish
 *
 * Only fires on body classes that match. If a body class contains a dash,
 * replace the dash with an underscore when adding it to the object below.
 *
 * .noConflict()
 * The routing is enclosed within an anonymous function so that you can
 * always reference jQuery with $, even when in .noConflict() mode.
 * ======================================================================== */

var custom_7_event = {};

(function($) {

    // Use this variable to set up the common and page specific functions. If you
    // rename this variable, you will also need to rename the namespace below.
    var Sage = {
        // All pages
        'common': {
            init: function() {
                $('.match-height').matchHeight();
                // JavaScript to be fired on all pages
            },
            finalize: function() {
                $('.js-submitting-select').on('change', function() {
                    $(this).closest('.js-form').submit();
                });
                // JavaScript to be fired on all pages, after page specific JS is fired

                $('.search-icon').on('click', function() {
                    var that = $(this);
                    setTimeout(function() {
                        if (that.hasClass('open')) {
                            $('.search-field').focus();
                        }
                    }, 100);
                });
            }
        },
        // Home page
        'home': {
            init: function() {
                // JavaScript to be fired on the home page
            },
            finalize: function() {
                // JavaScript to be fired on the home page, after the init JS
            }
        },
        // About us page, note the change from about-us to about_us.
        'about_us': {
            init: function() {
                // JavaScript to be fired on the about us page
            }
        }
    };

    // The routing fires all common scripts, followed by the page specific scripts.
    // Add additional events for more control over timing e.g. a finalize event
    var UTIL = {
        fire: function(func, funcname, args) {
            var fire;
            var namespace = Sage;
            funcname = (funcname === undefined) ? 'init' : funcname;
            fire = func !== '';
            fire = fire && namespace[func];
            fire = fire && typeof namespace[func][funcname] === 'function';

            if (fire) {
                namespace[func][funcname](args);
            }
        },
        loadEvents: function() {
            // Fire common init JS
            UTIL.fire('common');

            // Fire page-specific init JS, and then finalize JS
            $.each(document.body.className.replace(/-/g, '_').split(/\s+/), function(i, classnm) {
                UTIL.fire(classnm);
                UTIL.fire(classnm, 'finalize');
            });

            // Fire common finalize JS
            UTIL.fire('common', 'finalize');
        }
    };

    // Load Events
    $(document).ready(UTIL.loadEvents);

    // shared var
    var window_scrolltop = $(window).scrollTop();
    var window_width = $(window).width();
    var window_height = $(window).height();
    var nav_height = $('header').height();


    $(document).ready(function() {

        var document_height = $(document).height();

        // Update shared var
        $(window).scroll(function() {
            window_scrolltop = $(window).scrollTop();
            nav_height = $('header').height();
        })
        $(window).resize(function() {
            window_width = $(window).width();
            window_height = $(window).height();
            document_height = $(document).height();
            nav_height = $('header').height();
        })

        // Sticky menu
        function set_menustickyclass() {
            var $target = $('.js-header');
            $(window).scroll(function() {
                if (window_scrolltop) {
                    $($target).addClass('sticky');
                } else {
                    $($target).removeClass('sticky');
                }
            })
        }

        function nav_scrolltocontent() {

            $(window).on('hashchange load', function(e) {

                $target = $(window.location.hash + '_target');

                var animationDuration = 2000;
                if (window_width < 767) {
                    animationDuration = 1;
                }

                if ($($target).size()) {
                    $('html, body').animate({
                        scrollTop: $($target).offset().top - nav_height
                    }, animationDuration)
                    window.location.hash = '';
                }

            });

        }

        function set_headermenubtn() {
            var $targetbtn = $('.page-header .btn.btn-inverse');

            if (!$($targetbtn).size()) {
                $('header.navbar').addClass('show-header-btn');
                return;
            }

            var targetbtnOffsetTop = $($targetbtn).offset().top;
            var headerHeight = $('header.navbar').height();

            $(window).scroll(function() {
                if ((window_scrolltop) >= targetbtnOffsetTop) {
                    $('header.navbar').addClass('show-header-btn');
                } else {
                    $('header.navbar').removeClass('show-header-btn');
                }
            });
        }


        function set_videoloadingicon($video, $loader) {
            if (!$($video).size()) return;
            $($video).on('canplay', function(event) {
                $($loader).addClass('is-hidden');
            })
        }


        function homepage_product_features(e) {

            $('.container-feature').click(function(e) {
                if ($(e.target).hasClass('container-feature-content-close-btn')) return;
                $('.container-feature').removeClass('active');
                $(this).addClass('active');
            });

            $('.container-feature-content-close-btn').click(function(e) {
                e.preventDefault();
                $('.container-feature').removeClass('active');
            })


            function fixTitleAlign() {
                if (!$('.container-features').size()) return;
                $(window).bind('load resize', function() {
                    var offsetLeft = $('.container-features h2').offset().left - 42;
                    var $target = $('.container-feature h3');
                    if (window_width > 975) {
                        $target = $('.container-feature h3:even');
                        $('.container-feature h3:odd').css({ left: 0 });
                    }
                    $($target).css({ left: offsetLeft });
                })
            }
            fixTitleAlign();

        }


        function hpjcc() {

            var $email_input = $('#email');

            $('#webToCcWrapper form .btn-submit').click(function(e) {
                e.preventDefault();
                $('#webToCcWrapper form').submit();
            });

            $('#webToCcWrapper form').submit(function(e) {
                var email = $($email_input).val();
                if (!validateEmail(email)) {
                    $('.webToCcErrorRequired').slideDown();
                    e.preventDefault();
                }
            })

            $($email_input).focus(function() {
                $('.webToCcErrorRequired').slideUp();
            })

            function validateEmail(email) {
                var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                return re.test(email);
            }

        }

        function show_linktotop() {

            $(window).bind('load scroll', function() {

                if (window_scrolltop) {
                    $('#btn-scroll-top-page').removeClass('is-hidden');
                } else {
                    $('#btn-scroll-top-page').addClass('is-hidden');
                }

            })

            $('#btn-scroll-top-page').click(function(e) {
                e.preventDefault();
                $('html, body').animate({
                    scrollTop: 0
                }, 2000)
            })
        }


        function mobile_menu() {
            if (window_width < 830) {
                $('#menu-product-menu a').click(function(e) {
                    $('[data-toggle="collapse"]').trigger('click');
                })
            }
        }

        function prepareTableMobile() {
            var tables = $('.responsive-table');

            tables.each(function() {
                var headertext = [],
                    row,
                    headers = $(this).find("th"),
                    tablebody = $(this).find('tbody'),
                    tablecols = tablebody.find("tr");

                headers.each(function() {
                    headertext.push($(this).text().replace(/\r?\n|\r/, ""));
                });

                tablecols.each(function() {
                    var tablerows = $(this).find("td");

                    tablerows.each(function(i) {
                        $(this).attr('data-th', headertext[i])
                    });
                });
            });

        }


        // Carousel event. Set active class on btn
        $('#applicationsCarousel').on('slide.bs.carousel', function(e) {
            var active_index = $(e.relatedTarget).index();
            $('.container-use-cases .nav li').removeClass('active');
            $('.container-use-cases .nav li').eq(active_index).addClass('active');

            // Si on est en mobile, on scroll au content
            if (window_width < 975) {
                $('html, body').animate({
                    scrollTop: $('#applicationsCarousel').offset().top - 70
                }, 1000);
            }

        })

        // Contact 7 submit message confirmation
        custom_7_event.contact_7_sent_ok = function() {
                $('.page-template-template-productformsubscription')
                    .find('.form-container').slideUp()
                    .end()
                    .find('.form-container-confirmation-message').slideDown();
            }
        
		//Info block download page
        function infoBlockDownloads() {
            if (window_width < 769) {
                $('.download-list').find('.btn-info-tip').attr('data-placement', 'top');
            }
        }
		
        //Scroll to Element
        function scrollTo() {
            $('.scrollTo[href^="#"]').click(function(event) {
                var target = $($(this).attr('href'));
                if (target.length) {
                    event.preventDefault();
                    $('html, body').animate({
                        scrollTop: target.offset().top - 90
                    }, 700);
                }
            });
        }

        // MOBILE MENU
        function toggleMobileMenu() {
            var toggleMobileBtn = $('.js-mobile-menu-toggle');
            var body = $('body');
            var header = $('.js-header');

            toggleMobileBtn.click(function(e) {
                e.preventDefault();
                body.toggleClass('no-scroll');
                header.toggleClass('active');
            });
        }

        function toggleMobileSubMenu() {
            var subMenuTitle = $('.mobile-nav .navbar-nav > li.menu-item-has-children');

            subMenuTitle.click(function(e) {
                if (!$(this).hasClass('sub-menu-active')) {
                    e.preventDefault();
                    $(this).toggleClass('sub-menu-active');
                }
            });
        }

        // function mobileMenuClose() {
        //   var mobileMenuLink = $('.js-mobile-menu .mobile-menu .blu-nav li a');
        //   var mobileMenu = $('.js-mobile-menu');

        //   mobileMenuLink.click(function(e){
        //     mobileMenu.toggleClass('active');
        //     console.log('mobileMenuLink');
        //   });
        // }


        set_menustickyclass(); // Header ::: Add or Remove sticky class
        set_headermenubtn(); // Si bouton dans header-page, affiche celui dans le header quand celui-ci n'est plus visible ::: ex page accueil produit
        nav_scrolltocontent(); // Scroll to content lors du clique sur les liens
        set_videoloadingicon($('.producthomepage.bg-video video'), $('.video-loading-container')); // Loading progress du video
        homepage_product_features();
        show_linktotop();
        hpjcc();
        mobile_menu();
        prepareTableMobile();
        infoBlockDownloads();
        scrollTo();
        toggleMobileMenu();
        toggleMobileSubMenu();
    })


})(jQuery); // Fully reference jQuery after this point.