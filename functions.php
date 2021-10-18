<?php
defined('ABSPATH') || die;

define('AG_THEME_REQUIRED_PHP_VERSION', '5.3.0');
define('AG_THEME_URL', get_template_directory());
define('AG_THEME_CORE', AG_THEME_URL . '/core');
define('AG_THEME_INCLUDES_URL', AG_THEME_URL . '/includes');
define('AG_THEME_URI', get_template_directory_uri());
define('AG_THEME_CORE_URI', AG_THEME_URI . '/core');
define('AG_THEME_INCLUDES_URI', AG_THEME_URI . '/includes');

define('AG_THEME_NAME_OPTION', 'AGtheme');

$folder = wp_upload_dir();
define('AG_THEME_UPLOAD_FOLDERDIR', $folder['basedir'] . DIRECTORY_SEPARATOR . AG_THEME_NAME_OPTION);
define('AG_THEME_UPLOAD_FOLDERURL', $folder['baseurl'] . '/' . AG_THEME_NAME_OPTION);

define('ADVANCED_GUTENBERG_THEME_VERSION', '0.0.1');

$url_arr = explode('/', get_template_directory());
define('AG_THEME_FOLDER', array_pop($url_arr));

if (!defined('JU_BASE')) {
    define('JU_BASE', 'https://www.joomunited.com/');
}

if (!isset($content_width)) {
    $content_width = 1080;
}

if (!function_exists('ag_theme_setup')) {

    /**
     * Theme setup function
     *
     * @return void
     */
    function ag_theme_setup()
    {
        $language_folder = AG_THEME_URL . '/language';
        load_theme_textdomain('advanced-gutenberg-theme', $language_folder);

        // Setup theme supports
        add_theme_support('title-tag');
        add_theme_support('automatic-feed-links');
        /* Them post thumbnail */
        add_theme_support('post-thumbnails');

        add_theme_support(
            'post-formats',
            array(
                'image',
                'video',
                'gallery',
                'quote',
                'link',
                'audio'
            )
        );
        add_theme_support('align-wide');
        add_theme_support('editor-styles');

        add_theme_support('woocommerce');
        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');

        // menu
        register_nav_menus(array(
            'primary-menu' => __('Primary menu', 'advanced-gutenberg-theme'),
            'mobile-menu' => __('Mobile Menu', 'advanced-gutenberg-theme'),
            'footer-menu' => __('Footer menu', 'advanced-gutenberg-theme')
        ));

        register_sidebar(array(
            'name' => __('Primary Sidebar', 'advanced-gutenberg-theme'),
            'description' => __('Primary sidebar of AG Theme', 'advanced-gutenberg-theme'),
            'id' => 'primary-sidebar',
            'class' => 'agt-sidebar',
            'before_title' => '<h3 class="agt-sidebar-title">',
            'after_title' => '</h3>',
        ));

        remove_filter('the_content', 'wpautop');
    }

    require_once(AG_THEME_CORE . '/init.php');
}
add_action('after_setup_theme', 'ag_theme_setup');

if (!function_exists('ag_theme_change_item_menu')) {
    /**
     * Change item menu
     *
     * @param string   $item_output The menu item's starting HTML output.
     * @param WP_Post  $item        Menu item data object.
     * @param integer  $depth       Depth of menu item. Used for padding.
     * @param stdClass $args        An object of wp_nav_menu() arguments.
     *
     * @return string
     */
    function ag_theme_change_item_menu($item_output, $item, $depth, $args)
    {
        if ($args->theme_location === 'mobile-menu' || $args->theme_location === 'footer-menu') {
            $angle_down = '<i class="material-icons icon-angle-down ag-has-children">expand_more</i></a>';
            if (isset($item->classes)) {
                $count = count($item->classes);
                for ($i = 0; $i < $count; $i ++) {
                    if (isset($item->classes[$i]) && $item->classes[$i] === 'menu-item-has-children') {
                        $item_output = str_replace('</a>', $angle_down, $item_output);
                    }
                }
            }
        }
        return $item_output;
    }

    add_filter('walker_nav_menu_start_el', 'ag_theme_change_item_menu', 10, 4);
}

