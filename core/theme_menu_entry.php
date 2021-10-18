<?php
defined('ABSPATH') || die;

if (!function_exists('menu_theme_call')) {
    /**
     * Function create option theme menu
     *
     * @return void
     */
    function menu_theme_call()
    {
        wp_enqueue_style('codemirror_style_theme', AG_THEME_INCLUDES_URI . '/assets/codemirror/codemirror.css', array(), ADVANCED_GUTENBERG_THEME_VERSION);
        wp_enqueue_style('codemirror_style', AG_THEME_INCLUDES_URI . '/assets/codemirror/theme/oceanic-next.css', array(), ADVANCED_GUTENBERG_THEME_VERSION);

        wp_enqueue_script('codemirror', AG_THEME_INCLUDES_URI . '/assets/codemirror/codemirror.js', array(), ADVANCED_GUTENBERG_THEME_VERSION);
        wp_enqueue_script('codemirrorCss', AG_THEME_INCLUDES_URI . '/assets/codemirror/mode/css/css.js', array(), ADVANCED_GUTENBERG_THEME_VERSION);
        wp_enqueue_script('codemirrorHtmlmixed', AG_THEME_INCLUDES_URI . '/assets/codemirror/mode/htmlmixed/htmlmixed.js', array(), ADVANCED_GUTENBERG_THEME_VERSION);
        wp_enqueue_script('codemirrorJavascript', AG_THEME_INCLUDES_URI . '/assets/codemirror/mode/javascript/javascript.js', array(), ADVANCED_GUTENBERG_THEME_VERSION);
        wp_enqueue_script('codemirrorXml', AG_THEME_INCLUDES_URI . '/assets/codemirror/mode/xml/xml.js', array(), ADVANCED_GUTENBERG_THEME_VERSION);
        wp_enqueue_script('codemirrorText', AG_THEME_INCLUDES_URI . '/assets/codemirror/mode/textile/textile.js', array(), ADVANCED_GUTENBERG_THEME_VERSION);

        wp_enqueue_script('bootstrap_js', AG_THEME_INCLUDES_URI . '/assets/bootstrap.js', array(), ADVANCED_GUTENBERG_THEME_VERSION);
        wp_enqueue_script('bootbox_js', AG_THEME_INCLUDES_URI . '/assets/bootbox.js', array(), ADVANCED_GUTENBERG_THEME_VERSION);

        wp_enqueue_style('ag_cssJU_style', AG_THEME_INCLUDES_URI . '/cssJU/css/style.min.css', array(), ADVANCED_GUTENBERG_THEME_VERSION);
        wp_enqueue_style('ag_waves_style', AG_THEME_INCLUDES_URI . '/cssJU/css/waves.min.css', array(), ADVANCED_GUTENBERG_THEME_VERSION);
        wp_enqueue_style('ag_menu_entry_style', AG_THEME_URI . '/assets/css/menu_entry_style.css', array(), ADVANCED_GUTENBERG_THEME_VERSION);

        wp_enqueue_script('ag_waves_js', AG_THEME_INCLUDES_URI . '/cssJU/js/waves.min.js', array(), ADVANCED_GUTENBERG_THEME_VERSION);
        wp_enqueue_script('ag_velocity_js', AG_THEME_INCLUDES_URI . '/cssJU/js/velocity.min.js', array(), ADVANCED_GUTENBERG_THEME_VERSION);
        wp_enqueue_script('ag_tabs_js', AG_THEME_INCLUDES_URI . '/cssJU/js/tabs.js', array(), ADVANCED_GUTENBERG_THEME_VERSION);
        wp_enqueue_script('ag_script_js', AG_THEME_INCLUDES_URI . '/cssJU/js/script.js', array(), ADVANCED_GUTENBERG_THEME_VERSION);
        wp_enqueue_script('menu_entry_js', AG_THEME_URI . '/assets/js/menu_entry.js', array('jquery'), ADVANCED_GUTENBERG_THEME_VERSION);

        wp_localize_script('menu_entry_js', 'agThemeText', array(
            'FeaturedImage'       => __('Featured Image', 'advanced-gutenberg-theme'),
            'Select'               => __('Select', 'advanced-gutenberg-theme'),
            'installed'            => __('INSTALLED', 'advanced-gutenberg-theme'),
            'layoutsInThisPack' => __('Layouts In This Pack', 'advanced-gutenberg-theme'),
            'layoutPack'          => __('Layout Pack', 'advanced-gutenberg-theme'),
            'RESET_THEME_OPTION'   => __('This tool will reset all your theme setting to default! Sure?', 'advanced-gutenberg-theme'),
            'login' => __('This tool will reset all your theme setting to default! Sure?', 'advanced-gutenberg-theme'),
        ));
        ?>
        <?php
        global $UpdateChecker;

        $params = $UpdateChecker->localizeScript();
        wp_localize_script('check_token', 'updaterparams', $params);

        $option = ag_get_option(AG_THEME_NAME_OPTION, AG_THEME_FOLDER);
        wp_nonce_field('option_nonce', 'option_nonce');

        wp_enqueue_media();

        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- No action, nonce is not required
        if ((!empty($_GET['page']) && in_array($_GET['page'], array('theme-live-update', 'option-theme', 'theme-translation')))) {
            // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- No action, nonce is not required
            $content_page = $_GET['page'];
        } else {
            $content_page = '';
        }
        /*get folder templates in upload*/
        $folder = wp_upload_dir();
        $folderTemplates = $folder['baseurl'] . '/' . AG_THEME_NAME_OPTION . '/templates/';

        wp_localize_script('menu_entry_js', 'menu_entry_js', array(
            'ag_theme_option' => $option,
            'updaterparams' => $params,
            'content_page' => $content_page,
            'folderTemplates' => $folderTemplates,
            'siteurl' => get_option('siteurl')
        ));
        ?>
        <script>
            var ajaxurl = '<?php echo esc_url(admin_url('admin-ajax.php', 'relative')); ?>';
            var AG_THEME_NAME_OPTION = '<?php echo esc_attr(AG_THEME_NAME_OPTION); ?>';
            var AG_THEME_FOLDER = '<?php echo esc_attr(AG_THEME_FOLDER); ?>';
        </script>
        <div id="theme_setting" class="ju-main-wrapper">
            <div class="ju-left-panel-toggle">
                <i class="dashicons dashicons-leftright ju-left-panel-toggle-icon"></i>
            </div>
            <div class="ju-left-panel">
                <div class="ju-logo">
                    <a href="https://www.joomunited.com/" target="_blank">
                        <img src="<?php echo esc_url_raw(AG_THEME_URI . '/assets/images/logo-joomUnited-white.png');?>" alt="JoomUnited logo">
                    </a>
                </div>
                <div class="ju-menu-search">
                    <i class="mi mi-search ju-menu-search-icon"></i>
                    <input type="text" class="ju-menu-search-input" placeholder="Search settings" />
                </div>
                <ul id="config_option" class="tabs ju-menu-tabs">
                    <li class="tab theme_option active">
                        <a href="#theme_advanced" class="link-tab waves-effect waves-light"><?php esc_attr_e('Theme Options', 'advanced-gutenberg-theme'); ?></a>
                    </li>
                    <li class="tab theme_layout">
                        <a href="#theme-layout" class="tab_theme_layout link-tab waves-effect waves-light"><?php esc_attr_e('Layout', 'advanced-gutenberg-theme'); ?></a>
                    </li>
                    <li class="tab update">
                        <a href="#theme-live-update" class="link-tab waves-effect waves-light"><?php esc_attr_e('Live update', 'advanced-gutenberg-theme'); ?></a>
                    </li>
                    <li class="tab translation">
                        <a href="#theme-translation" class="link-tab waves-effect waves-light"><?php esc_attr_e('Translation', 'advanced-gutenberg-theme'); ?></a>
                    </li>
                </ul>
            </div>
            <div class="ju-right-panel">
                <div id="theme_advanced" class="ju-content-wrapper">
                    <div class="ju-top-tabs-wrapper">
                        <ul class="tabs ju-top-tabs">
                            <li class="tab theme_option active">
                                <a href="#theme_option" class="link-tab"><?php esc_attr_e('Theme Options', 'advanced-gutenberg-theme'); ?></a>
                            </li>
                            <li class="tab custom_code">
                                <a href="#custom_code" class="link-tab"><?php esc_attr_e('Custom Code', 'advanced-gutenberg-theme'); ?></a>
                            </li>
                            <li class="tab advanced">
                                <a href="#advanced" class="link-tab "><?php esc_attr_e('Advanced', 'advanced-gutenberg-theme'); ?></a>
                            </li>
                        </ul>
                    </div>
                    <div class="ju-notice-success message ju-notice-msg">
                        <span ><?php esc_attr_e('All modifications were saved', 'advanced-gutenberg-theme'); ?></span>
                        <i class="dashicons dashicons-dismiss ju-notice-close"></i>
                    </div>
                    <div class="ju-notice-error message ju-notice-msg">
                        <span ><?php esc_attr_e('there was an error', 'advanced-gutenberg-theme'); ?></span>
                        <i class="dashicons dashicons-dismiss ju-notice-close"></i>
                    </div>

                    <?php
                    require_once(AG_THEME_URL . '/option.php');
                    require_once(AG_THEME_URL . '/custom.php');
                    require_once(AG_THEME_URL . '/advanced.php');
                    ?>
                    <a id="saveTable" class="ju-button" title="Save modifications"><?php esc_attr_e('Save Changes', 'advanced-gutenberg-theme'); ?>
                    </a>
                </div>
                <div id="theme-layout" class="ju-content-wrapper">
                    <div class="ju-notice-success message ju-notice-msg">
                        <span ><?php esc_attr_e('All modifications were saved', 'advanced-gutenberg-theme'); ?></span>
                        <i class="dashicons dashicons-dismiss ju-notice-close"></i>
                    </div>
                    <div class="ju-notice-error message ju-notice-msg">
                        <span ><?php esc_attr_e('there was an error', 'advanced-gutenberg-theme'); ?></span>
                        <i class="dashicons dashicons-dismiss ju-notice-close"></i>
                    </div>
                    <div id="layout" class="tab-pane">
                        <?php require_once(AG_THEME_URL . '/layout.php'); ?>
                    </div>
                </div>
                <div id="theme-live-update" class="ju-content-wrapper">
                    <div id="theme-update" class="tab-pane">
                        <?php require_once(AG_THEME_URL . '/live-update.php'); ?>
                    </div>
                </div>
                <div id="theme-translation" class="ju-content-wrapper">
                    <div id="theme-jutranslation-config" class="tab-pane">
                        <?php echo \Joomunited\ADVGBTHEME\Jutranslation\Jutranslation::getInput(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- already escaped ?>
                    </div>
                </div>
            </div>
            <div id="loading_animation">
                <div class="ju-loader"></div>
            </div>
        </div>
        <?php
    }
}
