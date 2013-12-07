<?php
/**
 * The template for displaying search forms in clearcontent
 *
 * @package clearcontent
 */
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
    <label>
        <span class="screen-reader-text"><?php _ex('Search for:', 'label', 'clearcontent'); ?></span>
        <input type="search" class="search-field form-control" placeholder="<?php echo esc_attr_x('Search &hellip;', 'placeholder', 'clearcontent'); ?>" value="<?php echo esc_attr(get_search_query()); ?>" name="s" title="<?php _ex('Search for:', 'label', 'clearcontent'); ?>">
    </label>
</form>
