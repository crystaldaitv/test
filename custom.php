<?php
defined('ABSPATH') || die;
?>
<div id="custom_code" class="tab-pane">
    <div class="custom_css ag-box-1 ju-settings-option full-width">
        <label class="tooltip ju-setting-label" data-toggle="tooltip" data-placement="top" title="
        <?php
        esc_attr_e(
            'Load some custom CSS code all over the website',
            'advanced-gutenberg-theme'
        );?>">
            <?php esc_attr_e('Custom CSS', 'advanced-gutenberg-theme'); ?>:</label>

        <div class="ag-box-content">
            <textarea id="custom_css" name="codesnippet_readonly">
            </textarea>
        </div>
        <span class="ag-box-description"></span>
    </div>

    <div class="custom_head ag-box-1 ju-settings-option full-width">
        <label class="tooltip ju-setting-label" data-toggle="tooltip" data-placement="top" title="
        <?php
        esc_attr_e(
            'Load some code in the head section of your pages',
            'advanced-gutenberg-theme'
        );?>">
            <?php esc_html_e('< head > custom code', 'advanced-gutenberg-theme'); ?>:</label>

        <div class="ag-box-content">
            <textarea id="custom_head" name="codesnippet_readonly">
            </textarea>
        </div>
        <span class="ag-box-description"></span>
    </div>

    <div class="custom_body ag-box-1 ju-settings-option full-width">
        <label class="tooltip ju-setting-label" data-toggle="tooltip" data-placement="top" title="
        <?php
        esc_attr_e(
            'Load some code in the body section of your pages like Analytics tracking code',
            'advanced-gutenberg-theme'
        );?>">
            <?php esc_html_e('< body > custom code', 'advanced-gutenberg-theme'); ?>
            :</label>

        <div class="ag-box-content">
            <textarea id="custom_body" name="codesnippet_readonly">
            </textarea>
        </div>
        <span class="ag-box-description"></span>
    </div>
</div>

