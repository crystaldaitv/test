<?php
defined('ABSPATH') || die;

$customs        = ag_get_option(AG_THEME_NAME_OPTION, AG_THEME_FOLDER, 'full', '', array());
$footer_credits = isset($customs['custom_footer_credits']) ? $customs['custom_footer_credits'] : '';
if (isset($customs['customBody'])) {
    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- $customCss['customBody'] no need for EscapeOutput
    echo(stripslashes($customs['customBody']));
}

?>
<footer id="page-footer">
    <?php if (has_nav_menu('footer-menu')) : ?>
        <div id="footer-nav">
            <div class="wrapper_content">
                <?php wp_nav_menu(array(
                    'theme_location' => 'footer-menu',
                    'container'      => 'nav',
                    'menu_class'     => 'ag-menu ag-footer-menu',
                )); ?>
            </div>
        </div>
    <?php endif; ?>
    <div id="footer-bottom">
        <div class="wrapper_content">
            <?php
            theme_widgets('footer');
            ?>
            <?php if ($footer_credits !== '') : ?>
                <div id="page-copyright">
                    <?php
                    //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- $footer_credits no need for EscapeOutput
                    echo $footer_credits;
                    ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</footer>
</div> <!--end #page-container -->
<?php wp_footer(); ?>
<script type="text/javascript" id="ag_theme_scroll">
</script>
</body> <!--end body-->
</html> <!--end html -->
