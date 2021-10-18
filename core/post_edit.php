<?php
defined('ABSPATH') || die;

/**
 * Created by PhpStorm.
 * User: dandelion
 * Date: 19/09/2019
 * Time: 09:03
 */

if (!function_exists('ag_get_list_page_callback')) {
    /**
     * Get list exist page
     *
     * @return void
     */
    function ag_get_list_page_callback()
    {
        if (isset($_POST['option_nonce'])
            && wp_verify_nonce(sanitize_key($_POST['option_nonce']), 'option_nonce')
        ) {
            $args = array(
                'numberposts' => - 1
            );
            $data = get_posts($args);
            $data = array_merge($data, get_pages($args));

            $count      = count($data);
            $dataReturn = array();
            for ($i = 0; $i < $count; $i ++) {
                $dataReturn[$data[$i]->ID] = $data[$i];
            }

            $templatePostsMeta = get_option(
                'theme_ju_template_posts',
                array()
            );

            $data = array();
            foreach ($templatePostsMeta as $key => $template) {
                if (isset($dataReturn[$key])) {
                    $data[$key]                = new stdClass();
                    $data[$key]->templates     = $template;
                    $data[$key]->ID            = $dataReturn[$key]->ID;
                    $data[$key]->post_title    = $dataReturn[$key]->post_title;
                    $data[$key]->post_status   = $dataReturn[$key]->post_status;
                    $data[$key]->post_modified = $dataReturn[$key]->post_modified;
                    $data[$key]->post_type     = $dataReturn[$key]->post_type;
                    $data[$key]->post_content  = $dataReturn[$key]->post_content;
                }
            }
            wp_send_json(array(true, $data));
        } else {
            wp_send_json(array(false, null));
        }
    }

    add_action('wp_ajax_theme_ag_get_list_page', 'ag_get_list_page_callback');
}

if (!function_exists('ag_add_new_content_page')) {
    /**
     * Add new content to page by exist page
     *
     * @return void
     */
    function ag_add_new_content_page()
    {
        if (isset($_POST['post_type'], $_POST['option_nonce'], $_POST['ID'], $_POST['id_post'])
            && wp_verify_nonce(sanitize_key($_POST['option_nonce']), 'option_nonce')
        ) {
            $data      = get_post($_POST['ID']);
            $post_type = $_POST['post_type'];
            $id_post   = $_POST['id_post'];

            $content_post = get_post($id_post);

            if ($content_post === null) {
                wp_send_json(array(false, null));
            }

            if ($data->post_content !== '') {
                $id = theme_ju_create_post(
                    $id_post,
                    $content_post->post_title,
                    $data->post_content,
                    $content_post->post_status,
                    $content_post->post_author,
                    $post_type
                );

                if (!is_wp_error($id)) {
                    theme_ju_create_post_meta($id_post, '_wp_page_template', 'theme_ju_template.php');
                    $templatePostsMeta = get_option(
                        'theme_ju_template_posts',
                        array()
                    );
                    if (isset($templatePostsMeta[$id_post])) {
                        unset($templatePostsMeta[$id_post]);
                    }

                    if (isset($templatePostsMeta[$data->ID])) {
                        $templatePost = $templatePostsMeta[$data->ID];
                        $post_meta    = theme_ju_update_post_meta_val($templatePostsMeta, $id_post, $templatePost, $post_type);
                        wp_send_json(array(true, $post_meta));
                    }
                }
            }
            wp_send_json(array(false, null));
        } else {
            wp_send_json(array(false, null));
        }
    }

    add_action('wp_ajax_theme_ag_add_new_content_page', 'ag_add_new_content_page');
}
