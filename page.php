<?php
defined('ABSPATH') || die;

$load_sidebar = ag_get_option(AG_THEME_NAME_OPTION, AG_THEME_FOLDER, '', 'load_sidebar', true);
$sidebar_position = ag_get_option(AG_THEME_NAME_OPTION, AG_THEME_FOLDER, '', 'sidebar_position', 'right');
$page_sidebar = get_post_meta(get_the_ID(), 'page_sidebar', true);

if ($page_sidebar !== 'customizer') {
    $load_sidebar = ($page_sidebar === 'none') ? false : true;
    $sidebar_position = $page_sidebar;
}
get_header();
?>

<div id="main-content">
    <div id="container" class="wrapper_content">
        <?php if ($load_sidebar === true && $sidebar_position === 'left') { ?>
            <div id="sidebar">
                <?php get_sidebar(); ?>
            </div>
        <?php } ?>
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
        <?php if ($load_sidebar === true && $sidebar_position === 'right') { ?>
        <div id="sidebar">
            <?php get_sidebar(); ?>
        </div>
        <?php } ?>
    </div>
</div>

<?php get_footer(); ?>
