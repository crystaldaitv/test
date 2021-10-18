<?php
defined('ABSPATH') || die;

//add class customize control
require_once AG_THEME_CORE . '/customize_control.php';

/**
 * Function create customize
 *
 * @param object $wp_customize Wp customize
 * @param string $nameTheme    Name theme
 *
 * @return void
 */
function ag_create_customize_option_setting($wp_customize, $nameTheme)
{
    // Logo & Title
    $wp_customize->add_section('title_tagline', array(
        'title'       => esc_html__('Logo & Title', 'advanced-gutenberg-theme'),
        'description' => 'Logo & Title',
        'priority'    => 1,
    ));
    $wp_customize->add_setting(
        $nameTheme . '[sticky_image_upload]',
        array(
            'default'   => get_template_directory_uri() . '/assets/images/logo.png',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport' => 'postMessage',
        )
    );
    $wp_customize->add_control(
        new WP_Customize_Image_Control(
            $wp_customize,
            $nameTheme . '[sticky_image_upload]',
            array(
                'label'    => esc_html__('Sticky Logo Image', 'advanced-gutenberg-theme'),
                'section'  => 'title_tagline',
                'settings' => $nameTheme . '[sticky_image_upload]',
            )
        )
    );
    $wp_customize->add_setting($nameTheme . '[sticky_logo_width]', array(
        'default'           => '70',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control(
        new ThemeCustomizeControl(
            $wp_customize,
            $nameTheme . '[sticky_logo_width]',
            array(
                'label'       => esc_html__('Sticky Logo width:', 'advanced-gutenberg-theme'),
                'tooltiptext' => esc_html__('Define a Sticky logo width and generate a thumbnail', 'advanced-gutenberg-theme'),
                'section'     => 'title_tagline',
                'type'        => 'range',
                'input_attrs' => array(
                    'min'  => 1,
                    'max'  => 700,
                    'step' => 1
                ),
            )
        )
    );
    $wp_customize->add_setting(
        $nameTheme . '[img_upload]',
        array(
            'default'   => get_template_directory_uri() . '/assets/images/logo.png',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport' => 'postMessage',
        )
    );
    $wp_customize->add_control(
        new WP_Customize_Image_Control(
            $wp_customize,
            $nameTheme . '[img_upload]',
            array(
                'label'    => esc_html__('Logo Image', 'advanced-gutenberg-theme'),
                'section'  => 'title_tagline',
                'settings' => $nameTheme . '[img_upload]',
            )
        )
    );
    $wp_customize->add_setting($nameTheme . '[logo_width]', array(
        'default'           => '70',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control(
        new ThemeCustomizeControl(
            $wp_customize,
            $nameTheme . '[logo_width]',
            array(
                'label'       => esc_html__('Logo width:', 'advanced-gutenberg-theme'),
                'tooltiptext' => esc_html__('Define a logo width and generate a thumbnail', 'advanced-gutenberg-theme'),
                'section'     => 'title_tagline',
                'type'        => 'range',
                'input_attrs' => array(
                    'min'  => 1,
                    'max'  => 700,
                    'step' => 1
                ),
            )
        )
    );

    // General Settings
    $wp_customize->add_panel('ag_theme_general_settings', array(
        'title'    => esc_html__('General Settings', 'advanced-gutenberg-theme'),
        'priority' => 2,
    ));

    // Layout Settings
    $wp_customize->add_section('ag_theme_general_layout', array(
        'title' => esc_html__('Layout Settings', 'advanced-gutenberg-theme'),
        'panel' => 'ag_theme_general_settings',
    ));

    $wp_customize->add_setting($nameTheme . '[check_customize]', array(
        'default'           => false,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    $wp_customize->add_control($nameTheme . '[check_customize]', array(
        'label'    => esc_html__('check customize', 'advanced-gutenberg-theme'),
        'section'  => 'ag_theme_general_layout',
        'type'     => 'checkbox',
        'settings' => $nameTheme . '[check_customize]',
    ));

    $wp_customize->add_setting($nameTheme . '[container_width]', array(
        'default'           => '100',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control(
        new ThemeCustomizeControl(
            $wp_customize,
            $nameTheme . '[container_width]',
            array(
                'label'       => esc_html__('Website Width (%)', 'advanced-gutenberg-theme'),
                'section'     => 'ag_theme_general_layout',
                'type'        => 'range',
                'input_attrs' => array(
                    'min'  => 50,
                    'max'  => 100,
                    'step' => 0.5
                ),
            )
        )
    );

    $wp_customize->add_setting($nameTheme . '[content_width]', array(
        'default'           => '90',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control(
        new ThemeCustomizeControl(
            $wp_customize,
            $nameTheme . '[content_width]',
            array(
            'label'       => esc_html__('Website Content Width (%)', 'advanced-gutenberg-theme'),
            'section'     => 'ag_theme_general_layout',
            'type'        => 'range',
            'input_attrs' => array(
                'min'  => 50,
                'max'  => 100,
                'step' => 0.5
                ),
            )
        )
    );

    $wp_customize->add_setting($nameTheme . '[max_content_width]', array(
        'default'           => '1920',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control(
        new ThemeCustomizeControl(
            $wp_customize,
            $nameTheme . '[max_content_width]',
            array(
                'label'       => esc_html__('Max Content Width (px)', 'advanced-gutenberg-theme'),
                'section'     => 'ag_theme_general_layout',
                'type'        => 'range',
                'input_attrs' => array(
                    'min'  => 720,
                    'max'  => 2560,
                    'step' => 1
                ),
            )
        )
    );

    $wp_customize->add_setting($nameTheme . '[load_sidebar]', array(
        'default'           => true,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    $wp_customize->add_control($nameTheme . '[load_sidebar]', array(
        'label'    => esc_html__('Load Sidebar', 'advanced-gutenberg-theme'),
        'section'  => 'ag_theme_general_layout',
        'type'     => 'checkbox',
        'settings' => $nameTheme . '[load_sidebar]',
    ));

    $wp_customize->add_setting($nameTheme . '[sidebar_position]', array(
        'default'       => 'right',
        'type'          => 'option',
        'capability'    => 'edit_theme_options',
        'transport'     => 'postMessage',
        'sanitize_callback' => 'ag_sanitize_font_style',
    ));
    $wp_customize->add_control(new ThemeCustomizeControl($wp_customize, $nameTheme . '[sidebar_position]', array(
        'label'   => esc_html__('Sidebar Position', 'advanced-gutenberg-theme'),
        'section' => 'ag_theme_general_layout',
        'type'    => 'font_style',
        'icon'    => '',
        'choices' => theme_font_style_choices('sidebar_position'),
    )));

    $wp_customize->add_setting($nameTheme . '[select_layout_style]', array(
        'default'       => 'Custom',
        'type'          => 'option',
        'capability'    => 'edit_theme_options',
        'transport'     => 'postMessage',
    ));
    $wp_customize->add_control(new ThemeCustomizeControl($wp_customize, $nameTheme . '[select_layout_style]', array(
        'label'   => esc_html__('LAYOUT STYLE (header and footer)', 'advanced-gutenberg-theme'),
        'section' => 'ag_theme_general_layout',
        'type'    => 'select_option',
        'choices' => theme_selecter_template_choices(),
    )));

    // background
    $wp_customize->add_section('ag_theme_general_background', array(
        'title' => esc_html__('Background', 'advanced-gutenberg-theme'),
        'panel' => 'ag_theme_general_settings',
    ));

    $wp_customize->add_setting(
        $nameTheme . '[background_color]',
        array(
        'default'       => '#fafafa',
        'type'          => 'option',
        'capability'    => 'edit_theme_options',
        'transport'     => 'postMessage',
        'sanitize_callback' => 'ag_sanitize_alpha_color',
        )
    );
    $wp_customize->add_control(new ThemeCustomizeControl($wp_customize, $nameTheme . '[background_color]', array(
        'label' => esc_html__('Background Color', 'advanced-gutenberg-theme'),
        'section' => 'ag_theme_general_background',
        'show_opacity' => true,
        'type'         => 'alpha-color',
        'palette'      => array(
            'rgb(0, 0, 0)',
            'rgba( 255, 255, 255, 0.2 )',
            'rgb(221, 51, 51)',
            'rgb(221, 153, 51)',
            'rgb(238, 238, 34)',
            'rgb(129, 215, 66)',
            'rgb(30, 115, 190)',
            'rgb(130, 36, 227)'
        )
    )));

    $wp_customize->add_setting(
        'background_image',
        array(
        'default'   => '',
        'transport' => 'postMessage',
        )
    );
    $wp_customize->add_control(
        new WP_Customize_Image_Control(
            $wp_customize,
            'background_image',
            array(
                'label'    => esc_html__('Background Image', 'advanced-gutenberg-theme'),
                'section'  => 'ag_theme_general_background',
                'settings' => 'background_image',
            )
        )
    );

    // typography
    $wp_customize->add_section('ag_theme_general_typography', array(
        'title' => esc_html__('Typography', 'advanced-gutenberg-theme'),
        'panel' => 'ag_theme_general_settings',
    ));

    $wp_customize->add_setting($nameTheme . '[body_font_size]', array(
        'default'           => '14',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control(
        new ThemeCustomizeControl(
            $wp_customize,
            $nameTheme . '[body_font_size]',
            array(
                'label'       => esc_html__('Body Text Size (px)', 'advanced-gutenberg-theme'),
                'section'     => 'ag_theme_general_typography',
                'type'        => 'range',
                'input_attrs' => array(
                    'min'  => 10,
                    'max'  => 32,
                    'step' => 1
                ),
            )
        )
    );
    $wp_customize->add_setting($nameTheme . '[phone_body_font_size]', array(
        'default'           => '9',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control(new ThemeCustomizeControl($wp_customize, $nameTheme . '[phone_body_font_size]', array(
        'label'       => esc_html__('Phone Body Text Size (px)', 'advanced-gutenberg-theme'),
        'section'     => 'ag_theme_general_typography',
        'type'        => 'range',
        'input_attrs' => array(
            'min'  => 10,
            'max'  => 32,
            'step' => 1
        ),
    )));
    $wp_customize->add_setting($nameTheme . '[tablet_body_font_size]', array(
        'default'           => '11',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control(new ThemeCustomizeControl($wp_customize, $nameTheme . '[tablet_body_font_size]', array(
        'label'       => esc_html__('Table Body Text Size (px)', 'advanced-gutenberg-theme'),
        'section'     => 'ag_theme_general_typography',
        'type'        => 'range',
        'input_attrs' => array(
            'min'  => 10,
            'max'  => 32,
            'step' => 1
        ),
    )));
    $wp_customize->add_setting($nameTheme . '[body_font_height]', array(
        'default'           => '1.5',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'ag_sanitize_float_number',
    ));
    $wp_customize->add_control(new ThemeCustomizeControl($wp_customize, $nameTheme . '[body_font_height]', array(
        'label'       => esc_html__('Body Line Height', 'advanced-gutenberg-theme'),
        'section'     => 'ag_theme_general_typography',
        'type'        => 'range',
        'input_attrs' => array(
            'min'  => 0.8,
            'max'  => 5,
            'step' => 0.1
        ),
    )));
    $wp_customize->add_setting($nameTheme . '[body_header_size]', array(
        'default'           => '2.5',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control(new ThemeCustomizeControl($wp_customize, $nameTheme . '[body_header_size]', array(
        'label'       => esc_html__('Header Text Size (rem)', 'advanced-gutenberg-theme'),
        'section'     => 'ag_theme_general_typography',
        'type'        => 'range',
        'input_attrs' => array(
            'min'  => 0.5,
            'max'  => 5,
            'step' => 0.1
        ),
    )));
    $wp_customize->add_setting($nameTheme . '[body_header_spacing]', array(
        'default'           => '0',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'ag_sanitize_int_number',
    ));
    $wp_customize->add_control(new ThemeCustomizeControl($wp_customize, $nameTheme . '[body_header_spacing]', array(
        'label'       => esc_html__('Header Letter Spacing', 'advanced-gutenberg-theme'),
        'section'     => 'ag_theme_general_typography',
        'type'        => 'range',
        'input_attrs' => array(
            'min'  => -2,
            'max'  => 5,
            'step' => 0.5
        ),
    )));
    $wp_customize->add_setting($nameTheme . '[body_header_height]', array(
        'default'           => '1.5',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'ag_sanitize_float_number',
    ));
    $wp_customize->add_control(new ThemeCustomizeControl($wp_customize, $nameTheme . '[body_header_height]', array(
        'label'       => esc_html__('Header Line Height', 'advanced-gutenberg-theme'),
        'section'     => 'ag_theme_general_typography',
        'type'        => 'range',
        'input_attrs' => array(
            'min'  => 0.8,
            'max'  => 5,
            'step' => 0.1
        ),
    )));
    $wp_customize->add_setting($nameTheme . '[body_header_style]', array(
        'default'       => '|Bold',
        'type'          => 'option',
        'capability'    => 'edit_theme_options',
        'transport'     => 'postMessage',
        'sanitize_callback' => 'ag_sanitize_font_style',
    ));
    $wp_customize->add_control(new ThemeCustomizeControl($wp_customize, $nameTheme . '[body_header_style]', array(
        'label'   => esc_html__('Header Font Style', 'advanced-gutenberg-theme'),
        'section' => 'ag_theme_general_typography',
        'type'    => 'font_style',
        'icon'    => 'material-icons',
        'choices' => theme_font_style_choices(),
    )));
    $wp_customize->add_setting($nameTheme . '[body_font_google]', array(
        'default'       => '',
        'type'          => 'option',
        'capability'    => 'edit_theme_options',
        'transport'     => 'postMessage',
        'sanitize_callback' => 'ag_sanitize_font_style',
    ));
    $wp_customize->add_control(new ThemeCustomizeControl($wp_customize, $nameTheme . '[body_font_google]', array(
        'label'   => esc_html__('Body font', 'advanced-gutenberg-theme'),
        'section' => 'ag_theme_general_typography',
        'type'    => 'select_option_font',
        'choices' => theme_font_google_choices(),
    )));
    $wp_customize->add_setting($nameTheme . '[link_color]', array(
        'default'    => '#444',
        'type'       => 'option',
        'capability' => 'edit_theme_options',
        'transport'  => 'postMessage',
        'sanitize_callback' => 'ag_sanitize_alpha_color',
    ));
    $wp_customize->add_control(new ThemeCustomizeControl($wp_customize, $nameTheme . '[link_color]', array(
        'label'    => esc_html__('Body Link Color', 'advanced-gutenberg-theme'),
        'section'  => 'ag_theme_general_typography',
        'show_opacity' => true,
        'type'         => 'alpha-color',
        'palette'      => array(
            'rgb(0, 0, 0)',
            'rgba( 255, 255, 255, 0.2 )',
            'rgb(221, 51, 51)',
            'rgb(221, 153, 51)',
            'rgb(238, 238, 34)',
            'rgb(129, 215, 66)',
            'rgb(30, 115, 190)',
            'rgb(130, 36, 227)'
        )
    )));
    $wp_customize->add_setting(
        $nameTheme . '[font_color]',
        array(
            'default'           => '#444444',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'postMessage',
            'sanitize_callback' => 'ag_sanitize_alpha_color',
        )
    );
    $wp_customize->add_control(new ThemeCustomizeControl($wp_customize, $nameTheme . '[font_color]', array(
        'label'    => esc_html__('Body Text Color', 'advanced-gutenberg-theme'),
        'section'  => 'ag_theme_general_typography',
        'show_opacity' => true,
        'type'         => 'alpha-color',
        'palette'      => array(
            'rgb(0, 0, 0)',
            'rgba( 255, 255, 255, 0.2 )',
            'rgb(221, 51, 51)',
            'rgb(221, 153, 51)',
            'rgb(238, 238, 34)',
            'rgb(129, 215, 66)',
            'rgb(30, 115, 190)',
            'rgb(130, 36, 227)'
        )
    )));
    $wp_customize->add_setting($nameTheme . '[header_color]', array(
        'default'           => '#444',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'ag_sanitize_alpha_color',
    ));
    $wp_customize->add_control(new ThemeCustomizeControl($wp_customize, $nameTheme . '[header_color]', array(
        'label'    => esc_html__('Header Text Color', 'advanced-gutenberg-theme'),
        'section'  => 'ag_theme_general_typography',
        'show_opacity' => true,
        'type'         => 'alpha-color',
        'palette'      => array(
            'rgb(0, 0, 0)',
            'rgba( 255, 255, 255, 0.2 )',
            'rgb(221, 51, 51)',
            'rgb(221, 153, 51)',
            'rgb(238, 238, 34)',
            'rgb(129, 215, 66)',
            'rgb(30, 115, 190)',
            'rgb(130, 36, 227)'
        )
    )));

    //sidebar setting
    $wp_customize->add_section('ag_theme_general_sidebar', array(
        'title'       => esc_html__('Sidebar', 'advanced-gutenberg-theme'),
        'priority'    => 4,
    ));
    $wp_customize->add_setting($nameTheme . '[sidebar_width]', array(
        'default'           => '28',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control(
        new ThemeCustomizeControl(
            $wp_customize,
            $nameTheme . '[sidebar_width]',
            array(
                'label'       => esc_html__('Website Sidebar Width (%)', 'advanced-gutenberg-theme'),
                'section'     => 'ag_theme_general_sidebar',
                'type'        => 'range',
                'input_attrs' => array(
                    'min'  => 10,
                    'max'  => 50,
                    'step' => 1
                ),
            )
        )
    );

    $wp_customize->add_setting($nameTheme . '[sidebar_background_color]', array(
        'default'           => '#f9fbfd',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'ag_sanitize_alpha_color',
    ));
    $wp_customize->add_control(new ThemeCustomizeControl($wp_customize, $nameTheme . '[sidebar_background_color]', array(
        'label'    => esc_html__('Sidebar Background Color', 'advanced-gutenberg-theme'),
        'section'  => 'ag_theme_general_sidebar',
        'show_opacity' => true,
        'type'         => 'alpha-color',
        'palette'      => array(
            'rgb(0, 0, 0)',
            'rgba( 255, 255, 255, 0.2 )',
            'rgb(221, 51, 51)',
            'rgb(221, 153, 51)',
            'rgb(238, 238, 34)',
            'rgb(129, 215, 66)',
            'rgb(30, 115, 190)',
            'rgb(130, 36, 227)'
        )
    )));
    $wp_customize->add_setting($nameTheme . '[sidebar_link_color]', array(
        'default'    => '#42495d',
        'type'       => 'option',
        'capability' => 'edit_theme_options',
        'transport'  => 'postMessage',
        'sanitize_callback' => 'ag_sanitize_alpha_color',
    ));
    $wp_customize->add_control(new ThemeCustomizeControl($wp_customize, $nameTheme . '[sidebar_link_color]', array(
        'label'    => esc_html__('Body Link Color', 'advanced-gutenberg-theme'),
        'section'  => 'ag_theme_general_sidebar',
        'show_opacity' => true,
        'type'         => 'alpha-color',
        'palette'      => array(
            'rgb(0, 0, 0)',
            'rgba( 255, 255, 255, 0.2)',
            'rgb(221, 51, 51)',
            'rgb(221, 153, 51)',
            'rgb(238, 238, 34)',
            'rgb(129, 215, 66)',
            'rgb(30, 115, 190)',
            'rgb(130, 36, 227)'
        )
    )));
    $wp_customize->add_setting($nameTheme . '[sidebar_header_color]', array(
        'default'           => '#42495d',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'ag_sanitize_alpha_color',
    ));
    $wp_customize->add_control(new ThemeCustomizeControl($wp_customize, $nameTheme . '[sidebar_header_color]', array(
        'label'    => esc_html__('Header Text Color', 'advanced-gutenberg-theme'),
        'section'  => 'ag_theme_general_sidebar',
        'show_opacity' => true,
        'type'         => 'alpha-color',
        'palette'      => array(
            'rgb(0, 0, 0)',
            'rgba( 255, 255, 255, 0.2)',
            'rgb(221, 51, 51)',
            'rgb(221, 153, 51)',
            'rgb(238, 238, 34)',
            'rgb(129, 215, 66)',
            'rgb(30, 115, 190)',
            'rgb(130, 36, 227)'
        )
    )));
    $wp_customize->add_setting($nameTheme . '[sidebar_text_color]', array(
        'default'           => '#91a8c5',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'ag_sanitize_alpha_color',
    ));
    $wp_customize->add_control(new ThemeCustomizeControl($wp_customize, $nameTheme . '[sidebar_text_color]', array(
        'label'    => esc_html__('Text Color', 'advanced-gutenberg-theme'),
        'section'  => 'ag_theme_general_sidebar',
        'show_opacity' => true,
        'type'         => 'alpha-color',
        'palette'      => array(
            'rgb(0, 0, 0)',
            'rgba( 255, 255, 255, 0.2)',
            'rgb(221, 51, 51)',
            'rgb(221, 153, 51)',
            'rgb(238, 238, 34)',
            'rgb(129, 215, 66)',
            'rgb(30, 115, 190)',
            'rgb(130, 36, 227)'
        )
    )));
    $wp_customize->add_setting($nameTheme . '[sidebar_header_style]', array(
        'default'       => '|Bold|Uppercase',
        'type'          => 'option',
        'capability'    => 'edit_theme_options',
        'transport'     => 'postMessage',
        'sanitize_callback' => 'ag_sanitize_font_style',
    ));
    $wp_customize->add_control(new ThemeCustomizeControl($wp_customize, $nameTheme . '[sidebar_header_style]', array(
        'label'   => esc_html__('Header Font Style', 'advanced-gutenberg-theme'),
        'section' => 'ag_theme_general_sidebar',
        'type'    => 'font_style',
        'icon'    => 'material-icons',
        'choices' => theme_font_style_choices(),
    )));

    // HEADER & MENUS
    $wp_customize->add_panel('ag_theme_general_header_menus', array(
        'title'    => esc_html__('Header & Menus', 'advanced-gutenberg-theme'),
        'priority' => 3,
    ));

    // Menu & Logo position
    $wp_customize->add_section('ag_theme_general_menus_logo', array(
        'title' => esc_html__('Header Layout', 'advanced-gutenberg-theme'),
        'panel' => 'ag_theme_general_header_menus',
    ));
    $wp_customize->add_setting($nameTheme . '[header_width]', array(
        'default'       => '',
        'type'          => 'option',
        'capability'    => 'edit_theme_options',
        'transport'     => 'postMessage',
        'sanitize_callback' => 'ag_sanitize_font_style',
    ));
    $wp_customize->add_control($nameTheme . '[header_width]', array(
        'label'   => esc_html__('Header Width', 'advanced-gutenberg-theme'),
        'section' => 'ag_theme_general_menus_logo',
        'type'    => 'radio',
        'choices' => array(
            ''  => __('Container', 'advanced-gutenberg-theme'),
            'fullwidth'  => __('Full Width', 'advanced-gutenberg-theme')
        ),
    ));
    $wp_customize->add_setting($nameTheme . '[menus_logo]', array(
        'default'       => 'right',
        'type'          => 'option',
        'capability'    => 'edit_theme_options',
        'transport'     => 'postMessage',
        'sanitize_callback' => 'ag_sanitize_font_style',
    ));
    $wp_customize->add_control(new ThemeCustomizeControl($wp_customize, $nameTheme . '[menus_logo]', array(
        'label'   => esc_html__('Select Header Layout', 'advanced-gutenberg-theme'),
        'section' => 'ag_theme_general_menus_logo',
        'type'    => 'font_style',
        'icon'    => 'none',
        'choices' => theme_font_style_choices('menus_logo'),
    )));
    $wp_customize->add_setting($nameTheme . '[sticky_header]', array(
        'default'           => false,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    $wp_customize->add_control($nameTheme . '[sticky_header]', array(
        'label'    => esc_html__('Enable Sticky Header', 'advanced-gutenberg-theme'),
        'section'  => 'ag_theme_general_menus_logo',
        'type'     => 'checkbox',
        'settings' => $nameTheme . '[sticky_header]',
    ));
    $wp_customize->add_setting($nameTheme . '[fixed_navigation]', array(
        'default'           => true,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    $wp_customize->add_control($nameTheme . '[fixed_navigation]', array(
        'label'    => esc_html__('Enable Fixed Navigation', 'advanced-gutenberg-theme'),
        'section'  => 'ag_theme_general_menus_logo',
        'type'     => 'checkbox',
        'settings' => $nameTheme . '[fixed_navigation]',
    ));
    $wp_customize->add_setting($nameTheme . '[top_content]', array(
        'default'           => true,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    $wp_customize->add_control($nameTheme . '[top_content]', array(
        'label'    => esc_html__('Header Above Content', 'advanced-gutenberg-theme'),
        'section'  => 'ag_theme_general_menus_logo',
        'type'     => 'checkbox',
        'settings' => $nameTheme . '[top_content]',
    ));
    $wp_customize->add_setting($nameTheme . '[sticky_header_height]', array(
        'default'           => '80',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control(new ThemeCustomizeControl($wp_customize, $nameTheme . '[sticky_header_height]', array(
        'label'       => esc_html__('Sticky Header Height', 'advanced-gutenberg-theme'),
        'section'     => 'ag_theme_general_menus_logo',
        'type'        => 'range',
        'input_attrs' => array(
            'min'  => 30,
            'max'  => 300,
            'step' => 1
        ),
    )));
    $wp_customize->add_setting($nameTheme . '[sticky_background_color]', array(
        'default'           => '#ffffff',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'ag_sanitize_alpha_color',
    ));
    $wp_customize->add_control(new ThemeCustomizeControl($wp_customize, $nameTheme . '[sticky_background_color]', array(
        'label'        => esc_html__('Sticky Header Background Color', 'advanced-gutenberg-theme'),
        'section'      => 'ag_theme_general_menus_logo',
        'show_opacity' => true,
        'type'         => 'alpha-color',
        'palette'      => array(
            'rgb(0, 0, 0)',
            'rgba( 255, 255, 255, 0.2 )',
            'rgb(221, 51, 51)',
            'rgb(221, 153, 51)',
            'rgb(238, 238, 34)',
            'rgb(129, 215, 66)',
            'rgb(30, 115, 190)',
            'rgb(130, 36, 227)'
        )
    )));
    $wp_customize->add_setting($nameTheme . '[header_height]', array(
        'default'           => '80',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control(new ThemeCustomizeControl($wp_customize, $nameTheme . '[header_height]', array(
        'label'       => esc_html__('Header Height', 'advanced-gutenberg-theme'),
        'section'     => 'ag_theme_general_menus_logo',
        'type'        => 'range',
        'input_attrs' => array(
            'min'  => 30,
            'max'  => 300,
            'step' => 1
        ),
    )));

    $wp_customize->add_setting($nameTheme . '[header_font_google]', array(
        'default'           => '',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'ag_sanitize_font_style',
    ));
    $wp_customize->add_control(new ThemeCustomizeControl($wp_customize, $nameTheme . '[header_font_google]', array(
        'label'   => esc_html__('Header Font', 'advanced-gutenberg-theme'),
        'section' => 'ag_theme_general_menus_logo',
        'type'    => 'select_option_font',
        'choices' => theme_font_google_choices(),
    )));
    $wp_customize->add_setting($nameTheme . '[header_background_color]', array(
        'default'           => '#ffffff',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'ag_sanitize_alpha_color',
    ));
    $wp_customize->add_control(new ThemeCustomizeControl($wp_customize, $nameTheme . '[header_background_color]', array(
        'label'        => esc_html__('Header Background Color', 'advanced-gutenberg-theme'),
        'section'      => 'ag_theme_general_menus_logo',
        'show_opacity' => true,
        'type'         => 'alpha-color',
        'palette'      => array(
            'rgb(0, 0, 0)',
            'rgba( 255, 255, 255, 0.2 )',
            'rgb(221, 51, 51)',
            'rgb(221, 153, 51)',
            'rgb(238, 238, 34)',
            'rgb(129, 215, 66)',
            'rgb(30, 115, 190)',
            'rgb(130, 36, 227)'
        )
    )));
    $wp_customize->add_setting($nameTheme . '[header_border_color]', array(
        'default'           => '#7a8da733',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'ag_sanitize_alpha_color',
    ));
    $wp_customize->add_control(new ThemeCustomizeControl($wp_customize, $nameTheme . '[header_border_color]', array(
        'label'    => esc_html__('Header Border Color', 'advanced-gutenberg-theme'),
        'section'  => 'ag_theme_general_menus_logo',
        'show_opacity' => true,
        'type'         => 'alpha-color',
        'palette'      => array(
            'rgb(0, 0, 0)',
            'rgba( 255, 255, 255, 0.2 )',
            'rgb(221, 51, 51)',
            'rgb(221, 153, 51)',
            'rgb(238, 238, 34)',
            'rgb(129, 215, 66)',
            'rgb(30, 115, 190)',
            'rgb(130, 36, 227)'
        )
    )));
    $wp_customize->add_setting($nameTheme . '[header_border_size]', array(
        'default'           => '0.5',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control(new ThemeCustomizeControl($wp_customize, $nameTheme . '[header_border_size]', array(
        'label'       => esc_html__('Header Border Size', 'advanced-gutenberg-theme'),
        'section'     => 'ag_theme_general_menus_logo',
        'type'        => 'range',
        'input_attrs' => array(
            'min'  => 0,
            'max'  => 100,
            'step' => 0.5
        ),
    )));
    $wp_customize->add_setting(
        'background_header_image',
        array(
        'default'   => '',
        'transport' => 'postMessage',
        )
    );
    $wp_customize->add_control(
        new WP_Customize_Image_Control(
            $wp_customize,
            'background_header_image',
            array(
                'label'    => esc_html__('Background Header Image', 'advanced-gutenberg-theme'),
                'section'  => 'ag_theme_general_menus_logo',
                'settings' => 'background_header_image',
            )
        )
    );

    //sticky menu
    $wp_customize->add_section('ag_theme_general_sticky_menu_layout', array(
        'title' => esc_html__('Sticky menu layout', 'advanced-gutenberg-theme'),
        'panel' => 'ag_theme_general_header_menus',
    ));
    $wp_customize->add_setting($nameTheme . '[sticky_text_color]', array(
        'default'           => '#444',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'ag_sanitize_alpha_color',
    ));
    $wp_customize->add_control(new ThemeCustomizeControl($wp_customize, $nameTheme . '[sticky_text_color]', array(
        'label'    => esc_html__('Text Color', 'advanced-gutenberg-theme'),
        'section'  => 'ag_theme_general_sticky_menu_layout',
        'show_opacity' => true,
        'type'         => 'alpha-color',
        'palette'      => array(
            'rgb(0, 0, 0)',
            'rgba( 255, 255, 255, 0.2 )',
            'rgb(221, 51, 51)',
            'rgb(221, 153, 51)',
            'rgb(238, 238, 34)',
            'rgb(129, 215, 66)',
            'rgb(30, 115, 190)',
            'rgb(130, 36, 227)'
        )
    )));
    $wp_customize->add_setting($nameTheme . '[sticky_active_link_color]', array(
        'default'           => '#50a7ec',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'ag_sanitize_alpha_color',
    ));
    $wp_customize->add_control(new ThemeCustomizeControl($wp_customize, $nameTheme . '[sticky_active_link_color]', array(
        'label'    => esc_html__('Active Link Color', 'advanced-gutenberg-theme'),
        'section'  => 'ag_theme_general_sticky_menu_layout',
        'show_opacity' => true,
        'type'         => 'alpha-color',
        'palette'      => array(
            'rgb(0, 0, 0)',
            'rgba( 255, 255, 255, 0.2 )',
            'rgb(221, 51, 51)',
            'rgb(221, 153, 51)',
            'rgb(238, 238, 34)',
            'rgb(129, 215, 66)',
            'rgb(30, 115, 190)',
            'rgb(130, 36, 227)'
        )
    )));
    $wp_customize->add_setting($nameTheme . '[sticky_menu_background_color]', array(
        'default'           => '#ffffff',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'ag_sanitize_alpha_color',
    ));
    $wp_customize->add_control(new ThemeCustomizeControl($wp_customize, $nameTheme . '[sticky_menu_background_color]', array(
        'label'    => esc_html__('Background Color', 'advanced-gutenberg-theme'),
        'section'  => 'ag_theme_general_sticky_menu_layout',
        'show_opacity' => true,
        'type'         => 'alpha-color',
        'palette'      => array(
            'rgb(0, 0, 0)',
            'rgba( 255, 255, 255, 0.2 )',
            'rgb(221, 51, 51)',
            'rgb(221, 153, 51)',
            'rgb(238, 238, 34)',
            'rgb(129, 215, 66)',
            'rgb(30, 115, 190)',
            'rgb(130, 36, 227)'
        )
    )));

    //Main menu layout
    $wp_customize->add_section('ag_theme_general_main_menu_layout', array(
        'title' => esc_html__('Main menu layout', 'advanced-gutenberg-theme'),
        'panel' => 'ag_theme_general_header_menus',
    ));

    $wp_customize->add_setting($nameTheme . '[menu_height]', array(
        'default'           => '40',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control(new ThemeCustomizeControl($wp_customize, $nameTheme . '[menu_height]', array(
        'label'       => esc_html__('Menu Height', 'advanced-gutenberg-theme'),
        'section'     => 'ag_theme_general_main_menu_layout',
        'type'        => 'range',
        'input_attrs' => array(
            'min'  => 30,
            'max'  => 300,
            'step' => 1
        ),
    )));

    $wp_customize->add_setting($nameTheme . '[text_size_menu]', array(
        'default'           => '14',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control(new ThemeCustomizeControl($wp_customize, $nameTheme . '[text_size_menu]', array(
        'label'       => esc_html__('Text Size (px)', 'advanced-gutenberg-theme'),
        'section'     => 'ag_theme_general_main_menu_layout',
        'type'        => 'range',
        'input_attrs' => array(
            'min'  => 9,
            'max'  => 40,
            'step' => 1
        ),
    )));

    $wp_customize->add_setting($nameTheme . '[font_style]', array(
        'default'       => '',
        'type'          => 'option',
        'capability'    => 'edit_theme_options',
        'transport'     => 'postMessage',
        'sanitize_callback' => 'ag_sanitize_font_style',
    ));
    $wp_customize->add_control(new ThemeCustomizeControl($wp_customize, $nameTheme . '[font_style]', array(
        'label'   => esc_html__('Font style', 'advanced-gutenberg-theme'),
        'section' => 'ag_theme_general_main_menu_layout',
        'type'    => 'font_style',
        'icon'    => 'material-icons',
        'choices' => theme_font_style_choices(),
    )));

    $wp_customize->add_setting($nameTheme . '[text_color]', array(
        'default'           => '#444',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'ag_sanitize_alpha_color',
    ));
    $wp_customize->add_control(new ThemeCustomizeControl($wp_customize, $nameTheme . '[text_color]', array(
        'label'    => esc_html__('Text Color', 'advanced-gutenberg-theme'),
        'section'  => 'ag_theme_general_main_menu_layout',
        'show_opacity' => true,
        'type'         => 'alpha-color',
        'palette'      => array(
            'rgb(0, 0, 0)',
            'rgba( 255, 255, 255, 0.2 )',
            'rgb(221, 51, 51)',
            'rgb(221, 153, 51)',
            'rgb(238, 238, 34)',
            'rgb(129, 215, 66)',
            'rgb(30, 115, 190)',
            'rgb(130, 36, 227)'
        )
    )));

    $wp_customize->add_setting($nameTheme . '[active_link_color]', array(
        'default'           => '#50a7ec',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'ag_sanitize_alpha_color',
    ));
    $wp_customize->add_control(new ThemeCustomizeControl($wp_customize, $nameTheme . '[active_link_color]', array(
        'label'    => esc_html__('Active Link Color', 'advanced-gutenberg-theme'),
        'section'  => 'ag_theme_general_main_menu_layout',
        'show_opacity' => true,
        'type'         => 'alpha-color',
        'palette'      => array(
            'rgb(0, 0, 0)',
            'rgba( 255, 255, 255, 0.2 )',
            'rgb(221, 51, 51)',
            'rgb(221, 153, 51)',
            'rgb(238, 238, 34)',
            'rgb(129, 215, 66)',
            'rgb(30, 115, 190)',
            'rgb(130, 36, 227)'
        )
    )));

    $wp_customize->add_setting($nameTheme . '[menu_background_color]', array(
        'default'           => '#ffffff',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'ag_sanitize_alpha_color',
    ));
    $wp_customize->add_control(new ThemeCustomizeControl($wp_customize, $nameTheme . '[menu_background_color]', array(
        'label'    => esc_html__('Background Color', 'advanced-gutenberg-theme'),
        'section'  => 'ag_theme_general_main_menu_layout',
        'show_opacity' => true,
        'type'         => 'alpha-color',
        'palette'      => array(
            'rgb(0, 0, 0)',
            'rgba( 255, 255, 255, 0.2 )',
            'rgb(221, 51, 51)',
            'rgb(221, 153, 51)',
            'rgb(238, 238, 34)',
            'rgb(129, 215, 66)',
            'rgb(30, 115, 190)',
            'rgb(130, 36, 227)'
        )
    )));

    // Submenu
    $wp_customize->add_section('ag_theme_general_submenu', array(
        'title' => esc_html__('Submenu Setting', 'advanced-gutenberg-theme'),
        'panel' => 'ag_theme_general_header_menus',
    ));
    $wp_customize->add_setting($nameTheme . '[text_size_sub_menu]', array(
        'default'           => '16',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control(new ThemeCustomizeControl($wp_customize, $nameTheme . '[text_size_sub_menu]', array(
        'label'       => esc_html__('Text Size (px)', 'advanced-gutenberg-theme'),
        'section'     => 'ag_theme_general_submenu',
        'type'        => 'range',
        'input_attrs' => array(
            'min'  => 12,
            'max'  => 40,
            'step' => 1
        ),
    )));
    $wp_customize->add_setting($nameTheme . '[border_top_sub_menu]', array(
        'default'           => '1',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control(
        new ThemeCustomizeControl(
            $wp_customize,
            $nameTheme . '[border_top_sub_menu]',
            array(
                'label'       => esc_html__('Border Top', 'advanced-gutenberg-theme'),
                'section'     => 'ag_theme_general_submenu',
                'type'        => 'range',
                'input_attrs' => array(
                    'min'  => 0,
                    'max'  => 20,
                    'step' => 1
                ),
            )
        )
    );
    $wp_customize->add_setting($nameTheme . '[sub_menu_width]', array(
        'default'           => '200',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control(
        new ThemeCustomizeControl(
            $wp_customize,
            $nameTheme . '[sub_menu_width]',
            array(
                'label'       => esc_html__('Dropdown width', 'advanced-gutenberg-theme'),
                'section'     => 'ag_theme_general_submenu',
                'type'        => 'range',
                'input_attrs' => array(
                    'min'  => 200,
                    'max'  => 600,
                    'step' => 1
                ),
            )
        )
    );
    $wp_customize->add_setting($nameTheme . '[drop_menu_background_color]', array(
        'default'           => '#ffffff',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'ag_sanitize_alpha_color',
    ));
    $wp_customize->add_control(new ThemeCustomizeControl($wp_customize, $nameTheme . '[drop_menu_background_color]', array(
        'label'    => esc_html__('Background Color', 'advanced-gutenberg-theme'),
        'section'  => 'ag_theme_general_submenu',
        'show_opacity' => true,
        'type'         => 'alpha-color',
        'palette'      => array(
            'rgb(0, 0, 0)',
            'rgba( 255, 255, 255, 0.2 )',
            'rgb(221, 51, 51)',
            'rgb(221, 153, 51)',
            'rgb(238, 238, 34)',
            'rgb(129, 215, 66)',
            'rgb(30, 115, 190)',
            'rgb(130, 36, 227)'
        )
    )));
    $wp_customize->add_setting($nameTheme . '[drop_menu_background_color_hover]', array(
        'default'           => '#fafafa',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'ag_sanitize_alpha_color',
    ));
    $wp_customize->add_control(new ThemeCustomizeControl($wp_customize, $nameTheme . '[drop_menu_background_color_hover]', array(
        'label'    => esc_html__('Background Color Hover', 'advanced-gutenberg-theme'),
        'section'  => 'ag_theme_general_submenu',
        'show_opacity' => true,
        'type'         => 'alpha-color',
        'palette'      => array(
            'rgb(0, 0, 0)',
            'rgba( 255, 255, 255, 0.2 )',
            'rgb(221, 51, 51)',
            'rgb(221, 153, 51)',
            'rgb(238, 238, 34)',
            'rgb(129, 215, 66)',
            'rgb(30, 115, 190)',
            'rgb(130, 36, 227)'
        )
    )));
    $wp_customize->add_setting($nameTheme . '[drop_menu_line_color]', array(
        'default'           => '#fafafa',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'ag_sanitize_alpha_color',
    ));
    $wp_customize->add_control(new ThemeCustomizeControl($wp_customize, $nameTheme . '[drop_menu_line_color]', array(
        'label'    => esc_html__('Border Top Submenu Color', 'advanced-gutenberg-theme'),
        'section'  => 'ag_theme_general_submenu',
        'show_opacity' => true,
        'type'         => 'alpha-color',
        'palette'      => array(
            'rgb(0, 0, 0)',
            'rgba( 255, 255, 255, 0.2 )',
            'rgb(221, 51, 51)',
            'rgb(221, 153, 51)',
            'rgb(238, 238, 34)',
            'rgb(129, 215, 66)',
            'rgb(30, 115, 190)',
            'rgb(130, 36, 227)'
        )
    )));
    $wp_customize->add_setting($nameTheme . '[drop_menu_text_color]', array(
        'default'           => '#000000',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'ag_sanitize_alpha_color',
    ));
    $wp_customize->add_control(new ThemeCustomizeControl($wp_customize, $nameTheme . '[drop_menu_text_color]', array(
        'label'    => esc_html__('Text Color', 'advanced-gutenberg-theme'),
        'section'  => 'ag_theme_general_submenu',
        'show_opacity' => true,
        'type'         => 'alpha-color',
        'palette'      => array(
            'rgb(0, 0, 0)',
            'rgba( 255, 255, 255, 0.2 )',
            'rgb(221, 51, 51)',
            'rgb(221, 153, 51)',
            'rgb(238, 238, 34)',
            'rgb(129, 215, 66)',
            'rgb(30, 115, 190)',
            'rgb(130, 36, 227)'
        )
    )));

    //mobile menu
    $wp_customize->add_section('ag_theme_general_mobile_menu', array(
        'title' => esc_html__('Mobile Menu Setting', 'advanced-gutenberg-theme'),
        'panel' => 'ag_theme_general_header_menus',
    ));
    $wp_customize->add_setting($nameTheme . '[full_mobile_menu]', array(
        'default'           => false,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    $wp_customize->add_control($nameTheme . '[full_mobile_menu]', array(
        'label'    => esc_html__('Curtain Menu', 'advanced-gutenberg-theme'),
        'section'  => 'ag_theme_general_mobile_menu',
        'type'     => 'checkbox',
        'settings' => $nameTheme . '[full_mobile_menu]',
    ));
    $wp_customize->add_setting($nameTheme . '[position_mobile_menu]', array(
        'default'       => 'left',
        'type'          => 'option',
        'capability'    => 'edit_theme_options',
        'transport'     => 'postMessage',
        'sanitize_callback' => 'ag_sanitize_font_style',
    ));
    $wp_customize->add_control(new ThemeCustomizeControl($wp_customize, $nameTheme . '[position_mobile_menu]', array(
        'label'   => esc_html__('Menu Align', 'advanced-gutenberg-theme'),
        'section' => 'ag_theme_general_mobile_menu',
        'type'    => 'font_style',
        'icon'    => '',
        'choices' => theme_font_style_choices('footer_menu'),
    )));
    $wp_customize->add_setting($nameTheme . '[mobile_menu_height]', array(
        'default'           => '40',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control(new ThemeCustomizeControl($wp_customize, $nameTheme . '[mobile_menu_height]', array(
        'label'       => esc_html__('Menu Height', 'advanced-gutenberg-theme'),
        'section'     => 'ag_theme_general_mobile_menu',
        'type'        => 'range',
        'input_attrs' => array(
            'min'  => 20,
            'max'  => 100,
            'step' => 1
        ),
    )));
    $wp_customize->add_setting($nameTheme . '[mobile_text_size_menu]', array(
        'default'           => '12',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control(new ThemeCustomizeControl($wp_customize, $nameTheme . '[mobile_text_size_menu]', array(
        'label'       => esc_html__('Text Size (px)', 'advanced-gutenberg-theme'),
        'section'     => 'ag_theme_general_mobile_menu',
        'type'        => 'range',
        'input_attrs' => array(
            'min'  => 9,
            'max'  => 30,
            'step' => 1
        ),
    )));
    $wp_customize->add_setting($nameTheme . '[mobile_font_style]', array(
        'default'       => '',
        'type'          => 'option',
        'capability'    => 'edit_theme_options',
        'transport'     => 'postMessage',
        'sanitize_callback' => 'ag_sanitize_font_style',
    ));
    $wp_customize->add_control(new ThemeCustomizeControl($wp_customize, $nameTheme . '[mobile_font_style]', array(
        'label'   => esc_html__('Font style', 'advanced-gutenberg-theme'),
        'section' => 'ag_theme_general_mobile_menu',
        'type'    => 'font_style',
        'icon'    => 'material-icons',
        'choices' => theme_font_style_choices(),
    )));
    $wp_customize->add_setting($nameTheme . '[mobile_text_color]', array(
        'default'           => '#444',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'ag_sanitize_alpha_color',
    ));
    $wp_customize->add_control(new ThemeCustomizeControl($wp_customize, $nameTheme . '[mobile_text_color]', array(
        'label'    => esc_html__('Text Color', 'advanced-gutenberg-theme'),
        'section'  => 'ag_theme_general_mobile_menu',
        'show_opacity' => true,
        'type'         => 'alpha-color',
        'palette'      => array(
            'rgb(0, 0, 0)',
            'rgba( 255, 255, 255, 0.2 )',
            'rgb(221, 51, 51)',
            'rgb(221, 153, 51)',
            'rgb(238, 238, 34)',
            'rgb(129, 215, 66)',
            'rgb(30, 115, 190)',
            'rgb(130, 36, 227)'
        )
    )));
    $wp_customize->add_setting($nameTheme . '[mobile_active_link_color]', array(
        'default'           => '#50a7ec',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'ag_sanitize_alpha_color',
    ));
    $wp_customize->add_control(new ThemeCustomizeControl($wp_customize, $nameTheme . '[mobile_active_link_color]', array(
        'label'    => esc_html__('Active Link Color', 'advanced-gutenberg-theme'),
        'section'  => 'ag_theme_general_mobile_menu',
        'show_opacity' => true,
        'type'         => 'alpha-color',
        'palette'      => array(
            'rgb(0, 0, 0)',
            'rgba( 255, 255, 255, 0.2 )',
            'rgb(221, 51, 51)',
            'rgb(221, 153, 51)',
            'rgb(238, 238, 34)',
            'rgb(129, 215, 66)',
            'rgb(30, 115, 190)',
            'rgb(130, 36, 227)'
        )
    )));
    $wp_customize->add_setting($nameTheme . '[mobile_hover_color]', array(
        'default'           => '#cfcfcf',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'ag_sanitize_alpha_color',
    ));
    $wp_customize->add_control(new ThemeCustomizeControl($wp_customize, $nameTheme . '[mobile_hover_color]', array(
        'label'    => esc_html__('Hover Color', 'advanced-gutenberg-theme'),
        'section'  => 'ag_theme_general_mobile_menu',
        'show_opacity' => true,
        'type'         => 'alpha-color',
        'palette'      => array(
            'rgb(0, 0, 0)',
            'rgba( 255, 255, 255, 0.2 )',
            'rgb(221, 51, 51)',
            'rgb(221, 153, 51)',
            'rgb(238, 238, 34)',
            'rgb(129, 215, 66)',
            'rgb(30, 115, 190)',
            'rgb(130, 36, 227)'
        )
    )));
    $wp_customize->add_setting($nameTheme . '[mobile_menu_background_color]', array(
        'default'           => '#ffffff',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'ag_sanitize_alpha_color',
    ));
    $wp_customize->add_control(new ThemeCustomizeControl($wp_customize, $nameTheme . '[mobile_menu_background_color]', array(
        'label'    => esc_html__('Background Color', 'advanced-gutenberg-theme'),
        'section'  => 'ag_theme_general_mobile_menu',
        'show_opacity' => true,
        'type'         => 'alpha-color',
        'palette'      => array(
            'rgb(0, 0, 0)',
            'rgba( 255, 255, 255, 0.2 )',
            'rgb(221, 51, 51)',
            'rgb(221, 153, 51)',
            'rgb(238, 238, 34)',
            'rgb(129, 215, 66)',
            'rgb(30, 115, 190)',
            'rgb(130, 36, 227)'
        )
    )));

    // Footer
    $wp_customize->add_section('ag_theme_general_footer', array(
        'title'       => esc_html__('Footer Settings', 'advanced-gutenberg-theme'),
        'priority'    => 4,
    ));

    $wp_customize->add_setting($nameTheme . '[padding_top_footer]', array(
        'default'    => '0',
        'type'       => 'option',
        'capability' => 'edit_theme_options',
        'transport'  => 'postMessage',
    ));
    $wp_customize->add_control(
        new ThemeCustomizeControl(
            $wp_customize,
            $nameTheme . '[padding_top_footer]',
            array(
                'label'       => esc_html__('Top padding footer (px)', 'advanced-gutenberg-theme'),
                'section'     => 'ag_theme_general_footer',
                'type'        => 'range',
                'class'       => '',
                'input_attrs' => array(
                    'min'  => 0,
                    'max'  => 200,
                    'step' => 1
                ),
            )
        )
    );

    $wp_customize->add_setting($nameTheme . '[padding_bottom_footer]', array(
        'default'    => '0',
        'type'       => 'option',
        'capability' => 'edit_theme_options',
        'transport'  => 'postMessage',
    ));
    $wp_customize->add_control(
        new ThemeCustomizeControl(
            $wp_customize,
            $nameTheme . '[padding_bottom_footer]',
            array(
                'label'       => esc_html__('Bottom padding footer (px)', 'advanced-gutenberg-theme'),
                'section'     => 'ag_theme_general_footer',
                'type'        => 'range',
                'class'       => '',
                'input_attrs' => array(
                    'min'  => 0,
                    'max'  => 200,
                    'step' => 1
                ),
            )
        )
    );

    $wp_customize->add_setting($nameTheme . '[footer_menu]', array(
        'default'       => 'left',
        'type'          => 'option',
        'capability'    => 'edit_theme_options',
        'transport'     => 'postMessage',
        'sanitize_callback' => 'ag_sanitize_font_style',
    ));
    $wp_customize->add_control(new ThemeCustomizeControl($wp_customize, $nameTheme . '[footer_menu]', array(
        'label'   => esc_html__('Footer Menu Align', 'advanced-gutenberg-theme'),
        'section' => 'ag_theme_general_footer',
        'type'    => 'font_style',
        'icon'    => '',
        'choices' => theme_font_style_choices('footer_menu'),
    )));

    $wp_customize->add_setting($nameTheme . '[footer_columns]', array(
        'default'           => '1_2',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new ThemeCustomizeControl($wp_customize, $nameTheme . '[footer_columns]', array(
        'label'    => esc_html__('Columns layout', 'advanced-gutenberg-theme'),
        'section'  => 'ag_theme_general_footer',
        'settings' => $nameTheme . '[footer_columns]',
        'choices'  => theme_footer_column_choices(),
    )));

    $wp_customize->add_setting($nameTheme . '[footer_menu_background_color]', array(
        'default'           => '#666666',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'ag_sanitize_alpha_color',
    ));
    $wp_customize->add_control(new ThemeCustomizeControl($wp_customize, $nameTheme . '[footer_menu_background_color]', array(
        'label'    => esc_html__('Menu background color', 'advanced-gutenberg-theme'),
        'section'  => 'ag_theme_general_footer',
        'show_opacity' => true,
        'type'         => 'alpha-color',
        'palette'      => array(
            'rgb(0, 0, 0)',
            'rgba( 255, 255, 255, 0.2 )',
            'rgb(221, 51, 51)',
            'rgb(221, 153, 51)',
            'rgb(238, 238, 34)',
            'rgb(129, 215, 66)',
            'rgb(30, 115, 190)',
            'rgb(130, 36, 227)'
        )
    )));

    $wp_customize->add_setting($nameTheme . '[footer_background_color]', array(
        'default'           => '#2a2727',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'ag_sanitize_alpha_color',
    ));
    $wp_customize->add_control(new ThemeCustomizeControl($wp_customize, $nameTheme . '[footer_background_color]', array(
        'label'    => esc_html__('Background color', 'advanced-gutenberg-theme'),
        'section'  => 'ag_theme_general_footer',
        'show_opacity' => true,
        'type'         => 'alpha-color',
        'palette'      => array(
            'rgb(0, 0, 0)',
            'rgba( 255, 255, 255, 0.2 )',
            'rgb(221, 51, 51)',
            'rgb(221, 153, 51)',
            'rgb(238, 238, 34)',
            'rgb(129, 215, 66)',
            'rgb(30, 115, 190)',
            'rgb(130, 36, 227)'
        )
    )));

    $wp_customize->add_setting($nameTheme . '[custom_footer_credits]', array(
        'default'    => 'Designed by JoomUnited | Powered by WordPress',
        'type'       => 'option',
        'capability' => 'edit_theme_options',
        'transport'  => 'postMessage'
    ));
    $wp_customize->add_control($nameTheme . '[custom_footer_credits]', array(
        'label'    => esc_html__('Copyright content', 'advanced-gutenberg-theme'),
        'section'  => 'ag_theme_general_footer',
        'settings' => $nameTheme . '[custom_footer_credits]',
        'type'     => 'textarea',
    ));

    $wp_customize->add_setting($nameTheme . '[position_footer_credits]', array(
        'default'       => 'left',
        'type'          => 'option',
        'capability'    => 'edit_theme_options',
        'transport'     => 'postMessage',
        'sanitize_callback' => 'ag_sanitize_font_style',
    ));
    $wp_customize->add_control(new ThemeCustomizeControl($wp_customize, $nameTheme . '[position_footer_credits]', array(
        'label'   => esc_html__('Copyright align', 'advanced-gutenberg-theme'),
        'section' => 'ag_theme_general_footer',
        'type'    => 'font_style',
        'icon'    => '',
        'choices' => theme_font_style_choices('footer_menu'),
    )));
    $wp_customize->add_setting($nameTheme . '[footer_credit_color]', array(
        'default'           => '#eeeeee',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'ag_sanitize_alpha_color',
    ));
    $wp_customize->add_control(new ThemeCustomizeControl($wp_customize, $nameTheme . '[footer_credit_color]', array(
        'label'    => esc_html__('Copyright color', 'advanced-gutenberg-theme'),
        'section'  => 'ag_theme_general_footer',
        'show_opacity' => true,
        'type'         => 'alpha-color',
        'palette'      => array(
            'rgb(0, 0, 0)',
            'rgba( 255, 255, 255, 0.2 )',
            'rgb(221, 51, 51)',
            'rgb(221, 153, 51)',
            'rgb(238, 238, 34)',
            'rgb(129, 215, 66)',
            'rgb(30, 115, 190)',
            'rgb(130, 36, 227)'
        )
    )));
    $wp_customize->add_setting($nameTheme . '[line_height_footer_credit]', array(
        'default'           => '9',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'ag_sanitize_float_number',
    ));
    $wp_customize->add_control(new ThemeCustomizeControl($wp_customize, $nameTheme . '[line_height_footer_credit]', array(
        'label'       => esc_html__('Copyright Line Height', 'advanced-gutenberg-theme'),
        'section'     => 'ag_theme_general_footer',
        'type'        => 'range',
        'class'       => '',
        'input_attrs' => array(
            'min'  => 0.5,
            'max'  => 15,
            'step' => 0.1
        ),
    )));

    $wp_customize->add_setting($nameTheme . '[widgets_header_text_size]', array(
        'default'           => '3',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control(new ThemeCustomizeControl($wp_customize, $nameTheme . '[widgets_header_text_size]', array(
        'label'       => esc_html__('Widget header size (rem)', 'advanced-gutenberg-theme'),
        'section'     => 'ag_theme_general_footer',
        'type'        => 'range',
        'input_attrs' => array(
            'min'  => 0.1,
            'max'  => 10,
            'step' => 0.1
        ),
    )));

    $wp_customize->add_setting($nameTheme . '[widgets_header_font_style]', array(
        'default'       => '|Bold',
        'type'          => 'option',
        'capability'    => 'edit_theme_options',
        'transport'     => 'postMessage',
        'sanitize_callback' => 'ag_sanitize_font_style',
    ));
    $wp_customize->add_control(new ThemeCustomizeControl($wp_customize, $nameTheme . '[widgets_header_font_style]', array(
        'label'   => esc_html__('Header Font style', 'advanced-gutenberg-theme'),
        'section'  => 'ag_theme_general_footer',
        'type'    => 'font_style',
        'icon'    => 'material-icons',
        'choices' => theme_font_style_choices(),
    )));

    $wp_customize->add_setting($nameTheme . '[widgets_body_link_text_size]', array(
        'default'           => '1.02',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control(new ThemeCustomizeControl($wp_customize, $nameTheme . '[widgets_body_link_text_size]', array(
        'label'       => esc_html__('Widget text size (rem)', 'advanced-gutenberg-theme'),
        'section'     => 'ag_theme_general_footer',
        'type'        => 'range',
        'input_attrs' => array(
            'min'  => 0.1,
            'max'  => 10,
            'step' => 0.01
        ),
    )));

    $wp_customize->add_setting($nameTheme . '[widgets_body_link_line_height]', array(
        'default'           => '1.7',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control(new ThemeCustomizeControl($wp_customize, $nameTheme . '[widgets_body_link_line_height]', array(
        'label'       => esc_html__('Widget line height', 'advanced-gutenberg-theme'),
        'section'     => 'ag_theme_general_footer',
        'type'        => 'range',
        'input_attrs' => array(
            'min'  => 0.5,
            'max'  => 3,
            'step' => 0.1
        ),
    )));

    $wp_customize->add_setting($nameTheme . '[widgets_body_font_style]', array(
        'default'       => '',
        'type'          => 'option',
        'capability'    => 'edit_theme_options',
        'transport'     => 'postMessage',
        'sanitize_callback' => 'ag_sanitize_font_style',
    ));
    $wp_customize->add_control(new ThemeCustomizeControl($wp_customize, $nameTheme . '[widgets_body_font_style]', array(
        'label'   => esc_html__('Widget text style', 'advanced-gutenberg-theme'),
        'section' => 'ag_theme_general_footer',
        'type'    => 'font_style',
        'icon'    => 'material-icons',
        'choices' => theme_font_style_choices(),
    )));

    $wp_customize->add_setting($nameTheme . '[widget_text_color]', array(
        'default'           => '#eeeeee',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'ag_sanitize_alpha_color',
    ));
    $wp_customize->add_control(new ThemeCustomizeControl($wp_customize, $nameTheme . '[widget_text_color]', array(
        'label'    => esc_html__('Widget Text Color', 'advanced-gutenberg-theme'),
        'section'  => 'ag_theme_general_footer',
        'show_opacity' => true,
        'type'         => 'alpha-color',
        'palette'      => array(
            'rgb(0, 0, 0)',
            'rgba( 255, 255, 255, 0.2 )',
            'rgb(221, 51, 51)',
            'rgb(221, 153, 51)',
            'rgb(238, 238, 34)',
            'rgb(129, 215, 66)',
            'rgb(30, 115, 190)',
            'rgb(130, 36, 227)'
        )
    )));

    $wp_customize->add_setting($nameTheme . '[widget_link_color]', array(
        'default'           => '#eeeeee',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'ag_sanitize_alpha_color',
    ));
    $wp_customize->add_control(new ThemeCustomizeControl($wp_customize, $nameTheme . '[widget_link_color]', array(
        'label'    => esc_html__('Widget Link Color', 'advanced-gutenberg-theme'),
        'section'  => 'ag_theme_general_footer',
        'show_opacity' => true,
        'type'         => 'alpha-color',
        'palette'      => array(
            'rgb(0, 0, 0)',
            'rgba( 255, 255, 255, 0.2 )',
            'rgb(221, 51, 51)',
            'rgb(221, 153, 51)',
            'rgb(238, 238, 34)',
            'rgb(129, 215, 66)',
            'rgb(30, 115, 190)',
            'rgb(130, 36, 227)'
        )
    )));
    $wp_customize->add_setting($nameTheme . '[widget_header_color]', array(
        'default'           => '#eeeeee',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'ag_sanitize_alpha_color',
    ));
    $wp_customize->add_control(new ThemeCustomizeControl($wp_customize, $nameTheme . '[widget_header_color]', array(
        'label'    => esc_html__('Widget Header Color', 'advanced-gutenberg-theme'),
        'section'  => 'ag_theme_general_footer',
        'show_opacity' => true,
        'type'         => 'alpha-color',
        'palette'      => array(
            'rgb(0, 0, 0)',
            'rgba( 255, 255, 255, 0.2 )',
            'rgb(221, 51, 51)',
            'rgb(221, 153, 51)',
            'rgb(238, 238, 34)',
            'rgb(129, 215, 66)',
            'rgb(30, 115, 190)',
            'rgb(130, 36, 227)'
        )
    )));

    // BLOG/POST STYLE
    $wp_customize->add_section('ag_theme_general_blog_post_type', array(
        'title'       => esc_html__('Blog/Post style', 'advanced-gutenberg-theme'),
        'description' => 'Blog/Post style',
        'priority'    => 5,
    ));

    $wp_customize->add_setting($nameTheme . '[display_author]', array(
        'default'           => false,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    $wp_customize->add_control($nameTheme . '[display_author]', array(
        'label'    => esc_html__('Display Author', 'advanced-gutenberg-theme'),
        'section'  => 'ag_theme_general_blog_post_type',
        'type'     => 'checkbox',
        'settings' => $nameTheme . '[display_author]',
    ));
    $wp_customize->add_setting($nameTheme . '[display_date]', array(
        'default'           => true,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    $wp_customize->add_control($nameTheme . '[display_date]', array(
        'label'    => esc_html__('Display date', 'advanced-gutenberg-theme'),
        'section'  => 'ag_theme_general_blog_post_type',
        'type'     => 'checkbox',
        'settings' => $nameTheme . '[display_date]',
    ));
    $wp_customize->add_setting($nameTheme . '[display_category]', array(
        'default'           => false,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    $wp_customize->add_control($nameTheme . '[display_category]', array(
        'label'    => esc_html__('Display category', 'advanced-gutenberg-theme'),
        'section'  => 'ag_theme_general_blog_post_type',
        'type'     => 'checkbox',
        'settings' => $nameTheme . '[display_category]',
    ));
    $wp_customize->add_setting($nameTheme . '[display_comments]', array(
        'default'           => false,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    $wp_customize->add_control($nameTheme . '[display_comments]', array(
        'label'    => esc_html__('Display comments', 'advanced-gutenberg-theme'),
        'section'  => 'ag_theme_general_blog_post_type',
        'type'     => 'checkbox',
        'settings' => $nameTheme . '[display_comments]',
    ));
}

require_once AG_THEME_CORE . '/sanitization.php';

if (!function_exists('theme_font_style_choices')) {
    /**
     * Return font style options
     *
     * @param string $option Check value return
     *
     * @return array
     */
    function theme_font_style_choices($option = '')
    {
        if ($option === 'menus_logo') {
            return array(
            'Horizontal Left'   => 'left',
            'Horizontal Center' => 'center',
            'Horizontal Right'  => 'right',
            'Stacked Center A'  => 'centerA',
            );
        } elseif ($option === 'footer_menu') {
            return array(
                'Left'   => 'left',
                'Center' => 'center',
                'Right'  => 'right',
            );
        } elseif ($option === 'header_width') {
            return array(
                'Container'   => '',
                'Full Width' => 'fullwidth',
            );
        } elseif ($option === 'sidebar_position') {
            return array(
                'Left'   => 'left',
                'Right'  => 'right',
            );
        }
        return array(
            'format_bold'       => 'Bold',
            'format_italic'     => 'Italic',
            'title'             => 'Uppercase',
            'format_underlined' => 'Underline',
        );
    }
}

if (!function_exists('theme_font_google_choices')) {
    /**
     * Function return list google font
     *
     * @return array
     */
    function theme_font_google_choices()
    {
        require_once(AG_THEME_INCLUDES_URL . '/google-font-datas.php');
        $data_font = get_saved_google_fonts();
        return $data_font;
    }
}

if (!function_exists('theme_selecter_templatetem_choices')) {
    /**
     * Function return list template
     *
     * @return array
     */
    function theme_selecter_template_choices()
    {
        $theme_ju_template_pack = theme_ju_get_layout_pack(array('installed'));

        array_unshift($theme_ju_template_pack['installed'], 'Custom');

        return $theme_ju_template_pack['installed'];
    }
}


if (!function_exists('theme_footer_column_choices')) {
    /**
     * Returns list of footer column choices
     *
     * @return array
     */
    function theme_footer_column_choices()
    {
        $output = apply_filters(
            'theme_footer_column_choices',
            array(
                '4'     => '',
                '3'     => '',
                '2'     => '',
                '1'     => '',
                '1_3'   => '',
                '3_1'   => '',
                '1_2'   => '',
                '2_1'   => '',
                '1_1_2' => '',
                '2_1_1' => '',
                '3_1_1_1' => '',
                '1_1_1_3' => '',
            )
        );
        return $output;
    }
}


if (!function_exists('theme_update_change_option_check')) {
    /**
     * Function update change option check
     *
     * @param string $instance WP_Customize_Manager $manager WP_Customize_Manager instance
     *
     * @return void
     */
    function theme_update_change_option_check($instance)
    {
        $optionVal                    = get_option(AG_THEME_NAME_OPTION);
        $optionVal['check_customize'] = 1;
        update_option(AG_THEME_NAME_OPTION, $optionVal);
    }
    add_action('customize_save_after', 'theme_update_change_option_check', 10, 1);
}
