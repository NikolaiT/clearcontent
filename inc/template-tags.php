<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package clearcontent
 */

error_reporting(E_ALL);

if (!function_exists('clearcontent_content_nav')) :

    /**
     * Display navigation to next/previous pages when applicable
     */
    function clearcontent_content_nav($nav_id) {
        global $wp_query, $post;

        // Don't print empty markup on single pages if there's nowhere to navigate.
        if (is_single()) {
            $previous = ( is_attachment() ) ? get_post($post->post_parent) : get_adjacent_post(false, '', true);
            $next = get_adjacent_post(false, '', false);

            if (!$next && !$previous)
                return;
        }

        // Don't print empty markup in archives if there's only one page.
        if ($wp_query->max_num_pages < 2 && ( is_home() || is_archive() || is_search() ))
            return;

        $nav_class = ( is_single() ) ? 'post-navigation' : 'paging-navigation';
        ?>
        <nav role="navigation" id="<?php echo esc_attr($nav_id); ?>" class="<?php echo $nav_class; ?>">
            <h1 class="screen-reader-text"><?php _e('Post navigation', 'clearcontent'); ?></h1>

            <?php if (is_single()) : // navigation links for single posts  ?>

                <?php previous_post_link('<div class="nav-previous">%link</div>', '<span class="meta-nav">' . _x('&larr;', 'Previous post link', 'clearcontent') . '</span> %title'); ?>
                <?php next_post_link('<div class="nav-next">%link</div>', '%title <span class="meta-nav">' . _x('&rarr;', 'Next post link', 'clearcontent') . '</span>'); ?>

            <?php elseif ($wp_query->max_num_pages > 1 && ( is_home() || is_archive() || is_search() )) : // navigation links for home, archive, and search pages  ?>

                <?php if (get_next_posts_link()) : ?>
                    <div class="nav-previous"><?php next_posts_link(__('<span class="meta-nav">&larr;</span> Older posts', 'clearcontent')); ?></div>
                <?php endif; ?>

                <?php if (get_previous_posts_link()) : ?>
                    <div class="nav-next"><?php previous_posts_link(__('Newer posts <span class="meta-nav">&rarr;</span>', 'clearcontent')); ?></div>
                <?php endif; ?>

            <?php endif; ?>

        </nav><!-- #<?php echo esc_html($nav_id); ?> -->
        <?php
    }

endif; // clearcontent_content_nav

