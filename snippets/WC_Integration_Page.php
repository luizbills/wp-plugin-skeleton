<?php

namespace {ns};

use {ns}\functions as h;

final class WC_Integration_Page extends \WC_Integration {
	/**
	 * Init and hook in the integration.
	 */
	public function __construct() {
		$this->id                 = h\config_get( 'SLUG' );
		$this->method_title       = __( 'THE TITLE' );
		$this->method_description = __( 'PUT SOMENTHING USEFUL HERE' );

		// Load the settings.
		$this->init_form_fields();
		$this->init_settings();

		// Actions
		\add_action( "woocommerce_update_options_integration_{$this->id}", [ $this, 'process_admin_options' ] );

		// Define user set variables
		$this->options = [
			'api_key' => $this->get_option( 'api_key' ),
			'debug' => 'yes' === $this->get_option( 'debug' ),
		];
		h\config_set( 'settings', $this->options );
		
		// Add a settings link in plugins page
		h\add_plugin_action_link( 
			\esc_html__( 'Settings' ), 
			\admin_url( "admin.php?page=wc-settings&tab=integration&section={$this->id}" )
		);
	}

	/**
	 * Initialize integration settings form fields.
	 */
	public function init_form_fields() {
		$this->form_fields = [
			'api_key' => [
				'title'             => __( 'API Key' ),
				'type'              => 'text',
			],
			'debug' => [
				'title'             => __( 'Debug Log' ),
				'type'              => 'checkbox',
				'label'             => __( 'Enable' ),
				'default'           => 'no',
				'description'       => __( 'Log events such as API requests' ),
			],
		];
	}
}

/*
	Also, is necessary to create a class to load this integration page:
	
	final class Loader {
		public function __boot () {
			\add_filter( 'woocommerce_integrations', [ $this, 'register_integration_page' ] );
		}

		public function register_integration_page ( $integrations ) {
			$integrations[] = WC_Integration_Page::class;
			return $integrations;
		}
	}

	Learn more: https://woocommerce.com/document/implementing-wc-integration/
*/
