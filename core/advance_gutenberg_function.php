<?php
defined('ABSPATH') || die;

/**
 * Function load script for advgb_blocks
 *
 * @return void
 */
function advance_blocks()
{
    // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- No action, nonce is not required
    if (isset($_REQUEST['page']) && $_REQUEST['page'] === 'advgb_main') {
        return;
    }
    $post = get_post();
    if (!empty($post)) {
        wp_enqueue_style('wp-color-picker');

        wp_enqueue_script(
            'wp-color-picker-alpha',
            AG_THEME_INCLUDES_URI . '/assets/customizer-alpha-color-picker/js/wp-color-picker-alpha.min.js',
            array('wp-color-picker'),
            ADVANCED_GUTENBERG_THEME_VERSION,
            true
        );
        wp_enqueue_script(
            'theme_ag_select_template_page',
            get_template_directory_uri() . '/assets/js/page_edit.js',
            array('wp-blocks', 'wp-element', 'wp-components', 'wp-edit-post', 'wp-data', 'wp-color-picker')
        );
        wp_enqueue_style(
            'block_config_css',
            AG_THEME_URI . '/assets/css/edit_post_template.css',
            array(),
            ADVANCED_GUTENBERG_THEME_VERSION
        );

        $ju_template_pack = get_option('theme_ju_template_pack', null);
        $layoutTemplate = new stdClass();


        $id_post = $post->ID;
        $folder       = wp_upload_dir();
        $templatesDir = $folder['basedir'] . DIRECTORY_SEPARATOR . AG_THEME_NAME_OPTION
                        . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR;
        $templatesUrl = $folder['baseurl'] . '/' . AG_THEME_NAME_OPTION . '/templates/';

        if ($ju_template_pack !== null) {
            foreach ($ju_template_pack as $pack => $layout) {
                if ($layout->status === 2) {
                    $layoutTemplate->{$pack} = $layout;
                    $link = '';
                    $link .= $templatesDir . $pack .'/assets/css/style_backend.css';
                    if (file_exists($link)) {
                        $version = ADVANCED_GUTENBERG_THEME_VERSION;
                        if (isset($layout->version)) {
                            $version = (string) $layout->version;
                        }
                        wp_enqueue_style(
                            'style_backend_' . $pack,
                            $templatesUrl . $pack . '/assets/css/style_backend.css',
                            array(),
                            $version
                        );
                    }
                }
            }
        }

        $template_post = array();
        if (isset($id_post)) {
            $templates = get_option('theme_ju_template_posts', null);
            if (isset($templates[$id_post])) {
                if (isset($templates[$id_post]['template'])) {
                    $template_post = $templates[$id_post]['template'];
                } else {
                    $template_post = $templates[$id_post];
                }
            }
        }

        $customizer = ag_get_option(AG_THEME_NAME_OPTION, AG_THEME_FOLDER, 'full');
        $custom_template = get_option('theme_ju_custom_option', array());

        wp_localize_script('theme_ag_select_template_page', 'ag_list_layout', array(
            'option_nonce' => wp_create_nonce('option_nonce'),
            'layoutTemplate' => $layoutTemplate,
            'post_type' => $post->post_type,
            'template' => $template_post,
            'customizer' => $customizer,
            'custom_template' => $custom_template,
        ));

        wp_localize_script('theme_ag_select_template_page', 'agTheme', array('activated' => true));
        wp_localize_script(
            'theme_ag_select_template_page',
            'agThemeText',
            array(
                'messageErrorUseTwo' => __('You cannot use 2 layout packages for the same page!', 'advanced-gutenberg-theme'),
                'LOADED' => __('LOADED', 'advanced-gutenberg-theme'),
                'keepExistingContent' => __('Keep Existing Content', 'advanced-gutenberg-theme'),
                'searchLayoutPacket' => __('Search layout', 'advanced-gutenberg-theme'),
                'Layout' => __('Layout', 'advanced-gutenberg-theme'),
                'hideAdvancedGutenbergLayouts' => __('Hide Advanced Gutenberg Layouts', 'advanced-gutenberg-theme'),
                'advancedGutenbergLayouts' => __('Advanced Gutenberg Layouts', 'advanced-gutenberg-theme'),
                'importLayout' => __('Import Layout', 'advanced-gutenberg-theme'),
                'getTemplates' => __('Pages Layouts', 'advanced-gutenberg-theme'),
                'getPages' => __('Existing Pages', 'advanced-gutenberg-theme'),
                'configSelectLayout' => __('The layout selected does not fit the theme setting for header and footer: Change to match layout / Dismiss !', 'advanced-gutenberg-theme'),
            )
        );
    }
}

