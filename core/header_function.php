<?php
defined('ABSPATH') || die;

if (!function_exists('ag_theme_get_archive_header')) {
    /**
     * Get archive header in category and posts page
     *
     * @param string  $class   Class adding
     * @param boolean $archive Check archive post
     * @param string  $id      Id page in latest posts page
     *
     * @return void
     */
    function ag_theme_get_archive_header($class, $archive, $id)
    {
        if (!$archive) {
            $field_id_value     = get_post_meta($id);
            $page_title_content = (isset($field_id_value['page_title_content'][0]) && '' !== $field_id_value['page_title_content'][0]) ? $field_id_value['page_title_content'][0] : '0';
            $postData = get_post($id);
            $title = $postData->post_title;
        } else {
            $page_title_content = '0';
            $title = get_the_title();
        }
        ?>
        <div class="archive-header <?php echo esc_attr($class); ?>">
            <?php
            if ($archive) {
                ?>
                <h1 class="archive-title">
                <?php
                the_archive_title();
                ?>
                </h1>
                <?php
            } else {
                if ($page_title_content !== '1') {
                    if (function_exists('ag_breadcrumbs')) {
                        ag_breadcrumbs();
                    }
                    ?>
                <h1 class="archive-title">
                    <?php echo esc_attr($title); ?>
                </h1>
                    <?php
                }
            }
            ?>
            <div class="archive-description">
                <?php
                if ($archive) {
                    the_archive_description();
                }
                ?>
            </div>
        </div>
        <?php
    }
}
