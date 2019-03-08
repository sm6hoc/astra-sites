<?php
/**
 * Batch Processing
 *
 * @package Astra Sites
 * @since x.x.x
 */

if ( ! class_exists( 'Astra_Sites_Batch_Processing_Brizy' ) ) :

	/**
	 * Astra Sites Batch Processing Brizy
	 *
	 * @since x.x.x
	 */
	class Astra_Sites_Batch_Processing_Brizy {

		/**
		 * Instance
		 *
		 * @since x.x.x
		 * @access private
		 * @var object Class object.
		 */
		private static $instance;

		/**
		 * Initiator
		 *
		 * @since x.x.x
		 * @return object initialized object of class.
		 */
		public static function get_instance() {

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
		public function __construct() {}

		/**
		 * Import
		 *
		 * @since x.x.x
		 * @return void
		 */
		public function import() {

			Astra_Sites_Image_Importer::log( '---- Processing WordPress Posts / Pages - for "Brizy" ----' );

			if ( ! is_callable( 'Brizy_Editor_Storage_Common::instance' ) ) {
				return;
			}

			$post_types = Brizy_Editor_Storage_Common::instance()->get( 'post-types' );
			if ( empty( $post_types ) && ! is_array( $post_types ) ) {
				return;
			}

			$post_ids = Astra_Sites_Batch_Processing::get_pages( $post_types );
			if ( empty( $post_ids ) && ! is_array( $post_ids ) ) {
				return;
			}

			foreach ( $post_ids as $post_id ) {
				$is_brizy_post = get_post_meta( $post_id, 'brizy_post_uid', true );
				if ( $is_brizy_post ) {
					$this->import_single_post( $post_id );
				}
			}
		}

		/**
		 * Update post meta.
		 *
		 * @param  integer $post_id Post ID.
		 * @return void
		 */
		public function import_single_post( $post_id = 0 ) {

			$ids_mapping = get_option( 'astra_sites_wpforms_ids_mapping', array() );

			// Empty mapping? Then return.
			if ( empty( $ids_mapping ) ) {
				return;
			}

			$json_value = null;
			$instance   = Brizy_Editor_Storage_Post::instance( $post_id );
			$data       = $instance->get( Brizy_Editor_Post::BRIZY_POST, false );

			// Decode current data.
			$json_value = base64_decode( $data['editor_data'] );

			// Replace ID's.
			foreach ( $ids_mapping as $old_id => $new_id ) {
				$json_value = str_replace( '[wpforms id=\"' . $old_id, '[wpforms id=\"' . $new_id, $json_value );
			}

			// Encode modified data.
			$data['editor_data'] = base64_encode( $json_value );

			// Update data.
			if ( is_object( $json_value ) ) {
				$data->set_editor_data( $json_value );
			}

			$instance->set( Brizy_Editor_Post::BRIZY_POST, $data );

			// Save data.
			$post = new Brizy_Admin_Migrations_PostStorage( $post_id );

			$post->save();
		}

	}

	/**
	 * Kicking this off by calling 'get_instance()' method
	 */
	Astra_Sites_Batch_Processing_Brizy::get_instance();

endif;
