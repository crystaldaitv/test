<?php
defined('ABSPATH') || die;

require_once AG_THEME_CORE . '/post_edit.php';
require_once AG_THEME_CORE . '/customizer_function.php';

if (!function_exists('ag_get_option')) {
    /**
     * Get option theme
     *
     * @param string       $option        Name option
     * @param string       $name_folder   Name folder theme
     * @param string       $check         Get option/theme mod/full
     * @param string       $optionContent Get content option
     * @param string|array $defaultVal    Default value option
     *
     * @return array|mixed|string
     */
    function ag_get_option($option, $name_folder, $check = 'full', $optionContent = '', $defaultVal = '')
    {
        $optionVal   = array();
        $optionValue = get_option($option, array());

        if ($check === 'option') {
            $optionVal = $optionValue;
        } elseif ($check === 'themeMods') {
            $optionVal = get_theme_mods();
        } elseif ($check === 'full' || $check === '') {
            $themeModsVal = get_option('theme_mods_' . $name_folder, array());
            $optionVal    = array_merge($optionValue, $themeModsVal);
        }

        $optionVal = wp_change_option_template($optionVal);

        if ($optionContent !== '') {
            $optionArray = explode('.', $optionContent);
            $count       = count($optionArray);
            for ($i = 0; $i < $count; $i ++) {
                if (isset($optionVal[$optionArray[$i]])) {
                    $data = $optionVal[$optionArray[$i]];
                    if (is_array($optionVal[$optionArray[$i]])) {
                        $optionVal = $optionVal[$optionArray[$i]];
                    }
                } else {
                    $data = $defaultVal;
                }
            }
        } else {
            $data = $optionVal;
        }

        return $data;
    }
}

if (!function_exists('wp_change_option_template')) {
    /**
     * Function check before change customize option apply_filters
     *
     * @param array $optionVal Customize option
     *
     * @return array
     */
    function wp_change_option_template($optionVal = array())
    {
        if (isset($optionVal['check_customize']) && $optionVal['check_customize'] === 0) {
            $optionVal = apply_filters('advanced_gutenberg_theme_change_option_template', $optionVal);
        }

        return $optionVal;
    }
}

if (!function_exists('wp_resize_image_upload')) {
    /**
     * Resize image upload
     *
     * @param array  $pathinfoImage Url image
     * @param string $width         Width image
     * @param string $header_height Height of header
     * @param string $name_logo     Name logo image
     *
     * @return integer|string
     */
    function wp_resize_image_upload($pathinfoImage, $width, $header_height, $name_logo)
    {
        $folderImageLogoDir = AG_THEME_UPLOAD_FOLDERDIR . '/logo/';
        $folderImageLogoUri = AG_THEME_UPLOAD_FOLDERURL . '/logo/';

        if ($name_logo === 'sticky_logo') {
            $folderImageLogoDir = AG_THEME_UPLOAD_FOLDERDIR . '/sticky_logo/';
            $folderImageLogoUri = AG_THEME_UPLOAD_FOLDERDIR . '/sticky_logo/';
        }

        $originalUrl = $pathinfoImage['dirname'] . '/' . $pathinfoImage['basename'];
        $metadata    = getimagesize($originalUrl);

        /*create new logo image have size 100px, 200px, 300px, 400px, 500px*/
        if (is_array($metadata)) {
            $after_name = $width . '_' . $header_height;

            ag_create_thumbs($pathinfoImage, $metadata, (int) $width, 0, $folderImageLogoDir, $folderImageLogoUri, $after_name);

            if ((int) $width > 100) {
                $after_name = '100_' . $header_height;
                ag_create_thumbs($pathinfoImage, $metadata, 100, 0, $folderImageLogoDir, $folderImageLogoUri, $after_name);
            }

            if ((int) $width > 300) {
                $after_name = '300_' . $header_height;
                ag_create_thumbs($pathinfoImage, $metadata, 300, 0, $folderImageLogoDir, $folderImageLogoUri, $after_name);
            }

            if ((int) $width > 500) {
                $after_name = '500_' . $header_height;
                ag_create_thumbs($pathinfoImage, $metadata, 500, 0, $folderImageLogoDir, $folderImageLogoUri, $after_name);
            }

            return $metadata[0];
        }

        return 0;
    }
}

if (!function_exists('ag_create_thumbs')) {
    /**
     * Render image by width
     *
     * @param array   $pathinfoImage Path info image
     * @param array   $metadata      Data image
     * @param integer $width         Width render image
     * @param integer $height        Height render image
     * @param string  $folderDir     Dir folder logo upload data
     * @param string  $folderUri     Uri folder logo upload data
     * @param string  $afterName     String width_height of image
     *
     * @return string|boolean
     */
    function ag_create_thumbs($pathinfoImage, $metadata, $width, $height, $folderDir, $folderUri, $afterName)
    {
        if ($metadata[0] === 0) {
            return false;
        }

        if ($width !== 0) {
            $new_width = $width;
        } else {
            return false;
        }

        if ($height !== 0) {
            $new_height = $height;
        } else {
            $new_height = floor($metadata[1] * $new_width / $metadata[0]);
        }

        if ($afterName === '') {
            $afterName = $new_width . '_' . $new_height;
        }

        $filePath = $pathinfoImage['dirname'] . '/' . $pathinfoImage['basename'];

        $newNameImage = wp_basename($filePath, '.' . $pathinfoImage['extension'])
                        . '-' . $afterName . '.' . $pathinfoImage['extension'];
        $urlReturn    = $folderDir . '/' . $newNameImage;

        if (!file_exists($folderDir)) {
            mkdir($folderDir, 0777, true);
        }

        if (file_exists($urlReturn)) {
            return $folderUri . '/' . $newNameImage;
        }

        include_once AG_THEME_INCLUDES_URL . '/wideimage/WideImage.php';
        try {
            $image = WideImage::load($filePath)->resize($new_width, $new_height, 'inside', 'any');
            if (gettype($metadata['mime']) !== 'string') {
                return false;
            }
            if ($metadata['mime'] === 'image/jpeg') {
                $image->saveToFile($urlReturn, 90);
            } elseif ($metadata['mime'] === 'image/png') {
                $image->saveToFile($urlReturn, 8, PNG_NO_FILTER);
            } elseif ($metadata['mime'] === 'image/gif') {
                $image->saveToFile($urlReturn);
            } else {
                return false;
            }

            return $folderUri . '/' . $newNameImage;
        } catch (Exception $e) {
            return false;
        }
    }
}

