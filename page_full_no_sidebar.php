<?php
/**
 * Template Name: Page - No sidebar - Full width
 */
defined('ABSPATH') || die;

add_filter('advanced_gutenberg_theme_add_class_template', function ($option) {
    $option .= ' ag_sidebarHide ';
    return $option;
}, 10, 2);

get_header();

?>

<div id="main-content">
    <div id="container" class="no_wrapper_content">
        <div id="content">
            <main class="content-area">
                <?php if (have_posts()) : ?>
                    <?php while (have_posts()) : ?>
                        <?php the_post(); ?>
                        <?php get_template_part('content', false); ?>
                    <?php endwhile; ?>
                    <?php
                    the_posts_pagination(array(
                        'prev_text'          => __('Previous page', 'advanced-gutenberg-theme'),
                        'next_text'          => __('Next page', 'advanced-gutenberg-theme'),
                        'before_page_number' => '<span class="meta-nav screen-reader-text">' . __('Page', 'advanced-gutenberg-theme') . ' </span>',
                    ));
                    ?>
                <?php else : ?>
                    <?php get_template_part('content', false); ?>
                <?php endif; ?>
            </main>
        </div>
    </div>
</div>

<?php get_footer(); ?>
