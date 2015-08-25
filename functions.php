<?php
/**
 * ishtar functions and definitions.
 * @link https://codex.wordpress.org/Functions_File_Explained
 * @package ishtar
 */

/* Sets up theme defaults and registers support for WP features. Hooked into
 * after_setup_theme, which runs prior to init.  */
if ( ! function_exists( 'ishtar_setup' ) ) :
function ishtar_setup() {
	load_theme_textdomain( 'ishtar', get_template_directory() . '/languages' ); // For translations
	add_theme_support( 'automatic-feed-links' ); // Support for links to RSS feeds in header

	// Let WordPress manage the document title. Don't hardcode <title> in <head>
	add_theme_support( 'title-tag' );

	// @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	add_theme_support( 'post-thumbnails' ); // Enable support for Post Thumbnails on posts and pages

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary Menu', 'ishtar' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 * See https://developer.wordpress.org/themes/functionality/post-formats/
	 */
	add_theme_support( 'post-formats', array(
		'aside',
		'image',
		'video',
		'quote',
		'link',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'ishtar_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
}
endif; // ishtar_setup
add_action( 'after_setup_theme', 'ishtar_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 * Priority 0 to make it available to lower priority callbacks.
 * @global int $content_width
 */
function ishtar_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'ishtar_content_width', 640 );
}
add_action( 'after_setup_theme', 'ishtar_content_width', 0 );

// Register widget area.
// @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
function ishtar_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'ishtar' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'ishtar_widgets_init' );

// Enqueue scripts and styles.
// Is it ishtar-style, or just style? I'm seeing conflicting examples...
function ishtar_scripts() {
	wp_enqueue_style( 'ishtar-style', get_stylesheet_uri() );
	wp_enqueue_script( 'ishtar-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20120206', true );
	wp_enqueue_script( 'ishtar-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( is_singular() && wp_attachment_is_image() ) {
		wp_enqueue_script( 'keyboard-image-navigation', get_template_directory_uri() . '/js/keyboard-image-navigation.js', array( 'jquery' ), '20120202' );
	}
}
add_action( 'wp_enqueue_scripts', 'ishtar_scripts' );

// Custom Includes
require get_template_directory() . '/inc/custom-header.php';
require get_template_directory() . '/inc/template-tags.php';
require get_template_directory() . '/inc/extras.php';
require get_template_directory() . '/inc/customizer.php';
require get_template_directory() . '/inc/jetpack.php'; // Jetpack compatibility
