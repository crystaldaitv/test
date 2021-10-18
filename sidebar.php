<?php
defined('ABSPATH') || die;

if (is_active_sidebar('primary-sidebar')) : ?>
    <aside class="widget-area">
        <?php dynamic_sidebar('primary-sidebar'); ?>
    </aside>
<?php endif; ?>