add_action('enqueue_block_editor_assets', 'advance_blocks', 99999);

add_action('current_screen', 'ag_add_file_blocks');

if (!function_exists('ag_add_file_blocks')) {

    /**
     * Function require_once to file backend of template
     *
     * @return void
     */
    function ag_add_file_blocks()
    {
        if (function_exists('get_current_screen')) {
            $current_screen = get_current_screen();
        }
        if (!isset($current_screen) || $current_screen->base === 'edit' || $current_screen->base === 'post') {
            $ju_template_pack = get_option('theme_ju_template_pack', null);
            $folder = wp_upload_dir();
            $templatesDir = $folder['basedir'] . DIRECTORY_SEPARATOR . AG_THEME_NAME_OPTION . DIRECTORY_SEPARATOR
                . 'templates' . DIRECTORY_SEPARATOR;
            if ($ju_template_pack !== null) {
                $colorPalette = array();
                foreach ($ju_template_pack as $pack => $layout) {
                    if ($layout->status === 2) {
                        $linkFile = $templatesDir . $pack . DIRECTORY_SEPARATOR . 'layouts_backend.php';
                        if (file_exists($linkFile)) {
                            require_once $linkFile;
                        }

                        /*add editor color palette*/
                        $colorPalette = apply_filters('ag_theme_support_color_layout_' . $pack, $colorPalette);
                    }
                }
                add_color_palette($colorPalette);
            }
        }
    }
}
add_action('wp', 'ag_add_file_blocks');

if (!function_exists('add_color_palette')) {
    /**
     * Add color palette for editor
     *
     * @param array $colorPalette List color define in layouts
     *
     * @return void
     */
    function add_color_palette($colorPalette)
    {
        $default = array(
            array(
                'name' => __('Pale pink', 'ag-theme'),
                'slug' => 'ag_pale_pink',
                'color' => '#f78da7',
            ),
            array(
                'name' => __('Vivid red', 'ag-theme'),
                'slug' => 'ag_vivid_red',
                'color' => '#cf2e2e',
            ),
            array(
                'name' => __('Luminous vivid orange', 'ag-theme'),
                'slug' => 'ag_vivid_orange',
                'color' => '#ff6900',
            ),
            array(
                'name' => __('Luminous vivid amber', 'ag-theme'),
                'slug' => 'ag_vivid_amber',
                'color' => '#fcb900',
            ),
            array(
                'name' => __('Light green cyan', 'ag-theme'),
                'slug' => 'ag_light_green_cyan',
                'color' => '#7bdcb5',
            ),
            array(
                'name' => __('Vivid green cyan', 'ag-theme'),
                'slug' => 'ag_vivid_green_cyan',
                'color' => '#00d084',
            ),
            array(
                'name' => __('Pale cyan blue', 'ag-theme'),
                'slug' => 'ag_pale_cyan_blue',
                'color' => '#8ed1fc',
            ),
            array(
                'name' => __('Vivid cyan blue', 'ag-theme'),
                'slug' => 'ag_vivid_cyan_blue',
                'color' => '#0693e3',
            ),
            array(
                'name' => __('Very light gray', 'ag-theme'),
                'slug' => 'ag_light_gray',
                'color' => '#eeeeee',
            ),
            array(
                'name' => __('Cyan bluish gray', 'ag-theme'),
                'slug' => 'ag_bluish_gray',
                'color' => '#abb8c3',
            ),
            array(
                'name' => __('Black', 'ag-theme'),
                'slug' => 'ag_black',
                'color' => '#000000',
            ),
            array(
                'name' => __('White', 'ag-theme'),
                'slug' => 'ag_white',
                'color' => '#ffffff',
            ),
        );
        $count = count($colorPalette);

        while ($count > 0) {
            $count--;
            $default[10 + $count] = $colorPalette[$count];
        }
        add_theme_support('editor-color-palette', $default);
    }
}

