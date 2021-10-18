<?php
/**
 * The default template for displaying content
 *
 * Used for both single and index/archive/search.
 */

defined('ABSPATH') || die;
if (isset($post->ID)) {
    $page_type      = 'page';
    $page_id        = $post->ID;
    $field_id_value = get_post_meta($page_id);
}
$page_title_content = (isset($field_id_value['page_title_content'][0]) && '' !== $field_id_value['page_title_content'][0]) ? $field_id_value['page_title_content'][0] : '0';
?>
    <article id="post-<?php the_ID(); ?>">
        <?php if (!is_single() && has_post_thumbnail() && !is_page()) :
            theme_post_thumbnail();
        endif; ?>
        <div class="post_<?php the_ID(); ?>">
            <?php
            if (is_front_page()) {
                if ($page_title_content !== '1' && is_page() || !is_page()) :
                    ?>
                <header class="entry-header">
                    <?php
                    if (function_exists('ag_breadcrumbs')) {
                        ag_breadcrumbs();
                    }
                    ?>
                    <h3 class="entry-title">
                        <a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>">
                            <?php the_title(); ?>
                        </a>
                    </h3>
                    <?php ag_theme_entry_meta(true); ?>
                </header>
                    <?php
                endif;
            } else {
                if (is_single() || is_page()) {
                    if ($page_title_content !== '1') :
                        ?>
                        <header class="entry-header">
                            <?php
                            if (function_exists('ag_breadcrumbs')) {
                                ag_breadcrumbs();
                            }
                            ?>
                            <h1 class="entry-title">
                                <a href="<?php the_permalink(); ?>" rel="bookmark"
                                   title="<?php the_title_attribute(); ?>">
                                    <?php the_title(); ?>
                                </a>
                            </h1>
                            <?php ag_theme_entry_meta(true); ?>
                        </header>
                        <?php
                    endif;
                } elseif (is_category()) { //category
                    ?>
                    <header class="entry-header">
                        <h3 class="entry-title">
                            <a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>">
                                <?php the_title(); ?>
                            </a>
                        </h3>
                        <?php ag_theme_entry_meta(true); ?>
                    </header>
                    <?php
                } elseif (is_home()) { //Posts page
                    ?>
                        <header class="entry-header">
                            <h3 class="entry-title">
                                <a href="<?php the_permalink(); ?>" rel="bookmark"
                                   title="<?php the_title_attribute(); ?>">
                                    <?php the_title(); ?>
                                </a>
                            </h3>
                            <?php ag_theme_entry_meta(true); ?>
                        </header>
                        <?php
                } else { //other post
                    if ($page_title_content !== '1') :
                        ?>
                        <header class="entry-header">
                            <h3 class="entry-title">
                                <a href="<?php the_permalink(); ?>" rel="bookmark"
                                   title="<?php the_title_attribute(); ?>">
                                    <?php the_title(); ?>
                                </a>
                            </h3>
                            <?php ag_theme_entry_meta(true); ?>
                        </header>
                        <?php
                    endif;
                };
            };
            ?>
            <?php if (!is_single()) : ?>
                <?php if (is_page()) : ?>
            <div class="entry-content ag_main_page">
                    <?php the_content(); ?>
                <?php else : ?>
                <div class="entry-content ag_main_excerpt">
                    <?php
                    // Filters the displayed post excerpt
                    the_excerpt(); ?>
                <?php endif; ?>
                    <?php
                    $args = array(
                        'before'      => '<div class="page-links"><span class="page-links-title">'
                                         . __('Pages:', 'advanced-gutenberg-theme') . '</span>',
                        'after'       => '</div>',
                        'link_before' => '<span>',
                        'link_after'  => '</span>',
                        'pagelink'    => '<span class="screen-reader-text">'
                                         . __('Page:', 'advanced-gutenberg-theme') . ' </span>%',
                        'separator'   => '<span class="screen-reader-text">, </span>',
                    );
                    wp_link_pages($args); ?>
            <?php else : ?>
                    <?php $post_format = get_post_format(); ?>
                    <div class="entry-content ag_main_container ag_main_container_<?php echo esc_attr($post_format); ?>">
                        <?php the_content(); ?>
            <?php endif; ?>
                    </div>
                    <footer class="entry-footer">
                        <?php
                        edit_post_link(__('Edit', 'advanced-gutenberg-theme'), '<span class="edit-link">', '</span>');
                        if (isset($post->ID) && ag_check_comments_open($post)) {
                            comments_template();
                        };
                        ?>
                    </footer>
                </div>
    </article>
<?php
