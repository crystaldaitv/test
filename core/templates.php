<?php
defined('ABSPATH') || die;

if (!function_exists('does_url_exists')) {
    /**
     * Check if a file exists from a url
     *
     * @param string $url Link
     *
     * @return boolean
     */
    function does_url_exists($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($code === 200) {
            $status = true;
        } else {
            $status = false;
        }
        curl_close($ch);
        return $status;
    }
}

if (!function_exists('wp_custom_logo')) {
    /**
     * Function wp_custom_logo Create logo
     *
     * @param string  $logo_width    Size to original image
     * @param string  $header_height Header height
     * @param string  $image_logo    Link logo image
     * @param string  $name_logo     Name logo image
     * @param boolean $is_home       Homepage
     * @param string  $class         Class for a tag
     *
     * @return void
     */
    function wp_custom_logo($logo_width, $header_height, $image_logo, $name_logo, $is_home, $class = '')
    {
        $srcset      = '';
        $width_table = 300;
        if ($image_logo !== '' && $image_logo !== false) {
            $folder = wp_upload_dir();

            $folderImageLogoDir = $folder['basedir'] . DIRECTORY_SEPARATOR . AG_THEME_NAME_OPTION . '/logo/';
            $folderImageLogoUri = $folder['baseurl'] . DIRECTORY_SEPARATOR . AG_THEME_NAME_OPTION . '/logo/';

            if ($name_logo === 'sticky_logo') {
                $folderImageLogoDir = $folder['basedir'] . DIRECTORY_SEPARATOR . AG_THEME_NAME_OPTION . '/sticky_logo/';
                $folderImageLogoUri = $folder['baseurl'] . DIRECTORY_SEPARATOR . AG_THEME_NAME_OPTION . '/sticky_logo/';
            }

            if (!file_exists($folder['basedir'] . DIRECTORY_SEPARATOR . AG_THEME_NAME_OPTION)) {
                mkdir($folder['basedir'] . DIRECTORY_SEPARATOR . AG_THEME_NAME_OPTION, 0777, true);
            }

            if (!file_exists($folderImageLogoDir)) {
                mkdir($folderImageLogoDir, 0777, true);
            }

            $image = pathinfo($image_logo);

            /*check image exist*/
            $file_exist = file_exists($folderImageLogoDir . $image['filename'] . '-' . $logo_width
                                      . '_' . $header_height . '.' . $image['extension']);

            if (isset($image['filename'])) {
                if (!$file_exist && does_url_exists($image_logo)) {
                    /*file not exist*/
                    /*delete all old thumbs logo image*/
                    ag_delete_files($folderImageLogoDir);
                    /*create new thumbs logo images*/
                    $imageLogo_width = wp_resize_image_upload($image, $logo_width, $header_height, $name_logo);
                }
                $image_logo = $folderImageLogoUri . $image['filename'] . '-' . $logo_width
                              . '_' . $header_height . '.' . $image['extension'];
                $size       = array();
                if ((int) $logo_width > 100) {
                    $srcset .= $folderImageLogoUri . $image['filename'] . '-100_'
                               . $header_height . '.' . $image['extension'] . ' 425w, ';
                    $size[] = '(max-width: 425px) 420px';
                }
                if ((int) $logo_width > 300) {
                    $srcset      .= $folderImageLogoUri . $image['filename'] . '-300_'
                                    . $header_height . '.' . $image['extension'] . ' 768w, ';
                    $size[]      = '(max-width: 768px) 760px';
                    $width_table = $logo_width * 768 / 1200;
                } else {
                    $width_table = $logo_width;
                }
                if ((int) $logo_width > 500) {
                    $srcset .= $folderImageLogoUri . $image['filename'] . '-500_'
                               . $header_height . '.' . $image['extension'] . ' 1200w, ';
                    $srcset .= $image_logo . ' 1600w';
                    $size[] = '1200px';
                } else {
                    $srcset .= $image_logo . ' 1200w';
                    $size[] = '1200px';
                }
                $sizes = implode(',', $size);
            }
        }
        if (!isset($sizes)) {
            $image_logo = get_template_directory_uri() . '/assets/images/logo.png';
        }
        ?>
        <div class="logo_container <?php echo $name_logo === 'logo_container' ? 'logo_global' : 'sticky_logo ag-hide'; ?>">
            <?php if ($is_home) : ?>
            <h1 class="logo">
            <?php endif; ?>
                <a class="<?php echo esc_attr($class);?>" href="<?php echo esc_url(home_url('/')); ?>"
                   id="<?php echo $name_logo === 'logo_container' ? 'logo' : 'sticky_logo'; ?>">
                    <?php if ($srcset !== '') : ?>
                        <img sizes="<?php echo esc_attr($sizes); ?>" src="<?php echo esc_attr($image_logo); ?>"
                             srcset="<?php echo esc_attr($srcset); ?>"
                             alt="<?php echo esc_attr(get_bloginfo('name')); ?>" data-height-percentage=""/>
                    <?php else : ?>
                        <img src="<?php echo esc_attr($image_logo); ?>"
                             alt="<?php echo esc_attr(get_bloginfo('name')); ?>" data-height-percentage=""/>
                    <?php endif; ?>
                </a>
                <?php if ($is_home) : ?>
            </h1>
                <?php endif; ?>
        </div>
        <style>
            @media screen and (max-width: 768px) {
                #wp_body_layout_home #page-header <?php echo $name_logo === 'logo_container' ? '#logo': '#sticky_logo';?> img {
                    max-width: <?php echo esc_attr($width_table) . 'px'?>;
                }
            }

            @media screen and (max-width: 420px) {
                #wp_body_layout_home #page-header <?php echo $name_logo === 'logo_container' ? '#logo': '#sticky_logo';?> img {
                    max-width: 100px;
                }
            }
        </style>
        <?php
    }
}

