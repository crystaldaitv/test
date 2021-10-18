(function ($) {
    $(document).ready(function () {
        var data_font, AG_THEME_NAME_OPTION, protocol, theme_option;
        if (typeof mainCustomizerData === 'undefined') {
            AG_THEME_NAME_OPTION = 'AGtheme';
            data_font = {};
            protocol = location.protocol;
        } else {
            AG_THEME_NAME_OPTION = mainCustomizerData.AG_THEME_NAME_OPTION;
            data_font = mainCustomizerData.data_font;
            protocol = mainCustomizerData.protocol;
        }

        theme_option = {};
        theme_option.fontGoogle = {};

        var not_render_style = ['menus_logo', 'body_font_google', 'header_font_google', 'fontGoogle', 'top_content', 'fixed_navigation'];

        var width_sticky_header = function () {
            var content = '';
            var container_width = typeof mainCustomizerData.theme_option.container_width !== 'undefined' ? mainCustomizerData.theme_option.container_width : 90;
            // if (mainCustomizerData.theme_option.boxed_layout === false) {
            // } else {
                // var left = typeof mainCustomizerData.theme_option.margin_left_content !== 'undefined' ? mainCustomizerData.theme_option.margin_left_content : 5;
                // var right = typeof mainCustomizerData.theme_option.margin_right_content !== 'undefined' ? mainCustomizerData.theme_option.margin_right_content : 5;
                // var margin = parseInt(left) + parseInt(right);
                // content += 'body.ag_boxed_layout #page-header.sticky_header {width: ' +  container_width + '%;} ';
                // content += 'body.ag_theme_fixed_navigation.ag_boxed_layout #page-header {width: ' +  container_width + '%;} ';
                // content += '#wp_body_layout_home.ag_boxed_layout #page-container {width: ' + container_width + '%;} ';
                // content += '#wp_body_layout_home.ag_boxed_layout #page-container {margin-left: ' + left + '%;} ';
                // content += '#wp_body_layout_home.ag_boxed_layout #page-container {margin-right: ' + right + '%;} ';
            // }

            return content;
        };

        var render_style = function (theme_option) {
            var $style_css = $('#wp-ag-custom-css');
            var $style_css_header = $('#wp-ag-font-header');
            var $style_css_body = $('#wp-ag-font-body');
            var $ag_theme_scroll = $('#ag_theme_scroll');
            var content = '';
            if (theme_option !== null) {
                $.each(theme_option, function (u, v) {
                    if (u === 'header_font_google') {
                        if (_.size(theme_option.fontGoogle) > 0) {
                            var font = ' @import url(' + protocol + '://fonts.googleapis.com/css?family=';

                            if (typeof theme_option.fontGoogle.header !== 'undefined') {
                                font += theme_option.fontGoogle.header + '|';
                            }

                            font = font.replace(/(\s+)?.$/, '') + '); ';

                            font += v;
                            if (font !== $style_css_header.html()) {
                                $style_css_header.html(font);
                            }
                        }
                    } else if (u === 'body_font_google') {
                        if (_.size(theme_option.fontGoogle) > 0) {
                            var font = ' @import url(' + protocol + '://fonts.googleapis.com/css?family=';

                            if (typeof theme_option.fontGoogle.body !== 'undefined') {
                                font += theme_option.fontGoogle.body + '|';
                            }

                            font = font.replace(/(\s+)?.$/, '') + '); ';

                            font += v;
                            if (font !== $style_css_body.html()) {
                                $style_css_body.html(font);
                            }
                        }
                    } else if ($.inArray(u, not_render_style) === -1) {
                        content += v;
                    }
                });
                content += width_sticky_header();
                $style_css.html(content);
                localStorage.setItem('theme_option', JSON.stringify(theme_option));
            }
        };

        if (typeof localStorage.getItem('theme_option') !== 'undefined' && localStorage.getItem('theme_option') !== null) {
            try {
                var theme_option_local = jQuery.parseJSON(localStorage.getItem('theme_option'));
                if (theme_option_local !== {}) {
                    render_style(theme_option_local);
                }
            } catch (err) {

            }
        }

        function ag_set_font_styles(value, important_tag) {
            var font_styles = value.split('|'),
                style = '';
            if ($.inArray('Bold', font_styles) >= 0) {
                style += "font-weight: bold " + important_tag + ";";
            } else {
                style += "font-weight: initial " + important_tag + ";";
            }

            if ($.inArray('Italic', font_styles) >= 0) {
                style += "font-style: italic " + important_tag + ";";
            } else {
                style += "font-style: initial " + important_tag + ";";
            }

            if ($.inArray('Underline', font_styles) >= 0) {
                style += "text-decoration: underline " + important_tag + ";";
            } else {
                style += "text-decoration: initial " + important_tag + ";";
            }

            if ($.inArray('Uppercase', font_styles) >= 0) {
                style += "text-transform: uppercase " + important_tag + ";";
            } else {
                style += "text-transform: initial " + important_tag + ";";
            }

            return style;
        }

        /*set Cookie*/
        // agThemeGetCookie('ag_theme_customize_option', null, 'max_width_logo', max_width_logo);
        function agThemeCustomizeGetCookie(cname, value, reset) {
            if (typeof cname !== 'undefined') {
                var ca = document.cookie.split(';');
                var checkExistCookie = 1;
                for(var i=0; i<ca.length; i++) {
                    var c = ca[i].split('=');

                    if (c[0].charAt(0)===' ') {
                        c[0] = c[0].substring(1);
                    }

                    if (typeof c[0] !== 'undefined' && c[0] === cname) {
                        if (reset) {
                            document.cookie = cname + '=' + value;
                        }
                        if (value === '') {
                            return c[1];
                        }
                        checkExistCookie = 0;
                    } else {
                        checkExistCookie = checkExistCookie * 2;
                    }
                }
                if (checkExistCookie !== 0) {
                    document.cookie = cname + '=' + value;
                    agThemeCustomizeGetCookie(cname, value, reset);
                }
            }
            return '';
        }

        // change option customize to style
        wp.customize(AG_THEME_NAME_OPTION + '[img_upload]', function (value) {
            value.bind(function (to) {
                if (to === '') {
                    to = mainCustomizerData.link_logo_default;
                }
                var $image = $('#page-header').find('#logo');
                $image.find('img').remove();
                var logo = '<img src="' + to + '" alt="" data-height-percentage=""/>';
                $image.append(logo);

                var logo_width = mainCustomizerData.theme_option.logo_width;
                var image = new Image();
                image.src = to;
                image.onload = function() {
                    $('#page-header #logo img').css({'width': logo_width, 'height': image.naturalHeight * logo_width / image.naturalWidth});
                    render_style(null);
                };

                if ($('#sticky_logo.no_img').length > 0) {
                    var $sticky_image = $('#page-header').find('#sticky_logo');
                    $sticky_image.find('img').remove();
                    var sticky_logo = '<img src="' + to + '" alt="" data-height-percentage=""/>';
                    $sticky_image.append(sticky_logo);

                    logo_width = mainCustomizerData.theme_option.logo_width;
                    var sticky_image = new Image();
                    sticky_image.src = to;
                    sticky_image.onload = function() {
                        $('#page-header #sticky_logo img').css({'width': logo_width, 'height': sticky_image.naturalHeight * logo_width / sticky_image.naturalWidth});
                        render_style(null);
                    };
                }
            });
        });

        wp.customize(AG_THEME_NAME_OPTION + '[background_color]', function (value) {
            value.bind(function (to) {
                var content = '';
                content += '#wp_body_layout_home {background-color: ' + to + ';} ';
                theme_option.background_color = content;
                render_style(theme_option);
            });
        });

        wp.customize('background_image', function (value) {
            value.bind(function (to) {
                var content = '';
                content += '#wp_body_layout_home {background-image: url(' + to + ');} ';
                theme_option.background_image = content;
                render_style(theme_option);
            });
        });

        wp.customize(AG_THEME_NAME_OPTION + '[container_width]', function (value) {
            value.bind(function (to) {
                mainCustomizerData.theme_option.container_width = to;
                var content = '';
                content += '@media (min-width: 768px) { body.ag_boxed_layout #page-header.sticky_header {width: ' + to + '%;} ';
                content += 'body.ag_theme_fixed_navigation.ag_boxed_layout #page-header {width: ' + to + '%;} ';
                content += '#wp_body_layout_home.ag_boxed_layout #page-container {width: ' + to + '%;} } ';

                theme_option.container_width = content;
                /*add class when width < 100*/
                if (to !== 100 || to !== '100') {
                    $('body#wp_body_layout_home').addClass('ag_boxed_layout');
                } else {
                    $('body#wp_body_layout_home').removeClass('ag_boxed_layout');
                }
                render_style(theme_option);
            });
        });

        wp.customize(AG_THEME_NAME_OPTION + '[content_width]', function (value) {
            value.bind(function (to) {
                mainCustomizerData.theme_option.content_width = to;
                var content = '';
                content += 'body .wrapper_content {width: ' + to + '%;}';
                theme_option.content_width = content;
                render_style(theme_option);
            });
        });

        wp.customize(AG_THEME_NAME_OPTION + '[max_content_width]', function (value) {
            value.bind(function (to) {
                mainCustomizerData.theme_option.max_content_width = to;
                var content = '';
                content += 'body .wrapper_content {max-width: ' + to + 'px;} ';
                theme_option.max_content_width = content;
                render_style(theme_option);
            });
        });

        wp.customize(AG_THEME_NAME_OPTION + '[load_sidebar]', function (value) {
            value.bind(function (to) {
                wp.customize.preview.send('refresh');
            });
        });

        wp.customize(AG_THEME_NAME_OPTION + '[sidebar_position]', function (value) {
            value.bind(function (to) {
                wp.customize.preview.send('refresh');
            });
        });

        wp.customize(AG_THEME_NAME_OPTION + '[select_layout_style]', function (value) {
            value.bind(function (to) {
                mainCustomizerData.theme_option.select_layout_style = to;
                if (to !== 'Custom') {
                    $('#wp_body_layout_home').addClass('ag_layout_style');
                } else {
                    $('#wp_body_layout_home').removeClass('ag_layout_style');
                }
                render_customize(true);
            });
        });

        wp.customize(AG_THEME_NAME_OPTION + '[body_font_size]', function (value) {
            value.bind(function (to) {
                $('head #body_font_size').html(),
                    custom_style = "@media only screen and (min-width: 1200px ) {html {font-size:" + to + "px !important; }}",
                    $('head #body_font_size').html(custom_style);
                render_style(null);
            });
        });
        wp.customize(AG_THEME_NAME_OPTION + '[phone_body_font_size]', function (value) {
            value.bind(function (to) {
                $('head #phone_body_font_size').html(),
                    custom_style = "@media only screen and (max-width: 720px ) {html {font-size:"
                        + to + "px !important; }}",
                    $('head #phone_body_font_size').html(custom_style);
                render_style(null);
            });
        });
        wp.customize(AG_THEME_NAME_OPTION + '[tablet_body_font_size]', function (value) {
            value.bind(function (to) {
                $('head #tablet_body_font_size').html(),
                    custom_style = "@media only screen and (min-width: 720px) and (max-width: 1200px) {html {font-size:"
                        + to + "px !important; }}",
                    $('head #tablet_body_font_size').html(custom_style);
                render_style(null);
            });
        });

        wp.customize(AG_THEME_NAME_OPTION + '[body_font_height]', function (value) {
            value.bind(function (to) {
                var content = '';
                content += '#page-container #main-content{line-height: ' + to + ';} ';
                theme_option.body_font_height = content;
                render_style(theme_option);
            });
        });

        wp.customize(AG_THEME_NAME_OPTION + '[body_header_size]', function (value) {
            value.bind(function (to) {
                var content = '';
                content += 'h1 {font-size: ' + to + 'rem;} ';
                content += 'h2 {font-size: ' + to * 0.9 + 'rem;} ';
                content += 'h3 {font-size: ' + to * 0.77 + 'rem;} ';
                content += 'h4 {font-size: ' + to * 0.64 + 'rem;} ';
                content += 'h5 {font-size: ' + to * 0.5 + 'rem;} ';
                content += 'h6 {font-size: ' + to * 0.36 + 'rem;} ';
                theme_option.body_header_size = content;
                render_style(theme_option);
            });
        });

        wp.customize(AG_THEME_NAME_OPTION + '[body_header_spacing]', function (value) {
            value.bind(function (to) {
                theme_option.body_header_spacing = 'h1, h2, h3, h4, h5, h6 {letter-spacing: ' + to + 'px;} ';
                render_style(theme_option);
            });
        });

        wp.customize(AG_THEME_NAME_OPTION + '[body_header_height]', function (value) {
            value.bind(function (to) {
                theme_option.body_header_height = 'h1, h2, h3, h4, h5, h6 {line-height: ' + to + ';} ';
                render_style(theme_option);
            });
        });

        wp.customize(AG_THEME_NAME_OPTION + '[body_header_style]', function (value) {
            value.bind(function (to) {
                var styles = ag_set_font_styles(to, '');
                styles = '#main-content h1,#main-content h2,#main-content h3,#main-content h4,#main-content h5,#main-content h6{' + styles + '} ';
                theme_option.body_header_style = styles;
                render_style(theme_option);
            });
        });

        wp.customize(AG_THEME_NAME_OPTION + '[body_font_google]', function (value) {
            value.bind(function (to) {
                var type   = '';
                var font   = '' + to.replace(/ /g, "+");
                var styles = '';
                if (to === '') {
                    theme_option.fontGoogle.body = 'Open+Sans:300,300italic,regular,italic,600,600italic,700,700italic,800,800italic';
                    styles += '#wp_body_layout_home #content, #wp_body_layout_home #sidebar .widget-area, #wp_body_layout_home #page-footer #footer-bottom, #wp_body_layout_home #sidebar #searchform input{font-family: \'Open Sans\', sans-serif;} ';
                } else {
                    if (typeof data_font[to] !== 'undefined') {
                        font += ':' + data_font[to].styles;
                        type = data_font[to].type;
                        theme_option.fontGoogle.body = font;
                        styles += '#wp_body_layout_home #content, #wp_body_layout_home #sidebar .widget-area, #wp_body_layout_home #page-footer #footer-bottom, #wp_body_layout_home #sidebar #searchform input {font-family: \'' + to + '\', ' + type + ' !important;} ';
                    }
                }
                theme_option.body_font_google = styles;
                render_style(theme_option);
            });
        });

        wp.customize(AG_THEME_NAME_OPTION + '[link_color]', function (value) {
            value.bind(function (to) {
                var styles = '';
                styles = 'article p:not(.post-meta) a, .comment-edit-link, .pinglist a, .pagination a, :not(.entry-title) > a {color: ' + to + ';} ';
                theme_option.link_color = styles;
                render_style(theme_option);
            });
        });

        wp.customize(AG_THEME_NAME_OPTION + '[font_color]', function (value) {
            value.bind(function (to) {
                var styles = '';
                styles = '#page-container {color: ' + to + ';} ';
                theme_option.font_color = styles;
                render_style(theme_option);
            });
        });

        wp.customize(AG_THEME_NAME_OPTION + '[header_color]', function (value) {
            value.bind(function (to) {
                var styles = '';
                styles = '#main-content h1,#main-content h2,#main-content h3,#main-content h4,#main-content h5,#main-content h6, .entry-title a {color: ' + to + ';} ';
                theme_option.header_color = styles;
                render_style(theme_option);
            });
        });

        wp.customize(AG_THEME_NAME_OPTION + '[header_width]', function (value) {
            value.bind(function (to) {
                var page_header = $('#page-header');

                if(to === '') {
                    page_header.removeClass('header-fullwidth');
                } else {
                    page_header.addClass('header-fullwidth');
                }

                theme_option.header_width = '#page-header.header-fullwidth .wrapper_content {width: 100%;max-width: 100%}';
                render_style(theme_option);
            });
        });

        wp.customize(AG_THEME_NAME_OPTION + '[menus_logo]', function (value) {
            value.bind(function (to) {
                theme_option.menus_logo = to.replace('|', '');
                var $page_header = $('body#wp_body_layout_home').find('#page-header');
                var $menu_header = $('body#wp_body_layout_home').find('.menu-header');
                if (theme_option.menus_logo !== 'right') {
                    $menu_header.find('nav').eq(0).after($menu_header.find('.ag_theme_cart'));
                }
                var oldMenusLogos = $page_header.data('menu');
                var oldMenusLogoTemplate = $page_header.data('menu-template');
                $page_header.removeClass(oldMenusLogos);
                $page_header.removeClass(oldMenusLogoTemplate);

                $page_header.addClass('ag-menu-' + theme_option.menus_logo).data('menu', 'ag-menu-' + theme_option.menus_logo);
                $('#page-header:not(.ag-menu-centerA)').find('.logo_container').css('width', $page_header.find('.logo_container').find('img').width());
                // $('#page-header.ag-menu-centerA').find('.logo_container').css('width', '100%');
                render_style(theme_option);
            });
        });
        wp.customize(AG_THEME_NAME_OPTION + '[sticky_header]', function (value) {
            value.bind(function (to) {
                mainCustomizerData.theme_option.sticky_header = to;
                wp.customize.preview.send('refresh');
                render_style(theme_option);
            });
        });
        wp.customize(AG_THEME_NAME_OPTION + '[fixed_navigation]', function (value) {
            value.bind(function (to) {
                mainCustomizerData.theme_option.fixed_navigation = to;
                wp.customize.preview.send('refresh');
                render_style(theme_option);
            });
        });
        wp.customize(AG_THEME_NAME_OPTION + '[top_content]', function (value) {
            value.bind(function (to) {
                mainCustomizerData.theme_option.top_content = to;
                wp.customize.preview.send('refresh');
                render_style(theme_option);
            });
        });
        wp.customize(AG_THEME_NAME_OPTION + '[sticky_image_upload]', function (value) {
            value.bind(function (to) {
                if (to === '') {
                    to = mainCustomizerData.link_logo_default;
                    $('#sticky_logo').addClass('no_img');
                } else {
                    $('#sticky_logo').removeClass('no_img');
                }
                var $image = $('#page-header').find('#sticky_logo');
                $image.find('img').remove();
                var logo = '<img src="' + to + '" alt="" data-height-percentage=""/>';
                $image.append(logo);

                var logo_width = mainCustomizerData.theme_option.logo_width;
                var image = new Image();
                image.src = to;
                image.onload = function() {
                    $('#page-header #sticky_logo img').css({'width': logo_width, 'height': image.naturalHeight * logo_width / image.naturalWidth});
                    render_style(null);
                };
            });
        });

        wp.customize(AG_THEME_NAME_OPTION + '[header_font_google]', function (value) {
            value.bind(function (to) {
                var styles = '';
                var type   = '';
                var font   = '';
                if (to === '') {
                    theme_option.fontGoogle.header = 'Open+Sans:300,300italic,regular,italic,600,600italic,700,700italic,800,800italic';
                    styles += '#page-header{font-family: \'Open Sans\', sans-serif;} ';
                } else {
                    font = to.replace(/ /g, "+");
                    if (typeof data_font[to] !== 'undefined') {
                        font += ':' + data_font[to].styles;
                        type = data_font[to].type;
                        theme_option.fontGoogle.header = font;
                        styles += '#page-header{font-family: \'' + to + '\', ' + type + ';} ';
                    }
                }

                theme_option.header_font_google = styles;
                render_style(theme_option);
                // wp.customize.preview.send('refresh');
            });
        });

        wp.customize(AG_THEME_NAME_OPTION + '[sticky_header_height]', function (value) {
            value.bind(function (to) {
                //reset width logo by height of header
                var sticky_img = document.querySelector("#sticky_logo img");
                if (sticky_img == null) {
                    return false;
                }
                var img_width = sticky_img.naturalWidth;
                var img_height = sticky_img.naturalHeight;
                mainCustomizerData.theme_option.sticky_header_height = to;

                var styles = '';
                styles = '#wp_body_layout_home #page-header.sticky_header {min-height: ' + to + 'px;} ';
                theme_option.sticky_header_height = styles;
                render_style(theme_option);
            });
        });

        wp.customize(AG_THEME_NAME_OPTION + '[sticky_logo_width]', function (value) {
            value.bind(function (to) {
                var sticky_img = document.querySelector("#sticky_logo img");
                if (sticky_img == null) {
                    return false;
                }
                var img_width = sticky_img.naturalWidth;
                var img_height = sticky_img.naturalHeight;

                mainCustomizerData.theme_option.sticky_logo_width = to;

                $('#page-header #sticky_logo img').css({'width': to, 'height': to * img_height / img_width});
            });
        });

        wp.customize(AG_THEME_NAME_OPTION + '[sticky_background_color]', function (value) {
            value.bind(function (to) {
                var styles = '';
                styles = '#page-header.sticky_header {background-color: ' + to + ';} ';
                theme_option.sticky_background_color = styles;
                render_style(theme_option);
            });
        });

        wp.customize(AG_THEME_NAME_OPTION + '[sticky_text_color]', function (value) {
            value.bind(function (to) {
                var styles = '';
                styles = '#page-header.sticky_header .ag-header-menu>a, #page-header.sticky_header .ag-header-menu li a, .sticky_header .menu-header .cart-customlocation span, .sticky_header .menu-header .cart-customlocation i {color: ' + to + ';} ';
                theme_option.sticky_text_color = styles;
                render_style(theme_option);
            });
        });

        wp.customize(AG_THEME_NAME_OPTION + '[sticky_active_link_color]', function (value) {
            value.bind(function (to) {
                var styles = '';
                styles = '.sticky_header .ag-header-menu .current-menu-parent, .sticky_header .ag-header-menu .current-page-parent,#page-header.sticky_header  .ag-header-menu .current-menu-ancestor > a, #page-header.sticky_header  .ag-header-menu .current-menu-parent > a, #page-header.sticky_header  .ag-header-menu .current-menu-item > a, #page-header.sticky_header  .ag-header-menu .current_page_item > a {color: ' + to + ' !important;} ';
                styles += '#page-header.sticky_header  .ag-header-menu > .current-menu-itemm,#page-header.sticky_header  .ag-header-menu > .current_page_item {border-bottom-color: ' + to + ' !important;} ';
                theme_option.sticky_active_link_color = styles;
                render_style(theme_option);
            });
        });

        wp.customize(AG_THEME_NAME_OPTION + '[sticky_menu_background_color]', function (value) {
            value.bind(function (to) {
                var styles = '';
                styles = '.sticky_header .ag-header-menu>ul>li>a, #page-header.sticky_header .menu-header .ag_theme_cart {background-color: ' + to + ';} ';
                styles += '#page-header.sticky_header .ag-header-menu > ul >li, #page-header.sticky_header .menu-header .ag_theme_cart{border-bottom: 1px solid ' + to + ';} ';
                theme_option.sticky_menu_background_color = styles;
                render_style(theme_option);
            });
        });

        wp.customize(AG_THEME_NAME_OPTION + '[header_height]', function (value) {
            value.bind(function (to) {
                //reset width logo by height of header
                var img = document.querySelector("#logo img");
                if (img == null) {
                    return false;
                }
                var img_width = img.naturalWidth;
                var img_height = img.naturalHeight;

                mainCustomizerData.theme_option.header_height = to;

                var styles = '';
                styles = '#wp_body_layout_home #page-header {min-height: ' + to + 'px;} ';
                theme_option.header_height = styles;
                render_style(theme_option);
            });
        });

        wp.customize(AG_THEME_NAME_OPTION + '[logo_width]', function (value) {
            value.bind(function (to) {
                var img = document.querySelector("#logo img");
                if (img == null) {
                    return false;
                }
                var img_width = img.naturalWidth;
                var img_height = img.naturalHeight;

                mainCustomizerData.theme_option.logo_width = to;

                $('#page-header #logo img').css({'width': to, 'height': to * img_height / img_width});
            });
        });

        wp.customize(AG_THEME_NAME_OPTION + '[header_background_color]', function (value) {
            value.bind(function (to) {
                var styles = '';
                styles = '#page-header{background-color: ' + to + ';} ';
                theme_option.header_background_color = styles;
                render_style(theme_option);
            });
        });

        wp.customize(AG_THEME_NAME_OPTION + '[header_border_color]', function (value) {
            value.bind(function (to) {
                var styles = '';
                styles = '#wp_body_layout_home #page-header{border-color: ' + to + ';} ';
                theme_option.header_border_color = styles;
                render_style(theme_option);
            });
        });

        wp.customize(AG_THEME_NAME_OPTION + '[header_border_size]', function (value) {
            value.bind(function (to) {
                var styles = '';
                styles = '#wp_body_layout_home #page-header{border-width: ' + to + 'px;} ';
                theme_option.header_border_size = styles;
                render_style(theme_option);
            });
        });

        wp.customize('background_header_image', function (value) {
            value.bind(function (to) {
                var styles = '';
                styles = '#page-header {background-image: url(' + to + ') !important;} ';
                theme_option.background_header_image = styles;
                render_style(theme_option);
            });
        });

        wp.customize(AG_THEME_NAME_OPTION + '[menu_height]', function (value) {
            value.bind(function (to) {
                mainCustomizerData.theme_option.menu_height = to;

                var styles = '';
                var $line_height = to - 1;
                styles = '#page-header .ag_theme_cart, .show-menu-mobile i, #page-header .logo-header {line-height: ' + to + 'px; height: ' + to + 'px} ';
                styles += '#page-header .ag-header-menu li {line-height: ' + $line_height + 'px; height: ' + to + 'px} ';
                styles += '#page-header .ag-header-menu > ul {min-height: ' + to + 'px;} ';
                styles += '#page-header .curtain_menu nav:last-child>ul {padding-top: ' + to + 'px;} ';
                theme_option.menu_height = styles;
                render_style(theme_option);
            });
        });

        wp.customize(AG_THEME_NAME_OPTION + '[text_size_menu]', function (value) {
            value.bind(function (to) {
                var styles = '#wp_body_layout_home #page-header .ag-header-menu>ul>li>a {font-size: ' + to + 'px;} ';
                theme_option.text_size_menu = styles;
                render_style(theme_option);
            });
        });

        wp.customize(AG_THEME_NAME_OPTION + '[text_size_sub_menu]', function (value) {
            value.bind(function (to) {
                var styles = '#wp_body_layout_home #page-header .ag-header-menu .sub-menu a{font-size: ' + to + 'px;} ';
                theme_option.text_size_sub_menu = styles;
                render_style(theme_option);
            });
        });

        wp.customize(AG_THEME_NAME_OPTION + '[border_top_sub_menu]', function (value) {
            value.bind(function (to) {
                var styles = '#page-header nav.ag-header-menu .sub-menu {border-top-width: ' + to + 'px;} ';
                styles += '#page-header nav.ag-header-menu .sub-menu {border-top-style: solid;} ';
                theme_option.border_top_sub_menu = styles;
                render_style(theme_option);
            });
        });

        wp.customize(AG_THEME_NAME_OPTION + '[sub_menu_width]', function (value) {
            value.bind(function (to) {
                var styles = '#wp_body_layout_home #page-header .ag-header-menu li:hover > .sub-menu {width: ' + to + 'px;} ';
                styles += '.ag-footer-menu .sub-menu .sub-menu {left: ' + to + 'px;} ';
                theme_option.sub_menu_width = styles;
                render_style(theme_option);
            });
        });

        wp.customize(AG_THEME_NAME_OPTION + '[font_style]', function (value) {
            value.bind(function (to) {
                var styles = ag_set_font_styles(to, '');
                styles = '#wp_body_layout_home #page-header .ag-header-menu a,#wp_body_layout_home #page-header .ag-header-menu li a {' + styles + '} ';
                theme_option.font_style = styles;
                render_style(theme_option);
            });
        });

        wp.customize(AG_THEME_NAME_OPTION + '[text_color]', function (value) {
            value.bind(function (to) {
                var styles = '';
                styles = '#page-header .ag-header-menu>a, #page-header .ag-header-menu li a, .menu-header .cart-customlocation span, .menu-header .cart-customlocation i {color: ' + to + ';} ';
                theme_option.text_color = styles;
                render_style(theme_option);
            });
        });

        wp.customize(AG_THEME_NAME_OPTION + '[active_link_color]', function (value) {
            value.bind(function (to) {
                var styles = '';
                styles = '.ag-header-menu .current-menu-parent, .ag-header-menu .current-page-parent,#page-header .ag-header-menu .current-menu-ancestor > a, #page-header .ag-header-menu .current-menu-parent > a, #page-header .ag-header-menu .current-menu-item > a, #page-header .ag-header-menu .current_page_item > a {color: ' + to + ' !important;} ';
                styles += '#page-header .ag-header-menu > .current-menu-itemm,#page-header .ag-header-menu > .current_page_item {border-bottom-color: ' + to + ' !important;} ';
                theme_option.active_link_color = styles;
                render_style(theme_option);
            });
        });

        wp.customize(AG_THEME_NAME_OPTION + '[menu_background_color]', function (value) {
            value.bind(function (to) {
                var styles = '';
                styles = '.ag-header-menu>ul>li>a, #page-header .menu-header .ag_theme_cart {background-color: ' + to + ';} ';
                styles += '#page-header .ag-header-menu > ul >li, #page-header .menu-header .ag_theme_cart{border-bottom: 1px solid ' + to + ';} ';
                theme_option.menu_background_color = styles;
                render_style(theme_option);
            });
        });

        wp.customize(AG_THEME_NAME_OPTION + '[drop_menu_background_color]', function (value) {
            value.bind(function (to) {
                var styles = '';
                styles = '#page-header .ag-header-menu .sub-menu>li, #page-header .ag-header-menu li .sub-menu{background-color: ' + to + ';} ';
                theme_option.drop_menu_background_color = styles;
                render_style(theme_option);
            });
        });

        wp.customize(AG_THEME_NAME_OPTION + '[drop_menu_background_color_hover]', function (value) {
            value.bind(function (to) {
                var styles = '';
                styles = '#page-header .ag-header-menu .sub-menu li:hover {background-color: ' + to + ';} ';
                theme_option.drop_menu_background_color_hover = styles;
                render_style(theme_option);
            });
        });

        wp.customize(AG_THEME_NAME_OPTION + '[drop_menu_line_color]', function (value) {
            value.bind(function (to) {
                var styles = '';
                styles = '#page-header .ag-header-menu .sub-menu{border-color: ' + to + ';} ';
                theme_option.drop_menu_line_color = styles;
                render_style(theme_option);
            });
        });

        wp.customize(AG_THEME_NAME_OPTION + '[drop_menu_text_color]', function (value) {
            value.bind(function (to) {
                var styles = '';
                styles = '#page-header .ag-header-menu li ul a{color: ' + to + ';} ';
                theme_option.drop_menu_text_color = styles;
                render_style(theme_option);
            });
        });

        wp.customize(AG_THEME_NAME_OPTION + '[footer_menu]', function (value) {
            value.bind(function (to) {
                var styles = '';
                styles = '#wp_body_layout_home #page-footer #footer-nav {text-align: ' + to + ';} ';
                theme_option.footer_menu = styles;
                render_style(theme_option);
            });
        });

        wp.customize(AG_THEME_NAME_OPTION + '[footer_columns]', function (value) {
            value.bind(function (to) {
                $('#wp_body_layout_home').removeClass(function(index, css){
                    return (css.match (/\bag_theme_footer_\S+/g) || []).join(' ')
                });
                $('#wp_body_layout_home').addClass('ag_theme_footer_' + to);
                render_style(theme_option);
            });
        });

        wp.customize(AG_THEME_NAME_OPTION + '[footer_menu_background_color]', function (value) {
            value.bind(function (to) {
                var styles = '';
                styles = '#footer-nav, #footer-nav ul, #footer-nav .ag-footer-menu li, #footer-nav .ag-footer-menu li a {background-color: ' + to + ';} ';
                theme_option.footer_menu_background_color = styles;
                render_style(theme_option);
            });
        });

        wp.customize(AG_THEME_NAME_OPTION + '[footer_background_color]', function (value) {
            value.bind(function (to) {
                var styles = '';
                styles = '#wp_body_layout_home #page-footer{background-color: ' + to + ';} ';
                theme_option.footer_background_color = styles;
                render_style(theme_option);
            });
        });

        wp.customize(AG_THEME_NAME_OPTION + '[custom_footer_credits]', function (value) {
            value.bind(function (to) {
                $page_copyright = $('#page-copyright');
                $page_copyright.html(to);
                render_style(theme_option);
            });
        });
        wp.customize(AG_THEME_NAME_OPTION + '[position_footer_credits]', function (value) {
            value.bind(function (to) {
                var styles = '';
                styles = 'body #page-footer #page-copyright {text-align: ' + to + ';} ';
                theme_option.position_footer_credits = styles;
                render_style(theme_option);
            });
        });

        wp.customize(AG_THEME_NAME_OPTION + '[widgets_header_text_size]', function (value) {
            value.bind(function (to) {
                var styles = '';
                styles += '#footer-widgets h1 {font-size: ' + to + 'rem;} ';
                styles += '#footer-widgets h2 {font-size: ' + to * 0.9 + 'rem;} ';
                styles += '#footer-widgets h3 {font-size: ' + to * 0.77 + 'rem;} ';
                styles += '#footer-widgets h4 {font-size: ' + to * 0.64 + 'rem;} ';
                styles += '#footer-widgets h5 {font-size: ' + to * 0.5 + 'rem;} ';
                styles += '#footer-widgets h6 {font-size: ' + to * 0.36 + 'rem;} ';
                theme_option.widgets_header_text_size = styles;
                render_style(theme_option);
            });
        });

        wp.customize(AG_THEME_NAME_OPTION + '[widgets_header_font_style]', function (value) {
            value.bind(function (to) {
                var styles = ag_set_font_styles(to, '');
                styles = '#footer-widgets h1,#footer-widgets h2,#footer-widgets h3,#footer-widgets h4,#footer-widgets h5,#footer-widgets h6 {' + styles + '} ';
                theme_option.widgets_header_font_style = styles;
                render_style(theme_option);
            });
        });

        wp.customize(AG_THEME_NAME_OPTION + '[widgets_body_link_text_size]', function (value) {
            value.bind(function (to) {
                var styles = '';
                styles = '#footer-bottom {font-size: ' + to + 'rem;} ';
                theme_option.widgets_body_link_text_size = styles;
                render_style(theme_option);
            });
        });

        wp.customize(AG_THEME_NAME_OPTION + '[widgets_body_link_line_height]', function (value) {
            value.bind(function (to) {
                var styles = '';
                styles = '#footer-widgets ol, #footer-widgets ul, #footer-widgets div, #footer-widgets h1, #footer-widgets h2, #footer-widgets h3, #footer-widgets h4, #footer-widgets h5, #footer-widgets h6';
                styles += '{line-height: ' + to + ';} ';
                theme_option.widgets_body_link_line_height = styles;
                render_style(theme_option);
            });
        });

        wp.customize(AG_THEME_NAME_OPTION + '[widgets_body_font_style]', function (value) {
            value.bind(function (to) {
                var styles = ag_set_font_styles(to, '');
                styles = '#footer-widgets, #footer-widgets ul, #footer-widgets ol, #footer-widgets li, #footer-widgets p, #footer-widgets span, #footer-widgets a,#footer-widgets .custom-html-widget {' + styles + '} ';
                theme_option.widgets_body_font_style = styles;
                render_style(theme_option);
            });
        });

        wp.customize(AG_THEME_NAME_OPTION + '[widget_text_color]', function (value) {
            value.bind(function (to) {
                var styles = '';
                styles = '#footer-widgets{color: ' + to + ';} ';
                theme_option.widget_text_color = styles;
                render_style(theme_option);
            });
        });

        wp.customize(AG_THEME_NAME_OPTION + '[widget_link_color]', function (value) {
            value.bind(function (to) {
                var styles = '';
                styles = '#footer-widgets a{color: ' + to + ';} ';
                theme_option.widget_link_color = styles;
                render_style(theme_option);
            });
        });

        wp.customize(AG_THEME_NAME_OPTION + '[widget_header_color]', function (value) {
            value.bind(function (to) {
                var styles = '';
                styles = '#footer-widgets  h1,#footer-widgets  h2,#footer-widgets  h3,#footer-widgets  h4,#footer-widgets  h5,#footer-widgets  h6{color: ' + to + ';} ';
                theme_option.widget_header_color = styles;
                render_style(theme_option);
            });
        });

        wp.customize(AG_THEME_NAME_OPTION + '[footer_credit_color]', function (value) {
            value.bind(function (to) {
                var styles = '';
                styles = '#page-copyright {color: ' + to + ';} ';
                theme_option.footer_credit_color = styles;
                render_style(theme_option);
            });
        });

        wp.customize(AG_THEME_NAME_OPTION + '[display_author]', function (value) {
            value.bind(function (to) {
                wp.customize.preview.send('refresh');
            });
        });

        wp.customize(AG_THEME_NAME_OPTION + '[display_date]', function (value) {
            value.bind(function (to) {
                wp.customize.preview.send('refresh');
            });
        });

        wp.customize(AG_THEME_NAME_OPTION + '[display_category]', function (value) {
            value.bind(function (to) {
                wp.customize.preview.send('refresh');
            });
        });

        wp.customize(AG_THEME_NAME_OPTION + '[display_comments]', function (value) {
            value.bind(function (to) {
                wp.customize.preview.send('refresh');
            });
        });

        /*mobile menu*/
        wp.customize(AG_THEME_NAME_OPTION + '[full_mobile_menu]', function (value) {
            value.bind(function (to) {
                wp.customize.preview.send('refresh');
            });
        });
        wp.customize(AG_THEME_NAME_OPTION + '[position_mobile_menu]', function (value) {
            value.bind(function (to) {
                var styles = '';
                styles = '#wp_body_layout_home #page-header .ag-mobile-menu li a {text-align: ' + to + ';} ';
                theme_option.position_mobile_menu = styles;
                render_style(theme_option);
            });
        });

        wp.customize(AG_THEME_NAME_OPTION + '[mobile_menu_background_color]', function (value) {
            value.bind(function (to) {
                var styles = '';
                styles = '#page-header .menu-header .ag-mobile-menu ul {background-color: ' + to + ';} ';
                theme_option.mobile_menu_background_color = styles;
                render_style(theme_option);
            });
        });
        wp.customize(AG_THEME_NAME_OPTION + '[mobile_hover_color]', function (value) {
            value.bind(function (to) {
                var styles = '';
                styles = '#page-header .menu-header .ag-mobile-menu ul a:hover {background-color: ' + to + ';} ';
                theme_option.mobile_menu_background_color = styles;
                render_style(theme_option);
            });
        });
        wp.customize(AG_THEME_NAME_OPTION + '[mobile_menu_height]', function (value) {
            value.bind(function (to) {
                var styles = '';
                styles = 'body #page-header .menu-header .ag-mobile-menu > ul li {line-height: ' + to + 'px} ';
                theme_option.mobile_menu_height = styles;
                render_style(theme_option);
            });
        });
        wp.customize(AG_THEME_NAME_OPTION + '[mobile_text_size_menu]', function (value) {
            value.bind(function (to) {
                var styles = '#wp_body_layout_home #page-header .ag-mobile-menu>ul a {font-size: ' + to + 'px;} ';
                theme_option.mobile_text_size_menu = styles;
                render_style(theme_option);
            });
        });
        wp.customize(AG_THEME_NAME_OPTION + '[mobile_font_style]', function (value) {
            value.bind(function (to) {
                var styles = ag_set_font_styles(to, '');
                styles = '#wp_body_layout_home #page-header .ag-mobile-menu a,#wp_body_layout_home #page-header .ag-mobile-menu li a {' + styles + '} ';
                theme_option.mobile_font_style = styles;
                render_style(theme_option);
            });
        });
        wp.customize(AG_THEME_NAME_OPTION + '[mobile_text_color]', function (value) {
            value.bind(function (to) {
                var styles = '';
                styles = '.ag-mobile-menu>a, .ag-mobile-menu>ul>li a, .show-menu-mobile .cart-customlocation span, .show-menu-mobile i {color: ' + to + ';} ';
                theme_option.mobile_text_color = styles;
                render_style(theme_option);
            });
        });
        wp.customize(AG_THEME_NAME_OPTION + '[mobile_active_link_color]', function (value) {
            value.bind(function (to) {
                var styles = '';
                styles = '.ag-mobile-menu .current-menu-item > a, .ag-mobile-menu .current_page_item > a{color: ' + to + ' !important;} ';
                theme_option.mobile_active_link_color = styles;
                render_style(theme_option);
            });
        });
        wp.customize(AG_THEME_NAME_OPTION + '[sidebar_width]', function (value) {
            value.bind(function (to) {
                var styles = '';
                styles = 'body:not(.ag_sidebarHide)  #content {width: ' + (100 - to) + '%;} ';
                styles += '#sidebar {width: ' + to + '%;} ';
                theme_option.sidebar_width = styles;
                render_style(theme_option);
            });
        });
        wp.customize(AG_THEME_NAME_OPTION + '[sidebar_background_color]', function (value) {
            value.bind(function (to) {
                var styles = '';
                styles = '#main-content  #sidebar {background-color: ' + to + ';} ';
                theme_option.sidebar_background_color = styles;
                render_style(theme_option);
            });
        });
        wp.customize(AG_THEME_NAME_OPTION + '[sidebar_link_color]', function (value) {
            value.bind(function (to) {
                var styles = '';
                styles = '#main-content #sidebar a, #main-content #sidebar .comment-edit-link, #main-content #sidebar .pinglist a, #main-content #sidebar .pagination a {color: ' + to + ';} ';
                theme_option.sidebar_link_color = styles;
                render_style(theme_option);
            });
        });
        wp.customize(AG_THEME_NAME_OPTION + '[sidebar_header_color]', function (value) {
            value.bind(function (to) {
                var styles = '';
                styles = '#main-content #sidebar h1,#main-content #sidebar h2,#main-content #sidebar h3,#main-content #sidebar h4,#main-content #sidebar h5,#main-content #sidebar h6 {color: ' + to + ';} ';
                theme_option.sidebar_header_color = styles;
                render_style(theme_option);
            });
        });
        wp.customize(AG_THEME_NAME_OPTION + '[sidebar_text_color]', function (value) {
            value.bind(function (to) {
                var styles = '';
                styles = '#main-content #sidebar {color: ' + to + ';} ';
                theme_option.sidebar_text_color = styles;
                render_style(theme_option);
            });
        });
        wp.customize(AG_THEME_NAME_OPTION + '[sidebar_header_style]', function (value) {
            value.bind(function (to) {
                var styles = ag_set_font_styles(to, '');
                styles = '#main-content #sidebar h1,#main-content #sidebar h2,#main-content #sidebar h3,#main-content #sidebar h4,#main-content #sidebar h5,#main-content #sidebar h6 {' + styles + '} ';
                theme_option.sidebar_header_style = styles;
                render_style(theme_option);
            });
        });
        wp.customize(AG_THEME_NAME_OPTION + '[padding_top_footer]', function (value) {
            value.bind(function (to) {
                var styles = '';
                styles = ' #footer-bottom {padding-top: ' + to + 'px;} ';
                theme_option.padding_top_footer = styles;
                render_style(theme_option);
            });
        });
        wp.customize(AG_THEME_NAME_OPTION + '[padding_bottom_footer]', function (value) {
            value.bind(function (to) {
                var styles = '';
                styles = ' #footer-bottom {padding-bottom: ' + to + 'px;} ';
                theme_option.padding_bottom_footer = styles;
                render_style(theme_option);
            });
        });
        wp.customize(AG_THEME_NAME_OPTION + '[line_height_footer_credit]', function (value) {
            value.bind(function (to) {
                var styles = '';
                styles = ' #page-copyright {line-height: ' + to + ';} ';
                theme_option.line_height_footer_credit = styles;
                render_style(theme_option);
            });
        });


        /*set wp.customize to template customizer option*/
        var render_customize = function (page_refresh) {
            var select_layout_style = mainCustomizerData.theme_option.select_layout_style;
            if (select_layout_style !== 'Custom') {
                $.each(mainCustomizerData.data_template_default[select_layout_style].getCustomizer, function (i, v) {
                    wp.customize(AG_THEME_NAME_OPTION + '[' + v.name + ']', function (value) {
                        value.bind(function (to) {
                            var styles = '';
                            styles = v.object + ' {' + v.properties + ': ' + to + v.unit + ';} ';
                            theme_option[v.name] = styles;
                            render_style(theme_option);
                        });
                    });
                })
            }
            if (page_refresh) {
                wp.customize.preview.send('refresh');
            }
        }

        render_customize();
    });
})(jQuery);
