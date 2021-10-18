<?php
/**
 * Created by PhpStorm.
 * User: dandelion
 * Date: 05/09/2019
 * Time: 10:21
 */

defined('ABSPATH') || die;

if (!function_exists('ag_get_default_option_layouts')) {
    /**
     * Function get default value template option customizer
     *
     * @param boolean        $getCustomizer Get customizer of templates
     * @param boolean|string $template      Get option of a template
     *
     * @return array
     */
    function ag_get_default_option_layouts($getCustomizer = false, $template = false)
    {
        $data        = array();
        $dataDefault = get_option('theme_ju_template_default_option', array());

        $themeJuTemplatePack = theme_ju_get_layout_pack(array('installed'));
        $count               = count($themeJuTemplatePack['installed']);

        for ($i = 0; $i < $count; $i ++) {
            if (empty($dataDefault[$themeJuTemplatePack['installed'][$i]])) {
                $json_data                                          = ag_create_default_option_layout($themeJuTemplatePack['installed'][$i], $dataDefault);
                $dataDefault[$themeJuTemplatePack['installed'][$i]] = $json_data;
                if (!$json_data) {
                    $data[$themeJuTemplatePack['installed'][$i]] = array();
                } else {
                    $data[$themeJuTemplatePack['installed'][$i]] = $json_data;
                }
            }
            if ($template === false) {
                $data[$themeJuTemplatePack['installed'][$i]] = $dataDefault[$themeJuTemplatePack['installed'][$i]]['template_style'];
                if ($getCustomizer) {
                    $data[$themeJuTemplatePack['installed'][$i]]['getCustomizer'] = $dataDefault[$themeJuTemplatePack['installed'][$i]]['customizer'];
                }
            }
        }

        if ($template !== false) {
            $data = $dataDefault[$template];
        }
        return $data;
    }
}

if (!function_exists('ag_create_default_option_layout')) {
    /**
     * Function create theme_ju_template_default_option option by customizer.json in layouts folder
     *
     * @param string $templateName Name layout
     * @param array  $data_default Default customizer option layouts
     *
     * @return boolean|string
     */
    function ag_create_default_option_layout($templateName, $data_default)
    {
        $fileDir = AG_THEME_UPLOAD_FOLDERDIR . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $templateName . DIRECTORY_SEPARATOR . 'customizer.json';

        if (file_exists($fileDir)) {
            $json_data = file_get_contents($fileDir);
            $json_data = json_decode($json_data, true);

            $data_default[$templateName] = $json_data;
            update_option('theme_ju_template_default_option', $data_default);

            return $json_data;
        }

        return false;
    }
}

if (!function_exists('addToDefaultOptionTheme')) {
    /**
     * Add template option to default option in theme
     *
     * @param array $option  Data customizer option theme
     * @param array $default Default customizer option theme, template
     *
     * @return array
     */
    function addToDefaultOptionTheme($option, $default)
    {
        if (!empty($default)) {
            $data    = array();
            $default = array_merge($default, $data);
            if (!empty($option['select_layout_style']) && $option['select_layout_style'] !== 'Custom') {
                $data_default = get_option('theme_ju_template_default_option', array());
                $default      = array_merge($default, $data_default[$option['select_layout_style']]['customizer']);
            }

            return $default;
        }
    }
}

if (!function_exists('action_customize_save')) {
    /**
     * Update theme_ju_custom_option when customize save
     *
     * @param object $instance Instance
     *
     * @return void
     */
    function action_customize_save($instance)
    {
        $AGtheme    = get_option('AGtheme', array());
        $oldOptions = get_option('theme_ju_custom_option', array());
        $background_image        = get_theme_mod('background_image', '');
        $background_header_image = get_theme_mod('background_header_image', '');

        if (isset($AGtheme['select_layout_style']) && $AGtheme['select_layout_style'] !== '') {
            $oldOptions[$AGtheme['select_layout_style']] = $AGtheme;
        }

        update_option('theme_ju_custom_option', $oldOptions);
    }
}

add_action('customize_save', 'action_customize_save', 10, 1);
