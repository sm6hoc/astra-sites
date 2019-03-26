<?php
/**
 * Astra Sites Page
 *
 * @since 1.0.6
 * @package Astra Sites
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Astra_Sites_Page' ) ) {

	/**
	 * Astra Admin Settings
	 */
	class Astra_Sites_Page {

		/**
		 * View all actions
		 *
		 * @since 1.0.6
		 * @var array $view_actions
		 */
		public $view_actions = array();

		/**
		 * Member Variable
		 *
		 * @var instance
		 */
		private static $instance;

		/**
		 * Initiator
		 *
		 * @since x.x.x
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
		public function __construct() {

			if ( ! is_admin() ) {
				return;
			}

			add_action( 'after_setup_theme', array( $this, 'init_admin_settings' ), 99 );
			add_action( 'admin_init', array( $this, 'save_page_builder' ) );
		}

		// /**
		// * Help Tabs
		// *
		// * @return void
		// */
		// function help_tabs() {
		// $screen = get_current_screen();
		// $screen->add_help_tab(
		// array(
		// 'id'      => 'astra_sites_change_page_builder',
		// 'title'   => __( 'Change Page Builder' ),
		// translators: %s is change page builder link
		// 'content' => '<p>' . sprintf( __( 'Do you want to change selected page builder? Then click on <a href="%s">Set Another Page Builder</a>.', 'astra-sites' ), admin_url( 'themes.php?page=astra-sites&change-page-builder' ) ) . '</p>',
		// )
		// );
		// }

		/**
		 * Save Page Builder
		 *
		 * @return void
		 */
		function save_page_builder() {

			// Only admins can save settings.
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			// Make sure we have a valid nonce.
			if ( isset( $_REQUEST['astra-sites-page-builder'] ) && wp_verify_nonce( $_REQUEST['astra-sites-page-builder'], 'astra-sites-welcome-screen' ) ) {

				// Stored Settings.
				$stored_data = $this->get_settings();

				// New settings.
				$new_data = array(
					'page_builder' => ( isset( $_REQUEST['page_builder'] ) ) ? sanitize_key( $_REQUEST['page_builder'] ) : '',
				);

				// Merge settings.
				$data = wp_parse_args( $new_data, $stored_data );

				// Update settings.
				update_option( 'astra_sites_settings', $data );

				wp_redirect( admin_url( '/themes.php?page=astra-sites' ) );
			}
		}

		/**
		 * Get single setting value
		 *
		 * @param  string $key      Setting key.
		 * @param  mixed  $defaults Setting value.
		 * @return mixed           Stored setting value.
		 */
		function get_setting( $key = '', $defaults = '' ) {

			$settings = $this->get_settings();

			if ( empty( $settings ) ) {
				return $defaults;
			}

			if ( array_key_exists( $key, $settings ) ) {
				return $settings[ $key ];
			}

			return $defaults;
		}

		/**
		 * Get Settings
		 *
		 * @return array Stored settings.
		 */
		function get_settings() {

			$defaults = array(
				'page_builder' => '',
			);

			$stored_data = get_option( 'astra_sites_settings', $defaults );

			return wp_parse_args( $stored_data, $defaults );
		}

		/**
		 * Admin settings init
		 */
		public function init_admin_settings() {
			add_action( 'admin_menu', array( $this, 'add_admin_menu' ), 100 );
			add_action( 'admin_notices', array( $this, 'notices' ) );
			add_action( 'astra_sites_menu_general_action', array( $this, 'general_page' ) );
		}

		/**
		 * Admin notice
		 *
		 * @since 1.2.8
		 */
		public function notices() {

			if ( 'appearance_page_astra-sites' !== get_current_screen()->id ) {
				return;
			}

			if ( ! class_exists( 'XMLReader' ) ) {
				?>
				<div class="notice astra-sites-xml-notice notice-error">
					<p><b><?php _e( 'Required XMLReader PHP extension is missing on your server!', 'astra-sites' ); ?></b></p>
					<?php /* translators: %s is the white label name. */ ?>
					<p><?php printf( __( '%s import requires XMLReader extension to be installed. Please contact your web hosting provider and ask them to install and activate the XMLReader PHP extension.', 'astra-sites' ), ASTRA_SITES_NAME ); ?></p>
				</div>
				<?php
			}
		}

		/**
		 * Init Nav Menu
		 *
		 * @param mixed $action Action name.
		 * @since 1.0.6
		 */
		public function init_nav_menu( $action = '' ) {

			if ( '' !== $action ) {
				$this->render_tab_menu( $action );
			}
		}

		/**
		 * Render tab menu
		 *
		 * @param mixed $action Action name.
		 * @since 1.0.6
		 */
		public function render_tab_menu( $action = '' ) {
			?>
			<div id="astra-sites-menu-page">
				<?php $this->render( $action ); ?>
			</div>
			<?php
		}

		/**
		 * View actions
		 *
		 * @since 1.0.11
		 */
		public function get_view_actions() {

			if ( empty( $this->view_actions ) ) {

				$this->view_actions = apply_filters(
					'astra_sites_menu_item',
					array()
				);
			}

			return $this->view_actions;
		}

		/**
		 * Prints HTML content for tabs
		 *
		 * @param mixed $action Action name.
		 * @since 1.0.6
		 */
		public function render( $action ) {

			// Settings update message.
			if ( isset( $_REQUEST['message'] ) && ( 'saved' == $_REQUEST['message'] || 'saved_ext' == $_REQUEST['message'] ) ) {
				?>
					<span id="message" class="notice notice-success is-dismissive"><p> <?php esc_html_e( 'Settings saved successfully.', 'astra-sites' ); ?> </p></span>
				<?php
			}

			?>










			<div class="container">
			  <div class="row">
			    <div class="col-12@sm">
			      <h1>Shuffle homepage demo</h1>
			    </div>
			  </div>
			</div>

			<div class="container">
			  <div class="row">
			    <div class="col-4@sm col-3@md">
			      <div class="filters-group">
			        <label for="filters-search-input" class="filter-label">Search</label>
			        <input class="textfield filter__search js-shuffle-search" type="search" id="filters-search-input" />
			      </div>
			    </div>
			  </div>
			  <div class="row">
			    <div class="col-12@sm filters-group-wrap">
			      <div class="filters-group">
			        <p class="filter-label">Filter</p>
			        <div class="btn-group filter-options">
			          <button class="btn btn--primary" data-group="space">Space</button>
			          <button class="btn btn--primary" data-group="nature">Nature</button>
			          <button class="btn btn--primary" data-group="animal">Animal</button>
			          <button class="btn btn--primary" data-group="city">City</button>
			        </div>
			      </div>
			      <fieldset class="filters-group">
			        <legend class="filter-label">Sort</legend>
			        <div class="btn-group sort-options">
			          <label class="btn active">
			            <input type="radio" name="sort-value" value="dom" checked /> Default
			          </label>
			          <label class="btn">
			            <input type="radio" name="sort-value" value="title" /> Title
			          </label>
			          <label class="btn">
			            <input type="radio" name="sort-value" value="date-created" /> Date Created
			          </label>
			        </div>
			      </fieldset>
			    </div>
			  </div>
			</div>

			<div class="container">
			  <div id="grid" class="row my-shuffle-container">
			    <figure class="col-3@xs col-4@sm col-3@md picture-item" data-groups='["nature"]' data-date-created="2017-04-30" data-title="Lake Walchen">
			      <div class="picture-item__inner">
			        <div class="aspect aspect--16x9">
			          <div class="aspect__inner">
			            <img src="https://images.unsplash.com/photo-1493585552824-131927c85da2?ixlib=rb-0.3.5&auto=format&q=80&fm=jpg&crop=entropy&cs=tinysrgb&w=284&h=160&fit=crop&s=6ef0f8984525fc4500d43ffa53fe8190" srcset="https://images.unsplash.com/photo-1493585552824-131927c85da2?ixlib=rb-0.3.5&auto=format&q=80&fm=jpg&crop=entropy&cs=tinysrgb&w=284&h=160&fit=crop&s=6ef0f8984525fc4500d43ffa53fe8190 1x, https://images.unsplash.com/photo-1493585552824-131927c85da2?ixlib=rb-0.3.5&auto=format&q=55&fm=jpg&dpr=2&crop=entropy&cs=tinysrgb&w=284&h=160&fit=crop&s=6ef0f8984525fc4500d43ffa53fe8190 2x"
			              alt="A deep blue lake sits in the middle of vast hills covered with evergreen trees" />
			          </div>
			        </div>
			        <div class="picture-item__details">
			          <figcaption class="picture-item__title"><a href="https://unsplash.com/photos/zshyCr6HGw0" target="_blank" rel="noopener">Lake Walchen</a></figcaption>
			          <p class="picture-item__tags hidden@xs">nature</p>
			        </div>
			      </div>
			    </figure>
			    <figure class="col-3@xs col-8@sm col-6@md picture-item picture-item--overlay" data-groups='["city"]' data-date-created="2016-07-01" data-title="Golden Gate Bridge">
			      <div class="picture-item__inner">

			        <img src="https://images.unsplash.com/photo-1467348733814-f93fc480bec6?ixlib=rb-0.3.5&auto=format&q=80&fm=jpg&crop=entropy&cs=tinysrgb&w=584&h=329&fit=crop&s=2590c736835ec6555e952e19bb37f06e" srcset="https://images.unsplash.com/photo-1467348733814-f93fc480bec6?ixlib=rb-0.3.5&auto=format&q=80&fm=jpg&crop=entropy&cs=tinysrgb&w=584&h=329&fit=crop&s=2590c736835ec6555e952e19bb37f06e 1x, https://images.unsplash.com/photo-1467348733814-f93fc480bec6?ixlib=rb-0.3.5&auto=format&q=55&fm=jpg&dpr=2&crop=entropy&cs=tinysrgb&w=584&h=329&fit=crop&s=2590c736835ec6555e952e19bb37f06e 2x"
			          alt="Looking down over one of the pillars of the Golden Gate Bridge to the roadside and water below" />
			        <div class="picture-item__details">
			          <figcaption class="picture-item__title"><a href="https://unsplash.com/photos/RRNbMiPmTZY" target="_blank" rel="noopener">Golden Gate Bridge</a></figcaption>
			          <p class="picture-item__tags hidden@xs">city</p>
			        </div>
			      </div>
			    </figure>
			    <figure class="col-3@xs col-4@sm col-3@md picture-item" data-groups='["animal"]' data-date-created="2016-08-12" data-title="Crocodile">
			      <div class="picture-item__inner">
			        <div class="aspect aspect--16x9">
			          <div class="aspect__inner">
			            <img src="https://images.unsplash.com/photo-1471005197911-88e9d4a7834d?ixlib=rb-0.3.5&auto=format&q=80&fm=jpg&crop=entropy&cs=tinysrgb&w=284&h=160&fit=crop&s=bd8b952c4c983d4bde5e2018c90c9124" srcset="https://images.unsplash.com/photo-1471005197911-88e9d4a7834d?ixlib=rb-0.3.5&auto=format&q=80&fm=jpg&crop=entropy&cs=tinysrgb&w=284&h=160&fit=crop&s=bd8b952c4c983d4bde5e2018c90c9124 1x, https://images.unsplash.com/photo-1471005197911-88e9d4a7834d?ixlib=rb-0.3.5&auto=format&q=55&fm=jpg&dpr=2&crop=entropy&cs=tinysrgb&w=284&h=160&fit=crop&s=bd8b952c4c983d4bde5e2018c90c9124 2x"
			              alt="A close, profile view of a crocodile looking directly into the camera" />
			          </div>
			        </div>
			        <div class="picture-item__details">
			          <figcaption class="picture-item__title"><a href="https://unsplash.com/photos/YOX8ZMTo7hk" target="_blank" rel="noopener">Crocodile</a></figcaption>
			          <p class="picture-item__tags hidden@xs">animal</p>
			        </div>
			      </div>
			    </figure>
			    <figure class="col-3@xs col-4@sm col-3@md picture-item picture-item--h2" data-groups='["space"]' data-date-created="2016-03-07" data-title="SpaceX">
			      <div class="picture-item__inner">
			        <div class="aspect aspect--16x9">
			          <div class="aspect__inner">
			            <img src="https://images.unsplash.com/photo-1457364559154-aa2644600ebb?ixlib=rb-0.3.5&auto=format&q=80&fm=jpg&crop=entropy&cs=tinysrgb&w=284&h=160&fit=crop&s=3d0e3e8d72fc5667fd9fbe354e80957b" srcset="https://images.unsplash.com/photo-1457364559154-aa2644600ebb?ixlib=rb-0.3.5&auto=format&q=80&fm=jpg&crop=entropy&cs=tinysrgb&w=284&h=160&fit=crop&s=3d0e3e8d72fc5667fd9fbe354e80957b 1x, https://images.unsplash.com/photo-1457364559154-aa2644600ebb?ixlib=rb-0.3.5&auto=format&q=55&fm=jpg&dpr=2&crop=entropy&cs=tinysrgb&w=284&h=160&fit=crop&s=3d0e3e8d72fc5667fd9fbe354e80957b 2x"
			              alt="SpaceX launches a Falcon 9 rocket from Cape Canaveral Air Force Station" />
			          </div>
			        </div>
			        <div class="picture-item__details">
			          <figcaption class="picture-item__title"><a href="https://unsplash.com/photos/GDdRP7U5ct0" target="_blank" rel="noopener">SpaceX</a></figcaption>
			          <p class="picture-item__tags hidden@xs">space</p>
			        </div>
			        <p class="picture-item__description">SpaceX launches a Falcon 9 rocket from Cape Canaveral Air Force Station</p>
			      </div>
			    </figure>
			    <figure class="col-3@xs col-4@sm col-3@md picture-item" data-groups='["city"]' data-date-created="2016-06-09" data-title="Crossroads">
			      <div class="picture-item__inner">
			        <div class="aspect aspect--16x9">
			          <div class="aspect__inner">
			            <img src="https://images.unsplash.com/photo-1465447142348-e9952c393450?ixlib=rb-0.3.5&auto=format&q=80&fm=jpg&crop=entropy&cs=tinysrgb&w=284&h=160&fit=crop&s=7d97e22d36a9a73beb639a936e6774e9" srcset="https://images.unsplash.com/photo-1465447142348-e9952c393450?ixlib=rb-0.3.5&auto=format&q=80&fm=jpg&crop=entropy&cs=tinysrgb&w=284&h=160&fit=crop&s=7d97e22d36a9a73beb639a936e6774e9 1x, https://images.unsplash.com/photo-1465447142348-e9952c393450?ixlib=rb-0.3.5&auto=format&q=55&fm=jpg&dpr=2&crop=entropy&cs=tinysrgb&w=284&h=160&fit=crop&s=7d97e22d36a9a73beb639a936e6774e9 2x"
			              alt="A multi-level highway stack interchange in Puxi, Shanghai" />
			          </div>
			        </div>
			        <div class="picture-item__details">
			          <figcaption class="picture-item__title"><a href="https://unsplash.com/photos/7nrsVjvALnA" target="_blank" rel="noopener">Crossroads</a></figcaption>
			          <p class="picture-item__tags hidden@xs">city</p>
			        </div>
			      </div>
			    </figure>
			    <figure class="col-6@xs col-8@sm col-6@md picture-item picture-item--overlay" data-groups='["space","nature"]' data-date-created="2016-06-29" data-title="Milky Way">
			      <div class="picture-item__inner">

			        <img src="https://images.unsplash.com/photo-1467173572719-f14b9fb86e5f?ixlib=rb-0.3.5&auto=format&q=80&fm=jpg&crop=entropy&cs=tinysrgb&w=584&h=329&fit=crop&s=e641d6b3c4c2c967e80e998d02a4d03b" srcset="https://images.unsplash.com/photo-1467173572719-f14b9fb86e5f?ixlib=rb-0.3.5&auto=format&q=80&fm=jpg&crop=entropy&cs=tinysrgb&w=584&h=329&fit=crop&s=e641d6b3c4c2c967e80e998d02a4d03b 1x, https://images.unsplash.com/photo-1467173572719-f14b9fb86e5f?ixlib=rb-0.3.5&auto=format&q=55&fm=jpg&dpr=2&crop=entropy&cs=tinysrgb&w=584&h=329&fit=crop&s=e641d6b3c4c2c967e80e998d02a4d03b 2x"
			          alt="Dimly lit mountains give way to a starry night showing the Milky Way" />
			        <div class="picture-item__details">
			          <figcaption class="picture-item__title"><a href="https://unsplash.com/photos/_4Ib-a8g9aA" target="_blank" rel="noopener">Milky Way</a></figcaption>
			          <p class="picture-item__tags hidden@xs">space, nature</p>
			        </div>
			      </div>
			    </figure>
			    <figure class="col-6@xs col-8@sm col-6@md picture-item picture-item--h2" data-groups='["space"]' data-date-created="2015-11-06" data-title="Earth">
			      <div class="picture-item__inner">
			        <div class="aspect aspect--16x9">
			          <div class="aspect__inner">
			            <img src="https://images.unsplash.com/photo-1446776811953-b23d57bd21aa?ixlib=rb-0.3.5&auto=format&q=80&fm=jpg&crop=entropy&cs=tinysrgb&w=584&h=329&fit=crop&s=f4856588634def31d5885dc396fe9a2e" srcset="https://images.unsplash.com/photo-1446776811953-b23d57bd21aa?ixlib=rb-0.3.5&auto=format&q=80&fm=jpg&crop=entropy&cs=tinysrgb&w=584&h=329&fit=crop&s=f4856588634def31d5885dc396fe9a2e 1x, https://images.unsplash.com/photo-1446776811953-b23d57bd21aa?ixlib=rb-0.3.5&auto=format&q=55&fm=jpg&dpr=2&crop=entropy&cs=tinysrgb&w=584&h=329&fit=crop&s=f4856588634def31d5885dc396fe9a2e 2x"
			              alt="NASA Satellite view of Earth" />
			          </div>
			        </div>
			        <div class="picture-item__details">
			          <figcaption class="picture-item__title"><a href="https://unsplash.com/photos/yZygONrUBe8" target="_blank" rel="noopener">Earth</a></figcaption>
			          <p class="picture-item__tags hidden@xs">space</p>
			        </div>
			        <p class="picture-item__description">NASA Satellite view of Earth</p>
			      </div>
			    </figure>
			    <figure class="col-3@xs col-4@sm col-3@md picture-item picture-item--h2" data-groups='["animal"]' data-date-created="2015-07-23" data-title="Turtle">
			      <div class="picture-item__inner">
			        <div class="aspect aspect--16x9">
			          <div class="aspect__inner">
			            <img src="https://images.unsplash.com/photo-1437622368342-7a3d73a34c8f?ixlib=rb-0.3.5&auto=format&q=80&fm=jpg&crop=entropy&cs=tinysrgb&w=284&h=160&fit=crop&s=bc4e1180b6b8789d38c614edc8d0dd01" srcset="https://images.unsplash.com/photo-1437622368342-7a3d73a34c8f?ixlib=rb-0.3.5&auto=format&q=80&fm=jpg&crop=entropy&cs=tinysrgb&w=284&h=160&fit=crop&s=bc4e1180b6b8789d38c614edc8d0dd01 1x, https://images.unsplash.com/photo-1437622368342-7a3d73a34c8f?ixlib=rb-0.3.5&auto=format&q=55&fm=jpg&dpr=2&crop=entropy&cs=tinysrgb&w=284&h=160&fit=crop&s=bc4e1180b6b8789d38c614edc8d0dd01 2x"
			              alt="A close up of a turtle underwater" />
			          </div>
			        </div>
			        <div class="picture-item__details">
			          <figcaption class="picture-item__title"><a href="https://unsplash.com/photos/L-2p8fapOA8" target="_blank" rel="noopener">Turtle</a></figcaption>
			          <p class="picture-item__tags hidden@xs">animal</p>
			        </div>
			        <p class="picture-item__description">A close up of a turtle underwater</p>
			      </div>
			    </figure>
			    <figure class="col-3@xs col-4@sm col-3@md picture-item" data-groups='["nature"]' data-date-created="2014-10-12" data-title="Stanley Park">
			      <div class="picture-item__inner">
			        <div class="aspect aspect--16x9">
			          <div class="aspect__inner">
			            <img src="https://images.unsplash.com/uploads/1413142095961484763cf/d141726c?ixlib=rb-0.3.5&auto=format&q=80&fm=jpg&crop=entropy&cs=tinysrgb&w=284&h=160&fit=crop&s=6141097da144d759176d77b4024c064b" srcset="https://images.unsplash.com/uploads/1413142095961484763cf/d141726c?ixlib=rb-0.3.5&auto=format&q=80&fm=jpg&crop=entropy&cs=tinysrgb&w=284&h=160&fit=crop&s=6141097da144d759176d77b4024c064b 1x, https://images.unsplash.com/uploads/1413142095961484763cf/d141726c?ixlib=rb-0.3.5&auto=format&q=55&fm=jpg&dpr=2&crop=entropy&cs=tinysrgb&w=284&h=160&fit=crop&s=6141097da144d759176d77b4024c064b 2x"
			              alt="Many trees stand alonside a hill which overlooks a pedestrian path, next to the ocean at Stanley Park in Vancouver, Canada" />
			          </div>
			        </div>
			        <div class="picture-item__details">
			          <figcaption class="picture-item__title"><a href="https://unsplash.com/photos/b-yEdfrvQ50" target="_blank" rel="noopener">Stanley Park</a></figcaption>
			          <p class="picture-item__tags hidden@xs">nature</p>
			        </div>
			      </div>
			    </figure>
			    <figure class="col-3@xs col-4@sm col-3@md picture-item" data-groups='["animal"]' data-date-created="2017-01-12" data-title="Astronaut Cat">
			      <div class="picture-item__inner">
			        <div class="aspect aspect--16x9">
			          <div class="aspect__inner">
			            <img src="https://images.unsplash.com/photo-1484244233201-29892afe6a2c?ixlib=rb-0.3.5&auto=format&q=80&fm=jpg&crop=entropy&cs=tinysrgb&w=284&h=160&fit=crop&s=98423596f72d9f0913a4d44f0580a34c" srcset="https://images.unsplash.com/photo-1484244233201-29892afe6a2c?ixlib=rb-0.3.5&auto=format&q=80&fm=jpg&crop=entropy&cs=tinysrgb&w=284&h=160&fit=crop&s=98423596f72d9f0913a4d44f0580a34c 1x, https://images.unsplash.com/photo-1484244233201-29892afe6a2c?ixlib=rb-0.3.5&auto=format&q=55&fm=jpg&dpr=2&crop=entropy&cs=tinysrgb&w=284&h=160&fit=crop&s=98423596f72d9f0913a4d44f0580a34c 2x"
			              alt="An intrigued cat sits in grass next to a flag planted in front of it with an astronaut space kitty sticker on beige fabric." />
			          </div>
			        </div>
			        <div class="picture-item__details">
			          <figcaption class="picture-item__title"><a href="https://unsplash.com/photos/FqkBXo2Nkq0" target="_blank" rel="noopener">Astronaut Cat</a></figcaption>
			          <p class="picture-item__tags hidden@xs">animal</p>
			        </div>
			      </div>
			    </figure>
			    <figure class="col-3@xs col-8@sm col-6@md picture-item picture-item--overlay" data-groups='["city"]' data-date-created="2017-01-19" data-title="San Francisco">
			      <div class="picture-item__inner">

			        <img src="https://images.unsplash.com/photo-1484851050019-ca9daf7736fb?ixlib=rb-0.3.5&auto=format&q=80&fm=jpg&crop=entropy&cs=tinysrgb&w=584&h=329&fit=crop&s=05325a7cc678f7f765cbbdcf7159ab89" srcset="https://images.unsplash.com/photo-1484851050019-ca9daf7736fb?ixlib=rb-0.3.5&auto=format&q=80&fm=jpg&crop=entropy&cs=tinysrgb&w=584&h=329&fit=crop&s=05325a7cc678f7f765cbbdcf7159ab89 1x, https://images.unsplash.com/photo-1484851050019-ca9daf7736fb?ixlib=rb-0.3.5&auto=format&q=55&fm=jpg&dpr=2&crop=entropy&cs=tinysrgb&w=584&h=329&fit=crop&s=05325a7cc678f7f765cbbdcf7159ab89 2x"
			          alt="Pier 14 at night, looking towards downtown San Francisco's brightly lit buildings" />
			        <div class="picture-item__details">
			          <figcaption class="picture-item__title"><a href="https://unsplash.com/photos/h3jarbNzlOg" target="_blank" rel="noopener">San Francisco</a></figcaption>
			          <p class="picture-item__tags hidden@xs">city</p>
			        </div>
			      </div>
			    </figure>
			    <figure class="col-3@xs col-4@sm col-3@md picture-item" data-groups='["nature","city"]' data-date-created="2015-10-20" data-title="Central Park">
			      <div class="picture-item__inner">
			        <div class="aspect aspect--16x9">
			          <div class="aspect__inner">
			            <img src="https://images.unsplash.com/photo-1445346366695-5bf62de05412?ixlib=rb-0.3.5&auto=format&q=80&fm=jpg&crop=entropy&cs=tinysrgb&w=284&h=160&fit=crop&s=1822bfd69c4021973a3d926e9294b70f" srcset="https://images.unsplash.com/photo-1445346366695-5bf62de05412?ixlib=rb-0.3.5&auto=format&q=80&fm=jpg&crop=entropy&cs=tinysrgb&w=284&h=160&fit=crop&s=1822bfd69c4021973a3d926e9294b70f 1x, https://images.unsplash.com/photo-1445346366695-5bf62de05412?ixlib=rb-0.3.5&auto=format&q=55&fm=jpg&dpr=2&crop=entropy&cs=tinysrgb&w=284&h=160&fit=crop&s=1822bfd69c4021973a3d926e9294b70f 2x"
			              alt="Looking down on central park and the surrounding builds from the Rockefellar Center" />
			          </div>
			        </div>
			        <div class="picture-item__details">
			          <figcaption class="picture-item__title"><a href="https://unsplash.com/photos/utwYoEu9SU8" target="_blank" rel="noopener">Central Park</a></figcaption>
			          <p class="picture-item__tags hidden@xs">nature, city</p>
			        </div>
			      </div>
			    </figure>
			    <div class="col-1@sm col-1@xs my-sizer-element"></div>
			  </div>
			</div>




			<?php

			if( false ) {
				$default_page_builder = $this->get_setting( 'page_builder' );

				if ( empty( $default_page_builder ) ) {
					?>
					<div class="astra-sites-welcome">
						<div class="inner">
							<form id="astra-sites-welcome-form" enctype="multipart/form-data" method="post">
								<h1>Select Page Builder</h1>
								<p class="description">Select your favorite page builder to import sites or individual pages.</p>
								<div class="fields">
									<select name="page_builder" required="required">
										<option value="">Select</option>
										<option value="elementor" <?php selected( $default_page_builder, 'elementor' ); ?>>Elementor</option>
										<option value="beaver-builder" <?php selected( $default_page_builder, 'beaver-builder' ); ?>>Beaver Builder</option>
										<option value="brizy" <?php selected( $default_page_builder, 'brizy' ); ?>>Brizy</option>
										<option value="gutenberg" <?php selected( $default_page_builder, 'gutenberg' ); ?>>Gutenberg</option>
									</select>
									<?php submit_button(); ?>
								</div>
								<input type="hidden" name="message" value="saved" />
								<?php wp_nonce_field( 'astra-sites-welcome-screen', 'astra-sites-page-builder' ); ?>
							</form>
						</div>
					</div>
				<?php } else { ?>
					<?php
					$page_title = apply_filters( 'astra_sites_page_title', __( 'Astra Starter Sites - Your Library of 100+ Ready Templates!', 'astra-sites' ) );
					?>
					<div class="nav-tab-wrapper">
						<h1 class='astra-sites-title'> <?php echo esc_html( $page_title ); ?> </h1>
						<form id="astra-sites-welcome-form-inline" enctype="multipart/form-data" method="post">
							<div class="fields">
								<label><strong>Current Page Builder</strong></label>
								<select name="page_builder" required="required">
									<option value="">Select</option>
									<option value="elementor" <?php selected( $default_page_builder, 'elementor' ); ?>>Elementor</option>
									<option value="beaver-builder" <?php selected( $default_page_builder, 'beaver-builder' ); ?>>Beaver Builder</option>
									<option value="brizy" <?php selected( $default_page_builder, 'brizy' ); ?>>Brizy</option>
									<option value="gutenberg" <?php selected( $default_page_builder, 'gutenberg' ); ?>>Gutenberg</option>
								</select>
							</div>
							<input type="hidden" name="message" value="saved" />
							<?php wp_nonce_field( 'astra-sites-welcome-screen', 'astra-sites-page-builder' ); ?>
						</form>
						<?php
						$view_actions = $this->get_view_actions();

						foreach ( $view_actions as $slug => $data ) {

							if ( ! $data['show'] ) {
								continue;
							}

							$url = $this->get_page_url( $slug );

							if ( 'general' == $slug ) {
								update_option( 'astra_parent_page_url', $url );
							}

							$active = ( $slug == $action ) ? 'nav-tab-active' : '';
							?>
								<a class='nav-tab <?php echo esc_attr( $active ); ?>' href='<?php echo esc_url( $url ); ?>'> <?php echo esc_html( $data['label'] ); ?> </a>
						<?php } ?>
					</div><!-- .nav-tab-wrapper -->
					<?php
				}
			}
		}

		/**
		 * Get and return page URL
		 *
		 * @param string $menu_slug Menu name.
		 * @since 1.0.6
		 * @return  string page url
		 */
		public function get_page_url( $menu_slug ) {

			$parent_page = 'themes.php';

			if ( strpos( $parent_page, '?' ) !== false ) {
				$query_var = '&page=astra-sites';
			} else {
				$query_var = '?page=astra-sites';
			}

			$parent_page_url = admin_url( $parent_page . $query_var );

			$url = $parent_page_url . '&action=' . $menu_slug;

			return esc_url( $url );
		}

		/**
		 * Add main menu
		 *
		 * @since 1.0.6
		 */
		public function add_admin_menu() {
			$page_title = apply_filters( 'astra_sites_menu_page_title', __( 'Astra Sites', 'astra-sites' ) );

			$page = add_theme_page( $page_title, $page_title, 'manage_options', 'astra-sites', array( $this, 'menu_callback' ) );
		}

		/**
		 * Menu callback
		 *
		 * @since 1.0.6
		 */
		public function menu_callback() {

			$current_slug = isset( $_GET['action'] ) ? esc_attr( $_GET['action'] ) : 'general';

			$active_tab   = str_replace( '_', '-', $current_slug );
			$current_slug = str_replace( '-', '_', $current_slug );

			?>
			<div class="astra-sites-menu-page-wrapper">
				<?php $this->init_nav_menu( $active_tab ); ?>
				<?php do_action( 'astra_sites_menu_' . esc_attr( $current_slug ) . '_action' ); ?>
			</div>
			<?php
		}

		/**
		 * Include general page
		 *
		 * @since 1.0.6
		 */
		public function general_page() {
			$default_page_builder = $this->get_setting( 'page_builder' );
			if ( empty( $default_page_builder ) || isset( $_GET['change-page-builder'] ) ) {
				return;
			}
			require_once ASTRA_SITES_DIR . 'inc/includes/admin-page.php';
		}
	}

	Astra_Sites_Page::get_instance();

}// End if.