if (!function_exists('theme_option_setting_callback')) {
    /**
     * Function save custom css
     *
     * @return void
     */
    function theme_option_setting_callback()
    {
        $response = array();
        if (isset($_POST['customized'], $_POST['option_nonce'], $_POST['option_name'])
            && wp_verify_nonce(sanitize_key($_POST['option_nonce']), 'option_nonce')
        ) {
            $customized  = $_POST['customized'];
            $option_name = $_POST['option_name'];
        } else {
            $option_name = AG_THEME_NAME_OPTION;
            $customized  = array('customCss' => '', 'customHead' => '', 'customBody' => '');
        }

        $optionVal = get_option($option_name);
        if (!isset($optionVal)) {
            $optionVal = array();
        }
        $optionVal['check_customize'] = 1;
        if (!isset($optionVal['custom']) || !is_array($optionVal['custom'])) {
            $optionVal['custom'] = array();
        }
        if (isset($customized)) {
            $optionVal['custom'] = array_merge($optionVal['custom'], $customized);
            update_option($option_name, $optionVal);
        }

        $response['optionVal'] = $optionVal;
        wp_send_json($response);
    }

    add_action('wp_ajax_theme_option_setting', 'theme_option_setting_callback');
}

if (!function_exists('theme_option_default_callback')) {
    /**
     * Function save custom css
     *
     * @return void
     */
    function theme_option_default_callback()
    {
        $optionVal = array();
        if (isset($_POST['option_nonce'], $_POST['option_name'], $_POST['theme_folder'])
            && wp_verify_nonce(sanitize_key($_POST['option_nonce']), 'option_nonce')
        ) {
            $option_name = $_POST['option_name'];
            $optionVal   = theme_ag_option_default($optionVal, $option_name, true);
            wp_send_json(array(true, $optionVal));
        } else {
            wp_send_json(array(false, null));
        }
    }

    add_action('wp_ajax_theme_option_default', 'theme_option_default_callback');
}


if (!function_exists('theme_ag_option_default')) {
    /**
     * Function save custom css
     *
     * @param array   $optionVal   Data default customize
     * @param string  $option_name AG_THEME_NAME_OPTION
     * @param boolean $layouts     Reset list layouts
     *
     * @return array
     */
    function theme_ag_option_default($optionVal, $option_name, $layouts = false)
    {
        /*get list option theme*/
        //load default option theme
        require_once(AG_THEME_INCLUDES_URL . '/default-option-theme.php');
        $agDefaultTheme = new AgDefaultTheme();
        $option_style   = $agDefaultTheme->defaultOptionTheme();

        $count = count($option_style);
        for ($i = 0; $i < $count; $i ++) {
            if ($option_style[$i]['name'] === 'custom') {
                $value = $option_style[$i]['value'];
            } elseif (is_array($option_style[$i]['value'])) {
                $value = $option_style[$i]['value'][0];
            } else {
                $value = $option_style[$i]['value'];
            }
            $optionVal[$option_style[$i]['name']] = $value;
        }
        set_theme_mod('background_image', '');
        set_theme_mod('image_upload', '');
        set_theme_mod('background_header_image', '');
        update_option('theme_ju_template_default_option', array());
        update_option('theme_ju_custom_option', array());
        $fileDir   = AG_THEME_UPLOAD_FOLDERDIR . '/style_customize.css';

        if (file_exists($fileDir)) {
            unlink($fileDir);
        }

        /*reset list layouts data*/
        if ($layouts) {
            $theme_ju_template_pack = theme_ju_get_data_layout(true, true);
            $data                   = theme_ju_get_layout_pack(array('installed'));
            if ($theme_ju_template_pack === false) {
                $data['ju_template_pack'] = get_option('theme_ju_template_pack', null);
            } else {
                $data['ju_template_pack'] = $theme_ju_template_pack;
            }

            /*reset status of layouts packet from 2 to 1 when layouts packet folder not exist*/
            $count                                 = count((array) $data['ju_template_pack']);
            $optionVal['custom']['templateFooter'] = array();

            if ($count > 0) {
                $folderTemplates = AG_THEME_UPLOAD_FOLDERURL . '/templates/';
                foreach ($data['ju_template_pack'] as $key => $layout) {
                    if (!in_array($key, $data['installed']) && $layout->status === 2) {
                        $layout->status = 1;
                    } elseif (in_array($key, $data['installed'])) {
                        $layout->status = 2;
                        if (isset($layout->footer)) {
                            $image                                       = $folderTemplates . $key . '/assets/images/' . $layout->footer;
                            $optionVal['custom']['templateFooter'][$key] = array(
                                'url'  => $image,
                                'name' => $layout->name
                            );
                        }
                    }
                }
            }
            update_option('theme_ju_template_pack', $data['ju_template_pack']);
            update_option($option_name, $optionVal);

            $datas = array('optionVal' => $optionVal, 'listLayouts' => $data['ju_template_pack']);

            return $datas;
        } else {
            update_option($option_name, $optionVal);

            return $optionVal;
        }
    }
}

