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