add_action('init', function () {
    if (!is_admin()) {
        wp_enqueue_style(
            'ag_theme_style_reset',
            AG_THEME_URI . '/style.css',
            array(),
            ADVANCED_GUTENBERG_THEME_VERSION
        );
        wp_enqueue_style('dashicons');

        $optionTheme = ag_get_option(AG_THEME_NAME_OPTION, AG_THEME_FOLDER, 'full');
        $customCss = ag_get_option(AG_THEME_NAME_OPTION, AG_THEME_FOLDER, 'full', 'custom', array());
        if (!isset($customCss['customCss'])) {
            $customCss['customCss'] = '';
        }
        $customCss['customCss'] = stripslashes($customCss['customCss']);
        $style_url = apply_filters('ag_compilestyle_theme', $optionTheme, $customCss['customCss']);

        $optionFont = array();

        /*default google font*/
        array_push($optionFont, 'Open Sans');

        /*get font google header, body*/
        if (isset($optionTheme['body_font_google']) && $optionTheme['body_font_google'] !== '') {
            array_push($optionFont, $optionTheme['body_font_google']);
        }

        if (isset($optionTheme['header_font_google']) && $optionTheme['header_font_google'] !== '') {
            array_push($optionFont, $optionTheme['header_font_google']);
        }

        if (isset($optionTheme['template_header_font_google']) && $optionTheme['template_header_font_google'] !== '') {
            array_push($optionFont, $optionTheme['template_header_font_google']);
        }

        apply_filters('theme_font_google', $optionFont);
    }
    wp_enqueue_style(
        'material_icon_font_custom',
        AG_THEME_URI . '/assets/fonts/material-icons-custom.min.css',
        array(),
        ADVANCED_GUTENBERG_THEME_VERSION
    );
});

if (!function_exists('ag_customize_register')) {
    /**
     * Create customize
     *
     * @param object $wp_customize Wp customize
     *
     * @return void
     */
    function ag_customize_register($wp_customize)
    {
//        require_once AG_THEME_CORE . '/customize_setting_image.php';

        require_once AG_THEME_CORE . '/customize.php';
        ag_create_customize_option_setting($wp_customize, AG_THEME_NAME_OPTION);
        //add template customize option
        require_once AG_THEME_CORE . '/template_customize.php';
    }

    //create customize theme
    add_action('customize_register', 'ag_customize_register');
}

if (!function_exists('ag_theme_entry_meta')) {
    /**
     * Function add amtry meta
     *
     * @param boolean $checkTag Check display tag
     *
     * @return void
     */
    function ag_theme_entry_meta($checkTag = false)
    {
        global $post;
        if (!is_page()) {
            $optionTheme = ag_get_option(AG_THEME_NAME_OPTION, AG_THEME_FOLDER, 'option');
            echo '<div class="entry-meta">';

            if ($checkTag) {
                the_tags('Tagged with: ', ' • ', '<br />');
            }

            $checkDisplay = !isset($optionTheme['display_author']) || $optionTheme['display_author'];
            if ($checkDisplay) {
                //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- output html not escaping
                printf(__('<span class="author">Posted by %1$s</span>', 'advanced-gutenberg-theme'), get_the_author());
            }

            $checkDisplay = !isset($optionTheme['display_date']) || $optionTheme['display_date'];
            if ($checkDisplay) {
                //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- output html not escaping
                printf(__('<span class="date-published"> %1$s</span>', 'advanced-gutenberg-theme'), get_the_date());
            }

            $checkDisplay = !isset($optionTheme['display_category']) || $optionTheme['display_category'];
            if ($checkDisplay) {
                printf(
                    //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- output html not escaping
                    __('<span class="category"> %1$s</span>', 'advanced-gutenberg-theme'),
                    //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- output html not escaping
                    get_the_category_list(', ')
                );
            }
            if (isset($post) && ag_check_comments_open($post)) {
                echo ' <span class="meta-reply">';
                comments_popup_link(
                    __('Leave a comment', 'advanced-gutenberg-theme'),
                    __('One comment', 'advanced-gutenberg-theme'),
                    __('% comments', 'advanced-gutenberg-theme'),
                    'ag_comments',
                    __('Comments Off', 'advanced-gutenberg-theme')
                );
                echo '</span>';
            }
            echo '</div>';
        }
    }
}