if (!function_exists('theme_font_google')) {
    /**
     * Function add font google for site
     *
     * @param array $optionFont Data option font
     *
     * @return void
     */
    function theme_font_google($optionFont)
    {
        $protocol = is_ssl() ? 'https' : 'http';

        require_once(AG_THEME_INCLUDES_URL . '/google-font-datas.php');
        $data_font = get_saved_google_fonts();
        $count     = count($optionFont);
        $fonts     = '';
        $subset    = array();
        for ($i = 0; $i < $count; $i ++) {
            if (isset($optionFont[$i]) && isset($data_font[$optionFont[$i]])) {
                $fonts .= str_replace(' ', '+', $optionFont[$i]);
                if ($data_font[$optionFont[$i]]['styles'] !== 'regular') {
                    $fonts .= ':' . $data_font[$optionFont[$i]]['styles'];
                }
                $fonts  .= '|';
                $subset = array_merge(explode(',', $data_font[$optionFont[$i]]['character_set']), $subset);
            }
        }

        $fonts = rtrim($fonts, '|');
        wp_enqueue_style(
            'builder-googlefonts',
            esc_url(add_query_arg(array(
                'family' => $fonts,
                'subset' => implode(',', $subset),
            ), $protocol . '://fonts.googleapis.com/css')),
            array(),
            null
        );
    }

    /*filter when save customize*/
    add_filter('theme_font_google', 'theme_font_google', 10, 1);
}

if (!function_exists('theme_ju_get_layout_pack')) {
    /**
     * Function get layout pack list in theme local
     *
     * @param array $getList List option get
     *
     * @return array
     */
    function theme_ju_get_layout_pack($getList = array())
    {
        $data                     = array();
        $data['installed']        = array();
        $data['templatePacket']   = array();
        $data['ju_template_pack'] = new stdClass();
        $pattern                  = '@^((.*?)\\_layouts)$@';
        $folderDir                = AG_THEME_UPLOAD_FOLDERDIR . DIRECTORY_SEPARATOR;
        $templatesDir             = AG_THEME_UPLOAD_FOLDERDIR . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR;
        $templateDir              = AG_THEME_URL . DIRECTORY_SEPARATOR . 'template' . DIRECTORY_SEPARATOR;

        if (!file_exists($folderDir)) {
            mkdir($folderDir, 0777, true);
        } else {
            if (!file_exists($templatesDir)) {
                mkdir($templatesDir, 0777, true);
            }

            if (in_array('installed', $getList)) {
                $list  = scandir($templatesDir, 0);
                $count = count($list);
                for ($i = 0; $i < $count; $i ++) {
                    if (!in_array($list[$i], array('.', '..')) && preg_match($pattern, $list[$i], $matches)) {
                        array_push($data['installed'], $list[$i]);
                    }
                }
            }
        }

        if (!file_exists($templateDir)) {
            mkdir($templateDir, 0777, true);
        } else {
            if (in_array('templatePacket', $getList)) {
                $list  = scandir($templateDir, 0);
                $count = count($list);
                for ($i = 0; $i < $count; $i ++) {
                    if (!in_array($list[$i], array('.', '..')) && preg_match($pattern, $list[$i], $matches)) {
                        array_push($data['templatePacket'], $list[$i]);
                    }
                }
            }
        }

        if (in_array('ju_template_pack', $getList)) {
            $data['ju_template_pack'] = get_option('theme_ju_template_pack', null);
            $count                    = count($data['installed']);
            if ($count > 0) {
                for ($i = 0; $i < $count; $i ++) {
                    if (isset($data['installed'][$i])) {
                        if (isset($data['ju_template_pack']->{$data['installed'][$i]})) {
                            $data['ju_template_pack']->{$data['installed'][$i]}->status = 2;
                        }
                    }
                }
            }
        }

        return $data;
    }
}

if (!function_exists('theme_ju_get_data_layout')) {
    /**
     * Function get data form ju_update
     *
     * @param boolean $reset  Reset list layouts
     * @param boolean $return Return list layouts
     *
     * @return boolean|object
     */
    function theme_ju_get_data_layout($reset = false, $return = false)
    {
        $ju_base = JU_BASE;

        $file = wp_remote_get($ju_base . '/juupdater_files/advg-layouts.json');

        if (!is_wp_error($file) && isset($file['response']['code']) && ($file['response']['code'] === 200)) {
            $body = wp_remote_retrieve_body($file);
            if ($body !== '') {
                $json = json_decode($body, false);
                if ($reset === true) {
                    $theme_ju_template_pack = null;
                } else {
                    $theme_ju_template_pack = get_option('theme_ju_template_pack', null);
                }

                if ($theme_ju_template_pack === null) {
                    $theme_ju_template_pack = new stdClass();
                }
                foreach ($json as $key => $pack) {
                    if (!isset($theme_ju_template_pack->{$key}) || !isset($theme_ju_template_pack->{$key}->status)) {
                        $theme_ju_template_pack->{$key} = $pack;
                    }
                    if ($pack->status === 1 && $theme_ju_template_pack->{$key}->status === 0) {
                        $theme_ju_template_pack->{$key}->status = 1;
                    }
                }

                update_option('theme_ju_template_pack', $theme_ju_template_pack);
                if ($return) {
                    return $theme_ju_template_pack;
                } else {
                    return false;
                }
            }
        }

        return false;
    }
}

