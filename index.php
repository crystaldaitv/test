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
                if (is_home() && have_posts() && !is_front_page()) :
                    $page_for_posts = get_option('page_for_posts');
                    if (isset($page_for_posts)) {
                        require_once AG_THEME_CORE . '/header_function.php';
                        ag_theme_get_archive_header('ag_posts_page', false, $page_for_posts);
                    }
                endif;
                ?>
                <?php if (have_posts()) : ?>
                    <?php if (!is_home() && is_front_page()) : ?>
                        <header>
                            <h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
                        </header>
                    <?php endif;
                    while (have_posts()) :
                        the_post();
                        get_template_part('content', false);
                    endwhile;

                    the_posts_pagination(array(
                        'prev_text'          => __('←', 'advanced-gutenberg-theme'),
                        'next_text'          => __('→', 'advanced-gutenberg-theme'),
                        'before_page_number' => '<span class="meta-nav screen-reader-text">'
                                                . __('Page', 'advanced-gutenberg-theme') . ' </span>'
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
