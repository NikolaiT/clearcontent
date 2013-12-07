<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package clearcontent
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo('charset'); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php wp_title('|', true, 'right'); ?></title>
        <link rel="profile" href="http://gmpg.org/xfn/11">
        <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">

        <?php wp_head(); ?>
    </head>

    <body <?php body_class(); ?>>
        <div id="page" class="hfeed site">
            <?php do_action('before'); ?>
            <header role="banner">
                <nav class="navbar navbar-default navbar-inverse navbar-static-top" role="navigation">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-9 col-xs-8 ">
                                <div class="navbar-header">
                                    <h1><a id="heading" class="navbar-brand" href="<?php echo esc_url(home_url('/')); ?>" title="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>" rel="home" class="navbar-brand"><?php bloginfo('name'); ?></a></h1>
                                </div>
                            </div>

                            <div class="col-xs-4 col-md-3"><?php clearcontent_social_media_icons(); ?></div>
                        </div><!-- row -->

                        <div class="row"><div class="col-md-12"><h2 class="site-description"><?php bloginfo('description'); ?></h2></div></div>

                        <div class="row">
                            <div class="col-sm-8 col-md-6 col-sm-offset-4 col-md-offset-6">
                                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                </button>
                                <?php
                                /*
                                 * Make sure walker class exists. 
                                 * Lookup: wp.tutsplus.com "integrate bootstrap navbar"
                                 */
                                wp_nav_menu(array(
                                    'menu' => 'header-menu',
                                    'theme_location' => 'header-menu',
                                    'depth' => 2,
                                    'container' => 'div',
                                    'container_class' => 'collapse navbar-collapse navbar-ex1-collapse',
                                    'menu_class' => 'nav navbar-nav',
                                    'fallback_cb' => 'wp_bootstrap_navwalker::fallback',
                                    'walker' => new wp_bootstrap_navwalker())
                                );
                                ?>
                            </div>
                        </div><!-- row -->
                    </div>
                </nav><!-- #site-navigation -->
            </header><!-- #masthead -->
            <?php /* clearcontent_header_slider(); */ ?>

            <div id="main" class="site-main">