if (!function_exists('ag_post_format')) {
    /**
     * Get post format by id post
     *
     * @return mixed|void
     */
    function ag_post_format()
    {
        return apply_filters('ag_get_post_format', get_post_format(), get_the_ID());
    }
}

add_filter('ag_get_post_format', 'ag_post_format_in_pagebuilder', 10, 2);

/**
 * Return post format into false when using pagebuilder
 * return mixed string|bool string of post format or false for default
 *
 * @param string|false $post_format Post format
 * @param integer      $post_id     Id post
 *
 * @return boolean
 */
function ag_post_format_in_pagebuilder($post_format, $post_id)
{
    if (ag_pb_is_pagebuilder_used($post_id)) {
        return false;
    }

    return $post_format;
}

if (!function_exists('ag_pb_is_pagebuilder_used')) :
    /**
     * Function page builder
     *
     * @param integer $page_id Id page
     *
     * @return boolean
     */
    function ag_pb_is_pagebuilder_used($page_id)
    {
        return ('on' === get_post_meta($page_id, '_ag_pb_use_builder', true));
    }
endif;

if (!function_exists('ag_theme_image')) {
    /**
     * Function get image
     * can get option de lay loai image
     *
     * @param string $size Size image
     *
     * @return void
     */
    function ag_theme_image($size)
    {
        if (!is_single() && has_post_thumbnail() && !post_password_required() || has_post_format('image')) : ?>
            <figure class="post-thumbnail"><?php the_post_thumbnail($size); ?></figure><?php
        endif;
    }
}

if (!function_exists('ag_entry_header')) {
    /**
     * Get header
     *
     * @param string $post_format Format post
     *
     * @return void
     */
    function ag_entry_header($post_format)
    {
        if (is_single()) {
            ?>
            <h1 class="entry-title">
                <a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>">
                    <?php the_title(); ?>
                </a>
            </h1>
            <?php
        } else {
            ?>
            <h1 class="entry-title">
                <a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>">
                    <?php the_title(); ?>
                </a>
            </h1>
            <?php
        }
    }
}

if (!function_exists('theme_widgets')) {
    /**
     * Function add widgets footer
     *
     * @param string $position Position widgets
     *
     * @return void
     */
    function theme_widgets($position)
    {
        if ($position === 'footer') {
            /* The footer widget area is triggered if any of the areas
                    * have widgets. So let's check that first.
                    *
                    * If none of the sidebars have widgets, then let's bail early.
                    */
            if (!is_active_sidebar('first-footer-widget-area')
                && !is_active_sidebar('second-footer-widget-area')
                && !is_active_sidebar('third-footer-widget-area')
                && !is_active_sidebar('fourth-footer-widget-area')
            ) {
                return;
            } ?>
            <div id="footer-widgets">
                <aside class="fatfooter" role="complementary">
                    <?php
                    if (is_active_sidebar('first-footer-widget-area')) : ?>
                        <div class="first quarter left widget-area">
                            <?php dynamic_sidebar('first-footer-widget-area'); ?>
                        </div><!-- .first .widget-area -->
                    <?php endif;
                    if (is_active_sidebar('second-footer-widget-area')) : ?>
                        <div class="second quarter widget-area">
                            <?php dynamic_sidebar('second-footer-widget-area'); ?>
                        </div><!-- .second .widget-area -->
                    <?php endif;
                    if (is_active_sidebar('third-footer-widget-area')) : ?>
                        <div class="third quarter widget-area">
                            <?php dynamic_sidebar('third-footer-widget-area'); ?>
                        </div><!-- .third .widget-area -->
                    <?php endif;
                    if (is_active_sidebar('fourth-footer-widget-area')) : ?>
                        <div class="fourth quarter right widget-area">
                            <?php dynamic_sidebar('fourth-footer-widget-area'); ?>
                        </div><!-- .fourth .widget-area -->
                    <?php endif; ?>
                </aside><!-- #fatfooter -->
            </div>
            <?php
        } elseif ($position === 'header') {
            if (!is_active_sidebar('header-widget-area')) {
                return;
            }
            ?>
            <div id="header-widget-area" class="full-width widget-area">
                <?php dynamic_sidebar('header-widget-area'); ?>
            </div><!-- .widget-area -->
            <?php
        }
    }
}

