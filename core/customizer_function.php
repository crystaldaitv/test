<?php
defined('ABSPATH') || die;

/**
 * Created by PhpStorm.
 * User: dandelion
 * Date: 01/10/2019
 * Time: 16:11
 */

if (!function_exists('ag_set_font_styles')) {
    /**
     * Function set font style
     *
     * @param string $value         Value
     * @param string $important_tag Important tag
     *
     * @return array
     */
    function ag_set_font_styles($value, $important_tag)
    {
        $font_styles = explode('|', $value);
        $styleArray  = array();
        if (in_array('Bold', $font_styles)) {
            $styleArray[0] = 'bold ' . $important_tag . ';';
        } else {
            $styleArray[0] = 'initial ' . $important_tag . ';';
        }

        if (in_array('Italic', $font_styles)) {
            $styleArray[1] = 'italic ' . $important_tag . ';';
        } else {
            $styleArray[1] = 'initial ' . $important_tag . ';';
        }

        if (in_array('Underline', $font_styles)) {
            $styleArray[2] = 'underline ' . $important_tag . ';';
        } else {
            $styleArray[2] = 'initial ' . $important_tag . ';';
        }

        if (in_array('Uppercase', $font_styles)) {
            $styleArray[3] = 'uppercase ' . $important_tag . ';';
        } else {
            $styleArray[3] = 'initial ' . $important_tag . ';';
        }

        return $styleArray;
    }
}

if (!function_exists('write_css')) {
    /**
     * Function create string css
     *
     * @param array $options Data css for element
     *                       (object : properties = n:n, properties : value = n:1, value : unit = 1:1)
     *
     * @return string
     */
    function write_css($options = array())
    {
        $element    = is_array($options['object']) ? $options['object'] : array($options['object']);
        $properties = is_array($options['properties']) ? $options['properties'] : array($options['properties']);
        $value      = is_array($options['value']) ? $options['value'] : array($options['value']);
        $option     = is_array($options['unit']) ? $options['unit'] : array($options['unit']);
        $min        = isset($options['min']) ? $options['min'] : '';
        $max        = isset($options['max']) ? $options['max'] : '';
        $content    = '';
        if ($min . $max !== '') {
            if ($min !== '') {
                $content .= '@media ';
                $content .= '(min-width: ' . $min . 'px)';
                if ($max !== '') {
                    $content .= ' and (max-width: ' . $max . 'px)';
                }
                $content .= ' {';
            } elseif ($max !== '') {
                $content .= '@media ';
                $content .= '(max-width: ' . $max . 'px) {';
            }
        }
        $countElement = count($element);
        for ($j = 0; $j < $countElement; $j ++) {
            $content .= $element[$j] . ' {' . "\n";

            $optionJ = '';
            if ($countElement === 1) {
                $propertiesJ = $properties;
            } else {
                $properties[$j] = isset($properties[$j]) ? $properties[$j] : $properties[0];
                $propertiesJ    = is_array($properties[$j]) ? $properties[$j] : array($properties[$j]);
            }

            $countPropertiesJ = count($propertiesJ);
            if ($countPropertiesJ === 1 && isset($value[1])) {
                $valueJ = array($value[$j]);
            } else {
                $valueJ = $value;
            }

            for ($i = 0; $i < $countPropertiesJ; $i ++) {
                $valueI  = isset($valueJ[$i]) && $valueJ[$i] !== '' ? $valueJ[$i] : $valueJ[0];
                $optionJ = isset($option[$i]) ? $option[$i] : $option[0];
                if ($propertiesJ[$i] !== null) {
                    switch ($optionJ) {
                        case 'url':
                            $content .= $propertiesJ[$i] . ': url(' . (string) $valueI . ');' . "\n";
                            break;
                        case 'none':
                            $content .= $propertiesJ[$i] !== '' ?
                                $propertiesJ[$i] . ': ' . (string) $valueI . ';' :
                                (string) $valueI . "\n";
                            break;
                        default:
                            $content .= $propertiesJ[$i] . ': ' . (string) $valueI . $optionJ . ';' . "\n";
                            break;
                    }
                }
            }

            $content .= '}' . "\n";
        }
        if ($min . $max !== '') {
            $content .= '}' . "\n";
        }

        return $content;
    }
}