if (!function_exists('check_active_plugin')) {
    /**
     * Function check and active advance gutenberg plugin is active
     *
     * @return boolean
     */
    function check_active_plugin()
    {
        if (!function_exists('get_plugins')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        $listPlugin = get_plugins();

        if (!class_exists('AdvancedGutenbergMain')) {
            $checkInstall = false;
            foreach ($listPlugin as $key => $dataPlugin) {
                if (strpos($key, '/advanced-gutenberg.php') !== false) {
                    $checkInstall = true;
                    require_once(ABSPATH . '/wp-admin/includes/file.php');
                    $result = activate_plugin($key);
                    if (is_wp_error($result)) {
                        return false;
                    } else {
                        unset($_GET['deactivate']);
                        add_action(
                            'admin_notices',
                            function () {
                                ?>
                                <div class="notice notice-warning is-dismissible">
                                    <p>
                                        <?php
                                        echo esc_attr__(
                                            'Advanced Gutenberg plugin is required to use your current theme and layouts. Please switch theme fist.',
                                            'advanced-gutenberg-theme'
                                        );
                                        ?>
                                    </p>
                                </div>
                                <?php
                            },
                            1
                        );
                    }
                }
            }

            if (!$checkInstall) {
                add_action(
                    'admin_notices',
                    function () {
                        $gutenbergInstallUrl = wp_nonce_url(
                            add_query_arg(
                                array(
                                    'action' => 'install_plugin',
                                    'plugin' => 'advanced-gutenberg'
                                ),
                                admin_url('update.php')
                            ),
                            'install-plugin_advanced-gutenberg'
                        );
                        ?>
                        <div class="notice notice-success is-dismissible">
                            <p>
                                <?php
                                echo esc_attr__('In order to use all themes features and design layouts, please ', 'advanced-gutenberg-theme')
                                     . '<a href="' . esc_attr($gutenbergInstallUrl) . '">'
                                     . esc_attr__(
                                         'install the Advanced gutenberg plugin',
                                         'advanced-gutenberg-theme'
                                     ) . '</a>';
                                ?>
                            </p>
                        </div>
                        <?php
                    }
                );

                return false;
            }

            return true;
        }

        return true;
    }
}

if (!function_exists('checkLayoutInstalled')) {
    /**
     * Function check packet is installed
     *
     * @param null|object $listPack List layout packets
     *
     * @return boolean
     */
    function checkLayoutInstalled($listPack = null)
    {
        if ($listPack === null) {
            $listPack = get_option('theme_ju_template_pack', null);
        }
        foreach ($listPack as $packet => $value) {
            if ($value->status === 2) {
                return true;
            }
        }

        return false;
    }
}


if (!function_exists('after_theme_update_actions')) {
    /**
     * Return after theme updated, create new page template when first active theme
     *
     * @return void
     */
    function after_theme_update_actions()
    {
        $templatesDir = AG_THEME_UPLOAD_FOLDERDIR
                        . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR;
        if (!file_exists(AG_THEME_URL . DIRECTORY_SEPARATOR . 'template' . DIRECTORY_SEPARATOR)) {
            mkdir(AG_THEME_URL . DIRECTORY_SEPARATOR . 'template' . DIRECTORY_SEPARATOR, 0777, true);
        }
        /*get data layout*/
        $listPack = theme_ju_get_layout_pack(array('ju_template_pack'));

        if ($listPack['ju_template_pack'] === null) {
            $theme_ju_template_pack = theme_ju_get_data_layout(false, true);
            if ($theme_ju_template_pack === false) {
                $listPack['ju_template_pack'] = get_option('theme_ju_template_pack', null);
            } else {
                $listPack['ju_template_pack'] = $theme_ju_template_pack;
            }
            /*create new option theme_ju_template_posts*/
            update_option('theme_ju_template_posts', array());
        }
        $newTemplate = get_option('theme_ju_new_template', null);
        /*check create default option*/
        $option          = ag_get_option(AG_THEME_NAME_OPTION, AG_THEME_FOLDER);
        $folderTemplates = AG_THEME_UPLOAD_FOLDERURL . '/templates/';

        /* auto create default theme option*/
        if (!isset($option['custom'])) {
            $option                             = theme_ag_option_default(array(), AG_THEME_NAME_OPTION);
            $option['custom']['templateFooter'] = array();
        }

        /* auto create default templateFooter option*/
        if (isset($option['custom']['templateFooter']) && $listPack['ju_template_pack']) {
            $count = count($option['custom']['templateFooter']);
            if ($count < 1) {
                $installed = theme_ju_get_layout_pack(array('installed'));
                foreach ($listPack['ju_template_pack'] as $key => $package) {
                    if (in_array($key, $installed['installed'])
                        && isset($package->footer) && !isset($option['custom']['templateFooter'][$key])) {
                        $image                                    = $folderTemplates . $key . '/assets/images/' . $package->footer;
                        $option['custom']['templateFooter'][$key] = array('url' => $image, 'name' => $package->name);
                        update_option(AG_THEME_NAME_OPTION, $option);
                    }
                }
            }
        }

        /* When there is a sample package, check the plugin be activated*/
        if ($listPack['ju_template_pack'] !== null && checkLayoutInstalled($listPack['ju_template_pack'])) {
            $checkGutenberg = check_active_plugin();
        } else {
            $checkGutenberg = false;
        }

        if ($newTemplate !== null && $checkGutenberg) {
            if (isset($listPack['ju_template_pack']->{$newTemplate})) {
                update_option('theme_ju_new_template', null);

                /*footer image*/
                $folderTemplate = $folderTemplates . $newTemplate;
                if (isset($listPack['ju_template_pack']->{$newTemplate}->footer)) {
                    $image                                            =
                        $folderTemplate . '/assets/images/' . $listPack['ju_template_pack']->{$newTemplate}->footer;
                    $option['custom']['templateFooter'][$newTemplate] =
                        array('url' => $image, 'name' => $listPack['ju_template_pack']->{$newTemplate}->name);
                    update_option(AG_THEME_NAME_OPTION, $option);
                }
                $templateActive = array('baseurl' => $folderTemplate, 'name' => $newTemplate);

                $currLayoutArr = explode('_', $templateActive['name']);
                $currLayout = '';
                if (isset($currLayoutArr[1]) && $currLayoutArr[1] === 'layouts') {
                    $currLayout = $currLayoutArr[0];
                }

                $listPack['ju_template_pack']->{$newTemplate}->status = 2;
                update_option('theme_ju_template_pack', $listPack['ju_template_pack']);

                $layoutTemplate = $listPack['ju_template_pack']->{$newTemplate}->layout_template;
                /*check first create post use this template pack*/
                foreach ($layoutTemplate as $name => $template) {
                    $link = $templatesDir . $newTemplate . DIRECTORY_SEPARATOR . $template->name . '.txt';
                    if (file_exists($link)) {
                        $content = theme_ju_create_content_post(
                            $link,
                            $templateActive,
                            null
                        );

                        if (isset($template->category)) {
                            require_once ABSPATH . 'wp-admin/includes/taxonomy.php';
                            $category     = $template->category;
                            $idCategory   = wp_create_category($category);
                            $categoryList = array($idCategory);
                        } else {
                            $categoryList = array();
                        }

                        if ($template->type === 'post') {
                            if ($name === 'post') {
                                $name = 'Sample Post';
                            }
                            $id = theme_ju_create_post(
                                null,
                                $name,
                                $content['content'],
                                'publish',
                                get_current_user_id(),
                                'post',
                                $categoryList
                            );
                        } else {
                            $id = theme_ju_create_post(
                                null,
                                $name,
                                $content['content'],
                                'publish',
                                get_current_user_id(),
                                'page',
                                $categoryList,
                                ucfirst($currLayout)
                            );
                        }

                        if (!is_wp_error($id)) {
                            $id_meta = theme_ju_create_post_meta($id, '_wp_page_template', 'theme_ju_template.php');
                            if ($id_meta !== false) {
                                $templatePostsMeta = get_option('theme_ju_template_posts', array());
                                $valuePostTemplate = array(
                                    'template' => array($newTemplate => $template->name),
                                    'option'   => array(),
                                    'version'  => ''
                                );
                                theme_ju_update_post_meta_val($templatePostsMeta, $id, $valuePostTemplate, $template->type);
                            }
                        } else {
                            update_option('theme_ju_new_template', $newTemplate);
                        }
                    }
                }
            }
        }
    }

    add_action('after_setup_theme', 'after_theme_update_actions');
}

if (!function_exists('theme_ju_update_post_meta_val')) {
    /**
     * Function update option theme_ju_template_posts
     *
     * @param null|array  $templatePostsMeta Data theme_ju_template_posts
     * @param null|string $id                Id post
     * @param array       $template          Data pack id and name template used
     * @param string      $templateType      Type template
     *
     * @return array
     */
    function theme_ju_update_post_meta_val($templatePostsMeta, $id, $template, $templateType)
    {
        if (empty($templatePostsMeta) || $templatePostsMeta === null) {
            $templatePostsMeta = array();
        }

        if (empty($templatePostsMeta[$id])) {
            $templatePostsMeta[$id] = array();
            $post_meta = array();
            $meta = false;
            //add agtheme meta post
            foreach ($template['template'] as $nameLayout => $templateName) {
                $meta = ag_get_layouts_settings($nameLayout, $templateName);
            }
            if ($meta !== false) {
                foreach ($meta as $nameOption => $templateOption) {
                    $post_meta[$nameOption] =  update_post_meta($id, $nameOption, $templateOption);
                }
            }
        } else {
            $meta = '';
        }

        $templatePostsMeta[$id] = array_merge($templatePostsMeta[$id], $template);

        update_option('theme_ju_template_posts', $templatePostsMeta);

        return array('post_template' => $templatePostsMeta[$id], 'post_meta' => $meta);
    }
}

if (!function_exists('theme_install_pack_callback')) {
    /**
     * Function install layout pack
     *
     * @return void
     */
    function theme_install_pack_callback()
    {
        /*check token*/
        $token = get_option('ju_user_token', null);
        if ($token === null) {
            exitStatus(false, 'you not has token');
        }

        if (isset($_POST['option_nonce'], $_POST['slug'], $_POST['pack_name'])
            && wp_verify_nonce(sanitize_key($_POST['option_nonce']), 'option_nonce')
        ) {
            if (!function_exists('wp_handle_upload')) {
                require_once(ABSPATH . 'wp-admin/includes/file.php');
            }

            $link = 'https://www.joomunited.com/index.php?option=com_juupdater&task=download.download&extension=' . $_POST['slug'] . '&token=';

            $file = wp_remote_get($link . $token, array(
                'timeout' => 300
            ));

            $body = wp_remote_retrieve_body($file);
            if (!is_wp_error($file)) {
                $folderDir    = AG_THEME_UPLOAD_FOLDERDIR . DIRECTORY_SEPARATOR;
                $templatesDir = $folderDir . 'templates' . DIRECTORY_SEPARATOR;
                if (!file_exists($folderDir)) {
                    mkdir($folderDir, 0777, true);
                }

                if (!file_exists($templatesDir)) {
                    mkdir($templatesDir, 0777, true);
                }
                $file_path = $templatesDir . $_POST['pack_name'] . '.zip';

                $fp = fopen($file_path, 'w');
                if ($fp !== false) {
                    fwrite($fp, $body);

                    WP_Filesystem();
                    $unzipfile = unzip_file($file_path, $templatesDir);

                    if (is_wp_error($unzipfile)) {
                        unlink($file_path);
                        exitStatus(
                            false,
                            esc_attr__(
                                'Installation failed, have error in the installation!',
                                'advanced-gutenberg-theme'
                            )
                        );
                    }

                    $listPack = get_option('theme_ju_template_pack', null);
                    if ($listPack !== null) {
                        $id_template                      = sanitize_key($_POST['pack_name']);
                        $listPack->{$id_template}->status = 2;
                        update_option('theme_ju_template_pack', $listPack);
                        update_option('theme_ju_new_template', $id_template);
                    }
                    unlink($file_path);
                    ag_add_layoutImages_to_media_library($id_template);
                    exitStatus(
                        true,
                        esc_attr__(
                            'Layout Pack installed',
                            'advanced-gutenberg-theme'
                        )
                    );
                } else {
                    unlink($file_path);
                    exitStatus(
                        false,
                        esc_attr__(
                            'Installation failed, have error in the installation, Cannot read file!',
                            'advanced-gutenberg-theme'
                        )
                    );
                }
                fclose($fp);
            } else {
                exitStatus(
                    false,
                    esc_attr__(
                        'Installation failed, have error in the download!',
                        'advanced-gutenberg-theme'
                    )
                );
            }
        }
        exitStatus(
            false,
            esc_attr__(
                'Installation failed',
                'advanced-gutenberg-theme'
            )
        );
    }

    add_action('wp_ajax_theme_install_pack', 'theme_install_pack_callback');
}

if (!function_exists('ag_delete_files')) {
    /**
     * Function remove all file in $directory
     *
     * @param string $directory Parent folder
     *
     * @return void
     */
    function ag_delete_files($directory)
    {
        if (is_dir($directory)) {
            $files = scandir($directory);
            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..') {
                    ag_delete_files($directory . '/' . $file);
                }
            }
            rmdir($directory);
        } elseif (file_exists($directory)) {
            unlink($directory);
        }
    }
}

