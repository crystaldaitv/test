<?php
/*
 * If the current post is protected by a password and the visitor has not yet
 * entered the password we will return early without loading the comments.
 */
defined('ABSPATH') || die;

if (post_password_required()) {
    return;
}
?>

<div id="comments" class="comments-area">
    <?php if (have_comments()) : ?>
        <h5 class="comments-title">
            <?php
            printf(
                esc_attr(
                    '%1$s Comments',
                    get_comments_number(),
                    'advanced-gutenberg-theme'
                ),
                esc_attr(number_format_i18n(get_comments_number())),
                esc_html('<span>' . get_the_title() . '</span>')
            );
            ?>
        </h5>

        <ol class="comment-list">
            <?php
            wp_list_comments(array('callback' => 'ag_theme_comment'));
            ?>
        </ol><!-- .comment-list -->

        <?php
        // Are there comments to navigate through?
        if (get_comment_pages_count() > 1 && get_option('page_comments')) :
            ?>
            <nav class="navigation comment-navigation" role="navigation">
                <h3 class="screen-reader-text section-heading">
                    <?php esc_attr_e('Comment navigation', 'advanced-gutenberg-theme'); ?></h3>
                <div class="nav-previous">
                    <?php previous_comments_link(__('&larr; Older Comments', 'advanced-gutenberg-theme')); ?></div>
                <div class="nav-next">
                    <?php next_comments_link(__('Newer Comments &rarr;', 'advanced-gutenberg-theme')); ?></div>
            </nav><!-- .comment-navigation -->
        <?php endif; // Check for comment navigation ?>

        <?php if (!comments_open() && get_comments_number()) : ?>
            <p class="no-comments"><?php esc_attr_e('Comments are closed.', 'advanced-gutenberg-theme'); ?></p>
        <?php endif; ?>

    <?php endif; // have_comments() ?>

    <?php ag_get_comment_form(); ?>

</div><!-- #comments -->