//remove admin_notices in theme option page
add_action('admin_print_scripts', function () {
    global $wp_filter;
    // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- No action, nonce is not required
    if ((!empty($_GET['page']) && in_array($_GET['page'], array('theme-live-update', 'option-theme', 'theme-translation')))) {
        if (is_user_admin()) {
            if (isset($wp_filter['user_admin_notices'])) {
                unset($wp_filter['user_admin_notices']);
            }
        } elseif (isset($wp_filter['admin_notices'])) {
            unset($wp_filter['admin_notices']);
        }
        if (isset($wp_filter['all_admin_notices'])) {
            unset($wp_filter['all_admin_notices']);
        }
    }
});

/**
 * Function add meta boxes
 *
 * @return void
 */
function ag_theme_meta_boxes()
{
    add_meta_box('agtheme_register_metafield', esc_attr__('Advanced Gutenberg Theme', 'advanced-gutenberg-theme'), 'ag_theme_display_callback', 'post', 'side');
    add_meta_box('agtheme_register_metafield', esc_attr__('Advanced Gutenberg Theme', 'advanced-gutenberg-theme'), 'ag_theme_display_callback', 'page', 'side');
}

add_action('add_meta_boxes', 'ag_theme_meta_boxes');

if (!function_exists('ag_theme_display_callback')) {
    /**
     * Meta box render function
     *
     * @param object $post Post object.
     *
     * @return void
     */
    function ag_theme_display_callback($post)
    {
        $meta                   = get_post_meta($post->ID);
        $page_header_layout    = isset($meta['page_header_layout'][0]) ? $meta['page_header_layout'][0] : '4';
        $page_sidebar    = isset($meta['page_sidebar'][0]) ? $meta['page_sidebar'][0] : 'customizer';
        $page_header_style    = isset($meta['page_header_style'][0]) ? $meta['page_header_style'][0] : '3';
        $page_top_content    = isset($meta['page_top_content'][0]) ? $meta['page_top_content'][0] : '2';
        $page_title_content    = (isset($meta['page_title_content'][0]) && '' !== $meta['page_title_content'][0]) ? $meta['page_title_content'][0] : '0';
        $header_divider    = (isset($meta['header_divider'][0]) && '' !== $meta['header_divider'][0]) ? $meta['header_divider'][0] : '0';
        $page_menu_color    = (isset($meta['text_color'][0]) && '' !== $meta['text_color'][0]) ? $meta['text_color'][0] : '';
        $active_link_color    = (isset($meta['active_link_color'][0]) && '' !== $meta['active_link_color'][0]) ? $meta['active_link_color'][0] : '';
        $menu_background_color    = (isset($meta['menu_background_color'][0]) && '' !== $meta['menu_background_color'][0]) ? $meta['menu_background_color'][0] : '';
        $header_background_color    = (isset($meta['header_background_color'][0]) && '' !== $meta['header_background_color'][0]) ? $meta['header_background_color'][0] : '';
        wp_nonce_field('ag_theme_control_meta_box', 'ag_theme_control_meta_box_nonce');
        $palette ='true';
        ?>
        <style type="text/css">
            .post_meta_extras label {
                display: block;
                margin-bottom: 10px;
            }
            .post_meta_extras select {
                min-width: 84%;
                padding: 0 24px 0 10px;
            }
            .post_meta_extras .wp-picker-container .wp-color-result.button {
                min-height: 26px;
            }
            input[name="page_top_content"] {
                display: none;
            }
        </style>
        <div class="post_meta_extras ag-theme-meta-box">
            <p>
                <label>
                    <?php esc_attr_e('Header Layout', 'advanced-gutenberg-theme'); ?>
                    <select id="page_header_layout" name="page_header_layout" value="<?php echo esc_attr($page_header_layout); ?>">
                        <option value="0" <?php selected($page_header_layout, 0); ?> ><?php esc_attr_e('Left', 'advanced-gutenberg-theme')?></option>
                        <option value="1" <?php selected($page_header_layout, 1); ?> ><?php esc_attr_e('Center', 'advanced-gutenberg-theme')?></option>
                        <option value="2" <?php selected($page_header_layout, 2); ?> ><?php esc_attr_e('Right', 'advanced-gutenberg-theme')?></option>
                        <option value="3" <?php selected($page_header_layout, 3); ?> ><?php esc_attr_e('Top + Center', 'advanced-gutenberg-theme')?></option>
                        <option value="4" <?php selected($page_header_layout, 4); ?> ><?php esc_attr_e('Inherit', 'advanced-gutenberg-theme')?></option>
                    </select>
                </label>
            </p>
            <p>
                <label>
                    <?php esc_attr_e('Header Style', 'advanced-gutenberg-theme'); ?>
                    <select id="page_header_style" name="page_header_style" value="<?php echo esc_attr($page_header_style); ?>">
                        <option value="0" <?php selected($page_header_style, 0); ?> ><?php esc_attr_e('none', 'advanced-gutenberg-theme')?></option>
                        <option value="1" <?php selected($page_header_style, 1); ?> ><?php esc_attr_e('Sticky Header', 'advanced-gutenberg-theme')?></option>
                        <option value="2" <?php selected($page_header_style, 2); ?> ><?php esc_attr_e('Fixed Navigation', 'advanced-gutenberg-theme')?></option>
                        <option value="3" <?php selected($page_header_style, 3); ?> ><?php esc_attr_e('Inherit', 'advanced-gutenberg-theme')?></option>
                    </select>
                </label>
            </p>
            <p>
                <label>
                    <?php esc_attr_e('Header Above Content', 'advanced-gutenberg-theme'); ?>
                    <select id="page_top_content" name="page_top_content" value="<?php echo esc_attr($page_top_content); ?>">
                        <option value="0" <?php selected($page_top_content, 0); ?> ><?php esc_attr_e('Disable', 'advanced-gutenberg-theme')?></option>
                        <option value="1" <?php selected($page_top_content, 1); ?> ><?php esc_attr_e('Enable', 'advanced-gutenberg-theme')?></option>
                        <option value="2" <?php selected($page_top_content, 2); ?> ><?php esc_attr_e('Inherit', 'advanced-gutenberg-theme')?></option>
                    </select>
                </label>
            </p>
            <p>
                <label>
                    <?php esc_attr_e('Sidebar', 'advanced-gutenberg-theme'); ?>
                    <select id="page_sidebar" name="page_sidebar" value="<?php echo esc_attr($page_sidebar); ?>">
                        <option value="customizer" <?php selected($page_sidebar, 'customizer'); ?> ><?php esc_attr_e('Customizer Setting', 'advanced-gutenberg-theme')?></option>
                        <option value="left" <?php selected($page_sidebar, 'left'); ?> ><?php esc_attr_e('Left Sidebar', 'advanced-gutenberg-theme')?></option>
                        <option value="right" <?php selected($page_sidebar, 'right'); ?> ><?php esc_attr_e('Right Sidebar', 'advanced-gutenberg-theme')?></option>
                        <option value="none" <?php selected($page_sidebar, 'none'); ?> ><?php esc_attr_e('No Sidebar', 'advanced-gutenberg-theme')?></option>
                    </select>
                </label>
            </p>
            <p>
                <label><input type="checkbox" name="page_title_content"
                              value="1" <?php checked($page_title_content, 1); ?> /><?php esc_attr_e('Hide Title Content', 'advanced-gutenberg-theme'); ?>
                </label>
            </p>
            <p>
                <label><input type="checkbox" name="header_divider"
                              value="1" <?php checked($header_divider, 1); ?> /><?php esc_attr_e('Disable header divider', 'advanced-gutenberg-theme'); ?>
                </label>
            </p>
            <p>
                <label>
                    <?php esc_attr_e('Menu color', 'advanced-gutenberg-theme'); ?>
                    <div>
                        <input class="color-picker alpha-color-control" data-alpha="true"  data-palette="<?php echo esc_attr($palette); ?>" type="text" name="text_color"
                               data-default-color="#ffffff" value="<?php echo esc_attr($page_menu_color); ?>" />
                    </div>
                </label>
            </p>
            <p>
                <label>
                    <?php esc_attr_e('Active link menu color', 'advanced-gutenberg-theme'); ?>
                    <div>
                        <input class="color-picker alpha-color-control" data-alpha="true" data-palette="<?php echo esc_attr($palette); ?>" type="text" name="active_link_color"
                               data-default-color="#ffffff" value="<?php echo esc_attr($active_link_color); ?>" />
                    </div>
                </label>
            </p>
            <p>
                <label>
                    <?php esc_attr_e('Menu background color', 'advanced-gutenberg-theme'); ?>
                    <div>
                        <input class="color-picker alpha-color-control" data-alpha="true" data-palette="<?php echo esc_attr($palette); ?>" type="text" name="menu_background_color"
                               data-default-color="#ffffff" value="<?php echo esc_attr($menu_background_color); ?>" />
                    </div>
                </label>
            </p>
            <p>
                <label>
                    <?php esc_attr_e('Header background color', 'advanced-gutenberg-theme'); ?>
                    <div>
                        <input class="color-picker alpha-color-control" data-alpha="true" data-palette="<?php echo esc_attr($palette); ?>" type="text" name="header_background_color"
                               data-default-color="#ffffff" value="<?php echo esc_attr($header_background_color); ?>" />
                    </div>
                </label>
            </p>
        </div>
        <?php
    }
}

