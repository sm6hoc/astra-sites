<?php
/**
 * Astra Sites Compatibility for 'WPForms – Drag & Drop Form Builder for WordPress'
 *
 * @see  https://wordpress.org/plugins/wpforms-lite/
 *
 * @package Astra Sites
 * @since x.x.x
 */

if ( ! class_exists( 'Astra_Sites_Compatibility_WPForms' ) ) :

	/**
	 * Astra_Sites_Compatibility_WPForms
	 *
	 * @since x.x.x
	 */
	class Astra_Sites_Compatibility_WPForms {

		/**
		 * Instance
		 *
		 * @access private
		 * @var object Class object.
		 * @since x.x.x
		 */
		private static $instance;

		/**
		 * Initiator
		 *
		 * @since x.x.x
		 * @return object initialized object of class.
		 */
		public static function instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self;
			}
			return self::$instance;
		}

		/**
		 * Constructor
		 *
		 * @since x.x.x
		 */
		public function __construct() {
			add_action( 'astra_sites_after_plugin_activation', array( $this, 'after_plugin_activate' ), 10, 2 );
			add_action( 'astra_sites_import_complete', array( $this, 'after_import_complete' ) );
		}

		/**
		 * Avoid redirection
		 *
		 * @since x.x.x
		 *
		 * @param  string $plugin_init        Plugin init file.
		 * @param  array  $data               Data.
		 * @return void
		 */
		function after_plugin_activate( $plugin_init = '', $data = array() ) {
			if ( 'wpforms-lite/wpforms.php' === $plugin_init ) {
				update_option( 'wpforms_version_upgraded_from', true );
			}
		}

		/**
		 * Keep the default
		 *
		 * @since x.x.x
		 *
		 * @return void
		 */
		function after_import_complete() {
			delete_option( 'wpforms_version_upgraded_from' );
		}

	}

	/**
	 * Kicking this off by calling 'instance()' method
	 */
	Astra_Sites_Compatibility_WPForms::instance();

endif;
