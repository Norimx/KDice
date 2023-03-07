<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Register Customizer controls which add Elementor deeplinks
 *
 * @return void
 */
add_action( 'customize_register', 'kdice_customizer_register' );
function kdice_customizer_register( $wp_customize ) {
	require get_template_directory() . '/includes/customizer/elementor-upsell.php';

	$wp_customize->add_section(
		'kdice_theme_options',
		[
			'title' => __( 'Header &amp; Footer', 'kdice-elementor' ),
			'capability' => 'edit_theme_options',
		]
	);

	$wp_customize->add_setting(
		'kdice-elementor-header-footer',
		[
			'sanitize_callback' => false,
			'transport' => 'refresh',
		]
	);

	$wp_customize->add_control(
		new KdiceElementor\Includes\Customizer\Elementor_Upsell(
			$wp_customize,
			'kdice-elementor-header-footer',
			[
				'section' => 'kdice_theme_options',
				'priority' => 20,
			]
		)
	);
}


/**
 * Enqueue Customiser CSS
 *
 * @return string HTML to use in the customizer panel
 */
add_action( 'admin_enqueue_scripts', 'kdice_customizer_print_styles' );
function kdice_customizer_print_styles() {

	$min_suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

	wp_enqueue_style(
		'kdice-elementor-customizer',
		get_template_directory_uri() . '/customizer' . $min_suffix . '.css',
		[],
		kdice_ELEMENTOR_VERSION
	);
}
