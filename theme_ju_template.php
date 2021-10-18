<?php
/**
 * Template Name: Theme JU template
 * Template Post Type: post, page, product
 */

defined('ABSPATH') || die;

$templates    = get_option('theme_ju_template_posts', null);
$theme_option = ag_get_option(AG_THEME_NAME_OPTION, AG_THEME_FOLDER, 'full', '', array());

global $post;

$id_post   = $post->ID;

if (!empty($templates[$id_post])) {
    $folder       = wp_upload_dir();
    $templatesDir = $folder['basedir'] . DIRECTORY_SEPARATOR . AG_THEME_NAME_OPTION
        . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR;
    $template = $templates[$id_post];

    if (isset($template['template'])) {
        $packets = array_keys($template['template']);
    } else {
        $packets = array_keys($template);
    }
    $count   = count($packets);
    $linkFile = '';

    for ($i = 0; $i < $count; $i ++) {
        $linkFile = $templatesDir . $packets[$i] . DIRECTORY_SEPARATOR . 'layouts.php';
        if (file_exists($linkFile)) {
            require_once $linkFile;
        }
    }
} else {
    require_once AG_THEME_URL . DIRECTORY_SEPARATOR . 'index.php';
}
