<?php
/**
 * The Template for displaying all single posts.
 *
 * @package clearcontent
 */

get_header(); ?>

<div id="content" class="container" role="main">
	<div class="row">
		<div id="primary" class="col-xs-12 col-sm-9">

			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'content', 'single' ); ?>

				<?php clearcontent_content_nav( 'nav-below' ); ?>

				<?php
					// If comments are open or we have at least one comment, load up the comment template
					if ( comments_open() || '0' != get_comments_number() )
						comments_template();
				?>

			<?php endwhile; // end of the loop. ?>
		</div><!-- col-xs-12 col-sm-9 -->
				
		<div class="col-sm-3">
			<?php get_sidebar(); ?>
		</div>
	</div> <!-- #row -->
</div><!-- #content -->
	
<?php get_footer(); ?>
