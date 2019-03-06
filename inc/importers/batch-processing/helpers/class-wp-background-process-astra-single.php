<?php
/**
 * Image Background Process
 *
 * @package Astra Sites
 * @since 1.0.11
 */

if ( class_exists( 'WP_Background_Process' ) ) :

	/**
	 * Image Background Process
	 *
	 * @since 1.0.11
	 */
	class WP_Background_Process_Astra_Single extends WP_Background_Process {

		/**
		 * Image Process
		 *
		 * @var string
		 */
		protected $action = 'astra_sites_single_page';

		/**
		 * Task
		 *
		 * Override this method to perform any actions required on each
		 * queue item. Return the modified item for further processing
		 * in the next pass through. Or, return false to remove the
		 * item from the queue.
		 *
		 * @since 1.0.11
		 *
		 * @param object $process Queue item object.
		 * @return mixed
		 */
		protected function task( $page_id ) {

			error_log('Importing ' . $page_id);

			$import = new \Elementor\TemplateLibrary\Astra_Sites_Batch_Processing_Elementor();

			$import->import_single_post( $page_id );

			return false;
		}

		/**
		 * Complete
		 *
		 * Override if applicable, but ensure that the below actions are
		 * performed, or, call parent::complete().
		 *
		 * @since 1.0.11
		 */
		protected function complete() {

			error_log('Complete');

			parent::complete();

			do_action( 'astra_sites_image_import_complete' );

		}

	}

endif;
