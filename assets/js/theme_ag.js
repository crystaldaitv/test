(function ($) {
    $(document).ready(function () {
        //menu
        var $menuHeader = $('#page-header');
        var width_menu = 50;

        if ($menuHeader.find('.menu-header').hasClass('curtain_menu')) {
            width_menu = 100;
        }

        $menuHeader.find('.show-menu-mobile').toggle(
            function () {
                // $(this).addClass('menu-opened').text('close');
                $(this).addClass('menu-opened');
                $('.menu-header').show();
                $menuHeader.find('nav.ag-mobile-menu').removeClass('ag-hide').css({
                    'width': 0,
                    'opacity': 0,
                });
                $menuHeader.find('nav.ag-mobile-menu ul').removeClass('ag-hide');
                $menuHeader.find('nav.ag-mobile-menu').animate({
                    width: width_menu + '%',
                    opacity: '1'
                }, 200, "linear");
            },
            function () {
                // $(this).text('reorder');
                $menuHeader.find('nav.ag-mobile-menu').css({'width': width_menu + '%', 'opacity': 1});
                $menuHeader.find('nav.ag-mobile-menu').animate({
                    width: '0',
                    opacity: '0'
                }, 200, "linear", function () {
                    $(this).addClass('ag-hide');
                    $(this).find('ul').removeClass('ag-hide');
                    $('.show-menu-mobile').removeClass('menu-opened');
                });
                $('.menu-header').hide();
            }
        );

        $('#page-header nav.ag-header-menu').find('.sub-menu').each(function () {
            $(this).siblings('a').addClass('arrow_down');
        });

        // $('#page-header:not(.ag-menu-centerA)').find('.logo_container').css('width', $menuHeader.find('.logo_container').find('img').width());

        $menuHeader.find('.ag-header-menu').find('li').hover(function () {
            if ($(this).contents('.sub-menu').length > 0) {
                $(this).contents('.sub-menu').css('top', $(this).outerHeight());
            }
        });

        $menuHeader.find('i.ag-has-children').click(
            function (e) {
                e.preventDefault();
                var sub_menu = $(this).parents('a').next();
                if (sub_menu.hasClass('ag-open')) {
                    $(this).removeClass('active');
                    sub_menu.removeClass('ag-open');
                } else {
                    $(this).addClass('active');
                    sub_menu.addClass('ag-open');
                }
                return false;
            }
        );

        var docWidth = $(document).width();
        var bodyWidth = $('#page-container').outerWidth()

        $menuHeader.find('.ag-header-menu').find('.sub-menu li').each(function () {
            $(this).hover(function () {
                var contentLength = $(this).contents('.sub-menu').length;
                if (contentLength > 0) {
                    var position = $(this).offset();
                    var width = $(this).parents('.sub-menu').outerWidth();
                    var borderTop = $(this).parents('.sub-menu').css("border-top-width");

                    if ($(this).contents('.left_sub').length > 0 || (docWidth + bodyWidth) / 2 < position.left + width + contentLength * width) {
                        $(this).find('.sub-menu').css({
                            'top': '-' + borderTop,
                            'right': $(this).outerWidth() - 2 + 'px'
                        }).addClass('left_sub');
                    } else {
                        $(this).find('.sub-menu').css({
                            'top': '-' + borderTop,
                            'left': $(this).outerWidth() - 1 + 'px'
                        });
                    }
                }
            });
        });

        //fixed_header
        var $layout_home = document.getElementById("wp_body_layout_home");

        if (!$('#wp_body_layout_home').hasClass('ag_theme_top_content')) {
            $('#main-content').css('margin-top', -1 * $('#page-header').outerHeight());
        }

        if ($('#wp_body_layout_home.ag_theme_fixed_navigation').hasClass('ag_theme_top_content')) {
            $('#main-content').css('margin-top', $('#page-header').outerHeight());
        }

        if ($layout_home.classList.contains('ag_theme_sticky_header')) {
            var $page_header = $('#page-header');
            var height_header = $page_header.outerHeight();
            agt_sticky_header_scroll(height_header);

            //sticky header option
            window.onscroll = function () {
                agt_sticky_header_scroll(height_header);
                return false;
            };
        }

        function agt_sticky_header_scroll(height_header) {
            var top = $('#wpadminbar').outerHeight();
            if (window.pageYOffset > height_header) {
                if (!$page_header.hasClass('sticky_header')) {
                    $page_header.find('.sticky_logo').removeClass('ag-hide');
                    $page_header.find('.logo_global').addClass('ag-hide');
                    $page_header.addClass('sticky_header');
                    var height = $page_header.outerHeight();
                    $page_header.addClass('sticky_header').css('top', '-' + height + 'px').animate({'top': '+=' + (top + height) + 'px', 'opacity': '1'}, 1000);
                }
            } else {
                $page_header.removeClass('sticky_header').css('top', 0).finish();
                $page_header.find('.sticky_logo').addClass('ag-hide');
                $page_header.find('.logo_global').removeClass('ag-hide');
            }
        }
    });

    //footer menu
    if ($('#footer-nav').find('.ag-footer-menu').length > 0) {
        var $footer_menu = $('#footer-nav').find('.ag-footer-menu');
        $footer_menu.find('li.menu-item-has-children').hover(function () {
            var sub_menu = $(this).parent('.sub-menu');
            if (sub_menu.length > 0) {
                if (sub_menu.hasClass('left')) {
                    $(this).find('.sub-menu:eq(0)').addClass('right');
                } else {
                    $(this).find('.sub-menu:eq(0)').addClass('left');
                }
            }
        });
    }
    $('#footer-nav').find('i.ag-has-children').click(
        function (e) {
            e.preventDefault();
            var sub_menu = $(this).parents('a').next();
            if (sub_menu.hasClass('ag-open')) {
                $(this).removeClass('active');
                sub_menu.removeClass('ag-open');
            } else {
                $(this).addClass('active');
                sub_menu.addClass('ag-open');
            }
            return false;
        }
    );
})(jQuery);
