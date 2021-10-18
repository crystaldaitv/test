(function ($) {
    $(document).ready(function () {
        localStorage.setItem('theme_option', '');

        var $customize_footer_actions = $('#customize-footer-actions');
        var $customize_theme_controls = $('#customize-theme-controls');
        var $customize_control = $customize_theme_controls.find('li.customize-control');
        var $ag_theme_tablet = $customize_theme_controls.find('li.ag_theme_tablet');
        var $ag_theme_phone = $customize_theme_controls.find('li.ag_theme_phone');
        var AG_THEME_NAME_OPTION;

        if (typeof controlsCustomizerData === 'undefined') {
            AG_THEME_NAME_OPTION = 'AGtheme';
        } else {
            AG_THEME_NAME_OPTION = controlsCustomizerData.AG_THEME_NAME_OPTION;
        }

        $('#customize-controls').find('.control-section').on('click', function () {
            $('#customize-controls').find('#customize-control-' + AG_THEME_NAME_OPTION + '-check_customize').hide();

            //hide show customizer option in layouts
            var select_layout_style = $('#customize-control-AGtheme-select_layout_style input.ag_value_option ').val();
            if (select_layout_style !== 'Custom') {
                $('#customize-controls .template_option').removeClass('hide');
            } else {
                $('#customize-controls .template_option').addClass('hide');
            }
        });

        var hideShowOption = function ($control, $impactObjects, display) {
            if (typeof $control !== 'undefined') {
                if ($control.prop("checked") !== display) {
                    $.each($impactObjects, function (i, v) {
                        v.hide().addClass('hide');
                    });
                }
                $control.change(function () {
                    if ($(this).prop("checked") !== display) {
                        $.each($impactObjects, function (i, v) {
                            v.hide().addClass('hide');
                        });
                    } else {
                        $.each($impactObjects, function (i, v) {
                            v.show().removeClass('hide');
                        });
                    }
                });
            }
            return false;
        };

        var hideShowOptionRadio = function ($control, $checkedControl, $impactObjects, display) {
            if (typeof $control !== 'undefined') {
                var control_value = $checkedControl.val();
                if (control_value !== display) {
                    $.each($impactObjects, function (i, v) {
                        v.hide().addClass('hide');
                    });
                }
                $control.on('change', function (e) {
                    var control_value = $(e.target).val();
                    if (control_value !== display) {
                        $.each($impactObjects, function (i, v) {
                            v.hide().addClass('hide');
                        });
                    } else {
                        $.each($impactObjects, function (i, v) {
                            v.show().removeClass('hide');
                        });
                    }
                });
            }
            return false;
        };

        var firstSelect = false;
        $('#customize-controls').find('#accordion-panel-ag_theme_general_header_menus').on('click', function () {
            if (!firstSelect) {
                hideShowOption(
                    $('#customize-control-' + AG_THEME_NAME_OPTION + '-fixed_navigation').find('input'),
                    {},
                    true
                );

                hideShowOption(
                    $('#customize-control-' + AG_THEME_NAME_OPTION + '-sticky_header').find('input'),
                    {
                        0: $('#accordion-section-ag_theme_general_sticky_menu_layout'),
                        1: $('#customize-control-' + AG_THEME_NAME_OPTION + '-sticky_background_color'),
                        2: $('#customize-control-' + AG_THEME_NAME_OPTION + '-sticky_header_height'),
                        3: $('#customize-control-' + AG_THEME_NAME_OPTION + '-sticky_logo_width'),
                        4: $('#customize-control-' + AG_THEME_NAME_OPTION + '-sticky_image_upload')
                    },
                    true
                );

            }
            firstSelect = true;
        });

        var generalSettingFirstSelect = false;
        $('#customize-controls').find('#accordion-panel-ag_theme_general_settings').on('click', function () {
            if (!generalSettingFirstSelect) {

                hideShowOption(
                    $('#customize-control-' + AG_THEME_NAME_OPTION + '-load_sidebar').find('input'),
                    {
                        0: $('#customize-control-' + AG_THEME_NAME_OPTION + '-sidebar_position'),
                    },
                    true
                );

            }
            generalSettingFirstSelect = true;
        });

        $('#customize-control-' + AG_THEME_NAME_OPTION + '-sticky_header').find('input').on('click', function () {
            if ($(this).prop("checked") === true) {
                $('#customize-control-' + AG_THEME_NAME_OPTION + '-fixed_navigation').find('input').prop("checked", false).change();
            }
        });
        $('#customize-control-' + AG_THEME_NAME_OPTION + '-fixed_navigation').find('input').on('click', function () {
            if ($(this).prop("checked") === true) {
                $('#customize-control-' + AG_THEME_NAME_OPTION + '-sticky_header').find('input').prop("checked", false).change();
            }
        });

        //display site(mobile| tablet| desktop)
        function computerPhoneTable(device) {
            if (device === 'desktop') {
                $customize_control.css("display", "list-item");
                $ag_theme_tablet.hide();
                $ag_theme_phone.hide();
            } else if (device === 'tablet') {
                $ag_theme_tablet.css("display", "list-item");
                $ag_theme_tablet.each(function () {
                    var customize = $(this).data('customize');
                    $('#customize-control-' + AG_THEME_NAME_OPTION + '-phone_' + customize).hide();
                    $('#customize-control-' + AG_THEME_NAME_OPTION + '-' + customize).hide();
                });
            } else {
                $ag_theme_phone.css("display", "list-item");
                $ag_theme_phone.each(function () {
                    var customize = $(this).data('customize');
                    $('#customize-control-' + AG_THEME_NAME_OPTION + '-tablet_' + customize).hide();
                    $('#customize-control-' + AG_THEME_NAME_OPTION + '-' + customize).hide();
                });
            }
        }

        computerPhoneTable('desktop');

        //when change display site
        $customize_footer_actions.find('button[type="button"]').click(function (e) {
            e.preventDefault();
            computerPhoneTable($(this).data('device'));
        });

        function agThemeCustomizeGetCookie(cname, value, reset) {
            if (typeof cname !== 'undefined') {
                var ca = document.cookie.split(';');
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
                    }
                }
            }
            return '';
        }

        /*function reset max width of logo_width when change height header*/
        function change_max_logo_width(data) {
            if (data === 'AGtheme[header_height]'
                || data === 'AGtheme[logo_width]') {
                if ($('#customize-control-' + AG_THEME_NAME_OPTION + '-img_upload img.attachment-thumb').length > 0) {
                    var $logo_thumbnail = $('#customize-control-' + AG_THEME_NAME_OPTION + '-img_upload img.attachment-thumb');
                    var width_height_logo = $logo_thumbnail.width() / $logo_thumbnail.height();
                } else {
                    var width_height_logo = 102 / 32;
                }
                var $input = $('#customize-control-AGtheme-logo_width').find('input.ag-pb-range-input');
                var maxHeight = $('#customize-control-AGtheme-header_height').find('input.ag-pb-range-input').val();
            } else if (data === 'AGtheme[sticky_header_height]'
                || data === 'AGtheme[sticky_logo_width]') {
                if ($('#customize-control-' + AG_THEME_NAME_OPTION + '-sticky_image_upload img.attachment-thumb').length > 0) {
                    var $logo_thumbnail = $('#customize-control-' + AG_THEME_NAME_OPTION + '-img_upload img.attachment-thumb');
                    var width_height_logo = $logo_thumbnail.width() / $logo_thumbnail.height();
                } else if ($('#customize-control-' + AG_THEME_NAME_OPTION + '-img_upload img.attachment-thumb').length > 0) {
                    var $logo_thumbnail = $('#customize-control-' + AG_THEME_NAME_OPTION + '-img_upload img.attachment-thumb');
                    var width_height_logo = $logo_thumbnail.width() / $logo_thumbnail.height();
                } else {
                    var width_height_logo = 102 / 32;
                }
                var $input = $('#customize-control-AGtheme-sticky_logo_width').find('input.ag-pb-range-input');
                var maxHeight = $('#customize-control-AGtheme-sticky_header_height').find('input.ag-pb-range-input').val();
            }
            $input.attr({"max" : parseInt(width_height_logo * maxHeight)});
            $input.prev().attr({"max" : parseInt(width_height_logo * maxHeight)});
            return false;
        }

        $(document).on('mousedown', 'input[type=range]', function () {
            var $range_input = $(this).parent().children('.ag-pb-range-input');
            var data = $(this).data('customize-setting-link');
            var value = $(this).attr('value');
            $range_input.val(value);
            if (data === 'AGtheme[header_height]'
                || data === 'AGtheme[logo_width]'
                ||data === 'AGtheme[sticky_header_height]'
                || data === 'AGtheme[sticky_logo_width]') {
                change_max_logo_width(data);
            }

            $(this).mousemove(function () {
                value = $(this).attr('value');
                $range_input.val(value);
                if (data === 'AGtheme[header_height]'
                    || data === 'AGtheme[logo_width]'
                    ||data === 'AGtheme[sticky_header_height]'
                    || data === 'AGtheme[sticky_logo_width]') {
                    change_max_logo_width(data);
                }
            });
        });

        var range_input_number_timeout;

        function autocorrect_range_input_number(input_number, timeout) {
            var $range_input = input_number,
                $range = $range_input.parent().find('input[type="range"]'),
                value = parseFloat($range_input.val()),
                reset = parseFloat($range.attr('data-reset_value')),
                step = parseFloat($range_input.attr('step')),
                min = parseFloat($range_input.attr('min')),
                max = parseFloat($range_input.attr('max'));

            clearTimeout(range_input_number_timeout);

            range_input_number_timeout = setTimeout(function () {
                var data = $range.data('customize-setting-link');

                if (isNaN(value)) {
                    $range_input.val(reset);
                    $range.val(reset).trigger('change');
                    return;
                }

                if (step >= 1 && value % 1 !== 0) {
                    value = Math.round(value);
                    $range_input.val(value);
                    $range.val(value);
                }

                if (value > max) {
                    $range_input.val(max);
                    $range.val(max).trigger('change');
                }

                if (value < min) {
                    $range_input.val(min);
                    $range.val(min).trigger('change');
                }
                if (data === 'AGtheme[header_height]'
                    || data === 'AGtheme[logo_width]'
                    || data === 'AGtheme[sticky_header_height]'
                    || data === 'AGtheme[sticky_logo_width]') {
                    change_max_logo_width(data);
                }
            }, timeout);

            $range.val(value).trigger('change');
        }

        //
        $('input.ag-pb-range-input').on('change keyup', function () {
            autocorrect_range_input_number($(this), 1000);
        }).on('focusout', function () {
            autocorrect_range_input_number($(this), 0);
        });

        // click reset value input
        $customize_control.find('.ag_theme_reset').on('click', function () {
            // var attachment = wp.media.shibaMediaManager.frame.state().get('selection').first();

            // var myurl = attachment.attributes.url;
            // var mypid = attachment.attributes.id;
            // console.log(myurl, mypid);
            var $input_reset = $(this).siblings('.ag-pb-range-input');
            var value = $(this).siblings('input[type=range]').data('reset_value');
            $input_reset.val(value);
            autocorrect_range_input_number($input_reset, 1000);
        });

        //click ag_select_type option style
        $customize_control.find('.ag_select_type').on('click', function () {
            var valueThis = $(this).siblings('.ag_value_option').val();
            if ($(this).hasClass('none')) {
                // if (valueThis !== '|' + $(this).data('value')) {
                    $(this).siblings('.ag_theme_checked').removeClass('ag_theme_checked');
                    $(this).addClass('ag_theme_checked');
                    valueThis = '|' + $(this).data('value');
                    $(this).siblings('.ag_value_option').val(valueThis).trigger('change');
                // }
            } else if ($(this).hasClass('material-icons')) {
                if ($(this).hasClass('ag_theme_checked')) {
                    $(this).removeClass('ag_theme_checked');
                    valueThis = valueThis.replace('|' + $(this).data('value'), '');
                    $(this).siblings('.ag_value_option').val(valueThis).trigger('change');
                } else {
                    $(this).addClass('ag_theme_checked');
                    valueThis = valueThis + '|' + $(this).data('value');
                    $(this).siblings('.ag_value_option').val(valueThis).trigger('change');
                }
            } else {
                if (valueThis !== $(this).data('value')) {
                    $(this).siblings('.ag_theme_checked').removeClass('ag_theme_checked');
                    $(this).addClass('ag_theme_checked');
                    valueThis = $(this).data('value');
                    $(this).siblings('.ag_value_option').val(valueThis).trigger('change');
                }
            }
        });

        //click search font google
        var autocomplete_texts = function () {
            var $search_option = $('.ag_theme_select').find('.seach-option');
            var text = '';
            var searchWait;
            var data = [];

            $search_option.each(function () {
                if ($(this).parents('.customize-control-select_option_font').length > 0) {
                    data['data_font'] = [];
                    data['data_font'] = Object.keys(controlsCustomizerData.data_font);
                } else if ($(this).parents('.select_layout_style').length > 0) {
                    data['select_layout_style'] = [];
                    $(this).parents('.ag_theme_select').find('.ag_theme_option').each(function () {
                        data['select_layout_style'].push($(this).data('value'));
                    });
                }
                $(this).on("keyup", function (v) {
                    var that = $(v.target);
                    var data_search = [];
                    if (that.parents('.customize-control-select_option_font').length > 0) {
                        data_search = data['data_font'];
                    } else {
                        data_search = data['select_layout_style'];
                    }
                    text = $(this).val();
                    clearTimeout(searchWait);
                    searchWait = setTimeout(function () {
                        if (text === '') {
                            that.parents('.ag_theme_select').find('.ag_theme_option').show();
                        } else {
                            that.parents('.ag_theme_select').find('.ag_theme_option').hide();
                            for (var i = 0 in data_search) {
                                if (data_search[i].toLowerCase().indexOf(text.toLowerCase()) > -1) {
                                    that.parents('.ag_theme_select').find('.ag_theme_option[data-value="' + data_search[i] + '"]').show();
                                }
                            }
                        }
                    }, 500);
                });
            });
        };

        autocomplete_texts();

        var preview_font = function () {
            var $preview = $('.preview_font');
            $preview.each(function () {
                $(this).on("click", function () {
                    var font = $(this).parents('label').siblings('.ag_value_option').val();
                    font = font.replace(' ', '+');
                    window.open("https://fonts.google.com/specimen/" + font);
                })
            });
        };

        preview_font();

        $customize_control.find('.ag_theme_select_span').on('click', function (e) {
            $(this).hide();

            $(this).siblings('.ag_theme_select').css('display', 'block');
        });

        //click ag_select_type option style
        $customize_control.find('.ag_theme_select').find('.ag_theme_option').on('click', function (e) {
            $(this).siblings('.ag_theme_option').show();
            var data_old = 'ag_theme_option_' + $(this).parents('.ag_theme_select').siblings('.ag_value_option').val();

            var value = $(this).data('value');

            if ($(this).parents('.ag_theme_select').siblings('.ag_theme_select_span').hasClass('ag_theme_select_option')) {
                if ($(this).hasClass('ag_theme_option_default')) {
                    $(this).parents('.ag_theme_select').siblings('.ag_theme_select_span').html(agThemeText.defaultThemeFont);
                } else {
                    $(this).parents('.ag_theme_select').siblings('.ag_theme_select_span').html(value);
                }
            } else {
                $(this).parents('.ag_theme_select').siblings('.ag_theme_select_span').removeClass(data_old).addClass('ag_theme_option_' + value);
            }

            $(this).parents('.ag_theme_select').hide();

            $(this).parents('.ag_theme_select').siblings('.ag_theme_select_span').css('display', 'block');

            $(this).parents('.ag_theme_select').siblings('.ag_value_option').val(value).trigger('change');

            if ($(this).parents('.ag_theme_select').hasClass('select_layout_style')) {
                set_template_default_option(value, data_old);
            }
        });

        /*set template default option*/
        function set_template_default_option (template, oldTemplate) {
            var template_option = controlsCustomizerData.data_template_default;
            var oldCustomOption = controlsCustomizerData.oldCustomOption;

            if (typeof template_option[template] !== 'undefined') {
                $.each(template_option[template], function (i, v) {
                    if (i !== 'getCustomizer') {
                        var that = document.querySelector('#customize-control-AGtheme-' + i + ' input[data-customize-setting-link="AGtheme[' + i + ']"]');
                        var oldData = $(that).val();

                        if (typeof oldCustomOption[template] === 'undefined') {
                            oldCustomOption[template] = [];
                        }
                        oldCustomOption[template][i] = typeof oldCustomOption[template][i] !== 'undefined' ? oldCustomOption[template][i] : v;
                        if (typeof $(that).data('default-color') !== 'undefined') {
                            $(that).val(oldCustomOption[template][i]);
                            that.dataset.defaultColor = v;
                            $(that).data('default-color', v);
                            $(that).attr('data-default-color', v);
                            $(that).change();
                        } else if (typeof $(that).data('reset_value') !== 'undefined') {
                            $(that).val(oldCustomOption[template][i]);
                            that.dataset.reset_value = v;
                            $(that).data('reset_value', v);
                            $(that).siblings('.ag-pb-range-input').val(oldCustomOption[template][i]);
                            $(that).change();
                        } else {
                            if (v !== '') { //Enable bind value for option
                                $(that).val('');
                                $(that).trigger('change');
                            }
                            if ('false' == oldCustomOption[template][i]) {
                                $(that).prop("checked", false);
                                oldCustomOption[template][i] = 0;
                            }
                            if ('true' == oldCustomOption[template][i]) {
                                $(that).prop("checked", true);
                                oldCustomOption[template][i] = 1;
                            }

                             if ($(that).parents('li.customize-control-select_option_font').length > 0) {
                                $(that).siblings('.ag_theme_select').find('li[data-value="' + oldCustomOption[template][i] + '"]').trigger('click');
                            } else {
                                $(that).val(oldCustomOption[template][i]);
                                $(that).change();
                            }
                        }
                        oldCustomOption[oldTemplate] = [];
                        oldCustomOption[oldTemplate][i] = oldData;
                    }
                });
            }
        }
    });
})(jQuery);
