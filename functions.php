<?php

/**
 * planty functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package planty
 */

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function planty_setup()
{
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on planty, use a find and replace
		* to change 'planty' to the name of your theme in all the template files.
		*/
	load_theme_textdomain('planty', get_template_directory() . '/languages');

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support('title-tag');

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support('post-thumbnails');

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-1' => esc_html__('Primary', 'planty'),
			'footer' => esc_html__('Footer', 'planty'),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action('after_setup_theme', 'planty_setup');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function planty_content_width()
{
	$GLOBALS['content_width'] = apply_filters('planty_content_width', 640);
}
add_action('after_setup_theme', 'planty_content_width', 0);

/**
 * URL of a file inside the theme. Replaces the absolute /img/... paths the
 * static layout used, which no longer resolve inside a WordPress install.
 *
 * @param string $path Path relative to the theme root.
 * @return string URL.
 */
function planty_asset($path)
{
	return get_template_directory_uri() . '/' . ltrim($path, '/');
}

function planty_price($value)
{
	$planty_value = preg_replace('/[^0-9.,]/', '', (string) $value);

	if (empty($planty_value)) {
		return '';
	}

	return '$ ' . $planty_value;
}

/**
 * Enqueue scripts and styles.
 */
function planty_scripts()
{
	// Swiper 11, the same build the static layout loaded from the CDN.
	$planty_css = get_template_directory() . '/css/style.css';
	$planty_js = get_template_directory() . '/js/script.js';

	wp_enqueue_style('swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css', array(), '11');
	wp_enqueue_style('planty', planty_asset('css/style.css'), array('swiper'), filemtime($planty_css));

	wp_enqueue_script('swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', array(), '11', true);
	wp_enqueue_script('planty', planty_asset('js/script.js'), array('swiper'), filemtime($planty_js), true);

	wp_localize_script(
		'planty',
		'plantyShop',
		array(
			'restUrl' => str_replace(home_url(), '', rest_url('planty/v1/products')),
		)
	);

	// WordPress loads this only so other sites can embed ours. Not needed here.
	wp_deregister_script('wp-embed');
}
add_action('wp_enqueue_scripts', 'planty_scripts');

/**
 * Open Graph and Twitter Card tags, so a shared link gets a preview.
 *
 * The image is a fixed file in the theme: this is a one page site, so there is
 * nothing to pick per post. Width and height are deliberately left out — the
 * file can be replaced, and stale numbers would be worse than none.
 */
function planty_open_graph()
{
	$planty_title       = wp_get_document_title();
	$planty_description = get_bloginfo('description');
	$planty_url         = home_url('/');
	$planty_image       = planty_asset('img/collection/collection01.jpg');
	?>
	<meta property="og:type" content="website">
	<meta property="og:site_name" content="<?php echo esc_attr(get_bloginfo('name')); ?>">
	<meta property="og:locale" content="<?php echo esc_attr(get_locale()); ?>">
	<meta property="og:title" content="<?php echo esc_attr($planty_title); ?>">
	<meta property="og:description" content="<?php echo esc_attr($planty_description); ?>">
	<meta property="og:url" content="<?php echo esc_url($planty_url); ?>">
	<meta property="og:image" content="<?php echo esc_url($planty_image); ?>">
	<meta name="twitter:card" content="summary_large_image">
	<?php
}
add_action('wp_head', 'planty_open_graph', 5);

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Custom post types.
 */
require get_template_directory() . '/inc/post-types.php';

/**
 * Shop section.
 */
require get_template_directory() . '/inc/shop.php';


/**
 * Add the theme's item class to items of the primary menu
 * 
 * @param string[] $classes Css classes
 * @param WP_Post $item Menu item
 * @param stdClass $args wp_nav_menu() arguments
 * @return string[] Modified classes
 */

function planty_nav_menu_css_class($classes, $item, $args)
{
	if (! isset($args->theme_location)) {
		return $classes;
	}
	if ('menu-1' === $args->theme_location) {
		$classes[] = 'menu__item';
	} elseif ('footer' === $args->theme_location) {
		$classes[] = 'footer__item';
	}
	return $classes;
}

add_filter('nav_menu_css_class', 'planty_nav_menu_css_class', 10, 3);


/**
 * Add theme's link class to anchors of the primary menu
 * 
 * @param array $atts HTML attributes
 * @param WP_Post $item Menu item
 * @param stdClass $args wp_nav_menu() arguments
 * @return array Modifien attributes
 */

function planty_nav_menu_link_attributes($atts, $item, $args)
{
	if (! isset($args->theme_location)) {
		return $atts;
	}
	if ('menu-1' === $args->theme_location) {
		$atts['class'] = 'menu__link-item';
	} elseif ('footer' === $args->theme_location) {
		$atts['class'] = 'footer__link-item';
	}
	return $atts;
}

add_filter('nav_menu_link_attributes', 'planty_nav_menu_link_attributes', 10, 3);