if (!function_exists('ag_check_comments_open')) {
    /**
     * Function check open comment
     *
     * @param object $post Post data
     *
     * @return boolean
     */
    function ag_check_comments_open($post)
    {
        $displayComments = ag_get_option(AG_THEME_NAME_OPTION, AG_THEME_FOLDER, 'option', 'display_comments', true);
        if ($displayComments && comments_open($post)) {
            return true;
        }
        return false;
    }
}

if (!function_exists('ag_customize_preview_assets')) {
    /**
     * Function add file customizer-controls-styles.css
     *
     * @return void
     */
    function ag_customize_preview_assets()
    {
        wp_enqueue_style(
            'ag-customizer-controls-styles',
            AG_THEME_URI . '/assets/css/customizer-controls-styles.css',
            array(),
            ADVANCED_GUTENBERG_THEME_VERSION
        );
        wp_enqueue_style(
            'ag_theme_style_reset',
            AG_THEME_URI . '/style.css',
            array(),
            ADVANCED_GUTENBERG_THEME_VERSION
        );
    }

    add_action('customize_controls_enqueue_scripts', 'ag_customize_preview_assets');
}

if (!function_exists('ag_customize_preview_js')) {
    /**
     * Function add file theme-customizer.js
     *
     * @return void
     */
    function ag_customize_preview_js()
    {
        wp_enqueue_script(
            'ag-theme-customizer',
            AG_THEME_URI . '/assets/js/theme-customizer.js',
            array('jquery'),
            ADVANCED_GUTENBERG_THEME_VERSION,
            true
        );

        $protocol = is_ssl() ? 'https' : 'http';
        $data_font = apply_filters('get_saved_google_fonts', 'customize');
        $theme_option = ag_get_option(AG_THEME_NAME_OPTION, AG_THEME_FOLDER, 'full', '', array());
        $data_template_default = ag_get_default_option_layouts(true);

        wp_localize_script('ag-theme-customizer', 'mainCustomizerData', array(
            'AG_THEME_NAME_OPTION' => AG_THEME_NAME_OPTION,
            'data_font' => $data_font,
            'protocol' => $protocol,
            'theme_option' => $theme_option,
            'data_template_default' => $data_template_default,
            'link_logo_default' => get_template_directory_uri() . '/assets/images/logo.png',
        ));

        $theme_ju_template_pack = theme_ju_get_layout_pack(array('installed'));
        $count                  = count($theme_ju_template_pack['installed']);
        for ($i = 0; $i < $count; $i ++) {
            $fileDir   = AG_THEME_UPLOAD_FOLDERDIR . '/templates/' . $theme_ju_template_pack['installed'][$i] . '/assets/js/template-customizer.js';
            $fileURL   = AG_THEME_UPLOAD_FOLDERURL . '/templates/' . $theme_ju_template_pack['installed'][$i] . '/assets/js/template-customizer.js';
            if (file_exists($fileDir)) {
                wp_enqueue_script(
                    'ag-template-customizer-' . $theme_ju_template_pack['installed'][$i],
                    $fileURL,
                    array('jquery'),
                    ADVANCED_GUTENBERG_THEME_VERSION,
                    true
                );
                wp_localize_script(
                    'ag-template-customizer-' . $theme_ju_template_pack['installed'][$i],
                    'templateCustomizerData',
                    array(
                        'AG_THEME_NAME_OPTION' => AG_THEME_NAME_OPTION,
                        'data_font'            => $data_font,
                        'protocol'             => $protocol,
                        'theme_option'         => $theme_option,
                    )
                );
            }
        }
    }

    add_action('customize_preview_init', 'ag_customize_preview_js');
}

