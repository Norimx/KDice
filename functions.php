<?php
 

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'VER', '2.6.1' );

if ( ! isset( $content_width ) ) {
	$content_width = 800; // Pixels.
}

if ( ! function_exists( 'kdice_elementor_setup' ) ) {
	/**
	 * Set up theme support.
	 *
	 * @return void
	 */
	function kdice_elementor_setup() {
		if ( is_admin() ) {
			kdice_maybe_update_theme_version_in_db();
		}

		$hook_result = apply_filters_deprecated( 'elementor_kdice_theme_load_textdomain', [ true ], '2.0', 'kdice_elementor_load_textdomain' );
		if ( apply_filters( 'kdice_elementor_load_textdomain', $hook_result ) ) {
			load_theme_textdomain( 'kdice-elementor', get_template_directory() . '/languages' );
		}

		$hook_result = apply_filters_deprecated( 'elementor_kdice_theme_register_menus', [ true ], '2.0', 'kdice_elementor_register_menus' );
		if ( apply_filters( 'kdice_elementor_register_menus', $hook_result ) ) {
			register_nav_menus( [ 'menu-1' => __( 'Header', 'kdice-elementor' ) ] );
			register_nav_menus( [ 'menu-2' => __( 'Footer', 'kdice-elementor' ) ] );
		}

		$hook_result = apply_filters_deprecated( 'elementor_kdice_theme_add_theme_support', [ true ], '2.0', 'kdice_elementor_add_theme_support' );
		if ( apply_filters( 'kdice_elementor_add_theme_support', $hook_result ) ) {
			add_theme_support( 'post-thumbnails' );
			add_theme_support( 'automatic-feed-links' );
			add_theme_support( 'title-tag' );
			add_theme_support(
				'html5',
				[
					'search-form',
					'comment-form',
					'comment-list',
					'gallery',
					'caption',
					'script',
					'style',
				]
			);
			add_theme_support(
				'custom-logo',
				[
					'height'      => 100,
					'width'       => 350,
					'flex-height' => true,
					'flex-width'  => true,
				]
			);

	 
			// all actions related to emojis
			remove_action( 'admin_print_styles', 'print_emoji_styles' );
			remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
			remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
			remove_action( 'wp_print_styles', 'print_emoji_styles' );
			remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
			remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
			remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );

			// filter to remove TinyMCE emojis
			add_filter( 'tiny_mce_plugins', 'disable_emojicons_tinymce' );
			add_filter( 'wp_calculate_image_srcset', '__return_false' );
			/*
			 * WooCommerce.
			 */
			$hook_result = apply_filters_deprecated( 'elementor_kdice_theme_add_woocommerce_support', [ true ], '2.0', 'kdice_elementor_add_woocommerce_support' );
			if ( apply_filters( 'kdice_elementor_add_woocommerce_support', $hook_result ) ) {
				// WooCommerce in general.
				add_theme_support( 'woocommerce' );
				// Enabling WooCommerce product gallery features (are off by default since WC 3.0.0).
				// zoom.
				add_theme_support( 'wc-product-gallery-zoom' );
				// lightbox.
				add_theme_support( 'wc-product-gallery-lightbox' );
				// swipe.
				add_theme_support( 'wc-product-gallery-slider' );
			}
		}


		// Disable login modals introduced in WordPress 3.6
		remove_action( 'admin_enqueue_scripts', 'wp_auth_check_load' );

		// Disable useless tags
		remove_action( 'wp_head', 'rsd_link' );
		remove_action( 'wp_head', 'wlwmanifest_link' );
		remove_action( 'wp_head', 'wp_generator' );
		remove_action( 'wp_head', 'start_post_rel_link' );
		remove_action( 'wp_head', 'index_rel_link' );
		remove_action( 'wp_head', 'adjacent_posts_rel_link' );
		remove_action( 'wp_head', 'wp_shortlink_wp_head' );
	}
}
add_action( 'after_setup_theme', 'kdice_elementor_setup' );