add_action('save_post', 'ag_theme_save_metaboxes');

if (!function_exists('ag_theme_save_metaboxes')) {
    /**
     * Save controls from the meta boxes
     *
     * @param integer $post_id Current post id.
     *
     * @return string|void
     */
    function ag_theme_save_metaboxes($post_id)
    {
        if (!isset($_POST['ag_theme_control_meta_box_nonce']) || !wp_verify_nonce(sanitize_key($_POST['ag_theme_control_meta_box_nonce']), 'ag_theme_control_meta_box')) {
            return $post_id;
        }

        if (isset($_POST['post_type']) && 'page' === $_POST['post_type']) {
            if (!current_user_can('edit_page', $post_id)) {
                return $post_id;
            }
        } else {
            if (!current_user_can('edit_post', $post_id)) {
                return $post_id;
            }
        }

        /*
         * If this is an autosave, our form has not been submitted,
         * so we don't want to do anything.
         */
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $post_id;
        }

        $page_header_layout = isset($_POST['page_header_layout']) ? $_POST['page_header_layout'] : '4';
        update_post_meta($post_id, 'page_header_layout', esc_attr($page_header_layout));

        $page_header_style = isset($_POST['page_header_style']) ? $_POST['page_header_style'] : '3';
        update_post_meta($post_id, 'page_header_style', esc_attr($page_header_style));

        $page_top_content = isset($_POST['page_top_content']) ? $_POST['page_top_content'] : '2';
        update_post_meta($post_id, 'page_top_content', esc_attr($page_top_content));

        $page_sidebar = isset($_POST['page_sidebar']) ? $_POST['page_sidebar'] : 'customizer';
        update_post_meta($post_id, 'page_sidebar', esc_attr($page_sidebar));

        $page_menu_color = isset($_POST['text_color']) ? $_POST['text_color'] : '';
        update_post_meta($post_id, 'text_color', esc_attr($page_menu_color));

        $active_link_color = isset($_POST['active_link_color']) ? $_POST['active_link_color'] : '';
        update_post_meta($post_id, 'active_link_color', esc_attr($active_link_color));

        $menu_background_color = isset($_POST['menu_background_color']) ? $_POST['menu_background_color'] : '';
        update_post_meta($post_id, 'menu_background_color', esc_attr($menu_background_color));

        $header_background_color = isset($_POST['header_background_color']) ? $_POST['header_background_color'] : '';
        update_post_meta($post_id, 'header_background_color', esc_attr($header_background_color));

        $page_title_content = (isset($_POST['page_title_content']) && '1' === $_POST['page_title_content']) ? 1 : 0;
        update_post_meta($post_id, 'page_title_content', esc_attr($page_title_content));

        $header_divider = (isset($_POST['header_divider']) && '1' === $_POST['header_divider']) ? 1 : 0;
        update_post_meta($post_id, 'header_divider', esc_attr($header_divider));
    }
}
