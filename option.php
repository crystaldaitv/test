<?php
defined('ABSPATH') || die;
?>
<div id="theme_option" class="tab-pane">
    <h2 class="settings-separator-title">WooCommerce</h2>
    <div class="ju-settings-option full-width woocommerce">
        <div class="custom_css ju-settings-option">
            <label class="tooltip ju-setting-label" data-toggle="tooltip" data-placement="top" title="
            <?php
            esc_attr_e(
                'Select the number of WooCommerce products to display on the category product listing views',
                'advanced-gutenberg-theme'
            );
            ?>">
                <?php esc_attr_e('WooCommerce products on category pages', 'advanced-gutenberg-theme'); ?>
                :</label>

            <div class="ag-box-content">
                <input class=" ju-input" id="ag_woocommerce_archive_num_posts" type="number" value="9">
            </div>
        </div>
        <div class="custom_body ju-settings-option">
            <label class="tooltip ju-setting-label" data-toggle="tooltip" data-placement="top" title="
            <?php
            esc_attr_e('Image size used for the large image on product slider widget', 'advanced-gutenberg-theme'); ?>">
                <?php esc_attr_e('Product large image width (px)', 'advanced-gutenberg-theme'); ?>
                :</label>

            <div class="ag-box-content">
                <input class=" ju-input" id="ag_woo_single" type="number" value="1000">
            </div>
        </div>
    </div>

    <h2 class="settings-separator-title">Wordpress</h2>
    <div class="ju-settings-option full-width wordpress">
        <div class="custom_head ju-settings-option">
            <label class="tooltip ju-setting-label" data-toggle="tooltip" data-placement="top" title="
            <?php
            esc_attr_e(
                'Select the number of Posts to display on the category listing pages (blog)',
                'advanced-gutenberg-theme'
            );
            ?>">
                <?php esc_attr_e('Posts on category pages', 'advanced-gutenberg-theme'); ?>
                :</label>

            <div class="ag-box-content">
                <input class=" ju-input" id="ag_cat_page" type="number" value="6">
            </div>
        </div>
        <div class="custom_body ju-settings-option">
            <label class="tooltip ju-setting-label" data-toggle="tooltip" data-placement="top" title="
            <?php
            esc_attr_e(
                'Select the number of Posts to display on the archive listing views',
                'advanced-gutenberg-theme'
            );
            ?>">
                <?php esc_attr_e('Posts displayed on archive pages', 'advanced-gutenberg-theme'); ?>
                :</label>

            <div class="ag-box-content">
                <input class=" ju-input" id="ag_archive_page" type="number" value="5">
            </div>
        </div>
        <div class="custom_head ju-settings-option">
            <label class="tooltip ju-setting-label" data-toggle="tooltip" data-placement="top" title="
            <?php
            esc_attr_e(
                'Select the number of Posts to display on the WordPress post search engine',
                'advanced-gutenberg-theme'
            );
            ?>">
                <?php esc_attr_e('Posts displayed on search results', 'advanced-gutenberg-theme'); ?>
                :</label>

            <div class="ag-box-content">
                <input class=" ju-input" id="ag_search_page" type="number" value="5">
            </div>
        </div>
        <div class="custom_body ju-settings-option">
            <label class="tooltip ju-setting-label" data-toggle="tooltip" data-placement="top" title="
            <?php
            esc_attr_e(
                'Select the number of Posts to display on the tag listing views',
                'advanced-gutenberg-theme'
            );
            ?>">
                <?php esc_attr_e('Posts displayed on tag pages', 'advanced-gutenberg-theme'); ?>
                :</label>

            <div class="ag-box-content">
                <input class=" ju-input" id="ag_tag_page" type="number" value="5">
            </div>
        </div>

        <div class="custom_body ju-settings-option">
            <label class="tooltip ju-setting-label" data-toggle="tooltip" data-placement="top" title="
            <?php esc_attr_e('Option to use breadcrumb', 'advanced-gutenberg-theme'); ?>">
                <?php esc_attr_e('Use breadcrumb', 'advanced-gutenberg-theme'); ?>
                :</label>
            <div class="ag-box-content ju-switch-button">
                <label class="switch">
                    <input type="checkbox" name="your-setting-name" id="ag_breadcrumb" value="1" checked />
                    <span class="slider" data-show="ag_breadcrumb"></span>
                </label>
            </div>
        </div>

        <div class="custom_body ju-settings-option">
            <label class="tooltip ju-setting-label" data-toggle="tooltip" data-placement="top" title="
            <?php esc_attr_e('Option to use style footer according to template', 'advanced-gutenberg-theme'); ?>">
                <?php esc_attr_e('Use style footer to template', 'advanced-gutenberg-theme'); ?>
                :</label>
            <div class="ag-box-content ju-switch-button">
                <label class="switch">
                    <input type="checkbox" name="your-setting-name" id="ag_style_footer" value="1" checked />
                    <span class="slider" data-show="footer_template"></span>
                </label>
            </div>
        </div>

    </div>

    <div class="custom_body ju-settings-option full-width" id="footer_template">
        <label class="tooltip ju-setting-label" data-toggle="tooltip" data-placement="top" title="
            <?php
            esc_attr_e(
                'Select the background image for the footer according to the pattern',
                'advanced-gutenberg-theme'
            );
            ?>">
            <?php esc_attr_e('Background image for footer', 'advanced-gutenberg-theme'); ?>
            :</label>

        <div class="ag-box-content">
            <select id="ag_template" class="ju-select">
                <option value> <?php esc_attr_e('— Select Template —', 'advanced-gutenberg-theme');?> </option>
            </select>
            <div id="background_footer" style="">
                <div></div>
                <div>
                    <input id="ag_remove_footer_img" type="button" value="Remove Image"/>
                    <input id="ag_default_footer_img" class="ju-button" type="button" value="Default Image"/>
                    <input id="ag_template_footer_img" class="ju-button" type="button" value="Upload Image"/>
                </div>
            </div>
        </div>
    </div>
</div>
