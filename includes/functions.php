<?php
/**
 * Functions for Allowed Products.
 *
 * @since 1.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Filters the product rates for supported products.
 *
 * If the product ID is not in the allowed list and the current integration is supported,
 * the rate will be set to 0.
 *
 * @since 1.1
 *
 * @param float  $rate         Product-level referral rate.
 * @param int    $product_id   Product ID.
 * @param array  $args         Arguments for retrieving the product rate.
 * @param int    $affiliate_id Affilaite ID.
 * @param string $context      Order context.
 *
 * @return int
 */
function affwp_allowed_products_get_product_rate( $rate, $product_id, $args, $affiliate_id, $context ) {

	if ( ! in_array( $product_id, affwp_allowed_products_get_products() )
		&& in_array( $context, affwp_allowed_products_supported_integrations(), true )
	) {
		$rate = 0;
	}

	return $rate;

}
add_filter( 'affwp_get_product_rate', 'affwp_allowed_products_get_product_rate', 100, 5 );

/**
 * Retrieves the list of allowed products.
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
 * Allows product IDs to be entered from Affiliate -> Settings -> Integrations
 *
 * @since 1.0
 */
function affwp_allowed_products_settings( $fields ) {

	$fields['allowed_products'] = array(
		'name' => __( 'Allowed Products', 'affiliatewp-allowed-products' ),
		'desc' => '<p class="description">' . __( 'Enter any product IDs (separated by commas) that should be allowed to generate commissions.', 'affiliatewp-allowed-products' ) . '</p>',
		'type' => 'text'
	);

	return $fields;
}
add_filter( 'affwp_settings_integrations', 'affwp_allowed_products_settings' );

/**
 * Sanitizes the settings field.
 *
 * @since 1.0
 */
function affwp_allowed_products_sanitize_settings( $input ) {

	$input['allowed_products'] = sanitize_text_field( $input['allowed_products'] );

	return $input;
}
add_filter( 'affwp_settings_integrations_sanitize', 'affwp_allowed_products_sanitize_settings' );

/**
 * Prevents the referral notification email from being sent to the affiliate
 * if the product is blocked from receiving commission
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
 * Shows a dismissible notice when no product IDs have been entered
 *
 * @since 1.0.2
 */
function affwp_allowed_products_admin_notice() {

	$has_dismissed = get_user_meta( get_current_user_id(), '_affwp_no_allowed_products_dismissed', true );

    if ( ! affwp_allowed_products_get_products() && ! $has_dismissed ) { ?>
        <div class="error notice">
            <p><?php echo sprintf( __( 'There are currently no products configured to generate commissions. Visit the <a href="%s">Integrations</a> screen to enter some product IDs under the Allowed Products section.', 'affiliatewp-allowed-products' ), esc_url( admin_url( 'admin.php?page=affiliate-wp-settings&tab=integrations' ) ) ); ?></p>
			<p><a href="<?php echo wp_nonce_url( add_query_arg( array( 'affwp_action' => 'dismiss_notices', 'affwp_notice' => 'no_allowed_products' ) ), 'affwp_dismiss_notice', 'affwp_dismiss_notice_nonce' ); ?>"><?php _e( 'Dismiss Notice', 'affiliate-wp' ); ?></a></p>
        </div>
    <?php }
}
add_action( 'admin_notices', 'affwp_allowed_products_admin_notice' );

/**
 * Handles marking the allowed products notice as dismissed.
 *
 * @since 1.1
 *
 * @param string $notice Notice ID.
 */
function affwp_allowed_products_mark_notice_dismissed( $notice ) {
	if ( 'no_allowed_products' === $notice ) {
		update_user_meta( get_current_user_id(), '_affwp_no_allowed_products_dismissed', 1 );
	}
}
add_action( 'affwp_dismiss_notices_default', 'affwp_allowed_products_mark_notice_dismissed' );

/**
 * Retrieves a list of integrations that support Allowed Products.
 *
 * @since 1.1
 *
 * @return array List of supported integrations.
 */
function affwp_allowed_products_supported_integrations() {
	return array( 'edd', 'woocommerce', 'memberpress' );
}
