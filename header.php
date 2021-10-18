<?php
defined('ABSPATH') || die;
if (function_exists('is_woocommerce') && is_shop()) { //shop
    $page_id = get_option('woocommerce_shop_page_id');
    $page_type = 'shop';
    $field_id_value = get_post_meta($page_id);
} elseif (is_home() && !is_front_page()) { //latest posts page
    $page_for_posts = get_option('page_for_posts');
    if (isset($page_for_posts)) {
        $page_type = 'page';
        $field_id_value = get_post_meta($page_for_posts);
    }
} else {
    if ((is_page() && is_front_page()) || (!is_front_page() && isset($post->ID))) {
        $page_type = 'page';
        $page_id = $post->ID;
        $field_id_value = get_post_meta($page_id);
    }
}

$theme_option = ag_get_option(AG_THEME_NAME_OPTION, AG_THEME_FOLDER, 'full', '', array());
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>"/>
    <meta name="viewport" content="width=device-width">
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>"/>

    <?php wp_head(); ?>
    <style id="wp-ag-custom-css"></style>
    <style id="wp-ag-font-header"></style>
    <style id="wp-ag-font-body"></style>
    <style id="body_font_size"></style>
    <style id="phone_body_font_size"></style>
    <style id="tablet_body_font_size"></style>
    <?php
    $customCss = isset($theme_option['custom']) ? $theme_option['custom'] : array();

    if (isset($customCss['customHead'])) {
        //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- $customCss['customHead'] no need for EscapeOutput
        echo(stripslashes($customCss['customHead']));
    }
    if (isset($field_id_value)) {
        require_once AG_THEME_CORE . '/style_for_meta_box.php';
        //render css from post_option
        ag_theme_style_post($field_id_value);
        //change theme option value by post option
        $args = array('page_type'=> $page_type);
        $theme_option = ag_theme_post_option_render($field_id_value, $theme_option, $args);
    }
    ?>
</head>
<?php
$container_width = isset($theme_option['container_width']) ? $theme_option['container_width'] : '100';
$image_logo = isset($theme_option['img_upload']) ? $theme_option['img_upload'] : '';
$sticky_image_logo = isset($theme_option['sticky_image_upload']) ? $theme_option['sticky_image_upload'] : '';
$full_mobile_menu = isset($theme_option['full_mobile_menu']) ? $theme_option['full_mobile_menu'] : true;

$logo_width = isset($theme_option['logo_width']) ? $theme_option['logo_width'] : '70';
$sticky_logo_width = isset($theme_option['sticky_logo_width']) ? $theme_option['sticky_logo_width'] : '70';

$header_height = isset($theme_option['header_height']) ? $theme_option['header_height'] : '80';
$sticky_header_height = isset($theme_option['sticky_header_height']) ? $theme_option['sticky_header_height'] : '80';

$load_sidebar = isset($theme_option['load_sidebar']) ? $theme_option['load_sidebar'] : true;
$styleFooter = isset($theme_option['custom']['styleFooter']) ? $theme_option['custom']['styleFooter'] : '1';
$select_layout_style = isset($theme_option['select_layout_style']) ? $theme_option['select_layout_style'] : 'Custom';

$class_template = '';

$menus_logo = isset($theme_option['menus_logo']) ? $theme_option['menus_logo'] : 'right';
$menus_logo = str_replace('|', '', $menus_logo);

$header_width = isset($theme_option['header_width']) ? 'header-' . $theme_option['header_width'] : '';

$active_link_color = isset($theme_option['active_link_color']) ? $theme_option['active_link_color'] : '#50a7ec';

$footer_columns = isset($theme_option['footer_columns']) ? $theme_option['footer_columns'] : '4';

/*fixed_header in theme customizer and post customizer*/
$sticky_header = isset($theme_option['sticky_header']) ? $theme_option['sticky_header'] : false;
$fixed_navigation = isset($theme_option['fixed_navigation']) ? $theme_option['fixed_navigation'] : true;
$top_content = isset($theme_option['top_content']) ? $theme_option['top_content'] : true;

$class_template .= $load_sidebar === true ? '' : 'ag_sidebarHide';

if ($sticky_header) {
    $class_template .= ' ag_theme_sticky_header';
}
if ($fixed_navigation) {
    $class_template .= ' ag_theme_fixed_navigation';
}

if (!$top_content) {
    $class_template .= ' ag_theme_top_content ';
}

if ($select_layout_style !== 'Custom') {
    $class_template .= ' ag_layout_style';
}

