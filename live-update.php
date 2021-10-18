<?php
defined('ABSPATH') || die;

global $UpdateChecker;

wp_register_script(
    'check_token',
    AG_THEME_URI . '/juupdater/js/check_token.js',
    array(),
    '1.0.0'
);
wp_enqueue_script('check_token');
?>

<div class="ag-epanel-box">
    <h3><?php esc_attr_e('Joomunited live updates', 'advanced-gutenberg-theme'); ?></h3>
</div>
<div class="ag-epanel-box ag-box-2">
    <div class="ag-box-title">
        <h4><?php esc_attr_e('Live updates status', 'advanced-gutenberg-theme'); ?></h4>
    </div>
    <div class="ag-box-content">
        <?php $UpdateChecker->joomConnect(); ?>
    </div>
</div>
<?php
$params = $UpdateChecker->localizeScript();
wp_localize_script('check_token', 'updaterparams', $params);
