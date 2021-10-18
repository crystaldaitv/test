<?php
defined('ABSPATH') || die;

$load_sidebar = ag_get_option(AG_THEME_NAME_OPTION, AG_THEME_FOLDER, '', 'load_sidebar', true);

get_header();
?>

<div id="main-content">
    <div id="container" class="wrapper_content">
        <div id="content">
            <main class="content-area">
                <?php
                require_once AG_THEME_CORE . '/header_function.php';
                ag_theme_get_archive_header('ag_archive', true, '');
                ?>
                <?php if (have_posts()) : ?>
                    <?php while (have_posts()) : ?>
                        <?php the_post(); ?>
                        <?php get_template_part('content', false); ?>
                    <?php endwhile; ?>
                    <?php
                    the_posts_pagination(array(
                        'prev_text'          => __('Previous page', 'advanced-gutenberg-theme'),
                        'next_text'          => __('Next page', 'advanced-gutenberg-theme'),
                        'before_page_number' => '<span class="meta-nav screen-reader-text">'
                                                . __('Page', 'advanced-gutenberg-theme') . ' </span>',
                    ));
                    ?>
                <?php else : ?>
                    <?php get_template_part('content', 'none'); ?>
                <?php endif; ?>
            </main>
        </div>
        <div id="sidebar">
            <?php
            if ($load_sidebar === true) {
                get_sidebar();
            }
            ?>
        </div>
    </div>
</div>
<?php get_footer(); ?>