$class_template .= ' ag_theme_footer_' . (string)$footer_columns;

if ($styleFooter === '1') {
    $class_template .= ' ag_footerTemplate';
}

if ((int)$container_width < 100) {
    $class_template .= ' ag_boxed_layout ';
}

$class_template = apply_filters('advanced_gutenberg_theme_add_class_template', $class_template);

do_action('advanced_gutenberg_theme_wooCustomImageWidth');

/*add style in select_layout_style*/
$select_layout_style = $theme_option['select_layout_style'];
if (isset($select_layout_style) && $select_layout_style !== 'Custom') {
    $templatesDir = AG_THEME_UPLOAD_FOLDERURL . '/templates/';
    $min          = '.min';
    if (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) {
        $min = '';
    }
    wp_enqueue_style(
        'ag-select-layout-style',
        $templatesDir . $select_layout_style . '/assets/css/pages' . $min . '.css',
        array(),
        ADVANCED_GUTENBERG_THEME_VERSION
    );
}
?>
<body id="wp_body_layout_home"
    <?php body_class((string)$class_template); ?>>
<div id="page-container">
    <header id="page-header" data-menu-template="ag-menu-<?php echo esc_attr($menus_logo); ?>"
            data-menu="ag-menu-<?php echo esc_attr($menus_logo);?>" class="ag-menu-<?php echo esc_attr($menus_logo);?> <?php echo esc_attr($header_width); ?>">
        <div class="wrapper_content">
            <div class="logo-header">
                <?php
                if ($image_logo !== '' && $image_logo !== false) {
                    wp_custom_logo($logo_width, $header_height, $image_logo, 'logo_container', is_front_page());
                } else {
                    wp_custom_logo($logo_width, $header_height, $image_logo, 'logo_container', is_front_page(), 'no_img');
                }
                if ($sticky_header && $sticky_image_logo !== '' && $sticky_image_logo !== false) {
                    wp_custom_logo($sticky_logo_width, $sticky_header_height, $sticky_image_logo, 'sticky_logo', is_front_page());
                } elseif ($sticky_header) {
                    wp_custom_logo($sticky_logo_width, $sticky_header_height, $image_logo, 'sticky_logo', is_front_page(), 'no_img');
                }
                ?>
                <div class="show-menu-mobile">
                    <!-- // Use in conjunction with https://gist.github.com/woogists/c0a86397015b88f4ca722782a724ff6c-->
                    <?php
                    if (function_exists('is_woocommerce')) {
                        ?>
                        <a class="ag_theme_cart cart-customlocation" href="<?php esc_url(wc_get_cart_url()); ?>"
                           title="<?php esc_attr_e('View your shopping cart', 'advanced-gutenberg-theme'); ?>">
                            <i class="material-icons-outlined">
                                shopping_cart
                            </i>
                            <span>
                                <?php
                                echo esc_attr(WC()->cart->get_cart_contents_count());
                                ?>
                            </span>
                        </a>
                        <?php
                    }
                    ?>
                    <i class="material-icons open">reorder</i>
                    <i class="material-icons">close</i>
                </div>
            </div>
            <?php
            if ($full_mobile_menu) {
                ?>
                <div class="menu-header curtain_menu">
                <?php
            } else {
                ?>
                <div class="menu-header">
                <?php
            }
            ?>
                <?php wp_nav_menu(array(
                    'theme_location' => 'primary-menu',
                    'container' => 'nav',
                    'container_class' => 'ag-menu ag-header-menu',
                    'menu_class' => 'ag-menu ag-header-menu'
                )); ?>
                <?php
                if (function_exists('is_woocommerce')) {
                    ?>
                    <a class="ag_theme_cart cart-customlocation" href="<?php esc_url(wc_get_cart_url()); ?>"
                       title="<?php esc_attr_e('View your shopping cart', 'advanced-gutenberg-theme'); ?>">
                        <i class="material-icons-outlined">
                            shopping_cart
                        </i>
                        <span>
                            <?php
                            echo esc_attr(WC()->cart->get_cart_contents_count());
                            ?>
                        </span>
                    </a>
                    <?php
                }
                ?>
                <!-- menu in mobile-->
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'mobile-menu',
                    'container' => 'nav',
                    'container_class' => 'ag-hide ag-menu ag-mobile-menu',
                    'menu_class' => 'ag-menu ag-hide ag-mobile-menu'
                ));
                ?>
            </div>
        </div>
    </header>
<?php
