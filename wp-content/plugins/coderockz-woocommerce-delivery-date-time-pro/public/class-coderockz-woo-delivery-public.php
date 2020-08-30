<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://coderockz.com
 * @since      1.0.0
 *
 * @package    Coderockz_Woo_Delivery
 * @subpackage Coderockz_Woo_Delivery/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Coderockz_Woo_Delivery
 * @subpackage Coderockz_Woo_Delivery/public
 * @author     CodeRockz <admin@coderockz.com>
 */
class Coderockz_Woo_Delivery_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	public $helper;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->helper = new Coderockz_Woo_Delivery_Helper();
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Coderockz_Woo_Delivery_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Coderockz_Woo_Delivery_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		if(is_checkout()){
			$delivery_date_settings = get_option('coderockz_woo_delivery_date_settings');
			$delivery_date_calendar_theme = (isset($delivery_date_settings['calendar_theme']) && $delivery_date_settings['calendar_theme'] != "") ? $delivery_date_settings['calendar_theme'] : "";
			wp_enqueue_style( "flatpickr_css", plugin_dir_url( __FILE__ ) . 'css/flatpickr.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/coderockz-woo-delivery-public.css', array(), $this->version, 'all' );
			if($delivery_date_calendar_theme != "") {
				wp_enqueue_style( "flatpickr_calendar_theme_css", plugin_dir_url( __FILE__ ) .'css/calendar-themes/' . $delivery_date_calendar_theme.'.css', array(), $this->version, 'all' );
			}
		}

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Coderockz_Woo_Delivery_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Coderockz_Woo_Delivery_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		
		if(is_checkout()){
			$delivery_date_settings = get_option('coderockz_woo_delivery_date_settings');
			$delivery_date_calendar_locale = (isset($delivery_date_settings['calendar_locale']) && !empty($delivery_date_settings['calendar_locale'])) ? $delivery_date_settings['calendar_locale'] : "default";

			$pickup_date_settings = get_option('coderockz_woo_delivery_pickup_date_settings');
			$pickup_date_calendar_locale = (isset($pickup_date_settings['calendar_locale']) && !empty($pickup_date_settings['calendar_locale'])) ? $pickup_date_settings['calendar_locale'] : "default";

			wp_enqueue_script( "flatpickr_js", plugin_dir_url( __FILE__ ) . 'js/flatpickr.min.js', [], $this->version, true );

			wp_enqueue_script( "flatpickr_locale_js", 'https://npmcdn.com/flatpickr/dist/l10n/'.$delivery_date_calendar_locale.'.js', ["flatpickr_js"], $this->version, true );


			if($pickup_date_calendar_locale != $delivery_date_calendar_locale) {
				wp_enqueue_script( "flatpickr_pickup_locale_js", 'https://npmcdn.com/flatpickr/dist/l10n/'.$pickup_date_calendar_locale.'.js', ["flatpickr_js"], $this->version, true );
			}

			/*wp_enqueue_script( "select2_js", plugin_dir_url( __FILE__ ) . 'js/select2.min.js', ['jquery'], $this->version, true );*/

			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/coderockz-woo-delivery-public.js', array( 'jquery','selectWoo', 'flatpickr_js', 'flatpickr_locale_js' ), $this->version, true );
		}
		$coderockz_woo_delivery_nonce = wp_create_nonce('coderockz_woo_delivery_nonce');
	        wp_localize_script($this->plugin_name, 'coderockz_woo_delivery_ajax_obj', array(
	            'coderockz_woo_delivery_ajax_url' => admin_url('admin-ajax.php'),
	            'nonce' => $coderockz_woo_delivery_nonce,
	        ));

	}

		// This function adds the delivery time and delivery date fields and it's functionalities
	public function coderockz_woo_delivery_add_custom_field() {

		/* Start of Extra Code to adjust pickup date */

		if(get_option('coderockz_woo_delivery_date_time_change') == false) {
		global $wpdb;
		$plugin_options = $wpdb->get_results( "SELECT option_name FROM $wpdb->options WHERE option_name LIKE '%_needs_processing'" );

		foreach( $plugin_options as $option ) {
		    delete_option( $option->option_name );
		}

		if (get_option('coderockz_woo_delivery_notify_email_settings')  == false) {
			update_option( 'cron', '' );
			$notify_email_settings = get_option('coderockz_woo_delivery_reminder_email_settings') != false ? get_option('coderockz_woo_delivery_reminder_email_settings') : "";
			update_option( 'coderockz_woo_delivery_notify_email_settings' , $notify_email_settings);
		}
		
		if (get_option('coderockz_woo_delivery_reminder_email_settings')  != false) {
			delete_option( 'coderockz_woo_delivery_reminder_email_settings' );
		}

		$delivery_date_settings = get_option('coderockz_woo_delivery_date_settings');
		$enable_delivery_date = (isset($delivery_date_settings['enable_delivery_date']) && !empty($delivery_date_settings['enable_delivery_date'])) ? $delivery_date_settings['enable_delivery_date'] : false;
		$pickup_date_settings = get_option('coderockz_woo_delivery_pickup_date_settings');
		$enable_pickup_date = (isset($pickup_date_settings['enable_pickup_date']) && !empty($pickup_date_settings['enable_pickup_date'])) ? $pickup_date_settings['enable_pickup_date'] : false;

    	$timezone = $this->helper->get_the_timezone();
		date_default_timezone_set($timezone);
		$today = date('Y-m-d', time());
		$formated = date('Y-m-d H:i:s', strtotime($today));
		$formated_obj = new DateTime($formated);
		$last_day = $formated_obj->modify("+30 day")->format("Y-m-d");
		$filtered_date = $today .' - '. $last_day;
		$filtered_dates = explode(' - ', $filtered_date);
		$period = new DatePeriod(new DateTime($filtered_dates[0]), new DateInterval('P1D'), new DateTime($filtered_dates[1].' +1 day'));
		$dates = [];
	    foreach ($period as $date) {
	        $dates[] = $date->format("Y-m-d");
	    }
	    foreach ($dates as $date) {

	    	if($enable_delivery_date) {
	    		$args = array(
			        'limit' => -1,
			        'delivery_date' => strtotime(date('Y-m-d', strtotime($date))),
			        'delivery_type' => "delivery",
			        'return' => 'ids'
			    );
	    	} else {

	    		$args = array(
			        'limit' => -1,
			        'date_created' => date('Y-m-d', strtotime($date)),
			        'delivery_type' => "delivery",
			        'return' => 'ids'
			    );
	    		
	    	}
    		
	    	
		    $orders_array = wc_get_orders( $args );
		    foreach ($orders_array as $order_id) {
		    	if(metadata_exists('post', $order_id, 'delivery_date') && get_post_meta($order_id, 'delivery_date', true) !="") {
		    		if(strpos(get_post_meta($order_id, 'delivery_date', true),'-') !== false) {

		    		} else {
		    			update_post_meta($order_id, 'delivery_date', date('Y-m-d', get_post_meta($order_id, 'delivery_date', true)));
		    		}

		    	}

		    	if(metadata_exists('post', $order_id, 'delivery_time') && get_post_meta($order_id, 'delivery_time', true) !="" && get_post_meta($order_id, 'delivery_time', true) !="as-soon-as-possible") {

		    		if(strpos(get_post_meta($order_id, 'delivery_time', true),',') !== false) {
		    			$minutes = explode(',', get_post_meta($order_id, 'delivery_time', true));
		    			if($minutes[1] == "") {
		    				$new_time = date("H:i", mktime(0, (int)$minutes[0]));
		    			} else {
		    				$new_time = date("H:i", mktime(0, (int)$minutes[0])) . ' - ' . date("H:i", mktime(0, (int)$minutes[1]));
		    			}

		    			update_post_meta($order_id, 'delivery_time', $new_time);
		    		}

		    		

		    	}
		    }

		    if($enable_pickup_date) {
			    $pickupargs = array(
			        'limit' => -1,
			        'pickup_date' => strtotime(date('Y-m-d', strtotime($date))),
			        'delivery_type' => "pickup",
			        'return' => 'ids'
			    );
			} else {
				$pickupargs = array(
			        'limit' => -1,
			        'date_created' => date('Y-m-d', strtotime($date)),
			        'delivery_type' => "pickup",
			        'return' => 'ids'
			    );
			}
	    	
		    $pickuporders_array = wc_get_orders( $pickupargs );
		    foreach ($pickuporders_array as $pickuporder_id) {
		    	if(metadata_exists('post', $pickuporder_id, 'pickup_date') && get_post_meta($pickuporder_id, 'pickup_date', true) !="") {
		    		if(strpos(get_post_meta($pickuporder_id, 'pickup_date', true),'-') !== false) {

		    		} else {
		    			update_post_meta($pickuporder_id, 'pickup_date', date('Y-m-d', get_post_meta($pickuporder_id, 'pickup_date', true)));
		    		}

		    	}

		    	if(metadata_exists('post', $pickuporder_id, 'pickup_time') && get_post_meta($pickuporder_id, 'pickup_time', true) !="") {

		    		if(strpos(get_post_meta($pickuporder_id, 'pickup_time', true),',') !== false) {
		    			$pickupminutes = explode(',', get_post_meta($pickuporder_id, 'pickup_time', true));
		    			if($pickupminutes[1] == "") {
		    				$pickupnew_time = date("H:i", mktime(0, (int)$pickupminutes[0]));
		    			} else {
		    				$pickupnew_time = date("H:i", mktime(0, (int)$pickupminutes[0])) . ' - ' . date("H:i", mktime(0, (int)$pickupminutes[1]));
		    			}

		    			update_post_meta($pickuporder_id, 'pickup_time', $pickupnew_time);
		    		}

		    		

		    	}
		    }
	    }

	    $today = date('Y-m-d', time());
		$formated = date('Y-m-d H:i:s', strtotime($today));
		$formated_obj = new DateTime($formated);
	    $previous_last_day = $formated_obj->modify("-10 day")->format("Y-m-d");
		$previous_filtered_date = $previous_last_day .' - '. $today;
		$previous_filtered_dates = explode(' - ', $previous_filtered_date);
		$previous_period = new DatePeriod(new DateTime($previous_filtered_dates[0]), new DateInterval('P1D'), new DateTime($previous_filtered_dates[1].' +1 day'));

	    $previous_dates = [];
	    foreach ($previous_period as $previous_date) {
	        $previous_dates[] = $previous_date->format("Y-m-d");
	    }

	    foreach ($previous_dates as $previous_date) {
    		if($enable_delivery_date) {
	    		$previous_args = array(
			        'limit' => -1,
			        'delivery_date' => strtotime(date('Y-m-d', strtotime($previous_date))),
			        'delivery_type' => "delivery",
			        'return' => 'ids'
			    );
	    	} else {
    		$previous_args = array(
		        'limit' => -1,
		        'date_created' => date('Y-m-d', strtotime($previous_date)),
		        'delivery_type' => "delivery",
		        'return' => 'ids'
		    );
    		}
	    	
		    $previous_orders_array = wc_get_orders( $previous_args );


		    foreach ($previous_orders_array as $previous_order_id) {
		    	if(metadata_exists('post', $previous_order_id, 'delivery_date') && get_post_meta($previous_order_id, 'delivery_date', true) !="") {
		    		if(strpos(get_post_meta($previous_order_id, 'delivery_date', true),'-') !== false) {

		    		} else {
		    			update_post_meta($previous_order_id, 'delivery_date', date('Y-m-d', get_post_meta($previous_order_id, 'delivery_date', true)));
		    		}
		    		

		    	}

		    	if(metadata_exists('post', $previous_order_id, 'delivery_time') && get_post_meta($previous_order_id, 'delivery_time', true) !="" && get_post_meta($previous_order_id, 'delivery_time', true) !="as-soon-as-possible") {

		    		if(strpos(get_post_meta($previous_order_id, 'delivery_time', true),',') !== false) {
		    			$pre_minutes = explode(',', get_post_meta($previous_order_id, 'delivery_time', true));
		    			if($pre_minutes[1] == "") {
		    				$pre_new_time = date("H:i", mktime(0, (int)$pre_minutes[0]));
		    			} else {
		    				$pre_new_time = date("H:i", mktime(0, (int)$pre_minutes[0])) . ' - ' . date("H:i", mktime(0, (int)$pre_minutes[1]));
		    			}

		    			update_post_meta($previous_order_id, 'delivery_time', $pre_new_time);
		    		}

		    		

		    	}
		    }

		    if($enable_pickup_date) {
	    		$pre_pickupargs = array(
			        'limit' => -1,
			        'pickup_date' => strtotime(date('Y-m-d', strtotime($previous_date))),
			        'delivery_type' => "pickup",
			        'return' => 'ids'
			    );
	    	} else {
		    $pre_pickupargs = array(
		        'limit' => -1,
		        'date_created' => date('Y-m-d', strtotime($previous_date)),
		        'delivery_type' => "pickup",
		        'return' => 'ids'
		    );
			}
	    	
		    $pre_pickuporders_array = wc_get_orders( $pre_pickupargs );
		    foreach ($pre_pickuporders_array as $pre_pickuporder_id) {
		    	if(metadata_exists('post', $pre_pickuporder_id, 'pickup_date') && get_post_meta($pre_pickuporder_id, 'pickup_date', true) !="") {
		    		if(strpos(get_post_meta($pre_pickuporder_id, 'pickup_date', true),'-') !== false) {

		    		} else {
		    			update_post_meta($pre_pickuporder_id, 'pickup_date', date('Y-m-d', get_post_meta($pre_pickuporder_id, 'pickup_date', true)));
		    		}

		    	}

		    	if(metadata_exists('post', $pre_pickuporder_id, 'pickup_time') && get_post_meta($pre_pickuporder_id, 'pickup_time', true) !="") {

		    		if(strpos(get_post_meta($pre_pickuporder_id, 'pickup_time', true),',') !== false) {
		    			$pre_pickupminutes = explode(',', get_post_meta($pre_pickuporder_id, 'pickup_time', true));
		    			if($pre_pickupminutes[1] == "") {
		    				$pre_pickupnew_time = date("H:i", mktime(0, (int)$pre_pickupminutes[0]));
		    			} else {
		    				$pre_pickupnew_time = date("H:i", mktime(0, (int)$pre_pickupminutes[0])) . ' - ' . date("H:i", mktime(0, (int)$pre_pickupminutes[1]));
		    			}

		    			update_post_meta($pre_pickuporder_id, 'pickup_time', $pre_pickupnew_time);
		    		}

		    		

		    	}
		    }
	    }

	    update_option('coderockz_woo_delivery_date_time_change','completed');

		}
		/* End of Extra Code to adjust pickup date */

		//unset the plugin session & cookie first

		if(isset($_COOKIE['coderockz_woo_delivery_option_time_pickup'])) {
		    unset($_COOKIE['coderockz_woo_delivery_option_time_pickup']);
			setcookie("coderockz_woo_delivery_option_time_pickup", null, -1, '/');
		} elseif(!is_null(WC()->session)) {		  
			WC()->session->__unset( 'coderockz_woo_delivery_option_time_pickup' );  
		}


		if(isset($_COOKIE['coderockz_woo_delivery_available_shipping_methods'])) {
		    unset($_COOKIE["coderockz_woo_delivery_available_shipping_methods"]);
			setcookie("coderockz_woo_delivery_available_shipping_methods", null, -1, '/');
		} elseif(!is_null(WC()->session)) {		  
			WC()->session->__unset( 'coderockz_woo_delivery_available_shipping_methods' );  
		}

		if(!is_null(WC()->session)) {
			WC()->session->__unset( 'selected_delivery_date' );
			WC()->session->__unset( 'selected_delivery_time' );
			WC()->session->__unset( 'selected_pickup_time' );
			WC()->session->__unset( 'selected_order_type' );
		}

		// retrieving the data for delivery time
		$delivery_time_settings = get_option('coderockz_woo_delivery_time_settings');
		$pickup_time_settings = get_option('coderockz_woo_delivery_pickup_settings');
		$other_settings = get_option('coderockz_woo_delivery_other_settings');
		$delivery_option_settings = get_option('coderockz_woo_delivery_option_delivery_settings');
		$localization_settings = get_option('coderockz_woo_delivery_localization_settings');
		// if any timezone data is saved, set default timezone with the data
		$timezone = $this->helper->get_the_timezone();
		date_default_timezone_set($timezone);

		$disable_fields_for_downloadable_products = (isset($other_settings['disable_fields_for_downloadable_products']) && !empty($other_settings['disable_fields_for_downloadable_products'])) ? $other_settings['disable_fields_for_downloadable_products'] : false;

		$has_virtual_downloadable_products = $this->helper->check_virtual_downloadable_products();

		$exclude_condition = $this->helper->detect_exclude_condition();

		if( (!$exclude_condition && (!$has_virtual_downloadable_products || $disable_fields_for_downloadable_products))) {
		
		// starting the creating of view of delivery date and delivery time

		$spinner_animation_id = (isset($other_settings['spinner-animation-id']) && !empty($other_settings['spinner-animation-id'])) ? $other_settings['spinner-animation-id'] : "";

		if($spinner_animation_id != "") {

			$spinner_url = wp_get_attachment_image_src($spinner_animation_id,'full', true);
			$full_size_spinner_animation_path = $spinner_url[0];
		} else {
			$full_size_spinner_animation_path = CODEROCKZ_WOO_DELIVERY_URL.'public/images/loading.gif';
		}

		$spinner_animation_background = (isset($other_settings['spinner_animation_background']) && !empty($other_settings['spinner_animation_background'])) ? $this->helper->hex2rgb($other_settings['spinner_animation_background']) : array('red' => 31, 'green' => 158, 'blue' => 96);


		$remove_shipping_address = (isset($other_settings['hide_shipping_address']) && $other_settings['hide_shipping_address'] != "") ? $other_settings['hide_shipping_address'] : false;

		$shipping_state_zip_wise_offdays = false;
		$offdays_settings = get_option('coderockz_woo_delivery_off_days_settings');
		if((isset($offdays_settings['state_wise_offdays']) && !empty($offdays_settings['state_wise_offdays'])) || (isset($offdays_settings['postcode_wise_offdays']) && !empty($offdays_settings['postcode_wise_offdays'])) || (isset($offdays_settings['zone_wise_offdays']) && !empty($offdays_settings['zone_wise_offdays']))) {
			$shipping_state_zip_wise_offdays = true;
		}

		$additional_field_settings = get_option('coderockz_woo_delivery_additional_field_settings');

		$enable_additional_field = (isset($additional_field_settings['enable_additional_field']) && !empty($additional_field_settings['enable_additional_field'])) ? $additional_field_settings['enable_additional_field'] : false;

		$hide_additional_field_for = (isset($additional_field_settings['hide_additional_field_for']) && !empty($additional_field_settings['hide_additional_field_for'])) ? $additional_field_settings['hide_additional_field_for'] : array();

		if($enable_additional_field && count($hide_additional_field_for) > 0) {
			$hide_additional_field_for = $hide_additional_field_for;
		} else {
			$hide_additional_field_for = array();
		}

		$enable_delivery_restriction = (isset($delivery_option_settings['enable_delivery_restriction']) && !empty($delivery_option_settings['enable_delivery_restriction'])) ? $delivery_option_settings['enable_delivery_restriction'] : false;

		$delivery_restriction_amount = (isset($delivery_option_settings['minimum_amount_cart_restriction']) && $delivery_option_settings['minimum_amount_cart_restriction'] != "") ? (float)$delivery_option_settings['minimum_amount_cart_restriction'] : "";
		
		echo "<div data-delivery_restriction_amount='".$delivery_restriction_amount."' data-enable_delivery_restriction='".$enable_delivery_restriction."' data-shipping_state_zip_wise_offdays='".$shipping_state_zip_wise_offdays."' data-remove_shipping_address='".$remove_shipping_address."' data-animation_background='".json_encode($spinner_animation_background)."' data-animation_path='".$full_size_spinner_animation_path."' data-hide_additional_field_for='".json_encode($hide_additional_field_for)."' id='coderockz_woo_delivery_setting_wrapper'>";		

		$hide_heading_delivery_section = (isset($other_settings['hide_heading_delivery_section']) && $other_settings['hide_heading_delivery_section'] != "") ? $other_settings['hide_heading_delivery_section'] : false;

		
		if(!$hide_heading_delivery_section) {
			
			$delivery_heading_checkout = (isset($localization_settings['delivery_heading_checkout']) && !empty($localization_settings['delivery_heading_checkout'])) ? stripslashes($localization_settings['delivery_heading_checkout']) : "Delivery Information";

			echo "<div style='display:none;' id='coderockz-woo-delivery-public-delivery-details'>";
			echo "<h3 Style='margin-bottom:0;padding-bottom:10px;border-bottom:1px solid #eee;'>".$delivery_heading_checkout."</h3>";
		}

		$additional_message = isset($other_settings['additional_message']) && $other_settings['additional_message'] != "" ? stripslashes($other_settings['additional_message']) : "";

		echo '<p style="margin:10px 0;"><small>'.$additional_message.'</small></p>';
		echo "</div>";

		$enable_delivery_option = (isset($delivery_option_settings['enable_option_time_pickup']) && !empty($delivery_option_settings['enable_option_time_pickup'])) ? $delivery_option_settings['enable_option_time_pickup'] : false;
		$delivery_option_field_label = (isset($delivery_option_settings['delivery_option_label']) && !empty($delivery_option_settings['delivery_option_label'])) ? stripslashes($delivery_option_settings['delivery_option_label']) : "Order Type";
		$delivery_field_label = (isset($delivery_option_settings['delivery_label']) && !empty($delivery_option_settings['delivery_label'])) ? stripslashes($delivery_option_settings['delivery_label']) : "Delivery";
		$pickup_field_label = (isset($delivery_option_settings['pickup_label']) && !empty($delivery_option_settings['pickup_label'])) ? stripslashes($delivery_option_settings['pickup_label']) : "Pickup";
		$no_result_notice = (isset($delivery_option_settings['no_result_notice']) && !empty($delivery_option_settings['no_result_notice'])) ? stripslashes($delivery_option_settings['no_result_notice']) : "No Delivery or Pickup Today";

		if($enable_delivery_option) {
			echo '<div id="coderockz_woo_delivery_delivery_selection_field" style="display:none;">';
				woocommerce_form_field('coderockz_woo_delivery_delivery_selection_box',
				[
					'type' => 'select',
					'class' => [
						'coderockz_woo_delivery_delivery_selection_box form-row-wide'
					],
					'label' => $delivery_option_field_label,
					'placeholder' => $delivery_option_field_label,
				    'options' => Coderockz_Woo_Delivery_Delivery_Option::delivery_option($delivery_option_settings),
					'required' => true,
					'custom_attributes' => [
						'data-no_result_notice' => $no_result_notice,
					],
				], WC()->checkout->get_value('coderockz_woo_delivery_delivery_selection_box'));
			echo '</div>';
		}

		$today = date('Y-m-d', time());

		$delivery_date_settings = get_option('coderockz_woo_delivery_date_settings');
		$pickup_date_settings = get_option('coderockz_woo_delivery_pickup_date_settings');
		$processing_days_settings = get_option('coderockz_woo_delivery_processing_days_settings');
		$enable_delivery_date = (isset($delivery_date_settings['enable_delivery_date']) && !empty($delivery_date_settings['enable_delivery_date'])) ? $delivery_date_settings['enable_delivery_date'] : false;
		$enable_pickup_date = (isset($pickup_date_settings['enable_pickup_date']) && !empty($pickup_date_settings['enable_pickup_date'])) ? $pickup_date_settings['enable_pickup_date'] : false;

		
		/*Bring $max_processing_time calculation here because need in both day and time */
		$max_processing_time_array = [];
		$processing_time_settings = get_option('coderockz_woo_delivery_processing_time_settings');
		$enable_category_processing_time = (isset($processing_time_settings['enable_category_wise_processing_time']) && !empty($processing_time_settings['enable_category_wise_processing_time'])) ? $processing_time_settings['enable_category_wise_processing_time'] : false;
		$category_processing_time = (isset($processing_time_settings['category_processing_time']) && !empty($processing_time_settings['category_processing_time'])) ? $processing_time_settings['category_processing_time'] : array();

		if($enable_category_processing_time && !empty($category_processing_time)) {

			$checkout_product_categories = $this->helper->checkout_product_categories();

			foreach ($category_processing_time as $key => $value)
			{
				if(in_array(strtolower($key), $checkout_product_categories))
				{
					array_push($max_processing_time_array,(int)$value);
				}
			}
		} elseif (isset($processing_time_settings['overall_processing_time']) && !empty($processing_time_settings['overall_processing_time'])) {
			array_push($max_processing_time_array,(int)$processing_time_settings['overall_processing_time']);
		}
		
		$enable_product_processing_time = (isset($processing_time_settings['enable_product_wise_processing_time']) && !empty($processing_time_settings['enable_product_wise_processing_time'])) ? $processing_time_settings['enable_product_wise_processing_time'] : false;
		
		$enable_product_processing_time_quantity = (isset($processing_time_settings['enable_product_processing_time_quantity']) && !empty($processing_time_settings['enable_product_processing_time_quantity'])) ? $processing_time_settings['enable_product_processing_time_quantity'] : false;

		$product_processing_time = (isset($processing_time_settings['product_processing_time']) && !empty($processing_time_settings['product_processing_time'])) ? $processing_time_settings['product_processing_time'] : array();

		if($enable_product_processing_time && !empty($product_processing_time)) {
			
			$product_id = $this->helper->checkout_product_id();
			
			foreach ($product_processing_time as $key => $value)
			{
				if(in_array($key, $product_id))
				{
					
					if($enable_product_processing_time_quantity) {
						foreach ( WC()->cart->get_cart() as $cart_item ) { 
						    if($cart_item['product_id'] == $key ){
						        $qty =  $cart_item['quantity'];
						        break;
						    }
						}
						array_push($max_processing_time_array,(int)$value * $qty);
					} else {
						array_push($max_processing_time_array,(int)$value);
					}
					
				}
			}
		} elseif (isset($processing_time_settings['overall_processing_time']) && !empty($processing_time_settings['overall_processing_time'])) {
			array_push($max_processing_time_array,(int)$processing_time_settings['overall_processing_time']);
		}

		$max_processing_time = count($max_processing_time_array) > 0 ? max($max_processing_time_array) : 0;

		$disable_dates = [];
		$pickup_disable_dates = [];

		$current_time = (date("G")*60)+date("i");

		if($max_processing_time>0){
			$max_processing_time_with_current = $current_time+$max_processing_time;
			if($max_processing_time_with_current>=1440) {
				$x = 1440;
				$date = $today;
				$days_from_processing_time =0;
				while($max_processing_time_with_current>=$x) {
					$second_time = $max_processing_time_with_current - $x;
					$formated = date('Y-m-d H:i:s', strtotime($date));
					$formated_obj = new DateTime($formated);
					$processing_time_date = $formated_obj->modify("+".$days_from_processing_time." day")->format("Y-m-d");
					$disable_dates[] = $processing_time_date;
					$pickup_disable_dates[] = $processing_time_date;
					$max_processing_time_with_current = $second_time;
					$max_processing_time = $second_time;
					$days_from_processing_time = $days_from_processing_time+1;
				}
			}
		}

		$off_days = (isset($delivery_date_settings['off_days']) && !empty($delivery_date_settings['off_days'])) ? $delivery_date_settings['off_days'] : array();

		$consider_off_days = (isset($processing_days_settings['processing_days_consider_off_days']) && !empty($processing_days_settings['processing_days_consider_off_days'])) ? $processing_days_settings['processing_days_consider_off_days'] : false;
		$consider_weekends = (isset($processing_days_settings['processing_days_consider_weekends']) && !empty($processing_days_settings['processing_days_consider_weekends'])) ? $processing_days_settings['processing_days_consider_weekends'] : false;
		$consider_current_day = (isset($processing_days_settings['processing_days_consider_current_day']) && !empty($processing_days_settings['processing_days_consider_current_day'])) ? $processing_days_settings['processing_days_consider_current_day'] : false;

		$max_processing_days_array = [];

		$enable_category_processing_days = (isset($processing_days_settings['enable_category_wise_processing_days']) && !empty($processing_days_settings['enable_category_wise_processing_days'])) ? $processing_days_settings['enable_category_wise_processing_days'] : false;

		$category_processing_days = (isset($processing_days_settings['category_processing_days']) && !empty($processing_days_settings['category_processing_days'])) ? $processing_days_settings['category_processing_days'] : array();

		$overall_processing_days = (isset($processing_days_settings['overall_processing_days']) && $processing_days_settings['overall_processing_days'] != "") ? $processing_days_settings['overall_processing_days'] : "0";

		if($enable_category_processing_days && !empty($category_processing_days)) {

			$checkout_product_categories = $this->helper->checkout_product_categories();

			foreach ($category_processing_days as $key => $value)
			{
				if(in_array(strtolower($key), $checkout_product_categories))
				{
					array_push($max_processing_days_array,(int)$value);
				}
			}
		} else {
			$max_processing_days_array[] = (int)$overall_processing_days;
		}
		
		$enable_product_processing_days = (isset($processing_days_settings['enable_product_wise_processing_days']) && !empty($processing_days_settings['enable_product_wise_processing_days'])) ? $processing_days_settings['enable_product_wise_processing_days'] : false;

		$enable_product_processing_day_quantity = (isset($processing_days_settings['enable_product_processing_day_quantity']) && !empty($processing_days_settings['enable_product_processing_day_quantity'])) ? $processing_days_settings['enable_product_processing_day_quantity'] : false;

		$product_processing_days = (isset($processing_days_settings['product_processing_days']) && !empty($processing_days_settings['product_processing_days'])) ? $processing_days_settings['product_processing_days'] : array();
		if($enable_product_processing_days && !empty($product_processing_days)) {
			
			$product_id = $this->helper->checkout_product_id();

			foreach ($product_processing_days as $key => $value)
			{
				if(in_array($key, $product_id))
				{

					if($enable_product_processing_day_quantity) {
						foreach ( WC()->cart->get_cart() as $cart_item ) { 
						    if($cart_item['product_id'] == $key ){
						        $qty =  $cart_item['quantity'];
						        break;
						    }
						}
						array_push($max_processing_days_array,(int)$value * $qty);
					} else {
						array_push($max_processing_days_array,(int)$value);
					}

				}
			}
		} else {
			$max_processing_days_array[] = (int)$overall_processing_days;
		}

		$max_processing_days = count($max_processing_days_array) > 0 ? max($max_processing_days_array) : 0;
		$temp_max_processing_days = $max_processing_days;
		$disable_week_days_category = [];
		$offdays_settings = get_option('coderockz_woo_delivery_off_days_settings');
		$category_wise_offdays = (isset($offdays_settings['category_wise_offdays']) && !empty($offdays_settings['category_wise_offdays'])) ? $offdays_settings['category_wise_offdays'] : array();
		
		if(!empty($category_wise_offdays)) {
			$checkout_product_categories = $this->helper->checkout_product_categories();

			foreach ($category_wise_offdays as $key => $value)
			{
				if(in_array(strtolower($key), $checkout_product_categories))
				{
					foreach($value as $off_day) {
						$disable_week_days_category[] = $off_day;
					}
				}
			}
		}

		$disable_week_days_category = array_unique($disable_week_days_category, false);
		$disable_week_days_category = array_values($disable_week_days_category);


		$disable_week_days_product = [];
		$offdays_settings = get_option('coderockz_woo_delivery_off_days_settings');
		$product_wise_offdays = (isset($offdays_settings['product_wise_offdays']) && !empty($offdays_settings['product_wise_offdays'])) ? $offdays_settings['product_wise_offdays'] : array();
		
		if(!empty($product_wise_offdays)) {
			$product_id = $this->helper->checkout_product_id();

			foreach ($product_wise_offdays as $key => $value)
			{
				if(in_array($key, $product_id))
				{
					foreach($value as $off_day) {
						$disable_week_days_product[] = $off_day;
					}
				}
			}
		}

		$disable_week_days_product = array_unique($disable_week_days_product, false);
		$disable_week_days_product = array_values($disable_week_days_product);

		$off_day_dates = [];
		$selectable_start_date = date('Y-m-d H:i:s', time());
		$start_date = new DateTime($selectable_start_date);
		if(count($off_days)) {
			$date = $start_date;
			foreach ($off_days as $year => $months) {
				foreach($months as $month => $days){
					$month_num = date_parse($month)['month'];
					if(strlen($month_num) == 1) {
						$month_num_final = "0".$month_num;
					} else {
						$month_num_final = $month_num;
					}
					$days = explode(',', $days);
					foreach($days as $day){						
						$disable_dates[] = $year . "-" . $month_num_final . "-" .$day;
						$pickup_disable_dates[] = $year . "-" . $month_num_final . "-" .$day;
						$off_day_dates[] = $year . "-" . $month_num_final . "-" .$day;
					}
				}
			}
		}

		$enable_closing_time = (isset($delivery_time_settings['enable_closing_time']) && !empty($delivery_time_settings['enable_closing_time'])) ? $delivery_time_settings['enable_closing_time'] : false;

		$extended_closing_days = (isset($delivery_time_settings['extended_closing_days']) && !empty($delivery_time_settings['extended_closing_days'])) ? (int)$delivery_time_settings['extended_closing_days'] : 0;

		$enable_different_closing_time = (isset($delivery_time_settings['enable_different_closing_time']) && !empty($delivery_time_settings['enable_different_closing_time'])) ? $delivery_time_settings['enable_different_closing_time'] : false;

		if($enable_different_closing_time) {
			$current_week_day = date("w"); 				
			$store_closing_time = (isset($delivery_time_settings['different_store_closing_time'][$current_week_day]) && $delivery_time_settings['different_store_closing_time'][$current_week_day] != "") ? (int)$delivery_time_settings['different_store_closing_time'][$current_week_day] : "";
			$current_time = (date("G")*60)+date("i");

		} else {
			$store_closing_time = (isset($delivery_time_settings['store_closing_time']) && $delivery_time_settings['store_closing_time'] != "") ? (int)$delivery_time_settings['store_closing_time'] : "";
			$current_time = (date("G")*60)+date("i");
		}

		
		if($enable_closing_time) {
			if($store_closing_time != "" && ($current_time >= $store_closing_time || $current_time+$max_processing_time >= $store_closing_time)) {
				$disable_dates[] = $today;
				$pickup_disable_dates[] = $today;
				$date = $today;
				for($i=1;$i<=$extended_closing_days;$i++) {
					$formated = date('Y-m-d H:i:s', strtotime($date));
					$formated_obj = new DateTime($formated);
					$extended_closing_date = $formated_obj->modify("+1 day")->format("Y-m-d");
					$disable_dates[] = $extended_closing_date;
					$pickup_disable_dates[] = $extended_closing_date;
					$date = $extended_closing_date;

				}
			}
		}
				

		// Delivery Date --------------------------------------------------------------
		$enable_pickup_time = (isset($pickup_time_settings['enable_pickup_time']) && !empty($pickup_time_settings['enable_pickup_time'])) ? $pickup_time_settings['enable_pickup_time'] : false;
		
		if( ($enable_delivery_date  && (!$enable_pickup_date || !$enable_pickup_time) && !$enable_delivery_option) || ($enable_delivery_option && $enable_delivery_date) ) {

			$auto_select_first_date = (isset($delivery_date_settings['auto_select_first_date']) && !empty($delivery_date_settings['auto_select_first_date'])) ? $delivery_date_settings['auto_select_first_date'] : false;			

			$delivery_days = isset($delivery_date_settings['delivery_days']) && $delivery_date_settings['delivery_days'] != "" ? $delivery_date_settings['delivery_days'] : "6,0,1,2,3,4,5";			
			$delivery_date_mandatory = (isset($delivery_date_settings['delivery_date_mandatory']) && !empty($delivery_date_settings['delivery_date_mandatory'])) ? $delivery_date_settings['delivery_date_mandatory'] : false;
			$delivery_date_format = (isset($delivery_date_settings['date_format']) && !empty($delivery_date_settings['date_format'])) ? $delivery_date_settings['date_format'] : "F j, Y";		
			$delivery_date_calendar_locale = (isset($delivery_date_settings['calendar_locale']) && !empty($delivery_date_settings['calendar_locale'])) ? $delivery_date_settings['calendar_locale'] : "default";
			$week_starts_from = (isset($delivery_date_settings['week_starts_from']) && !empty($delivery_date_settings['week_starts_from'])) ? $delivery_date_settings['week_starts_from']:"0";
			
			$selectable_date = (isset($delivery_date_settings['selectable_date']) && !empty($delivery_date_settings['selectable_date']))?$delivery_date_settings['selectable_date']:"365";

			$same_day_delivery = (isset($delivery_date_settings['disable_same_day_delivery']) && !empty($delivery_date_settings['disable_same_day_delivery'])) ? $delivery_date_settings['disable_same_day_delivery'] : false;

			$delivery_days = explode(',', $delivery_days);

			$week_days = ['0', '1', '2', '3', '4', '5', '6'];
			$disable_week_days = array_values(array_diff($week_days, $delivery_days));
					
			$selectable_start_date = date('Y-m-d H:i:s', time());
			$start_date = new DateTime($selectable_start_date);
			
			if($max_processing_days > 0) {

				if($consider_current_day && $max_processing_days > 0) {
					$disable_dates[] = $start_date->format("Y-m-d");
					$max_processing_days = $max_processing_days - 1;
					$start_date = $start_date->modify("+1 day");
				} else {
					$disable_dates[] = $start_date->format("Y-m-d");
					$start_date = $start_date->modify("+1 day");
				}

				while($max_processing_days > 0) {
					$date = $start_date;
					if($consider_weekends) {

						$disable_dates[] = $date->format("Y-m-d");
						$max_processing_days = $max_processing_days - 1;
						$start_date = $start_date->modify("+1 day");
					} else {
						if (!in_array($date->format("w"), $disable_week_days)) {
							$disable_dates[] = $date->format("Y-m-d");
							$max_processing_days = $max_processing_days - 1;
							$start_date = $start_date->modify("+1 day");
						} else {
							$disable_dates[] = $date->format("Y-m-d");
							$start_date = $start_date->modify("+1 day");

						}

					}

				}


				$period = new DatePeriod(new DateTime(date('Y-m-d', time())), new DateInterval('P1D'), new DateTime($start_date->format("Y-m-d").' +1 day'));
				$dates = [];
				foreach ($period as $date) {
			        $dates[] = $date->format("Y-m-d");
			    }

				$off_days_count = count(array_intersect($dates,$off_day_dates));
				while($off_days_count > 0) {
					if(!$consider_off_days) {
						$disable_dates[] = $start_date->format("Y-m-d");
					}

					$off_days_count = $off_days_count-1;
				}

			}	

			$disable_dates = array_unique($disable_dates, false);
			$disable_dates = array_values($disable_dates);

			$delivery_date_field_heading = (isset($delivery_date_settings['field_label']) && !empty($delivery_date_settings['field_label'])) ? stripslashes($delivery_date_settings['field_label']) : "Delivery Date";
			$delivery_date_field_placeholder = (isset($delivery_date_settings['field_placeholder']) && !empty($delivery_date_settings['field_placeholder'])) ? stripslashes($delivery_date_settings['field_placeholder']) : "Delivery Date";


			$delivery_open_days = (isset($delivery_date_settings['open_days']) && !empty($delivery_date_settings['open_days'])) ? $delivery_date_settings['open_days'] : array();
			$special_open_days_dates = [];
			$selectable_start_date = date('Y-m-d H:i:s', time());
			$start_date = new DateTime($selectable_start_date);
			if(count($delivery_open_days)) {
				$date = $start_date;
				foreach ($delivery_open_days as $year => $months) {
					foreach($months as $month => $days){
						$month_num = date_parse($month)['month'];
						if(strlen($month_num) == 1) {
							$month_num_final = "0".$month_num;
						} else {
							$month_num_final = $month_num;
						}
						$days = explode(',', $days);
						foreach($days as $day){						
							$special_open_days_dates[] = $year . "-" . $month_num_final . "-" .$day;
						}
					}
				}
			}

			echo '<div id="coderockz_woo_delivery_delivery_date_section" style="display:none;">';

			woocommerce_form_field('coderockz_woo_delivery_date_field',
			[
				'type' => 'text',
				'class' => array(
				  'coderockz_woo_delivery_date_field form-row-wide'
				) ,
				'id' => "coderockz_woo_delivery_date_datepicker",
				'label' => $delivery_date_field_heading,
				'placeholder' => $delivery_date_field_placeholder,
				'required' => $delivery_date_mandatory, 
				'custom_attributes' => [
					'data-selectable_dates' => $selectable_date,
					'data-disable_week_days' => json_encode($disable_week_days),
					'data-disable_week_days_category' => json_encode($disable_week_days_category),
					'data-disable_week_days_product' => json_encode($disable_week_days_product),
					'data-date_format' => $delivery_date_format,
					'data-disable_dates' => json_encode($disable_dates),
					'data-calendar_locale' => $delivery_date_calendar_locale,
					'data-week_starts_from' => $week_starts_from,
					'data-default_date' => $auto_select_first_date,
					'data-same_day_delivery' => $same_day_delivery,
					'data-special_open_days_dates' => json_encode($special_open_days_dates),
					'data-delivery_date_mandatory' => $delivery_date_mandatory,
				],
			] , WC()->checkout->get_value('coderockz_woo_delivery_date_field'));
			echo '</div>';

		}

		// End Delivery Date

		// Pickup Date --------------------------------------------------------------
		$enable_delivery_time = (isset($delivery_time_settings['enable_delivery_time']) && !empty($delivery_time_settings['enable_delivery_time'])) ? $delivery_time_settings['enable_delivery_time'] : false;

		if( ($enable_pickup_date && (!$enable_delivery_date || !$enable_delivery_time) && !$enable_delivery_option) || ($enable_delivery_option && $enable_pickup_date)) {

			$auto_select_first_pickup_date = (isset($pickup_date_settings['auto_select_first_pickup_date']) && !empty($pickup_date_settings['auto_select_first_pickup_date'])) ? $pickup_date_settings['auto_select_first_pickup_date'] : false;			

			$pickup_days = isset($pickup_date_settings['pickup_days']) && $pickup_date_settings['pickup_days'] != "" ? $pickup_date_settings['pickup_days'] : "6,0,1,2,3,4,5";			

			$pickup_date_mandatory = (isset($pickup_date_settings['pickup_date_mandatory']) && !empty($pickup_date_settings['pickup_date_mandatory'])) ? $pickup_date_settings['pickup_date_mandatory'] : false;
			$pickup_date_format = (isset($pickup_date_settings['date_format']) && !empty($pickup_date_settings['date_format'])) ? $pickup_date_settings['date_format'] : "F j, Y";		
			$pickup_date_calendar_locale = (isset($pickup_date_settings['calendar_locale']) && !empty($pickup_date_settings['calendar_locale'])) ? $pickup_date_settings['calendar_locale'] : "default";
			$pickup_week_starts_from = (isset($pickup_date_settings['week_starts_from']) && !empty($pickup_date_settings['week_starts_from'])) ? $pickup_date_settings['week_starts_from']:"0";
			
			$pickup_selectable_date = (isset($pickup_date_settings['selectable_date']) && !empty($pickup_date_settings['selectable_date']))?$pickup_date_settings['selectable_date']:"365";

			$same_day_pickup = (isset($pickup_date_settings['disable_same_day_pickup']) && !empty($pickup_date_settings['disable_same_day_pickup'])) ? $pickup_date_settings['disable_same_day_pickup'] : false;

			$pickup_days = explode(',', $pickup_days);

			$week_days = ['0', '1', '2', '3', '4', '5', '6'];
			$pickup_disable_week_days = array_values(array_diff($week_days, $pickup_days));

			$selectable_start_date = date('Y-m-d H:i:s', time());
			$start_date = new DateTime($selectable_start_date);
			$max_processing_days = $temp_max_processing_days;
			
			if($max_processing_days > 0) {

				if($consider_current_day && $max_processing_days > 0) {
					$pickup_disable_dates[] = $start_date->format("Y-m-d");
					$max_processing_days = $max_processing_days - 1;
					$start_date = $start_date->modify("+1 day");
				} else {
					$pickup_disable_dates[] = $start_date->format("Y-m-d");
					$start_date = $start_date->modify("+1 day");
				}

				while($max_processing_days > 0) {
					$date = $start_date;
					if($consider_weekends) {

						$pickup_disable_dates[] = $date->format("Y-m-d");
						$max_processing_days = $max_processing_days - 1;
						$start_date = $start_date->modify("+1 day");
					} else {
						if (!in_array($date->format("w"), $pickup_disable_week_days)) {
							$pickup_disable_dates[] = $date->format("Y-m-d");
							$max_processing_days = $max_processing_days - 1;
							$start_date = $start_date->modify("+1 day");
						} else {

							$pickup_disable_dates[] = $date->format("Y-m-d");
							$start_date = $start_date->modify("+1 day");

						}

					}

				}

				$period = new DatePeriod(new DateTime(date('Y-m-d', time())), new DateInterval('P1D'), new DateTime($start_date->format("Y-m-d").' +1 day'));
				$dates = [];
				foreach ($period as $date) {
			        $dates[] = $date->format("Y-m-d");
			    }

				$off_days_count = count(array_intersect($dates,$off_day_dates));
				while($off_days_count > 0) {
					if(!$consider_off_days) {
						$pickup_disable_dates[] = $start_date->format("Y-m-d");
					}

					$off_days_count = $off_days_count-1;
				}

			}

			$pickup_disable_dates = array_unique($pickup_disable_dates, false);
			$pickup_disable_dates = array_values($pickup_disable_dates);

			$pickup_date_field_heading = (isset($pickup_date_settings['pickup_field_label']) && !empty($pickup_date_settings['pickup_field_label'])) ? stripslashes($pickup_date_settings['pickup_field_label']) : "Pickup Date";
			$pickup_date_field_placeholder = (isset($pickup_date_settings['pickup_field_placeholder']) && !empty($pickup_date_settings['pickup_field_placeholder'])) ? stripslashes($pickup_date_settings['pickup_field_placeholder']) : "Pickup Date";

			$pickup_open_days = (isset($pickup_date_settings['open_days']) && !empty($pickup_date_settings['open_days'])) ? $pickup_date_settings['open_days'] : array();
			$special_open_days_dates_pickup = [];
			$selectable_start_date = date('Y-m-d H:i:s', time());
			$start_date = new DateTime($selectable_start_date);
			if(count($pickup_open_days)) {
				$date = $start_date;
				foreach ($pickup_open_days as $year => $months) {
					foreach($months as $month => $days){
						$month_num = date_parse($month)['month'];
						if(strlen($month_num) == 1) {
							$month_num_final = "0".$month_num;
						} else {
							$month_num_final = $month_num;
						}
						$days = explode(',', $days);
						foreach($days as $day){						
							$special_open_days_dates_pickup[] = $year . "-" . $month_num_final . "-" .$day;
						}
					}
				}
			}

			echo '<div id="coderockz_woo_delivery_pickup_date_section" style="display:none;">';

			woocommerce_form_field('coderockz_woo_delivery_pickup_date_field',
			[
				'type' => 'text',
				'class' => array(
				  'coderockz_woo_delivery_pickup_date_field form-row-wide'
				) ,
				'id' => "coderockz_woo_delivery_pickup_date_datepicker",
				'label' => $pickup_date_field_heading,
				'placeholder' => $pickup_date_field_placeholder,
				'required' => $pickup_date_mandatory, 
				'custom_attributes' => [
					'data-pickup_selectable_dates' => $pickup_selectable_date,
					'data-pickup_disable_week_days' => json_encode($pickup_disable_week_days),
					'data-disable_week_days_category' => json_encode($disable_week_days_category),
					'data-disable_week_days_product' => json_encode($disable_week_days_product),
					'data-pickup_date_format' => $pickup_date_format,
					'data-pickup_disable_dates' => json_encode($pickup_disable_dates),
					'data-pickup_calendar_locale' => $pickup_date_calendar_locale,
					'data-pickup_week_starts_from' => $pickup_week_starts_from,
					'data-pickup_default_date' => $auto_select_first_pickup_date,
					'data-same_day_pickup' => $same_day_pickup,
					'data-special_open_days_dates_pickup' => json_encode($special_open_days_dates_pickup),
					'data-pickup_date_mandatory' => $pickup_date_mandatory,
				],
			] , WC()->checkout->get_value('coderockz_woo_delivery_pickup_date_field'));
			echo '</div>';

		}

		// End Pickup Date

		// Delivery Time --------------------------------------------------------------
		
		$enable_delivery_time = (isset($delivery_time_settings['enable_delivery_time']) && !empty($delivery_time_settings['enable_delivery_time'])) ? $delivery_time_settings['enable_delivery_time'] : false;

		$delivery_time_field_label = (isset($delivery_time_settings['field_label']) && !empty($delivery_time_settings['field_label'])) ? stripslashes($delivery_time_settings['field_label']) : "Delivery Time";
		$delivery_time_field_placeholder = (isset($delivery_time_settings['field_placeholder']) && !empty($delivery_time_settings['field_placeholder'])) ? stripslashes($delivery_time_settings['field_placeholder']) : "Delivery Time";

		$delivery_time_mandatory = (isset($delivery_time_settings['delivery_time_mandatory']) && !empty($delivery_time_settings['delivery_time_mandatory'])) ? $delivery_time_settings['delivery_time_mandatory'] : false;
		$auto_select_first_time = (isset($delivery_time_settings['auto_select_first_time']) && !empty($delivery_time_settings['auto_select_first_time'])) ? $delivery_time_settings['auto_select_first_time'] : false;
		$search_box_hidden = (isset($delivery_time_settings['hide_searchbox']) && !empty($delivery_time_settings['hide_searchbox'])) ? $delivery_time_settings['hide_searchbox'] : false;

		if($enable_delivery_time) {


			$disable_timeslot_with_processing_time = (isset($processing_time_settings['disable_timeslot_with_processing_time']) && !empty($processing_time_settings['disable_timeslot_with_processing_time'])) ? $processing_time_settings['disable_timeslot_with_processing_time'] : false;

			$order_limit_notice = (isset($localization_settings['order_limit_notice']) && !empty($localization_settings['order_limit_notice'])) ? "(".stripslashes($localization_settings['order_limit_notice']).")" : "(Maximum Order Limit Exceed)";
			$select_delivery_date_notice = (isset($localization_settings['select_delivery_date_notice']) && !empty($localization_settings['select_delivery_date_notice'])) ? stripslashes($localization_settings['select_delivery_date_notice']) : "Select Delivery Date First";


			$custom_time_slot_settings = get_option('coderockz_woo_delivery_time_slot_settings');
			$enable_custom_time_slot = (isset($custom_time_slot_settings['enable_custom_time_slot']) && !empty($custom_time_slot_settings['enable_custom_time_slot'])) ? $custom_time_slot_settings['enable_custom_time_slot'] : false;
			$timeslot_zone_check = false;
			if($enable_custom_time_slot) {

				if(isset($custom_time_slot_settings['time_slot']) && count($custom_time_slot_settings['time_slot'])>0){
					foreach($custom_time_slot_settings['time_slot'] as $individual_time_slot) {
			  			 
			  			if( $individual_time_slot['enable'] && (!empty($individual_time_slot['disable_postcode']) || !empty($individual_time_slot['disable_state'])) ) {
			  				$timeslot_zone_check = true;
			  				break;
			  			}
			  		}
				}
			}

			echo '<div id="coderockz_woo_delivery_delivery_time_section" style="display:none;">';

			woocommerce_form_field('coderockz_woo_delivery_time_field',
			[
				'type' => 'select',
				'class' => [
					'coderockz_woo_delivery_time_field form-row-wide'
				],
				'label' => $delivery_time_field_label,
				'placeholder' => $delivery_time_field_placeholder,
				'options' => Coderockz_Woo_Delivery_Time_Option::delivery_time_option($delivery_time_settings),
				'required' => $delivery_time_mandatory,
				'custom_attributes' => [
					'data-default_time' => $auto_select_first_time,
					'data-max_processing_time' => $max_processing_time,
					'data-disable_timeslot_with_processing_time' => $disable_timeslot_with_processing_time,
					'data-hide_searchbox' => $search_box_hidden,
					'data-order_limit_notice' => $order_limit_notice,
					'data-timeslot_zone_check' => $timeslot_zone_check,
					'data-select_delivery_date_notice' => $select_delivery_date_notice,
				],
			], WC()->checkout->get_value('coderockz_woo_delivery_time_field'));
			echo '</div>';

		}
		// End Delivery Time

		// Pickup Time --------------------------------------------------------------
		
		$enable_pickup_time = (isset($pickup_time_settings['enable_pickup_time']) && !empty($pickup_time_settings['enable_pickup_time'])) ? $pickup_time_settings['enable_pickup_time'] : false;
		$pickup_time_field_label = (isset($pickup_time_settings['field_label']) && !empty($pickup_time_settings['field_label'])) ? stripslashes($pickup_time_settings['field_label']) : "Pickup Time";
		$pickup_time_field_placeholder = (isset($pickup_time_settings['field_placeholder']) && !empty($pickup_time_settings['field_placeholder'])) ? stripslashes($pickup_time_settings['field_placeholder']) : "Pickup Time";

		$pickup_time_mandatory = (isset($pickup_time_settings['pickup_time_mandatory']) && !empty($pickup_time_settings['pickup_time_mandatory'])) ? $pickup_time_settings['pickup_time_mandatory'] : false;
		$pickup_auto_select_first_time = (isset($pickup_time_settings['auto_select_first_time']) && !empty($pickup_time_settings['auto_select_first_time'])) ? $pickup_time_settings['auto_select_first_time'] : false;
		$pickup_search_box_hidden = (isset($pickup_time_settings['hide_searchbox']) && !empty($pickup_time_settings['hide_searchbox'])) ? $pickup_time_settings['hide_searchbox'] : false;

		if($enable_pickup_time) {


			$disable_timeslot_with_processing_time = (isset($processing_time_settings['disable_timeslot_with_processing_time']) && !empty($processing_time_settings['disable_timeslot_with_processing_time'])) ? $processing_time_settings['disable_timeslot_with_processing_time'] : false;

			$pickup_limit_notice = (isset($localization_settings['pickup_limit_notice']) && !empty($localization_settings['pickup_limit_notice'])) ? "(".stripslashes($localization_settings['pickup_limit_notice']).")" : "(Maximum Pickup Limit Exceed)";

			$select_pickup_date_notice = (isset($localization_settings['select_pickup_date_notice']) && !empty($localization_settings['select_pickup_date_notice'])) ? stripslashes($localization_settings['select_pickup_date_notice']) : "Select Pickup Date First";

			$select_pickup_date_location_notice = (isset($localization_settings['select_pickup_date_location_notice']) && !empty($localization_settings['select_pickup_date_location_notice'])) ? stripslashes($localization_settings['select_pickup_date_location_notice']) : "Select Pickup Date & Location First";

			$select_pickup_location_notice = (isset($localization_settings['select_pickup_location_notice']) && !empty($localization_settings['select_pickup_location_notice'])) ? stripslashes($localization_settings['select_pickup_location_notice']) : "Select Pickup Location First";

			$custom_pickup_slot_settings = get_option('coderockz_woo_delivery_pickup_slot_settings');
			$enable_custom_pickup_slot = (isset($custom_pickup_slot_settings['enable_custom_pickup_slot']) && !empty($custom_pickup_slot_settings['enable_custom_pickup_slot'])) ? $custom_pickup_slot_settings['enable_custom_pickup_slot'] : false;
			$pickupslot_zone_check = false;
			if($enable_custom_pickup_slot) {

				if(isset($custom_pickup_slot_settings['time_slot']) && count($custom_pickup_slot_settings['time_slot'])>0){
					foreach($custom_pickup_slot_settings['time_slot'] as $individual_time_slot) {
			  			 
			  			if( $individual_time_slot['enable'] && (!empty($individual_time_slot['disable_postcode']) || !empty($individual_time_slot['disable_state'])) ) {
			  				$pickupslot_zone_check = true;
			  				break;
			  			}
			  		}
				}
			}

			echo '<div id="coderockz_woo_delivery_pickup_time_section" style="display:none;">';

			woocommerce_form_field('coderockz_woo_delivery_pickup_time_field',
			[
				'type' => 'select',
				'class' => [
					'coderockz_woo_delivery_pickup_time_field form-row-wide'
				],
				'label' => $pickup_time_field_label,
				'placeholder' => $pickup_time_field_placeholder,
				'options' => Coderockz_Woo_Delivery_Pickup_Option::pickup_time_option($pickup_time_settings),
				'required' => $pickup_time_mandatory,
				'custom_attributes' => [
					'data-default_time' => $pickup_auto_select_first_time,
					'data-max_processing_time' => $max_processing_time,
					'data-disable_timeslot_with_processing_time' => $disable_timeslot_with_processing_time,
					'data-hide_searchbox' => $pickup_search_box_hidden,
					'data-pickup_limit_notice' => $pickup_limit_notice,
					'data-pickupslot_zone_check' => $pickupslot_zone_check,
					'data-select_pickup_date_notice' => $select_pickup_date_notice,
					'data-select_pickup_date_location_notice' => $select_pickup_date_location_notice,
					'data-select_pickup_location_notice' => $select_pickup_location_notice,
				],
			], WC()->checkout->get_value('coderockz_woo_delivery_pickup_time_field'));
			echo '</div>';

		}
		// End Pickup Time

		$pickup_location_settings = get_option('coderockz_woo_delivery_pickup_location_settings');

		$enable_pickup_location = (isset($pickup_location_settings['enable_pickup_location']) && !empty($pickup_location_settings['enable_pickup_location'])) ? $pickup_location_settings['enable_pickup_location'] : false;

		$pickup_location_field_label = (isset($pickup_location_settings['field_label']) && !empty($pickup_location_settings['field_label'])) ? stripslashes($pickup_location_settings['field_label']) : "Pickup Location";
		$pickup_location_field_placeholder = (isset($pickup_location_settings['field_placeholder']) && !empty($pickup_location_settings['field_placeholder'])) ? stripslashes($pickup_location_settings['field_placeholder']) : "Pickup Location";

		$pickup_location_mandatory = (isset($pickup_location_settings['pickup_location_mandatory']) && !empty($pickup_location_settings['pickup_location_mandatory'])) ? $pickup_location_settings['pickup_location_mandatory'] : false;


		if($enable_pickup_location)
		{
			echo '<div id="coderockz_woo_delivery_delivery_pickup_section" style="display:none;">';
			
			woocommerce_form_field('coderockz_woo_delivery_pickup_location_field',
			[
				'type' => 'select',
				'class' =>
				[
					'coderockz_woo_delivery_pickup_location_field form-row-wide',
				],
				'label' => $pickup_location_field_label,
				'placeholder' => $pickup_location_field_placeholder,
				'options' => Coderockz_Woo_Delivery_Pickup_Location_Option::pickup_location_option($pickup_location_settings),
				'required' => $pickup_location_mandatory,
				'custom_attributes' => [
					'data-pickup_location_mandatory' => $pickup_location_mandatory,
				],
			], WC()->checkout->get_value('coderockz_woo_delivery_pickup_location_field'));
			echo '</div>';
		}

		$additional_field_settings = get_option('coderockz_woo_delivery_additional_field_settings');

		$enable_additional_field = (isset($additional_field_settings['enable_additional_field']) && !empty($additional_field_settings['enable_additional_field'])) ? $additional_field_settings['enable_additional_field'] : false;

		$additional_field_field_label = (isset($additional_field_settings['field_label']) && !empty($additional_field_settings['field_label'])) ? stripslashes($additional_field_settings['field_label']) : "Special Note About Delivery";

		$additional_field_mandatory = (isset($additional_field_settings['additional_field_mandatory']) && !empty($additional_field_settings['additional_field_mandatory'])) ? $additional_field_settings['additional_field_mandatory'] : false;

		$additional_field_character_limit = (isset($additional_field_settings['character_limit']) && !empty($additional_field_settings['character_limit'])) ? $additional_field_settings['character_limit'] : "";

		if($enable_additional_field)
		{

			echo "<div id='coderockz_woo_delivery_additional_field_section' style='display:none'>";
			woocommerce_form_field('coderockz_woo_delivery_additional_field_field',
			[
				'type' => 'textarea',
				'class' =>
				[
					'coderockz_woo_delivery_additional_field_field form-row-wide',
				],
				'label' => $additional_field_field_label,
				'placeholder' => $additional_field_field_label,
				'required' => $additional_field_mandatory,
				'maxlength' => $additional_field_character_limit,
				'custom_attributes' => [
					'data-character_limit' => $additional_field_character_limit,
				],
			], WC()->checkout->get_value('coderockz_woo_delivery_additional_field_field'));
			echo "</div>";
		}

		echo "</div>";
	
		}

	}

	/**
	 * Checkout Process
	*/	
	public function coderockz_woo_delivery_customise_checkout_field_process() {
		
		$timezone = $this->helper->get_the_timezone();
		date_default_timezone_set($timezone);
		$today = date('Y-m-d', time());

		$delivery_option_settings = get_option('coderockz_woo_delivery_option_delivery_settings');
		$delivery_date_settings = get_option('coderockz_woo_delivery_date_settings');
		$pickup_date_settings = get_option('coderockz_woo_delivery_pickup_date_settings');
		$delivery_time_settings = get_option('coderockz_woo_delivery_time_settings');
		$pickup_time_settings = get_option('coderockz_woo_delivery_pickup_settings');
		$enable_delivery_option = (isset($delivery_option_settings['enable_option_time_pickup']) && !empty($delivery_option_settings['enable_option_time_pickup'])) ? $delivery_option_settings['enable_option_time_pickup'] : false;

		$enable_delivery_date = (isset($delivery_date_settings['enable_delivery_date']) && !empty($delivery_date_settings['enable_delivery_date'])) ? $delivery_date_settings['enable_delivery_date'] : false;
		$delivery_date_mandatory = (isset($delivery_date_settings['delivery_date_mandatory']) && !empty($delivery_date_settings['delivery_date_mandatory'])) ? $delivery_date_settings['delivery_date_mandatory'] : false;

		$enable_pickup_date = (isset($pickup_date_settings['enable_pickup_date']) && !empty($pickup_date_settings['enable_pickup_date'])) ? $pickup_date_settings['enable_pickup_date'] : false;
		$pickup_date_mandatory = (isset($pickup_date_settings['pickup_date_mandatory']) && !empty($pickup_date_settings['pickup_date_mandatory'])) ? $pickup_date_settings['pickup_date_mandatory'] : false;


		$enable_delivery_time = (isset($delivery_time_settings['enable_delivery_time']) && !empty($delivery_time_settings['enable_delivery_time'])) ? $delivery_time_settings['enable_delivery_time'] : false;
		$delivery_time_mandatory = (isset($delivery_time_settings['delivery_time_mandatory']) && !empty($delivery_time_settings['delivery_time_mandatory'])) ? $delivery_time_settings['delivery_time_mandatory'] : false;


		$enable_pickup_time = (isset($pickup_time_settings['enable_pickup_time']) && !empty($pickup_time_settings['enable_pickup_time'])) ? $pickup_time_settings['enable_pickup_time'] : false;
		$pickup_time_mandatory = (isset($pickup_time_settings['pickup_time_mandatory']) && !empty($pickup_time_settings['pickup_time_mandatory'])) ? $pickup_time_settings['pickup_time_mandatory'] : false;

		$disable_fields_for_downloadable_products = (isset(get_option('coderockz_woo_delivery_other_settings')['disable_fields_for_downloadable_products']) && !empty(get_option('coderockz_woo_delivery_other_settings')['disable_fields_for_downloadable_products'])) ? get_option('coderockz_woo_delivery_other_settings')['disable_fields_for_downloadable_products'] : false;

		$checkout_notice = get_option('coderockz_woo_delivery_localization_settings');
		$checkout_delivery_option_notice = (isset($checkout_notice['checkout_delivery_option_notice']) && !empty($checkout_notice['checkout_delivery_option_notice'])) ? stripslashes($checkout_notice['checkout_delivery_option_notice']) : "Please Select Your Order Type.";
		$checkout_date_notice = (isset($checkout_notice['checkout_date_notice']) && !empty($checkout_notice['checkout_date_notice'])) ? stripslashes($checkout_notice['checkout_date_notice']) : "Please Enter Delivery Date.";
		$checkout_pickup_date_notice = (isset($checkout_notice['checkout_pickup_date_notice']) && !empty($checkout_notice['checkout_pickup_date_notice'])) ? stripslashes($checkout_notice['checkout_pickup_date_notice']) : "Please Enter Pickup Date.";
		$checkout_time_notice = (isset($checkout_notice['checkout_time_notice']) && !empty($checkout_notice['checkout_time_notice'])) ? stripslashes($checkout_notice['checkout_time_notice']) : "Please Enter Delivery Time.";	
		$checkout_pickup_time_notice = (isset($checkout_notice['checkout_pickup_time_notice']) && !empty($checkout_notice['checkout_pickup_time_notice'])) ? stripslashes($checkout_notice['checkout_pickup_time_notice']) : "Please Enter Pickup Time.";	
		$checkout_pickup_notice = (isset($checkout_notice['checkout_pickup_notice']) && !empty($checkout_notice['checkout_pickup_notice'])) ? stripslashes($checkout_notice['checkout_pickup_notice']) : "Please Enter Pickup Location.";
		$checkout_additional_field_notice = (isset($checkout_notice['checkout_additional_field_notice']) && !empty($checkout_notice['checkout_additional_field_notice'])) ? stripslashes($checkout_notice['checkout_additional_field_notice']) : "Please Enter Special Note for Delivery.";
		

		$has_virtual_downloadable_products = $this->helper->check_virtual_downloadable_products();

		$exclude_condition = $this->helper->detect_exclude_condition();

		if(isset($_COOKIE['coderockz_woo_delivery_option_time_pickup'])) {
		  $delivery_option_session = $_COOKIE['coderockz_woo_delivery_option_time_pickup'];
		} elseif(!is_null(WC()->session)) {
		  $delivery_option_session = WC()->session->get( 'coderockz_woo_delivery_option_time_pickup' );
		}

		if ($enable_delivery_option && (!$has_virtual_downloadable_products || $disable_fields_for_downloadable_products) && !$exclude_condition) {
			if (!isset($_POST['coderockz_woo_delivery_delivery_selection_box'])) wc_add_notice(__($checkout_delivery_option_notice) , 'error');
		}

		// if the field is set, if not then show an error message.

		if(($enable_delivery_option && isset($delivery_option_session) && $delivery_option_session == "delivery") && $enable_delivery_date && $delivery_date_mandatory && (!$has_virtual_downloadable_products || $disable_fields_for_downloadable_products) && !$exclude_condition && isset($_POST['coderockz_woo_delivery_date_field'])) {
			if ($_POST['coderockz_woo_delivery_date_field'] == "") wc_add_notice(__($checkout_date_notice) , 'error');
		} elseif (!$enable_delivery_option && $enable_delivery_date && $delivery_date_mandatory && (!$has_virtual_downloadable_products || $disable_fields_for_downloadable_products) && !$exclude_condition && isset($_POST['coderockz_woo_delivery_date_field'])) {
			if ($_POST['coderockz_woo_delivery_date_field'] == "") wc_add_notice(__($checkout_date_notice) , 'error');
		}


		if(($enable_delivery_option && isset($delivery_option_session) && $delivery_option_session == "pickup") && $enable_pickup_date && $pickup_date_mandatory && (!$has_virtual_downloadable_products || $disable_fields_for_downloadable_products) && !$exclude_condition && isset($_POST['coderockz_woo_delivery_pickup_date_field'])) {
			if ($_POST['coderockz_woo_delivery_pickup_date_field'] == "") wc_add_notice(__($checkout_pickup_date_notice) , 'error');
		} elseif (!$enable_delivery_option && $enable_pickup_date && $pickup_date_mandatory && (!$has_virtual_downloadable_products || $disable_fields_for_downloadable_products) && !$exclude_condition && isset($_POST['coderockz_woo_delivery_pickup_date_field'])) {
			if ($_POST['coderockz_woo_delivery_pickup_date_field'] == "") wc_add_notice(__($checkout_pickup_date_notice) , 'error');
		}


		// if the field is set, if not then show an error message.
		if(($enable_delivery_option && isset($delivery_option_session) && $delivery_option_session == "delivery") && $enable_delivery_time && $delivery_time_mandatory && (!$has_virtual_downloadable_products || $disable_fields_for_downloadable_products) && !$exclude_condition && isset($_POST['coderockz_woo_delivery_time_field'])) {
			if ($_POST['coderockz_woo_delivery_time_field'] == "") wc_add_notice(__($checkout_time_notice) , 'error');
			if(($enable_delivery_date && $_POST['coderockz_woo_delivery_date_field'] && !empty($_POST['coderockz_woo_delivery_date_field'])) && ($enable_delivery_time && $_POST['coderockz_woo_delivery_time_field'] && $_POST['coderockz_woo_delivery_time_field'] != "" && $_POST['coderockz_woo_delivery_time_field'] != "as-soon-as-possible")) {
				$this->check_delivery_quantity_before_placed($_POST['coderockz_woo_delivery_date_field'],$_POST['coderockz_woo_delivery_time_field']);
			} elseif((!$enable_delivery_date) && ($enable_delivery_time && $_POST['coderockz_woo_delivery_time_field'] && $_POST['coderockz_woo_delivery_time_field'] != "" && $_POST['coderockz_woo_delivery_time_field'] != "as-soon-as-possible")) {

				$this->check_delivery_quantity_before_placed($today,$_POST['coderockz_woo_delivery_time_field'],true);

			}
			
		} elseif (!$enable_delivery_option && $enable_delivery_time && $delivery_time_mandatory && (!$has_virtual_downloadable_products || $disable_fields_for_downloadable_products) && !$exclude_condition && isset($_POST['coderockz_woo_delivery_time_field'])) {
			if ($_POST['coderockz_woo_delivery_time_field'] == "") wc_add_notice(__($checkout_time_notice) , 'error');
			if(($enable_delivery_date && $_POST['coderockz_woo_delivery_date_field'] && !empty($_POST['coderockz_woo_delivery_date_field'])) && ($enable_delivery_time && $_POST['coderockz_woo_delivery_time_field'] && !empty($_POST['coderockz_woo_delivery_time_field']) && $_POST['coderockz_woo_delivery_time_field'] != "as-soon-as-possible")) {
				$this->check_delivery_quantity_before_placed($_POST['coderockz_woo_delivery_date_field'],$_POST['coderockz_woo_delivery_time_field']);
			} elseif((!$enable_delivery_date) && ($enable_delivery_time && $_POST['coderockz_woo_delivery_time_field'] && !empty($_POST['coderockz_woo_delivery_time_field']) && $_POST['coderockz_woo_delivery_time_field'] != "as-soon-as-possible")) {

				$this->check_delivery_quantity_before_placed($today,$_POST['coderockz_woo_delivery_time_field'],true);

			}
		}
		
		// if the field is set, if not then show an error message.
		if(($enable_delivery_option && isset($delivery_option_session) && $delivery_option_session == "pickup") && $enable_pickup_time && $pickup_time_mandatory && (!$has_virtual_downloadable_products || $disable_fields_for_downloadable_products) && !$exclude_condition && isset($_POST['coderockz_woo_delivery_pickup_time_field'])) {
			if ($_POST['coderockz_woo_delivery_pickup_time_field'] == "") wc_add_notice(__($checkout_pickup_time_notice) , 'error');

			if(($enable_pickup_date && $_POST['coderockz_woo_delivery_pickup_date_field'] && !empty($_POST['coderockz_woo_delivery_pickup_date_field'])) && ($enable_pickup_time && $_POST['coderockz_woo_delivery_pickup_time_field'] && !empty($_POST['coderockz_woo_delivery_pickup_time_field']))) {
				$this->check_pickup_quantity_before_placed($_POST['coderockz_woo_delivery_pickup_date_field'],$_POST['coderockz_woo_delivery_pickup_time_field']);
			} elseif((!$enable_pickup_date) && ($enable_pickup_time && $_POST['coderockz_woo_delivery_pickup_time_field'] && !empty($_POST['coderockz_woo_delivery_pickup_time_field']))) {

				$this->check_pickup_quantity_before_placed($today,$_POST['coderockz_woo_delivery_pickup_time_field'],true);

			}



		} elseif(!$enable_delivery_option && $enable_pickup_time && $pickup_time_mandatory && (!$has_virtual_downloadable_products || $disable_fields_for_downloadable_products) && !$exclude_condition && isset($_POST['coderockz_woo_delivery_pickup_time_field'])) {
			if ($_POST['coderockz_woo_delivery_pickup_time_field'] == "") wc_add_notice(__($checkout_pickup_time_notice) , 'error');
			if(($enable_pickup_date && $_POST['coderockz_woo_delivery_pickup_date_field'] && !empty($_POST['coderockz_woo_delivery_pickup_date_field'])) && ($enable_pickup_time && $_POST['coderockz_woo_delivery_pickup_time_field'] && !empty($_POST['coderockz_woo_delivery_pickup_time_field']))) {
				$this->check_pickup_quantity_before_placed($_POST['coderockz_woo_delivery_pickup_date_field'],$_POST['coderockz_woo_delivery_pickup_time_field']);
			} elseif((!$enable_pickup_date) && ($enable_pickup_time && $_POST['coderockz_woo_delivery_pickup_time_field'] && !empty($_POST['coderockz_woo_delivery_pickup_time_field']))) {

				$this->check_pickup_quantity_before_placed($today,$_POST['coderockz_woo_delivery_pickup_time_field'],true);

			}
		}


		$pickup_location_settings = get_option('coderockz_woo_delivery_pickup_location_settings');
		$enable_pickup_location = (isset($pickup_location_settings['enable_pickup_location']) && !empty($pickup_location_settings['enable_pickup_location'])) ? $pickup_location_settings['enable_pickup_location'] : false;
		$pickup_location_mandatory = (isset($pickup_location_settings['pickup_location_mandatory']) && !empty($pickup_location_settings['pickup_location_mandatory'])) ? $pickup_location_settings['pickup_location_mandatory'] : false;
		// if the field is set, if not then show an error message.
		if(($enable_delivery_option && isset($delivery_option_session) && $delivery_option_session == "pickup") && $enable_pickup_location && $pickup_location_mandatory && (!$has_virtual_downloadable_products || $disable_fields_for_downloadable_products) && !$exclude_condition && isset($_POST['coderockz_woo_delivery_pickup_location_field'])) {
			if ($_POST['coderockz_woo_delivery_pickup_location_field'] == "") wc_add_notice(__($checkout_pickup_notice) , 'error');
		} elseif(!$enable_delivery_option && $enable_pickup_location && $pickup_location_mandatory && (!$has_virtual_downloadable_products || $disable_fields_for_downloadable_products) && !$exclude_condition && isset($_POST['coderockz_woo_delivery_pickup_location_field'])) {
			if ($_POST['coderockz_woo_delivery_pickup_location_field'] == "") wc_add_notice(__($checkout_pickup_notice) , 'error');
		}

		$additional_field_settings = get_option('coderockz_woo_delivery_additional_field_settings');
		$enable_additional_field = (isset($additional_field_settings['enable_additional_field']) && !empty($additional_field_settings['enable_additional_field'])) ? $additional_field_settings['enable_additional_field'] : false;
		$additional_field_mandatory = (isset($additional_field_settings['additional_field_mandatory']) && !empty($additional_field_settings['additional_field_mandatory'])) ? $additional_field_settings['additional_field_mandatory'] : false;
		// if the field is set, if not then show an error message.
		if($enable_additional_field && $additional_field_mandatory && (!$has_virtual_downloadable_products || $disable_fields_for_downloadable_products) && !$exclude_condition && isset($_POST['coderockz_woo_delivery_additional_field_field'])) {
			if ($_POST['coderockz_woo_delivery_additional_field_field'] == "") wc_add_notice(__($checkout_additional_field_notice) , 'error');
		}
		
	}


	public function check_delivery_quantity_before_placed($delivery_date,$delivery_time,$no_delivery_date = false) {

		$delivery_time_settings = get_option('coderockz_woo_delivery_time_settings');
		$timezone = $this->helper->get_the_timezone();
		date_default_timezone_set($timezone);
		$delivery_time = sanitize_text_field($delivery_time);
	    if($no_delivery_date) {
			$order_date = date("Y-m-d", (int)sanitize_text_field(strtotime($delivery_date))); 
			$args = array(
		        'limit' => -1,
		        'date_created' => $order_date,
		        'delivery_time' => $delivery_time,
		        'delivery_type' => "delivery",
		        'return' => 'ids'
		    );



		} else {
			$en_date = $this->helper->date_conversion(sanitize_text_field($delivery_date),"delivery");
			$args = array(
		        'limit' => -1,
		        'delivery_date' => date("Y-m-d", strtotime($en_date)),
		        'delivery_time' => $delivery_time,
		        'delivery_type' => "delivery",
		        'return' => 'ids'
		    );		    
		}

	    $order_ids = wc_get_orders( $args );

	    if($delivery_time != "") {
        	if(strpos($delivery_time, ' - ') !== false) {
        		$delivery_times = explode(' - ', $delivery_time);
				$slot_key_one = explode(':', $delivery_times[0]);
				$slot_key_two = explode(':', $delivery_times[1]);
				$delivery_time = ((int)$slot_key_one[0]*60+(int)$slot_key_one[1]).' - '.((int)$slot_key_two[0]*60+(int)$slot_key_two[1]);
				$delivery_times = explode(" - ",$delivery_time);
        	} else {
        		$delivery_times = [];
        		$slot_key_one = explode(':', $delivery_time);
        		$delivery_time = ((int)$slot_key_one[0]*60+(int)$slot_key_one[1]);
        		$delivery_times[] = $delivery_time;
        	}
    		
		}

		$custom_time_slot_settings = get_option('coderockz_woo_delivery_time_slot_settings');
		$enable_custom_time_slot = (isset($custom_time_slot_settings['enable_custom_time_slot']) && !empty($custom_time_slot_settings['enable_custom_time_slot'])) ? $custom_time_slot_settings['enable_custom_time_slot'] : false;
		if($enable_custom_time_slot) {
			if(isset($custom_time_slot_settings['time_slot']) && count($custom_time_slot_settings['time_slot'])>0){

				foreach($custom_time_slot_settings['time_slot'] as $key => $individual_time_slot) {

		  			if($individual_time_slot['enable']) {
			  			$key = preg_replace('/-/', ' - ', $key);

			  			$key_array = explode(" - ",$key);

					    $max_order = (isset($individual_time_slot['max_order']) && $individual_time_slot['max_order'] != "") ? $individual_time_slot['max_order'] : 10000000000000;

					    if(!empty($delivery_time) && isset($delivery_times[1]) && $delivery_times[0]>= $key_array[0] && $delivery_times[1] <= $key_array[1]) {

				    		$time_max_order = (int)$max_order;

					    } elseif(!empty($delivery_time) && !isset($delivery_times[1]) && $delivery_times[0]>= $key_array[0] && $delivery_times[0] < $key_array[1]) {

					    	$time_max_order = (int)$max_order;
					    }

					}
				}
				
				if (count($order_ids)>=$time_max_order) wc_add_notice(__('Maximum Order Limit Exceed For This Time Slot. Please Reload The Page') , 'error');

			}
		} else {

		    $time_settings = get_option('coderockz_woo_delivery_time_settings');
	  		$x = (int)$time_settings['delivery_time_starts'];
	  		$each_time_slot = (isset($time_settings['each_time_slot']) && !empty($time_settings['each_time_slot'])) ? (int)$time_settings['each_time_slot'] : (int)$time_settings['delivery_time_ends']-(int)$time_settings['delivery_time_starts'];
	  		$max_order = (isset($time_settings['max_order_per_slot']) && $time_settings['max_order_per_slot'] != "") ? $time_settings['max_order_per_slot'] : 10000000000000;

			while((int)$time_settings['delivery_time_ends']>$x) {
				$second_time = $x+$each_time_slot;
				$key = $x . ' - ' . $second_time; 
				if(!empty($delivery_time) && ($delivery_time == $key) ) {	
					$time_max_order = (int)$max_order;
					if (count($order_ids)>=$time_max_order) {
						wc_add_notice(__('Maximum Order Limit Exceed For This Time Slot. Please Reload The Page') , 'error');
					}

					break; 
			    }
				$x = $second_time;
			}
		}

	}


	public function check_pickup_quantity_before_placed($pickup_date,$pickup_time,$no_pickup_date = false) {
		$delivery_time_settings = get_option('coderockz_woo_delivery_time_settings');
		$timezone = $this->helper->get_the_timezone();
		date_default_timezone_set($timezone);
		$pickup_time = sanitize_text_field($pickup_time);
	    if($no_pickup_date) {
			$order_date = date("Y-m-d", (int)sanitize_text_field(strtotime($pickup_date))); 
			$args = array(
		        'limit' => -1,
		        'date_created' => $order_date,
		        'pickup_time' => $pickup_time,
		        'delivery_type' => "pickup",
		        'return' => 'ids'
		    );

		} else {
			$en_date = $this->helper->date_conversion(sanitize_text_field($pickup_date),"pickup");
			$args = array(
		        'limit' => -1,
		        'pickup_date' => date("Y-m-d", strtotime($en_date)),
		        'pickup_time' => $pickup_time,
		        'delivery_type' => "pickup",
		        'return' => 'ids'
		    );		    
		}

	    $order_ids = wc_get_orders( $args );

	    if($pickup_time != "") {
        	if(strpos($pickup_time, ' - ') !== false) {
        		$pickup_times = explode(' - ', $pickup_time);
				$slot_key_one = explode(':', $pickup_times[0]);
				$slot_key_two = explode(':', $pickup_times[1]);
				$pickup_time = ((int)$slot_key_one[0]*60+(int)$slot_key_one[1]).' - '.((int)$slot_key_two[0]*60+(int)$slot_key_two[1]);
				$pickup_times = explode(" - ",$pickup_time);
        	} else {
        		$pickup_times = [];
        		$slot_key_one = explode(':', $pickup_time);
        		$pickup_time = ((int)$slot_key_one[0]*60+(int)$slot_key_one[1]);
        		$pickup_times[] = $pickup_time;
        	}
    		
		}

		$custom_pickup_slot_settings = get_option('coderockz_woo_delivery_pickup_slot_settings');
		$enable_custom_pickup_slot = (isset($custom_pickup_slot_settings['enable_custom_pickup_slot']) && !empty($custom_pickup_slot_settings['enable_custom_pickup_slot'])) ? $custom_pickup_slot_settings['enable_custom_pickup_slot'] : false;
		if($enable_custom_pickup_slot) {
			if(isset($custom_pickup_slot_settings['time_slot']) && count($custom_pickup_slot_settings['time_slot'])>0){

				foreach($custom_pickup_slot_settings['time_slot'] as $key => $individual_pickup_slot) {

		  			if($individual_pickup_slot['enable']) {
			  			$key = preg_replace('/-/', ' - ', $key);

			  			$key_array = explode(" - ",$key);

					    $max_order = (isset($individual_pickup_slot['max_order']) && $individual_pickup_slot['max_order'] != "") ? $individual_pickup_slot['max_order'] : 10000000000000;
					
					    /*if($individual_pickup_slot['enable_single_splited_slot'] || $individual_pickup_slot['enable_single_slot']) {
					    	$x = $key_array[0];
							while($key_array[1]>=$x) {
								$second_time = $x+(int)$individual_pickup_slot['split_slot_duration'];
								if(!empty($pickup_time) && $pickup_times[0] == $x && !isset($pickup_times[1])) {
						    		$pickup_max_order = (int)$max_order;
						    		break;
						    	}								
								$x = $second_time;
							}
					    } elseif(!empty($pickup_time) && ($pickup_time == $key || ($pickup_times[0]>= $key_array[0] && (!empty($pickup_times[1]) && $pickup_times[1] <= $key_array[1])))) {
					    	$pickup_max_order = (int)$max_order;
					    	break;
					    }*/

					    if(!empty($pickup_time) && isset($pickup_times[1]) && $pickup_times[0]>= $key_array[0] && $pickup_times[1] <= $key_array[1]) {

				    		$pickup_max_order = (int)$max_order;

					    } elseif(!empty($pickup_time) && !isset($pickup_times[1]) && $pickup_times[0]>= $key_array[0] && $pickup_times[0] < $key_array[1]) {

					    	$pickup_max_order = (int)$max_order;
					    }

					}
				}
				if (count($order_ids)>=$pickup_max_order) wc_add_notice(__('Maximum Order Limit Exceed For This Pickup Slot. Please Reload The Page') , 'error');
			}
		} else {

		    $pickup_settings = get_option('coderockz_woo_delivery_pickup_settings');
	  		$x = (int)$pickup_settings['pickup_time_starts'];
	  		$each_time_slot = (isset($pickup_settings['each_time_slot']) && !empty($pickup_settings['each_time_slot'])) ? (int)$pickup_settings['each_time_slot'] : (int)$pickup_settings['pickup_time_ends']-(int)$pickup_settings['pickup_time_starts'];
	  		$max_order = (isset($pickup_settings['max_pickup_per_slot']) && $pickup_settings['max_pickup_per_slot'] != "") ? $pickup_settings['max_pickup_per_slot'] : 10000000000000;

			while((int)$pickup_settings['pickup_time_ends']>$x) {
				$second_time = $x+$each_time_slot;
				$key = $x . ' - ' . $second_time; 
				if(!empty($pickup_time) && ($pickup_time == $key) ) {	
					$pickup_max_order = (int)$max_order;
					if (count($order_ids)>=$pickup_max_order) {
						wc_add_notice(__('Maximum Order Limit Exceed For This Pickup Slot. Please Reload The Page') , 'error');
					}

					break; 
			    }
				$x = $second_time;
			}

		}

	}

	/**
	 * Update value of field
	*/
	public function coderockz_woo_delivery_customise_checkout_field_update_order_meta($order_id) {
		
		$delivery_time_settings = get_option('coderockz_woo_delivery_time_settings');
		$timezone = $this->helper->get_the_timezone();
		date_default_timezone_set($timezone);

		if(isset($_POST['coderockz_woo_delivery_date_field'])) {
			$en_delivery_date = $this->helper->date_conversion(sanitize_text_field($_POST['coderockz_woo_delivery_date_field']),"delivery");
		}
		
		if(isset($_POST['coderockz_woo_delivery_pickup_date_field'])) {
			$en_pickup_date = $this->helper->date_conversion(sanitize_text_field($_POST['coderockz_woo_delivery_pickup_date_field']),"pickup");
		}
		
		$delivery_option_settings = get_option('coderockz_woo_delivery_option_delivery_settings');
		$delivery_date_settings = get_option('coderockz_woo_delivery_date_settings');
		$pickup_date_settings = get_option('coderockz_woo_delivery_pickup_date_settings');
		$pickup_time_settings = get_option('coderockz_woo_delivery_pickup_settings');
		$enable_delivery_option = (isset($delivery_option_settings['enable_option_time_pickup']) && !empty($delivery_option_settings['enable_option_time_pickup'])) ? $delivery_option_settings['enable_option_time_pickup'] : false;

		$enable_delivery_date = (isset($delivery_date_settings['enable_delivery_date']) && !empty($delivery_date_settings['enable_delivery_date'])) ? $delivery_date_settings['enable_delivery_date'] : false;

		$enable_pickup_date = (isset($pickup_date_settings['enable_pickup_date']) && !empty($pickup_date_settings['enable_pickup_date'])) ? $pickup_date_settings['enable_pickup_date'] : false;

		$enable_delivery_time = (isset($delivery_time_settings['enable_delivery_time']) && !empty($delivery_time_settings['enable_delivery_time'])) ? $delivery_time_settings['enable_delivery_time'] : false;
	  	
		$enable_pickup_time = (isset($pickup_time_settings['enable_pickup_time']) && !empty($pickup_time_settings['enable_pickup_time'])) ? $pickup_time_settings['enable_pickup_time'] : false;

		$disable_fields_for_downloadable_products = (isset(get_option('coderockz_woo_delivery_other_settings')['disable_fields_for_downloadable_products']) && !empty(get_option('coderockz_woo_delivery_other_settings')['disable_fields_for_downloadable_products'])) ? get_option('coderockz_woo_delivery_other_settings')['disable_fields_for_downloadable_products'] : false;

		$has_virtual_downloadable_products = $this->helper->check_virtual_downloadable_products();

		$exclude_condition = $this->helper->detect_exclude_condition();
	  	
		if ($enable_delivery_option && $_POST['coderockz_woo_delivery_delivery_selection_box'] != "" && !$exclude_condition) {
			update_post_meta($order_id, 'delivery_type', $_POST['coderockz_woo_delivery_delivery_selection_box']);
		} elseif(!$enable_delivery_option && (($enable_delivery_time && !$enable_pickup_time) || ($enable_delivery_date && !$enable_pickup_date)) && $_POST['coderockz_woo_delivery_time_field'] != "" && (!$has_virtual_downloadable_products || $disable_fields_for_downloadable_products) && !$exclude_condition) {
			update_post_meta($order_id, 'delivery_type', 'delivery');
		} elseif(!$enable_delivery_option && ((!$enable_delivery_time && $enable_pickup_time) || (!$enable_delivery_date && $enable_pickup_date)) && $_POST['coderockz_woo_delivery_pickup_time_field'] != "" && (!$has_virtual_downloadable_products || $disable_fields_for_downloadable_products) && !$exclude_condition) {
			update_post_meta($order_id, 'delivery_type', 'pickup');
		}


		if(isset($_COOKIE['coderockz_woo_delivery_option_time_pickup'])) {
		  $delivery_option_session = $_COOKIE['coderockz_woo_delivery_option_time_pickup'];
		} elseif(!is_null(WC()->session)) {
		  $delivery_option_session = WC()->session->get( 'coderockz_woo_delivery_option_time_pickup' );
		}

	  	if(($enable_delivery_option && isset($delivery_option_session) && $delivery_option_session == "delivery") && $enable_delivery_date && $_POST['coderockz_woo_delivery_date_field'] != "" && (!$has_virtual_downloadable_products || $disable_fields_for_downloadable_products) && !$exclude_condition) {
			update_post_meta($order_id, 'delivery_date', date("Y-m-d", strtotime($en_delivery_date)));
		} elseif (!$enable_delivery_option && $enable_delivery_date && $_POST['coderockz_woo_delivery_date_field'] != "" && (!$has_virtual_downloadable_products || $disable_fields_for_downloadable_products) && !$exclude_condition) {
			update_post_meta($order_id, 'delivery_date', date("Y-m-d", strtotime($en_delivery_date)));
		}

		if(($enable_delivery_option && isset($delivery_option_session) && $delivery_option_session == "pickup") && $enable_pickup_date && $_POST['coderockz_woo_delivery_pickup_date_field'] != "" && (!$has_virtual_downloadable_products || $disable_fields_for_downloadable_products) && !$exclude_condition) {
			update_post_meta($order_id, 'pickup_date', date("Y-m-d", strtotime($en_pickup_date)));
		} elseif (!$enable_delivery_option && $enable_pickup_date && $_POST['coderockz_woo_delivery_pickup_date_field'] != "" && (!$has_virtual_downloadable_products || $disable_fields_for_downloadable_products) && !$exclude_condition) {
			update_post_meta($order_id, 'pickup_date', date("Y-m-d", strtotime($en_pickup_date)));
		}


		if(($enable_delivery_option && isset($delivery_option_session) && $delivery_option_session == "delivery") && $enable_delivery_time && $_POST['coderockz_woo_delivery_time_field'] != "" && (!$has_virtual_downloadable_products || $disable_fields_for_downloadable_products) && !$exclude_condition) {
			update_post_meta($order_id, 'delivery_time', sanitize_text_field($_POST['coderockz_woo_delivery_time_field']));
		} elseif (!$enable_delivery_option && $enable_delivery_time && $_POST['coderockz_woo_delivery_time_field'] != "" && (!$has_virtual_downloadable_products || $disable_fields_for_downloadable_products) && !$exclude_condition) {
			update_post_meta($order_id, 'delivery_time', sanitize_text_field($_POST['coderockz_woo_delivery_time_field']));
		}

		if(($enable_delivery_option && isset($delivery_option_session) && $delivery_option_session == "pickup") && $enable_pickup_time && $_POST['coderockz_woo_delivery_pickup_time_field'] != "" && (!$has_virtual_downloadable_products || $disable_fields_for_downloadable_products) && !$exclude_condition) {
			update_post_meta($order_id, 'pickup_time', sanitize_text_field($_POST['coderockz_woo_delivery_pickup_time_field']));
		} elseif(!$enable_delivery_option && $enable_pickup_time && $_POST['coderockz_woo_delivery_pickup_time_field'] != "" && (!$has_virtual_downloadable_products || $disable_fields_for_downloadable_products) && !$exclude_condition) {
			update_post_meta($order_id, 'pickup_time', sanitize_text_field($_POST['coderockz_woo_delivery_pickup_time_field']));
		}


	  	$pickup_location_settings = get_option('coderockz_woo_delivery_pickup_location_settings');
		$enable_pickup_location = (isset($pickup_location_settings['enable_pickup_location']) && !empty($pickup_location_settings['enable_pickup_location'])) ? $pickup_location_settings['enable_pickup_location'] : false;
	  	if(($enable_delivery_option && isset($delivery_option_session) && $delivery_option_session == "pickup") && $enable_pickup_location && $_POST['coderockz_woo_delivery_pickup_location_field'] != "" && (!$has_virtual_downloadable_products || $disable_fields_for_downloadable_products) && !$exclude_condition) {
			update_post_meta($order_id, 'delivery_pickup', sanitize_text_field($_POST['coderockz_woo_delivery_pickup_location_field']));
		} elseif(!$enable_delivery_option && $enable_pickup_location && $_POST['coderockz_woo_delivery_pickup_location_field'] != "" && (!$has_virtual_downloadable_products || $disable_fields_for_downloadable_products) && !$exclude_condition) {
			update_post_meta($order_id, 'delivery_pickup', sanitize_text_field($_POST['coderockz_woo_delivery_pickup_location_field']));
		}

	  	$additional_field_settings = get_option('coderockz_woo_delivery_additional_field_settings');
		$enable_additional_field = (isset($additional_field_settings['enable_additional_field']) && !empty($additional_field_settings['enable_additional_field'])) ? $additional_field_settings['enable_additional_field'] : false;
	  	if ($enable_additional_field && $_POST['coderockz_woo_delivery_additional_field_field'] != "" && (!$has_virtual_downloadable_products || $disable_fields_for_downloadable_products) && !$exclude_condition) {
			update_post_meta($order_id, 'additional_note', sanitize_textarea_field($_POST['coderockz_woo_delivery_additional_field_field']));
	  	}
	}

	public function coderockz_woo_delivery_option_delivery_time_pickup() {
		check_ajax_referer('coderockz_woo_delivery_nonce');
		//WC()->session->__unset( 'coderockz_woo_delivery_option_time_pickup' );
		/*if(isset($_COOKIE['coderockz_woo_delivery_option_time_pickup'])) {
		    unset($_COOKIE["coderockz_woo_delivery_option_time_pickup"]);
			setcookie("coderockz_woo_delivery_option_time_pickup", null, -1, '/');
		} 

		if(!is_null(WC()->session)) {		  
			WC()->session->__unset( 'coderockz_woo_delivery_option_time_pickup' );  
		}*/
		$delivery_option = (isset($_POST['deliveryOption']) && $_POST['deliveryOption'] !="") ? sanitize_text_field($_POST['deliveryOption']) : "";
		setcookie('coderockz_woo_delivery_option_time_pickup', $delivery_option, time() + 60 * 60, '/');
		WC()->session->set( 'coderockz_woo_delivery_option_time_pickup', $delivery_option );

		$delivery_date_settings = get_option('coderockz_woo_delivery_date_settings');
		$pickup_date_settings = get_option('coderockz_woo_delivery_pickup_date_settings');

		$timezone = $this->helper->get_the_timezone();
		date_default_timezone_set($timezone);

		$disable_for_max_delivery_dates = [];
		$disable_for_max_pickup_dates = [];
		$today = date('Y-m-d', time());
		$range_first_date = date('Y-m-d', strtotime($today));
		$formated_obj = new DateTime($range_first_date);
	    
		if($delivery_option == "delivery" && (isset($delivery_date_settings['maximum_order_per_day']) && $delivery_date_settings['maximum_order_per_day'] != "") ) {

			$range_last_date = $formated_obj->modify("+40 day")->format("Y-m-d");
			$filtered_date = $range_first_date . ',' . $range_last_date;
			$filtered_dates = explode(',', $filtered_date);
			$period = new DatePeriod(new DateTime($filtered_dates[0]), new DateInterval('P1D'), new DateTime($filtered_dates[1].' +1 day'));


			$max_order_per_day = (isset($delivery_date_settings['maximum_order_per_day']) && $delivery_date_settings['maximum_order_per_day'] != "") ? (int)$delivery_date_settings['maximum_order_per_day'] : 10000000000000;
			foreach ($period as $date) { 
				$args = array(
			        'limit' => -1,
			        'delivery_date' => date("Y-m-d", strtotime($date->format("Y-m-d"))),
			        'delivery_type' => "delivery",
			        'return' => 'ids'
			    );
			    $orders_array = wc_get_orders( $args );
			    if(count($orders_array) >= $max_order_per_day) {
					$disable_for_max_delivery_dates[] = date('Y-m-d', strtotime($date->format("Y-m-d")));
			    }
			}

			$disable_for_max_delivery_dates = array_unique($disable_for_max_delivery_dates, false);
			$disable_for_max_delivery_dates = array_values($disable_for_max_delivery_dates);

		} elseif($delivery_option == "pickup" && (isset($pickup_date_settings['maximum_pickup_per_day']) && $pickup_date_settings['maximum_pickup_per_day'] != "")) {

			$range_last_date = $formated_obj->modify("+40 day")->format("Y-m-d");
			$filtered_date = $range_first_date . ',' . $range_last_date;
			$filtered_dates = explode(',', $filtered_date);
			$period = new DatePeriod(new DateTime($filtered_dates[0]), new DateInterval('P1D'), new DateTime($filtered_dates[1].' +1 day'));

			$max_pickup_per_day = (isset($pickup_date_settings['maximum_pickup_per_day']) && $pickup_date_settings['maximum_pickup_per_day'] != "") ? (int)$pickup_date_settings['maximum_pickup_per_day'] : 10000000000000;
			foreach ($period as $date) {
				$args = array(
			        'limit' => -1,
			        'pickup_date' => date("Y-m-d", strtotime($date->format("Y-m-d"))),
			        'delivery_type' => "pickup",
			        'return' => 'ids'
			    );
			    $orders_array = wc_get_orders( $args );
			    if(count($orders_array) >= $max_pickup_per_day) {
					$disable_for_max_pickup_dates[] = date('Y-m-d', strtotime($date->format("Y-m-d")));
			    }
			}

			$disable_for_max_pickup_dates = array_unique($disable_for_max_pickup_dates, false);
			$disable_for_max_pickup_dates = array_values($disable_for_max_pickup_dates);
		}

		$disable_delivery_date_passed_time = [];
		$disable_pickup_date_passed_time = [];

		$delivery_time_settings = get_option('coderockz_woo_delivery_time_settings');
		$pickup_time_settings = get_option('coderockz_woo_delivery_pickup_settings');

		$enable_delivery_time = (isset($delivery_time_settings['enable_delivery_time']) && !empty($delivery_time_settings['enable_delivery_time'])) ? $delivery_time_settings['enable_delivery_time'] : false;
	  	
		$enable_pickup_time = (isset($pickup_time_settings['enable_pickup_time']) && !empty($pickup_time_settings['enable_pickup_time'])) ? $pickup_time_settings['enable_pickup_time'] : false;
		
		
		if($enable_delivery_time) {
			$time_slot_end = [0];
			$custom_time_slot_settings = get_option('coderockz_woo_delivery_time_slot_settings');
			$enable_custom_time_slot = (isset($custom_time_slot_settings['enable_custom_time_slot']) && !empty($custom_time_slot_settings['enable_custom_time_slot'])) ? $custom_time_slot_settings['enable_custom_time_slot'] : false;
			if($enable_custom_time_slot) {
				if(isset($custom_time_slot_settings['time_slot']) && count($custom_time_slot_settings['time_slot'])>0){				
					foreach($custom_time_slot_settings['time_slot'] as $key => $individual_time_slot) {

			  			if($individual_time_slot['enable'] && !in_array(date("w"),$individual_time_slot['disable_for'])) {
				  			$key = preg_replace('/-/', ',', $key);

				  			$key_array = explode(",",$key);

						
						    if($individual_time_slot['enable_split']) {
						    	$x = $key_array[0];
								while($key_array[1]>$x) {
									$second_time = $x+$individual_time_slot['split_slot_duration'];

									if($individual_time_slot['enable_single_splited_slot']) {
										$time_slot_end[] = (int)$x;
									} else {
										$time_slot_end[] = (int)$second_time;
									}
																	
									$x = $second_time;
								}
						    } else {
						    	if($individual_time_slot['enable_single_slot']) {
									$time_slot_end[] = (int)$individual_time_slot['start'];
								} else {
									$time_slot_end[] = (int)$individual_time_slot['end'];
								}
						    }

						}
					}
				}
			} else {

				$time_settings = get_option('coderockz_woo_delivery_time_settings');
				$time_slot_end[] = (int)$time_settings['delivery_time_ends'];												
			}

			$highest_timeslot_end = max($time_slot_end);

			$current_time = (date("G")*60)+date("i");

			if($current_time>$highest_timeslot_end) {
				$disable_delivery_date_passed_time[] = date('Y-m-d', time());
			}

		}

		if($enable_pickup_time) {

			$pickup_slot_end = [0];
			$custom_pickup_slot_settings = get_option('coderockz_woo_delivery_pickup_slot_settings');
			$enable_custom_pickup_slot = (isset($custom_pickup_slot_settings['enable_custom_pickup_slot']) && !empty($custom_pickup_slot_settings['enable_custom_pickup_slot'])) ? $custom_pickup_slot_settings['enable_custom_pickup_slot'] : false;
			if($enable_custom_pickup_slot) {
				if(isset($custom_pickup_slot_settings['time_slot']) && count($custom_pickup_slot_settings['time_slot'])>0){

					foreach($custom_pickup_slot_settings['time_slot'] as $pickup_key => $individual_pickup_slot) {

			  			if($individual_pickup_slot['enable'] && !in_array(date("w"),$individual_pickup_slot['disable_for'])) {
				  			$pickup_key = preg_replace('/-/', ',', $pickup_key);

				  			$pickup_key_array = explode(",",$pickup_key);
						
						    if($individual_pickup_slot['enable_split']) {
						    	$pickup_x = $pickup_key_array[0];
								while($pickup_key_array[1]>$pickup_x) {
									$pickup_second_time = $pickup_x+$individual_pickup_slot['split_slot_duration'];

									if($individual_pickup_slot['enable_single_splited_slot']) {
										$pickup_slot_end[] = (int)$pickup_x;
									} else {
										$pickup_slot_end[] = (int)$pickup_second_time;
									}

									$pickup_x = $pickup_second_time;
								}
						    } else {
						    	if($individual_pickup_slot['enable_single_slot']) {
									$pickup_slot_end[] = (int)$individual_pickup_slot['start'];
								} else {
									$pickup_slot_end[] = (int)$individual_pickup_slot['end'];
								}
						    }

						}
					}
				}
			} else {

		    	$pickup_settings = get_option('coderockz_woo_delivery_pickup_settings');
				$pickup_slot_end[] = (int)$pickup_settings['pickup_time_ends'];;
			}

			$highest_pickupslot_end = max($pickup_slot_end);

			$current_time = (date("G")*60)+date("i");
			if($current_time>$highest_pickupslot_end) {
				$disable_pickup_date_passed_time[] = date('Y-m-d', time());
			}
		}

		$response=[
			"disable_for_max_delivery_dates" => $disable_for_max_delivery_dates,
			"disable_for_max_pickup_dates" => $disable_for_max_pickup_dates,
			"disable_delivery_date_passed_time" => $disable_delivery_date_passed_time,
			"disable_pickup_date_passed_time" => $disable_pickup_date_passed_time,
		];
		$response = json_encode($response);
		wp_send_json_success($response);
	}

	//Without this function of filter "woocommerce_order_data_store_cpt_get_orders_query" query with post_meta "delivery_date" is not possible
	public function coderockz_woo_delivery_handle_custom_query_var( $query, $query_vars ) {
		if ( ! empty( $query_vars['delivery_date'] ) ) {
			$query['meta_query'][] = array(
				'key' => 'delivery_date',
				'value' => esc_attr( $query_vars['delivery_date'] ),
			);
		}

		if ( ! empty( $query_vars['pickup_date'] ) ) {
			$query['meta_query'][] = array(
				'key' => 'pickup_date',
				'value' => esc_attr( $query_vars['pickup_date'] ),
			);
		}

		if ( ! empty( $query_vars['delivery_type'] ) ) {
			$query['meta_query'][] = array(
				'key' => 'delivery_type',
				'value' => esc_attr( $query_vars['delivery_type'] ),
			);
		}

		if ( ! empty( $query_vars['delivery_time'] ) ) {
			$query['meta_query'][] = array(
				'key' => 'delivery_time',
				'value' => esc_attr( $query_vars['delivery_time'] ),
			);
		}

		if ( ! empty( $query_vars['pickup_time'] ) ) {
			$query['meta_query'][] = array(
				'key' => 'pickup_time',
				'value' => esc_attr( $query_vars['pickup_time'] ),
			);
		}

		return $query;
	}

	public function coderockz_woo_delivery_get_orders() {

		check_ajax_referer('coderockz_woo_delivery_nonce');
		
		$delivery_time_settings = get_option('coderockz_woo_delivery_time_settings');

		// if any timezone data is saved, set default timezone with the data
		$timezone = $this->helper->get_the_timezone();
		date_default_timezone_set($timezone);
		
		$disabled_current_time_slot = (isset($delivery_time_settings['disabled_current_time_slot']) && !empty($delivery_time_settings['disabled_current_time_slot'])) ? $delivery_time_settings['disabled_current_time_slot'] : false;

		$custom_time_slot_settings = get_option('coderockz_woo_delivery_time_slot_settings');
		$enable_custom_time_slot = (isset($custom_time_slot_settings['enable_custom_time_slot']) && !empty($custom_time_slot_settings['enable_custom_time_slot'])) ? $custom_time_slot_settings['enable_custom_time_slot'] : false;

		if(isset($_POST['onlyDeliveryTime']) && $_POST['onlyDeliveryTime']) {
			$order_date = date("Y-m-d", strtotime(sanitize_text_field($_POST['date']))); 
			$args = array(
		        'limit' => -1,
		        'date_created' => $order_date,
		        'return' => 'ids'
		    );

		} else {
			$delivery_date = date("Y-m-d", strtotime(sanitize_text_field($_POST['date'])));
			$args = array(
		        'limit' => -1,
		        'delivery_date' => $delivery_date,
		        'return' => 'ids'
		    );		    
		}

		$order_ids = wc_get_orders( $args );

		$given_state = (isset($_POST['givenState']) && $_POST['givenState'] !="") ? sanitize_text_field($_POST['givenState']) : "";
		$given_zip = (isset($_POST['givenZip']) && $_POST['givenZip'] !="") ? sanitize_text_field($_POST['givenZip']) : "";
		$response_delivery = [];
		$delivery_times = [];
		$max_order_per_slot = [];
		$slot_disable_for = [];
		$disable_timeslot = [];
		$state_zip_disable_timeslot_all = [];
		$no_state_zip_disable_timeslot_all = [];
		if($enable_custom_time_slot && isset($custom_time_slot_settings['time_slot']) && count($custom_time_slot_settings['time_slot'])>0){
	  		foreach($custom_time_slot_settings['time_slot'] as $key => $individual_time_slot) {

	  			if($individual_time_slot['enable']) {
	  				$times = explode('-', $key);
		  			if($individual_time_slot['enable_split']) {
						$times = explode('-', $key);
						$x = $times[0];
						while($times[1]>$x) {
							$second_time = $x+$individual_time_slot['split_slot_duration'];
							$disable = $individual_time_slot['disable_for'];
							if($individual_time_slot['enable_single_splited_slot']) {
								$slot_disable_for[date("H:i", mktime(0, (int)$x))] = $disable;
							} else {
								$slot_disable_for[date("H:i", mktime(0, (int)$x)) . ' - ' . date("H:i", mktime(0, (int)$second_time))] = $disable;
							}
							
							$x = $second_time;
						}
					} else {
						$times = explode('-', $key);
						$disable = $individual_time_slot['disable_for'];
						if($individual_time_slot['enable_single_slot']) {
							$slot_disable_for[date("H:i", mktime(0, (int)$times[0]))] = $disable;
						} else {
							$slot_disable_for[date("H:i", mktime(0, (int)$times[0])) . ' - ' . date("H:i", mktime(0, (int)$times[1]))] = $disable;
						}
						
					}

		  			if((isset($individual_time_slot['disable_state']) && !empty($individual_time_slot['disable_state']) && in_array($given_state,$individual_time_slot['disable_state']))){
		  				$times = explode('-', $key);

						if($individual_time_slot['enable_split']) {
							
							$x = $times[0];
							while($times[1]>$x) {
								$second_time = $x+$individual_time_slot['split_slot_duration'];
								$disable = $individual_time_slot['disable_for'];
								if($individual_time_slot['enable_single_splited_slot']) {
									$disable_timeslot['state'][] = date("H:i", mktime(0, (int)$x));								
								} else {
									$disable_timeslot['state'][] = date("H:i", mktime(0, (int)$x)) . ' - ' . date("H:i", mktime(0, (int)$second_time));							
								}
								
								$x = $second_time;
							}
						} else {

							if($individual_time_slot['enable_single_slot']) {
								$disable_timeslot['state'][] = date("H:i", mktime(0, (int)$times[0]));								
							} else {
								$disable_timeslot['state'][] = date("H:i", mktime(0, (int)$times[0])) . ' - ' . date("H:i", mktime(0, (int)$times[1]));							
							}
							
						}		  				 
		  			} 

		  			if(isset($individual_time_slot['disable_postcode']) && !empty($individual_time_slot['disable_postcode'])) {

		  				foreach($individual_time_slot['disable_postcode'] as $postcode_value) {
							$postcode_range = [];
						    if (stripos($postcode_value,'...') !== false) {
						    	$range = explode('...', $postcode_value);
						    	$x = $range[0];
						    	while($x<=$range[1]) {
						    		$postcode_range[] = (string)$x;
						    		$x = $x+1;
						    	}
						    }
						    if (substr($postcode_value, -1) == '*') {
						    	if($this->helper->starts_with($given_zip,substr($postcode_value, 0, -1))) {
						    		$times = explode('-', $key);
									if($individual_time_slot['enable_split']) {
										$x = $times[0];
										while($times[1]>$x) {
											$second_time = $x+$individual_time_slot['split_slot_duration'];
											$disable = $individual_time_slot['disable_for'];
											if($individual_time_slot['enable_single_splited_slot']) {
												$disable_timeslot['postcode'][] = date("H:i", mktime(0, (int)$x));								
											} else {
												$disable_timeslot['postcode'][] = date("H:i", mktime(0, (int)$x)) . ' - ' . date("H:i", mktime(0, (int)$second_time));							
											}
											
											$x = $second_time;
										}
									} else {
										if($individual_time_slot['enable_single_slot']) {
											$disable_timeslot['postcode'][] = date("H:i", mktime(0, (int)$times[0]));								
										} else {
											$disable_timeslot['postcode'][] = date("H:i", mktime(0, (int)$times[0])) . ' - ' . date("H:i", mktime(0, (int)$times[1]));						
										}						
									}
						    	}
						    } elseif(in_array($given_zip,$postcode_range) || $postcode_value == $given_zip) {
						    	$times = explode('-', $key);
								if($individual_time_slot['enable_split']) {
									$x = $times[0];
									while($times[1]>$x) {
										$second_time = $x+$individual_time_slot['split_slot_duration'];
										$disable = $individual_time_slot['disable_for'];
										if($individual_time_slot['enable_single_splited_slot']) {
											$disable_timeslot['postcode'][] = date("H:i", mktime(0, (int)$x));								
										} else {
											$disable_timeslot['postcode'][] = date("H:i", mktime(0, (int)$x)) . ' - ' . date("H:i", mktime(0, (int)$second_time));							
										}
										
										$x = $second_time;
									}
								} else {
									if($individual_time_slot['enable_single_slot']) {
										$disable_timeslot['postcode'][] = date("H:i", mktime(0, (int)$times[0]));								
									} else {
										$disable_timeslot['postcode'][] = date("H:i", mktime(0, (int)$times[0])) . ' - ' . date("H:i", mktime(0, (int)$times[1]));						
									}						
								}
						    }
						}		  				 
		  			}


		  			if((isset($individual_time_slot['disable_state']) && !empty($individual_time_slot['disable_state']))){
		  				$times = explode('-', $key);
						if($individual_time_slot['enable_split']) {
							$x = $times[0];
							while($times[1]>$x) {
								$second_time = $x+$individual_time_slot['split_slot_duration'];
								$disable = $individual_time_slot['disable_for'];
								if($individual_time_slot['enable_single_splited_slot']) {
									$state_zip_disable_timeslot_all['state'][] = date("H:i", mktime(0, (int)$x));								
								} else {
									$state_zip_disable_timeslot_all['state'][] = date("H:i", mktime(0, (int)$x)) . ' - ' . date("H:i", mktime(0, (int)$second_time));							
								}
								
								$x = $second_time;
							}
						} else {
							if($individual_time_slot['enable_single_slot']) {
								$state_zip_disable_timeslot_all['state'][] = date("H:i", mktime(0, (int)$times[0]));								
							} else {
								$state_zip_disable_timeslot_all['state'][] = date("H:i", mktime(0, (int)$times[0])) . ' - ' . date("H:i", mktime(0, (int)$times[1]));							
							}
							
						}		  				 
		  			}

		  			if((isset($individual_time_slot['disable_postcode']) && !empty($individual_time_slot['disable_postcode']))){
		  				$times = explode('-', $key);
						if($individual_time_slot['enable_split']) {
							$x = $times[0];
							while($times[1]>$x) {
								$second_time = $x+$individual_time_slot['split_slot_duration'];
								$disable = $individual_time_slot['disable_for'];
								if($individual_time_slot['enable_single_splited_slot']) {
									$state_zip_disable_timeslot_all['postcode'][] = date("H:i", mktime(0, (int)$x));								
								} else {
									$state_zip_disable_timeslot_all['postcode'][] = date("H:i", mktime(0, (int)$x)) . ' - ' . date("H:i", mktime(0, (int)$second_time));							
								}
								
								$x = $second_time;
							}
						} else {
							if($individual_time_slot['enable_single_slot']) {
								$state_zip_disable_timeslot_all['postcode'][] = date("H:i", mktime(0, (int)$times[0]));								
							} else {
								$state_zip_disable_timeslot_all['postcode'][] = date("H:i", mktime(0, (int)$times[0])) . ' - ' . date("H:i", mktime(0, (int)$times[1]));							
							}						
						}		  				 
		  			} else {
		  				$times = explode('-', $key);
						if($individual_time_slot['enable_split']) {
							$x = $times[0];
							while($times[1]>=$x) {
								$second_time = $x+$individual_time_slot['split_slot_duration'];
								$disable = $individual_time_slot['disable_for'];
								if($individual_time_slot['enable_single_splited_slot']) {
									$no_state_zip_disable_timeslot_all['nostatezip'][] = date("H:i", mktime(0, (int)$x));								
								} else {
									$no_state_zip_disable_timeslot_all['nostatezip'][] = date("H:i", mktime(0, (int)$x)) . ' - ' . date("H:i", mktime(0, (int)$second_time));							
								}
								
								$x = $second_time;
							}

						} else {
							if($individual_time_slot['enable_single_slot']) {
								$no_state_zip_disable_timeslot_all['nostatezip'][] = date("H:i", mktime(0, (int)$times[0]));								
							} else {
								$no_state_zip_disable_timeslot_all['nostatezip'][] = date("H:i", mktime(0, (int)$times[0])) . ' - ' . date("H:i", mktime(0, (int)$times[1]));							
							}

						}		  				 
		  			}
	  			}
	  		}
	  	} else {
	  		$time_settings = get_option('coderockz_woo_delivery_time_settings');
	  		$x = (int)$time_settings['delivery_time_starts'];
	  		$each_time_slot = (isset($time_settings['each_time_slot']) && !empty($time_settings['each_time_slot'])) ? (int)$time_settings['each_time_slot'] : (int)$time_settings['delivery_time_ends']-(int)$time_settings['delivery_time_starts'];
			while((int)$time_settings['delivery_time_ends']>$x) {
				$second_time = $x+$each_time_slot;
				$no_state_zip_disable_timeslot_all['nostatezip'][] = date("H:i", mktime(0, (int)$x)) . ' - ' . date("H:i", mktime(0, (int)$second_time));		
				$x = $second_time;
			}  		
	  	}

	  	foreach ($order_ids as $order) {
			$date = get_post_meta($order,"delivery_date",true);
			$time = get_post_meta($order,"delivery_time",true);
			
			if((isset($date) && isset($time)) || isset($time)) {
				if($time != "as-soon-as-possible") {
					$delivery_times[] = $time;
				}
				
			}

		}

		$unique_delivery_times = array_unique($delivery_times, false);
		$unique_delivery_times = array_values($unique_delivery_times);

		if($enable_custom_time_slot) {
			if(isset($custom_time_slot_settings['time_slot']) && count($custom_time_slot_settings['time_slot'])>0){

				foreach($custom_time_slot_settings['time_slot'] as $key => $individual_time_slot) {

		  			if($individual_time_slot['enable']) {
			  			$key = preg_replace('/-/', ',', $key);

			  			$key_array = explode(",",$key);

					    $max_order = $individual_time_slot['max_order'];
					
					    if($individual_time_slot['enable_split']) {
							$x = $key_array[0];
							while($key_array[1]>=$x) {
								$second_time = $x+$individual_time_slot['split_slot_duration'];

								if($individual_time_slot['enable_single_splited_slot']) {
									if(in_array(date("H:i", mktime(0, (int)$x)),$unique_delivery_times)) {
										$max_order_per_slot[date("H:i", mktime(0, (int)$x))] = (int)$max_order;
									}
									
								} else {
									if(in_array(date("H:i", mktime(0, (int)$x)) . ' - ' . date("H:i", mktime(0, (int)$second_time)),$unique_delivery_times)) {
									$max_order_per_slot[date("H:i", mktime(0, (int)$x)) . ' - ' . date("H:i", mktime(0, (int)$second_time))] = (int)$max_order;
									}						
								}
								
								$x = $second_time;
							}

						} else {
							if($individual_time_slot['enable_single_slot']) {
					
								if(in_array(date("H:i", mktime(0, (int)$key_array[0])),$unique_delivery_times)) {
									$max_order_per_slot[date("H:i", mktime(0, (int)$key_array[0]))] = (int)$max_order;
								}
							} else {
								if(in_array(date("H:i", mktime(0, (int)$key_array[0])) . ' - ' . date("H:i", mktime(0, (int)$key_array[1])),$unique_delivery_times)) {
									$max_order_per_slot[date("H:i", mktime(0, (int)$key_array[0])) . ' - ' . date("H:i", mktime(0, (int)$key_array[1]))] = (int)$max_order;	
								}						
							}

						}

					}
				}
			}
		} else {

		    $time_settings = get_option('coderockz_woo_delivery_time_settings');
		    $max_order = $time_settings['max_order_per_slot'];
	  		$x = (int)$time_settings['delivery_time_starts'];
	  		$each_delivery_slot = (isset($time_settings['each_time_slot']) && !empty($time_settings['each_time_slot'])) ? (int)$time_settings['each_time_slot'] : (int)$time_settings['delivery_time_ends']-(int)$time_settings['delivery_time_starts'];
			while((int)$time_settings['delivery_time_ends']>$x) {
				$second_time = $x+$each_delivery_slot;
				if(in_array(date("H:i", mktime(0, (int)$x)) . ' - ' . date("H:i", mktime(0, (int)$second_time)),$unique_delivery_times)) {
					$max_order_per_slot[date("H:i", mktime(0, (int)$x)) . ' - ' . date("H:i", mktime(0, (int)$second_time))] = (int)$max_order;
				}		
				$x = $second_time;
			}
		}
	  	
		$response_delivery = [
			"delivery_times" => $delivery_times,
			"max_order_per_slot" => $max_order_per_slot,
			"slot_disable_for" => $slot_disable_for,
			'disabled_current_time_slot' => $disabled_current_time_slot,
			'disable_timeslot' => $disable_timeslot,
			'state_zip_disable_timeslot_all' => $state_zip_disable_timeslot_all,
			'no_state_zip_disable_timeslot_all' => $no_state_zip_disable_timeslot_all,
		];

		$formated_date = date('Y-m-d H:i:s', strtotime(sanitize_text_field($_POST['date'])));
		$formated_date = new DateTime($formated_date);
		$formated_date = $formated_date->format("w");

		$current_time = (date("G")*60)+date("i");

		$response_for_all = [
			"formated_date" => $formated_date,
			"current_time" => $current_time,
		];

		$response = array_merge($response_delivery, $response_for_all);

		$response = json_encode($response);
		wp_send_json_success($response);
	}

	public function coderockz_woo_delivery_get_orders_pickup() {

		check_ajax_referer('coderockz_woo_delivery_nonce');
		
		$delivery_pickup_settings = get_option('coderockz_woo_delivery_pickup_settings');
		// if any timezone data is saved, set default timezone with the data
		$timezone = $this->helper->get_the_timezone();
		date_default_timezone_set($timezone);

		
		$pickup_disabled_current_time_slot = (isset($delivery_pickup_settings['disabled_current_pickup_time_slot']) && !empty($delivery_pickup_settings['disabled_current_pickup_time_slot'])) ? $delivery_pickup_settings['disabled_current_pickup_time_slot'] : false;


		$custom_pickup_slot_settings = get_option('coderockz_woo_delivery_pickup_slot_settings');
		$enable_custom_pickup_slot = (isset($custom_pickup_slot_settings['enable_custom_pickup_slot']) && !empty($custom_pickup_slot_settings['enable_custom_pickup_slot'])) ? $custom_pickup_slot_settings['enable_custom_pickup_slot'] : false;

		$pickup_location_settings = get_option('coderockz_woo_delivery_pickup_location_settings');
		$enable_pickup_location = (isset($pickup_location_settings['enable_pickup_location']) && !empty($pickup_location_settings['enable_pickup_location'])) ? $pickup_location_settings['enable_pickup_location'] : false;
		
		if(isset($_POST['onlyPickupTime']) && $_POST['onlyPickupTime']) {
			$order_date = date("Y-m-d", strtotime(sanitize_text_field($_POST['date']))); 
			$args = array(
		        'limit' => -1,
		        'date_created' => $order_date,
		        'return' => 'ids'
		    );

		} else {
			$pickup_date = date("Y-m-d", strtotime(sanitize_text_field($_POST['date'])));
			$args = array(
		        'limit' => -1,
		        'pickup_date' => $pickup_date,
		        'return' => 'ids'
		    );		    
		}

		$order_ids = wc_get_orders( $args );

		$given_state = (isset($_POST['givenState']) && $_POST['givenState'] !="") ? sanitize_text_field($_POST['givenState']) : "";
		$given_zip = (isset($_POST['givenZip']) && $_POST['givenZip'] !="") ? sanitize_text_field($_POST['givenZip']) : "";
		$given_location = (isset($_POST['givenLocation']) && $_POST['givenLocation'] !="") ? sanitize_text_field($_POST['givenLocation']) : "";
		$response_pickup = [];
		$pickup_delivery_times = [];
		$pickup_max_order_per_slot = [];
		$pickup_slot_disable_for = [];
		$pickup_disable_timeslot = [];
		$pickup_state_zip_disable_timeslot_all = [];
		$pickup_no_state_zip_disable_timeslot_all = [];
		$pickup_disable_timeslot_location[] = [];
		$pickup_location_disable_timeslot_all = [];
		$pickup_no_location_disable_timeslot_all[] = [];
		$detect_pickup_location_hide = false;
		if($enable_custom_pickup_slot && isset($custom_pickup_slot_settings['time_slot']) && count($custom_pickup_slot_settings['time_slot'])>0){
	  		foreach($custom_pickup_slot_settings['time_slot'] as $key => $individual_pickup_slot) {

	  			if($individual_pickup_slot['enable']) {
		  			if($individual_pickup_slot['enable_split']) {
						$times = explode('-', $key);
						$x = $times[0];
						while($times[1]>$x) {
							$second_time = $x+$individual_pickup_slot['split_slot_duration'];
							$disable = $individual_pickup_slot['disable_for'];
							if($individual_pickup_slot['enable_single_splited_slot']) {
								$pickup_slot_disable_for[date("H:i", mktime(0, (int)$x))] = $disable;
							} else {
								$pickup_slot_disable_for[date("H:i", mktime(0, (int)$x)) . ' - ' . date("H:i", mktime(0, (int)$second_time))] = $disable;
							}
							
							$x = $second_time;
						}
					} else {
						$times = explode('-', $key);
						$disable = $individual_pickup_slot['disable_for'];
						if($individual_pickup_slot['enable_single_slot']) {
							$pickup_slot_disable_for[date("H:i", mktime(0, (int)$times[0]))] = $disable;
						} else {
							$pickup_slot_disable_for[date("H:i", mktime(0, (int)$times[0])) . ' - ' . date("H:i", mktime(0, (int)$times[1]))] = $disable;
						}
					}


		  			if((isset($individual_pickup_slot['disable_state']) && !empty($individual_pickup_slot['disable_state']) && in_array($given_state,$individual_pickup_slot['disable_state']))){
		  				$times = explode('-', $key);

						if($individual_pickup_slot['enable_split']) {
							
							$x = $times[0];
							while($times[1]>$x) {
								$second_time = $x+$individual_pickup_slot['split_slot_duration'];
								$disable = $individual_pickup_slot['disable_for'];
								if($individual_pickup_slot['enable_single_splited_slot']) {
									$pickup_disable_timeslot['state'][] = date("H:i", mktime(0, (int)$x));								
								} else {
									$pickup_disable_timeslot['state'][] = date("H:i", mktime(0, (int)$x)) . ' - ' . date("H:i", mktime(0, (int)$second_time));							
								}
								
								$x = $second_time;
							}
						} else {

							if($individual_pickup_slot['enable_single_slot']) {
								$pickup_disable_timeslot['state'][] = date("H:i", mktime(0, (int)$times[0]));								
							} else {
								$pickup_disable_timeslot['state'][] = date("H:i", mktime(0, (int)$times[0])) . ' - ' . date("H:i", mktime(0, (int)$times[1]));							
							}
							
						}		  				 
		  			} 

		  			if(isset($individual_pickup_slot['disable_postcode']) && !empty($individual_pickup_slot['disable_postcode'])){

		  				foreach($individual_pickup_slot['disable_postcode'] as $postcode_value) {
							$postcode_range = [];
						    if (stripos($postcode_value,'...') !== false) {
						    	$range = explode('...', $postcode_value);
						    	$x = $range[0];
						    	while($x<=$range[1]) {
						    		$postcode_range[] = (string)$x;
						    		$x = $x+1;
						    	}
						    }
						    if (substr($postcode_value, -1) == '*') {
						    	if($this->helper->starts_with($given_zip,substr($postcode_value, 0, -1))) {
						    		$times = explode('-', $key);
									if($individual_pickup_slot['enable_split']) {
										$x = $times[0];
										while($times[1]>$x) {
											$second_time = $x+$individual_pickup_slot['split_slot_duration'];
											$disable = $individual_pickup_slot['disable_for'];
											if($individual_pickup_slot['enable_single_splited_slot']) {
												$pickup_disable_timeslot['postcode'][] = date("H:i", mktime(0, (int)$x));								
											} else {
												$pickup_disable_timeslot['postcode'][] = date("H:i", mktime(0, (int)$x)) . ' - ' . date("H:i", mktime(0, (int)$second_time));							
											}
											
											$x = $second_time;
										}
									} else {
										if($individual_pickup_slot['enable_single_slot']) {
											$pickup_disable_timeslot['postcode'][] = date("H:i", mktime(0, (int)$times[0]));								
										} else {
											$pickup_disable_timeslot['postcode'][] = date("H:i", mktime(0, (int)$times[0])) . ' - ' . date("H:i", mktime(0, (int)$times[1]));						
										}						
									}
						    	}
						    } elseif(in_array($given_zip,$postcode_range) || $postcode_value == $given_zip) {
						    	$times = explode('-', $key);
								if($individual_pickup_slot['enable_split']) {
									$x = $times[0];
									while($times[1]>$x) {
										$second_time = $x+$individual_pickup_slot['split_slot_duration'];
										$disable = $individual_pickup_slot['disable_for'];
										if($individual_pickup_slot['enable_single_splited_slot']) {
											$pickup_disable_timeslot['postcode'][] = date("H:i", mktime(0, (int)$x));								
										} else {
											$pickup_disable_timeslot['postcode'][] = date("H:i", mktime(0, (int)$x)) . ' - ' . date("H:i", mktime(0, (int)$second_time));							
										}
										
										$x = $second_time;
									}
								} else {
									if($individual_pickup_slot['enable_single_slot']) {
										$pickup_disable_timeslot['postcode'][] = date("H:i", mktime(0, (int)$times[0]));								
									} else {
										$pickup_disable_timeslot['postcode'][] = date("H:i", mktime(0, (int)$times[0])) . ' - ' . date("H:i", mktime(0, (int)$times[1]));						
									}						
								}
						    }
						}		  				 
		  			}


		  			if((isset($individual_pickup_slot['disable_state']) && !empty($individual_pickup_slot['disable_state']))){
		  				$times = explode('-', $key);
						if($individual_pickup_slot['enable_split']) {
							$x = $times[0];
							while($times[1]>$x) {
								$second_time = $x+$individual_pickup_slot['split_slot_duration'];
								$disable = $individual_pickup_slot['disable_for'];
								if($individual_pickup_slot['enable_single_splited_slot']) {
									$pickup_state_zip_disable_timeslot_all['state'][] = date("H:i", mktime(0, (int)$x));								
								} else {
									$pickup_state_zip_disable_timeslot_all['state'][] = date("H:i", mktime(0, (int)$x)) . ' - ' . date("H:i", mktime(0, (int)$second_time));							
								}
								
								$x = $second_time;
							}
						} else {
							if($individual_pickup_slot['enable_single_slot']) {
								$pickup_state_zip_disable_timeslot_all['state'][] = date("H:i", mktime(0, (int)$times[0]));								
							} else {
								$pickup_state_zip_disable_timeslot_all['state'][] = date("H:i", mktime(0, (int)$times[0])) . ' - ' . date("H:i", mktime(0, (int)$times[1]));							
							}
							
						}		  				 
		  			}

		  			if((isset($individual_pickup_slot['disable_postcode']) && !empty($individual_pickup_slot['disable_postcode']))) {
		  				$times = explode('-', $key);
						if($individual_pickup_slot['enable_split']) {
							$x = $times[0];
							while($times[1]>$x) {
								$second_time = $x+$individual_pickup_slot['split_slot_duration'];
								$disable = $individual_pickup_slot['disable_for'];
								if($individual_pickup_slot['enable_single_splited_slot']) {
									$pickup_state_zip_disable_timeslot_all['postcode'][] = date("H:i", mktime(0, (int)$x));								
								} else {
									$pickup_state_zip_disable_timeslot_all['postcode'][] = date("H:i", mktime(0, (int)$x)) . ' - ' . date("H:i", mktime(0, (int)$second_time));							
								}
								
								$x = $second_time;
							}
						} else {
							if($individual_pickup_slot['enable_single_slot']) {
								$pickup_state_zip_disable_timeslot_all['postcode'][] = date("H:i", mktime(0, (int)$times[0]));								
							} else {
								$pickup_state_zip_disable_timeslot_all['postcode'][] = date("H:i", mktime(0, (int)$times[0])) . ' - ' . date("H:i", mktime(0, (int)$times[1]));							
							}						
						}		  				 
		  			} else {
		  				$times = explode('-', $key);
						if($individual_pickup_slot['enable_split']) {
							$x = $times[0];
							while($times[1]>=$x) {
								$second_time = $x+$individual_pickup_slot['split_slot_duration'];
								$disable = $individual_pickup_slot['disable_for'];
								if($individual_pickup_slot['enable_single_splited_slot']) {
									$pickup_no_state_zip_disable_timeslot_all['nostatezip'][] = date("H:i", mktime(0, (int)$x));								
								} else {
									$pickup_no_state_zip_disable_timeslot_all['nostatezip'][] = date("H:i", mktime(0, (int)$x)) . ' - ' . date("H:i", mktime(0, (int)$second_time));							
								}
								
								$x = $second_time;
							}

						} else {
							if($individual_pickup_slot['enable_single_slot']) {
								$pickup_no_state_zip_disable_timeslot_all['nostatezip'][] = date("H:i", mktime(0, (int)$times[0]));								
							} else {
								$pickup_no_state_zip_disable_timeslot_all['nostatezip'][] = date("H:i", mktime(0, (int)$times[0])) . ' - ' . date("H:i", mktime(0, (int)$times[1]));							
							}

						}		  				 
		  			}


		  			


		  			if(isset($individual_pickup_slot['hide_for_pickup_location']) && !empty($individual_pickup_slot['hide_for_pickup_location']) && $enable_pickup_location) {
		  				$detect_pickup_location_hide = true;
		  				if((isset($individual_pickup_slot['hide_for_pickup_location']) && !empty($individual_pickup_slot['hide_for_pickup_location']) && in_array($given_location,$individual_pickup_slot['hide_for_pickup_location']))){
			  				$times = explode('-', $key);

							if($individual_pickup_slot['enable_split']) {
								
								$x = $times[0];
								while($times[1]>$x) {
									$second_time = $x+$individual_pickup_slot['split_slot_duration'];

									if($individual_pickup_slot['enable_single_splited_slot']) {
										$pickup_disable_timeslot_location['location'][] = date("H:i", mktime(0, (int)$x));								
									} else {
										$pickup_disable_timeslot_location['location'][] = date("H:i", mktime(0, (int)$x)) . ' - ' . date("H:i", mktime(0, (int)$second_time));							
									}
									
									$x = $second_time;
								}
							} else {

								if($individual_pickup_slot['enable_single_slot']) {
									$pickup_disable_timeslot_location['location'][] = date("H:i", mktime(0, (int)$times[0]));								
								} else {
									$pickup_disable_timeslot_location['location'][] = date("H:i", mktime(0, (int)$times[0])) . ' - ' . date("H:i", mktime(0, (int)$times[1]));							
								}
								
							}		  				 
			  			}



			  			if((isset($individual_pickup_slot['hide_for_pickup_location']) && !empty($individual_pickup_slot['hide_for_pickup_location']))){
		  					$times = explode('-', $key);
							if($individual_pickup_slot['enable_split']) {
								$x = $times[0];
								while($times[1]>$x) {
									$second_time = $x+$individual_pickup_slot['split_slot_duration'];
									if($individual_pickup_slot['enable_single_splited_slot']) {
										$pickup_location_disable_timeslot_all['location'][] = date("H:i", mktime(0, (int)$x));								
									} else {
										$pickup_location_disable_timeslot_all['location'][] = date("H:i", mktime(0, (int)$x)) . ' - ' . date("H:i", mktime(0, (int)$second_time));							
									}
									
									$x = $second_time;
								}
							} else {
								if($individual_pickup_slot['enable_single_slot']) {
									$pickup_location_disable_timeslot_all['location'][] = date("H:i", mktime(0, (int)$times[0]));								
								} else {
									$pickup_location_disable_timeslot_all['location'][] = date("H:i", mktime(0, (int)$times[0])) . ' - ' . date("H:i", mktime(0, (int)$times[1]));							
								}
								
							}		  				 
		  				} else {
			  				$times = explode('-', $key);
							if($individual_pickup_slot['enable_split']) {
								$x = $times[0];
								while($times[1]>=$x) {
									$second_time = $x+$individual_pickup_slot['split_slot_duration'];
									if($individual_pickup_slot['enable_single_splited_slot']) {
										$pickup_no_location_disable_timeslot_all['nolocation'][] = date("H:i", mktime(0, (int)$x));								
									} else {
										$pickup_no_location_disable_timeslot_all['nolocation'][] = date("H:i", mktime(0, (int)$x)) . ' - ' . date("H:i", mktime(0, (int)$second_time));							
									}
									
									$x = $second_time;
								}

							} else {
								if($individual_pickup_slot['enable_single_slot']) {
									$pickup_no_location_disable_timeslot_all['nolocation'][] = date("H:i", mktime(0, (int)$times[0]));								
								} else {
									$pickup_no_location_disable_timeslot_all['nolocation'][] = date("H:i", mktime(0, (int)$times[0])) . ' - ' . date("H:i", mktime(0, (int)$times[1]));							
								}

							}		  				 
			  			}
		  			}


	  			}
	  		}
	  	} else {
	  		
	  		$pickup_settings = get_option('coderockz_woo_delivery_pickup_settings');
	  		$x = (int)$pickup_settings['pickup_time_starts'];
	  		$each_pickup_slot = (isset($pickup_settings['each_time_slot']) && !empty($pickup_settings['each_time_slot'])) ? (int)$pickup_settings['each_time_slot'] : (int)$pickup_settings['pickup_time_ends']-(int)$pickup_settings['pickup_time_starts'];
			while((int)$pickup_settings['pickup_time_ends']>$x) {
				$second_time = $x+$each_pickup_slot;
				$pickup_no_state_zip_disable_timeslot_all['nostatezip'][] = date("H:i", mktime(0, (int)$x)) . ' - ' . date("H:i", mktime(0, (int)$second_time));		
				$x = $second_time;
			}	  		
	  	}

	  	foreach ($order_ids as $order) {
			$date = get_post_meta($order,"pickup_date",true);
			$time = get_post_meta($order,"pickup_time",true);
			
			if((isset($date) && isset($time)) || isset($time)) {
				$pickup_delivery_times[] = $time;
			}

		}

		$unique_pickup_times = array_unique($pickup_delivery_times, false);
		$unique_pickup_times = array_values($unique_pickup_times);

		if($enable_custom_pickup_slot) {
			if(isset($custom_pickup_slot_settings['time_slot']) && count($custom_pickup_slot_settings['time_slot'])>0){

				foreach($custom_pickup_slot_settings['time_slot'] as $key => $individual_pickup_slot) {

		  			if($individual_pickup_slot['enable']) {
			  			$key = preg_replace('/-/', ',', $key);

			  			$key_array = explode(",",$key);

					    $max_order = $individual_pickup_slot['max_order'];
					
					    if($individual_pickup_slot['enable_split']) {
							$x = $key_array[0];
							while($key_array[1]>=$x) {
								$second_time = $x+$individual_pickup_slot['split_slot_duration'];

								if($individual_pickup_slot['enable_single_splited_slot']) {
									if(in_array(date("H:i", mktime(0, (int)$x)),$unique_pickup_times)) {
										$pickup_max_order_per_slot[date("H:i", mktime(0, (int)$x))] = (int)$max_order;
									}
									
								} else {
									if(in_array(date("H:i", mktime(0, (int)$x)) . ' - ' . date("H:i", mktime(0, (int)$second_time)),$unique_pickup_times)) {
									$pickup_max_order_per_slot[date("H:i", mktime(0, (int)$x)) . ' - ' . date("H:i", mktime(0, (int)$second_time))] = (int)$max_order;
									}						
								}
								
								$x = $second_time;
							}

						} else {
							if($individual_pickup_slot['enable_single_slot']) {
					
								if(in_array(date("H:i", mktime(0, (int)$key_array[0])),$unique_pickup_times)) {
									$pickup_max_order_per_slot[date("H:i", mktime(0, (int)$key_array[0]))] = (int)$max_order;
								}
							} else {
								if(in_array(date("H:i", mktime(0, (int)$key_array[0])) . ' - ' . date("H:i", mktime(0, (int)$key_array[1])),$unique_pickup_times)) {
									$pickup_max_order_per_slot[date("H:i", mktime(0, (int)$key_array[0])) . ' - ' . date("H:i", mktime(0, (int)$key_array[1]))] = (int)$max_order;	
								}						
							}

						}

					}
				}
			}
		} else {

		    $pickup_settings = get_option('coderockz_woo_delivery_pickup_settings');
		    $max_order = $pickup_settings['max_pickup_per_slot'];
	  		$x = (int)$pickup_settings['pickup_time_starts'];
	  		$each_pickup_slot = (isset($pickup_settings['each_time_slot']) && !empty($pickup_settings['each_time_slot'])) ? (int)$pickup_settings['each_time_slot'] : (int)$pickup_settings['pickup_time_ends']-(int)$pickup_settings['pickup_time_starts'];
			while((int)$pickup_settings['pickup_time_ends']>$x) {
				$second_time = $x+$each_pickup_slot;
				if(in_array(date("H:i", mktime(0, (int)$x)) . ' - ' . date("H:i", mktime(0, (int)$second_time)),$unique_pickup_times)) {
					$pickup_max_order_per_slot[date("H:i", mktime(0, (int)$x)) . ' - ' . date("H:i", mktime(0, (int)$second_time))] = (int)$max_order;
				}		
				$x = $second_time;
			}
		}

		$response_pickup = [
			"pickup_delivery_times" => $pickup_delivery_times,
			"pickup_max_order_per_slot" => $pickup_max_order_per_slot,
			"pickup_slot_disable_for" => $pickup_slot_disable_for,
			'pickup_disabled_current_time_slot' => $pickup_disabled_current_time_slot,
			'pickup_disable_timeslot' => $pickup_disable_timeslot,
			'pickup_state_zip_disable_timeslot_all' => $pickup_state_zip_disable_timeslot_all,
			'pickup_no_state_zip_disable_timeslot_all' => $pickup_no_state_zip_disable_timeslot_all,
			'pickup_disable_timeslot_location' => $pickup_disable_timeslot_location,
			'pickup_location_disable_timeslot_all' => $pickup_location_disable_timeslot_all,
			'pickup_no_location_disable_timeslot_all' => $pickup_no_location_disable_timeslot_all,
			'detect_pickup_location_hide' => $detect_pickup_location_hide,
		];

		$formated_date = date('Y-m-d H:i:s', strtotime(sanitize_text_field($_POST['date'])));
		$formated_date_obj = new DateTime($formated_date);
		$formated_date = $formated_date_obj->format("w");
		$formated_pickup_date_selected = $formated_date_obj->format("Y-m-d");

		$current_time = (date("G")*60)+date("i");

		$response_for_all = [
			"formated_date" => $formated_date,
			"current_time" => $current_time,
			"formated_pickup_date_selected" => $formated_pickup_date_selected,
		];

		$response = array_merge($response_pickup, $response_for_all);

		$response = json_encode($response);
		wp_send_json_success($response);
	}

	public function coderockz_woo_delivery_get_correct_formated_date() {
		check_ajax_referer('coderockz_woo_delivery_nonce');

		$timezone = $this->helper->get_the_timezone();
		date_default_timezone_set($timezone);
		if(isset($_POST['formatedDateSelected']) && $_POST['formatedDateSelected'] != "") {
			$formated_date_selected = $this->helper->date_conversion(sanitize_text_field($_POST['formatedDateSelected']),"delivery");
			$formated_date_selected = date('Y-m-d', strtotime($formated_date_selected));
		} else {
			$formated_date_selected = "";
		}
		if(isset($_POST['formatedPickupDateSelected']) && $_POST['formatedPickupDateSelected'] != "") {
			$formated_pickup_date_selected = $this->helper->date_conversion(sanitize_text_field($_POST['formatedPickupDateSelected']),"pickup");
			$formated_pickup_date_selected = date('Y-m-d', strtotime($formated_pickup_date_selected));
		} else {
			$formated_pickup_date_selected = "";
		}

		$response = [
			"formated_date_selected" => $formated_date_selected,
			"formated_pickup_date_selected" => $formated_pickup_date_selected
		];
		$response = json_encode($response);
		wp_send_json_success($response);


	}


	public function coderockz_woo_delivery_get_state_zip_disable_weekday_checkout() {
		check_ajax_referer('coderockz_woo_delivery_nonce');
		$offdays_settings = get_option('coderockz_woo_delivery_off_days_settings');
		$given_state_offdays = [];
		$given_state = (isset($_POST['givenState']) && $_POST['givenState'] !="") ? sanitize_text_field($_POST['givenState']) : "";
		if($given_state != "") {

			if(isset($offdays_settings['state_wise_offdays']) && !empty($offdays_settings['state_wise_offdays']) && isset($offdays_settings['state_wise_offdays'][$given_state]) && !empty($offdays_settings['state_wise_offdays'][$given_state])) { 
				$given_state_offdays = $offdays_settings['state_wise_offdays'][$given_state];
			}

			if(isset($offdays_settings['zone_wise_offdays']) && !empty($offdays_settings['zone_wise_offdays'])) {

				foreach($offdays_settings['zone_wise_offdays'] as $zone) {
					if(isset($zone['state']) && !empty($zone['state']) && isset($zone['state'][$given_state])) {
						$off_days = explode(",",$zone['off_days']);
						foreach($off_days as $off_day) {
							$given_state_offdays[] = $off_day;
						}					
					}
				}
			}

			$given_state_offdays = array_unique($given_state_offdays, false);
			$given_state_offdays = array_values($given_state_offdays);

		}

		$given_zip_offdays = [];
		$given_zip = (isset($_POST['givenZip']) && $_POST['givenZip'] !="") ? sanitize_text_field($_POST['givenZip']) : "";
		if(isset($offdays_settings['postcode_wise_offdays']) && !empty($offdays_settings['postcode_wise_offdays'])) {
			foreach($offdays_settings['postcode_wise_offdays'] as $key => $off_days) {
				$individual_postcode_range = [];
			    if (stripos($key,'...') !== false) {
			    	$range = explode('...', $key);
			    	$x = $range[0];
			    	while($x<=$range[1]) {
			    		$individual_postcode_range[] = (string)$x;
			    		$x = $x+1;
			    	}
			    }
			    if (substr($key, -1) == '*') {
			    	if($this->helper->starts_with($given_zip,substr($key, 0, -1))) {
			    		$given_zip_offdays = [];
						foreach($off_days as $off_day) {
							$given_zip_offdays[] = $off_day;
						}
			    	}
			    } elseif(in_array($given_zip,$individual_postcode_range) || $key == $given_zip) {
					foreach($off_days as $off_day) {
						$given_zip_offdays[] = $off_day;
					}
			    }
			}			
		}

		if(isset($offdays_settings['zone_wise_offdays']) && !empty($offdays_settings['zone_wise_offdays'])) {

			foreach($offdays_settings['zone_wise_offdays'] as $zone) {
				if(isset($zone['postcode']) && !empty($zone['postcode'])) {
					foreach($zone['postcode'] as $postcode_value) {
						$postcode_range = [];
					    if (stripos($postcode_value,'...') !== false) {
					    	$range = explode('...', $postcode_value);
					    	$x = $range[0];
					    	while($x<=$range[1]) {
					    		$postcode_range[] = (string)$x;
					    		$x = $x+1;
					    	}
					    }
					    if (substr($postcode_value, -1) == '*') {
					    	if($this->helper->starts_with($given_zip,substr($postcode_value, 0, -1))) {
					    		$off_days = explode(",",$zone['off_days']);
					    		$given_zip_offdays = [];
								foreach($off_days as $off_day) {
									$given_zip_offdays[] = $off_day;
								}
					    	}
					    } elseif(in_array($given_zip,$postcode_range) || $postcode_value ==$given_zip) {
					    	$off_days = explode(",",$zone['off_days']);
							foreach($off_days as $off_day) {
								$given_zip_offdays[] = $off_day;
							}
					    }
					}
										
				}
			}
		}

		$given_zip_offdays = array_unique($given_zip_offdays, false);
		$given_zip_offdays = array_values($given_zip_offdays);

		$timezone = $this->helper->get_the_timezone();
		date_default_timezone_set($timezone);
		if(isset($_POST['formatedDateSelected']) && $_POST['formatedDateSelected'] != "") {
			$formated_date_selected = $this->helper->date_conversion(sanitize_text_field($_POST['formatedDateSelected']),"delivery");
			$formated_date_selected = date('Y-m-d', strtotime($formated_date_selected));
		} else {
			$formated_date_selected = "";
		}
		if(isset($_POST['formatedPickupDateSelected']) && $_POST['formatedPickupDateSelected'] != "") {
			$formated_pickup_date_selected = $this->helper->date_conversion(sanitize_text_field($_POST['formatedPickupDateSelected']),"pickup");
			$formated_pickup_date_selected = date('Y-m-d', strtotime($formated_pickup_date_selected));
		} else {
			$formated_pickup_date_selected = "";
		}

		$response = [
			"given_state_offdays" => $given_state_offdays,
			"given_zip_offdays" => $given_zip_offdays,
			"formated_date_selected" => $formated_date_selected,
			"formated_pickup_date_selected" => $formated_pickup_date_selected
		];
		$response = json_encode($response);
		wp_send_json_success($response);


	}

	public function coderockz_woo_delivery_disable_max_delivery_pickup_date() {
		check_ajax_referer('coderockz_woo_delivery_nonce');
		$delivery_date_settings = get_option('coderockz_woo_delivery_date_settings');
		$pickup_date_settings = get_option('coderockz_woo_delivery_pickup_date_settings');
		$enable_delivery_date = (isset($delivery_date_settings['enable_delivery_date']) && !empty($delivery_date_settings['enable_delivery_date'])) ? $delivery_date_settings['enable_delivery_date'] : false;
		$enable_pickup_date = (isset($pickup_date_settings['enable_pickup_date']) && !empty($pickup_date_settings['enable_pickup_date'])) ? $pickup_date_settings['enable_pickup_date'] : false;
		// if any timezone data is saved, set default timezone with the data
		$timezone = $this->helper->get_the_timezone();
		date_default_timezone_set($timezone);

		$disable_for_max_delivery_dates = [];
		$disable_for_max_pickup_dates = [];
		$today = date('Y-m-d', time());
		$range_first_date = date('Y-m-d', strtotime($today));
		$formated_obj = new DateTime($range_first_date);
	    
		if($enable_delivery_date && !$enable_pickup_date && (isset($delivery_date_settings['maximum_order_per_day']) && $delivery_date_settings['maximum_order_per_day'] != "")) {

			$range_last_date = $formated_obj->modify("+40 day")->format("Y-m-d");
			$filtered_date = $range_first_date . ',' . $range_last_date;
			$filtered_dates = explode(',', $filtered_date);
			$period = new DatePeriod(new DateTime($filtered_dates[0]), new DateInterval('P1D'), new DateTime($filtered_dates[1].' +1 day'));


			$max_order_per_day = (isset($delivery_date_settings['maximum_order_per_day']) && $delivery_date_settings['maximum_order_per_day'] != "") ? (int)$delivery_date_settings['maximum_order_per_day'] : 10000000000000;
			foreach ($period as $date) { 
				$args = array(
			        'limit' => -1,
			        'delivery_date' => date("Y-m-d", strtotime($date->format("Y-m-d"))),
			        'delivery_type' => "delivery",
			        'return' => 'ids'
			    );
			    $orders_array = wc_get_orders( $args );
			    if(count($orders_array) >= $max_order_per_day) {
					$disable_for_max_delivery_dates[] = date('Y-m-d', strtotime($date->format("Y-m-d")));
			    }
			}

			$disable_for_max_delivery_dates = array_unique($disable_for_max_delivery_dates, false);
			$disable_for_max_delivery_dates = array_values($disable_for_max_delivery_dates);

		} elseif(!$enable_delivery_date && $enable_pickup_date && (isset($pickup_date_settings['maximum_pickup_per_day']) && $pickup_date_settings['maximum_pickup_per_day'] != "")) {
			
			$range_last_date = $formated_obj->modify("+40 day")->format("Y-m-d");
			$filtered_date = $range_first_date . ',' . $range_last_date;
			$filtered_dates = explode(',', $filtered_date);
			$period = new DatePeriod(new DateTime($filtered_dates[0]), new DateInterval('P1D'), new DateTime($filtered_dates[1].' +1 day'));

			$max_pickup_per_day = (isset($pickup_date_settings['maximum_pickup_per_day']) && $pickup_date_settings['maximum_pickup_per_day'] != "") ? (int)$pickup_date_settings['maximum_pickup_per_day'] : 10000000000000;
			foreach ($period as $date) {
				$args = array(
			        'limit' => -1,
			        'pickup_date' => date("Y-m-d", strtotime($date->format("Y-m-d"))),
			        'delivery_type' => "pickup",
			        'return' => 'ids'
			    );
			    $orders_array = wc_get_orders( $args );
			    if(count($orders_array) >= $max_pickup_per_day) {
					$disable_for_max_pickup_dates[] = date('Y-m-d', strtotime($date->format("Y-m-d")));
			    }
			}

			$disable_for_max_pickup_dates = array_unique($disable_for_max_pickup_dates, false);
			$disable_for_max_pickup_dates = array_values($disable_for_max_pickup_dates);
		} else {
			$range_last_date = $formated_obj->modify("+40 day")->format("Y-m-d");
			$filtered_date = $range_first_date . ',' . $range_last_date;
			$filtered_dates = explode(',', $filtered_date);
			$period = new DatePeriod(new DateTime($filtered_dates[0]), new DateInterval('P1D'), new DateTime($filtered_dates[1].' +1 day'));


			$max_order_per_day = (isset($delivery_date_settings['maximum_order_per_day']) && $delivery_date_settings['maximum_order_per_day'] != "") ? (int)$delivery_date_settings['maximum_order_per_day'] : 10000000000000;
			foreach ($period as $date) { 
				$args = array(
			        'limit' => -1,
			        'delivery_date' => date("Y-m-d", strtotime($date->format("Y-m-d"))),
			        'delivery_type' => "delivery",
			        'return' => 'ids'
			    );
			    $orders_array = wc_get_orders( $args );
			    if(count($orders_array) >= $max_order_per_day) {
					$disable_for_max_delivery_dates[] = date('Y-m-d', strtotime($date->format("Y-m-d")));
			    }
			}

			$disable_for_max_delivery_dates = array_unique($disable_for_max_delivery_dates, false);
			$disable_for_max_delivery_dates = array_values($disable_for_max_delivery_dates);

			$range_last_date = $formated_obj->modify("+40 day")->format("Y-m-d");
			$filtered_date = $range_first_date . ',' . $range_last_date;
			$filtered_dates = explode(',', $filtered_date);
			$period = new DatePeriod(new DateTime($filtered_dates[0]), new DateInterval('P1D'), new DateTime($filtered_dates[1].' +1 day'));

			$max_pickup_per_day = (isset($pickup_date_settings['maximum_pickup_per_day']) && $pickup_date_settings['maximum_pickup_per_day'] != "") ? (int)$pickup_date_settings['maximum_pickup_per_day'] : 10000000000000;
			foreach ($period as $date) {
				$args = array(
			        'limit' => -1,
			        'pickup_date' => date("Y-m-d", strtotime($date->format("Y-m-d"))),
			        'delivery_type' => "pickup",
			        'return' => 'ids'
			    );
			    $orders_array = wc_get_orders( $args );
			    if(count($orders_array) >= $max_pickup_per_day) {
					$disable_for_max_pickup_dates[] = date('Y-m-d', strtotime($date->format("Y-m-d")));
			    }
			}

			$disable_for_max_pickup_dates = array_unique($disable_for_max_pickup_dates, false);
			$disable_for_max_pickup_dates = array_values($disable_for_max_pickup_dates);
		}

		$disable_delivery_date_passed_time = [];
		$disable_pickup_date_passed_time = [];

		$delivery_time_settings = get_option('coderockz_woo_delivery_time_settings');
		$pickup_time_settings = get_option('coderockz_woo_delivery_pickup_settings');

		$enable_delivery_time = (isset($delivery_time_settings['enable_delivery_time']) && !empty($delivery_time_settings['enable_delivery_time'])) ? $delivery_time_settings['enable_delivery_time'] : false;
	  	
		$enable_pickup_time = (isset($pickup_time_settings['enable_pickup_time']) && !empty($pickup_time_settings['enable_pickup_time'])) ? $pickup_time_settings['enable_pickup_time'] : false;
		
		
		if($enable_delivery_time) {

			$time_slot_end = [0];
			$custom_time_slot_settings = get_option('coderockz_woo_delivery_time_slot_settings');
			$enable_custom_time_slot = (isset($custom_time_slot_settings['enable_custom_time_slot']) && !empty($custom_time_slot_settings['enable_custom_time_slot'])) ? $custom_time_slot_settings['enable_custom_time_slot'] : false;
			if($enable_custom_time_slot) {
				if(isset($custom_time_slot_settings['time_slot']) && count($custom_time_slot_settings['time_slot'])>0){				
					foreach($custom_time_slot_settings['time_slot'] as $key => $individual_time_slot) {

			  			if($individual_time_slot['enable'] && !in_array(date("w"),$individual_time_slot['disable_for'])) {
				  			$key = preg_replace('/-/', ',', $key);

				  			$key_array = explode(",",$key);

						
						    if($individual_time_slot['enable_split']) {
						    	$x = $key_array[0];
								while($key_array[1]>$x) {
									$second_time = $x+$individual_time_slot['split_slot_duration'];

									if($individual_time_slot['enable_single_splited_slot']) {
										$time_slot_end[] = (int)$x;
									} else {
										$time_slot_end[] = (int)$second_time;
									}
																	
									$x = $second_time;
								}
						    } else {
						    	if($individual_time_slot['enable_single_slot']) {
									$time_slot_end[] = (int)$individual_time_slot['start'];
								} else {
									$time_slot_end[] = (int)$individual_time_slot['end'];
								}
						    }

						}

					}
				}
			} else {

				$time_settings = get_option('coderockz_woo_delivery_time_settings');
				$time_slot_end[] = (int)$time_settings['delivery_time_ends'];												
			}

			$highest_timeslot_end = max($time_slot_end);
			$current_time = (date("G")*60)+date("i");

			if($current_time>$highest_timeslot_end) {
				$disable_delivery_date_passed_time[] = date('Y-m-d', time());
			}
		}

		if($enable_pickup_time) {

			$pickup_slot_end = [0];
			$custom_pickup_slot_settings = get_option('coderockz_woo_delivery_pickup_slot_settings');
			$enable_custom_pickup_slot = (isset($custom_pickup_slot_settings['enable_custom_pickup_slot']) && !empty($custom_pickup_slot_settings['enable_custom_pickup_slot'])) ? $custom_pickup_slot_settings['enable_custom_pickup_slot'] : false;
			if($enable_custom_pickup_slot) {
				if(isset($custom_pickup_slot_settings['time_slot']) && count($custom_pickup_slot_settings['time_slot'])>0){

					foreach($custom_pickup_slot_settings['time_slot'] as $pickup_key => $individual_pickup_slot) {

			  			if($individual_pickup_slot['enable'] && !in_array(date("w"),$individual_pickup_slot['disable_for'])) {
				  			$pickup_key = preg_replace('/-/', ',', $pickup_key);

				  			$pickup_key_array = explode(",",$pickup_key);
						
						    if($individual_pickup_slot['enable_split']) {
						    	$pickup_x = $pickup_key_array[0];
								while($pickup_key_array[1]>$pickup_x) {
									$pickup_second_time = $pickup_x+$individual_pickup_slot['split_slot_duration'];

									if($individual_pickup_slot['enable_single_splited_slot']) {
										$pickup_slot_end[] = (int)$pickup_x;
									} else {
										$pickup_slot_end[] = (int)$pickup_second_time;
									}

									$pickup_x = $pickup_second_time;
								}
						    } else {
						    	if($individual_pickup_slot['enable_single_slot']) {
									$pickup_slot_end[] = (int)$individual_pickup_slot['start'];
								} else {
									$pickup_slot_end[] = (int)$individual_pickup_slot['end'];
								}
						    }

						}
					}
				}
			} else {

		    	$pickup_settings = get_option('coderockz_woo_delivery_pickup_settings');
				$pickup_slot_end[] = (int)$pickup_settings['pickup_time_ends'];;
			}

			$highest_pickupslot_end = max($pickup_slot_end);

			$current_time = (date("G")*60)+date("i");

			if($current_time>$highest_pickupslot_end) {
				$disable_pickup_date_passed_time[] = date('Y-m-d', time());
			}

		}

		$response=[
			"disable_for_max_delivery_dates" => $disable_for_max_delivery_dates,
			"disable_for_max_pickup_dates" => $disable_for_max_pickup_dates,
			"disable_delivery_date_passed_time" => $disable_delivery_date_passed_time,
			"disable_pickup_date_passed_time" => $disable_pickup_date_passed_time,
		];
		$response = json_encode($response);
		wp_send_json_success($response);
		
	}

	
	public function coderockz_woo_delivery_add_account_orders_column( $columns ) {
		$delivery_details_text = (isset(get_option('coderockz_woo_delivery_localization_settings')['delivery_details_text']) && !empty(get_option('coderockz_woo_delivery_localization_settings')['delivery_details_text'])) ? stripslashes(get_option('coderockz_woo_delivery_localization_settings')['delivery_details_text']) : "Delivery Details"; 

		if(class_exists('CodeRockz_Woocommerce_Delivery_Date_Time')) {
			$columns  = array_splice($columns, 0, 3, true) +
				['order_delivery_details' => $delivery_details_text] +
				array_splice($columns, 1, count($columns) - 1, true);
		}
		
	    return $columns;
	}

	public function coderockz_woo_delivery_show_delivery_details_my_account_tab($order) {
		if(class_exists('CodeRockz_Woocommerce_Delivery_Date_Time')) {
			$delivery_date_settings = get_option('coderockz_woo_delivery_date_settings');			
			$pickup_date_settings = get_option('coderockz_woo_delivery_pickup_date_settings');			
			$delivery_time_settings = get_option('coderockz_woo_delivery_time_settings');
			$pickup_time_settings = get_option('coderockz_woo_delivery_pickup_settings');
			$delivery_pickup_settings = get_option('coderockz_woo_delivery_pickup_location_settings');
			$additional_field_settings = get_option('coderockz_woo_delivery_additional_field_settings');

			$delivery_date_field_label = (isset($delivery_date_settings['field_label']) && !empty($delivery_date_settings['field_label'])) ? stripslashes($delivery_date_settings['field_label']) : "Delivery Date";
			$pickup_date_field_label = (isset($pickup_date_settings['pickup_field_label']) && !empty($pickup_date_settings['pickup_field_label'])) ? stripslashes($pickup_date_settings['pickup_field_label']) : "Pickup Date";
			$delivery_time_field_label = (isset($delivery_time_settings['field_label']) && !empty($delivery_time_settings['field_label'])) ? stripslashes($delivery_time_settings['field_label']) : "Delivery Time";
			$pickup_time_field_label = (isset($pickup_time_settings['field_label']) && !empty($pickup_time_settings['field_label'])) ? stripslashes($pickup_time_settings['field_label']) : "Pickup Time";
			$pickup_location_field_label = (isset($pickup_location_settings['field_label']) && !empty($pickup_location_settings['field_label'])) ? stripslashes($pickup_location_settings['field_label']) : "Pickup Location";
			$additional_field_field_label = (isset($additional_field_settings['field_label']) && !empty($additional_field_settings['field_label'])) ? stripslashes($additional_field_settings['field_label']) : "Special Note About Delivery";


			// if any timezone data is saved, set default timezone with the data
			$timezone = $this->helper->get_the_timezone();
			date_default_timezone_set($timezone);

			$delivery_date_format = (isset($delivery_date_settings['date_format']) && !empty($delivery_date_settings['date_format'])) ? $delivery_date_settings['date_format'] : "F j, Y";
			$pickup_date_format = (isset($pickup_date_settings['date_format']) && !empty($pickup_date_settings['date_format'])) ? $pickup_date_settings['date_format'] : "F j, Y";

			$time_format = (isset($delivery_time_settings['time_format']) && !empty($delivery_time_settings['time_format']))?$delivery_time_settings['time_format']:"12";
			if($time_format == 12) {
				$time_format = "h:i A";
			} elseif ($time_format == 24) {
				$time_format = "H:i";
			}
			
			$my_account_column = "";
			if(metadata_exists('post', $order->get_id(), 'delivery_date') && get_post_meta($order->get_id(), 'delivery_date', true) !="") {

				$delivery_date = $this->helper->date_conversion_to_locale(date($delivery_date_format, strtotime(get_post_meta( $order->get_id(), 'delivery_date', true ))),"delivery");

				$my_account_column .= $delivery_date_field_label.": " . $delivery_date;
				$my_account_column .= "<br>";
			}

			if(metadata_exists('post', $order->get_id(), 'delivery_time') && get_post_meta($order->get_id(), 'delivery_time', true) !="") {
				if(get_post_meta($order->get_id(),"delivery_time",true) == "as-soon-as-possible") {
					$as_soon_as_possible_text = (isset($delivery_time_settings['as_soon_as_possible_text']) && !empty($delivery_time_settings['as_soon_as_possible_text'])) ? stripslashes($delivery_time_settings['as_soon_as_possible_text']) : "As Soon As Possible";
					$my_account_column .= $delivery_time_field_label.": " . $as_soon_as_possible_text;
					$my_account_column .= "<br>";
				} else {
					$minutes = get_post_meta($order->get_id(),"delivery_time",true);

					$minutes = explode(' - ', $minutes);
		    		if(!isset($minutes[1])) {
		    			$time_value = date($time_format, strtotime($minutes[0]));
		    		} else {
		    			$time_value = date($time_format, strtotime($minutes[0])) . ' - ' . date($time_format, strtotime($minutes[1]));
		    		}

					$my_account_column .= $delivery_time_field_label.": " . $time_value;
					$my_account_column .= "<br>";
				}
			}

			if(metadata_exists('post', $order->get_id(), 'pickup_date') && get_post_meta($order->get_id(), 'pickup_date', true) !="") {
				$pickup_date = $this->helper->date_conversion_to_locale(date($pickup_date_format, strtotime(get_post_meta( $order->get_id(), 'pickup_date', true ))),"pickup");
				$my_account_column .= $pickup_date_field_label.": " . $pickup_date;
				$my_account_column .= "<br>";
			}

			if(metadata_exists('post', $order->get_id(), 'pickup_time') && get_post_meta($order->get_id(), 'pickup_time', true) !="") {
				$pickup_minutes = get_post_meta($order->get_id(),"pickup_time",true);
				$pickup_time_format = (isset($pickup_time_settings['time_format']) && !empty($pickup_time_settings['time_format']))?$pickup_time_settings['time_format']:"12";
				if($pickup_time_format == 12) {
					$pickup_time_format = "h:i A";
				} elseif ($pickup_time_format == 24) {
					$pickup_time_format = "H:i";
				}
				$pickup_minutes = explode(' - ', $pickup_minutes);
	    		if(!isset($pickup_minutes[1])) {
	    			$pickup_time_value = date($pickup_time_format, strtotime($pickup_minutes[0]));
	    		} else {
	    			$pickup_time_value = date($pickup_time_format, strtotime($pickup_minutes[0])) . ' - ' . date($pickup_time_format, strtotime($pickup_minutes[1]));
	    		}

				$my_account_column .= $pickup_time_field_label.": " . $pickup_time_value;
				$my_account_column .= "<br>";

			}

			if(metadata_exists('post', $order->get_id(), 'delivery_pickup') && get_post_meta($order->get_id(), 'delivery_pickup', true) !="") {
				$my_account_column .= $pickup_location_field_label.": " . get_post_meta($order->get_id(), 'delivery_pickup', true);
				$my_account_column .= "<br>";
			}

			if(metadata_exists('post', $order->get_id(), 'additional_note') && get_post_meta($order->get_id(), 'additional_note', true) !="") {
				$my_account_column .= $additional_field_field_label.": " . get_post_meta($order->get_id(), 'additional_note', true);
			}

			echo $my_account_column;
		}
	}

	public function coderockz_woo_delivery_add_delivery_information_row( $total_rows, $order ) {
 
		$delivery_date_settings = get_option('coderockz_woo_delivery_date_settings');			
		$pickup_date_settings = get_option('coderockz_woo_delivery_pickup_date_settings');			
		$delivery_time_settings = get_option('coderockz_woo_delivery_time_settings');
		$pickup_time_settings = get_option('coderockz_woo_delivery_pickup_settings');
		$delivery_pickup_settings = get_option('coderockz_woo_delivery_pickup_location_settings');
		$additional_field_settings = get_option('coderockz_woo_delivery_additional_field_settings');

		$delivery_date_field_label = (isset($delivery_date_settings['field_label']) && !empty($delivery_date_settings['field_label'])) ? stripslashes($delivery_date_settings['field_label']) : "Delivery Date";
		$pickup_date_field_label = (isset($pickup_date_settings['pickup_field_label']) && !empty($pickup_date_settings['pickup_field_label'])) ? stripslashes($pickup_date_settings['pickup_field_label']) : "Pickup Date";
		$delivery_time_field_label = (isset($delivery_time_settings['field_label']) && !empty($delivery_time_settings['field_label'])) ? stripslashes($delivery_time_settings['field_label']) : "Delivery Time";
		$pickup_time_field_label = (isset($pickup_time_settings['field_label']) && !empty($pickup_time_settings['field_label'])) ? stripslashes($pickup_time_settings['field_label']) : "Pickup Time";
		$pickup_location_field_label = (isset($pickup_location_settings['field_label']) && !empty($pickup_location_settings['field_label'])) ? stripslashes($pickup_location_settings['field_label']) : "Pickup Location";
		$additional_field_field_label = (isset($additional_field_settings['field_label']) && !empty($additional_field_settings['field_label'])) ? stripslashes($additional_field_settings['field_label']) : "Special Note About Delivery";

		// if any timezone data is saved, set default timezone with the data
		$timezone = $this->helper->get_the_timezone();
		date_default_timezone_set($timezone);

		$delivery_date_format = (isset($delivery_date_settings['date_format']) && !empty($delivery_date_settings['date_format'])) ? $delivery_date_settings['date_format'] : "F j, Y";
		$pickup_date_format = (isset($pickup_date_settings['date_format']) && !empty($pickup_date_settings['date_format'])) ? $pickup_date_settings['date_format'] : "F j, Y";

		$time_format = (isset($delivery_time_settings['time_format']) && !empty($delivery_time_settings['time_format']))?$delivery_time_settings['time_format']:"12";
		if($time_format == 12) {
			$time_format = "h:i A";
		} elseif ($time_format == 24) {
			$time_format = "H:i";
		}

		$pickup_time_format = (isset($pickup_time_settings['time_format']) && !empty($pickup_time_settings['time_format']))?$pickup_time_settings['time_format']:"12";
		if($pickup_time_format == 12) {
			$pickup_time_format = "h:i A";
		} elseif ($pickup_time_format == 24) {
			$pickup_time_format = "H:i";
		}

		if( version_compare( get_option( 'woocommerce_version' ), '3.0.0', ">=" ) ) {            
	        $order_id = $order->get_id();
	    } else {
	        $order_id = $order->id;
	    }

	    $delivery_option_settings = get_option('coderockz_woo_delivery_option_delivery_settings');
	    $order_type_field_label = (isset($delivery_option_settings['delivery_option_label']) && !empty($delivery_option_settings['delivery_option_label'])) ? stripslashes($delivery_option_settings['delivery_option_label']) : "Order Type";
	    $delivery_field_label = (isset($delivery_option_settings['delivery_label']) && !empty($delivery_option_settings['delivery_label'])) ? stripslashes($delivery_option_settings['delivery_label']) : "Delivery";
		$pickup_field_label = (isset($delivery_option_settings['pickup_label']) && !empty($delivery_option_settings['pickup_label'])) ? stripslashes($delivery_option_settings['pickup_label']) : "Pickup";
	    if(metadata_exists('post', $order_id, 'delivery_type') && get_post_meta($order_id, 'delivery_type', true) !="") {

			if(get_post_meta($order_id, 'delivery_type', true) == "delivery") {
				$total_rows['delivery_type'] = array(
				   'label' => $order_type_field_label,
				   'value'   => $delivery_field_label
				);
			} elseif(get_post_meta($order_id, 'delivery_type', true) == "pickup") {
				$total_rows['delivery_type'] = array(
				   'label' => $order_type_field_label,
				   'value'   => $pickup_field_label
				);
			}

	    }
	    
	    if(metadata_exists('post', $order_id, 'delivery_date') && get_post_meta( $order_id, 'delivery_date', true ) != "") {

	    	$delivery_date = $this->helper->date_conversion_to_locale(date($delivery_date_format, strtotime(get_post_meta( $order_id, 'delivery_date', true ))),"delivery");

	    	$total_rows['delivery_date'] = array(
			   'label' => $delivery_date_field_label,
			   'value'   => $delivery_date
			);
	    }
		
	    if(metadata_exists('post', $order_id, 'delivery_time') && get_post_meta($order_id,"delivery_time",true) != "") {
	    	if(get_post_meta($order_id,"delivery_time",true) == "as-soon-as-possible") {
	    		$as_soon_as_possible_text = (isset($delivery_time_settings['as_soon_as_possible_text']) && !empty($delivery_time_settings['as_soon_as_possible_text'])) ? stripslashes($delivery_time_settings['as_soon_as_possible_text']) : "As Soon As Possible";
	    		$time_value = $as_soon_as_possible_text;
	    	} else {
				$minutes = get_post_meta($order_id,"delivery_time",true);
				$minutes = explode(' - ', $minutes);

	    		if(!isset($minutes[1])) {
	    			$time_value = date($time_format, strtotime($minutes[0]));
	    		} else {

	    			$time_value = date($time_format, strtotime($minutes[0])) . ' - ' . date($time_format, strtotime($minutes[1]));

	    		}

    		}

			$total_rows['delivery_time'] = array(
			   'label' => $delivery_time_field_label,
			   'value'   => $time_value
			);
		}

		if(metadata_exists('post', $order_id, 'pickup_date') && get_post_meta( $order_id, 'pickup_date', true ) != "") {

			$pickup_date = $this->helper->date_conversion_to_locale(date($pickup_date_format, strtotime(get_post_meta( $order_id, 'pickup_date', true ))),"pickup");

	    	$total_rows['pickup_date'] = array(
			   'label' => $pickup_date_field_label,
			   'value'   => $pickup_date
			);
	    }

		if(metadata_exists('post', $order_id, 'pickup_time') && get_post_meta($order_id,"pickup_time",true) != "") {
			$pickup_minutes = get_post_meta($order_id,"pickup_time",true);
			$pickup_minutes = explode(' - ', $pickup_minutes);

    		if(!isset($pickup_minutes[1])) {
    			$pickup_time_value = date($pickup_time_format, strtotime($pickup_minutes[0]));
    		} else {
    			$pickup_time_value = date($pickup_time_format, strtotime($pickup_minutes[0])) . ' - ' . date($pickup_time_format, strtotime($pickup_minutes[1]));
    		}

			$total_rows['pickup_time'] = array(
			   'label' => $pickup_time_field_label,
			   'value'   => $pickup_time_value
			);
		}

		if(metadata_exists('post', $order_id, 'delivery_pickup') && get_post_meta($order_id, 'delivery_pickup', true) !="") {
			$total_rows['delivery_pickup'] = array(
			   'label' => $pickup_location_field_label,
			   'value'   => get_post_meta($order_id, 'delivery_pickup', true)
			);
		}

		if(metadata_exists('post', $order_id, 'additional_note') && get_post_meta($order_id, 'additional_note', true) !="") {
			$total_rows['additional_note'] = array(
			   'label' => $additional_field_field_label,
			   'value'   => get_post_meta($order_id, 'additional_note', true)
			);
		}

		if(isset($_COOKIE['coderockz_woo_delivery_option_time_pickup'])) {
		  $delivery_option_session = $_COOKIE['coderockz_woo_delivery_option_time_pickup'];
		} elseif(!is_null(WC()->session)) {
		  $delivery_option_session = WC()->session->get( 'coderockz_woo_delivery_option_time_pickup' );
		}
	    

		$delivery_option_settings = get_option('coderockz_woo_delivery_option_delivery_settings');
		$enable_delivery_option = (isset($delivery_option_settings['enable_option_time_pickup']) && !empty($delivery_option_settings['enable_option_time_pickup'])) ? $delivery_option_settings['enable_option_time_pickup'] : false;

		$enable_pickup_time = (isset($pickup_time_settings['enable_pickup_time']) && !empty($pickup_time_settings['enable_pickup_time'])) ? $pickup_time_settings['enable_pickup_time'] : false;

		$enable_delivery_time = (isset($delivery_time_settings['enable_delivery_time']) && !empty($delivery_time_settings['enable_delivery_time'])) ? $delivery_time_settings['enable_delivery_time'] : false;

		if(!is_null(WC()->session)) {
			$chosen_shipping_method = WC()->session->get('chosen_shipping_methods');
		} else {
			$chosen_shipping_method = [];
			$chosen_shipping_method[] = '';
		}

	    if(((isset($delivery_option_session) && $delivery_option_session == "pickup" && (strpos($chosen_shipping_method[0], 'local_pickup') === false && $order->get_shipping_total() == 0)) || (!isset($delivery_option_session) && !$enable_delivery_option && !$enable_delivery_time && (strpos($chosen_shipping_method[0], 'local_pickup') === false && $order->get_shipping_total() == 0)) || ($enable_pickup_time && !$enable_delivery_time && (strpos($chosen_shipping_method[0], 'local_pickup') === false && $order->get_shipping_total() == 0)))) {
	    	unset($total_rows['shipping']);
	    }
		 
		return $total_rows;
	}

	public function coderockz_woo_delivery_add_custom_fee ( $cart ) {
		if ( ! $_POST || ( is_admin() && ! is_ajax() ) ) {
	        return;
	    }

	    if(isset($_COOKIE['coderockz_woo_delivery_option_time_pickup'])) {
		  $delivery_option_session = $_COOKIE['coderockz_woo_delivery_option_time_pickup'];
		} elseif(!is_null(WC()->session)) {
		  $delivery_option_session = WC()->session->get( 'coderockz_woo_delivery_option_time_pickup' );
		}

        $selected_delivery_date = WC()->session->get( 'selected_delivery_date' );
        $selected_delivery_time = WC()->session->get( 'selected_delivery_time' );
               
        $selected_pickup_time = WC()->session->get( 'selected_pickup_time' );

        $selected_order_type = WC()->session->get( 'selected_order_type' );
	    $delivery_time_settings = get_option('coderockz_woo_delivery_time_settings');

		// if any timezone data is saved, set default timezone with the data
		$timezone = $this->helper->get_the_timezone();
		date_default_timezone_set($timezone);

	    $fees_settings = get_option('coderockz_woo_delivery_fee_settings');

		$custom_time_slot_settings = get_option('coderockz_woo_delivery_time_slot_settings');
		$enable_custom_time_slot = (isset($custom_time_slot_settings['enable_custom_time_slot']) && !empty($custom_time_slot_settings['enable_custom_time_slot'])) ? $custom_time_slot_settings['enable_custom_time_slot'] : false;

		$delivery_option_settings = get_option('coderockz_woo_delivery_option_delivery_settings');
		$enable_delivery_option = (isset($delivery_option_settings['enable_option_time_pickup']) && !empty($delivery_option_settings['enable_option_time_pickup'])) ? $delivery_option_settings['enable_option_time_pickup'] : false;

		$pickup_time_settings = get_option('coderockz_woo_delivery_pickup_settings');
		$enable_pickup_time = (isset($pickup_time_settings['enable_pickup_time']) && !empty($pickup_time_settings['enable_pickup_time'])) ? $pickup_time_settings['enable_pickup_time'] : false;

		$delivery_time_settings = get_option('coderockz_woo_delivery_time_settings');
		$enable_delivery_time = (isset($delivery_time_settings['enable_delivery_time']) && !empty($delivery_time_settings['enable_delivery_time'])) ? $delivery_time_settings['enable_delivery_time'] : false;



		$localization_settings = get_option('coderockz_woo_delivery_localization_settings');
		$delivery_fee_text = (isset($localization_settings['delivery_fee_text']) && !empty($localization_settings['delivery_fee_text'])) ? stripslashes($localization_settings['delivery_fee_text']) : "Delivery Time Slot Fee";
		$pickup_fee_text = (isset($localization_settings['pickup_fee_text']) && !empty($localization_settings['pickup_fee_text'])) ? stripslashes($localization_settings['pickup_fee_text']) : "Pickup Slot Fee";
		$sameday_fee_text = (isset($localization_settings['sameday_fee_text']) && !empty($localization_settings['sameday_fee_text'])) ? stripslashes($localization_settings['sameday_fee_text']) : "Same Day Delivery Fee";
		$nextday_fee_text = (isset($localization_settings['nextday_fee_text']) && !empty($localization_settings['nextday_fee_text'])) ? stripslashes($localization_settings['nextday_fee_text']) : "Next Day Delivery Fee";
		$day_after_tomorrow_fee_text = (isset($localization_settings['day_after_tomorrow_fee_text']) && !empty($localization_settings['day_after_tomorrow_fee_text'])) ? stripslashes($localization_settings['day_after_tomorrow_fee_text']) : "Day After Tomorrow Delivery Fee";
		$other_fee_text = (isset($localization_settings['other_fee_text']) && !empty($localization_settings['other_fee_text'])) ? stripslashes($localization_settings['other_fee_text']) : "Other Day Delivery Fee";
		$weekday_fee_text = (isset($localization_settings['weekday_fee_text']) && !empty($localization_settings['weekday_fee_text'])) ? stripslashes($localization_settings['weekday_fee_text']) : "Weekday Delivery Fee";


		if(((isset($delivery_option_session) && $delivery_option_session == "delivery" && $enable_delivery_option && $enable_delivery_time && $selected_order_type != "") || (!$enable_delivery_option && $enable_delivery_time && !$enable_pickup_time)) && is_checkout()) {
			if($selected_delivery_time != "") {
	        	if(strpos($selected_delivery_time, ' - ') !== false) {
	        		$selected_delivery_time = explode(' - ', $selected_delivery_time);
					$inserted_data_key_array_one = explode(':', $selected_delivery_time[0]);
					$inserted_data_key_array_two = explode(':', $selected_delivery_time[1]);
					$selected_delivery_time = ((int)$inserted_data_key_array_one[0]*60+(int)$inserted_data_key_array_one[1]).' - '.((int)$inserted_data_key_array_two[0]*60+(int)$inserted_data_key_array_two[1]);
					$inserted_data_key_array = explode(" - ",$selected_delivery_time);
	        	} else {
	        		$inserted_data_key_array = [];
	        		$inserted_data_key_array_one = explode(':', $selected_delivery_time);
	        		$selected_delivery_time = ((int)$inserted_data_key_array_one[0]*60+(int)$inserted_data_key_array_one[1]);
	        		$inserted_data_key_array[] = $selected_delivery_time;
	        	}
	    		
			} 
			if($enable_custom_time_slot) {
				if(isset($custom_time_slot_settings['time_slot']) && count($custom_time_slot_settings['time_slot'])>0){


			  		foreach($custom_time_slot_settings['time_slot'] as $key => $individual_time_slot) {
			  			if($individual_time_slot['enable']) {
				  			$key = preg_replace('/-/', ' - ', $key);

				  			$key_array = explode(" - ",$key);
				    		
					    	if(!empty($selected_delivery_time) && (isset($inserted_data_key_array[1]) && $inserted_data_key_array[0]>= $key_array[0] && $inserted_data_key_array[1] <= $key_array[1])) {

					    		if($individual_time_slot["fee"] !="") {
					    			if(class_exists('WOOCS_STARTER')){
	
										$individual_fee = apply_filters('woocs_exchange_value', $individual_time_slot["fee"]);
									} else {
										$individual_fee = $individual_time_slot["fee"];
									}
					    			$cart->add_fee( __( $delivery_fee_text, 'coderockz-woo-delivery' ) , $individual_fee, false );
					    		}
						    } elseif(!empty($selected_delivery_time) && !isset($inserted_data_key_array[1]) && ($inserted_data_key_array[0]>= $key_array[0] && $inserted_data_key_array[0] < $key_array[1])) {

						    	if($individual_time_slot["fee"] !="") {
						    		if(class_exists('WOOCS_STARTER')){
	
										$individual_fee = apply_filters('woocs_exchange_value', $individual_time_slot["fee"]);
									} else {
										$individual_fee = $individual_time_slot["fee"];
									}
					    			$cart->add_fee( __( $delivery_fee_text, 'coderockz-woo-delivery' ) , $individual_fee, false );
					    		}
						    }
						}
			  		}
			  	}
			} else {
				if(isset($fees_settings['enable_time_slot_fee']) && $fees_settings['enable_time_slot_fee'] && isset($selected_delivery_time))
				{
			    	foreach($fees_settings['time_slot_fee'] as $key => $slot_fee)
			    	{
			    		
			    		$key = preg_replace('/-/', ' - ', $key);

			    		if(!empty($selected_delivery_time) && $selected_delivery_time == $key) {
			    			if(class_exists('WOOCS_STARTER')){	
								$individual_slot_fee = apply_filters('woocs_exchange_value', $slot_fee);
							} else {
								$individual_slot_fee = $slot_fee;
							}
					    	$cart->add_fee( __( $delivery_fee_text, 'coderockz-woo-delivery' ) , $individual_slot_fee, false );
					    }

			    	}
				}
			}

		}

		$custom_pickup_slot_settings = get_option('coderockz_woo_delivery_pickup_slot_settings');
		$enable_custom_pickup_slot = (isset($custom_pickup_slot_settings['enable_custom_pickup_slot']) && !empty($custom_pickup_slot_settings['enable_custom_pickup_slot'])) ? $custom_pickup_slot_settings['enable_custom_pickup_slot'] : false;


		if(((isset($delivery_option_session) && $delivery_option_session == "pickup" && $enable_delivery_option && $enable_pickup_time && $selected_order_type != "") || (!$enable_delivery_option && !$enable_delivery_time && $enable_pickup_time)) && is_checkout()) {
			if($selected_pickup_time != "") {
	        	if(strpos($selected_pickup_time, ' - ') !== false) {
	        		$selected_pickup_time = explode(' - ', $selected_pickup_time);
					$inserted_data_key_array_one = explode(':', $selected_pickup_time[0]);
					$inserted_data_key_array_two = explode(':', $selected_pickup_time[1]);
					$selected_pickup_time = ((int)$inserted_data_key_array_one[0]*60+(int)$inserted_data_key_array_one[1]).' - '.((int)$inserted_data_key_array_two[0]*60+(int)$inserted_data_key_array_two[1]);
					$inserted_data_key_array = explode(" - ",$selected_pickup_time);
	        	} else {
	        		$inserted_data_key_array = [];
	        		$inserted_data_key_array_one = explode(':', $selected_pickup_time);
	        		$selected_pickup_time = ((int)$inserted_data_key_array_one[0]*60+(int)$inserted_data_key_array_one[1]);
	        		$inserted_data_key_array[] = $selected_pickup_time;
	        	}
	    		
			}
			if($enable_custom_pickup_slot) {
				if(isset($custom_pickup_slot_settings['time_slot']) && count($custom_pickup_slot_settings['time_slot'])>0){


			  		foreach($custom_pickup_slot_settings['time_slot'] as $key => $individual_time_slot) {
			  			if($individual_time_slot['enable']) {
				  			$key = preg_replace('/-/', ' - ', $key);

				  			$key_array = explode(" - ",$key);
				    		
						    if(!empty($selected_pickup_time) && (isset($inserted_data_key_array[1]) && $inserted_data_key_array[0]>= $key_array[0] && $inserted_data_key_array[1] <= $key_array[1])) {

					    		if($individual_time_slot["fee"] !="") {
					    			if(class_exists('WOOCS_STARTER')){
	
										$individual_fee = apply_filters('woocs_exchange_value', $individual_time_slot["fee"]);
									} else {
										$individual_fee = $individual_time_slot["fee"];
									}
					    			$cart->add_fee( __( $pickup_fee_text, 'coderockz-woo-delivery' ) , $individual_fee, false );
					    		}
						    } elseif(!empty($selected_pickup_time) && !isset($inserted_data_key_array[1]) && ($inserted_data_key_array[0]>= $key_array[0] && $inserted_data_key_array[0] < $key_array[1])) {
						    	
						    	if($individual_time_slot["fee"] !="") {
						    		if(class_exists('WOOCS_STARTER')){
	
										$individual_fee = apply_filters('woocs_exchange_value', $individual_time_slot["fee"]);
									} else {
										$individual_fee = $individual_time_slot["fee"];
									}
					    			$cart->add_fee( __( $pickup_fee_text, 'coderockz-woo-delivery' ) , $individual_fee, false );
					    		}
						    }


						}
			  		}
			  	}
			} else {
				if(isset($fees_settings['enable_pickup_slot_fee']) && $fees_settings['enable_pickup_slot_fee'] && isset($selected_pickup_time))
				{
			    	foreach($fees_settings['pickup_slot_fee'] as $key => $slot_fee)
			    	{
			    		$key = preg_replace('/-/', ' - ', $key);
				    	if(!empty($selected_pickup_time) && $selected_pickup_time == $key) {
				    		if(class_exists('WOOCS_STARTER')){	
								$individual_slot_fee = apply_filters('woocs_exchange_value', $slot_fee);
							} else {
								$individual_slot_fee = $slot_fee;
							}
					    	$cart->add_fee( __( $pickup_fee_text, 'coderockz-woo-delivery' ) , $individual_slot_fee, false );
					    }
			    	}
				}
			}

		}

		$delivery_date_settings = get_option('coderockz_woo_delivery_date_settings');
		$enable_delivery_date = (isset($delivery_date_settings['enable_delivery_date']) && !empty($delivery_date_settings['enable_delivery_date'])) ? $delivery_date_settings['enable_delivery_date'] : false;

		
		if(((isset($delivery_option_session) && $delivery_option_session == "delivery" && $selected_order_type != "") || (!$enable_delivery_option && $enable_delivery_date)) && is_checkout()) {

			if (isset($fees_settings['enable_delivery_date_fee']) && $fees_settings['enable_delivery_date_fee'] && isset($selected_delivery_date) && !empty($selected_delivery_date))
			{
				$today = date('Y-m-d', time());
				$today_dt = new DateTime($today);
				$tomorrow = $today_dt->modify('+1 day')->format("Y-m-d");
				$today_dt = new DateTime($today);
				$day_after_tomorrow = $today_dt->modify('+2 day')->format("Y-m-d");

				if(date("Y-m-d", strtotime($selected_delivery_date)) == $today)
				{
					if(isset($fees_settings['same_day_fee']))
					{
		    			if(class_exists('WOOCS_STARTER')){	
							$fee = apply_filters('woocs_exchange_value', $fees_settings['same_day_fee']);
						} else {
							$fee = $fees_settings['same_day_fee'];
						}

		    			$day = $sameday_fee_text;
					}
				}
				elseif(date("Y-m-d", strtotime($selected_delivery_date)) == $tomorrow)
				{
					if(isset($fees_settings['next_day_fee']))
					{
		    			if(class_exists('WOOCS_STARTER')){	
							$fee = apply_filters('woocs_exchange_value', $fees_settings['next_day_fee']);
						} else {
							$fee = $fees_settings['next_day_fee'];
						}

		    			$day = $nextday_fee_text;
					}
				}
				elseif(date("Y-m-d", strtotime($selected_delivery_date)) == $day_after_tomorrow)
				{
					if(isset($fees_settings['day_after_tomorrow_fee']))
					{
		    			if(class_exists('WOOCS_STARTER')){	
							$fee = apply_filters('woocs_exchange_value', $fees_settings['day_after_tomorrow_fee']);
						} else {
							$fee = $fees_settings['day_after_tomorrow_fee'];
						}

		    			$day = $day_after_tomorrow_fee_text;
					}
				}
				else
				{
					if(isset($fees_settings['other_days_fee']))
					{
		    			if(class_exists('WOOCS_STARTER')){	
							$fee = apply_filters('woocs_exchange_value', $fees_settings['other_days_fee']);
						} else {
							$fee = $fees_settings['other_days_fee'];
						}

		    			$day = $other_fee_text;
					}
				}
				if(isset($fee) && $fee != 0)
				{
			    	$cart->add_fee( __( $day, 'coderockz-woo-delivery' ) , $fee, false ); 
				}
			}

			if (isset($fees_settings['enable_weekday_wise_delivery_fee']) && $fees_settings['enable_weekday_wise_delivery_fee'] && isset($selected_delivery_date) && !empty($selected_delivery_date))
			{	
				$current_week_day = date("w",strtotime($selected_delivery_date));			
				
				if(class_exists('WOOCS_STARTER')){
					$week_day_fee = (isset($fees_settings['weekday_wise_delivery_fee'][$current_week_day]) && $fees_settings['weekday_wise_delivery_fee'][$current_week_day] != "") ? apply_filters('woocs_exchange_value', (int)$fees_settings['weekday_wise_delivery_fee'][$current_week_day]) : "";	
				} else {
					$week_day_fee = (isset($fees_settings['weekday_wise_delivery_fee'][$current_week_day]) && $fees_settings['weekday_wise_delivery_fee'][$current_week_day] != "") ? (int)$fees_settings['weekday_wise_delivery_fee'][$current_week_day] : "";
				}

				if( $week_day_fee != "" && $week_day_fee != 0 )
				{
			    	$cart->add_fee( __( $weekday_fee_text, 'coderockz-woo-delivery' ) , $week_day_fee, false ); 
				}
			}

		}

	}

	public function coderockz_checkout_delivery_date_time_set_session( $posted_data ) {
	    parse_str( $posted_data, $output );
	    if ( isset( $output['coderockz_woo_delivery_date_field'] ) ){
	        WC()->session->set( 'selected_delivery_date', $output['coderockz_woo_delivery_date_field'] );
	    }

	    if ( isset( $output['coderockz_woo_delivery_time_field'] ) && $output['coderockz_woo_delivery_time_field'] != "as-soon-as-possible"){
	        WC()->session->set( 'selected_delivery_time', $output['coderockz_woo_delivery_time_field'] );
	    } else {
	    	WC()->session->set( 'selected_delivery_time', "" );
	    }

	    if ( isset( $output['coderockz_woo_delivery_pickup_time_field'] ) ){
	        WC()->session->set( 'selected_pickup_time', $output['coderockz_woo_delivery_pickup_time_field'] );
	    }

	    if ( isset( $output['coderockz_woo_delivery_delivery_selection_box'] ) ){
	        WC()->session->set( 'selected_order_type', $output['coderockz_woo_delivery_delivery_selection_box'] );
	    }
	}

	public function coderockz_woo_delivery_remove_order_note() {
		return false;
	}

	public function hide_show_shipping_methods_based_on_selection( $available_shipping_methods, $package ) {
			
		if(isset($_COOKIE['coderockz_woo_delivery_option_time_pickup'])) {
		  $delivery_option_session = $_COOKIE['coderockz_woo_delivery_option_time_pickup'];
		} elseif(!is_null(WC()->session)) {
		  $delivery_option_session = WC()->session->get( 'coderockz_woo_delivery_option_time_pickup' );
		}
	    

		$delivery_option_settings = get_option('coderockz_woo_delivery_option_delivery_settings');
		$enable_delivery_option = (isset($delivery_option_settings['enable_option_time_pickup']) && !empty($delivery_option_settings['enable_option_time_pickup'])) ? $delivery_option_settings['enable_option_time_pickup'] : false;

		$delivery_date_settings = get_option('coderockz_woo_delivery_date_settings');
		$pickup_date_settings = get_option('coderockz_woo_delivery_pickup_date_settings');
		$enable_delivery_date = (isset($delivery_date_settings['enable_delivery_date']) && !empty($delivery_date_settings['enable_delivery_date'])) ? $delivery_date_settings['enable_delivery_date'] : false;
		$enable_pickup_date = (isset($pickup_date_settings['enable_pickup_date']) && !empty($pickup_date_settings['enable_pickup_date'])) ? $pickup_date_settings['enable_pickup_date'] : false;

		$pickup_time_settings = get_option('coderockz_woo_delivery_pickup_settings');
		$enable_pickup_time = (isset($pickup_time_settings['enable_pickup_time']) && !empty($pickup_time_settings['enable_pickup_time'])) ? $pickup_time_settings['enable_pickup_time'] : false;

		$delivery_time_settings = get_option('coderockz_woo_delivery_time_settings');
		$enable_delivery_time = (isset($delivery_time_settings['enable_delivery_time']) && !empty($delivery_time_settings['enable_delivery_time'])) ? $delivery_time_settings['enable_delivery_time'] : false;


		$except_local_pickup = [];
		$local_pickup = [];

		$cart_total = $this ->helper->cart_total();
		$enable_free_shipping_restriction = (isset($delivery_option_settings['enable_free_shipping_restriction']) && !empty($delivery_option_settings['enable_free_shipping_restriction'])) ? $delivery_option_settings['enable_free_shipping_restriction'] : false;
		$enable_hide_other_shipping_method = (isset($delivery_option_settings['enable_hide_other_shipping_method']) && !empty($delivery_option_settings['enable_hide_other_shipping_method'])) ? $delivery_option_settings['enable_hide_other_shipping_method'] : false;
		$minimum_amount = (isset($delivery_option_settings['minimum_amount_shipping_restriction']) && $delivery_option_settings['minimum_amount_shipping_restriction'] != "") ? (float)$delivery_option_settings['minimum_amount_shipping_restriction'] : "";

	    if( $enable_free_shipping_restriction && $minimum_amount != "" && $cart_total < $minimum_amount){
	    	$hide_free_shipping = true;
	    } else {
	    	$hide_free_shipping = false;
	    }

	    if($hide_free_shipping) {
		    foreach ( $available_shipping_methods as $shipping_method_id => $shipping_method ) {
		    	if($shipping_method->method_id == "free_shipping") {
		    		unset($available_shipping_methods[$shipping_method_id]);
		    		break;
		    	}
		    }
		}

		$free_shipping_available = false;
		if($enable_free_shipping_restriction && $enable_hide_other_shipping_method && $minimum_amount != "" && $cart_total > $minimum_amount) {
			foreach ( $available_shipping_methods as $shipping_method_id => $shipping_method ) {
		    	if($shipping_method->method_id == "free_shipping") {
		    		$free_shipping_available = true;
		    	}
		    }

		    if($free_shipping_available) {
		    	foreach ( $available_shipping_methods as $shipping_method_id => $shipping_method ) {
			    	if($shipping_method->method_id == "flat_rate") {
			    		unset($available_shipping_methods[$shipping_method_id]);
			    	}
			    }
		    }
		}


		if( is_checkout() ) {

			$shipping_id = [];

			unset($_COOKIE["coderockz_woo_delivery_available_shipping_methods"]);
			setcookie("coderockz_woo_delivery_available_shipping_methods", null, -1, '/');
			WC()->session->__unset( 'coderockz_woo_delivery_available_shipping_methods' );
		
		    foreach ( $available_shipping_methods as $shipping_method_id => $shipping_method ) {

		    	$shipping_id [] = $shipping_method->method_id;

		    	if ( ! in_array( $shipping_method->method_id, ['local_pickup'] ) ) {
		            $local_pickup[] = $shipping_method_id; 
		        }

		        if ( in_array( $shipping_method->method_id, ['local_pickup'] ) ) {
		            $except_local_pickup[] = $shipping_method_id; 
		        }
		        
		    }

		    setcookie("coderockz_woo_delivery_available_shipping_methods", json_encode($shipping_id));
		    WC()->session->set( 'coderockz_woo_delivery_available_shipping_methods', $shipping_id );

		    $other_settings = get_option('coderockz_woo_delivery_other_settings');
		    $disable_dynamic_shipping = (isset($other_settings['disable_dynamic_shipping']) && !empty($other_settings['disable_dynamic_shipping'])) ? $other_settings['disable_dynamic_shipping'] : false;

			if(!$disable_dynamic_shipping) {

			    if((($enable_delivery_option && isset($delivery_option_session) && $delivery_option_session == "delivery") || (!$enable_delivery_option && (($enable_delivery_time && !$enable_pickup_time)||($enable_delivery_date && !$enable_pickup_date)))) && !empty($local_pickup) && is_checkout()) {

			    	foreach ( $except_local_pickup as $rate_id ) {
			            unset($available_shipping_methods[$rate_id]);
			        }

			        if(!empty($available_shipping_methods)) {		        	
						return $available_shipping_methods;
					} else {
						$remove_shipping = add_filter( 'woocommerce_cart_ready_to_calc_shipping', array($this,'coderockz_woo_delivery_disable_shipping_calc_on_cart'), 99 );
						return array();
					}
			    } elseif((($enable_delivery_option && isset($delivery_option_session) && $delivery_option_session == "pickup") || (!$enable_delivery_option && ((!$enable_delivery_time && $enable_pickup_time)||(!$enable_delivery_date && $enable_pickup_date)))) && !empty($except_local_pickup) && is_checkout()) {
			    	

			    	foreach ( $local_pickup as $rate_id ) {
			            unset($available_shipping_methods[$rate_id]);
			        }

			        if(!empty($available_shipping_methods)) {
						return $available_shipping_methods;
					} else {
						$remove_shipping = add_filter( 'woocommerce_cart_ready_to_calc_shipping', array($this,'coderockz_woo_delivery_disable_shipping_calc_on_cart'), 99 );
						return array();
					}
			    }

			    if((($enable_delivery_option && isset($delivery_option_session) && $delivery_option_session == "pickup") || (!$enable_delivery_option && ((!$enable_delivery_time && $enable_pickup_time)||(!$enable_delivery_date && $enable_pickup_date)))) && !empty($except_local_pickup) && is_checkout()) { 
					$remove_shipping = add_filter( 'woocommerce_cart_ready_to_calc_shipping', array($this,'coderockz_woo_delivery_disable_shipping_calc_on_cart'), 99 );

					foreach ( $available_shipping_methods as $rate_key => $rate_values ) {
			            // Not for "Free Shipping method" (all others only)
			            if ( 'free_shipping' !== $rate_values->method_id ) {

			                // Set the rate cost
			                $available_shipping_methods[$rate_key]->cost = 0;

			                // Set taxes rate cost (if enabled)
			                $taxes = array();
			                foreach ($available_shipping_methods[$rate_key]->taxes as $key => $tax)
			                    if( $available_shipping_methods[$rate_key]->taxes[$key] > 0 ) // set the new tax cost
			                        $taxes[$key] = 0;
			                $available_shipping_methods[$rate_key]->taxes = $taxes;
			            }
			        }

					return $available_shipping_methods;
				}


			}

		}
	
		return $available_shipping_methods;

	}

	public function coderockz_woo_delivery_disable_shipping_calc_on_cart( $show_shipping ) {
	    if( is_checkout() ) {
	        return false;
	    }
	    return $show_shipping;
	}

	public function refresh_shipping_methods( $post_data ){

	    $bool = true;
	    if(isset($_COOKIE['coderockz_woo_delivery_option_time_pickup'])) {
		  $delivery_option_session = $_COOKIE['coderockz_woo_delivery_option_time_pickup'];
		} elseif (!is_null(WC()->session)) {
		  $delivery_option_session = WC()->session->get( 'coderockz_woo_delivery_option_time_pickup' );
		}
	    
		$delivery_option_settings = get_option('coderockz_woo_delivery_option_delivery_settings');
		$enable_delivery_option = (isset($delivery_option_settings['enable_option_time_pickup']) && !empty($delivery_option_settings['enable_option_time_pickup'])) ? $delivery_option_settings['enable_option_time_pickup'] : false;

		$delivery_date_settings = get_option('coderockz_woo_delivery_date_settings');
		$pickup_date_settings = get_option('coderockz_woo_delivery_pickup_date_settings');
		$enable_delivery_date = (isset($delivery_date_settings['enable_delivery_date']) && !empty($delivery_date_settings['enable_delivery_date'])) ? $delivery_date_settings['enable_delivery_date'] : false;
		$enable_pickup_date = (isset($pickup_date_settings['enable_pickup_date']) && !empty($pickup_date_settings['enable_pickup_date'])) ? $pickup_date_settings['enable_pickup_date'] : false;

		$pickup_time_settings = get_option('coderockz_woo_delivery_pickup_settings');
		$enable_pickup_time = (isset($pickup_time_settings['enable_pickup_time']) && !empty($pickup_time_settings['enable_pickup_time'])) ? $pickup_time_settings['enable_pickup_time'] : false;

		$delivery_time_settings = get_option('coderockz_woo_delivery_time_settings');
		$enable_delivery_time = (isset($delivery_time_settings['enable_delivery_time']) && !empty($delivery_time_settings['enable_delivery_time'])) ? $delivery_time_settings['enable_delivery_time'] : false;


	    if((($enable_delivery_option && isset($delivery_option_session) && $delivery_option_session == "pickup") || (!$enable_delivery_option && ((!$enable_delivery_time && $enable_pickup_time)||(!$enable_delivery_date && $enable_pickup_date)))) && is_checkout()) {
	    	$bool = false;
	    }
	    // Mandatory to make it work with shipping methods
	    if(is_checkout()){
	    	foreach ( WC()->cart->get_shipping_packages() as $package_key => $package ){
		        WC()->session->set( 'shipping_for_package_' . $package_key, $bool );
		    }
	    }
	    
	    WC()->cart->calculate_shipping();
	}

	public function add_delivery_info_order_note( $order_id ) {

		$delivery_date_settings = get_option('coderockz_woo_delivery_date_settings');			
		$pickup_date_settings = get_option('coderockz_woo_delivery_pickup_date_settings');			
		$delivery_time_settings = get_option('coderockz_woo_delivery_time_settings');
		$pickup_time_settings = get_option('coderockz_woo_delivery_pickup_settings');
		$delivery_pickup_settings = get_option('coderockz_woo_delivery_pickup_location_settings');
		$additional_field_settings = get_option('coderockz_woo_delivery_additional_field_settings');

		$delivery_date_field_label = (isset($delivery_date_settings['field_label']) && !empty($delivery_date_settings['field_label'])) ? stripslashes($delivery_date_settings['field_label']) : "Delivery Date";
		$pickup_date_field_label = (isset($pickup_date_settings['pickup_field_label']) && !empty($pickup_date_settings['pickup_field_label'])) ? stripslashes($pickup_date_settings['pickup_field_label']) : "Pickup Date";
		$delivery_time_field_label = (isset($delivery_time_settings['field_label']) && !empty($delivery_time_settings['field_label'])) ? stripslashes($delivery_time_settings['field_label']) : "Delivery Time";
		$pickup_time_field_label = (isset($pickup_time_settings['field_label']) && !empty($pickup_time_settings['field_label'])) ? stripslashes($pickup_time_settings['field_label']) : "Pickup Time";
		$pickup_location_field_label = (isset($pickup_location_settings['field_label']) && !empty($pickup_location_settings['field_label'])) ? stripslashes($pickup_location_settings['field_label']) : "Pickup Location";
		$additional_field_field_label = (isset($additional_field_settings['field_label']) && !empty($additional_field_settings['field_label'])) ? stripslashes($additional_field_settings['field_label']) : "Special Note About Delivery";

		// if any timezone data is saved, set default timezone with the data
		$timezone = $this->helper->get_the_timezone();
		date_default_timezone_set($timezone);

		$delivery_date_format = (isset($delivery_date_settings['date_format']) && !empty($delivery_date_settings['date_format'])) ? $delivery_date_settings['date_format'] : "F j, Y";

		$time_format = (isset($delivery_time_settings['time_format']) && !empty($delivery_time_settings['time_format']))?$delivery_time_settings['time_format']:"12";
		if($time_format == 12) {
			$time_format = "h:i A";
		} elseif ($time_format == 24) {
			$time_format = "H:i";
		}

		$pickup_time_format = (isset($pickup_time_settings['time_format']) && !empty($pickup_time_settings['time_format']))?$pickup_time_settings['time_format']:"12";
		if($pickup_time_format == 12) {
			$pickup_time_format = "h:i A";
		} elseif ($pickup_time_format == 24) {
			$pickup_time_format = "H:i";
		}

	    $delivery_option_settings = get_option('coderockz_woo_delivery_option_delivery_settings');
	    $order_type_field_label = (isset($delivery_option_settings['delivery_option_label']) && !empty($delivery_option_settings['delivery_option_label'])) ? stripslashes($delivery_option_settings['delivery_option_label']) : "Order Type";
	    $delivery_field_label = (isset($delivery_option_settings['delivery_label']) && !empty($delivery_option_settings['delivery_label'])) ? stripslashes($delivery_option_settings['delivery_label']) : "Delivery";
		$pickup_field_label = (isset($delivery_option_settings['pickup_label']) && !empty($delivery_option_settings['pickup_label'])) ? stripslashes($delivery_option_settings['pickup_label']) : "Pickup";
	    
	    $order_note = "";

	    if(isset($_POST['coderockz_woo_delivery_delivery_selection_box']) && !empty($_POST['coderockz_woo_delivery_delivery_selection_box'])) {

			if($_POST['coderockz_woo_delivery_delivery_selection_box'] == "delivery") {
				$order_note .= $order_type_field_label .': '. $delivery_field_label;
				if(isset($_POST['coderockz_woo_delivery_date_field']) && !empty($_POST['coderockz_woo_delivery_date_field'])) {
					$order_note .= "<br>".$delivery_date_field_label .': '.sanitize_text_field($_POST['coderockz_woo_delivery_date_field']);
			    }
				

			    if(isset($_POST['coderockz_woo_delivery_time_field']) && !empty($_POST['coderockz_woo_delivery_time_field'])) {
					if($_POST['coderockz_woo_delivery_time_field'] == "as-soon-as-possible") {
						$as_soon_as_possible_text = (isset($delivery_time_settings['as_soon_as_possible_text']) && !empty($delivery_time_settings['as_soon_as_possible_text'])) ? stripslashes($delivery_time_settings['as_soon_as_possible_text']) : "As Soon As Possible";
						$time_value = $as_soon_as_possible_text;
					} else {

						$minutes = sanitize_text_field($_POST['coderockz_woo_delivery_time_field']);
						$minutes = explode(' - ', $minutes);

			    		if(!isset($minutes[1])) {
			    			$time_value = date($time_format, strtotime($minutes[0]));
			    		} else {

			    			$time_value = date($time_format, strtotime($minutes[0])) . ' - ' . date($time_format, strtotime($minutes[1]));

			    		}

		    		}

					$order_note .= "<br>".$delivery_time_field_label .': '.$time_value;
				}
			} elseif($_POST['coderockz_woo_delivery_delivery_selection_box'] == "pickup") {
				$order_note .= $order_type_field_label .': '. $pickup_field_label;
				if(isset($_POST['coderockz_woo_delivery_pickup_date_field']) && !empty($_POST['coderockz_woo_delivery_pickup_date_field'])) {

					$order_note .= "<br>".$pickup_date_field_label .': '.sanitize_text_field($_POST['coderockz_woo_delivery_pickup_date_field']);
			    }


				if(isset($_POST['coderockz_woo_delivery_pickup_time_field']) && !empty($_POST['coderockz_woo_delivery_pickup_time_field'])) {
					$pickup_minutes = sanitize_text_field($_POST['coderockz_woo_delivery_pickup_time_field']);
					$pickup_minutes = explode(' - ', $pickup_minutes);

		    		if(!isset($pickup_minutes[1])) {
		    			$pickup_time_value = date($pickup_time_format, strtotime($pickup_minutes[0]));
		    		} else {

		    			$pickup_time_value = date($pickup_time_format, strtotime($pickup_minutes[0])) . ' - ' . date($pickup_time_format, strtotime($pickup_minutes[1]));
		    		}

					$order_note .= "<br>".$pickup_time_field_label .': '.$pickup_time_value;
				}


				if(isset($_POST['coderockz_woo_delivery_pickup_location_field']) && !empty($_POST['coderockz_woo_delivery_pickup_location_field'])) {

					$order_note .= "<br>".$pickup_location_field_label .': '.sanitize_text_field($_POST['coderockz_woo_delivery_pickup_location_field']);
				}
			}
	    } else {
	    	if(isset($_POST['coderockz_woo_delivery_date_field']) && !empty($_POST['coderockz_woo_delivery_date_field'])) {

				$order_note .= "<br>".$delivery_date_field_label .': '.sanitize_text_field($_POST['coderockz_woo_delivery_date_field']);
		    }
			

		    if(isset($_POST['coderockz_woo_delivery_time_field']) && !empty($_POST['coderockz_woo_delivery_time_field'])) {
				$minutes = sanitize_text_field($_POST['coderockz_woo_delivery_time_field']);
				if($_POST['coderockz_woo_delivery_time_field'] == "as-soon-as-possible") {
					$as_soon_as_possible_text = (isset($delivery_time_settings['as_soon_as_possible_text']) && !empty($delivery_time_settings['as_soon_as_possible_text'])) ? stripslashes($delivery_time_settings['as_soon_as_possible_text']) : "As Soon As Possible";
					$time_value = $as_soon_as_possible_text;
				} else {

					$minutes = sanitize_text_field($_POST['coderockz_woo_delivery_time_field']);
					$minutes = explode(' - ', $minutes);

		    		if(!isset($minutes[1])) {
		    			$time_value = date($time_format, strtotime($minutes[0]));
		    		} else {

		    			$time_value = date($time_format, strtotime($minutes[0])) . ' - ' . date($time_format, strtotime($minutes[1]));

		    		}

	    		}

				$order_note .= "<br>".$delivery_time_field_label .': '.$time_value;
			}

			if(isset($_POST['coderockz_woo_delivery_pickup_date_field']) && !empty($_POST['coderockz_woo_delivery_pickup_date_field'])) {

				$order_note .= "<br>".$pickup_date_field_label .': '.sanitize_text_field($_POST['coderockz_woo_delivery_pickup_date_field']);
		    }


			if(isset($_POST['coderockz_woo_delivery_pickup_time_field']) && !empty($_POST['coderockz_woo_delivery_pickup_time_field'])) {
				$pickup_minutes = sanitize_text_field($_POST['coderockz_woo_delivery_pickup_time_field']);
				$pickup_minutes = explode(' - ', $pickup_minutes);

	    		if(!isset($pickup_minutes[1])) {
	    			$pickup_time_value = date($pickup_time_format, strtotime($pickup_minutes[0]));
	    		} else {

	    			$pickup_time_value = date($pickup_time_format, strtotime($pickup_minutes[0])) . ' - ' . date($pickup_time_format, strtotime($pickup_minutes[1]));
	    		}

				$order_note .= "<br>".$pickup_time_field_label .': '.$pickup_time_value;
			}


			if(isset($_POST['coderockz_woo_delivery_pickup_location_field']) && !empty($_POST['coderockz_woo_delivery_pickup_location_field'])) {

				$order_note .= "<br>".$pickup_location_field_label .': '.sanitize_text_field($_POST['coderockz_woo_delivery_pickup_location_field']);
			}
	    }


		if(isset($_POST['coderockz_woo_delivery_additional_field_field']) && !empty($_POST['coderockz_woo_delivery_additional_field_field'])) {

			$order_note .= "<br>".$additional_field_field_label .': '.sanitize_text_field($_POST['coderockz_woo_delivery_additional_field_field']);
		}


		// Add the note
		$order = new WC_Order( $order_id );
		if($order_note != "") {
			$order->add_order_note( $order_note );
		}
		
	}

	public function add_custom_notice_minimum_amount( ){

	    $cart_total = $this->helper->cart_total();

	    $currency_symbol = get_woocommerce_currency_symbol();

	    $delivery_option_settings = get_option('coderockz_woo_delivery_option_delivery_settings'); 

		$enable_delivery_restriction = (isset($delivery_option_settings['enable_delivery_restriction']) && !empty($delivery_option_settings['enable_delivery_restriction'])) ? $delivery_option_settings['enable_delivery_restriction'] : false;
		$minimum_amount = (isset($delivery_option_settings['minimum_amount_cart_restriction']) && $delivery_option_settings['minimum_amount_cart_restriction'] != "") ? (float)$delivery_option_settings['minimum_amount_cart_restriction'] : "";

		$minimum_cart_amount_notice = (isset($delivery_option_settings['delivery_restriction_notice']) && $delivery_option_settings['delivery_restriction_notice'] != "") ? $delivery_option_settings['delivery_restriction_notice'] : "Your cart amount must be ".$currency_symbol.$minimum_amount." to get the Delivery Option";

	    if( $enable_delivery_restriction && $minimum_amount != "" && $cart_total < $minimum_amount){
	        /*wc_clear_notices();*/
	        wc_add_notice( __($minimum_cart_amount_notice), 'notice');
	    }


		$enable_free_shipping_restriction = (isset($delivery_option_settings['enable_free_shipping_restriction']) && !empty($delivery_option_settings['enable_free_shipping_restriction'])) ? $delivery_option_settings['enable_free_shipping_restriction'] : false;
		$minimum_amount_free_shipping = (isset($delivery_option_settings['minimum_amount_shipping_restriction']) && $delivery_option_settings['minimum_amount_shipping_restriction'] != "") ? (float)$delivery_option_settings['minimum_amount_shipping_restriction'] : "";
		$minimum_cart_amount_notice_free_shipping = (isset($delivery_option_settings['free_shipping_restriction_notice']) && $delivery_option_settings['free_shipping_restriction_notice'] != "") ? $delivery_option_settings['free_shipping_restriction_notice'] : "Your cart amount must be ".$currency_symbol.$minimum_amount_free_shipping." to get the Free Shipping";

	    if( $enable_free_shipping_restriction && $minimum_amount_free_shipping != "" && $cart_total < $minimum_amount_free_shipping){
	    	/*wc_clear_notices();*/
	        wc_add_notice( __($minimum_cart_amount_notice_free_shipping), 'notice');
	    }


	}

	public function delete_woo_delivery_plugin_session_cookie() {
		//unset the plugin session & cookie first
		if(is_cart()){
			if(isset($_COOKIE['coderockz_woo_delivery_available_shipping_methods'])) {
			    unset($_COOKIE["coderockz_woo_delivery_available_shipping_methods"]);
				setcookie("coderockz_woo_delivery_available_shipping_methods", null, -1, '/');
			} 

			if(!is_null(WC()->session)) {		  
				WC()->session->__unset( 'coderockz_woo_delivery_available_shipping_methods' );  
			}
		}

	}

	public function coderockz_woo_delivery_get_available_shipping_methods() {

		$only_local_pickup = null;
		$no_local_pickup_with_other = null;
		$has_local_pickup_with_other = null;
		$shipping_methods = null;
		$dynamic_delivery_pickup_notice = "";


		$delivery_option_settings = get_option('coderockz_woo_delivery_option_delivery_settings'); 

		$enable_dynamic_order_type = (isset($delivery_option_settings['enable_dynamic_order_type']) && !empty($delivery_option_settings['enable_dynamic_order_type'])) ? $delivery_option_settings['enable_dynamic_order_type'] : false;

		$dynamic_order_type_no_delivery = isset($delivery_option_settings['dynamic_order_type_no_delivery']) && $delivery_option_settings['dynamic_order_type_no_delivery'] != "" ? stripslashes($delivery_option_settings['dynamic_order_type_no_delivery']) : "";
		$dynamic_order_type_no_pickup = isset($delivery_option_settings['dynamic_order_type_no_pickup']) && $delivery_option_settings['dynamic_order_type_no_pickup'] != "" ? stripslashes($delivery_option_settings['dynamic_order_type_no_pickup']) : "";
		$dynamic_order_type_no_delivery_pickup = isset($delivery_option_settings['dynamic_order_type_no_delivery_pickup']) && $delivery_option_settings['dynamic_order_type_no_delivery_pickup'] != "" ? stripslashes($delivery_option_settings['dynamic_order_type_no_delivery_pickup']) : "";

		if($enable_dynamic_order_type) {

			if(isset($_COOKIE['coderockz_woo_delivery_available_shipping_methods'])) {
			    $shipping_methods = json_decode(stripslashes($_COOKIE['coderockz_woo_delivery_available_shipping_methods']),true);
			} elseif(!is_null(WC()->session)) {		  
				$shipping_methods = WC()->session->get( 'coderockz_woo_delivery_available_shipping_methods' );  
			}


			if(is_array($shipping_methods)){
				if((in_array('local_pickup',$shipping_methods) && count($shipping_methods) >= 1)) {
					$has_local_pickup_with_other = true;
				}

				if((in_array('local_pickup',$shipping_methods) && count($shipping_methods) == 1)) {
					$only_local_pickup = true;
					$dynamic_delivery_pickup_notice = $dynamic_order_type_no_delivery;
				}

				
				if(!in_array('local_pickup',$shipping_methods) && count($shipping_methods) >= 1) {
					$no_local_pickup_with_other = true;
					$dynamic_delivery_pickup_notice = $dynamic_order_type_no_pickup;
				}

				if(count($shipping_methods) == 0) {
					$dynamic_delivery_pickup_notice = $dynamic_order_type_no_delivery_pickup;
				}
			}

		}

		if(isset($_COOKIE['coderockz_woo_delivery_available_shipping_methods'])) {
		    unset($_COOKIE["coderockz_woo_delivery_available_shipping_methods"]);
			setcookie("coderockz_woo_delivery_available_shipping_methods", null, -1, '/');
		} elseif(!is_null(WC()->session)) {		  
			WC()->session->__unset( 'coderockz_woo_delivery_available_shipping_methods' );  
		}

		$response = [
			"shipping_methods" => $shipping_methods,
			"has_local_pickup_with_other" => $has_local_pickup_with_other,
			"only_local_pickup" => $only_local_pickup,
			"no_local_pickup_with_other" => $no_local_pickup_with_other,
			"dynamic_delivery_pickup_notice" => $dynamic_delivery_pickup_notice
		];
		$response = json_encode($response);
		wp_send_json_success($response);
	}

	public function coderockz_woo_delivery_change_delivery_option_dynamically() {

		$cart_total = $this->helper->cart_total();
		wp_send_json_success($cart_total);
		
	}

}
