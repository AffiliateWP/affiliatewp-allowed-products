<?php
/**
 * Plugin Name: AffiliateWP - Allowed Products
 * Plugin URI: http://affiliatewp.com/
 * Description: Allows only specific products to generate commission
 * Author: Pippin Williamson and Andrew Munro
 * Author URI: http://affiliatewp.com
 * Version: 1.0.2
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
 if( ! defined( 'ABSPATH' ) ) exit;

 if ( ! class_exists( 'AffiliateWP_Allowed_Products' ) ) {

 	final class AffiliateWP_Allowed_Products {

 		/**
 		 * Holds the instance
 		 *
 		 * Ensures that only one instance of AffiliateWP_Allowed_Products exists in memory at any one
 		 * time and it also prevents needing to define globals all over the place.
 		 *
 		 * TL;DR This is a static property property that holds the singleton instance.
 		 *
 		 * @var object
 		 * @static
 		 * @since 1.0.3
 		 */
 		private static $instance;

 		/**
 		 * The version number of Allowed Products
 		 *
 		 * @since 1.0.3
 		 */
 		private $version = '1.0.3';

 		/**
 		 * Main AffiliateWP_Allowed_Products Instance
 		 *
 		 * Insures that only one instance of AffiliateWP_Allowed_Products exists in memory at any one
 		 * time. Also prevents needing to define globals all over the place.
 		 *
 		 * @since 1.0.3
 		 * @static
 		 * @static var array $instance
 		 * @return The one true AffiliateWP_Allowed_Products
 		 */
 		public static function instance() {
 			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof AffiliateWP_Allowed_Products ) ) {

 				self::$instance = new AffiliateWP_Allowed_Products;
 				self::$instance->setup_constants();
 				self::$instance->load_textdomain();
 				self::$instance->includes();
 				self::$instance->hooks();

 			}

 			return self::$instance;
 		}

 		/**
 		 * Throw error on object clone
 		 *
 		 * The whole idea of the singleton design pattern is that there is a single
 		 * object therefore, we don't want the object to be cloned.
 		 *
 		 * @since 1.0.3
 		 * @access protected
 		 * @return void
 		 */
 		public function __clone() {
 			// Cloning instances of the class is forbidden
 			_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'affiliatewp-allowed-products' ), '1.0' );
 		}

 		/**
 		 * Disable unserializing of the class
 		 *
 		 * @since 1.0.3
 		 * @access protected
 		 * @return void
 		 */
 		public function __wakeup() {
 			// Unserializing instances of the class is forbidden
 			_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'affiliatewp-allowed-products' ), '1.0' );
 		}

 		/**
 		 * Constructor Function
 		 *
 		 * @since 1.0.3
 		 * @access private
 		 */
 		private function __construct() {
 			self::$instance = $this;
 		}

 		/**
 		 * Reset the instance of the class
 		 *
 		 * @since 1.0.3
 		 * @access public
 		 * @static
 		 */
 		public static function reset() {
 			self::$instance = null;
 		}

 		/**
 		 * Setup plugin constants
 		 *
 		 * @access private
 		 * @since 1.0.3
 		 * @return void
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
 		 * Loads the plugin language files
 		 *
 		 * @access public
 		 * @since 1.0.3
 		 * @return void
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
 	}

 	/**
 	 * The main function responsible for returning the one true AffiliateWP_Allowed_Products
 	 * Instance to functions everywhere.
 	 *
 	 * Use this function like you would a global variable, except without needing
 	 * to declare the global.
 	 *
 	 * Example: <?php $affwp_ap = AffiliateWP_Allowed_Products(); ?>
 	 *
 	 * @since 1.0.3
 	 * @return object The one true AffiliateWP_Allowed_Products Instance
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

/**
 * Filter the referral amounts
 *
 * @since 1.0
 */
function affwp_allowed_products_calc_referral_amount( $referral_amount, $affiliate_id, $amount, $reference, $product_id ) {

	if ( $product_id != in_array( $product_id, affwp_allowed_products_get_products() ) ) {
		return 0.00;
	}

    return $referral_amount;
}
add_filter( 'affwp_calc_referral_amount', 'affwp_allowed_products_calc_referral_amount', 10, 5 );

/**
 * Get allowed products
 *
 * @since 1.0
 */
function affwp_allowed_products_get_products() {

	$products = affiliate_wp()->settings->get( 'allowed_products' );
	$products = explode( ',', $products );
	$products = array_filter( array_map( 'trim', $products ) );

	return $products;
}

/**
 * Allow product IDs to be entered from Affiliate -> Settings -> Integrations
 *
 * @since 1.0
 */
function affwp_allowed_products_settings( $fields ) {

	$fields['allowed_products'] = array(
		'name' => __( 'Allowed Products', 'affiliatewp-allowed-products' ),
		'desc' => '<p class="description">' . __( 'Enter any product IDs (separated by commas) that should be allowed to generate commission.', 'affiliatewp-allowed-products' ) . '</p>',
		'type' => 'text'
	);

	return $fields;
}
add_filter( 'affwp_settings_integrations', 'affwp_allowed_products_settings' );

/**
 * Sanitize settings field
 *
 * @since 1.0
 */
function affwp_allowed_products_sanitize_settings( $input ) {

	$input['allowed_products'] = sanitize_text_field( $input['allowed_products'] );

	return $input;
}
add_filter( 'affwp_settings_integrations_sanitize', 'affwp_allowed_products_sanitize_settings' );

/**
 * Prevent the referral notification email from being sent to the affiliate if the product is blocked from receiving commission
 *
 * @since 1.0.1
 */
function affwp_allowed_products_notify_on_new_referral( $return, $referral ) {

	$products = $referral->products;

	// get an array of the product IDs being purchased
	$product_ids = wp_list_pluck( $products, 'id' );

	if ( $product_ids ) {

		foreach ( $product_ids as $id ) {

			// check to see if one of the product IDs exists in the allowed products array.
			// If found, send the email
			if ( in_array( $id, affwp_allowed_products_get_products() ) ) {
				$return = true;
				break;
			} else {
				// don't send the email
				$return = false;
			}

		}

	}

	return $return;
}
add_filter( 'affwp_notify_on_new_referral', 'affwp_allowed_products_notify_on_new_referral', 10, 2 );

/**
 * Show a dismissable notice when no product IDs have been entered
 *
 * @since 1.0.2
 */
function affwp_allowed_products_admin_notice() {

	$has_dismissed = get_user_meta( get_current_user_id(), '_affwp_no_allowed_products_dismissed', true );

    if ( ! affwp_allowed_products_get_products() && ! $has_dismissed ) { ?>
        <div class="error notice">
            <p><?php echo sprintf( __( 'All products are blocked from generating commission, as no product IDs have been entered for the <a href="%s" target="_blank">Allowed Products</a> add-on. <a href="%s">Enter product IDs</a> to generate commission for specific products. ', 'affiliatewp-allowed-products' ), 'https://affiliatewp.com/addons/allowed-products/', admin_url( 'admin.php?page=affiliate-wp-settings&tab=integrations' ) ) ?></p>
			<p><a href="<?php echo wp_nonce_url( add_query_arg( array( 'affwp_action' => 'dismiss_notices', 'affwp_notice' => 'no_allowed_products' ) ), 'affwp_dismiss_notice', 'affwp_dismiss_notice_nonce' ); ?>"><?php _e( 'Dismiss Notice', 'affiliate-wp' ); ?></a></p>
        </div>
    <?php }
}
add_action( 'admin_notices', 'affwp_allowed_products_admin_notice' );
