<?php
/**
 * The template for displaying Archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package clearcontent
 */

get_header(); ?>

<div id="content" class="container" role="main">
	<div class="row">
		<div id="primary" class="col-xs-12 col-sm-8">
			<?php if ( have_posts() ) : ?>

				<header class="page-header">
					<h1 class="page-title">
						<?php
							if ( is_category() ) :
								single_cat_title( 'Category: ' );

							elseif ( is_tag() ) :
								single_tag_title( 'Tag: ' );

							elseif ( is_author() ) :
								/* Queue the first post, that way we know
								 * what author we're dealing with (if that is the case).
								*/
								the_post();
								printf( __( 'Author: %s', 'clearcontent' ), '<span class="vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '" title="' . esc_attr( get_the_author() ) . '" rel="me">' . get_the_author() . '</a></span>' );
								/* Since we called the_post() above, we need to
								 * rewind the loop back to the beginning that way
								 * we can run the loop properly, in full.
								 */
								rewind_posts();

							elseif ( is_day() ) :
								printf( __( 'Day: %s', 'clearcontent' ), '<span>' . get_the_date() . '</span>' );

							elseif ( is_month() ) :
								printf( __( 'Month: %s', 'clearcontent' ), '<span>' . get_the_date( 'F Y' ) . '</span>' );

							elseif ( is_year() ) :
								printf( __( 'Year: %s', 'clearcontent' ), '<span>' . get_the_date( 'Y' ) . '</span>' );

							elseif ( is_tax( 'post_format', 'post-format-aside' ) ) :
								_e( 'Asides', 'clearcontent' );

							elseif ( is_tax( 'post_format', 'post-format-image' ) ) :
								_e( 'Images', 'clearcontent');

							elseif ( is_tax( 'post_format', 'post-format-video' ) ) :
								_e( 'Videos', 'clearcontent' );

							elseif ( is_tax( 'post_format', 'post-format-quote' ) ) :
								_e( 'Quotes', 'clearcontent' );

							elseif ( is_tax( 'post_format', 'post-format-link' ) ) :
								_e( 'Links', 'clearcontent' );

							else :
								_e( 'Archives', 'clearcontent' );

							endif;
						?>
					</h1>
					<?php
						// Show an optional term description.
						$term_description = term_description();
						if ( ! empty( $term_description ) ) :
							printf( '<div class="taxonomy-description">%s</div>', $term_description );
						endif;
					?>
				</header><!-- .page-header -->

				<?php /* Start the Loop */ ?>
				<?php while ( have_posts() ) : the_post(); ?>

					<?php
						/* Include the Post-Format-specific template for the content.
						 * If you want to overload this in a child theme then include a file
						 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
						 */
						get_template_part( 'content', get_post_format() );
					?>

				<?php endwhile; ?>

				<?php clearcontent_content_nav( 'nav-below' ); ?>

			<?php else : ?>

				<?php get_template_part( 'no-results', 'archive' ); ?>

			<?php endif; ?>
		</div><!-- col-xs-12 col-sm-8 -->
		
		<div class="col-sm-4">
			<?php get_sidebar(); ?>
		</div>
	</div> <!-- #row -->
</div><!-- #content -->

<?php get_footer(); ?>
