<?php
defined('ABSPATH') || die;

if (!function_exists('ag_theme_style_post')) {
    /**
     * Function render style for meta from box post
     *
     * @param array $field_id_value Meta box data
     *
     * @return void
     */
    function ag_theme_style_post($field_id_value)
    {
        $style = '';

        require_once(AG_THEME_INCLUDES_URL . '/default-option-theme.php');
        $agDefaultTheme = new AgDefaultTheme();
        $option_style   = $agDefaultTheme->defaultOptionTheme();
        if (!empty($option_style) && isset($field_id_value)) {
            $count          = count($option_style);

            for ($i = 0; $i < $count; $i ++) {
                if (isset($field_id_value[$option_style[$i]['name']])) {
                    if (isset($option_style[$i]['keyValue'])) {
                        $key = $option_style[$i]['keyValue'][$field_id_value[$option_style[$i]['name']][0]];
                        $option_style[$i]['value'] = $option_style[$i]['value'][$key];
                    } else {
                        $option_style[$i]['value'] = $field_id_value[$option_style[$i]['name']][0];
                    }
                    $style .= write_css($option_style[$i]);
                }
            }
            if ($style !== '') {
                ?>
                <style>
                    <?php
                    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- already escaped
                    echo $style;
                    ?>
                </style>
                <?php
            }
        }
    }
}

if (!function_exists('ag_theme_post_option_render')) {
    /**
     * Change theme option value by post option
     *
     * @param array $field_id_values Post option value
     * @param array $theme_option    Theme option value
     * @param array $args            Args
     *
     * @return array
     */
    function ag_theme_post_option_render($field_id_values, $theme_option, $args)
    {
        if (!empty($field_id_values)) {
            foreach ($field_id_values as $option_name => $field_id_value) {
                if (isset($field_id_value[0])) {
                    $latest_posts_page = is_home() && !is_front_page();
                    $is_shop           = isset($args['page_type']) && $args['page_type'] === 'shop';
                    if ($latest_posts_page || is_single() || is_page() || $is_shop) {
                        switch ($option_name) {
                            case 'page_header_layout':
                                switch ((string) $field_id_value[0]) {
                                    case '0':
                                        $theme_option['menus_logo'] = '|left';
                                        break;
                                    case '1':
                                        $theme_option['menus_logo'] = '|center';
                                        break;
                                    case '2':
                                        $theme_option['menus_logo'] = '|right';
                                        break;
                                    case '3':
                                        $theme_option['menus_logo'] = '|centerA';
                                        break;
                                    case '4':
                                        break;
                                    default:
                                        break;
                                }
                                break;
                            case 'page_header_style':
                                switch ((string) $field_id_value[0]) {
                                    case '0':
                                        $theme_option['sticky_header']    = false;
                                        $theme_option['fixed_navigation'] = false;
                                        break;
                                    case '1':
                                        $theme_option['sticky_header']    = true;
                                        $theme_option['fixed_navigation'] = false;
                                        break;
                                    case '2':
                                        $theme_option['sticky_header']    = false;
                                        $theme_option['fixed_navigation'] = true;
                                        break;
                                    case '3':
                                        break;
                                    default:
                                        break;
                                }
                                break;
                            case 'page_top_content':
                                switch ((string) $field_id_value[0]) {
                                    case '0':
                                        $theme_option['top_content'] = false;
                                        break;
                                    case '1':
                                        $theme_option['top_content'] = true;
                                        break;
                                    case '2':
                                        break;
                                    default:
                                        break;
                                }
                                break;
                            case 'page_sidebar':
                                switch ((string) $field_id_value[0]) {
                                    case 'customizer':
                                        break;
                                    case 'left':
                                        $theme_option['load_sidebar'] = true;
                                        $theme_option['sidebar_position'] = 'left';
                                        break;
                                    case 'right':
                                        $theme_option['load_sidebar'] = true;
                                        $theme_option['sidebar_position'] = 'right';
                                        break;
                                    case 'none':
                                        $theme_option['load_sidebar'] = false;
                                        break;
                                }
                                break;
                            case 'header_divider':
                                break;
                            case 'page_title_content':
                                break;
                            default:
                                break;
                        }
                    }
                }
            }
        }
        return $theme_option;
    }
}
