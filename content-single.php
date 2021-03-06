<?php
/**
 * @package clearcontent
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('row'); ?>>
    <div class="col-md-12">
        <header class="entry-header">
            <h1 class="entry-title"><?php the_title(); ?></h1>

            <div class="entry-meta">
                <?php clearcontent_post_meta(array('byline', 'post_date', 'modified_date')); ?>
            </div><!-- .entry-meta -->
        </header><!-- .entry-header -->

        <div class="entry-content">
            <?php the_content(); ?>
            <?php
            wp_link_pages(array(
                'before' => '<div class="page-links">' . __('Pages:', 'clearcontent'),
                'after' => '</div>',
            ));
            ?>
        </div><!-- .entry-content -->

        <footer class="entry-meta">
            <?php
            /* translators: used between list items, there is a space after the comma */
            $category_list = get_the_category_list(__(', ', 'clearcontent'));

            /* translators: used between list items, there is a space after the comma */
            $tag_list = get_the_tag_list('', __(', ', 'clearcontent'));

            if (!clearcontent_categorized_blog()) {
                // This blog only has 1 category so we just need to worry about tags in the meta text
                if ('' != $tag_list) {
                    $meta_text = __('This entry was tagged %2$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'clearcontent');
                } else {
                    $meta_text = __('Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'clearcontent');
                }
            } else {
                // But this blog has loads of categories so we should probably display them here
                if ('' != $tag_list) {
                    $meta_text = __('This entry was posted in %1$s and tagged %2$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'clearcontent');
                } else {
                    $meta_text = __('This entry was posted in %1$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'clearcontent');
                }
            } // end check for categories on this blog

            printf(
                    $meta_text, $category_list, $tag_list, get_permalink(), the_title_attribute('echo=0')
            );
            ?>

            <?php edit_post_link(__('Edit', 'clearcontent'), '<span class="edit-link">', '</span>'); ?>
        </footer><!-- .entry-meta -->
    </div>
</article><!-- #post-## -->