if (!function_exists('ag_control_customize_preview_js')) {
    /**
     * Function add file customizer-controls-js.js
     *
     * @return void
     */
    function ag_control_customize_preview_js()
    {
        wp_enqueue_script(
            'customizer-alpha-color-picker',
            AG_THEME_INCLUDES_URI . '/assets/customizer-alpha-color-picker/js/alpha-color-picker.js',
            array('jquery', 'wp-color-picker'),
            ADVANCED_GUTENBERG_THEME_VERSION,
            true
        );
        wp_enqueue_style(
            'customizer-alpha-color-picker',
            AG_THEME_INCLUDES_URI . '/assets/customizer-alpha-color-picker/css/alpha-color-picker.css',
            array('wp-color-picker'),
            ADVANCED_GUTENBERG_THEME_VERSION
        );
        wp_enqueue_script(
            'ag-customizer-controls-js',
            AG_THEME_URI . '/assets/js/customizer-controls-js.js',
            array('jquery'),
            ADVANCED_GUTENBERG_THEME_VERSION,
            true
        );

        $data_font = apply_filters('get_saved_google_fonts', 'customize');

        $data_template_default = ag_get_default_option_layouts(true);
        //list custom option was saved in LAYOUT STYLE function
        $oldCustomOption =  get_option('theme_ju_custom_option', array());

        wp_localize_script('ag-customizer-controls-js', 'controlsCustomizerData', array(
            'ajaxurl' => admin_url('admin-ajax.php', 'relative'),
            'AG_THEME_NAME_OPTION' => AG_THEME_NAME_OPTION,
            'data_font' => $data_font,
            'data_template_default' => $data_template_default,
            'oldCustomOption' => $oldCustomOption,
        ));

        $key   = array_keys($data_template_default);
        $count = count($key);

        for ($i = 0; $i < $count; $i ++) {
            $fileDir = AG_THEME_UPLOAD_FOLDERDIR . '/templates/' . $key[$i] . '/assets/js/template-customizer-controls-js.js';
            $fileURL = AG_THEME_UPLOAD_FOLDERURL . '/templates/' . $key[$i] . '/assets/js/template-customizer-controls-js.js';
            if (file_exists($fileDir)) {
                wp_enqueue_script(
                    'ag-customizer-controls-js-' . $key[$i],
                    $fileURL,
                    array('jquery'),
                    ADVANCED_GUTENBERG_THEME_VERSION,
                    true
                );
            }
        }

        wp_localize_script('ag-customizer-controls-js', 'agThemeText', array(
            'defaultThemeFont' => __('Default Theme Font', 'advanced-gutenberg-theme'),
        ));
    }

    add_action('customize_controls_enqueue_scripts', 'ag_control_customize_preview_js');
}

if (!function_exists('ag_theme_load_scripts_styles')) {
    /**
     * Function load style
     *
     * @return void
     */
    function ag_theme_load_scripts_styles()
    {
        /*
         * Loads the main stylesheet.
         */
        $min = '.min';
        if (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) {
            $min = '';
        }
        wp_enqueue_style(
            'ag-theme-style',
            AG_THEME_URI . '/assets/css/style' . $min . '.css',
            array(),
            ADVANCED_GUTENBERG_THEME_VERSION
        );

        /*add child theme*/
        do_action('wp_enqueue_scripts_child', 'child_theme_styles', 20);

        $folderDir = AG_THEME_UPLOAD_FOLDERDIR . DIRECTORY_SEPARATOR;
        $folderUrl = AG_THEME_UPLOAD_FOLDERURL . '/';
        $style_url = $folderUrl . 'style_customize.css';

        if (file_exists($folderDir . 'style_customize.css')) {
            wp_enqueue_style('ag_style', $style_url, array(), ADVANCED_GUTENBERG_THEME_VERSION);
        }

        wp_enqueue_script(
            'ag-controls-js',
            AG_THEME_URI . '/assets/js/theme_ag.js',
            array('jquery'),
            ADVANCED_GUTENBERG_THEME_VERSION,
            true
        );
    }

    add_action('wp_enqueue_scripts', 'ag_theme_load_scripts_styles', 1);
}

