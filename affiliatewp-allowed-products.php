<?php
/**
 * Plugin Name: AffiliateWP - Allowed Products
 * Plugin URI: http://affiliatewp.com/
 * Description: Allows only specific products to generate commission
 * Author: AffiliateWP, LLC
 * Author URI: http://affiliatewp.com
 * Version: 1.1.2
 *
 * AffiliateWP is distributed under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * AffiliateWP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with AffiliateWP. If not, see <http://www.gnu.org/licenses/>.
 */

 // Exit if accessed directly
 if( ! defined( 'ABSPATH' ) ) {
 	exit;
 }

 if ( ! class_exists( 'AffiliateWP_Allowed_Products' ) ) {

	 /**
	  * AffiliateWP - Allowed Products add-on.
	  *
	  * @since 1.0
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
		private $version = '1.1.2';

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
		public static function instance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof AffiliateWP_Allowed_Products ) ) {

				self::$instance = new AffiliateWP_Allowed_Products;
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
				define( 'AFFWP_AP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
			}

			// Plugin Folder URL
			if ( ! defined( 'AFFWP_AP_PLUGIN_URL' ) ) {
				define( 'AFFWP_AP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
			}

			// Plugin Root File
			if ( ! defined( 'AFFWP_AP_PLUGIN_FILE' ) ) {
				define( 'AFFWP_AP_PLUGIN_FILE', __FILE__ );
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
			$lang_dir = dirname( plugin_basename( __FILE__ ) ) . '/languages/';
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
	    if ( ! class_exists( 'Affiliate_WP' ) ) {

	        if ( ! class_exists( 'AffiliateWP_Activation' ) ) {
	            require_once 'includes/class-activation.php';
	        }

			// AffiliateWP activation
            if ( ! class_exists( 'Affiliate_WP' ) ) {
    			$activation = new AffiliateWP_Activation( plugin_dir_path( __FILE__ ), basename( __FILE__ ) );
    			$activation = $activation->run();
    		}

	    } else {

	        return AffiliateWP_Allowed_Products::instance();

	    }
	}
	add_action( 'plugins_loaded', 'affiliatewp_allowed_products', 100 );

}