function kdice_maybe_update_theme_version_in_db() {
	$theme_version_option_name = 'kdice_theme_version';
	// The theme version saved in the database.
	$kdice_theme_db_version = get_option( $theme_version_option_name );

	// If the 'kdice_theme_version' option does not exist in the DB, or the version needs to be updated, do the update.
	if ( ! $kdice_theme_db_version || version_compare( $kdice_theme_db_version, VER, '<' ) ) {
		update_option( $theme_version_option_name, VER );
	}
}

if ( ! function_exists( 'kdice_elementor_scripts_styles' ) ) {
	/**
	 * Theme Scripts & Styles.
	 *
	 * @return void
	 */
	function kdice_elementor_scripts_styles() {
		$enqueue_basic_style = apply_filters_deprecated( 'elementor_kdice_theme_enqueue_style', [ true ], '2.0', 'kdice_elementor_enqueue_style' );
		$min_suffix          = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		if ( apply_filters( 'kdice_elementor_enqueue_style', $enqueue_basic_style ) ) {
			wp_enqueue_style(
				'kdice-elementor',
				get_template_directory_uri() . '/style.css',
				[],
				VER
			);
		}

		if ( apply_filters( 'kdice_elementor_enqueue_theme_style', true ) ) {
			wp_enqueue_style(
				'kdice-elementor-theme-style',
				get_template_directory_uri() . '/theme.css',
				[],
				VER
			);
		}
	}
}
add_action( 'wp_enqueue_scripts', 'kdice_elementor_scripts_styles' );

if ( ! function_exists( 'kdice_elementor_register_elementor_locations' ) ) {
	/**
	 * Register Elementor Locations.
	 *
	 * @param ElementorPro\Modules\ThemeBuilder\Classes\Locations_Manager $elementor_theme_manager theme manager.
	 *
	 * @return void
	 */
	function kdice_elementor_register_elementor_locations( $elementor_theme_manager ) {
		$hook_result = apply_filters_deprecated( 'elementor_kdice_theme_register_elementor_locations', [ true ], '2.0', 'kdice_elementor_register_elementor_locations' );
		if ( apply_filters( 'kdice_elementor_register_elementor_locations', $hook_result ) ) {
			$elementor_theme_manager->register_all_core_location();
		}
	}
}
add_action( 'elementor/theme/register_locations', 'kdice_elementor_register_elementor_locations' );

if ( ! function_exists( 'kdice_elementor_content_width' ) ) {
	/**
	 * Set default content width.
	 *
	 * @return void
	 */
	function kdice_elementor_content_width() {
		$GLOBALS['content_width'] = apply_filters( 'kdice_elementor_content_width', 800 );
	}
}
add_action( 'after_setup_theme', 'kdice_elementor_content_width', 0 );

if ( is_admin() ) {
	require get_template_directory() . '/includes/admin-functions.php';
}

/**
 * If Elementor is installed and active, we can load the Elementor-specific Settings & Features
*/

// Allow active/inactive via the Experiments
require get_template_directory() . '/includes/elementor-functions.php';

/**
 * Include customizer registration functions
*/
function kdice_register_customizer_functions() {
	if ( is_customize_preview() ) {
		require get_template_directory() . '/includes/customizer-functions.php';
	}
}
add_action( 'init', 'kdice_register_customizer_functions' );

if ( ! function_exists( 'kdice_elementor_check_hide_title' ) ) {
	/**
	 * Check hide title.
	 *
	 * @param bool $val default value.
	 *
	 * @return bool
	 */
	function kdice_elementor_check_hide_title( $val ) {
		if ( defined( 'ELEMENTOR_VERSION' ) ) {
			$current_doc = Elementor\Plugin::instance()->documents->get( get_the_ID() );
			if ( $current_doc && 'yes' === $current_doc->get_settings( 'hide_title' ) ) {
				$val = false;
			}
		}
		return $val;
	}
}
add_filter( 'kdice_elementor_page_title', 'kdice_elementor_check_hide_title' );

/**
 * Wrapper function to deal with backwards compatibility.
 */
if ( ! function_exists( 'kdice_elementor_body_open' ) ) {
	function kdice_elementor_body_open() {
		if ( function_exists( 'wp_body_open' ) ) {
			wp_body_open();
		} else {
			do_action( 'wp_body_open' );
		}
	}
}
