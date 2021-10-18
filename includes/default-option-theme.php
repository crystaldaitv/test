<?php
defined('ABSPATH') || die;
/**
 * Class AgDefaultTheme
 */
class AgDefaultTheme
{
    /**
     * Function agDefaultTheme constructor.
     *
     * @return void
     */
    public function __construct()
    {
    }
    /**
     * Create list default option of theme
     *
     * @return array
     */
    public function defaultOptionTheme()
    {
        $option_style = array(
            array(
                'name' => 'img_upload',
                'properties' => null,
                'value' => false,
                'object' => '',
                'unit' => 'none'
            ),
            array(
                'name' => 'sticky_image_upload',
                'properties' => null,
                'value' => false,
                'object' => '',
                'unit' => 'none'
            ),
            array(
                'name' => 'container_width',
                'properties' => array('width'),
                'value' => '100',
                'object' => array(
                    '.ag_boxed_layout #page-container, body.ag_boxed_layout #page-header.sticky_header, body.ag_theme_fixed_navigation.ag_boxed_layout #page-header ',
                ),
                'unit' => '%',
                'min' => '768'
            ),
            array(
                'name' => 'content_width',
                'properties' => array('width'),
                'value' => '90',
                'object' => array(
                    'body .wrapper_content'
                ),
                'unit' => '%'
            ),
            array(
                'name' => 'max_content_width',
                'properties' => array('max-width'),
                'value' => '1920',
                'object' => array(
                    'body .wrapper_content',
                ),
                'unit' => 'px'
            ),
            array(
                'name' => 'sidebar_width',
                'properties' => 'width',
                'value' => array('28', '78'),
                'object' => array('#sidebar', 'body:not(.ag_sidebarHide)  #content'),
                'unit' => '%'
            ),
            array(
                'name' => 'load_sidebar',
                'properties' => null,
                'value' => true,
                'object' => '',
                'unit' => ''
            ),
            array(
                'name' => 'sidebar_position',
                'properties' => null,
                'value' => 'right',
                'object' => '',
                'unit' => ''
            ),
            array(
                'name' => 'select_layout_style',
                'properties' => null,
                'value' => 'Custom',
                'object' => '',
                'unit' => ''
            ),
            array(
                'name' => 'background_color',
                'properties' => 'background-color',
                'value' => '#fafafa',
                'object' => '#wp_body_layout_home',
                'unit' => 'none'
            ),
            array(
                'name' => 'background_image',
                'properties' => 'background-image',
                'value' => '',
                'object' => '#wp_body_layout_home',
                'unit' => 'url'
            ),
            array(
                'name' => 'body_font_size',
                'properties' => 'font-size',
                'value' => '14',
                'object' => 'html ',
                'unit' => 'px',
                'min' => '1100'
            ),
            array(
                'name' => 'phone_body_font_size',
                'properties' => 'font-size',
                'value' => '11',
                'object' => 'html ',
                'unit' => 'px',
                'max' => '768'
            ),
            array(
                'name' => 'tablet_body_font_size',
                'properties' => 'font-size',
                'value' => '12',
                'object' => 'html ',
                'unit' => 'px',
                'min' => '768',
                'max' => '1100'
            ),
            array(
                'name' => 'body_font_height',
                'properties' => 'line-height',
                'value' => '1.5',
                'object' => '#page-container #main-content',
                'unit' => 'none',
            ),
            array(
                'name' => 'body_header_size',
                'properties' => 'font-size',
                'value' => array(2.5, 2.5 * 0.9, 2.5 * 0.77, 2.5 * 0.64, 2.5 * 0.5, 2.5 * 0.36),
                'object' => array('h1', 'h2', 'h3', 'h4', 'h5', 'h6'),
                'unit' => 'rem',
            ),
            array(
                'name' => 'body_header_spacing',
                'properties' => 'letter-spacing',
                'value' => '0.5',
                'object' => 'h1, h2, h3, h4, h5, h6',
                'unit' => 'px',
            ),
            array(
                'name' => 'body_header_height',
                'properties' => 'line-height',
                'value' => '1.5',
                'object' => 'h1, h2, h3, h4, h5, h6',
                'unit' => 'none',
            ),
            array(
                'name' => 'body_header_style',
                'properties' => array('font-weight', 'font-style', 'text-decoration', 'text-transform'),
                'value' => '|Bold',
                'object' => '#main-content h1,#main-content h2,#main-content h3,
                #main-content h4,#main-content h5,#main-content h6',
                'unit' => 'none',
            ),
            array(
                'name' => 'sidebar_header_style',
                'properties' => array('font-weight', 'font-style', 'text-decoration', 'text-transform'),
                'value' => '|Bold|Uppercase',
                'object' => '#main-content #sidebar h1,#main-content #sidebar h2,#main-content #sidebar h3,
                #main-content #sidebar h4,#main-content #sidebar h5,#main-content #sidebar h6 ',
                'unit' => 'none',
            ),
            array(
                'name' => 'body_font_google',
                'properties' => 'font-family',
                'value' => '',
                'object' => '#wp_body_layout_home #content, #wp_body_layout_home #sidebar .widget-area, #wp_body_layout_home #page-footer #footer-bottom, #wp_body_layout_home #sidebar #searchform input',
                'unit' => '',
            ),
            array(
                'name' => 'link_color',
                'properties' => 'color',
                'value' => '#444',
                'object' => 'article p:not(.post-meta) a, .comment-edit-link, .pinglist a, .pagination a, :not(.entry-title) > a',
                'unit' => 'none',
            ),
            array(
                'name' => 'sidebar_link_color',
                'properties' => 'color',
                'value' => '#42495d',
                'object' => '#main-content #sidebar a, #main-content #sidebar .comment-edit-link, #main-content #sidebar .pinglist a, #main-content #sidebar .pagination a ',
                'unit' => 'none',
            ),
            array(
                'name' => 'font_color',
                'properties' => 'color',
                'value' => '#444444',
                'object' => '#page-container',
                'unit' => 'none',
            ),
            array(
                'name' => 'sidebar_text_color',
                'properties' => 'color',
                'value' => '#91a8c5',
                'object' => '#main-content #sidebar ',
                'unit' => 'none',
            ),
            array(
                'name' => 'header_color',
                'properties' => 'color',
                'value' => '#444',
                'object' => '#main-content h1, #main-content h2,
                #main-content h3, #main-content h4,
                #main-content h5, #main-content h6,
                .entry-title a',
                'unit' => 'none',
            ),
            array(
                'name' => 'sidebar_header_color',
                'properties' => 'color',
                'value' => '#42495d',
                'object' => '#main-content #sidebar h1,#main-content #sidebar h2,#main-content #sidebar h3,#main-content #sidebar h4,#main-content #sidebar h5,#main-content #sidebar h6 ',
                'unit' => 'none',
            ),
            array(
                'name' => 'sidebar_background_color',
                'properties' => 'background-color',
                'value' => '#f9fbfd',
                'object' => '#container #sidebar',
                'unit' => 'none',
            ),
            array(
                'name' => 'header_width',
                'properties' => null,
                'value' => '',
                'object' => '',
                'unit' => '',
            ),
            array(
                'name' => 'menus_logo',
                'properties' => null,
                'value' => '|right',
                'object' => '',
                'unit' => '',
            ),
            array(
                'name' => 'sticky_header',
                'properties' => null,
                'value' => true,
                'object' => '',
                'unit' => ''
            ),
            array(
                'name' => 'fixed_navigation',
                'properties' => null,
                'value' => false,
                'object' => '',
                'unit' => ''
            ),
            array(
                'name' => 'top_content',
                'properties' => null,
                'value' => false,
                'object' => '',
                'unit' => ''
            ),
            array(
                'name' => 'header_height',
                'properties' => 'min-height',
                'value' => '80',
                'object' => '#wp_body_layout_home #page-header',
                'unit' => 'px',
            ),
            array(
                'name' => 'logo_width',
                'properties' => null,
                'value' => '70',
                'object' => '#page-header #logo img',
                'unit' => 'px',
            ),
            array(
                'name' => 'sticky_header_height',
                'properties' => 'min-height',
                'value' => '60',
                'object' => '#wp_body_layout_home #page-header.sticky_header',
                'unit' => 'px',
            ),
            array(
                'name' => 'sticky_background_color',
                'properties' => 'background-color',
                'value' => 'rgba(255,255,255,0.75)',
                'object' => '#page-header.sticky_header ',
                'unit' => 'none',
            ),
            array(
                'name' => 'sticky_active_link_color',
                'properties' => array('color', 'border-bottom-color'),
                'value' => array('#50a7ec', '#50a7ec'),
                'object' => array(
                    '.sticky_header .ag-header-menu .current-menu-parent, .sticky_header .ag-header-menu .current-page-parent,#page-header.sticky_header .ag-header-menu .current-menu-ancestor > a,#page-header.sticky_header .ag-header-menu .current-menu-parent > a,#page-header.sticky_header .ag-header-menu .current-menu-item > a,#page-header.sticky_header .ag-header-menu .current_page_item > a',
                    '#page-header.sticky_header .ag-header-menu > .current-menu-itemm,#page-header.sticky_header .ag-header-menu > .current_page_item',
                ),
                'unit' => 'none',
            ),
            array(
                'name' => 'sticky_text_color',
                'properties' => 'color',
                'value' => '#444',
                'object' => '#page-header.sticky_header ag-header-menu>a, #page-header.sticky_header .ag-header-menu li a, .sticky_header .menu-header .cart-customlocation span, .sticky_header .menu-header .cart-customlocation i',
                'unit' => 'none',
            ),
            array(
                'name' => 'sticky_menu_background_color',
                'properties' => 'background-color',
                'value' => 'rgba(255,255,255,0)',
                'object' => array(
                    '.sticky_header .ag-header-menu>ul>li>a, #page-header.sticky_header .menu-header .ag_theme_cart',
                    '#page-header.sticky_header .ag-header-menu > ul >li, #page-header.sticky_header .menu-header .ag_theme_cart'
                ),
                'unit' => 'none',
            ),
            array(
                'name' => 'sticky_logo_width',
                'properties' => null,
                'value' => '70',
                'object' => '#page-header .sticky_logo img',
                'unit' => 'px',
            ),
            array(
                'name' => 'header_font_google',
                'properties' => 'font-family',
                'value' => '',
                'object' => '#page-header',
                'unit' => '',
            ),
            array(
                'name' => 'header_background_color',
                'properties' => 'background-color',
                'value' => 'rgba(255,255,255,0.85)',
                'object' => '#page-header',
                'unit' => 'none',
            ),
            array(
                'name' => 'header_border_color',
                'properties' => 'border-color',
                'value' => '#7a8da733',
                'object' => '#wp_body_layout_home #page-header',
                'unit' => 'none',
            ),
            array(
                'name' => 'header_border_size',
                'properties' => 'border-width',
                'value' => '0.5',
                'object' => '#wp_body_layout_home #page-header',
                'unit' => 'px',
            ),
            array(
                'name' => 'menu_height',
                'properties' => array('line-height', 'line-height', 'height', 'min-height', 'padding-top'),
                'value' => '40',
                'object' => array(
                    '#page-header .ag-header-menu li',
                    '#page-header .ag_theme_cart, .show-menu-mobile i, #page-header .logo-header',
                    '#page-header .ag-header-menu li, #page-header .ag_theme_cart, .show-menu-mobile i, #page-header .logo-header',
                    '#page-header .ag-header-menu ul',
                    '#page-header .curtain_menu nav:last-child>ul'
                ),
                'unit' => 'px',
            ),
            array(
                'name' => 'mobile_menu_height',
                'properties' => array('line-height'),
                'value' => '40',
                'object' => array(
                    'body #page-header .menu-header .ag-mobile-menu > ul li'
                ),
                'unit' => 'px',
            ),
            array(
                'name' => 'background_header_image',
                'properties' => 'background-image',
                'value' => '',
                'object' => '#page-header',
                'unit' => 'url',
            ),
            array(
                'name' => 'text_size_menu',
                'properties' => 'font-size',
                'value' => '14',
                'object' => '#wp_body_layout_home #page-header .ag-header-menu>ul>li>a',
                'unit' => 'px',
            ),
            array(
                'name' => 'mobile_text_size_menu',
                'properties' => 'font-size',
                'value' => '12',
                'object' => '#wp_body_layout_home #page-header .ag-mobile-menu>ul a',
                'unit' => 'px',
            ),
            array(
                'name' => 'text_size_sub_menu',
                'properties' => 'font-size',
                'value' => '16',
                'object' => '#wp_body_layout_home #page-header .ag-header-menu .sub-menu a',
                'unit' => 'px',
            ),
            array(
                'name' => 'border_top_sub_menu',
                'properties' => array('border-top-width'),
                'value' => array('1'),
                'object' => '#page-header .ag-header-menu .sub-menu',
                'unit' => array('px')
            ),
            array(
                'name' => 'sub_menu_width',
                'properties' => array('width', 'left'),
                'value' => array('200', '200'),
                'object' => array(
                    '#wp_body_layout_home #page-header .ag-menu li:hover > .sub-menu',
                    '#wp_body_layout_home #page-header .ag-footer-menu .sub-menu .sub-menu'
                ),
                'unit' => 'px'
            ),
            array(
                'name' => 'font_style',
                'properties' => array('font-weight', 'font-style', 'text-decoration', 'text-transform'),
                'value' => '',
                'object' => '#wp_body_layout_home #page-header .ag-header-menu ul a,#wp_body_layout_home #page-header .ag-header-menu li a ',
                'unit' => 'none',
            ),
            array(
                'name' => 'mobile_font_style',
                'properties' => array('font-weight', 'font-style', 'text-decoration', 'text-transform'),
                'value' => '',
                'object' => '#wp_body_layout_home #page-header .ag-mobile-menu ul a,#wp_body_layout_home #page-header .ag-mobile-menu li a ',
                'unit' => 'none',
            ),
            array(
                'name' => 'text_color',
                'properties' => 'color',
                'value' => '#222',
                'object' => '#page-header .ag-header-menu>a, #page-header .ag-header-menu li a, .menu-header .cart-customlocation span, .menu-header .cart-customlocation i',
                'unit' => 'none',
            ),
            array(
                'name' => 'mobile_text_color',
                'properties' => 'color',
                'value' => '#444',
                'object' => '.ag-mobile-menu>a, .ag-mobile-menu>ul>li a, .show-menu-mobile .cart-customlocation span, .show-menu-mobile i',
                'unit' => 'none',
            ),
            array(
                'name' => 'mobile_active_link_color',
                'properties' => array('color'),
                'value' => array('#50a7ec'),
                'object' => array(
                    '.ag-mobile-menu .current-menu-item > a, .ag-mobile-menu .current_page_item > a'
                ),
                'unit' => 'none',
            ),
            array(
                'name' => 'full_mobile_menu',
                'properties' => null,
                'value' => true,
                'object' => '',
                'unit' => ''
            ),
            array(
                'name' => 'position_mobile_menu',
                'properties' => 'text-align',
                'value' => 'left',
                'object' => '#wp_body_layout_home #page-header .ag-mobile-menu li a ',
                'unit' => ''
            ),
            array(
                'name' => 'active_link_color',
                'properties' => array('color', 'border-bottom-color'),
                'value' => array('#50a7ec', '#50a7ec'),
                'object' => array(
                    '.ag-header-menu .current-menu-parent, .ag-header-menu .current-page-parent,#page-header .ag-header-menu .current-menu-ancestor > a,#page-header .ag-header-menu .current-menu-parent > a,#page-header .ag-header-menu .current-menu-item > a,#page-header .ag-header-menu .current_page_item > a',
                    '#page-header .ag-header-menu > .current-menu-itemm,#page-header .ag-header-menu > .current_page_item',
                ),
                'unit' => 'none',
            ),
            array(
                'name' => 'menu_background_color',
                'properties' => array('background-color', 'border-bottom-color'),
                'value' => array('#ffffff', '#ffffff'),
                'object' => array(
                    '.ag-header-menu>ul>li>a, #page-header .menu-header .ag_theme_cart',
                    '#page-header .ag-header-menu > ul >li, #page-header .menu-header .ag_theme_cart'
                ),
                'unit' => 'none',
            ),
            array(
                'name' => 'mobile_hover_color',
                'properties' => array('background-color'),
                'value' => array('#cfcfcf'),
                'object' => array(
                    '#page-header .menu-header .ag-mobile-menu ul a:hover'
                ),
                'unit' => 'none',
            ),
            array(
                'name' => 'mobile_menu_background_color',
                'properties' => array('background-color'),
                'value' => array('#ffffff'),
                'object' => array(
                    '#page-header .menu-header .ag-mobile-menu ul'
                ),
                'unit' => 'none',
            ),
            array(
                'name' => 'drop_menu_background_color',
                'properties' => 'background-color',
                'value' => '#ffffff',
                'object' => '#page-header .ag-header-menu .sub-menu>li, #page-header .ag-header-menu li .sub-menu',
                'unit' => 'none',
            ),
            array(
                'name' => 'drop_menu_background_color_hover',
                'properties' => 'background-color',
                'value' => '#50a7ec1a',
                'object' => '#page-header .ag-header-menu .sub-menu li:hover',
                'unit' => 'none',
            ),
            array(
                'name' => 'drop_menu_line_color',
                'properties' => 'border-color',
                'value' => '#fafafa',
                'object' => '#page-header .ag-header-menu .sub-menu',
                'unit' => 'none',
            ),
            array(
                'name' => 'drop_menu_text_color',
                'properties' => 'color',
                'value' => '#000000',
                'object' => '#page-header .ag-header-menu li ul a',
                'unit' => 'none',
            ),
            array(
                'name' => 'footer_menu',
                'properties' => 'text-align',
                'value' => 'left',
                'object' => '#wp_body_layout_home #page-footer #footer-nav',
                'unit' => '',
            ),
            array(
                'name' => 'footer_columns',
                'properties' => null,
                'value' => '3_1_1_1',
                'object' => '',
                'unit' => '',
            ),
            array(
                'name' => 'footer_menu_background_color',
                'properties' => 'background-color',
                'value' => '#292626',
                'object' => '#footer-nav, #footer-nav ul, #footer-nav .ag-footer-menu li,
                 #footer-nav .ag-footer-menu li a',
                'unit' => 'none',
            ),
            array(
                'name' => 'footer_background_color',
                'properties' => 'background-color',
                'value' => '#292626',
                'object' => '#wp_body_layout_home #page-footer',
                'unit' => 'none',
            ),
            array(
                'name' => 'custom_footer_credits',
                'properties' => null,
                'value' => 'Designed by JoomUnited | Powered by WordPress',
                'object' => '',
                'unit' => '',
            ),
            array(
                'name' => 'position_footer_credits',
                'properties' => 'text-align',
                'value' => 'left',
                'object' => 'body #page-footer #page-copyright',
                'unit' => '',
            ),
            array(
                'name' => 'widgets_header_text_size',
                'properties' => 'font-size',
                'value' => array(3, 3 * 0.9, 3 * 0.77, 3 * 0.64, 3 * 0.5, 3 * 0.36),
                'object' => array(
                    '#footer-widgets h1',
                    '#footer-widgets h2',
                    '#footer-widgets h3',
                    '#footer-widgets h4',
                    '#footer-widgets h5',
                    '#footer-widgets h6'
                ),
                'unit' => 'rem',
            ),
            array(
                'name' => 'widgets_header_font_style',
                'properties' => array('font-weight', 'font-style', 'text-decoration', 'text-transform'),
                'value' => '|bold|initial|initial|initial',
                'object' => '#footer-widgets h1,#footer-widgets h2,#footer-widgets h3,
                #footer-widgets h4,#footer-widgets h5,#footer-widgets h6',
                'unit' => 'none',
            ),
            array(
                'name' => 'widgets_body_link_text_size',
                'properties' => 'font-size',
                'value' => '1.02',
                'object' => '#footer-bottom',
                'unit' => 'rem',
            ),
            array(
                'name' => 'widgets_body_link_line_height',
                'properties' => 'line-height',
                'value' => '1.7',
                'object' => '#footer-widgets ol, #footer-widgets ul, #footer-widgets div,
                 #footer-widgets h1, #footer-widgets h2, #footer-widgets h3,
                  #footer-widgets h4, #footer-widgets h5, #footer-widgets h6',
                'unit' => 'none',
            ),
            array(
                'name' => 'widgets_body_font_style',
                'properties' => array('font-weight', 'font-style', 'text-decoration', 'text-transform'),
                'value' => '',
                'object' => '#footer-widgets, #footer-widgets ul, #footer-widgets ol, #footer-widgets li,
                 #footer-widgets p, #footer-widgets span, #footer-widgets a, #footer-widgets .custom-html-widget ',
                'unit' => 'none',
            ),
            array(
                'name' => 'widget_text_color',
                'properties' => 'color',
                'value' => '#eeeeee',
                'object' => '#footer-widgets',
                'unit' => 'none',
            ),
            array(
                'name' => 'widget_link_color',
                'properties' => 'color',
                'value' => '#eeeeee',
                'object' => '#footer-widgets a',
                'unit' => 'none',
            ),
            array(
                'name' => 'widget_header_color',
                'properties' => 'color',
                'value' => '#eeeeee',
                'object' => '#footer-widgets  h1,#footer-widgets  h2,#footer-widgets  h3,#footer-widgets  h4,#footer-widgets  h5,#footer-widgets  h6',
                'unit' => 'none',
            ),
            array(
                'name' => 'padding_top_footer',
                'properties' => 'padding-top',
                'value' => '160',
                'object' => ' #footer-bottom ',
                'unit' => 'px',
            ),
            array(
                'name' => 'padding_bottom_footer',
                'properties' => 'padding-bottom',
                'value' => '50',
                'object' => ' #footer-bottom',
                'unit' => 'px',
            ),
            array(
                'name' => 'line_height_footer_credit',
                'properties' => 'line-height',
                'value' => '2.5',
                'object' => ' #page-copyright',
                'unit' => 'none',
            ),
            array(
                'name' => 'footer_credit_color',
                'properties' => 'color',
                'value' => 'rgba(255,255,255,0.5)',
                'object' => '#page-copyright',
                'unit' => 'none',
            ),
            array(
                'name' => 'display_author',
                'properties' => null,
                'value' => false,
                'object' => '',
                'unit' => '',
            ),
            array(
                'name' => 'display_date',
                'properties' => null,
                'value' => true,
                'object' => '',
                'unit' => '',
            ),
            array(
                'name' => 'display_category',
                'properties' => null,
                'value' => false,
                'object' => '',
                'unit' => '',
            ),
            array(
                'name' => 'display_comments',
                'properties' => null,
                'value' => false,
                'object' => '',
                'unit' => '',
            ),
            array(
                'name' => 'custom',
                'properties' => null,
                'value' => array(
                    'numberWooC' => '9',
                    'numberCat' => '6',
                    'numberArchive' => '5',
                    'numberSearch' => '5',
                    'numberTag' => '5',
                    'customCss' => '',
                    'customHead' => '',
                    'customBody' => '',
                    'agBreadcrumb' => '1',
                    'styleFooter' => '1',
                    'wooButtonStyle' => '1',
                    'agWooSingle' => 1000,
                    'templateFooter' => array(),
                ),
                'object' => '',
                'unit' => '',
            ),
            array(
                'name' => 'check_customize',
                'properties' => null,
                'value' => '1',
                'object' => '',
                'unit' => '',
            ),
            array(
                'name' => 'header_divider',
                'properties' => 'border',
                'keyValue' => array('0', '1'),
                'value' => array('', 'none'),
                'object' => '#wp_body_layout_home #page-header',
                'unit' => '',
            ),
        );
        return $option_style;
    }

    /**
     * Get list option not render to style_customize.css
     *
     * @return array
     */
    public function valueNotRender()
    {
        $data = array(
            'header_divider',
            'display_author',
            'check_customize',
            'custom',
            'display_category',
            'display_comments',
            'display_date',
            'custom_footer_credits',
            'boxed_layout',
            'load_sidebar',
            'select_layout_style',
            'menus_logo',
            'sticky_header',
            'fixed_navigation',
            'top_content',
            'logo_width',
            'footer_columns',
            'full_mobile_menu',
            'sticky_logo_width',
            'header_width'
        );

        return $data;
    }

    /**
     * Check menu and post exist function
     *
     * @return array
     */
    public function checkMenuPages()
    {
        $data = array();
        /*get list menu*/
        $data['menu'] = get_terms('nav_menu');

        /*get list post, page*/
        $data['post'] = get_posts();
        $data['page'] = get_pages();

        return $data;
    }
}
