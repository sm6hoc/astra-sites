<?php
/**
 * Astra Sites Importer
 *
 * @since  1.0.0
 * @package Astra Sites
 */

defined( 'ABSPATH' ) or exit;

if ( ! class_exists( 'Astra_Sites_Importer' ) ) :

	/**
	 * Astra Sites Importer
	 */
	class Astra_Sites_Importer {

		/**
		 * Instance
		 *
		 * @since  1.0.0
		 * @var (Object) Class object
		 */
		public static $_instance = null;

		/**
		 * Set Instance
		 *
		 * @since  1.0.0
		 *
		 * @return object Class object.
		 */
		public static function get_instance() {
			if ( ! isset( self::$_instance ) ) {
				self::$_instance = new self;
			}

			return self::$_instance;
		}

		/**
		 * Constructor.
		 *
		 * @since  1.0.0
		 */
		public function __construct() {

			require_once ASTRA_SITES_DIR . 'inc/classes/class-astra-sites-importer-log.php';
			require_once ASTRA_SITES_DIR . 'inc/importers/class-astra-sites-helper.php';
			require_once ASTRA_SITES_DIR . 'inc/importers/class-widgets-importer.php';
			require_once ASTRA_SITES_DIR . 'inc/importers/class-astra-customizer-import.php';
			require_once ASTRA_SITES_DIR . 'inc/importers/class-astra-site-options-import.php';

			// Import AJAX.
			add_action( 'wp_ajax_astra-sites-import-set-site-data', array( $this, 'import_start' ) );
			add_action( 'wp_ajax_astra-sites-import-wpforms', array( $this, 'import_wpforms' ) );
			add_action( 'wp_ajax_astra-sites-import-customizer-settings', array( $this, 'import_customizer_settings' ) );
			add_action( 'wp_ajax_astra-sites-import-prepare-xml', array( $this, 'prepare_xml_data' ) );
			add_action( 'wp_ajax_astra-sites-import-options', array( $this, 'import_options' ) );
			add_action( 'wp_ajax_astra-sites-import-widgets', array( $this, 'import_widgets' ) );
			add_action( 'wp_ajax_astra-sites-import-end', array( $this, 'import_end' ) );

			// Hooks in AJAX.
			add_action( 'astra_sites_import_complete', array( $this, 'clear_cache' ) );
			add_action( 'init', array( $this, 'load_importer' ) );

			require_once ASTRA_SITES_DIR . 'inc/importers/batch-processing/class-astra-sites-batch-processing.php';

			add_action( 'astra_sites_image_import_complete', array( $this, 'clear_cache' ) );

			// Reset Customizer Data.
			add_action( 'wp_ajax_astra-sites-reset-customizer-data', array( $this, 'reset_customizer_data' ) );
			add_action( 'wp_ajax_astra-sites-reset-site-options', array( $this, 'reset_site_options' ) );
			add_action( 'wp_ajax_astra-sites-reset-widgets-data', array( $this, 'reset_widgets_data' ) );

			// Reset Post & Terms.
			add_action( 'wp_ajax_astra-sites-delete-posts', array( $this, 'delete_imported_posts' ) );
			add_action( 'wp_ajax_astra-sites-delete-wp-forms', array( $this, 'delete_imported_wp_forms' ) );
			add_action( 'wp_ajax_astra-sites-delete-terms', array( $this, 'delete_imported_terms' ) );

			add_action( 'wp_ajax_astra-sites-set-all-sites', array( $this, 'set_all_sites' ) );

			// Cache.
			add_action( 'wp_ajax_astra-sites-cache-page-builders', array( $this, 'save_cache_page_builders' ) );
			add_action( 'wp_ajax_astra-sites-cache-categories', array( $this, 'save_cache_categories' ) );
			// add_action( 'wp_ajax_astra-sites-cache-sites', array( $this, 'save_cache_sites' ) );
		}

		// function save_cache_sites() {
		// 	$data = isset( $_POST['data'] ) ? $_POST['data'] : ''; 
		// 	$page = isset( $_POST['page'] ) ? $_POST['page'] : 1;

		// 	if( $data ) {

		// 		$stored = get_transient( 'astra-sites-cache-sites-draft' );
		// 		if( false == $stored ) {
		// 			$stored = array(
		// 				'items'       => $data['items'],
		// 				'items_count' => $data['items_count'],
		// 				'page'    => $page,
		// 			);
		// 		}

		// 		$new = array(
		// 			'items'       => $data['items'],
		// 			'items_count' => $data['items_count'],
		// 			'items_count' => $page,
		// 		);

		// 		$new = wp_parse_args( $new, $stored );

		// 		set_transient( 'astra-sites-cache-sites-draft', $new, WEEK_IN_SECONDS );

		// 		if( $data['items_count'] !== count( $new['items'] ) ) {
		// 			wp_send_json_success( array( 'procuess_again' => true, 'data' => $new, 'page' => $page + 1 ) );
		// 		}
		// 		// set_transient( 'astra-sites-cache-sites', $data, WEEK_IN_SECONDS );
		// 	}
		// 	wp_send_json_success();
		// }

		function save_cache_categories() {
			$data = isset( $_POST['data'] ) ? $_POST['data'] : ''; 
			if( $data ) {
				set_transient( 'astra-sites-cache-categories', $data, WEEK_IN_SECONDS );
			}
			wp_send_json_success();
		}

		function save_cache_page_builders() {
			$data = isset( $_POST['data'] ) ? $_POST['data'] : ''; 
			if( $data ) {
				set_transient( 'astra-sites-cache-page-builders', $data, WEEK_IN_SECONDS );
			}
			wp_send_json_success();
		}

		function set_all_sites() {
			$total_requests = $this->get_total_requests();

			$sites = array();
			if ( $total_requests ) {
				for ( $page = 1; $page <= $total_requests; $page++ ) {
					$response = $this->get_sites( array( 'page' => $page ) );
					if( isset( $response['sites'] ) ) {
						$sites = wp_parse_args( $response['sites'], $sites );
					}
				}
			}

			wp_send_json_success( $sites );
		}

		/**
		 * Get Total Requests
		 *
		 * @return integer
		 */
		function get_total_requests() {
			$response = $this->get_sites( array( 'per_page' => '1' ) );

			if ( isset( $response['x-wp-totalpages'] ) ) {
				return absint( $response['x-wp-totalpages'] );
			}

			$this->get_total_requests();
		}

 		/**
		 * Get Astra portfolios.
		 *
		 * @since 1.0.0
		 *
		 * @param  array $args For selecting the demos (Search terms, pagination etc).
		 * @return array        Astra Portfolio list.
		 */
		 function get_sites($args = array() ) {
		
			// Default values.
			$defaults = apply_filters( 'astra_sites_api_params', array(
				'page'         => '1',
				'per_page'     => '100',

				// Use this for premium demos.
				'purchase_key' => '',
				'site_url'     => '',
			) );

			$request_params = apply_filters( 'astra_portfolio_api_params', wp_parse_args( $args, $defaults ) );

			$url = add_query_arg( $request_params, 'https://websitedemos.net/wp-json/wp/v2/astra-sites' );

			$astra_demos = array(
				'sites'        => array(),
				'sites_count'  => 0,
				'api_response' => '',
			);

			$api_args = apply_filters(
				'astra_sites_api_args',
				array(
					'timeout' => 15,
				)
			);

			$response                    = wp_remote_get( $url, $api_args );
			$astra_demos['api_response'] = $response;

			if ( ! is_wp_error( $response ) || wp_remote_retrieve_response_code( $response ) === 200 ) {

				$astra_demos['sites_count']     = wp_remote_retrieve_header( $response, 'x-wp-total' );
				$astra_demos['x-wp-total']      = wp_remote_retrieve_header( $response, 'x-wp-total' );
				$astra_demos['x-wp-totalpages'] = wp_remote_retrieve_header( $response, 'x-wp-totalpages' );

				$result = json_decode( wp_remote_retrieve_body( $response ), true );

				// If is array then proceed
				// Else skip it.
				if ( is_array( $result ) ) {

					foreach ( $result as $key => $demo ) {

						if ( ! isset( $demo['id'] ) ) {
							continue;
						}

						$astra_demos['sites'][ $key ]['id']                      = isset( $demo['id'] ) ? esc_attr( $demo['id'] ) : '';
						$astra_demos['sites'][ $key ]['slug']                    = isset( $demo['slug'] ) ? esc_attr( $demo['slug'] ) : '';
						$astra_demos['sites'][ $key ]['astra_demo_type']         = isset( $demo['astra-site-type'] ) ? sanitize_key( $demo['astra-site-type'] ) : '';
						$astra_demos['sites'][ $key ]['title']                   = isset( $demo['title']['rendered'] ) ? esc_attr( $demo['title']['rendered'] ) : '';
						$astra_demos['sites'][ $key ]['featured_image_url']      = isset( $demo['featured-image-url'] ) ? esc_url( $demo['featured-image-url'] ) : '';
						$astra_demos['sites'][ $key ]['demo_api']                = isset( $demo['_links']['self'][0]['href'] ) ? esc_url( $demo['_links']['self'][0]['href'] ) : self::get_sites_api_url( new stdClass() ) . $demo['id'];
						$astra_demos['sites'][ $key ]['astra-site-category']     = isset( $demo['astra-site-category'] ) ? (array) $demo['astra-site-category'] : '';
						$astra_demos['sites'][ $key ]['astra-site-page-builder'] = isset( $demo['astra-site-page-builder'] ) ? (array) $demo['astra-site-page-builder'] : '';

						$site_url = '';
						if ( isset( $demo['astra-site-url'] ) ) {
							$site_url = set_url_scheme( '' . esc_url( $demo['astra-site-url'] ), 'https' );
						}
						$astra_demos['sites'][ $key ]['astra_demo_url'] = $site_url;
					}

					// Free up memory by un setting variables that are not required.
					unset( $result );
					unset( $response );
				}
			}

			return $astra_demos;

		}

		/**
		 * Load WordPress WXR importer.
		 */
		public function load_importer() {
			require_once ASTRA_SITES_DIR . 'inc/importers/wxr-importer/class-astra-wxr-importer.php';
		}

		/**
		 * Start Site Import
		 *
		 * @since 1.1.0
		 * @return void
		 */
		function import_start() {

			if ( ! current_user_can( 'customize' ) ) {
				wp_send_json_error( __( 'You have not "customize" access to import the Astra site.', 'astra-sites' ) );
			}

			$demo_api_uri = isset( $_POST['api_url'] ) ? esc_url( $_POST['api_url'] ) : '';

			if ( ! empty( $demo_api_uri ) ) {

				$demo_data = self::get_astra_single_demo( $demo_api_uri );

				update_option( 'astra_sites_import_data', $demo_data );

				if ( is_wp_error( $demo_data ) ) {
					wp_send_json_error( $demo_data->get_error_message() );
				} else {
					$log_file = Astra_Sites_Importer_Log::add_log_file_url( $demo_data['slug'] );
					if ( isset( $log_file['url'] ) && ! empty( $log_file['url'] ) ) {
						$demo_data['log_file'] = $log_file['url'];
					}
					do_action( 'astra_sites_import_start', $demo_data, $demo_api_uri );
				}

				wp_send_json_success( $demo_data );

			} else {
				wp_send_json_error( __( 'Request site API URL is empty. Try again!', 'astra-sites' ) );
			}

		}

		/**
		 * Import WP Forms
		 *
		 * @since 1.2.14
		 *
		 * @return void
		 */
		function import_wpforms() {

			$wpforms_url = ( isset( $_REQUEST['wpforms_url'] ) ) ? urldecode( $_REQUEST['wpforms_url'] ) : '';
			$ids_mapping = array();

			if ( ! empty( $wpforms_url ) && function_exists( 'wpforms_encode' ) ) {

				// Download XML file.
				$xml_path = Astra_Sites_Helper::download_file( $wpforms_url );

				if ( $xml_path['success'] ) {
					if ( isset( $xml_path['data']['file'] ) ) {

						$ext = strtolower( pathinfo( $xml_path['data']['file'], PATHINFO_EXTENSION ) );

						if ( 'json' === $ext ) {
							$forms = json_decode( file_get_contents( $xml_path['data']['file'] ), true );

							if ( ! empty( $forms ) ) {

								foreach ( $forms as $form ) {
									$title = ! empty( $form['settings']['form_title'] ) ? $form['settings']['form_title'] : '';
									$desc  = ! empty( $form['settings']['form_desc'] ) ? $form['settings']['form_desc'] : '';

									$new_id = post_exists( $title );

									if ( ! $new_id ) {
										$new_id = wp_insert_post(
											array(
												'post_title'   => $title,
												'post_status'  => 'publish',
												'post_type'    => 'wpforms',
												'post_excerpt' => $desc,
											)
										);

										// Set meta for tracking the post.
										update_post_meta( $new_id, '_astra_sites_imported_wp_forms', true );
										Astra_Sites_Image_Importer::log( '==== INSERTED - WP Form ' . $new_id );
									}

									if ( $new_id ) {

										// ID mapping.
										$ids_mapping[ $form['id'] ] = $new_id;

										$form['id'] = $new_id;
										wp_update_post(
											array(
												'ID' => $new_id,
												'post_content' => wpforms_encode( $form ),
											)
										);
									}
								}
							}
						}
					}
				}
			}

			update_option( 'astra_sites_wpforms_ids_mapping', $ids_mapping );

			wp_send_json_success( $ids_mapping );
		}

		/**
		 * Import Customizer Settings.
		 *
		 * @since 1.0.14
		 * @return void
		 */
		function import_customizer_settings() {

			$customizer_data = ( isset( $_POST['customizer_data'] ) ) ? (array) json_decode( stripcslashes( $_POST['customizer_data'] ), 1 ) : '';

			do_action( 'astra_sites_import_customizer_settings', $customizer_data );

			if ( ! empty( $customizer_data ) ) {

				// Set meta for tracking the post.
				Astra_Sites_Image_Importer::log( 'Customizer Data ' . stripslashes( $customizer_data ) );
				update_option( '_astra_sites_old_customizer_data', $customizer_data );

				Astra_Customizer_Import::instance()->import( $customizer_data );

				wp_send_json_success( $customizer_data );

			} else {
				wp_send_json_error( __( 'Customizer data is empty!', 'astra-sites' ) );
			}

		}

		/**
		 * Prepare XML Data.
		 *
		 * @since 1.1.0
		 * @return void
		 */
		function prepare_xml_data() {

			if ( ! class_exists( 'XMLReader' ) ) {
				wp_send_json_error( __( 'If XMLReader is not available, it imports all other settings and only skips XML import. This creates an incomplete website. We should bail early and not import anything if this is not present.', 'astra-sites' ) );
			}

			$wxr_url = ( isset( $_REQUEST['wxr_url'] ) ) ? urldecode( $_REQUEST['wxr_url'] ) : '';

			do_action( 'astra_sites_import_prepare_xml_data', $wxr_url );

			if ( isset( $wxr_url ) ) {

				// Download XML file.
				$xml_path = Astra_Sites_Helper::download_file( $wxr_url );

				if ( $xml_path['success'] ) {
					if ( isset( $xml_path['data']['file'] ) ) {
						$data        = Astra_WXR_Importer::instance()->get_xml_data( $xml_path['data']['file'] );
						$data['xml'] = $xml_path['data'];
						wp_send_json_success( $data );
					} else {
						wp_send_json_error( __( 'There was an error downloading the XML file.', 'astra-sites' ) );
					}
				} else {
					wp_send_json_error( $xml_path['data'] );
				}
			} else {
				wp_send_json_error( __( 'Invalid site XML file!', 'astra-sites' ) );
			}

		}

		/**
		 * Import Options.
		 *
		 * @since 1.0.14
		 * @return void
		 */
		function import_options() {

			$options_data = ( isset( $_POST['options_data'] ) ) ? (array) json_decode( stripcslashes( $_POST['options_data'] ), 1 ) : '';

			do_action( 'astra_sites_import_options', $options_data );

			if ( ! empty( $options_data ) ) {

				// Set meta for tracking the post.
				if ( is_array( $options_data ) ) {
					Astra_Sites_Image_Importer::log( 'Site Options Data ' . json_encode( $options_data ) );
					update_option( '_astra_sites_old_site_options', $options_data );
				}

				$options_importer = Astra_Site_Options_Import::instance();
				$options_importer->import_options( $options_data );
				wp_send_json_success( $options_data );
			} else {
				wp_send_json_error( __( 'Site options are empty!', 'astra-sites' ) );
			}

		}

		/**
		 * Import Widgets.
		 *
		 * @since 1.0.14
		 * @return void
		 */
		function import_widgets() {

			$widgets_data = ( isset( $_POST['widgets_data'] ) ) ? (object) json_decode( stripcslashes( $_POST['widgets_data'] ) ) : '';

			do_action( 'astra_sites_import_widgets', $widgets_data );

			if ( ! empty( $widgets_data ) ) {

				$widgets_importer = Astra_Widget_Importer::instance();
				$status           = $widgets_importer->import_widgets_data( $widgets_data );

				// Set meta for tracking the post.
				if ( is_object( $widgets_data ) ) {
					$widgets_data = (array) $widgets_data;
					Astra_Sites_Image_Importer::log( 'Widget Data ' . json_encode( $widgets_data ) );
					update_option( '_astra_sites_old_widgets_data', $widgets_data );
				}

				wp_send_json_success( $widgets_data );
			} else {
				wp_send_json_error( __( 'Widget data is empty!', 'astra-sites' ) );
			}

		}

		/**
		 * Import End.
		 *
		 * @since 1.0.14
		 * @return void
		 */
		function import_end() {
			do_action( 'astra_sites_import_complete' );
		}


		/**
		 * Get single demo.
		 *
		 * @since  1.0.0
		 *
		 * @param  (String) $demo_api_uri API URL of a demo.
		 *
		 * @return (Array) $astra_demo_data demo data for the demo.
		 */
		public static function get_astra_single_demo( $demo_api_uri ) {

			// default values.
			$remote_args = array();
			$defaults    = array(
				'id'                         => '',
				'astra-site-widgets-data'    => '',
				'astra-site-customizer-data' => '',
				'astra-site-options-data'    => '',
				'astra-post-data-mapping'    => '',
				'astra-site-wxr-path'        => '',
				'astra-site-wpforms-path'    => '',
				'astra-enabled-extensions'   => '',
				'astra-custom-404'           => '',
				'required-plugins'           => '',
			);

			$api_args = apply_filters(
				'astra_sites_api_args',
				array(
					'timeout' => 15,
				)
			);

			// Use this for premium demos.
			$request_params = apply_filters(
				'astra_sites_api_params',
				array(
					'purchase_key' => '',
					'site_url'     => '',
				)
			);

			$demo_api_uri = add_query_arg( $request_params, $demo_api_uri );

			// API Call.
			$response = wp_remote_get( $demo_api_uri, $api_args );

			if ( is_wp_error( $response ) || ( isset( $response->status ) && 0 == $response->status ) ) {
				if ( isset( $response->status ) ) {
					$data = json_decode( $response, true );
				} else {
					return new WP_Error( 'api_invalid_response_code', $response->get_error_message() );
				}
			} else {
				$data = json_decode( wp_remote_retrieve_body( $response ), true );
			}

			$data = json_decode( wp_remote_retrieve_body( $response ), true );

			if ( ! isset( $data['code'] ) ) {
				$remote_args['id']                         = $data['id'];
				$remote_args['astra-site-widgets-data']    = json_decode( $data['astra-site-widgets-data'] );
				$remote_args['astra-site-customizer-data'] = $data['astra-site-customizer-data'];
				$remote_args['astra-site-options-data']    = $data['astra-site-options-data'];
				$remote_args['astra-post-data-mapping']    = $data['astra-post-data-mapping'];
				$remote_args['astra-site-wxr-path']        = $data['astra-site-wxr-path'];
				$remote_args['astra-site-wpforms-path']    = $data['astra-site-wpforms-path'];
				$remote_args['astra-enabled-extensions']   = $data['astra-enabled-extensions'];
				$remote_args['astra-custom-404']           = $data['astra-custom-404'];
				$remote_args['required-plugins']           = $data['required-plugins'];
			}

			// Merge remote demo and defaults.
			return wp_parse_args( $remote_args, $defaults );
		}

		/**
		 * Clear Cache.
		 *
		 * @since  1.0.9
		 */
		public function clear_cache() {
			// Clear 'Elementor' file cache.
			if ( class_exists( '\Elementor\Plugin' ) ) {
				Elementor\Plugin::$instance->posts_css_manager->clear_cache();
			}

			// Clear 'Builder Builder' cache.
			if ( is_callable( 'FLBuilderModel::delete_asset_cache_for_all_posts' ) ) {
				FLBuilderModel::delete_asset_cache_for_all_posts();
			}

			// Clear 'Astra Addon' cache.
			if ( is_callable( 'Astra_Minify::refresh_assets' ) ) {
				Astra_Minify::refresh_assets();
			}

		}

		/**
		 * Reset customizer data
		 *
		 * @since x.x.x
		 * @return void
		 */
		function reset_customizer_data() {
			do_action( 'astra_sites_reset_customizer_data', get_option( 'astra-settings', array() ) );

			delete_option( 'astra-settings' );

			wp_send_json_success();
		}

		/**
		 * Reset site options
		 *
		 * @since x.x.x
		 * @return void
		 */
		function reset_site_options() {

			$options = get_option( '_astra_sites_old_site_options', array() );

			do_action( 'astra_sites_reset_site_options', $options );

			if ( $options ) {
				foreach ( $options as $option_key => $option_value ) {
					delete_option( $option_key );
				}
			}

			wp_send_json_success();
		}

		/**
		 * Reset widgets data
		 *
		 * @since x.x.x
		 * @return void
		 */
		function reset_widgets_data() {
			$old_widgets = get_option( '_astra_sites_old_widgets_data', array() );

			do_action( 'astra_sites_reset_widgets_data', $old_widgets );

			if ( $old_widgets ) {
				$sidebars_widgets = get_option( 'sidebars_widgets', array() );

				foreach ( $old_widgets as $sidebar_id => $widgets ) {

					if ( $widgets ) {
						foreach ( $widgets as $widget_key => $widget_data ) {

							if ( isset( $sidebars_widgets['wp_inactive_widgets'] ) ) {
								if ( ! in_array( $widget_key, $sidebars_widgets['wp_inactive_widgets'] ) ) {
									Astra_Sites_Image_Importer::log( '==== IN ACTIVATE - Widget ' . $widget_key );
									$sidebars_widgets['wp_inactive_widgets'][] = $widget_key;
								}
							}
						}
					}
				}

				update_option( 'sidebars_widgets', $sidebars_widgets );
			}

			wp_send_json_success();
		}

		/**
		 * Delete imported posts
		 *
		 * @since x.x.x
		 * @return void
		 */
		function delete_imported_posts() {
			$post_id = isset( $_REQUEST['post_id'] ) ? absint( $_REQUEST['post_id'] ) : '';

			do_action( 'astra_sites_delete_imported_posts', $post_id );

			if ( $post_id ) {
				wp_delete_post( $post_id, true );
			}

			/* translators: %s is the post ID */
			wp_send_json_success( sprintf( __( 'Post ID %s deleted!', 'astra-sites' ), $post_id ) );
		}

		/**
		 * Delete imported WP forms
		 *
		 * @since x.x.x
		 * @return void
		 */
		function delete_imported_wp_forms() {
			$post_id = isset( $_REQUEST['post_id'] ) ? absint( $_REQUEST['post_id'] ) : '';

			do_action( 'astra_sites_delete_imported_wp_forms', $post_id );

			if ( $post_id ) {
				wp_delete_post( $post_id, true );
			}

			/* translators: %s is the form ID */
			wp_send_json_success( sprintf( __( 'Form ID %s deleted!', 'astra-sites' ), $post_id ) );
		}

		/**
		 * Delete imported terms
		 *
		 * @since x.x.x
		 * @return void
		 */
		function delete_imported_terms() {

			$term_id = isset( $_REQUEST['term_id'] ) ? absint( $_REQUEST['term_id'] ) : '';

			if ( $term_id ) {
				$term = get_term( $term_id );
				if ( $term ) {
					do_action( 'astra_sites_delete_imported_terms', $term_id, $term );
					wp_delete_term( $term_id, $term->taxonomy );
				}
			}

			/* translators: %s is the term ID */
			wp_send_json_success( sprintf( __( 'Term ID %s deleted!', 'astra-sites' ), $term_id ) );
		}

	}

	/**
	 * Kicking this off by calling 'get_instance()' method
	 */
	Astra_Sites_Importer::get_instance();

endif;