if (!function_exists('theme_post_thumbnail')) {
    /**
     * Function get thumbnail
     *
     * @return void
     */
    function theme_post_thumbnail()
    {
        if (post_password_required() || is_attachment()) {
            return;
        }

        if (is_singular()) :
            ?>
            <div class="post-thumbnail">
                <?php the_post_thumbnail('thumbnail') ?>
            </div>
        <?php else :
            ?>
            <a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true">
                <?php
                the_post_thumbnail('post-thumbnail', array('alt' => get_the_title()));
                ?>
            </a>

        <?php endif;
    }
}

if (!function_exists('ag_theme_comment')) {
    /**
     * Template for comments and pingbacks.
     * Used as a callback by wp_list_comments() for displaying the comments.
     *
     * @param array  $comment Data comment
     * @param array  $args    Args
     * @param string $depth   Comment lever
     *
     * @return void
     */
    function ag_theme_comment($comment, $args, $depth)
    {
        // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
        $GLOBALS['comment'] = $comment;
        switch ($comment->comment_type) {
            case 'pingback':
            case 'trackback':
                ?>
                <li class="post pingback">
                <p><?php esc_attr_e('Pingback:', 'advanced-gutenberg-theme'); ?><?php comment_author_link(); ?><?php edit_comment_link(__('Edit', 'advanced-gutenberg-theme'), '<span class="edit-link">', '<span>'); ?></p>
                <?php
                break;
            default:
                ?>
            <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
                <article id="comment-<?php comment_ID(); ?>" class="comment-inner">

                    <div class="flex-row align-top">
                        <div class="flex-col">
                            <div class="comment-author mr-half">
                                <?php echo get_avatar($comment, 70); ?>
                            </div>
                        </div><!-- .large-3 -->

                        <div class="flex-col flex-grow">
                            <?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- already escaped
                            printf(__('%s <span class="says">says:</span>', 'advanced-gutenberg-theme'), sprintf('<cite class="strong fn">%s</cite>', get_comment_author_link())); ?>
                            <?php if ((int)$comment->comment_approved === 0) : ?>
                                <em><?php esc_attr_e('Your comment is awaiting moderation.', 'advanced-gutenberg-theme'); ?></em>
                                <br/>
                            <?php endif; ?>

                            <div class="comment-content"><?php comment_text(); ?></div>


                            <div class="comment-meta commentmetadata uppercase is-xsmall clear">
                                <a href="<?php echo esc_url(get_comment_link($comment->comment_ID)); ?>">
                                    <time datetime="<?php comment_time('c'); ?>" class="pull-left">
                                        <?php
                                        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- already escaped
                                        printf(_x('%1$s at %2$s', '1: date, 2: time', 'advanced-gutenberg-theme'), get_comment_date(), get_comment_time());
                                        ?>
                                    </time>
                                </a>
                                <?php edit_comment_link(__('Edit', 'advanced-gutenberg-theme'), '<span class="edit-link ml-half strong">', '<span>'); ?>

                                <div class="reply pull-right">
                                    <?php
                                    comment_reply_link(array_merge($args, array(
                                        'depth'     => $depth,
                                        'max_depth' => $args['max_depth'],
                                    )));
                                    ?>
                                </div><!-- .reply -->
                            </div><!-- .comment-meta .commentmetadata -->

                        </div><!-- .flex-col -->
                    </div><!-- .flex-row -->
                </article>
                <!-- #comment -->

                <?php
                break;
        };
    }
}
