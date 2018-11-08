<?php
/*
 * Plugin Name: WooCommerce Subscriptions - Auto Add Resubscribe Coupon
 * Plugin URI: https://github.com/Prospress/woocommerce-subscriptions-auto-add-resubscribe-coupon/blob/master/README.md
 * Description: This plugin automatically adds a specific coupon to every resubscribe cart.
 * Author: Prospress Inc.
 * Author URI: https://prospress.com/
 * License: GPLv3
 * Version: 1.0.0
 * Requires at least: 4.0
 * Tested up to: 4.8
 *
 * GitHub Plugin URI: Prospress/{plugin_slug}
 * GitHub Branch: master
 *
 * Copyright 2018 Prospress, Inc.  (email : freedoms@prospress.com)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package		WooCommerce Subscriptions Auto Add Resubscribe Coupon
 * @author		Prospress Inc.
 * @since		1.0
 */

require_once( 'includes/class-pp-dependencies.php' );

if ( false === PP_Dependencies::is_woocommerce_active( '3.0' ) ) {
	PP_Dependencies::enqueue_admin_notice( 'WooCommerce Subscriptions - Auto Add Resubscribe Coupon', 'WooCommerce', '3.0' );
	return;
}

if ( false === PP_Dependencies::is_subscriptions_active( '2.1' ) ) {
	PP_Dependencies::enqueue_admin_notice( 'WooCommerce Subscriptions - Auto Add Resubscribe Coupon', 'WooCommerce Subscriptions', '2.1' );
	return;
}


/**
 * Class WCS_Auto_Resubscribe_Coupon.
 *
 * @since 1.0
 */
class WCS_Auto_Resubscribe_Coupon {

	/**
	 * Add cart hook.
	 */
	static function init() {
		add_action( 'woocommerce_before_calculate_totals', [ __CLASS__, 'add_coupon_to_cart' ] );
	}

	/**
	 * Adds a coupon to the cart is the cart contains a resubscribe.
	 * We don't want to prevent the coupon from being added if it's not a resubscribe cart.
	 */
	static function add_coupon_to_cart() {
		$coupon = self::get_coupon_code();

		if ( ! $coupon || WC()->cart->is_empty() || WC()->cart->has_discount( $coupon ) || ! wcs_cart_contains_resubscribe() ) {
			return;
		}

		WC()->cart->apply_coupon( $coupon );
	}

	/**
	 * @return bool|string
	 */
	static function get_coupon_code() {
		if ( defined( 'WOOCOMMERCE_SUBSCRIPTIONS_AUTO_RESUBSCRIBE_COUPON' ) ) {
			return WOOCOMMERCE_SUBSCRIPTIONS_AUTO_RESUBSCRIBE_COUPON;
		}
		return false;
	}


}

WCS_Auto_Resubscribe_Coupon::init();
