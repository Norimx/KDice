<?php

namespace kdiceElementor\Includes\Customizer;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Elementor_Upsell extends \WP_Customize_Control {

	// Whitelist content parameter
	public $content = '';

	/**
	 * Render the control's content.
	 *
	 * Allows the content to be overriden without having to rewrite the wrapper.
	 *
	 * @since   2.4.0
	 * @return  void
	 */
	public function render_content() {
		$this->print_customizer_upsell();

		if ( isset( $this->description ) ) {
			echo '<span class="description customize-control-description">' . wp_kses_post( $this->description ) . '</span>';
		}
	}

	private function print_customizer_upsell() {
		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$customizer_content = '';

		// Get Plugins
		$plugins = get_plugins();

		if ( ! isset( $plugins['elementor/elementor.php'] ) ) {
			$customizer_content .= $this->get_customizer_upsell_html(
				__( 'Install Elementor', 'kdice-elementor' ),
				__( 'Create a cross-site Header and Footer using Elementor & kdice theme.', 'kdice-elementor' ),
				wp_nonce_url(
					add_query_arg(
						[
							'action' => 'install-plugin',
							'plugin' => 'elementor',
						],
						admin_url( 'update.php' )
					),
					'install-plugin_elementor'
				),
				__( 'Install &amp; Activate', 'kdice-elementor' ),
				get_template_directory_uri() . '/assets/images/go-pro.svg'
			);
		} elseif ( ! defined( 'ELEMENTOR_VERSION' ) ) {
			$customizer_content .= $this->get_customizer_upsell_html(
				__( 'Activate Elementor', 'kdice-elementor' ),
				__( 'Create a cross-site Header and Footer using Elementor & kdice theme.', 'kdice-elementor' ),
				wp_nonce_url( 'plugins.php?action=activate&plugin=elementor/elementor.php', 'activate-plugin_elementor/elementor.php' ),
				__( 'Activate Elementor', 'kdice-elementor' ),
				get_template_directory_uri() . '/assets/images/go-pro.svg'
			);
		} elseif ( defined( 'ELEMENTOR_VERSION' ) && version_compare( ELEMENTOR_VERSION, '3.0.12', '<' ) ) {
			$customizer_content .= $this->get_customizer_upsell_html(
				__( 'Update Elementor', 'kdice-elementor' ),
				__( 'You need Elementor version 3.1.0 or above to create a cross-site Header and Footer.', 'kdice-elementor' ),
				wp_nonce_url( 'update-core.php' ),
				__( 'Update Elementor', 'kdice-elementor' ),
				get_template_directory_uri() . '/assets/images/go-pro.svg'
			);
		} elseif ( ! kdice_header_footer_experiment_active() ) {
			$customizer_content .= $this->get_customizer_upsell_html(
				__( 'Set Your Header &amp; Footer', 'kdice-elementor' ),
				__( 'Create cross-site Header and Footer using Elementor & kdice theme.', 'kdice-elementor' ),
				wp_nonce_url( 'admin.php?page=elementor#tab-experiments' ),
				__( 'Activate Now', 'kdice-elementor' ),
				get_template_directory_uri() . '/assets/images/go-pro.svg'
			);
		} else {
			$customizer_content .= $this->get_customizer_upsell_html(
				__( 'Set Your Header &amp; Footer', 'kdice-elementor' ),
				__( 'Create cross-site Header and Footer using Elementor & kdice theme.', 'kdice-elementor' ),
				wp_nonce_url( 'post.php?post=' . get_option( 'elementor_active_kit' ) . '&action=elementor' ),
				__( 'Start Here', 'kdice-elementor' ),
				get_template_directory_uri() . '/assets/images/go-pro.svg'
			);
		}

		echo wp_kses_post( $customizer_content );
	}

	private function get_customizer_upsell_html( $title, $text, $url, $button_text, $image ) {
		return sprintf( '
			<div class="customize-control-header-footer-holder">
				<img src="%5$s">
				<div class="elementor-nerd-box-message">
					<p class="elementor-panel-heading-title elementor-section-title">%1$s</p>
					<p class="elementor-section-body">%2$s</p>
				</div>
				<a class="button button-primary" target="_blank" href="%3$s">%4$s</a>
			</div>',
			$title,
			$text,
			$url,
			$button_text,
			$image
		);
	}
}
