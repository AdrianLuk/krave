<?php
require_once CODEROCKZ_WOO_DELIVERY_DIR . 'includes/class-coderockz-woo-delivery-helper.php';
if( !class_exists( 'Coderockz_Woo_Delivery_Delivery_Option' ) ) {

	class Coderockz_Woo_Delivery_Delivery_Option {

		public static function delivery_option($delivery_option_settings) {
			
			$helper = new Coderockz_Woo_Delivery_Helper();
			$timezone = $helper->get_the_timezone();
			date_default_timezone_set($timezone);
			
			$disable_delivery_facility = (isset($delivery_option_settings['disable_delivery_facility']) && !empty($delivery_option_settings['disable_delivery_facility'])) ? $delivery_option_settings['disable_delivery_facility'] : array();
			$disable_pickup_facility = (isset($delivery_option_settings['disable_pickup_facility']) && !empty($delivery_option_settings['disable_pickup_facility'])) ? $delivery_option_settings['disable_pickup_facility'] : array();
			$delivery_field_label = (isset($delivery_option_settings['delivery_label']) && !empty($delivery_option_settings['delivery_label'])) ? stripslashes($delivery_option_settings['delivery_label']) : "Delivery";
			$pickup_field_label = (isset($delivery_option_settings['pickup_label']) && !empty($delivery_option_settings['pickup_label'])) ? stripslashes($delivery_option_settings['pickup_label']) : "Pickup";

			$current_week_day = date("w");

			$delivery_option = [];

			$delivery_option[''] = '';

			$cart_total = $helper->cart_total();

			$enable_delivery_restriction = (isset($delivery_option_settings['enable_delivery_restriction']) && !empty($delivery_option_settings['enable_delivery_restriction'])) ? $delivery_option_settings['enable_delivery_restriction'] : false;
			$minimum_amount = (isset($delivery_option_settings['minimum_amount_cart_restriction']) && $delivery_option_settings['minimum_amount_cart_restriction'] != "") ? (float)$delivery_option_settings['minimum_amount_cart_restriction'] : "";

		    if( $enable_delivery_restriction && $minimum_amount != "" && $cart_total < $minimum_amount){
		    	$hide_delivery = true;
		    } else {
		    	$hide_delivery = false;
		    }

			if(!in_array($current_week_day, $disable_delivery_facility) && !$hide_delivery) {
				$delivery_option['delivery'] = __( $delivery_field_label, 'coderockz-woo-delivery' );
			}

			if(!in_array($current_week_day, $disable_pickup_facility)) {
				$delivery_option['pickup'] = __( $pickup_field_label, 'coderockz-woo-delivery' );
			}
			
			return $delivery_option;
		}
	}
}