if (!function_exists('recurse_copy')) {
    /**
     * Function remove template pack
     *
     * @param string $src Template pack dir
     * @param string $dst Destination template pack dir
     *
     * @return void
     */
    function recurse_copy($src, $dst)
    {
        $dir = opendir($src);

        if (!file_exists($dst)) {
            mkdir($dst);
        }

        while (false !== ($file = readdir($dir))) {
            if (($file !== '.') && ($file !== '..')) {
                if (is_dir($src . '/' . $file)) {
                    recurse_copy($src . '/' . $file, $dst . '/' . $file);
                } else {
                    copy($src . '/' . $file, $dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }
}

if (!function_exists('theme_get_pack_list_callback')) {
    /**
     * Function active layout pack
     *
     * @return void
     */
    function theme_get_pack_list_callback()
    {
        if (isset($_POST['option_nonce'])
            && wp_verify_nonce(sanitize_key($_POST['option_nonce']), 'option_nonce')
        ) {
            $theme_ju_template_pack = theme_ju_get_data_layout(true, true);
            $data                   = theme_ju_get_layout_pack(array('installed'));
            if ($theme_ju_template_pack === false) {
                $data['ju_template_pack'] = get_option('theme_ju_template_pack', null);
            } else {
                $data['ju_template_pack'] = $theme_ju_template_pack;
            }

            /*reset status of layouts packet from 2 to 1 when layouts packet folder not exist*/
            $count = count((array) $data['ju_template_pack']);
            if ($count > 0) {
                foreach ($data['ju_template_pack'] as $key => $layout) {
                    if (!in_array($key, $data['installed']) && $layout->status === 2) {
                        $layout->status = 1;
                    } elseif (in_array($key, $data['installed'])) {
                        $layout->status = 2;
                    }
                }
            }

            update_option('theme_ju_template_pack', $data['ju_template_pack']);
            exitStatus(true, $data);
        }

        exitStatus(false, 'Save false');
    }

    add_action('wp_ajax_theme_get_pack_list', 'theme_get_pack_list_callback');
}



if (!function_exists('theme_create_content_callback')) {
    /**
     * Function save custom css
     *
     * @return void
     */
    function theme_create_content_callback()
    {
        if (isset($_POST['option_nonce'], $_POST['id'], $_POST['pack_active'], $_POST['layout_active'], $_POST['oldContent']) && wp_verify_nonce($_POST['option_nonce'], 'option_nonce')
        ) {
            $layout                  = array();
            $layout['active']        = (string) $_POST['pack_active'];
            $layout['layout_active'] = (string) $_POST['layout_active'];
            $oldContent              = (int) $_POST['oldContent'] === 1 ? true : false;
            $check                   = theme_ju_update_template_post((int) $_POST['id'], $layout, $oldContent);

            if ($check === false) {
                wp_send_json(array(false, null));
            }

            wp_send_json(array(true, $check));
        } else {
            wp_send_json(array(false, null));
        }
    }

    add_action('wp_ajax_theme_ag_create_content_template', 'theme_create_content_callback');
}

if (!function_exists('theme_ju_update_template_post')) {
    //todo: when have layouts version, we add new version param
    /**
     * Update template to post
     *
     * @param integer $idPost        Id of post
     * @param array   $layout        Data template, layout active
     * @param boolean $getOldContent Check use old content
     *
     * @return boolean|array
     */
    function theme_ju_update_template_post($idPost, $layout, $getOldContent)
    {
        $oldContent   = null;
        $content_post = get_post($idPost);

        if ($content_post === null) {
            return false;
        }

        if ($getOldContent) {
            $oldContent = $content_post->post_content;
        }
        $templatesDir  = AG_THEME_UPLOAD_FOLDERDIR . DIRECTORY_SEPARATOR
                         . 'templates' . DIRECTORY_SEPARATOR;
        $link          = $templatesDir . $layout['active'] . DIRECTORY_SEPARATOR . $layout['layout_active'] . '.txt';
        $baseurlActive = AG_THEME_UPLOAD_FOLDERURL . '/templates/' . $layout['active'];

        if (!file_exists($link)) {
            return false;
        }
        $packActive = array('baseurl' => $baseurlActive, 'name' => $layout['active']);
        $content    = theme_ju_create_content_post($link, $packActive, $oldContent);
        if ($content !== '') {
            $id = theme_ju_create_post(
                $idPost,
                $content_post->post_title,
                $content['content'],
                $content_post->post_status,
                $content_post->post_author,
                $content_post->post_type
            );

            if (!is_wp_error($id)) {
                theme_ju_create_post_meta($id, '_wp_page_template', 'theme_ju_template.php');
                $templatePostsMeta = get_option(
                    'theme_ju_template_posts',
                    array()
                );

                if ($templatePostsMeta === '') {
                    $templatePostsMeta = array();
                }

                if (!$getOldContent && isset($templatePostsMeta[$idPost])) {
                    unset($templatePostsMeta[$idPost]);
                }
                //todo: will add new version param later
                if (isset($templatePostsMeta[$idPost]['template'][$layout['active']]) && $getOldContent) {
                    $templatePost = array(
                        'template' => array(
                            $layout['active'] => $templatePostsMeta[$idPost]['template'][$layout['active']] . '|' . $layout['layout_active']
                        ),
                        'option'   => array(),
                        'version'  => ''
                    );
                } else {
                    $templatePost = array(
                        'template' => array(
                            $layout['active'] => $layout['layout_active']
                        ),
                        'option'   => array(),
                        'version'  => ''
                    );
                }

                $layout['post_meta'] = theme_ju_update_post_meta_val($templatePostsMeta, $idPost, $templatePost, $content_post->post_type);
                $layout['content']   = $content['new_content'];


                return $layout;
            }

            return false;
        } else {
            return false;
        }
    }
}

if (!function_exists('theme_ju_create_content_post')) {
    /**
     * Function create| update content post
     *
     * @param string      $linkContent Link file has content
     * @param array       $packActive  Packet active data
     * @param null|string $oldContent  Old content of post
     *
     * @return array
     */
    function theme_ju_create_content_post($linkContent, $packActive, $oldContent = null)
    {
        $json_data     = fopen($linkContent, 'rb');
        $json          = fread($json_data, filesize($linkContent));
        $namePacket    = $packActive['name'];

        $listAttachment = get_option('theme_ju_ag_images_id', null);

        if (!isset($listAttachment[$namePacket])) {
            ag_add_layoutImages_to_media_library($namePacket);
            $listAttachment = get_option('theme_ju_ag_images_id', null);
        }

        //replace link image, id image
        preg_match_all('@(\[image:)([\_|\-|0-9|a-zA-Z|.]+\])@', $json, $val);
        $val   = $val[0];
        $count = count($val);
        $newImageDir = '';
        $savingDir = '';

        for ($i = 0; $i < $count; $i ++) {
            $arrayVal  = explode(':', $val[$i]);
            $nameImage = rtrim($arrayVal[1], '\]');
            $dataImage = $listAttachment[$namePacket][$nameImage];
            if (!isset($dataImage['imageDir'])) {
                if ($newImageDir === '') {
                    $folder      = wp_upload_dir();
                    $newImageDir = $folder['basedir'] . DIRECTORY_SEPARATOR . AG_THEME_NAME_OPTION . DIRECTORY_SEPARATOR
                                     . 'templates' . DIRECTORY_SEPARATOR . $namePacket . '/assets/images/';

                    $savingDir = $folder['path'];
                }
                $dataImage['imageDir']  = $newImageDir;
                $dataImage['savingDir'] = $savingDir . '/' . $nameImage;
            }

            /*check exist image in media_library folder and template folder, if satisfy the condition then add image*/
            if (!file_exists($dataImage['savingDir']) && file_exists($dataImage['imageDir'] . $nameImage)) {
                $dataImage = ag_add_image_to_media_library(
                    $dataImage['imageDir'],
                    rtrim($dataImage['savingDir'], '/' . $nameImage),
                    $nameImage
                );
                $listAttachment[$namePacket][$nameImage] = $dataImage;
                update_option('theme_ju_ag_images_id', $listAttachment);
            }
            $json = preg_replace('/\[image:' . $nameImage . '\]/', $dataImage['url'], $json);
            $json = preg_replace('/\[e-image:' . $nameImage . '\]/', urlencode($dataImage['url']), $json);
            $json = preg_replace('/\[id-image:' . $nameImage . '\]/', $dataImage['id'], $json);
        }

        fclose($json_data);

        if ($oldContent !== null) {
            $jsons = $oldContent . $json;

            return array('new_content' => $json, 'content' => $jsons);
        } else {
            return array('new_content' => $json, 'content' => $json);
        }
    }
}

add_filter('theme_ag_createContentPost', 'theme_ju_create_content_post', 10, 4);

if (!function_exists('theme_ju_create_post')) {
    /**
     * Function create| update post
     *
     * @param null|integer $id           Id of post
     * @param string       $postTitle    Title of post
     * @param string       $postContent  Content Post
     * @param string       $postStatus   Status of post
     * @param string       $postAuthor   Author post
     * @param string       $postType     Type post
     * @param array        $postCategory Category post
     * @param string       $currPack     Category post
     *
     * @return integer|null|WP_Error
     */
    function theme_ju_create_post(
        $id = null,
        $postTitle = '',
        $postContent = '',
        $postStatus = '',
        $postAuthor = '',
        $postType = '',
        $postCategory = array(),
        $currPack = ''
    ) {
        $new_coupon = array(
            'post_title'    => $postType === 'page' && $currPack !== '' ? ucfirst($postTitle) . ' - ' . $currPack : ucfirst($postTitle),
            'post_content'  => $postContent,
            'post_status'   => $postStatus,
            'post_author'   => $postAuthor,
            'post_type'     => $postType,
        );
        $count = count($postCategory);

        if ($count > 0) {
            $new_coupon['post_category'] = $postCategory;
        }

        if ($id !== null) {
            $new_coupon['ID'] = $id;
            return wp_update_post($new_coupon, true);
        } else {
            return wp_insert_post($new_coupon, true);
        }
    }
}

if (!function_exists('theme_ju_create_post_meta')) {
    /**
     * Function create| update post meta
     *
     * @param integer $idPost    Id of post meta
     * @param string  $metaKey   Meta key
     * @param string  $metaValue Meta value
     *
     * @return boolean|integer|null
     */
    function theme_ju_create_post_meta($idPost, $metaKey, $metaValue)
    {
        $id = null;
        $id = update_post_meta($idPost, $metaKey, $metaValue);

        return $id;
    }
}

if (!function_exists('theme_get_list_post_meta_template')) {
    /**
     * Function get list page have use layout pack
     *
     * @return array
     */
    function theme_get_list_post_meta_template()
    {
        global $wpdb;

        $result = $wpdb->query(
            'SELECT c.* FROM ' . $wpdb->prefix . 'postmeta as c WHERE c.meta_key = \'_wp_page_template\' AND c.meta_value = \'theme_ju_template.php\''
        );
        if ($result === false) {
            return array();
        }
        $result = $wpdb->get_results(
            'SELECT c.* FROM ' . $wpdb->prefix . 'postmeta as c WHERE c.meta_key = \'_wp_page_template\' AND c.meta_value = \'theme_ju_template.php\'',
            OBJECT
        );

        $list = array();
        if ($result !== null) {
            $countTemplate = count($result);
            for ($i = 0; $i < $countTemplate; $i ++) {
                $status = get_post_status($result[$i]->post_id);
                if ('trash' !== $status && 'auto-draft' !== $status) {
                    $list[$result[$i]->post_id] = $result[$i]->meta_id;
                }
            }
        }

        return $list;
    }
}

if (!function_exists('ag_add_layoutImages_to_media_library')) {
    /**
     * Add layout images to media library
     *
     * @param string $id_template Packet name
     *
     * @return void
     */
    function ag_add_layoutImages_to_media_library($id_template)
    {
        $upload_dir = wp_upload_dir();

        $imageDir    = $upload_dir['basedir'] . DIRECTORY_SEPARATOR
                       . AG_THEME_NAME_OPTION . '/templates/' . $id_template . '/assets/images/';
        $newImageDir = $upload_dir['path'];

        $list  = scandir($imageDir, 0);
        $count = count($list);
        $dataImages = array();

        for ($i = 0; $i < $count; $i ++) {
            if (!in_array($list[$i], array('.', '..'))) {
                $dataImage  = ag_add_image_to_media_library($imageDir, $newImageDir, $list[$i]);
                $dataImages[$list[$i]] = $dataImage;
            }
        }
        ag_save_attachment_id($id_template, $dataImages);
    }
}

if (!function_exists('ag_add_image_to_media_library')) {
    /**
     * Add layout image to media library
     *
     * @param string $imageDir    Link to image folder
     * @param string $newImageDir Folder to saving image
     * @param string $file_name   Image name
     *
     * @return array
     */
    function ag_add_image_to_media_library($imageDir, $newImageDir, $file_name)
    {
        if ($newImageDir !== '') {
            $link_image = $imageDir . $file_name;
            $image_data = file_get_contents($link_image);
            file_put_contents($newImageDir . '/' . $file_name, $image_data);
        }

        $wp_filetype = wp_check_filetype($file_name, null);

        $attachment = array(
            'post_mime_type' => $wp_filetype['type'],
            'post_title'     => sanitize_file_name($file_name),
            'post_content'   => '',
            'post_status'    => 'inherit'
        );

        $attach_id = wp_insert_attachment($attachment, $newImageDir . '/' . $file_name);
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        $attach_data = wp_generate_attachment_metadata($attach_id, $newImageDir . '/' . $file_name);
        wp_update_attachment_metadata($attach_id, $attach_data);
        $url = wp_get_attachment_image_src($attach_id, 'full');

        $data = array(
            'id'        => $attach_id,
            'url'       => $url[0],
            'savingDir' => $newImageDir . '/' . $file_name,
            'imageDir'  => $imageDir
        );
        return $data;
    }
}

if (!function_exists('ag_save_attachment_id')) {
    /**
     * Save image_attachment_id to theme_ju_ag_images_id option
     *
     * @param string $id_template Packet name
     * @param array  $dataImage   Image data(attachment_id, name)\
     *
     * @return void
     */
    function ag_save_attachment_id($id_template, $dataImage)
    {
        $listAttachment = get_option('theme_ju_ag_images_id', null);
        if ($listAttachment === null) {
            $listAttachment = array();
        }
        if (!isset($listAttachment[$id_template])) {
            $listAttachment[$id_template] = array();
        }
        $listAttachment[$id_template] = $dataImage;

        update_option('theme_ju_ag_images_id', $listAttachment);
    }
}

/**
 * Exit a request serving a json result
 *
 * @param string $status Exit status
 * @param array  $datas  Echoed datas
 *
 * @return void
 */
function exitStatus($status = '', $datas = array())
{
    $response = array('response' => $status, 'datas' => $datas);
    echo json_encode($response);
    die();
}

if (!function_exists('ag_get_layouts_settings')) {
    /**
     * Get list meta
     *
     * @param string $nameLayout   Name layout
     * @param string $templateName Name template
     *
     * @return array|boolean
     */
    function ag_get_layouts_settings($nameLayout, $templateName)
    {
        //add template customize option
        require_once AG_THEME_CORE . '/template_customize.php';
        $data_template_default = ag_get_default_option_layouts(false, $nameLayout);

        if (isset($data_template_default['page'])) {
            return $data_template_default['page'][$templateName];
        } else {
            return false;
        }
    }
}
