<?php
/**
 * Elementor Importer
 *
 * @package CARTFLOWS
 */

namespace Elementor\TemplateLibrary;

use Elementor\Core\Base\Document;
use Elementor\DB;
use Elementor\Core\Settings\Page\Manager as PageSettingsManager;
use Elementor\Core\Settings\Manager as SettingsManager;
use Elementor\Core\Settings\Page\Model;
use Elementor\Editor;
use Elementor\Plugin;
use Elementor\Settings;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor template library local source.
 *
 * Elementor template library local source handler class is responsible for
 * handling local Elementor templates saved by the user locally on his site.
 *
 * @since 1.0.0
 */
class Astra_Sites_Batch_Processing_Elementor extends Source_Local {

	/**
	 * Import
	 *
	 * @since 1.0.14
	 * @return void
	 */
	public function import() {

		\Astra_Sites_Image_Importer::log( '---- Processing WordPress Posts / Pages - for Elementor ----' );

		$post_ids = \Astra_Sites_Batch_Processing::get_pages();
		if ( is_array( $post_ids ) ) {
			foreach ( $post_ids as $post_id ) {
				$this->import_single_post( $post_id );
			}
		}

	}

	/**
	 * Update post meta.
	 *
	 * @since 1.0.14
	 * @param  integer $post_id Post ID.
	 * @return void
	 */
	public function import_single_post( $post_id = 0 ) {

		\Astra_Sites_Image_Importer::log( 'Post ID: ' . $post_id );

		if ( ! empty( $post_id ) ) {

			$hotlink_imported = get_post_meta( $post_id, '_astra_sites_hotlink_imported', true );

			if ( empty( $hotlink_imported ) ) {

				$data = get_post_meta( $post_id, '_elementor_data', true );

				if ( ! empty( $data ) ) {

					$data = add_magic_quotes( $data );
					$data = json_decode( $data, true );

					// $data = json_decode( $data, true );
					// $data = $this->replace_elements_ids( $data );
					// $data = $this->process_export_import_content( $data, 'on_import' );
					// Import the data.
					$content = $this->process_export_import_content( $content, 'on_import' );

					// Update processed meta.
					update_metadata( 'post', $post_id, '_elementor_data', $data );
					update_metadata( 'post', $post_id, '_astra_sites_hotlink_imported', true );

					// !important, Clear the cache after images import.
					Plugin::$instance->posts_css_manager->clear_cache();

				}
			}
		}

	}
}
