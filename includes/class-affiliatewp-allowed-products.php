<?php
/**
 * Core: Plugin Bootstrap
 *
 * @package     AffiliateWP Allowed Products
 * @subpackage  Core
 * @copyright   Copyright (c) 2021, Sandhills Development, LLC
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.2
 */

 // Exit if accessed directly
 if( ! defined( 'ABSPATH' ) ) {
 	exit;
 }

 if ( ! class_exists( 'AffiliateWP_Allowed_Products' ) ) {

	/**
	 * Main plugin bootstrap.
	 *
	 * @since 1.0.0
	 */
	final class AffiliateWP_Allowed_Products {

		/**
		 * Holds the instance
		 *
		 * Ensures that only one instance of AffiliateWP_Allowed_Products exists in memory at any one
		 * time and it also prevents needing to define globals all over the place.
		 *
		 * TL;DR This is a static property property that holds the singleton instance.
		 *
		 * @access private
		 * @since  1.1
		 * @var    \AffiliateWP_Allowed_Products
		 * @static
		 */
		private static $instance;

		/**
		 * The version number of Allowed Products.
		 *
		 * @access private
		 * @since  1.1
		 * @var    string
		 */
		private $version = '1.1.3';

		/**
		 * Main plugin file.
		 *
		 * @since 1.0.0
		 * @var   string
		 */
		private $file = '';

		/**
		 * Main AffiliateWP_Allowed_Products Instance.
		 *
		 * Insures that only one instance of AffiliateWP_Allowed_Products exists in memory at any one
		 * time. Also prevents needing to define globals all over the place.
		 *
		 * @access public
		 * @since  1.1
		 * @static
		 *
		 * @return \AffiliateWP_Allowed_Products The one true AffiliateWP_Allowed_Products instance.
		 */
		public static function instance( $file = null ) {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof AffiliateWP_Allowed_Products ) ) {

				self::$instance = new AffiliateWP_Allowed_Products;
				self::$instance->file = $file;
				self::$instance->setup_constants();
				self::$instance->load_textdomain();
				self::$instance->includes();

			}

			return self::$instance;
		}

		/**
		 * Throws error on object clone.
		 *
		 * The whole idea of the singleton design pattern is that there is a single
		 * object therefore, we don't want the object to be cloned.
		 *
		 * @access public
		 * @since  1.1
		 */
		public function __clone() {
			// Cloning instances of the class is forbidden
			_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'affiliatewp-allowed-products' ), '1.0' );
		}

		/**
		 * Disables unserializing of the class.
		 *
		 * @access public
		 * @since  1.1
		 *
		 * @return void
		 */
		public function __wakeup() {
			// Unserializing instances of the class is forbidden
			_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'affiliatewp-allowed-products' ), '1.0' );
		}

		/**
		 * Runs during class start-up.
		 *
		 * @access private
		 * @since  1.1
		 */
		private function __construct() {
			self::$instance = $this;
		}

		/**
		 * Resets the instance of the class.
		 *
		 * @access public
		 * @since 1.1
		 * @static
		 */
		public static function reset() {
			self::$instance = null;
		}

		/**
		 * Sets up plugin constants.
		 *
		 * @access private
		 * @since  1.1
		 */
		private function setup_constants() {

			// Plugin version
			if ( ! defined( 'AFFWP_AP_VERSION' ) ) {
				define( 'AFFWP_AP_VERSION', $this->version );
			}

			// Plugin Folder Path
			if ( ! defined( 'AFFWP_AP_PLUGIN_DIR' ) ) {
				define( 'AFFWP_AP_PLUGIN_DIR', plugin_dir_path( $this->file ) );
			}

			// Plugin Folder URL
			if ( ! defined( 'AFFWP_AP_PLUGIN_URL' ) ) {
				define( 'AFFWP_AP_PLUGIN_URL', plugin_dir_url( $this->file ) );
			}

			// Plugin Root File
			if ( ! defined( 'AFFWP_AP_PLUGIN_FILE' ) ) {
				define( 'AFFWP_AP_PLUGIN_FILE', $this->file );
			}

		}

		/**
		 * Loads the plugin language files.
		 *
		 * @access public
		 * @since  1.1
		 */
		public function load_textdomain() {

			// Set filter for plugin's languages directory
			$lang_dir = dirname( plugin_basename( $this->file ) ) . '/languages/';
			$lang_dir = apply_filters( 'affwp_ap_languages_directory', $lang_dir );

			// Traditional WordPress plugin locale filter
			$locale   = apply_filters( 'plugin_locale',  get_locale(), 'affiliatewp-allowed-products' );
			$mofile   = sprintf( '%1$s-%2$s.mo', 'affiliatewp-allowed-products', $locale );

			// Setup paths to current locale file
			$mofile_local  = $lang_dir . $mofile;
			$mofile_global = WP_LANG_DIR . '/affiliatewp-allowed-products/' . $mofile;

			if ( file_exists( $mofile_global ) ) {
				// Look in global /wp-content/languages/affiliatewp-allowed-products/ folder
				load_textdomain( 'affiliatewp-allowed-products', $mofile_global );
			} elseif ( file_exists( $mofile_local ) ) {
				// Look in local /wp-content/plugins/affiliatewp-allowed-products/languages/ folder
				load_textdomain( 'affiliatewp-allowed-products', $mofile_local );
			} else {
				// Load the default language files
				load_plugin_textdomain( 'affiliatewp-allowed-products', false, $lang_dir );
			}
		}

        /**
		 * Includes necessary files.
		 *
         * @access private
         * @since  1.1
		 */
		private function includes() {

			require_once AFFWP_AP_PLUGIN_DIR . 'includes/functions.php';

		}
	}

	/**
	 * The main function responsible for returning the one true AffiliateWP_Allowed_Products
	 * Instance to functions everywhere.
	 *
	 * Use this function like you would a global variable, except without needing
	 * to declare the global.
	 *
	 * Example: <?php $affwp_ap = affiliatewp_allowed_products(); ?>
	 *
	 * @since 1.1
	 *
	 * @return \AffiliateWP_Allowed_Products The one true AffiliateWP_Allowed_Products Instance.
	 */
	function affiliatewp_allowed_products() {
		return AffiliateWP_Allowed_Products::instance();
	}

}
