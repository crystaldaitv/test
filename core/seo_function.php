<?php
defined('ABSPATH') || die;

if (!function_exists('ag_breadcrumbs')) {
    /**
     * Print breadcrumbs string to page in site
     *
     * @return void
     */
    function ag_breadcrumbs()
    {
        $load_sidebar = (int) ag_get_option(AG_THEME_NAME_OPTION, AG_THEME_FOLDER, 'option', 'custom.agBreadcrumb', '1');
        if ($load_sidebar === 1) {
            $delimiter     = ' / ';
            $currentBefore = '<span class="current">';
            $currentAfter  = '</span>';
            if (!is_home() && !is_front_page() || is_paged()) {
                global $post;
                echo '<div id="ag_crumbs">';
                if (is_category()) {
                    global $wp_query;
                    $cat_obj   = $wp_query->get_queried_object();
                    $thisCat   = $cat_obj->term_id;
                    $thisCat   = get_category($thisCat);
                    $parentCat = get_category($thisCat->parent);

                    if ((int) $thisCat->parent !== 0) {
                        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- already escaped
                        echo get_category_parents($parentCat, true, $delimiter);
                    }
                    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- already escaped
                    echo $currentBefore . 'Archive by category &#39;';
                    single_cat_title();
                    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- already escaped
                    echo '&#39;' . $currentAfter;
                } elseif (is_day()) {
                    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- already escaped
                    echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a>' . $delimiter;
                    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- already escaped
                    echo '<a href="' . get_month_link(get_the_time('Y'), get_the_time('m')) . '">' . get_the_time('F') . '</a>' . $delimiter;
                    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- already escaped
                    echo $currentBefore . get_the_time('d') . $currentAfter;
                } elseif (is_month()) {
                    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- already escaped
                    echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a>' . $delimiter;
                    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- already escaped
                    echo $currentBefore . get_the_time('F') . $currentAfter;
                } elseif (is_year()) {
                    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- already escaped
                    echo $currentBefore . get_the_time('Y') . $currentAfter;
                } elseif (is_single() && !is_attachment()) {
                    $cat = get_the_category();

                    if (!empty($cat)) {
                        $cat = $cat[0];
                        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- already escaped
                        echo get_category_parents($cat, true, $delimiter);
                    }
                    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- already escaped
                    echo $currentBefore;
                    the_title();
                    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- already escaped
                    echo $currentAfter;
                } elseif (is_attachment()) {
                    $parent = get_post($post->post_parent);
                    $cat    = get_the_category($parent->ID);

                    if (!empty($cat)) {
                        $cat = $cat[0];
                        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- already escaped
                        echo get_category_parents($cat, true, $delimiter);
                    }
                    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- already escaped
                    echo '<a href="' . get_permalink($parent) . '">' . $parent->post_title . '</a> ' . $delimiter . ' ';
                    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- already escaped
                    echo $currentBefore;
                    the_title();
                    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- already escaped
                    echo $currentAfter;
                } elseif (is_page() && !$post->post_parent) {
                    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- already escaped
                    echo $currentBefore;
                    the_title();
                    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- already escaped
                    echo $currentAfter;
                } elseif (is_page() && $post->post_parent) {
                    $parent_id   = $post->post_parent;
                    $breadcrumbs = array();
                    while ($parent_id) {
                        $page          = get_page($parent_id);
                        $breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
                        $parent_id     = $page->post_parent;
                    }
                    $breadcrumbs = array_reverse($breadcrumbs);
                    foreach ($breadcrumbs as $crumb) {
                        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- already escaped
                        echo $crumb . ' ' . $delimiter . ' ';
                    }
                    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- already escaped
                    echo $currentBefore;
                    the_title();
                    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- already escaped
                    echo $currentAfter;
                } elseif (is_tag()) {
                    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- already escaped
                    echo $currentBefore . 'Posts tagged &#39;';
                    single_tag_title();
                    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- already escaped
                    echo '&#39;' . $currentAfter;
                } elseif (is_search()) {
                    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- already escaped
                    echo $currentBefore . 'Search results for &#39;' . get_search_query() . '&#39;' . $currentAfter;
                } elseif (is_author()) {
                    global $author;
                    $userdata = get_userdata($author);
                    if (!empty($userdata)) {
                        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- already escaped
                        echo $currentBefore . 'Articles posted by ' . $userdata->display_name . $currentAfter;
                    }
                } elseif (is_404()) {
                    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- already escaped
                    echo $currentBefore . 'Error 404' . $currentAfter;
                }

                if (get_query_var('paged')) {
                    if (is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author()) {
                        echo ' (';
                    }
                    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- already escaped
                    echo esc_attr__('Page', 'advanced-gutenberg-theme') . ' ' . get_query_var('paged');
                    if (is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author()) {
                        echo ')';
                    }
                }

                echo '</div>';
            }
        }
    }
}
