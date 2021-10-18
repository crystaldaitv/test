<?php
defined('ABSPATH') || die;

//default templates
require_once AG_THEME_CORE . '/templates.php';

require_once AG_THEME_CORE . '/functions.php';
/**
 * Function add link customize page in theme options page
 *
 * @return void
 */
function add_ag_customizer_admin_menu()
{
    if (!current_user_can('customize')) {
        return;
    }

    global $wp_admin_bar;

    $current_url   = (is_ssl() ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    $customize_url = add_query_arg('url', urlencode($current_url), wp_customize_url());

    // add Theme Customizer admin menu only if it's enabled for current user
    $wp_admin_bar->add_menu(array(
        'parent' => 'appearance',
        'id'     => 'customize-ag-theme',
        'title'  => esc_html__('Theme Customizer', 'advanced-gutenberg-theme'),
        'href'   => $customize_url . '&ag_customizer_option_set=theme',
        'meta'   => array(
            'class' => 'hide-if-no-customize',
        ),
    ));
    $wp_admin_bar->remove_menu('customize');
}

add_action('admin_bar_menu', 'add_ag_customizer_admin_menu', 999);

/**
 * Create menu
 *
 * @return void
 */
function ag_theme_menu()
{
    require_once AG_THEME_CORE . '/theme_menu_entry.php';

    add_menu_page('Advanced Gutenberg Theme', esc_html__('Theme Options', 'advanced-gutenberg-theme'), 'edit_posts', 'option-theme', 'menu_theme_call', 'dashicons-images-alt2');
    add_submenu_page('option-theme', esc_html__('Live update', 'advanced-gutenberg-theme'), 'Live update', 'manage_options', 'theme-live-update', 'menu_theme_call');
    add_submenu_page('option-theme', esc_html__('Theme Customizer', 'advanced-gutenberg-theme'), esc_html__('Theme Customizer', 'advanced-gutenberg-theme'), 'manage_options', 'customize.php?ag_customizer_option_set=theme');
    add_submenu_page('option-theme', esc_html__('Translation', 'advanced-gutenberg-theme'), 'Translation', 'manage_options', 'theme-translation', 'menu_theme_call');
}

//menu option theme
add_action('admin_menu', 'ag_theme_menu');
