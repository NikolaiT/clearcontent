<?php
/**
 * @package clearcontent
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('row'); ?>>
<div class="col-md-12">
	<header class="entry-header">
		<h1 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h1>

		<?php if ( 'post' == get_post_type() ) : ?>
		<div class="entry-meta">
			<?php clearcontent_post_meta( array( 'byline' => True, 'post_date' => True, 'comments' => False ) ); ?>
		</div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->

	<?php if ( is_search() ) : // Only display Excerpts for Search ?>
	<div class="entry-summary">
		<?php the_excerpt(); ?>
	</div><!-- .entry-summary -->
	<?php else : /* In every common case (Such as viewing the start page) we want to see a summary of the posts */?>
	<div class="entry-summary">
		<?php the_excerpt( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'clearcontent' ) ); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'clearcontent' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-summary -->
	<?php endif; ?>

	<footer class="entry-meta">
		
		<?php clearcontent_post_meta( array( 'posted-in' => True, 'tags' => True ), $echosep=false); ?>
		
		<div class="clearfix">
			<?php clearcontent_post_meta( array( 'byline' => False, 'post_date' => False, 'comments' => True ), $echosep=False ); ?>
		</div>

		<?php edit_post_link( __( 'Edit', 'clearcontent' ), '<span class="edit-link">', '</span>' ); ?>
                
        <div class="clearfix"><a class="readmore" href="<?php the_permalink() ?>" rel="bookmark" title="Read <?php the_title_attribute(); ?>">Read more</a></div>
	</footer><!-- .entry-meta -->
</div>
</article><!-- #post-## -->
