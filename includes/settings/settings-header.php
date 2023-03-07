<?php

namespace kdiceElementor\Includes\Settings;

use Elementor\Plugin;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Core\Responsive\Responsive;
use Elementor\Core\Kits\Documents\Tabs\Tab_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Settings_Header extends Tab_Base {

	public function get_id() {
		return 'kdice-settings-header';
	}

	public function get_title() {
		return __( 'Header', 'kdice-elementor' );
	}

	public function get_icon() {
		return 'eicon-header';
	}

	public function get_help_url() {
		return '';
	}

	public function get_group() {
		return 'theme-style';
	}

	protected function register_tab_controls() {
		$this->start_controls_section(
			'kdice_header_section',
			[
				'tab' => 'kdice-settings-header',
				'label' => __( 'Header', 'kdice-elementor' ),
			]
		);

		$this->add_control(
			'kdice_header_logo_display',
			[
				'type' => Controls_Manager::SWITCHER,
				'label' => __( 'Site Logo', 'kdice-elementor' ),
				'default' => 'yes',
				'label_on' => __( 'Show', 'kdice-elementor' ),
				'label_off' => __( 'Hide', 'kdice-elementor' ),
			]
		);

		$this->add_control(
			'kdice_header_tagline_display',
			[
				'type' => Controls_Manager::SWITCHER,
				'label' => __( 'Tagline', 'kdice-elementor' ),
				'default' => 'yes',
				'label_on' => __( 'Show', 'kdice-elementor' ),
				'label_off' => __( 'Hide', 'kdice-elementor' ),
			]
		);

		$this->add_control(
			'kdice_header_menu_display',
			[
				'type' => Controls_Manager::SWITCHER,
				'label' => __( 'Menu', 'kdice-elementor' ),
				'default' => 'yes',
				'label_on' => __( 'Show', 'kdice-elementor' ),
				'label_off' => __( 'Hide', 'kdice-elementor' ),
			]
		);

		$this->add_control(
			'kdice_header_layout',
			[
				'type' => Controls_Manager::SELECT,
				'label' => __( 'Layout', 'kdice-elementor' ),
				'options' => [
					'default' => __( 'Default', 'kdice-elementor' ),
					'inverted' => __( 'Inverted', 'kdice-elementor' ),
					'stacked' => __( 'Centered', 'kdice-elementor' ),
				],
				'selector' => '.site-header',
				'default' => 'default',
			]
		);

		$this->add_control(
			'kdice_header_width',
			[
				'type' => Controls_Manager::SELECT,
				'label' => __( 'Width', 'kdice-elementor' ),
				'options' => [
					'boxed' => __( 'Boxed', 'kdice-elementor' ),
					'full-width' => __( 'Full Width', 'kdice-elementor' ),
				],
				'selector' => '.site-header',
				'default' => 'boxed',
			]
		);

		$this->add_responsive_control(
			'kdice_header_custom_width',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => __( 'Content Width', 'kdice-elementor' ),
				'size_units' => [
					'%',
					'px',
				],
				'range' => [
					'px' => [
						'max' => 2000,
						'step' => 1,
					],
					'%' => [
						'max' => 100,
						'step' => 1,
					],
				],
				'condition' => [
					'kdice_header_width' => 'boxed',
				],
				'selectors' => [
					'.site-header .header-inner' => 'width: {{SIZE}}{{UNIT}}; max-width: 100%;',
				],
			]
		);

		$this->add_responsive_control(
			'kdice_header_gap',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => __( 'Gap', 'kdice-elementor' ),
				'size_units' => [
					'%',
					'px',
				],
				'default' => [
					'size' => '0',
				],
				'range' => [
					'px' => [
						'max' => 2000,
						'step' => 1,
					],
					'%' => [
						'max' => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					'.site-header' => 'padding-right: {{SIZE}}{{UNIT}}; padding-left: {{SIZE}}{{UNIT}}',
				],
				'conditions' => [
					'relation' => 'and',
					'terms' => [
						[
							'name' => 'kdice_header_layout',
							'operator' => '!=',
							'value' => 'stacked',
						],
					],
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'kdice_header_background',
				'label' => __( 'Background', 'kdice-elementor' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '.site-header',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'kdice_header_logo_section',
			[
				'tab' => 'kdice-settings-header',
				'label' => __( 'Site Logo', 'kdice-elementor' ),
				'conditions' => [
					'relation' => 'and',
					'terms' => [
						[
							'name' => 'kdice_header_logo_display',
							'operator' => '=',
							'value' => 'yes',
						],
					],
				],
			]
		);

		$this->add_control(
			'kdice_header_logo_type',
			[
				'label' => __( 'Type', 'kdice-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => ( has_custom_logo() ? 'logo' : 'title' ),
				'options' => [
					'logo' => __( 'Logo', 'kdice-elementor' ),
					'title' => __( 'Title', 'kdice-elementor' ),
				],
				'frontend_available' => true,
			]
		);

		$this->add_responsive_control(
			'kdice_header_logo_width',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => __( 'Logo Width', 'kdice-elementor' ),
				'description' => sprintf( __( 'Go to <a href="%s">Site Identity</a> to manage your site\'s logo', 'kdice-elementor' ), wp_nonce_url( 'customize.php?autofocus[section]=title_tagline' ) ),
				'size_units' => [
					'%',
					'px',
					'vh',
				],
				'range' => [
					'px' => [
						'max' => 1000,
						'step' => 1,
					],
					'%' => [
						'max' => 100,
						'step' => 1,
					],
				],
				'condition' => [
					'kdice_header_logo_display' => 'yes',
					'kdice_header_logo_type' => 'logo',
				],
				'selectors' => [
					'.site-header .site-branding .site-logo img' => 'width: {{SIZE}}{{UNIT}}; max-width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'kdice_header_title_color',
			[
				'label' => __( 'Text Color', 'kdice-elementor' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'kdice_header_logo_display' => 'yes',
					'kdice_header_logo_type' => 'title',
				],
				'selectors' => [
					'.site-header h1.site-title a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'kdice_header_title_typography',
				'label' => __( 'Typography', 'kdice-elementor' ),
				'description' => sprintf( __( 'Go to <a href="%s">Site Identity</a> to manage your site\'s title and tagline', 'kdice-elementor' ), wp_nonce_url( 'customize.php?autofocus[section]=title_tagline' ) ),
				'condition' => [
					'kdice_header_logo_display' => 'yes',
					'kdice_header_logo_type' => 'title',
				],
				'selector' => '.site-header h1.site-title',
			]
		);

		$this->add_control(
			'kdice_header_title_link',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => sprintf( __( 'Go to <a href="%s">Site Identity</a> to manage your site\'s title and tagline', 'kdice-elementor' ), wp_nonce_url( 'customize.php?autofocus[section]=title_tagline' ) ),
				'content_classes' => 'elementor-control-field-description',
				'condition' => [
					'kdice_header_logo_display' => 'yes',
					'kdice_header_logo_type' => 'title',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'kdice_header_tagline',
			[
				'tab' => 'kdice-settings-header',
				'label' => __( 'Tagline', 'kdice-elementor' ),
				'conditions' => [
					'relation' => 'and',
					'terms' => [
						[
							'name' => 'kdice_header_tagline_display',
							'operator' => '=',
							'value' => 'yes',
						],
					],
				],
			]
		);

		$this->add_control(
			'kdice_header_tagline_color',
			[
				'label' => __( 'Text Color', 'kdice-elementor' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'kdice_header_tagline_display' => 'yes',
				],
				'selectors' => [
					'.site-header .site-description' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'kdice_header_tagline_typography',
				'label' => __( 'Typography', 'kdice-elementor' ),
				'condition' => [
					'kdice_header_tagline_display' => 'yes',
				],
				'selector' => '.site-header .site-description',
			]
		);

		$this->add_control(
			'kdice_header_tagline_link',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => sprintf( __( 'Go to <a href="%s">Site Identity</a> to manage your site\'s title and tagline', 'kdice-elementor' ), wp_nonce_url( 'customize.php?autofocus[section]=title_tagline' ) ),
				'content_classes' => 'elementor-control-field-description',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'kdice_header_menu_tab',
			[
				'tab' => 'kdice-settings-header',
				'label' => __( 'Menu', 'kdice-elementor' ),
				'conditions' => [
					'relation' => 'and',
					'terms' => [
						[
							'name' => 'kdice_header_menu_display',
							'operator' => '=',
							'value' => 'yes',
						],
					],
				],
			]
		);

		$available_menus = wp_get_nav_menus();

		$menus = [ '0' => __( '— Select a Menu —', 'kdice-elementor' ) ];
		foreach ( $available_menus as $available_menu ) {
			$menus[ $available_menu->term_id ] = $available_menu->name;
		}

		if ( 1 === count( $menus ) ) {
			$this->add_control(
				'kdice_header_menu_notice',
				[
					'type' => Controls_Manager::RAW_HTML,
					'raw' => '<strong>' . __( 'There are no menus in your site.', 'kdice-elementor' ) . '</strong><br>' . sprintf( __( 'Go to <a href="%s" target="_blank">Menus screen</a> to create one.', 'kdice-elementor' ), admin_url( 'nav-menus.php?action=edit&menu=0' ) ),
					'separator' => 'after',
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				]
			);
		} else {
			$this->add_control(
				'kdice_header_menu',
				[
					'label' => __( 'Menu', 'kdice-elementor' ),
					'type' => Controls_Manager::SELECT,
					'options' => $menus,
					'default' => array_keys( $menus )[0],
					'description' => sprintf( __( 'Go to the <a href="%s" target="_blank">Menus screen</a> to manage your menus.', 'kdice-elementor' ), admin_url( 'nav-menus.php' ) ),
				]
			);

			$this->add_control(
				'kdice_header_menu_warning',
				[
					'type' => Controls_Manager::RAW_HTML,
					'raw' => __( 'Changes will be reflected in the preview only after the page reloads.', 'kdice-elementor' ),
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				]
			);

			$this->add_control(
				'kdice_header_menu_layout',
				[
					'label' => __( 'Menu Layout', 'kdice-elementor' ),
					'type' => Controls_Manager::SELECT,
					'default' => 'horizontal',
					'options' => [
						'horizontal' => __( 'Horizontal', 'kdice-elementor' ),
						'dropdown' => __( 'Dropdown', 'kdice-elementor' ),
					],
					'frontend_available' => true,
				]
			);

			$breakpoints = Responsive::get_breakpoints();

			$this->add_control(
				'kdice_header_menu_dropdown',
				[
					'label' => __( 'Breakpoint', 'kdice-elementor' ),
					'type' => Controls_Manager::SELECT,
					'default' => 'tablet',
					'options' => [
						/* translators: %d: Breakpoint number. */
						'mobile' => sprintf( __( 'Mobile (< %dpx)', 'kdice-elementor' ), $breakpoints['md'] ),
						/* translators: %d: Breakpoint number. */
						'tablet' => sprintf( __( 'Tablet (< %dpx)', 'kdice-elementor' ), $breakpoints['lg'] ),
						'none' => __( 'None', 'kdice-elementor' ),
					],
					'selector' => '.site-header',
					'condition' => [
						'kdice_header_menu_layout!' => 'dropdown',
					],
				]
			);

			$this->add_control(
				'kdice_header_menu_color',
				[
					'label' => __( 'Color', 'kdice-elementor' ),
					'type' => Controls_Manager::COLOR,
					'condition' => [
						'kdice_header_menu_display' => 'yes',
					],
					'selectors' => [
						'.site-header .site-navigation ul.menu li a' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'kdice_header_menu_toggle_color',
				[
					'label' => __( 'Toggle Color', 'kdice-elementor' ),
					'type' => Controls_Manager::COLOR,
					'condition' => [
						'kdice_header_menu_display' => 'yes',
					],
					'selectors' => [
						'.site-header .site-navigation-toggle i' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'kdice_header_menu_typography',
					'label' => __( 'Typography', 'kdice-elementor' ),
					'condition' => [
						'kdice_header_menu_display' => 'yes',
					],
					'selector' => '.site-header .site-navigation .menu li',
				]
			);
		}

		$this->end_controls_section();
	}

	public function on_save( $data ) {
		// Save chosen header menu to the WP settings.
		if ( isset( $data['settings']['kdice_header_menu'] ) ) {
			$menu_id = $data['settings']['kdice_header_menu'];
			$locations = get_theme_mod( 'nav_menu_locations' );
			$locations['menu-1'] = (int) $menu_id;
			set_theme_mod( 'nav_menu_locations', $locations );
		}
	}

	public function get_additional_tab_content() {
		if ( ! defined( 'ELEMENTOR_PRO_VERSION' ) ) {
			return sprintf( '
				<div class="kdice-elementor elementor-nerd-box">
					<img src="%4$s" class="elementor-nerd-box-icon">
					<div class="elementor-nerd-box-message">
						<p class="elementor-panel-heading-title elementor-nerd-box-title">%1$s</p>
						<p>%2$s</p>
					</div>
					<a class="elementor-button elementor-button-default elementor-nerd-box-link" target="_blank" href="https://go.elementor.com/kdice-theme-header/">%3$s</a>
				</div>
				',
				__( 'Create a custom header with multiple options', 'kdice-elementor' ),
				__( 'Upgrade to Elementor Pro and enjoy free design and many more features', 'kdice-elementor' ),
				__( 'Upgrade', 'kdice-elementor' ),
				get_template_directory_uri() . '/assets/images/go-pro.svg'
			);
		} else {
			return sprintf( '
				<div class="kdice-elementor elementor-nerd-box">
					<img src="%4$s" class="elementor-nerd-box-icon">
					<div class="elementor-nerd-box-message">
						<p class="elementor-panel-heading-title elementor-nerd-box-title">%1$s</p>
						<p class="elementor-nerd-box-message">%2$s</p>
					</div>
					<a class="elementor-button elementor-button-success elementor-nerd-box-link" target="_blank" href="%5$s">%3$s</a>
				</div>
				',
				__( 'Create a custom header with the new Theme Builder', 'kdice-elementor' ),
				__( 'With the new Theme Builder you can jump directly into each part of your site', 'kdice-elementor' ),
				__( 'Create Header', 'kdice-elementor' ),
				get_template_directory_uri() . '/assets/images/go-pro.svg',
				get_admin_url( null, 'admin.php?page=elementor-app#/site-editor/templates/header' )
			);
		}
	}
}
