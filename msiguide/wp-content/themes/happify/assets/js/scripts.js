(function($) {

    "use strict";


    /*------------------------------------------
        = ALL ESSENTIAL FUNCTIONS
    -------------------------------------------*/

    // Function for toggle class for small menu
    function toggleClassForSmallNav() {
        var windowWidth = window.innerWidth;
        var mainNav = $("#slide-nav > ul");

        if (windowWidth <= 991) {
            mainNav.addClass("small-nav");
        } else {
            mainNav.removeClass("small-nav");
        }
    }

    toggleClassForSmallNav();


    // Function for small menu
    function smallNavFunctionality() {
        var windowWidth = window.innerWidth;
        var mainNav = $(".navigation-holder");
        var smallNav = $(".navigation-holder > .small-nav");
        var subMenu = smallNav.find(".sub-menu");
        var megamenu = smallNav.find(".mega-menu");
        var menuItemWidthSubMenu = smallNav.find(".menu-item-has-children > a");

        if (windowWidth <= 991) {
            subMenu.hide();
            megamenu.hide();
            menuItemWidthSubMenu.on("click", function(e) {
                var $this = $(this);
                $this.siblings().slideToggle();
                 e.preventDefault();
                e.stopImmediatePropagation();
            })
        } else if (windowWidth > 991) {
            mainNav.find(".sub-menu").show();
            mainNav.find(".mega-menu").show();
        }
    }

    smallNavFunctionality();

    /*------------------------------------------
    = MOBILE SIDE MENU FUNCTIONALITY
    -------------------------------------------*/
    function modalMobileMenu() {

        // jQuery element variables
        var $hamburgerMenuBtn,
        $slideNav,
        $closeBtn,
        $main;

        // focus management variables
        var $focusableInNav,
        $firstFocusableElement,
        $lastFocusableElement;

        $(document).ready(function() {
            $hamburgerMenuBtn = $("#hamburger-menu"),
            $slideNav = $("#slide-nav"),
            $closeBtn = $("#close"),
            $main = $("main"),
            $focusableInNav = $('#slide-nav button, #slide-nav [href], #slide-nav input, #slide-nav select, #slide-nav textarea, #slide-nav [tabindex]:not([tabindex="-1"])');
            if ($focusableInNav.length) {
                $firstFocusableElement = $focusableInNav.first();
                $lastFocusableElement = $focusableInNav.last();
            }
            addEventListeners();
        });

        function addEventListeners() {

            $hamburgerMenuBtn.on("click", function() {
                $slideNav.addClass("visible active");
                setTimeout(function() {
                    $firstFocusableElement.focus()
                }, 1);
                $main.attr("aria-hidden", "true");
                $hamburgerMenuBtn.attr("aria-hidden", "true");
            })

            $closeBtn.on("click", function(e) {
                if (e.type === "keyup" && e.key !== "Escape") {
                return;
            }

            $slideNav.removeClass("active");
            $main.removeAttr("aria-hidden");
            $hamburgerMenuBtn.removeAttr("aria-hidden");
            setTimeout(function() {
                $hamburgerMenuBtn.focus()
            }, 1);
            setTimeout(function() {
                $slideNav.removeClass("visible")
            }, 501);
            })


            $slideNav.on("keyup", function(e) {
                if (e.type === "keyup" && e.key !== "Escape") {
                    return;
                }

                $slideNav.removeClass("active");
                $main.removeAttr("aria-hidden");
                $hamburgerMenuBtn.removeAttr("aria-hidden");
                setTimeout(function() {
                    $hamburgerMenuBtn.focus()
                }, 1);
                setTimeout(function() {
                    $slideNav.removeClass("visible")
                }, 501);
            })

            $firstFocusableElement = $focusableInNav.first();
            $firstFocusableElement.on("keydown", function(e) {
                if (e.key === "Tab" && e.shiftKey) {
                    e.preventDefault();
                    $lastFocusableElement.focus()
                }
            })

            $lastFocusableElement = $focusableInNav.last();
            $lastFocusableElement.on("keydown", function(e) {
                if (e.key === "Tab" && !e.shiftKey) {
                    e.preventDefault();
                    $firstFocusableElement.focus();
                }
            })
        }
    }

    modalMobileMenu();



    function toggleId() {
        var windowWidth = window.innerWidth;

        if (windowWidth >= 991) {
            $('.slide-content').removeAttr('id');
            $('.slide-content').attr('id', "navbar");

        } else {
            $('.slide-content').attr('id', "slide-nav");
        }
    }


    toggleId();


    /*------------------------------------------
    = STICKY NAVIGATION
    -------------------------------------------*/
    function stickyNavi() {
        var amountScrolled = 200;
        var amountScrolled2 = 300;
        var navi = $(".navigation.active-sticky");
        var body = $("body");
        
        if ($(window).scrollTop() > amountScrolled ) {

            navi.addClass("sticky-on");

            body.css({
                "padding-top": navi.innerHeight() + 'px'
            })

            if ($(window).scrollTop() > amountScrolled2 ) {
                navi.addClass("sticky-header");
            } else {
                navi.removeClass("sticky-header");
            }
        } else {
            navi.removeClass("sticky-on");
            body.css({
                "padding-top": 0
            })
        }
    }


    /*------------------------------------------
        = HEADER SEARCH AREA
    -------------------------------------------*/
    if ($(".site-search-area").length) {
        var serachFormBox = $(".site-search-area .site-search-form");
        var openSeachBtn = $(".site-search-area .open-btn");

        $(document.body).append(serachFormBox);
        serachFormBox.hide();

        openSeachBtn.on("click", function(e) {
            serachFormBox.fadeIn();
            return false;
        });

        serachFormBox.on("click", function() {
            serachFormBox.fadeOut();
            return false;
        }).find(".form").on("click", function(e) {
            e.stopPropagation();
        })
    }


    // Parallax background
    function bgParallax() {
        if ($(".parallax").length) {
            $(".parallax").each(function() {
                var height = $(this).position().top;
                var resize     = height - $(window).scrollTop();
                var doParallax = -(resize/5);
                var positionValue   = doParallax + "px";
                var img = $(this).data("bg-image");

                $(this).css({
                    backgroundImage: "url(" + img + ")",
                    backgroundPosition: "50%" + positionValue,
                    backgroundSize: "cover"
                });
            });
        }
    }


   // SLIDER BACKGROUND IMAGE
    var sliderBgSetting = $(".slide-bg-image");
    sliderBgSetting.each(function(indx){
        if ($(this).attr("data-background")){
            $(this).css("background-image", "url(" + $(this).data("background") + ")");
        }
    });




    /*------------------------------------------
        = HIDE PRELOADER
    -------------------------------------------*/
    function preloader() {
        if($('.preloader').length) {
            $('.preloader').delay(100).fadeOut(500, function() {

            });
        }
    }



    /*------------------------------------------
        = ACTIVE POPUP IMAGE
    -------------------------------------------*/
    if ($(".fancybox").length) {
        $(".fancybox").fancybox({
            openEffect  : "elastic",
            closeEffect : "elastic",
            wrapCSS     : "project-fancybox-title-style"
        });
    }


    /*------------------------------------------
        = POPUP VIDEO
    -------------------------------------------*/
    if ($(".video-btn").length) {
        $(".video-btn").on("click", function(){
            $.fancybox({
                href: this.href,
                type: $(this).data("type"),
                'title'         : this.title,
                helpers     : {
                    title : { type : 'inside' },
                    media : {}
                },

                beforeShow : function(){
                    $(".fancybox-wrap").addClass("gallery-fancybox");
                }
            });
            return false
        });
    }


    /*------------------------------------------
        = ACTIVE GALLERY POPUP IMAGE
    -------------------------------------------*/
    if ($(".popup-gallery").length) {
        $('.popup-gallery').magnificPopup({
            delegate: 'a',
            type: 'image',

            gallery: {
              enabled: true
            },

            zoom: {
                enabled: true,

                duration: 300,
                easing: 'ease-in-out',
                opener: function(openerElement) {
                    return openerElement.is('img') ? openerElement : openerElement.find('img');
                }
            }
        });
    }

 

    /*-----------------------------------------
        = BACK TO TOP BTN SETTING
    -------------------------------------------*/
    $("body").append("<a href='#' class='back-to-top'><i class='ti-arrow-up'></i></a>");

    function toggleBackToTopBtn() {
        var amountScrolled = 1000;
        if ($(window).scrollTop() > amountScrolled) {
            $("a.back-to-top").fadeIn("slow");
        } else {
            $("a.back-to-top").fadeOut("slow");
        }
    }

    $(".back-to-top").on("click", function() {
        $("html,body").animate({
            scrollTop: 0
        }, 700);
        return false;
    })



    /*==========================================================================
        WHEN DOCUMENT LOADING
    ==========================================================================*/
        $(window).on('load', function() {

            preloader();

            toggleClassForSmallNav();
        
            smallNavFunctionality();

            toggleId();

            modalMobileMenu();

        });



    /*==========================================================================
        WHEN WINDOW SCROLL
    ==========================================================================*/
    $(window).on("scroll", function() {

         toggleBackToTopBtn();

         stickyNavi();
    });


    /*==========================================================================
        WHEN WINDOW RESIZE
    ==========================================================================*/
    $(window).on("resize", function() {
        
        toggleClassForSmallNav();
        
        smallNavFunctionality();

        toggleId();

        modalMobileMenu();

        clearTimeout($.data(this, 'resizeTimer'));

        $.data(this, 'resizeTimer', setTimeout(function() {
            toggleClassForSmallNav();
            
            smallNavFunctionality();

            toggleId();

            modalMobileMenu();
        }, 200));

    });

})(window.jQuery);