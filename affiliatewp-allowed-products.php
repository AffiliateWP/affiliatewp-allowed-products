<?php
/**
 * Plugin Name: AffiliateWP - Allowed Products
 * Plugin URI: http://affiliatewp.com/
 * Description: Allows only specific products to generate commission
 * Author: Pippin Williamson and Andrew Munro
 * Author URI: http://affiliatewp.com
 * Version: 1.0
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
