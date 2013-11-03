<?php
/**
 * clearcontent functions and definitions
 *
 * @package clearcontent
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) )
	$content_width = 640; /* pixels */

if ( ! function_exists( 'clearcontent_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 */
function clearcontent_setup() {

	/**
	 * Make theme available for translation
	 * Translations can be filed in the /languages/ directory
	 * If you're building a theme based on clearcontent, use a find and replace
	 * to change 'clearcontent' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'clearcontent', get_template_directory() . '/languages' );

	/**
	 * Add default posts and comments RSS feed links to head
	 */
	add_theme_support( 'automatic-feed-links' );

	/**
	 * Enable support for Post Thumbnails on posts and pages
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	//add_theme_support( 'post-thumbnails' );

	/**
	 * This theme uses wp_nav_menu() in one location.
	 */
	register_nav_menus( array(
		'header-menu' => __( 'Header Menu', 'clearcontent' ),
	) );

	/**
	 * Enable support for Post Formats
	 */
	add_theme_support( 'post-formats', array( 'aside', 'image', 'video', 'quote', 'link' ) );

	/**
	 * Setup the WordPress core custom background feature.
	 */
	add_theme_support( 'custom-background', apply_filters( 'clearcontent_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
}
endif; // clearcontent_setup
add_action( 'after_setup_theme', 'clearcontent_setup' );

/**
 * Register widgetized area and update sidebar with default widgets
 */
function clearcontent_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'clearcontent' ),
		'id'            => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
        
        register_sidebar( array (
                'name'          => __( 'Footer Sidebar 1', 'clearcontent' ),
                'id'            => 'footer-sidebar-1',
                'description'   => 'Appears in the footer area on the left side',
                'before_widget' => '<aside id="%1$s" class="widget %2$s">',
                'after_widget'  => '</aside>',
                'before_title'  => '<h1 class="widget-title">',
                'after_title'   => '</h1>',
        ) );
        
        register_sidebar( array (
                'name'          => __( 'Footer Sidebar 2', 'clearcontent' ),
                'id'            => 'footer-sidebar-2',
                'description'   => 'Appears in the middle of the footer area',
                'before_widget' => '<aside id="%1$s" class="widget %2$s">',
                'after_widget'  => '</aside>',
                'before_title'  => '<h1 class="widget-title">',
                'after_title'   => '</h1>',
        ) );
        
        register_sidebar( array (
                'name'          => __( 'Footer Sidebar 3', 'clearcontent' ),
                'id'            => 'footer-sidebar-3',
                'description'   => 'Appears in the footer area on the right side',
                'before_widget' => '<aside id="%1$s" class="widget %2$s">',
                'after_widget'  => '</aside>',
                'before_title'  => '<h1 class="widget-title">',
                'after_title'   => '</h1>',
        ) );
}
add_action( 'widgets_init', 'clearcontent_widgets_init' );

/**
 * Enqueue scripts and styles
 */
function clearcontent_scripts() {	
	wp_enqueue_style( 'clearcontent-style', get_stylesheet_uri() );

	wp_enqueue_script( 'clearcontent-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20120206', true );
        
	/* For the slideshow that is implemented and printed into the site with clearcontent_header_slider() */
	wp_enqueue_script( 'unslider', get_template_directory_uri() . '/js/unslider.js', array( 'jquery' ) );

	wp_enqueue_script( 'clearcontent-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( is_singular() && wp_attachment_is_image() ) {
		wp_enqueue_script( 'clearcontent-keyboard-image-navigation', get_template_directory_uri() . '/js/keyboard-image-navigation.js', array( 'jquery' ), '20120202' );
	}
	
	/* Get bootstrap js */
	wp_enqueue_script('clearcontent-bootstrap', get_template_directory_uri(). '/bootstrap/js/bootstrap.min.js', array('jquery'));
}
/*
 * Load some fonts for the theme.
 */
function clearcontent_load_fonts() {
    wp_register_style( 'quicksand', 'http://fonts.googleapis.com/css?family=Quicksand' );
    wp_register_style( 'open-sans', 'http://fonts.googleapis.com/css?family=Open+Sans' );
    wp_enqueue_style( 'quicksand' );
    wp_enqueue_style( 'open-sans' );
}
add_action( 'wp_enqueue_scripts', 'clearcontent_scripts' );
add_action( 'wp_enqueue_scripts', 'clearcontent_load_fonts' );


if ( ! function_exists( 'clearcontent_esc_deep' ) ) :
/*
 * Hell of a function to escape for html entities within nested arrays. Derived from 
 * stripslashes_deep()
 */
function clearcontent_esc_deep($value) {
	if ( is_array($value) ) {
		$value = array_map('clearcontent_esc_deep', $value);
	} elseif ( is_object($value) ) {
		$vars = get_object_vars( $value );
		foreach ($vars as $key=>$data) {
			$value->{$key} = clearcontent_esc_deep( $data );
		}
	} elseif ( is_string( $value ) ) {
		$value = esc_html($value);
	}
	return $value;
}
endif;

if ( !function_exists( 'clearcontent_tracking_code' ) ) :
/*
 * Incorporate your tracking code. Just paste whatever you tracking provider gives you as tracking code inside this function.
 */
function clearcontent_tracking_code() {
    ?>
    <!-- Piwik -->
    <script type="text/javascript">
      var _paq = _paq || [];
      _paq.push(["trackPageView"]);
      _paq.push(["enableLinkTracking"]);

      (function() {
        var u=(("https:" == document.location.protocol) ? "https" : "http") + "://piwik.incolumitas.com/";
        _paq.push(["setTrackerUrl", u+"piwik.php"]);
        _paq.push(["setSiteId", "1"]);
        var d=document, g=d.createElement("script"), s=d.getElementsByTagName("script")[0]; g.type="text/javascript";
        g.defer=true; g.async=true; g.src=u+"piwik.js"; s.parentNode.insertBefore(g,s);
      })();
    </script>
    <?php /* The content inside the <noscript> element will be displayed if scripts are not supported, or are disabled in the user’s browser. */ ?>
    <noscript>
    <!-- Piwik Image Tracker -->
    <img src="https://piwik.incolumitas.com/piwik.php?idsite=1&amp;rec=1" style="border:0" alt="" />
    <!-- End Piwik -->
    </noscript>
<!-- End Piwik Code -->
    <?php
}
endif;

if ( !function_exists( 'clearcontent_next_element_by_key' ) ) :
/*
 * Get's the next array element by the key. Returns False if key is not found or the array has no next element. If the 
 * next element is successfully located, return it (The element may be NULL).
 */
function clearcontent_next_element_by_key($key, $array) {
	if (!in_array($key, array_keys($array)))
		return False;
	$next = NULL;
	foreach ($array as $k => $value) {
		if ($key == $k) {
			$next = $value;
			continue;
		}
		if ($next != NULL)
			return $value;
	}
	return False;
}
endif;

if ( !function_exists( 'clearcontent_add_comment_author_to_reply_link' ) ) :
/*
 * Change the comment reply link to use 'Reply to &lt;Author First Name>'
 * and add the CSS class to use bootstrap buttons.
 * Credits go to: http://raamdev.com/2013/personalizing-the-wordpress-comment-reply-link/
 */
function clearcontent_add_comment_author_to_reply_link($link, $args, $comment) {

    $comment = get_comment( $comment );

    // If no comment author is blank, use 'Anonymous'
    if ( empty($comment->comment_author) ) {
        if (!empty($comment->user_id)) {
            $user=get_userdata($comment->user_id);
            $author=$user->user_login;
        } else {
            $author = __('Anonymous', 'clearcontent');
        }
    } else {
        $author = $comment->comment_author;
    }

    // If the user provided more than a first name, use only first name
    if(strpos($author, ' ')){
        $author = substr($author, 0, strpos($author, ' '));
    }

    // Replace Reply Link with "Reply to &lt;Author First Name>"
    $reply_link_text = $args['reply_text'];
    $link = str_replace($reply_link_text, 'Reply to ' . $author, $link);

    return str_replace('comment-reply-link', 'comment-reply-link btn btn-primary btn-xs', $link);
}
add_filter('comment_reply_link', 'clearcontent_add_comment_author_to_reply_link', 10, 3);
endif;


/*
 * The higher the number, the lower the priority and as a result your hook
 * will be executed further down the page. Enqueued scripts are executed at priority level 20.
 */
add_action( 'wp_footer', 'clearcontent_tracking_code', 100); /* echo the tracking code to the bottom of the page */




/**
 * Register Custom Navigation Walker
 */

require get_template_directory() . '/inc/wp_bootstrap_navwalker.php';

/**
 * Implement the Custom Header feature.
 */
//require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';