if (!function_exists('theme_widgets_init')) {
    /**
     * Function create widgets footer
     *
     * @return void
     */
    function theme_widgets_init()
    {
        // First footer widget area.
        register_sidebar(array(
            'name' => __('First Footer Widget Area', 'advanced-gutenberg-theme'),
            'id' => 'first-footer-widget-area',
            'description' => __('The first footer widget area', 'advanced-gutenberg-theme'),
            'before_widget' => '<div id="%1$s" class="widget-container %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ));

        // Second Footer Widget Area.
        register_sidebar(array(
            'name' => __('Second Footer Widget Area', 'advanced-gutenberg-theme'),
            'id' => 'second-footer-widget-area',
            'description' => __('The second footer widget area', 'advanced-gutenberg-theme'),
            'before_widget' => '<div id="%1$s" class="widget-container %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ));

        // Third Footer Widget Area, located in the footer. Empty by default.
        register_sidebar(array(
            'name' => __('Third Footer Widget Area', 'advanced-gutenberg-theme'),
            'id' => 'third-footer-widget-area',
            'description' => __('The third footer widget area', 'advanced-gutenberg-theme'),
            'before_widget' => '<div id="%1$s" class="widget-container %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ));

        // Fourth Footer Widget Area, located in the footer. Empty by default.
        register_sidebar(array(
            'name' => __('Fourth Footer Widget Area', 'advanced-gutenberg-theme'),
            'id' => 'fourth-footer-widget-area',
            'description' => __('The fourth footer widget area', 'advanced-gutenberg-theme'),
            'before_widget' => '<div id="%1$s" class="widget-container %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ));
    }

    add_action('widgets_init', 'theme_widgets_init');
}

if (!function_exists('my_search_form')) {
    /**
     * Function create form search
     *
     * @param string $form Value
     *
     * @return string
     */
    function my_search_form($form)
    {
        $form = '<form role="search" method="get" id="searchform" class="searchform" action="' . home_url('/') . '" >
        <div>
        <i class="dashicons dashicons-search"></i>
            <input type="text" placeholder="Search" value="' . get_search_query() . '" name="s" id="s"  />
        </div>
    </form>';

        return $form;
    }

    add_filter('get_search_form', 'my_search_form', 100);
}

if (!function_exists('remove_website_field')) {
    /**
     * Function remove, change style field
     *
     * @param array $fields Fields
     *
     * @return mixed
     */
    function remove_website_field($fields)
    {
        if (empty($post_id)) {
            $post_id = get_the_ID();
        }

        $commenter = wp_get_current_commenter();
        $user = wp_get_current_user();
        $user_identity = $user->exists() ? $user->display_name : '';

        $args = array();
        $args['format'] = current_theme_supports('html5', 'comment-form') ? 'html5' : 'xhtml';

        $req = get_option('require_name_email');
        $aria_req = ($req ? " aria-required='true'" : '');
        $html_req = ($req ? " required='required'" : '');
        $html5 = 'html5' === $args['format'];

        $fields['author'] = '<p class="comment-form-author">'
            . ' <input id="author" class="form-control" placeholder="'
            . __('Name *', 'advanced-gutenberg-theme') . '" name="author" type="text" value="'
            . esc_attr($commenter['comment_author']) . '" size="30"' . $aria_req . $html_req . ' /></p>';
        $fields['email'] = '<p class="comment-form-email">'
            . '<input id="email" class="form-control" placeholder="'
            . __('Email *', 'advanced-gutenberg-theme') . '" name="email" '
            . ($html5 ? 'type="email"' : 'type="text"') . ' value="'
            . esc_attr($commenter['comment_author_email']) . '" size="30" aria-describedby="email-notes"'
            . $aria_req . $html_req . ' /></p>';
        $fields['url'] = '<p class="comment-form-url">'
            . '<input id="url" class="form-control" placeholder="'
            . __('Website', 'advanced-gutenberg-theme') . '" name="url" '
            . ($html5 ? 'type="url"' : 'type="text"') . ' value="'
            . esc_attr($commenter['comment_author_url']) . '" size="30" /></p>';

        return $fields;
    }

    add_filter('comment_form_default_fields', 'remove_website_field');
}

if (!function_exists('ag_get_comment_form')) {
    /**
     * Function echo form comment
     *
     * @return void
     */
    function ag_get_comment_form()
    {
        $args = array(
            'id_form' => 'ag_theme_form_comment',
            'id_submit' => 'ag_theme_submit_comment',
            'comment_notes_before' => '',
            'comment_field' => '<p class="comment-form-comment">
<textarea id="comment_textarea" class="form-control" name="comment"
 placeholder="Comment" cols="45" rows="8"  aria-required="true" required="required"></textarea></p>',
            'title_reply_before'   => '<h5 id="reply-title" class="comment-reply-title">',
            'title_reply_after'    => '</h5>',
        );

        echo esc_attr(comment_form($args));
    }
}

if (!function_exists('ag_get_post_bg_inline_style')) {
    /**
     * Function get post inline style
     *
     * @return string
     */
    function ag_get_post_bg_inline_style()
    {
        $inline_style = '';

        $post_id = get_the_ID();

        $post_use_bg_color = get_post_meta($post_id, '_ag_post_use_bg_color', true) ? true : false;
        $bg_color = get_post_meta($post_id, '_ag_post_bg_color', true);
        $post_bg_color = $bg_color && '' !== $bg_color ? $bg_color : '#ffffff';
        if ($post_use_bg_color) {
            $inline_style = sprintf(' style="background-color: %1$s;"', esc_html($post_bg_color));
        }

        return $inline_style;
    }
}

if (!function_exists('ag_get_post_text_color')) {
    /**
     * Function get post text color
     *
     * @return mixed|string
     */
    function ag_get_post_text_color()
    {
        $text_color_class = '';

        $post_format = get_post_format();

        if (in_array($post_format, array('audio', 'link', 'quote'))) {
            $text_color = get_post_meta(get_the_ID(), '_ag_post_bg_layout', true);
            $text_color_class = $text_color ? $text_color : 'light';
            $text_color_class = ' ag_pb_text_color_' . $text_color_class;
        }

        return $text_color_class;
    }
}

if (!function_exists('ag_pb_get_audio_player')) {
    /**
     * Funtion get audio
     *
     * @return boolean|string
     */
    function ag_pb_get_audio_player()
    {
        $shortcode_audio = do_shortcode('[audio]');
        if ('' === $shortcode_audio) {
            return false;
        }

        $output = '<div class="et_audio_container">' . $shortcode_audio . '</div> <!-- .et_audio_container -->';

        return $output;
    }
}

if (!function_exists('ag_custom_posts_per_page')) {
    /**
     * Function custom posts per page
     *
     * @param boolean $query Query
     *
     * @return void
     */
    function ag_custom_posts_per_page($query = false)
    {
        if (is_admin()) {
            return;
        }

        if (!is_a($query, 'WP_Query') || !$query->is_main_query()) {
            return;
        }
        $customCss = ag_get_option(AG_THEME_NAME_OPTION, AG_THEME_FOLDER, 'full', 'custom', array());
        if ($query->is_category) {
            $query->set('posts_per_page', isset($customCss['numberCat']) ? (int)$customCss['numberCat'] : 6);
        } elseif ($query->is_tag) {
            $query->set('posts_per_page', isset($customCss['numberTag']) ? (int)$customCss['numberTag'] : 5);
        } elseif ($query->is_search) {
            $query->set('posts_per_page', isset($customCss['numberSearch']) ? (int)$customCss['numberSearch'] : 5);
        } elseif ($query->is_archive) {
            $posts_number = isset($customCss['numberArchive']) ? (int)$customCss['numberArchive'] : 5;
            if (function_exists('is_woocommerce') && is_woocommerce()) {
                $posts_number = (int)ag_get_option(
                    AG_THEME_NAME_OPTION,
                    AG_THEME_FOLDER,
                    'option',
                    'custom.numberWooC',
                    '9'
                );
                $query->set('posts_per_page', $posts_number);
            } else {
                $query->set('posts_per_page', $posts_number);
            }
            //Change number or products per row
//            add_filter('loop_shop_columns', create_function('$cols', 'return ' . $posts_number . ';'));
        }
    }

    add_action('pre_get_posts', 'ag_custom_posts_per_page');
}

// load functions for wooCommerce
require_once(AG_THEME_CORE . '/agWooCommerce.php');
new AgWooCommerce();

do_action('advanced_gutenberg_theme_wooCustomImageWidth');

// Load jutranslation helper
include_once('jutranslation' . DIRECTORY_SEPARATOR . 'jutranslation.php');
call_user_func(
    '\Joomunited\ADVGBTHEME\Jutranslation\Jutranslation::init',
    __FILE__,
    'advanced-gutenberg-theme',
    'Advanced Gutenberg Theme',
    'advanced-gutenberg-theme',
    'languages' . DIRECTORY_SEPARATOR . 'advanced-gutenberg-theme-en_US.mo',
    'theme'
);

require_once(AG_THEME_URL . '/juupdater/juupdater.php');

new ThemeUpdateChecker(
    'advanced-gutenberg-theme',
    'https://www.joomunited.com/juupdater_files/advanced-gutenberg-theme.json'
);
$remote_updateinfo = JU_BASE . 'juupdater_files/advanced-gutenberg-theme.json';
//end config

global $UpdateChecker;
$UpdateChecker = Jufactory::buildUpdateChecker(
    $remote_updateinfo,
    __FILE__
);

if (!function_exists('check_theme_setup')) {
    /**
     * Function check wp_version, php version and template packet in theme folder before theme activation
     *
     * @return boolean
     */
    function check_theme_setup()
    {
        $check = false;
        if (version_compare(phpversion(), AG_THEME_REQUIRED_PHP_VERSION, '<')) {
            add_action(
                'admin_notices',
                function () {
                    ?>
                    <script type='text/javascript'>
                        var answer = alert("<?php echo esc_attr__('Your php version is not supported, please contact your webmaster to upgrade the php version!', 'advanced-gutenberg-theme'); ?>");
                        if (typeof answer !== 'undfined') {
                            window.location.reload();
                        }
                    </script>
                    <?php
                }
            );
            $check = true;
        }

        global $wp_version;
        /*add script because wp not reload page after old theme activation*/
        if (!version_compare($wp_version, '5.0.0', '>=')) {
            add_action(
                'admin_notices',
                function () {
                    ?>
                    <script type='text/javascript'>
                        var answer = alert("<?php echo esc_attr__('Your WordPress version is not supported, please contact your webmaster to upgrade the WordPress version!', 'advanced-gutenberg-theme'); ?>");
                        if (typeof answer !== 'undfined') {
                            window.location.reload();
                        }
                    </script>
                    <?php
                }
            );
            $check = true;
        }

        if ($check) {
            $old_theme = get_option('theme_switched');
            switch_theme($old_theme);
        }

        //move template folder from theme folder to uploads
        $template = theme_ju_get_layout_pack(array('templatePacket', 'installed'));

        $count = count($template['templatePacket']);
        if ($count > 0) {
            if (!in_array($template['templatePacket'][0], $template['installed'])) {
                mkdir(AG_THEME_UPLOAD_FOLDERDIR . DIRECTORY_SEPARATOR
                    . 'templates' . DIRECTORY_SEPARATOR . $template['templatePacket'][0], 0777, true);

                recurse_copy(
                    AG_THEME_URL . DIRECTORY_SEPARATOR . 'template' . DIRECTORY_SEPARATOR
                    . $template['templatePacket'][0] . DIRECTORY_SEPARATOR,
                    AG_THEME_UPLOAD_FOLDERDIR . DIRECTORY_SEPARATOR . 'templates'
                    . DIRECTORY_SEPARATOR . $template['templatePacket'][0]
                );
                update_option('theme_ju_new_template', (string)$template['templatePacket'][0]);
                if (file_exists(AG_THEME_URL . DIRECTORY_SEPARATOR . 'template' . DIRECTORY_SEPARATOR
                    . $template['templatePacket'][0] . DIRECTORY_SEPARATOR)) {
                    ag_delete_files(AG_THEME_URL . DIRECTORY_SEPARATOR . 'template' . DIRECTORY_SEPARATOR
                        . $template['templatePacket'][0] . DIRECTORY_SEPARATOR);
                }
            }
        }
        return false;
    }
}
add_action('after_switch_theme', 'check_theme_setup');

if (file_exists(AG_THEME_CORE . '/advance_gutenberg_function.php')) {
    require_once(AG_THEME_CORE . '/advance_gutenberg_function.php');
}

if (file_exists(AG_THEME_CORE . '/seo_function.php')) {
    require_once(AG_THEME_CORE . '/seo_function.php');
}
