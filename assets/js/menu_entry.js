(function ($) {
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip({
            animation: true,
            delay: {show: 100, hide: 10}
        });
        var custom             = {};
        var $advanced_theme    = $('#theme_advanced');
        var $theme_layout      = $('#theme-layout');
        var $theme_live_update = $('#theme-live-update');
        var $loading_animation = $('#loading_animation');

        var readOnlyCodeMirrorCss, readOnlyCodeMirrorHead, readOnlyCodeMirrorBody;
        var checkReset     = 1;
        var updaterparams  = typeof menu_entry_js.updaterparams !== 'undefined' ? menu_entry_js.updaterparams : [];
        var listPack       = {};

        var getJsonFunction = true;

        if (typeof menu_entry_js.ag_theme_option.custom !== 'undefined') {
            custom = menu_entry_js.ag_theme_option.custom;
        }

        if (typeof AG_THEME_NAME_OPTION === 'undefined') {
            var AG_THEME_NAME_OPTION = 'AGtheme';
        }

        if (typeof menu_entry_js.content_page !== 'undefined' && menu_entry_js.content_page !== '') {
            if (typeof location.hash !== 'undefined' && location.hash === '') {
                switch (menu_entry_js.content_page) {
                    case 'theme-live-update':
                        $('#config_option li.update a').trigger('click');
                        break;
                    case 'theme-translation':
                        $('#config_option li.translation a').trigger('click');
                        break;
                    default:
                        break;
                }
            }
        }

        $('#config_option li a').click(function () {
            var $parent = $(this).parents('li.tab');
            if ($parent.hasClass('theme_option')) {
                window.location.replace('#theme_advanced');
            } else if ($parent.hasClass('theme_layout')) {
                window.location.replace('#theme-layout');
            } else if ($parent.hasClass('update')) {
                window.location.replace('#theme-live-update');
            } else if ($parent.hasClass('translation')) {
                window.location.replace('#theme-translation');
            }
        });

        function showImageBackground ($backgroundDiv, $url) {
            if (typeof $url !== 'undefined' && $url !== '' && $url !== null) {
                $backgroundDiv.css('background', 'url(' + $url + ')');
            } else {
                $backgroundDiv.css({
                    background: 'none'
                });
            }
        }

        function checkInput($input, $show) {
            if ($input.val() == 1) {
                $input.prop("checked", true);
                if ($show.length > 0) {
                    $show.css({display : 'block'});
                }
            } else {
                $input.prop("checked", false);
                if ($show.length > 0) {
                    $show.hide();
                }
            }
        }

        //select, remove, default background footer image
        $('#ag_template_footer_img').click(function(e) {
            var btnClicked = $( this );
            var custom_uploader = wp.media({
                title: agThemeText.FeaturedImage,
                button: {
                    text: agThemeText.select
                },
                multiple: false  // Set this to true to allow multiple files to be selected
            })
                .on('select', function() {
                    var attachment = custom_uploader.state().get('selection').first().toJSON();
                    showImageBackground($('#background_footer div:eq(0)'), attachment.url)
                    var data_id = $('#ag_template').find('option:selected').data('id');
                    custom.templateFooter[data_id].url = attachment.url;
                })
                .open();
            return false;
        });
        $('#ag_remove_footer_img').click(function(e) {
            var data_id = $('#ag_template').find('option:selected').data('id');
            custom.templateFooter[data_id].url = '';
            showImageBackground($('#background_footer div:eq(0)'), null);
            return false;
        });
        $('#ag_default_footer_img').click(function(e) {
            var data_id = $('#ag_template').find('option:selected').data('id');
            custom.templateFooter[data_id].url = menu_entry_js.folderTemplates + data_id + '/assets/images/' + listPack[data_id].footer;
            showImageBackground($('#background_footer div:eq(0)'), custom.templateFooter[data_id].url);
            return false;
        });

        //theme option
        function theme_option(custom) {
            $advanced_theme.find('#ag_woocommerce_archive_num_posts').val(typeof custom.numberWooC !== 'undefined' ? custom.numberWooC : 9);
            $advanced_theme.find('#ag_cat_page').val(typeof custom.numberCat !== 'undefined' ? custom.numberCat : 6);
            $advanced_theme.find('#ag_archive_page').val(typeof custom.numberArchive !== 'undefined' ? custom.numberArchive : 5);
            $advanced_theme.find('#ag_search_page').val(typeof custom.numberSearch !== 'undefined' ? custom.numberSearch : 5);
            $advanced_theme.find('#ag_tag_page').val(typeof custom.numberTag !== 'undefined' ? custom.numberTag : 5);
            $advanced_theme.find('#ag_woo_single').val(typeof custom.agWooSingle !== 'undefined' ? custom.agWooSingle : 600);
            $advanced_theme.find('#ag_woo_gallery_thumbnail').val(typeof custom.agWooGalleryThumbnail !== 'undefined' ? custom.agWooGalleryThumbnail : 100);
            $advanced_theme.find('#ag_woo_thumbnail').val(typeof custom.agWooThumbnail !== 'undefined' ? custom.agWooThumbnail : 600);
            $advanced_theme.find('#ag_woo_button_style').val(typeof custom.wooButtonStyle !== 'undefined' ? custom.wooButtonStyle : 1);

            $advanced_theme.find('#ag_breadcrumb').val(typeof custom.agBreadcrumb !== 'undefined' ? custom.agBreadcrumb : 1);
            $advanced_theme.find('#ag_style_footer').val(typeof custom.styleFooter !== 'undefined' ? custom.styleFooter : 1);
            $('.ag-box-content span.slider').each(function (e) {
                var id = $(this).data('show');
                checkInput($(this).siblings(), $('#' + id));
            });
            $('.ag-box-content span.slider').on('click', function (e) {
                e.preventDefault();
                var $input = $(this).siblings();
                $input.val(Math.abs(1 - $input.val()));
                var id = $(this).data('show');
                checkInput($input, $('#' + id));
            });

            //get list template installed
            $advanced_theme.find('#ag_template option').remove('.layout');
            $.each(custom.templateFooter, function (i, v) {
                if (v.name !== '') {
                    $advanced_theme.find('#ag_template').append($('<option class="layout" data-id="' + i + '">' + v.name + '</option>'));
                }
            });

            $('#ag_template').change(function () {
                $('#background_footer').find('input').show();
                var data_id = $(this).find('option:selected').data('id');
                if (typeof data_id !== 'undefined' && data_id !== 'undefined') {
                    if (typeof custom.templateFooter !== 'undefined' && typeof custom.templateFooter[data_id] !== 'undefined') {
                        showImageBackground($('#background_footer div:eq(0)'), custom.templateFooter[data_id].url);
                    }
                } else {
                    $('#background_footer').find('input').hide();
                    showImageBackground($('#background_footer div:eq(0)'), null);
                }
            });
        }

        theme_option(custom);

        //custom code
        function custom_code() {
            //custom css
            var $custom_css = document.getElementById("custom_css");
            readOnlyCodeMirrorCss = CodeMirror.fromTextArea($custom_css, {
                mode: "css",
                theme: "oceanic-next",
                lineNumbers: true
            });

            //custom head
            var $custom_head = document.getElementById("custom_head");
            readOnlyCodeMirrorHead = CodeMirror.fromTextArea($custom_head, {
                mode: "text/html",
                theme: "oceanic-next",
                lineNumbers: true
            });

            //custom body
            var $custom_body = document.getElementById("custom_body");
            readOnlyCodeMirrorBody = CodeMirror.fromTextArea($custom_body, {
                mode: "htmlmixed",
                theme: "oceanic-next",
                lineNumbers: true
            });
            return {
                'customCss': readOnlyCodeMirrorCss,
                'customHead': readOnlyCodeMirrorHead,
                'customBody': readOnlyCodeMirrorBody
            };
        }

        function setValCodeMirror(CodeMirror, optionVal) {
            String.prototype.stripSlashes = function(){
                return this.replace(/\\(.)/mg, "$1");
            };
            $.each(CodeMirror, function (i, v) {
                if (typeof optionVal[i] === 'undefined') {
                    optionVal[i] = '';
                }
                v.setValue(optionVal[i].stripSlashes());
                if (optionVal[i] === '') {
                    v.refresh();
                }
            });
        }

        var CodeMirrors = custom_code();
        setValCodeMirror(CodeMirrors, custom);

        //advanced theme
        function advanced () {
            $('#advanced .reset_setting_theme').on('click', function () {
                bootbox.confirm(agThemeText.RESET_THEME_OPTION, function(result){
                    if (result === true) {
                        $.ajax({
                            type: 'POST',
                            url: ajaxurl,
                            data: {
                                'option_name': AG_THEME_NAME_OPTION,
                                'theme_folder': AG_THEME_FOLDER,
                                'option_nonce': $('#option_nonce').val(),
                                'action': 'theme_option_default'
                            },
                            success: function (result) {
                                if (result[0]) {
                                    $('#theme_advanced').find('.message.ju-notice-success').fadeIn(1000);
                                    custom = result[1]['optionVal'].custom;
                                    /*reset theme option*/
                                    theme_option(custom);
                                    checkReset = 1;
                                } else {
                                    $('#theme_advanced').find('.message.u-notice-error').fadeIn(1000);
                                }
                                return false;
                            },
                            error: function (result) {
                                $('#theme_advanced').find('.message.u-notice-error').fadeIn(1000);
                            }
                        });
                    }
                });
            });
        }
        advanced();

        /*click select setting menu in theme option*/
        var select_option = function () {
            $('.ju-top-tabs li.tab').on('click', function (e) {
                var $that = $(this);
                if ($that.hasClass('advanced')) {
                    $('#saveTable').hide();
                } else if ($that.hasClass('custom_code')) {
                    /*reset theme option custom code*/
                    if (checkReset === 1) {
                        setTimeout(function () {
                            setValCodeMirror({
                                'customCss': readOnlyCodeMirrorCss,
                                'customHead': readOnlyCodeMirrorHead,
                                'customBody': readOnlyCodeMirrorBody
                            }, custom);
                            checkReset = 0;
                        },200);
                    }
                    $('#saveTable').show();
                } else {
                    $('#saveTable').show();
                }
                /*change size for .indicator*/
                setTimeout(function () {
                    var width_li = $that.outerWidth();
                    var position_li = $that.position();
                    $that.siblings('.indicator').css({'left': position_li.left, 'width': width_li});
                }, 500);
            });
        };
        select_option();

        $('#theme_setting').find('#saveTable').on('click', function (e) {
            var optionSave = {};
            optionSave.customCss = readOnlyCodeMirrorCss.getValue();
            optionSave.customHead = readOnlyCodeMirrorHead.getValue();
            optionSave.customBody = readOnlyCodeMirrorBody.getValue();
            optionSave.numberWooC = $advanced_theme.find('#ag_woocommerce_archive_num_posts').val();
            optionSave.numberCat = $advanced_theme.find('#ag_cat_page').val();
            optionSave.numberArchive = $advanced_theme.find('#ag_archive_page').val();
            optionSave.numberSearch = $advanced_theme.find('#ag_search_page').val();
            optionSave.numberTag = $advanced_theme.find('#ag_tag_page').val();
            optionSave.agWooSingle = $advanced_theme.find('#ag_woo_single').val();
            optionSave.agWooGalleryThumbnail = $advanced_theme.find('#ag_woo_gallery_thumbnail').val();
            optionSave.agWooThumbnail = $advanced_theme.find('#ag_woo_thumbnail').val();
            optionSave.agBreadcrumb = $advanced_theme.find('#ag_breadcrumb').val();
            optionSave.styleFooter = $advanced_theme.find('#ag_style_footer').val();
            optionSave.wooButtonStyle = $advanced_theme.find('#ag_woo_button_style').val();
            optionSave.templateFooter = custom.templateFooter;
            $loading_animation.show();
            $.ajax({
                type: 'POST',
                url: ajaxurl,
                data: {
                    'option_nonce': $('#option_nonce').val(),
                    'customized': optionSave,
                    'option_name': AG_THEME_NAME_OPTION,
                    'action': 'theme_option_setting'
                },
                success: function (result) {
                    $loading_animation.hide();
                    $('.message.ju-notice-success').fadeIn(1000);
                    window.scroll({top: 0});
                },
                error: function (result) {
                    $loading_animation.hide();
                    $('.message.u-notice-error').fadeIn(1000);
                    window.scroll({top: 0});
                }
            });
        });

        $('#config_option').find('.theme_layout a.tab_theme_layout').on('click', function () {
            if (getJsonFunction) {
                getLayout();
            }
        });

        $theme_layout.find('.ju-top-tabs a').on('click', function () {
            if (getJsonFunction) {
                getLayout();
            }
        });

        getLayout();

        function agThemeGetCookie(cname, value) {
            if (typeof cname !== 'undefined') {
                var ca = document.cookie.split(';');
                for(var i=0; i<ca.length; i++) {
                    var c = ca[i].split('=');

                    if (c[0].charAt(0)===' ') {
                        c[0] = c[0].substring(1);
                    }

                    if (typeof c[0] !== 'undefined' && c[0] === cname) {
                        document.cookie = 'ag_layouts_select=' + value;
                        if (value === '') {
                            return c[1];
                        }
                    }
                }
            }
            return '';
        }

        function getLayout() {
            $loading_animation.show();
            getJsonFunction = false;
            $.ajax({
                type: 'POST',
                url: ajaxurl,
                data: {
                    'option_nonce': $('#option_nonce').val(),
                    'action': 'theme_get_pack_list'
                },
                success: function (result) {
                    var data = $.parseJSON(result);
                    if (data.response) {
                        var listLayoutPack = data.datas;
                        /*temporary get data by datalayoutlist.json*/
                        if (typeof listLayoutPack.ju_template_pack !== 'undefined' && listLayoutPack.ju_template_pack !== null) {
                            listPack = $.extend({}, listLayoutPack.ju_template_pack);
                        }
                        $loading_animation.hide();
                        /*temporary get data by datalayoutlist.json*/
                        $.each(listPack, function (i, v) {
                            var layout = $('<a class="layout_avatar"></a>');

                            if (listPack[i].status > 1) {
                                layout.append('<div class="ag_dlib_animate" data-layout="' + i + '"><div class="hasPack">' + agThemeText.installed + '</div><div class="image_view"><image src="' + v.src + '"/></div></div>');
                            } else {
                                layout.append('<div class="ag_dlib_animate" data-layout="' + i + '"><div class="image_view"><image src="' + v.src + '"/></div></div>');
                            }
                            layout.append('<div class="title"><h4>' + v.name + '</h4><h5>' + agThemeText.layoutPack + '</h5></div>');

                            /*append layout*/
                            $theme_layout.find('#theme_premade_layout').find('.ag-box-content').append(layout);
                            if (listPack[i].status > 0) {
                                $theme_layout.find('#your_layout').find('.ag-box-content').append(layout.clone());
                            }
                        });

                        event_layouts();

                        var open_layout = {
                            show: $theme_layout.find('#layout_content'),
                            hide: $theme_layout.find('.list_pack'),
                            buttonShow: $theme_layout.find('.layout_avatar'),
                            buttonHide: $theme_layout.find('#layout_content .close'),
                            get openLayout() {
                                var $layout_content = $theme_layout.find('#layout_content');
                                var id = $theme_layout.find('.layout_avatar.active .ag_dlib_animate').data('layout');
                                /*add description of this pack*/
                                $layout_content.find('.description .description_title').empty(),
                                    $layout_content.find('.description .description_title').html(listPack[id].description);

                                /*render list layout pack*/
                                var number_layout = 0;
                                $layout_content.find('.description .list_layout').empty();
                                $.each(listPack[id].layout_template, function (i, v) {
                                    var layout = i.replace('_', ' ');
                                    layout     = layout.toLowerCase().replace(/\b[a-z]/g, function(letter) {
                                        return letter.toUpperCase();
                                    });
                                    var layoutPack = $('<a data-layout="' + i + '"><div><image  src="' + v.src + '"></image></div><h5 style="margin-left: 10px;">' + layout + '</h5></a>');
                                    $layout_content.find('.description .list_layout').append(layoutPack);
                                    number_layout++;
                                });

                                /*add number layout in this pack*/
                                $layout_content.find('.description .number_layout').empty(),
                                    $layout_content.find('.description .number_layout').append('<h4>' + agThemeText.layoutsInThisPack + ' (' + number_layout + ')</h4>');

                                /*active layout pack number 0*/
                                $layout_content.find('.description .list_layout a:eq(0)').addClass('active');
                                active_layout($layout_content, listPack[id]);

                                /*click layout pack*/
                                $.each($layout_content.find('.description .list_layout a'), function () {
                                    $(this).on('click', function () {
                                        $layout_content.find('.description .list_layout a.active').removeClass('active'),
                                            $(this).addClass('active');
                                        active_layout($layout_content, listPack[id]);
                                    });
                                });
                            }
                        };
                        showAndHideWindow(open_layout);

                        /*open the layout saved in cookie when reload page*/
                        var ag_layouts_select =  agThemeGetCookie('ag_layouts_select', '');

                        if (ag_layouts_select !== '' && ag_layouts_select !== null) {
                            open_layout.buttonShow.find('.ag_dlib_animate[data-layout="' + ag_layouts_select + '"]').trigger('click');
                        }

                        /*when click button download_pack*/
                        layoutPackStatus(listPack);
                    }
                },
                error: function (result) {
                    $loading_animation.hide();
                    console.log(result);
                }
            });
        }

        /*scroll avatar layout*/
        function animate_avatar (){
            var that = $theme_layout.find('.layout_avatar').find('div.active');
            if (that.length > 0) {
                var height = that.height() - 220;
                if (height > 0) {
                    that.animate({
                        'top': - height + 'px'
                    }, height / 0.14).animate({
                        'top': '0px'
                    }, height / 0.14, animate_avatar);
                }
            }
        }
        function event_layouts() {
            $theme_layout.find('.layout_avatar').hover(
                function () {
                    $(this).find('.ag_dlib_animate').find('div:not(.hasPack)').addClass('active');
                    animate_avatar();
                }, function () {
                    $(this).find('.ag_dlib_animate').find('div.active').removeClass('active').finish();
                }
            );
        }

        function showAndHideWindow(open_layout) {
            open_layout.buttonShow.on('click', function () {
                agThemeGetCookie('ag_layouts_select', '');
                open_layout.show.removeClass('hide');
                open_layout.hide.each(function (i, v) {
                    if ($(v).is(":visible")) {
                        $(v).addClass('hide');
                    }
                });
                $(this).addClass('active');
                open_layout.openLayout;
            });

            open_layout.buttonHide.on('click', function () {
                open_layout.show.addClass('hide');
                open_layout.hide.each(function (i, v) {
                    if ($(v).hasClass('hide')) {
                        $(v).removeClass('hide');
                    }
                });
                open_layout.buttonShow.parent().find('.active').removeClass('active');
            });

            $('body').click(function (event) {
                if (open_layout.show.is(":visible")) {
                    if (!$(event.target).parents().is(open_layout.buttonShow) && !$(event.target).parents().is(open_layout.show) || $(event.target).parents().is(open_layout.buttonHide)) {
                        open_layout.buttonHide.trigger('click');
                    }
                }
            });
        }

        function active_layout($layout_content, layoutPack) {
            var layout = $layout_content.find('.description .list_layout a.active').data('layout');

            $layout_content.find('.avatar').empty(),
                $layout_content.find('.avatar').append('<image src="' + layoutPack.layout_template[layout].show + '">');

            $layout_content.find('.button_layout a:eq(0)').attr('href', layoutPack.layout_template[layout].demo);

            layout     = layout.replace('_', ' ');
            layout     = layout.toLowerCase().replace(/\b[a-z]/g, function(letter) {
                return letter.toUpperCase();
            });

            $layout_content.find('.description h3').empty(),
                $layout_content.find('.description h3').html(layout + ' Page');

            $layout_content.find('.description .status_button').append($theme_layout.find('#layout_content .button_layout a.download_pack'));
            var link = $theme_live_update.find('.ag-box-content>a.thickbox').attr('href');
            switch (layoutPack.status) {
                case 1:
                    if (updaterparams.token && updaterparams.token !== '') {
                        $theme_layout.find('#layout_content .button_layout').append($layout_content.find('.description .status_button a.install'));
                    } else {
                        $theme_layout.find('#layout_content .button_layout').append($layout_content.find('.description .status_button a.login'));
                        $theme_layout.find('#layout_content .button_layout a.download_pack').attr('href', link);
                    }
                    break;
                case 2:
                    $theme_layout.find('#layout_content .button_layout').append($layout_content.find('.description .status_button a.active'));
                    break;
                default:
                    if (updaterparams.token && updaterparams.token !== '') {
                        $theme_layout.find('#layout_content .button_layout').append($layout_content.find('.description .status_button a.buyPack'));
                    } else {
                        $theme_layout.find('#layout_content .button_layout').append($layout_content.find('.description .status_button a.login'));
                        $theme_layout.find('#layout_content .button_layout a.download_pack').attr('href', link);
                    }
                    break;
            }
        }

        function layoutPackStatus() {
            /*when click button buy*/
            $theme_layout.find('#layout_content .description .status_button a').on('click', function () {
                var status_pack = $(this).attr('class').split(/\s+/);
                var id = $theme_layout.find('.layout_avatar.active .ag_dlib_animate').data('layout');
                switch (status_pack[1]) {
                    case 'install':
                        if (typeof updaterparams.token !== 'undefined' && updaterparams.token !== false) {
                            $loading_animation.show();
                            $.ajax({
                                type: 'POST',
                                url: ajaxurl,
                                data: {
                                    'option_nonce': $('#option_nonce').val(),
                                    'slug': listPack[id].slug,
                                    'action': 'theme_install_pack',
                                    'pack_name': id
                                },
                                success: function (result) {
                                    var data = $.parseJSON(result);
                                    if (data.response) {
                                        $theme_layout.find('.message.ju-notice-success').fadeIn(1000),
                                            $theme_layout.find('.message.ju-notice-success').find('span').html(data.datas);
                                        /*change status pack*/
                                        listPack[id].status = 2;
                                        /*change status button*/
                                        $theme_layout.find('#layout_content').find('.description .status_button').append($theme_layout.find('#layout_content .button_layout a.download_pack'));
                                        $theme_layout.find('#layout_content .button_layout').append($theme_layout.find('#layout_content').find('.description .status_button a.active'));
                                        $theme_layout.find('#your_layout').find('.ag-box-content .ag_dlib_animate[data-layout="' + id + '"] .image_view').before('<div class="hasPack">' + agThemeText.installed + '</div>');
                                    } else {
                                        $theme_layout.find('.message.ju-notice-error').fadeIn(1000),
                                            $theme_layout.find('.message.ju-notice-error').find('span').html(data.datas);
                                    }
                                    $loading_animation.hide();
                                },
                                error: function (result) {
                                    $theme_layout.find('.message.ju-notice-error').fadeIn(1000),
                                        $theme_layout.find('.message.ju-notice-error').find('span').html(data.datas);
                                    $loading_animation.hide();
                                }
                            });
                        } else {
                            $('#theme-update').find('a').trigger('click');
                        }
                        break;
                    case 'has_active':
                        break;
                    case 'login':
                        agThemeGetCookie('ag_layouts_select', id);
                        break;
                    default :
                        break;
                }
            });
        }
    })
})(jQuery);
