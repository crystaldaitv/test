(function ($) {
    $(document).ready(function () {
        var __ = wp.i18n.__;
        var subscribe = wp.data.subscribe;
        var withSelect = wp.data.withSelect;
        var withDispatch = wp.data.withDispatch;

        var $editor = $('#editor');
        var checkExistContent = true;
        var listPost = {};

        /*add color-picker*/
        $('.color-picker').wpColorPicker();

        /*scroll avatar layout*/
        function animate_avatar (){
            var $pack_layout = $editor.find('#pack_layout');
            var that = $pack_layout.find('.layout_avatar').find('div.active');
            if (that.length > 0) {
                var heightParent = that.parent().height();
                var height = that.height() - heightParent;
                if (height > 0) {
                    that.animate({
                        'top': - height + 'px'
                    }, height / 0.14).animate({
                        'top': '0px'
                    }, height / 0.14, animate_avatar);
                }
            }
        }
        function event_layouts(packName, template) {
            var $pack_layout = $editor.find('#pack_layout');
            $pack_layout.find('.layout_avatar').hover(
                function () {
                    $(this).find('.ag_dlib_animate').find('div').addClass('active');
                    animate_avatar();
                }, function () {
                    $(this).find('.ag_dlib_animate').find('div.active').removeClass('active').finish();
                }
            );

            $(template.show).find('.select_template').on('click', function () {
                var post_id = $('#post_ID').val();
                var layout_active = $(this).data('layout');
                var oldContent = 0;

                if ($(template.show).find('.exist_content input:checked').length > 0) {
                    oldContent = 1;
                }

                if (oldContent === 1 && _.size(ag_list_layout.template) > 0 && typeof ag_list_layout.template[packName] === 'undefined') {
                    var answer = alert(agThemeText.messageErrorUseTwo);
                    if (typeof answer !== 'undefined') {
                        template.button_hide.click();
                    }
                    return false;
                } else if (typeof ag_list_layout.customizer.select_layout_style && ag_list_layout.customizer.select_layout_style !== 'Custom') {
                    if (ag_list_layout.customizer.select_layout_style !== packName) {
                        if (confirm(agThemeText.configSelectLayout)) {
                        } else {
                            template.button_hide.click();
                            return false;
                        }
                    }
                }

                $(template.show).css({'z-index': 999});
                $('#loading_animation').show();
                action_create_content_template(post_id, template, packName, layout_active, oldContent);

                return false;
            });
        }

        function action_create_content_template(post_id, template, packName, layout_active, oldContent) {
            $.ajax({
                type: 'POST',
                url: ajaxurl,
                data: {
                    'option_nonce': ag_list_layout.option_nonce,
                    'id': post_id,
                    'pack_active': packName,
                    'layout_active': layout_active,
                    'oldContent': oldContent,
                    'action': 'theme_ag_create_content_template'
                },
                success: function (result) {
                    $('#loading_animation').hide();
                    if (result[0]) {
                        var post_template = result[1].post_meta.post_template.template[packName];
                        var post_meta = result[1].post_meta.post_meta;
                        if (oldContent === 0) {
                            wp.data.dispatch('core/editor').resetBlocks([]);
                            changer_layouts_used(packName, post_template, [], result[1].layout_active);
                        } else {
                            if (typeof ag_list_layout.template[packName] === 'undefined') {
                                changer_layouts_used(packName, post_template, [], result[1].layout_active);
                            } else {
                                changer_layouts_used(packName, post_template, ag_list_layout.template[packName].split('|'), result[1].layout_active);
                            }
                        }

                        if (post_meta !== '') {
                            $.each(post_meta, function (i, v) {
                                switch (i) {
                                    case 'page_header_style':
                                        $('#page_header_style').val(v).trigger('change');
                                        $('#countries option[value=' + v + ']').prop({selected: true}).attr('selected', 'selected').trigger('change');
                                        break;
                                    case 'page_header_layout':
                                        $('#page_header_layout').val(v).trigger('change');
                                        $('#countries option[value=' + v + ']').prop({selected: true}).attr('selected', 'selected').trigger('change');
                                        break;
                                    case 'page_top_content':
                                        $('#page_top_content').val(v).trigger('change');
                                        $('#countries option[value=' + v + ']').prop({selected: true}).attr('selected', 'selected').trigger('change');
                                        break;
                                    case 'header_divider':
                                        if (v === '1') {
                                            $('input[name="header_divider"]').val(v).prop("checked", true).trigger('change');
                                        } else {
                                            $('input[name="header_divider"]').val(v).prop("checked", false).trigger('change');
                                        }
                                        break;
                                    case 'page_title_content':
                                        if (v === '1') {
                                            $('input[name="page_title_content"]').val(v).prop("checked", true).trigger('change');
                                        } else {
                                            $('input[name="page_title_content"]').val(v).prop("checked", false).trigger('change');
                                        }
                                        break;
                                    case 'active_link_color':
                                        $('input[name="active_link_color"]').val(v).trigger('change');
                                        break;
                                    case 'header_background_color':
                                        $('input[name="header_background_color"]').val(v).trigger('change');
                                        break;
                                    case 'menu_background_color':
                                        $('input[name="menu_background_color"]').val(v).trigger('change');
                                        break;
                                    case 'text_color':
                                        $('input[name="text_color"]').val(v).trigger('change');
                                        break;
                                    default :
                                        break;
                                }
                            });
                        }
                        var content = wp.blocks.parse(result[1].content);
                        wp.data.dispatch('core/editor').insertBlocks(content);

                        wp.data.dispatch('core/editor').editPost({template: 'theme_ju_template.php'});
                    } else {
                        console.log(result);
                    }
                    $(template.show).css({'z-index': 99999});
                    $(template.show).find('#preview').find('a.layout_avatar').remove();
                    $("#preview").data('layout', '');
                    template.button_hide.click();
                },
                error: function (result) {
                    $('#loading_animation').hide();
                    $(template.show).css({'z-index': 99999});
                    $(template.show).find('#preview').find('a.layout_avatar').remove();
                    $("#preview").data('layout', '');
                    template.button_hide.click();
                }
            });
        }

        function event_pack(packName, template) {
            var layoutTemplate = ag_list_layout.layoutTemplate[packName];
            var length = ag_list_layout.used.length;
            var popup = '';
            $.each(layoutTemplate.layout_template, function (i, v){
                if (ag_list_layout.post_type === v.type) {
                    popup += '<a class="layout_avatar">';
                    popup += '<div>';
                    if ($.inArray(v.name, ag_list_layout.used[length - 1]) >= 0) {
                        popup += '<input type="button" class="false select_template" data-layout="' + v.name + '" value="' + agThemeText.LOADED + '">';
                        popup += '</input>';
                    } else {
                        popup += '<input type="button" class="true select_template" data-layout="' + v.name + '" value="' + agThemeText.importLayout + '">';
                        popup += '</input>';
                    }
                    popup += '</div>';
                    popup += '<div class="ag_dlib_animate">';
                    popup += '<div><image src="' + v.src + '"/></div>';
                    popup += '</div>';
                    var name = i.replace('_', ' ');
                    name = name.toLowerCase().replace(/\b[a-z]/g, function(letter) {
                        return letter.toUpperCase();
                    });
                    popup += '<div class="title"><h4>' + name + ' ' + agThemeText.Layout + '</h4></div>';
                    popup += '</a>';
                }
            });
            $("#preview").append(popup).data('layout', packName);
            event_layouts(packName, template);
        }

        function changer_layouts_used(packName, template_data_post, old_layouts, new_layouts) {
            ag_list_layout.template = $.extend({}, {});
            if (typeof ag_list_layout.template[packName] === 'undefined') {
                ag_list_layout.template[packName] = '';
            }
            ag_list_layout.template[packName] = template_data_post;

            var length = ag_list_layout.used.length;
            ag_list_layout.used[length] = old_layouts.concat(new_layouts);
            return true;
        }

        function onclickbutton(template) {
            template.button_show.onclick = function () {
                var $preview = $('#preview');
                template.button_show.classList.add("hide");
                template.button_hide.classList.remove("hide");
                document.getElementById("over_pack_layout").classList.remove("hide");
                template.show.classList.remove("hide");
                // $(template.show).height($('#over_pack_layout').height() * 0.8);

                //search layout packet function
                document.getElementById('search_name_packet').onkeyup = function (event) {
                    var listPacket, txtValue, element;
                    txtValue = event.target.value.toUpperCase();
                    listPacket = document.getElementById('list_pack');
                    element = listPacket.getElementsByTagName('a');
                    for (i = 0; i < element.length; i++) {
                        if (element[i].dataset.name.toUpperCase().indexOf(txtValue) > -1) {
                            element[i].style.display = "inline-block";
                        } else {
                            element[i].style.display = "none";
                        }
                    }
                };
                if (ag_list_layout.used.length > 0) {
                    var packet = Object.keys(ag_list_layout.template);

                    /*remove old layout when other selector*/
                    if (typeof $preview.data('layout') === 'undefined' ||  $preview.data('layout') === '' || $preview.data('layout') !== packet[0]) {
                        $preview.contents().remove();
                        event_pack(packet[0], template);
                    }
                }

                /*get list existing page to add template*/
                $.ajax({
                    type: 'POST',
                    url: ajaxurl,
                    data: {
                        'option_nonce': ag_list_layout.option_nonce,
                        'action': 'theme_ag_get_list_page'
                    },
                    success: function (result) {
                        if (result[1] !== null) {
                            listPost = $.extend({}, result[1]);
                            var $list_page = $(template.show).find('#list_pages div:eq(1)');
                            $list_page.contents().remove();

                            // ag_list_layout.post_type
                            if (typeof listPost !== 'undefined') {
                                $html = '';
                                $html += '<table>';
                                $html += '<thead>';
                                $html += '<tr>';
                                $html += '<th scope="col" class="post_type_thead">Title</th>';
                                $html += '<th scope="col" class="statust_thead">Template</th>';
                                $html += '<th scope="col" class="statust_thead">Status</th>';
                                $html += '<th scope="col" class="public_thead">Published</th>';
                                $html += '</tr>';
                                $html += '</thead>';
                                $html += '<tbody>';
                                $.each(listPost, function (i, v) {
                                    if (ag_list_layout.post_type === v.post_type) {
                                        var template = typeof v.templates.template !== 'undefined' ? Object.keys(v.templates.template)[0] : Object.keys(v.templates)[0];
                                        $html += '<tr data-id="' + v.ID + '">';
                                        $html += '<th scope="col" class="post_type">' + v.post_title + '</th>';
                                        $html += '<th scope="col" class="post_type">' + ag_list_layout.layoutTemplate[template].name + '</th>';
                                        $html += '<th scope="col" class="statust">' + v.post_status + '</th>';
                                        $html += '<th scope="col" class="public">' + v.post_modified + '</th>';
                                        $html += '</tr>';
                                    }
                                });
                                $html += '</tbody>';
                                $html += '</table>';
                                $list_page.append($html);
                            }
                            console.log(listPost);

                            /*set template to post by exist post*/
                            setContentPage();
                        }
                    },
                    error: function (result) {
                        console.log(result);
                    }
                });

                $(template.show).find('#select_page li').on('click', function () {
                    $(this).addClass('active');
                    $(this).siblings('li').removeClass('active');
                    if ($(this).hasClass('get_pages')) {
                        $(template.show).find('#list_pages').show();
                        $(template.show).find('#select_templates').hide();
                    } else if ($(this).hasClass('get_templates')) {
                        $(template.show).find('#list_pages').hide();
                        $(template.show).find('#select_templates').show();
                    }
                });

                $(template.show).find('#select_page li.get_templates').trigger('click');
            };

            (setContentPage = function () {
                var $list_pages = $('#list_pages');
                $list_pages.find('tbody tr').on('click', function () {
                    var id = $(this).data('id');
                    if (typeof listPost[id] !== 'undefined') {
                        var content = typeof listPost[id].templates.template !== 'undefined' ? listPost[id].templates.template : listPost[id].templates;

                        wp.data.dispatch('core/block-editor').resetBlocks([]);
                        var key = Object.keys(content);
                        changer_layouts_used(key[0], content[key[0]], [], content[key[0]]);
                        wp.data.dispatch('core/block-editor').insertBlocks(wp.blocks.parse(listPost[id].post_content));
                        wp.data.dispatch('core/editor').editPost({template: 'theme_ju_template.php'});

                        //save new content and data of pages (It is not necessary if we can created block container)
                        $.ajax({
                            type: 'POST',
                            url: ajaxurl,
                            data: {
                                'option_nonce': ag_list_layout.option_nonce,
                                'post_type': listPost[id].post_type,
                                'ID': listPost[id].ID,
                                'id_post': $('#post_ID').val(),
                                'action': 'theme_ag_add_new_content_page'
                            },
                            success: function (result) {
                                $(template.button_hide).trigger('click');
                                if (result[0]) {
                                    var post_meta = result[1].post_meta;
                                    if (post_meta !== '') {
                                        $.each(post_meta, function (i, v) {
                                            switch (i) {
                                                case 'page_header_style':
                                                    $('#page_header_style').val(v).trigger('change');
                                                    $('#countries option[value=' + v + ']').prop({selected: true}).attr('selected', 'selected').trigger('change');
                                                    break;
                                                case 'page_header_layout':
                                                    $('#page_header_layout').val(v).trigger('change');
                                                    $('#countries option[value=' + v + ']').prop({selected: true}).attr('selected', 'selected').trigger('change');
                                                    break;
                                                case 'page_top_content':
                                                    $('#page_top_content').val(v).trigger('change');
                                                    $('#countries option[value=' + v + ']').prop({selected: true}).attr('selected', 'selected').trigger('change');
                                                    break;
                                                case 'header_divider':
                                                    if (v === '1') {
                                                        $('input[name="header_divider"]').val(v).prop("checked", true).trigger('change');
                                                    } else {
                                                        $('input[name="header_divider"]').val(v).prop("checked", false).trigger('change');
                                                    }
                                                    break;
                                                case 'page_title_content':
                                                    if (v === '1') {
                                                        $('input[name="page_title_content"]').val(v).prop("checked", true).trigger('change');
                                                    } else {
                                                        $('input[name="page_title_content"]').val(v).prop("checked", false).trigger('change');
                                                    }
                                                    break;
                                                case 'active_link_color':
                                                    $('input[name="active_link_color"]').val(v).trigger('change');
                                                    break;
                                                case 'header_background_color':
                                                    $('input[name="header_background_color"]').val(v).trigger('change');
                                                    break;
                                                case 'menu_background_color':
                                                    $('input[name="menu_background_color"]').val(v).trigger('change');
                                                    break;
                                                case 'text_color':
                                                    $('input[name="text_color"]').val(v).trigger('change');
                                                    break;
                                                default :
                                                    break;
                                            }
                                        });
                                    }
                                }
                                console.log(result);
                            },
                            error: function (result) {
                                $(template.button_hide).trigger('click');
                                console.log(result);
                            }
                        });
                    }
                });
            })();

            $(template.show).find('#select_pack .layout_avatar').on('click', function () {
                $("#preview").contents().remove();
                event_pack($(this).data('name'), template);
            });

            template.button_hide.onclick = function () {
                template.button_show.classList.remove("hide");
                template.button_hide.classList.add("hide");
                template.show.classList.add("hide");
                document.getElementById("over_pack_layout").classList.add("hide");
            };

            $('body').click(function(event) {
                if ($(template.show).is(":visible")) {
                    if ($('#loading_animation').is(":visible")) {
                        return;
                    }
                    if (!$(event.target).is($(template.button_show)) && !$(event.target).parents().is($('#pack_layout')) && !$(event.target).is($(template.show)) || $(event.target).is($(template.button_hide))) {
                        $(template.button_hide).trigger('click');
                    }
                }
            });
        }

        ag_add_template = subscribe(function () {
            if (document.getElementById("ag_add_template") === null && $(".edit-post-header-toolbar").length > 0) {
                var button_show = '<button id="ag_show_template_list">' + agThemeText.advancedGutenbergLayouts + '</button>';
                var button_hide = '<button id="ag_hide_template_list" class="hide">' + agThemeText.hideAdvancedGutenbergLayouts + '</button>';
                $('<div id="ag_add_template">' + button_show + button_hide + '</div>').insertAfter('.edit-post-header-toolbar');

                if (typeof ag_list_layout !== 'undefined' && typeof ag_list_layout.option_nonce !== 'undefined' && ag_list_layout.layoutTemplate !== '') {
                    var popup = '<div><div id="over_pack_layout" class="hide"><div id="loading_animation"></div></div><div id="pack_layout" class="hide">';
                    popup += '<div id="select_page">';
                    popup += '<ul><li class="get_templates"><a>' + agThemeText.getTemplates + '</a></li><li class="get_pages"><a>' + agThemeText.getPages + '</a></li></ul>';
                    popup += '</div>';
                    popup += '<div id="select_templates">';
                    popup += '<div id="select_pack">';
                    popup += '<div id="search_pack">';
                    popup += '<div class="search_pack"><input type="text" id="search_name_packet" placeholder="' + agThemeText.searchLayoutPacket + '"></div>';
                    popup += '<div class="exist_content"><input type="checkbox"><span>' + agThemeText.keepExistingContent + '</span></div>';
                    popup += '</div>';
                    popup += '<div id="list_pack">';

                    if (typeof ag_list_layout.used === 'undefined') {
                        ag_list_layout.used = [];
                    }

                    $.each(ag_list_layout.layoutTemplate, function (i, v) {
                        var length = ag_list_layout.used.length;

                        if (typeof ag_list_layout.template[i] !== 'undefined') {
                            ag_list_layout.used[length] = ag_list_layout.template[i].split('|');
                        }

                        popup += '<a class="layout_avatar" data-name="' + i + '">';
                        popup += '<div class="ag_dlib_animate">';
                        popup += '<div><image src="' + v.src + '"/></div>';
                        popup += '</div>';
                        var name = i.replace('_', ' ');
                        name = name.toLowerCase().replace(/\b[a-z]/g, function(letter) {
                            return letter.toUpperCase();
                        });
                        popup += '<div class="title"><h4>' + name + '</h4></div>';
                        popup += '</a>';

                    });
                    popup += '</div>';
                    popup += '</div>';

                    popup += '<div id="preview"></div>';
                    popup += '</div>';
                    popup += '<div id="list_pages"><div></div><div></div></div>';
                    popup += '</div></div>';
                    $(popup).insertAfter('#editor .components-drop-zone__provider');

                    var template = {
                        button_show: document.getElementById("ag_show_template_list"),
                        button_hide: document.getElementById("ag_hide_template_list"),
                        show: document.getElementById("pack_layout")
                    };
                    onclickbutton(template);
                }
            }

            //todo: check post have block, when hasn't block return function render option post
            checkExistContent = wp.data.select('core/editor').getEditedPostContent() !== '';
            if (!checkExistContent) {
            }
            if ($('.edit-post-meta-boxes-area').find('input[name="page_top_content"]').length > 0) {
                // console.log($('.edit-post-meta-boxes-area').find('input[name="page_top_content"]'));
                // $('.edit-post-meta-boxes-area').find('input[name="page_top_content"]').on('check')
            }
            return false;
        });

    });
})(jQuery);