if (!function_exists('compile_file_css')) {
    /**
     * Function compile content file.css
     *
     * @param array  $option    Data style
     * @param string $customCss Data custom css
     *
     * @return string
     */
    function compile_file_css($option, $customCss)
    {
        $content = '';
        /*add change of theme option for var themeOption in woocommerce*/
        do_action('ag_add_woocommerce_themeOption');

        require_once(AG_THEME_INCLUDES_URL . '/google-font-datas.php');
        $data_font = get_saved_google_fonts();

        /*get list option theme*/

        //load default option theme
        require_once(AG_THEME_INCLUDES_URL . '/default-option-theme.php');
        $agDefaultTheme = new AgDefaultTheme();
        $option_style   = $agDefaultTheme->defaultOptionTheme();
        $value_not_render   = $agDefaultTheme->valueNotRender();

        //add template option to default option in theme
        require_once AG_THEME_CORE . '/template_customize.php';
        $option_style   = addToDefaultOptionTheme($option, $option_style);

        $count                         = count($option_style);
        for ($i = 0; $i < $count; $i ++) {
            if ($option_style[$i]['properties'] !== null && !in_array($option_style[$i]['name'], $value_not_render)) {
                if (isset($option[$option_style[$i]['name']])) {
                    $option_style[$i]['value'] = $option[$option_style[$i]['name']];
                }
                switch ($option_style[$i]['name']) {
                    case 'sidebar_width':
                        $value                     = floatval($option_style[$i]['value']);
                        $option_style[$i]['value'] = array(
                            $value,
                            100 - $value
                        );
                        break;
                    case 'body_header_size':
                        $value                     = floatval($option_style[$i]['value']);
                        $option_style[$i]['value'] = array(
                            $value,
                            $value * 0.9,
                            $value * 0.77,
                            $value * 0.64,
                            $value * 0.5,
                            $value * 0.36
                        );
                        break;
                    case 'widgets_header_text_size':
                        $value                     = floatval($option_style[$i]['value']);
                        $option_style[$i]['value'] = array(
                            $value,
                            $value * 0.9,
                            $value * 0.77,
                            $value * 0.64,
                            $value * 0.5,
                            $value * 0.47
                        );
                        break;
                    case 'body_header_style':
                        $value                     = ag_set_font_styles($option_style[$i]['value'], '');
                        $option_style[$i]['value'] = $value;
                        break;
                    case 'sidebar_header_style':
                        $value                     = ag_set_font_styles($option_style[$i]['value'], '');
                        $option_style[$i]['value'] = $value;
                        break;
                    case 'mobile_font_style':
                        $value                     = ag_set_font_styles($option_style[$i]['value'], '');
                        $option_style[$i]['value'] = $value;
                        break;
                    case 'font_style':
                        $value                     = ag_set_font_styles($option_style[$i]['value'], '');
                        $option_style[$i]['value'] = $value;
                        break;
                    case 'widgets_header_font_style':
                        $value                     = ag_set_font_styles($option_style[$i]['value'], '');
                        $option_style[$i]['value'] = $value;
                        break;
                    case 'widgets_body_font_style':
                        $value                     = ag_set_font_styles($option_style[$i]['value'], '');
                        $option_style[$i]['value'] = $value;
                        break;
                    case 'border_top_sub_menu':
                        $value                     = $option_style[$i]['value'];
                        $option_style[$i]['value'] = $value;
                        break;
                    case 'body_font_google':
                        if ($option_style[$i]['value'] !== '') {
                            $option_style[$i]['value'] = '\'' . $option_style[$i]['value'] . '\','
                                                         . $data_font[$option_style[$i]['value']]['type'];
                        } else {
                            $option_style[$i]['value'] = '\'Open Sans\', sans-serif';
                        }
                        break;
                    case 'header_font_google':
                        if ($option_style[$i]['value'] !== '' && isset($data_font[$option_style[$i]['value']])) {
                            $option_style[$i]['value'] = '\'' . $option_style[$i]['value'] . '\','
                                                         . $data_font[$option_style[$i]['value']]['type'];
                        } else {
                            $option_style[$i]['value'] = '\'Open Sans\', sans-serif';
                        }
                        break;
                    case 'active_link_color':
                        if ($option_style[$i]['value'] !== '') {
                            $option_style[$i]['value'] = $option_style[$i]['value'];
                        }
                        break;
                    case 'sticky_active_link_color':
                        if ($option_style[$i]['value'] !== '') {
                            $option_style[$i]['value'] = $option_style[$i]['value'];
                        }
                        break;
                    case 'menu_height':
                        if ($option_style[$i]['value'] !== '') {
                            $option_style[$i]['value'] = array(
                                $option_style[$i]['value'] - 1,
                                $option_style[$i]['value'],
                                $option_style[$i]['value'],
                                $option_style[$i]['value'],
                                $option_style[$i]['value'],
                            );
                        }
                        break;
                    default:
                        break;
                }
                if ($option_style[$i]['unit'] !== 'url' || $option_style[$i]['value'] !== '') {
                    $content .= write_css($option_style[$i]);
                }
            }
        }

        if (isset($customCss) && $customCss !== '') {
            $content .= "\n" . $customCss;
        }

        $contents = '';
        require_once(AG_THEME_INCLUDES_URL . '/less.php/Less.php');
        $parser = new Less_Parser();
        $parser->parse($content);
        $contents .= $parser->getCss();

        return $contents;
    }

    add_filter('wp_compile_file_css', 'compile_file_css', 10, 3);
}

if (!function_exists('compile_style')) {
    /**
     * Function create file style*.css
     *
     * @param array  $option    Data option
     * @param string $customCss Data style custom
     *
     * @return boolean|string
     */
    function compile_style($option, $customCss = '')
    {
        $folderDir = AG_THEME_UPLOAD_FOLDERDIR . DIRECTORY_SEPARATOR;
        $folderUrl = AG_THEME_UPLOAD_FOLDERURL . '/';
        $file      = $folderUrl . 'style_customize.css';

        if (!file_exists($folderDir)) {
            mkdir($folderDir, 0777, true);
        }
        $checkExistsOption = get_option(AG_THEME_NAME_OPTION);
        if ($checkExistsOption === false || !file_exists($folderDir . 'style_customize.css')) {
            if (file_exists($folderDir . 'style_customize.css')) {
                return $file;
            } else {
                array_map('unlink', glob($folderDir . '*.css'));
                $option['check_customize'] = 0;
                $content                   = '';
                $content                   = apply_filters('wp_compile_file_css', $option, $customCss);
                if (!file_put_contents($folderDir . 'style_customize.css', $content)) {
                    return false;
                }
            }
        } elseif ((string) $option['check_customize'] === '1') {
            array_map('unlink', glob($folderDir . '*.css'));
            $option['check_customize'] = 0;

            update_option(AG_THEME_NAME_OPTION, $option);

            $content = '';
            $content = apply_filters('wp_compile_file_css', $option, $customCss);
            if (!file_put_contents($folderDir . 'style_customize.css', $content)) {
                return false;
            }
        }

        return $file;
    }

    add_filter('ag_compilestyle_theme', 'compile_style', 10, 3);
}
