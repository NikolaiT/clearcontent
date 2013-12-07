<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after.
 * There are three sidebars in the footer of this theme. If no sidebars are
 * specified in the admin area, then 3 static sidebar widgets are displayed.
 *
 * @package clearcontent
 */
?>

</div><!-- #main -->

<footer id="colophon" class="site-footer" role="contentinfo">
    <div class="container">

        <div class="row">
            <div id="footer-sidebar1" class="footer-widget col-md-4 col-xs-12">
                <?php
                if (is_active_sidebar('footer-sidebar-1')) {
                    dynamic_sidebar('footer-sidebar-1');
                } else {
                    /* Display meta widget */
                    the_widget('WP_Widget_Meta', 'title=Meta Actions');
                }
                ?>
            </div>
            <div id="footer-sidebar2" class="footer-widget col-md-4 col-xs-12">
                <?php
                if (is_active_sidebar('footer-sidebar-2')) {
                    dynamic_sidebar('footer-sidebar-2');
                } else {
                    the_widget('WP_Widget_Categories', 'count=1');
                }
                ?>
            </div>
            <div id="footer-sidebar3" class="footer-widget col-md-4 col-xs-12">
                <?php
                if (is_active_sidebar('footer-sidebar-3')) {
                    dynamic_sidebar('footer-sidebar-3');
                } else {
                    the_widget('WP_Widget_Calendar');
                }
                ?>
            </div>
        </div>
        <div class="site-info row">
            <div class="col-md-8 col-md-offset-2">
                <?php do_action('clearcontent_credits'); ?>
                <a href="http://wordpress.org/" title="<?php esc_attr_e('A Semantic Personal Publishing Platform', 'clearcontent'); ?>" rel="generator"><?php printf(__('Proudly powered by %s', 'clearcontent'), 'WordPress'); ?></a>
                <span class="sep"> | </span>
                <?php printf(__('Theme: %1$s by %2$s.', 'clearcontent'), 'clearcontent', '<a href="http://incolumitas.com/" rel="designer">incolumitas.com</a>'); ?>
            </div>
        </div><!-- .site-info -->
    </div><!-- .footer-wrapper -->
</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