if (!function_exists('clearcontent_comment')) :

    /**
     * Template for comments and pingbacks.
     *
     * Used as a callback by wp_list_comments() for displaying the comments.
     */
    function clearcontent_comment($comment, $args, $depth) {
        $GLOBALS['comment'] = $comment;

        if ('pingback' == $comment->comment_type || 'trackback' == $comment->comment_type) :
            ?>

            <li id="comment-<?php comment_ID(); ?>" <?php comment_class(); ?>>
                <div class="comment-body">
                    <?php _e('Pingback:', 'clearcontent'); ?> <?php comment_author_link(); ?> <?php edit_comment_link(__('Edit', 'clearcontent'), '<span class="edit-link">', '</span>'); ?>
                </div>

            <?php else : ?>

            <li id="comment-<?php comment_ID(); ?>" <?php comment_class(empty($args['has_children']) ? '' : 'parent' ); ?>>
                <article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
                    <footer class="comment-meta">
                        <div class="comment-author vcard">
                            <?php if (0 != $args['avatar_size']) echo get_avatar($comment, $args['avatar_size']); ?>
                            <?php printf(__('%s <span class="says">says:</span>', 'clearcontent'), sprintf('<cite class="fn">%s</cite>', get_comment_author_link())); ?>
                        </div><!-- .comment-author -->

                        <div class="comment-metadata">
                            <a href="<?php echo esc_url(get_comment_link($comment->comment_ID)); ?>">
                                <time datetime="<?php comment_time('c'); ?>">
                                    <?php printf(_x('%1$s at %2$s', '1: date, 2: time', 'clearcontent'), get_comment_date(), get_comment_time()); ?>
                                </time>
                            </a>
                            <?php edit_comment_link(__('Edit', 'clearcontent'), '<span class="edit-link">', '</span>'); ?>
                        </div><!-- .comment-metadata -->

                        <?php if ('0' == $comment->comment_approved) : ?>
                            <p class="comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.', 'clearcontent'); ?></p>
                        <?php endif; ?>
                    </footer><!-- .comment-meta -->

                    <div class="comment-content">
                        <?php comment_text(); ?>
                    </div><!-- .comment-content -->

                    <div class="reply">
                        <?php comment_reply_link(array_merge($args, array('add_below' => 'div-comment', 'depth' => $depth, 'max_depth' => $args['max_depth'])));
                        ?>

                    </div><!-- .reply -->
                </article><!-- .comment-body -->

            <?php
            endif;
        }

    endif; // ends check for clearcontent_comment()

    if (!function_exists('clearcontent_the_attached_image')) :

        /**
         * Prints the attached image with a link to the next attached image.
         */
        function clearcontent_the_attached_image() {
            $post = get_post();
            $attachment_size = apply_filters('clearcontent_attachment_size', array(1200, 1200));
            $next_attachment_url = wp_get_attachment_url();

            /**
             * Grab the IDs of all the image attachments in a gallery so we can get the
             * URL of the next adjacent image in a gallery, or the first image (if
             * we're looking at the last image in a gallery), or, in a gallery of one,
             * just the link to that image file.
             */
            $attachment_ids = get_posts(array(
                'post_parent' => $post->post_parent,
                'fields' => 'ids',
                'numberposts' => -1,
                'post_status' => 'inherit',
                'post_type' => 'attachment',
                'post_mime_type' => 'image',
                'order' => 'ASC',
                'orderby' => 'menu_order ID'
            ));

            // If there is more than 1 attachment in a gallery...
            if (count($attachment_ids) > 1) {
                foreach ($attachment_ids as $attachment_id) {
                    if ($attachment_id == $post->ID) {
                        $next_id = current($attachment_ids);
                        break;
                    }
                }

                // get the URL of the next image attachment...
                if ($next_id)
                    $next_attachment_url = get_attachment_link($next_id);

                // or get the URL of the first image attachment.
                else
                    $next_attachment_url = get_attachment_link(array_shift($attachment_ids));
            }

            printf('<a href="%1$s" title="%2$s" rel="attachment">%3$s</a>', esc_url($next_attachment_url), the_title_attribute(array('echo' => false)), wp_get_attachment_image($post->ID, $attachment_size)
            );
        }

    endif;

    if (!function_exists('clearcontent_post_meta')) :

        /**
         * Prints HTML with meta information for the current post-date/time and author, as well as a link to the comments and the tags.
         * 
         * The function is designed in such a way that each entity of the meta information may be added depending on the parameters set.
         * 
         * Example: clearcontent_post_meta($show = array('byline', 'post_date', 'modified_date', 'comments', 'posted-in', 'tags'));
         * If the function is called like above, the keywords indicate in exactly the order they are placed in the array which meta information 
         * should be printed.
         * 
         * @param array $show Elements are keys which indicate which entity is echoed in exactly the order as supplied.
         *  to the argument.
         * @param boolean $echosep If $echosep if true, echo a separator.
         * @return None This function does not have a return value.
         *
         */
        function clearcontent_post_meta($show = array('byline', 'post_date', 'modified_date', 'comments', 'posted-in', 'tags'), $echosep = False) {
            $meta_data = array();
        
            if (in_array('byline', $show)) {
                $meta_data[] = sprintf('<span class="byline">By <span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s">%3$s</a></span></span>',
                    esc_url(get_author_posts_url(get_the_author_meta('ID'))),
                    esc_attr(sprintf(__('View all posts by %s', 'clearcontent'),
                    get_the_author())), esc_html(get_the_author())
                );
            }

            if (in_array('post_date', $show)) {
                $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';
                $time_string = sprintf($time_string, esc_attr(get_the_date('c')), esc_html(get_the_date()));

                list($year, $month, $day) = explode(' ', get_the_date('Y m d'));
                $meta_data[] = sprintf(
                    '<span class="posted-on">Posted on <a href="%1$s" title="%2$s" rel="bookmark">%3$s</a></span>',
                    esc_url(get_day_link($year, $month, $day)),
                    esc_attr(get_the_time()),
                    $time_string
                );
            }

            if (in_array('modified_date', $show)) {
                if (get_the_time('U') !== get_the_modified_time('U') && get_the_modified_time()) {
                    $updated = '<time class="updated" datetime="%1$s">%2$s</time>';
                    $updated = sprintf($updated, esc_attr(get_the_modified_date('c')), esc_html(get_the_modified_date())
                    );
                    $meta_data[] = sprintf(
                        '<span class="modified-on">Modified on <a href="%1$s" title="%2$s" rel="bookmark">%3$s</a></span>',
                        esc_url(get_permalink()), esc_attr(get_the_time()),
                        $updated
                    );
                }
            }

            if ((in_array('comments', $show)) && (!post_password_required() && comments_open() )) {
                $meta_data[] = sprintf('<span class="comments-link">%1$s</span>',
                    comments_popup_link(__('Leave a comment ', 'clearcontent'), __('1 Comment ', 'clearcontent'), __('% Comments ', 'clearcontent'))
                );
            }

            /* The next two only when this is a real post (Not search page or other) */
            if ('post' == get_post_type()) {

                if (in_array('posted', $show)) {
                    /* translators: used between list items, there is a space after the comma */
                    $categories_list = get_the_category_list(__(', ', 'clearcontent'));
                    if ($categories_list && clearcontent_categorized_blog()) {
                        $meta_data[] = sprintf('<span class="cat-links">%1$s</span>',"Posted in $categories_list");
                    }
                }

                if (in_array('tags', $show)) {
                    /* translators: used between list items, there is a space after the comma */
                    $tags_list = get_the_tag_list('', __(', ', 'clearcontent'));
                    $meta_data[] = sprintf('<span class="tags-links">%1$s</span>',
                            $tags_list ? sprintf(__('Tagged %1$s', 'clearcontent'), $tags_list) : sprintf(__('No tags yet :(', 'clearcontent'), $tags_list));
                }

            }
            
            printf(implode($meta_data, '<span class="separator">-</span>'));
        }

    endif;

    /**
     * Returns true if a blog has more than 1 category
     */
    function clearcontent_categorized_blog() {
        if (false === ( $all_the_cool_cats = get_transient('all_the_cool_cats') )) {
            // Create an array of all the categories that are attached to posts
            $all_the_cool_cats = get_categories(array(
                'hide_empty' => 1,
            ));

            // Count the number of categories that are attached to the posts
            $all_the_cool_cats = count($all_the_cool_cats);

            set_transient('all_the_cool_cats', $all_the_cool_cats);
        }

        if ('1' != $all_the_cool_cats) {
            // This blog has more than 1 category so clearcontent_categorized_blog should return true
            return true;
        } else {
            // This blog has only 1 category so clearcontent_categorized_blog should return false
            return false;
        }
    }

    if (!function_exists(' clearcontent_social_media_icons ')) :
        /* Echo social media icons into the header of the theme 
         * 
         */

        function clearcontent_social_media_icons() {
            $icon_path = get_template_directory_uri() . '/pics/64_64/';

            /*
             * Every key represents a slug which determines the kind of social plugin.
             * Then the value is an array with the following arguments:
             * [1] icon image when not hoving over
             * [2] icon image when hoving over
             * [3] url to the social media profile.
             * It must have an length of 3.
             */
            $icon_data = array(
                'Github' => array($icon_path . 'github.png', $icon_path . 'github_x.png', 'https://github.com/NikolaiT'),
                'Twitter' => array($icon_path . 'twitter.png', $icon_path . 'twitter_x.png', 'https://twitter.com/incolumitas_'),
                'Rss' => array($icon_path . 'rss.png', $icon_path . 'rss_x.png', get_bloginfo('url')),
                'Email' => array($icon_path . 'email.png', $icon_path . 'email_x.png', get_bloginfo('url'))
            );
            /* Clean recursively */
            $cleaner = function ($item) {
                return clearcontent_esc_deep($item);
            };
            $icon_data = array_map($cleaner, $icon_data);
            ?>
            <div class="header-icons">
                <?php foreach ($icon_data as $key => $value) : ?>
                    <a title="Follow Nikolai Tschacher on <?php esc_html_e($key); ?>" href="<?php echo $value[2]; ?>" target="_blank">
                        <img src="<?php echo $value[0]; ?>" onmouseover="this.src = '<?php echo $value[1]; ?>'" onmouseout="this.src = '<?php echo $value[0]; ?>'"width="36px" alt="Follow me on <?php echo $key; ?>"></a>
            <?php endforeach; ?>
            </div>

            <?php
        }

    endif;


    if (!function_exists(' clearcontent_comment_template ')) :
        /*
         * Echo a custom comment template aligned and styled with bootstrap 3.02
         * The big problem here is, that there is no way in wordpress 3.7.1 to supply
         * specific parameters to certain class attributes within the comment template.
         * There are just no filters for it. (Such as for the form element class attribute).
         * 
         * But in order to stlye forms with bootstrap 3.02 I do need this access.
         * 
         * Approach: Just copy comment_form() function from
         * http://core.trac.wordpress.org/browser/tags/3.7.1/src/wp-includes/comment-template.php#L1509
         * and modify it to our liking. Thats definitely not nice, but how else?
         * 
         */

        /**
         * Output a complete commenting form for use within a template.
         *
         * Most strings and form fields may be controlled through the $args array passed
         * into the function, while you may also choose to use the comment_form_default_fields
         * filter to modify the array of default fields if you'd just like to add a new
         * one or remove a single field. All fields are also individually passed through
         * a filter of the form comment_form_field_$name where $name is the key used
         * in the array of fields.
         *
         * @since 3.0.0
         *
         * @param array       $args {
         *     Optional. Default arguments and form fields to override.
         *
         *     @type array 'fields' {
         *         Default comment fields, filterable by default via the 'comment_form_default_fields' hook.
         *
         *         @type string 'author' The comment author field HTML.
         *         @type string 'email'  The comment author email field HTML.
         *         @type string 'url'    The comment author URL field HTML.
         *     }
         *     @type string 'comment_field'        The comment textarea field HTML.
         *     @type string 'must_log_in'          HTML element for a 'must be logged in to comment' message.
         *     @type string 'logged_in_as'         HTML element for a 'logged in as <user>' message.
         *     @type string 'comment_notes_before' HTML element for a message displayed before the comment form.
         *                                         Default 'Your email address will not be published.'.
         *     @type string 'comment_notes_after'  HTML element for a message displayed after the comment form.
         *                                         Default 'You may use these HTML tags and attributes ...'.
         *     @type string 'id_form'              The comment form element id attribute. Default 'commentform'.
         *     @type string 'id_submit'            The comment submit element id attribute. Default 'submit'.
         *     @type string 'title_reply'          The translatable 'reply' button label. Default 'Leave a Reply'.
         *     @type string 'title_reply_to'       The translatable 'reply-to' button label. Default 'Leave a Reply to %s',
         *                                         where %s is the author of the comment being replied to.
         *     @type string 'cancel_reply_link'    The translatable 'cancel reply' button label. Default 'Cancel reply'.
         *     @type string 'label_submit'         The translatable 'submit' button label. Default 'Post a comment'.
         *     @type string 'format'               The comment form format. Default 'xhtml'. Accepts 'xhtml', 'html5'.
         * }
         * @param int|WP_Post $post_id Optional. Post ID or WP_Post object to generate the form for. Default current post.
         */
        function clearcontent_comment_form($args = array(), $post_id = null) {
            if (null === $post_id)
                $post_id = get_the_ID();
            else
                $id = $post_id;

            $commenter = wp_get_current_commenter();
            $user = wp_get_current_user();
            $user_identity = $user->exists() ? $user->display_name : '';

            $args = wp_parse_args($args);
            if (!isset($args['format']))
                $args['format'] = current_theme_supports('html5', 'comment-form') ? 'html5' : 'xhtml';

            $req = get_option('require_name_email');
            $aria_req = ( $req ? " aria-required='true'" : '' );
            $html5 = 'html5' === $args['format'];
            $fields = array(
                'author' => '<div class="form-group"><label for="author" class="col-sm-2 control-label comment-form-author">' . __('Name') . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> ' .
                '<div class="col-sm-10"><input id="author" class="form-control" name="author" type="text" value="' . esc_attr($commenter['comment_author']) . '" size="30"' . $aria_req . ' /></div></div>',
                'email' => '<div class="form-group"><label for="email" class="col-sm-2 control-label comment-form-email">' . __('Email') . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> ' .
                '<div class="col-sm-10"><input id="email" class="form-control" name="email" ' . ( $html5 ? 'type="email"' : 'type="text"' ) . ' value="' . esc_attr($commenter['comment_author_email']) . '" size="30"' . $aria_req . ' /></div></div>',
                'url' => '<div class="form-group"><label for="url" class="col-sm-2 control-label comment-form-url">' . __('Website') . '</label> ' .
                '<div class="col-sm-10"><input id="url" class="form-control" name="url" ' . ( $html5 ? 'type="url"' : 'type="text"' ) . ' value="' . esc_attr($commenter['comment_author_url']) . '" size="30" /></div></div>',
            );

            $required_text = sprintf(' ' . __('Required fields are marked %s'), '<span class="required">*</span>');

            /**
             * Filter the default comment form fields.
             *
             * @since 3.0.0
             *
             * @param array $fields The default comment fields.
             */
            $fields = apply_filters('comment_form_default_fields', $fields);
            $defaults = array(
                'fields' => $fields,
                'comment_field' => '<div class="form-group"><label for="comment" class="col-sm-2 control-label">' . _x('Comment', 'noun') . '</label><div class="col-sm-10"><textarea id="comment" class="form-control" name="comment" cols="45" rows="7" aria-required="true"></textarea></div></div>',
                'must_log_in' => '<p class="must-log-in">' . sprintf(__('You must be <a href="%s">logged in</a> to post a comment.'), wp_login_url(apply_filters('the_permalink', get_permalink($post_id)))) . '</p>',
                'logged_in_as' => '<p class="logged-in-as">' . sprintf(__('Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>'), get_edit_user_link(), $user_identity, wp_logout_url(apply_filters('the_permalink', get_permalink($post_id)))) . '</p>',
                'comment_notes_before' => '<p class="comment-notes">' . __('Your email address will not be published.') . ( $req ? $required_text : '' ) . '</p>',
                'comment_notes_after' => '<p class="form-allowed-tags">' . sprintf(__('You may use these <abbr title="HyperText Markup Language">HTML</abbr> tags and attributes: %s'), ' <pre><code>' . allowed_tags() . '</code></pre>') . '</p>',
                'id_form' => 'commentform',
                'id_submit' => 'submit',
                'title_reply' => __('Leave a Reply'),
                'title_reply_to' => __('Leave a Reply to %s'),
                'cancel_reply_link' => __('Cancel reply'),
                'label_submit' => __('Post Comment'),
                'format' => 'xhtml',
            );

            /**
             * Filter the comment form default arguments.
             *
             * Use 'comment_form_default_fields' to filter the comment fields.
             *
             * @since 3.0.0
             *
             * @param array $defaults The default comment form arguments.
             */
            $args = wp_parse_args($args, apply_filters('comment_form_defaults', $defaults));
            ?>
            <?php if (comments_open($post_id)) : ?>
                <?php
                /**
                 * Fires before the comment form.
                 *
                 * @since 3.0.0
                 */
                do_action('comment_form_before');
                ?>
                <div id="respond" class="comment-respond">
                    <h3 id="reply-title" class="comment-reply-title"><?php comment_form_title($args['title_reply'], $args['title_reply_to']); ?> <small><?php cancel_comment_reply_link($args['cancel_reply_link']); ?></small></h3>
                    <?php if (get_option('comment_registration') && !is_user_logged_in()) : ?>
                        <?php echo $args['must_log_in']; ?>
                        <?php
                        /**
                         * Fires after the HTML-formatted 'must log in after' message in the comment form.
                         *
                         * @since 3.0.0
                         */
                        do_action('comment_form_must_log_in_after');
                        ?>
                        <?php else : ?>
                        <form action="<?php echo site_url('/wp-comments-post.php'); ?>" method="post" id="<?php echo esc_attr($args['id_form']); ?>" class="comment-form form-horizontal" role="form" <?php echo $html5 ? ' novalidate' : ''; ?>>
                            <?php
                            /**
                             * Fires at the top of the comment form, inside the <form> tag.
                             *
                             * @since 3.0.0
                             */
                            do_action('comment_form_top');
                            ?>
                            <?php if (is_user_logged_in()) : ?>
                                <?php
                                /**
                                 * Filter the 'logged in' message for the comment form for display.
                                 *
                                 * @since 3.0.0
                                 *
                                 * @param string $args['logged_in_as'] The logged-in-as HTML-formatted message.
                                 * @param array  $commenter            An array containing the comment author's username, email, and URL.
                                 * @param string $user_identity        If the commenter is a registered user, the display name, blank otherwise.
                                 */
                                echo apply_filters('comment_form_logged_in', $args['logged_in_as'], $commenter, $user_identity);
                                ?>
                                <?php
                                /**
                                 * Fires after the is_user_logged_in() check in the comment form.
                                 *
                                 * @since 3.0.0
                                 *
                                 * @param array  $commenter     An array containing the comment author's username, email, and URL.
                                 * @param string $user_identity If the commenter is a registered user, the display name, blank otherwise.
                                 */
                                do_action('comment_form_logged_in_after', $commenter, $user_identity);
                                ?>
                            <?php else : ?>
                                <?php echo $args['comment_notes_before']; ?>
                                <?php
                                /**
                                 * Fires before the comment fields in the comment form.
                                 *
                                 * @since 3.0.0
                                 */
                                do_action('comment_form_before_fields');
                                foreach ((array) $args['fields'] as $name => $field) {
                                    /**
                                     * Filter a comment form field for display.
                                     *
                                     * The dynamic portion of the filter hook, $name, refers to the name
                                     * of the comment form field. Such as 'author', 'email', or 'url'.
                                     *
                                     * @since 3.0.0
                                     *
                                     * @param string $field The HTML-formatted output of the comment form field.
                                     */
                                    echo apply_filters("comment_form_field_{$name}", $field) . "\n";
                                }
                                /**
                                 * Fires after the comment fields in the comment form.
                                 *
                                 * @since 3.0.0
                                 */
                                do_action('comment_form_after_fields');
                                ?>
                            <?php endif; ?>
                            <?php
                            /**
                             * Filter the content of the comment textarea field for display.
                             *
                             * @since 3.0.0
                             *
                             * @param string $args['comment_field'] The content of the comment textarea field.
                             */
                            echo apply_filters('comment_form_field_comment', $args['comment_field']);
                            ?>
                <?php echo $args['comment_notes_after']; ?>
                            <div class="form-group">
                                <div class="col-sm-10">
                                    <p class="form-submit">
                                        <input name="submit" type="submit" class="btn btn-primary" id="<?php echo esc_attr($args['id_submit']); ?>" value="<?php echo esc_attr($args['label_submit']); ?>" />
                <?php comment_id_fields($post_id); ?>
                                    </p>
                                </div>
                            </div>
                            <?php
                            /**
                             * Fires at the bottom of the comment form, inside the closing </form> tag.
                             *
                             * @since 1.5.2
                             *
                             * @param int $post_id The post ID.
                             */
                            do_action('comment_form', $post_id);
                            ?>
                        </form>
                <?php endif; ?>
                </div><!-- #respond -->
                <?php
                /**
                 * Fires after the comment form.
                 *
                 * @since 3.0.0
                 */
                do_action('comment_form_after');
            else :
                /**
                 * Fires after the comment form if comments are closed.
                 *
                 * @since 3.0.0
                 */
                do_action('comment_form_comments_closed');
            endif;
        }

    endif;

    if (!function_exists('clearcontent_header_slider')):
        /*
         * This function includes a minimal jquery slideshow into the header of the site. It uses unslider.js in 
         * order to achieve this objective. Link to github site: https://github.com/idiot/unslider
         */

        function clearcontent_header_slider() {
            ?>

            <div class="header-slideshow">
                <ul>
                    <li style="background-image: url('<?php echo get_template_directory_uri() . '/pics/slideshow/1.png' ?>');"></li>
                    <li style="background-image: url('<?php echo get_template_directory_uri() . '/pics/slideshow/2.png' ?>');"></li>
                    <li style="background-image: url('<?php echo get_template_directory_uri() . '/pics/slideshow/3.png' ?>');"></li>
                </ul>
            </div>
            <script type="text/javascript">
                var $j = jQuery.noConflict();

                // Use jQuery via $j(...) instead of $(..) to prevent name clashes.
                $j(document).ready(function() {
                    $j('.header-slideshow').unslider({
                        arrows: true,
                        fluid: true,
                        dots: true
                    });
                });
            </script>
            <?php
        }

    endif;

    /**
     * Flush out the transients used in clearcontent_categorized_blog
     */
    function clearcontent_category_transient_flusher() {
        // Like, beat it. Dig?
        delete_transient('all_the_cool_cats');
    }

    add_action('edit_category', 'clearcontent_category_transient_flusher');
    add_action('save_post', 'clearcontent_category_transient_flusher');
    