<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package clearcontent
 */
get_header();
?>

<div id="content" class="container" role="main">
    <div class="row">
        <div id="primary" class="col-xs-12 col-sm-8">

            <?php if (have_posts()) : ?>

                <header class="page-header">
                    <h1 class="page-title"><?php printf(__('Search Results for: %s', 'clearcontent'), '<span>' . get_search_query() . '</span>'); ?></h1>
                </header><!-- .page-header -->

                <?php /* Start the Loop */ ?>
                <?php while (have_posts()) : the_post(); ?>

                    <?php get_template_part('content', 'search'); ?>

                <?php endwhile; ?>

                <?php clearcontent_content_nav('nav-below'); ?>

            <?php else : ?>

                <?php get_template_part('no-results', 'search'); ?>

            <?php endif; ?>

        </div><!-- col-xs-12 col-sm-8 -->

        <div class="col-sm-4">
            <?php get_sidebar(); ?>
        </div>
    </div> <!-- #row -->
</div><!-- #content -->

<?php get_footer(); ?>
