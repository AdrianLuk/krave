<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://coderockz.com
 * @since      1.0.0
 *
 * @package    Coderockz_Woo_Delivery
 * @subpackage Coderockz_Woo_Delivery/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Coderockz_Woo_Delivery
 * @subpackage Coderockz_Woo_Delivery/admin
 * @author     CodeRockz <admin@coderockz.com>
 */
class Coderockz_Woo_Delivery_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->helper = new Coderockz_Woo_Delivery_Helper();
	}

	/**
	 * Register the stylesheets for the admin area.
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

		$delivery_date_settings = get_option('coderockz_woo_delivery_date_settings');
		$delivery_date_calendar_theme = (isset($delivery_date_settings['calendar_theme']) && $delivery_date_settings['calendar_theme'] != "") ? $delivery_date_settings['calendar_theme'] : "";

		wp_enqueue_style( 'select2mincss', plugin_dir_url( __FILE__ ) . 'css/select2.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'jquery-ui-css', plugin_dir_url( __FILE__ ) . 'css/jquery-ui.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( "flatpickr_css", CODEROCKZ_WOO_DELIVERY_URL . 'public/css/flatpickr.min.css', array(), $this->version, 'all' );
		if($delivery_date_calendar_theme != "") {
			wp_enqueue_style( "flatpickr_calendar_theme_css", CODEROCKZ_WOO_DELIVERY_URL .'public/css/calendar-themes/' . $delivery_date_calendar_theme.'.css', array(), $this->version, 'all' );
		}
		if($this->helper->detect_plugin_settings_page()) {
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_style( "data_table_css", plugin_dir_url( __FILE__ ) . 'css/datatables.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( "date_range_css", plugin_dir_url( __FILE__ ) . 'css/daterangepicker.min.css', $this->version, 'all' );
			wp_enqueue_style( 'selectize_css',  plugin_dir_url(__FILE__) . 'css/selectize.min.css', array(),$this->version);
		}

		if($this->helper->detect_delivery_calendar_page()) {
			wp_enqueue_style( 'selectize_css',  plugin_dir_url(__FILE__) . 'css/selectize.min.css', array(),$this->version);
			wp_enqueue_style( "calendar_css", plugin_dir_url( __FILE__ ) . 'css/calendar.min.css', $this->version, 'all' );
		}

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/coderockz-woo-delivery-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
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
		
		$delivery_date_settings = get_option('coderockz_woo_delivery_date_settings');
		$delivery_date_calendar_locale = (isset($delivery_date_settings['calendar_locale']) && !empty($delivery_date_settings['calendar_locale'])) ? $delivery_date_settings['calendar_locale'] : "default";
		$pickup_date_settings = get_option('coderockz_woo_delivery_pickup_date_settings');
		$pickup_date_calendar_locale = (isset($pickup_date_settings['calendar_locale']) && !empty($pickup_date_settings['calendar_locale'])) ? $pickup_date_settings['calendar_locale'] : "default";
		wp_enqueue_script( 'jquery-effects-slide' );
		wp_enqueue_script( 'jquery-ui-accordion' );
		
		wp_enqueue_script( 'jquery-emojiRatings-js', plugin_dir_url( __FILE__ ) . 'js/jquery.emojiRatings.min.js', array( 'jquery' ), $this->version, true );
		
		if($this->helper->detect_plugin_settings_page()) {
			wp_enqueue_script( "animejs", plugin_dir_url( __FILE__ ) . 'js/anime.min.js', array( 'jquery' ), $this->version, true );
			wp_enqueue_script( "data_table_js", plugin_dir_url( __FILE__ ) . 'js/datatables.min.js', [], $this->version, true );
			wp_enqueue_script( "moment_js", plugin_dir_url( __FILE__ ) . 'js/moment.min.js', [], $this->version, true );
			wp_enqueue_script( "date_range_js", plugin_dir_url( __FILE__ ) . 'js/jquery.daterangepicker.min.js', ['moment_js'], $this->version, true );
			wp_enqueue_script("selectize_js", plugin_dir_url(__FILE__) . 'js/selectize.min.js', array( 'jquery' ), $this->version, true);
			wp_enqueue_script( $this->plugin_name."_js_plugin", plugin_dir_url( __FILE__ ) . 'js/coderockz-woo-delivery-admin-js-plugin.js', array( 'jquery', 'data_table_js', 'date_range_js','selectize_js' ), $this->version, true );
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/coderockz-woo-delivery-admin.js', array( 'jquery', 'animejs', 'selectWoo', 'wp-color-picker' ), $this->version, true );
		}

		if($this->helper->detect_delivery_calendar_page()) {
			wp_enqueue_script("selectize_js", plugin_dir_url(__FILE__) . 'js/selectize.min.js', array( 'jquery' ), $this->version, true);
			wp_enqueue_script( "delivery_calendar_locale_js", plugin_dir_url( __FILE__ ) . 'js/calendar-locales-all.min.js', ["delivery_calendar_js"], $this->version, true );
			wp_enqueue_script( "delivery_calendar_js", plugin_dir_url( __FILE__ ) . 'js/calendar.min.js', ['jquery'], $this->version, true );
			wp_enqueue_script( "delivery_calendar_script_js", plugin_dir_url( __FILE__ ) . 'js/coderockz-woo-delivery-delivery-calendar.js', array( 'jquery', 'delivery_calendar_js','delivery_calendar_locale_js', 'selectWoo', 'selectize_js' ), $this->version, true );
		}

		global $pagenow;
		if(( $pagenow == 'post.php' ) || (get_post_type() == 'post') ) {
			wp_enqueue_script( "flatpickr_js", CODEROCKZ_WOO_DELIVERY_URL . 'public/js/flatpickr.min.js', [], $this->version, true );
			wp_enqueue_script( "flatpickr_locale_js", 'https://npmcdn.com/flatpickr/dist/l10n/'.$delivery_date_calendar_locale.'.js', ["flatpickr_js"], $this->version, true );
			if($pickup_date_calendar_locale != $delivery_date_calendar_locale) {
				wp_enqueue_script( "flatpickr_pickup_locale_js", 'https://npmcdn.com/flatpickr/dist/l10n/'.$pickup_date_calendar_locale.'.js', ["flatpickr_js"], $this->version, true );
			}
			wp_enqueue_script( $this->plugin_name."_js_plugin", plugin_dir_url( __FILE__ ) . 'js/coderockz-woo-delivery-admin-js-single-order-js.js', array( 'jquery', 'flatpickr_js', 'flatpickr_locale_js'), $this->version, true );
		}

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/coderockz-woo-delivery-admin-script.js', array( 'jquery', 'jquery-emojiRatings-js', 'selectWoo' ), $this->version, true );
		
		$coderockz_woo_delivery_nonce = wp_create_nonce('coderockz_woo_delivery_nonce');
	    wp_localize_script($this->plugin_name, 'coderockz_woo_delivery_ajax_obj', array(
            'coderockz_woo_delivery_ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => $coderockz_woo_delivery_nonce,
        ));

	}

	public function coderockz_woo_delivery_menus_sections() {

        $access_shop_manager = (isset(get_option('coderockz_woo_delivery_other_settings')['access_shop_manager']) && !empty(get_option('coderockz_woo_delivery_other_settings')['access_shop_manager'])) ? get_option('coderockz_woo_delivery_other_settings')['access_shop_manager'] : false;


        if($access_shop_manager) {
        	if(current_user_can( 'manage_woocommerce' )) {
	        	add_menu_page(
					__('Woo Delivery', 'coderockz-woo-delivery'),
		            __('Woo Delivery', 'coderockz-woo-delivery'),
					'view_woocommerce_reports',
					'coderockz-woo-delivery-settings',
					array($this, "coderockz_woo_delivery_main_layout"),
					"dashicons-cart",
					null
				);
	        }


        } else {
        	add_menu_page(
				__('Woo Delivery', 'coderockz-woo-delivery'),
	            __('Woo Delivery', 'coderockz-woo-delivery'),
				'manage_options',
				'coderockz-woo-delivery-settings',
				array($this, "coderockz_woo_delivery_main_layout"),
				"dashicons-cart",
				null
			);

        }     

    }

    public function coderockz_woo_delivery_woocommerce_submenu() {


    	$access_shop_manager = (isset(get_option('coderockz_woo_delivery_other_settings')['access_shop_manager']) && !empty(get_option('coderockz_woo_delivery_other_settings')['access_shop_manager'])) ? get_option('coderockz_woo_delivery_other_settings')['access_shop_manager'] : false;


        if($access_shop_manager) {
        	if(current_user_can( 'manage_woocommerce' )) {
	        	add_submenu_page(
					'woocommerce',
					__( 'Delivery Calendar', 'coderockz-woo-delivery' ),
					__( 'Delivery Calendar', 'coderockz-woo-delivery' ),
					'view_woocommerce_reports',
					'coderockz-woo-delivery-delivery-calendar',
					array( $this, 'coderockz_woo_delivery_delivery_calendar' ),
					2
				);
	        }


        } else {
        	add_submenu_page(
				'woocommerce',
				__( 'Delivery Calendar', 'coderockz-woo-delivery' ),
				__( 'Delivery Calendar', 'coderockz-woo-delivery' ),
				'manage_options',
				'coderockz-woo-delivery-delivery-calendar',
				array( $this, 'coderockz_woo_delivery_delivery_calendar' ),
				2
			);

        } 

    }

	public function coderockz_woo_delivery_settings_link( $links ) {
    	if ( array_key_exists( 'deactivate', $links ) ) {
			$links['deactivate'] = str_replace( '<a', '<a class="coderockz-woo-delivery-deactivate-link"', $links['deactivate'] );
		}

        $links[] = '<a href="admin.php?page=coderockz-woo-delivery-settings">Settings</a>';

        return $links;
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

	public function register_bulk_delivery_completed_actions( $bulk_actions ) {
		$bulk_actions['coderockz_bulk_delivery_completed'] = __( 'Make Delivery/Pickup Completed', 'coderockz-woo-delivery' );
		return $bulk_actions;
	}


    public function coderockz_woo_delivery_get_order_details() {
    	check_ajax_referer('coderockz_woo_delivery_nonce');
    	$order_id = sanitize_text_field($_POST[ 'orderId' ]);
    	$order = wc_get_order($order_id);
    	$order_extra_details = "";
	    $order_extra_details .= '<div class="coderockz-woo-delivery-address-section">
		    <div class="coderockz-woo-delivery-billing-address">
		        <p class="coderockz-woo-delivery-address-heading">Billing Address</p>
		        <p>'.$order->get_billing_first_name().' '.$order->get_billing_last_name().'</p>';
		if($order->get_billing_company()) {
			$order_extra_details .= '<p>'.$order->get_billing_company().'</p>';
		}
		$order_extra_details .= '<p>'.$order->get_billing_address_1().'</p>';
		if($order->get_billing_address_2()) {
			$order_extra_details .= '<p>'.$order->get_billing_address_2().'</p>';
		}
		
		    $order_extra_details .= '<p>'.$order->get_billing_city() .'</p>';
		    $order_extra_details .= '<p>'.$order->get_billing_state();
		if($order->get_billing_postcode()) {
			$order_extra_details .= '-'.$order->get_billing_postcode().'</p>';
		}
		$order_extra_details .= '<p>'.$order->get_billing_country().'</p>';
		$order_extra_details .= '<p><span style="font-weight:700">Mobile:</span> '.$order->get_billing_phone().'</p>';
		$order_extra_details .= '<p><span style="font-weight:700">Email:</span> '.$order->get_billing_email().'</p>';
		$order_extra_details .= '<p><span style="font-weight:700">Payment Method:</span> '.$order->get_payment_method_title().'</p>';
		    $order_extra_details .= '</div>';
		    if($order->get_formatted_shipping_address()){
		    $order_extra_details .= '<div class="coderockz-woo-delivery-shipping-address">
		        <p class="coderockz-woo-delivery-address-heading">Shipping Address</p>
		        <p>'.$order->get_shipping_first_name().' '.$order->get_shipping_last_name().'</p>';
				if($order->get_shipping_company()) {
					$order_extra_details .= '<p>'.$order->get_shipping_company().'</p>';
				}
				$order_extra_details .= '<p>'.$order->get_shipping_address_1().'</p>';
				if($order->get_shipping_address_2()) {
					$order_extra_details .= '<p>'.$order->get_shipping_address_2().'</p>';
				}
				
				    $order_extra_details .= '<p>'.$order->get_shipping_city() .'</p>';
				    $order_extra_details .= '<p>'.$order->get_shipping_state();
				if($order->get_shipping_postcode()) {
					$order_extra_details .= '-'.$order->get_shipping_postcode().'</p>';
				}
				$order_extra_details .= '<p>'.$order->get_shipping_country().'</p>';
		    $order_extra_details .= '</div>';
			}
		    $order_extra_details .= '<div class="coderockz-woo-delivery-order-details">
		        <p class="coderockz-woo-delivery-address-heading">Order Products</p>
		        <table>
		            <thead>
		                <tr>
		                    <th style="width:40px;">S/N</th>
		                    <th style="width:250px;">item</th>
		                    <th>Cost</th>
		                    <th>Qty</th>
		                    <th>Total</th>
		                </tr>
		            </thead>
		            <tbody>';
		$i=1;
		foreach ($order->get_items() as $item) {
		$order_extra_details .= '<tr>';
		$order_extra_details .= '<td>'.$i.'</td>';
		$order_extra_details .= '<td>'.$this->helper->get_product_image($item->get_product_id()).$this->helper->product_name_length($item->get_name()).'</td>';
		$order_extra_details .= '<td>'.$this->helper->format_price($order->get_item_total( $item ),$order->get_id()).'</td>';
		$order_extra_details .= '<td>'.$item->get_quantity().'</td>';
		$order_extra_details .= '<td>'.$this->helper->format_price($item->get_total(),$order->get_id()).'</td>';
		$order_extra_details .= '</tr>';
		$i = $i+1;
		}
		$order_extra_details .= '</tbody>
		            
		        </table>
		    </div>
		</div>';
    	wp_send_json_success($order_extra_details);
    }
    public function coderockz_woo_delivery_submit_report_filter_form() { 
    	check_ajax_referer('coderockz_woo_delivery_nonce');
    	
    	$filtered_date = sanitize_text_field($_POST[ 'filteredDate' ]);
    	$filtered_delivery_type = sanitize_text_field($_POST[ 'filteredDeliveryType' ]);

    	$delivery_date_settings = get_option('coderockz_woo_delivery_date_settings');			
    	$pickup_date_settings = get_option('coderockz_woo_delivery_pickup_date_settings');			
		$delivery_time_settings = get_option('coderockz_woo_delivery_time_settings');
		$pickup_time_settings = get_option('coderockz_woo_delivery_pickup_settings');
		$delivery_pickup_settings = get_option('coderockz_woo_delivery_pickup_location_settings');
		$additional_field_settings = get_option('coderockz_woo_delivery_additional_field_settings');
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

		$delivery_date_field_label = (isset($delivery_date_settings['field_label']) && !empty($delivery_date_settings['field_label'])) ? stripslashes($delivery_date_settings['field_label']) : "Delivery Date";
		$pickup_date_field_label = (isset($pickup_date_settings['pickup_field_label']) && !empty($pickup_date_settings['pickup_field_label'])) ? stripslashes($pickup_date_settings['pickup_field_label']) : "Pickup Date";
		$delivery_time_field_label = (isset($delivery_time_settings['field_label']) && !empty($delivery_time_settings['field_label'])) ? stripslashes($delivery_time_settings['field_label']) : "Delivery Time";
		$pickup_time_field_label = (isset($pickup_time_settings['field_label']) && !empty($pickup_time_settings['field_label'])) ? stripslashes($pickup_time_settings['field_label']) : "Pickup Time";
		$pickup_location_field_label = (isset($pickup_location_settings['field_label']) && !empty($pickup_location_settings['field_label'])) ? stripslashes($pickup_location_settings['field_label']) : "Pickup Location";
		$additional_field_label = (isset($additional_field_settings['field_label']) && !empty($additional_field_settings['field_label'])) ? $additional_field_settings['field_label'] : "Special Note for Delivery";

		$localization_settings = get_option('coderockz_woo_delivery_localization_settings');
		$delivery_status_not_delivered_text = (isset($localization_settings['delivery_status_not_delivered_text']) && !empty($localization_settings['delivery_status_not_delivered_text'])) ? stripslashes($localization_settings['delivery_status_not_delivered_text']) : "Not Delivered";
		$delivery_status_delivered_text = (isset($localization_settings['delivery_status_delivered_text']) && !empty($localization_settings['delivery_status_delivered_text'])) ? stripslashes($localization_settings['delivery_status_delivered_text']) : "Delivery Completed";
		$pickup_status_not_picked_text = (isset($localization_settings['pickup_status_not_picked_text']) && !empty($localization_settings['pickup_status_not_picked_text'])) ? stripslashes($localization_settings['pickup_status_not_picked_text']) : "Not Picked";
		$pickup_status_picked_text = (isset($localization_settings['pickup_status_picked_text']) && !empty($localization_settings['pickup_status_picked_text'])) ? stripslashes($localization_settings['pickup_status_picked_text']) : "Pickup Completed";

		if(strpos($filtered_date, ' - ') !== false) {
			$filtered_dates = explode(' - ', $filtered_date);
			$orders = [];
			$delivery_orders = [];
			$pickup_orders = [];
			$period = new DatePeriod(new DateTime($filtered_dates[0]), new DateInterval('P1D'), new DateTime($filtered_dates[1].' +1 day'));
		    foreach ($period as $date) {
		        $dates[] = $date->format("Y-m-d");
		    }
		    foreach ($dates as $date) {
		    	if($filtered_delivery_type == "delivery"){
		    		$args = array(
				        'limit' => -1,
				        'delivery_date' => date("Y-m-d", strtotime($date)),
				        'delivery_type' => "delivery",
				    );
				    $orders_array = wc_get_orders( $args );
				    foreach ($orders_array as $order) {
				    	$orders[] = $order;
				    }
		    	} elseif($filtered_delivery_type == "pickup") {
		    		$args = array(
				        'limit' => -1,
				        'pickup_date' => date("Y-m-d", strtotime($date)),
				        'delivery_type' => "pickup",
				    );
				    $orders_array = wc_get_orders( $args );
				    foreach ($orders_array as $order) {
				    	$orders[] = $order;
				    }
		    	} else {
		    		$args = array(
				        'limit' => -1,
				        'delivery_date' => date("Y-m-d", strtotime($date)),
				    );

				    $orders_array = wc_get_orders( $args );
				    foreach ($orders_array as $order) {
				    	$delivery_orders[] = $order;
				    }

				    $args = array(
				        'limit' => -1,
				        'pickup_date' => date("Y-m-d", strtotime($date)),
				    );

				    $orders_array = wc_get_orders( $args );
				    foreach ($orders_array as $order) {
				    	$pickup_orders[] = $order;
				    }

				    $orders = array_merge($delivery_orders, $pickup_orders);
		    	}
		    	
			    
		    }
			

		} else {

		    if($filtered_delivery_type == "delivery"){
	    		$args = array(
			        'limit' => -1,
			        'delivery_date' => date('Y-m-d', strtotime($filtered_date)),
			        'delivery_type' => "delivery",
			    );
			    $orders = wc_get_orders( $args );
	    	} elseif($filtered_delivery_type == "pickup") {
	    		$args = array(
			        'limit' => -1,
			        'pickup_date' => date('Y-m-d', strtotime($filtered_date)),
			        'delivery_type' => "pickup",
			    );
			    $orders = wc_get_orders( $args );
	    	} else {
	    		$args = array(
			        'limit' => -1,
			        'delivery_date' => date('Y-m-d', strtotime($filtered_date)),
			    );
			    $delivery_orders = wc_get_orders( $args );

			    $args = array(
			        'limit' => -1,
			        'pickup_date' => date('Y-m-d', strtotime($filtered_date)),
			    );
			    $pickup_orders = wc_get_orders( $args );

			    $orders = array_merge($delivery_orders, $pickup_orders);
	    	}
		    
		}

		$order_details_html_body = '';
		$i=1;
		$unsorted_orders = [];
		foreach($orders as $order) {
			if( version_compare( get_option( 'woocommerce_version' ), '3.0.0', ">=" ) ) {            
		        $order_id = $order->get_id();
		    } else {
		        $order_id = $order->id;
		    }

		    if(metadata_exists('post', $order_id, 'delivery_type')) {
				$delivery_complete_btn_text = ucfirst(get_post_meta($order_id, 'delivery_type', true));
			} else {
				$delivery_complete_btn_text = "Delivery";
			}

		    $delivery_date_timestamp = 0;
	    	$delivery_time_start = 0;
	    	$delivery_time_end = 0;

	    	if(metadata_exists('post', $order_id, 'delivery_date') && get_post_meta($order_id, 'delivery_date', true) !="") {
		    	$delivery_date_timestamp = strtotime(get_post_meta( $order_id, 'delivery_date', true ));
		    } elseif(metadata_exists('post', $order_id, 'pickup_date') && get_post_meta($order_id, 'pickup_date', true) !="") {
		    	$delivery_date_timestamp = strtotime(get_post_meta( $order_id, 'pickup_date', true ));
		    }

	    	if(metadata_exists('post', $order_id, 'delivery_time') && get_post_meta($order_id, 'delivery_time', true) !="") {
	    		if(get_post_meta($order_id, 'delivery_time', true) !="as-soon-as-possible") {
	    			$minutes = get_post_meta($order_id,"delivery_time",true);

			    	$slot_key = explode(' - ', $minutes);
					$slot_key_one = explode(':', $slot_key[0]);
					$delivery_time_start = ((int)$slot_key_one[0]*60+(int)$slot_key_one[1]);

			    	if(!isset($slot_key[1])) {
			    		$delivery_time_end = 0;
			    	} else {
			    		$slot_key_two = explode(':', $slot_key[1]);
			    		$delivery_time_end = ((int)$slot_key_two[0]*60+(int)$slot_key_two[1]);
			    	}
	    		} else {
	    			$delivery_time_end = 0;
	    		}
		    	
		    	
		    } elseif(metadata_exists('post', $order_id, 'pickup_time') && get_post_meta($order_id, 'pickup_time', true) !="") {
		    	$minutes = get_post_meta($order_id,"pickup_time",true);
		    	$slot_key = explode(' - ', $minutes);
				$slot_key_one = explode(':', $slot_key[0]);
				$delivery_time_start = ((int)$slot_key_one[0]*60+(int)$slot_key_one[1]);
		    	if(!isset($slot_key[1])) {
		    		$delivery_time_end = 0;
		    	} else {
		    		$slot_key_two = explode(':', $slot_key[1]);
			    	$delivery_time_end = ((int)$slot_key_two[0]*60+(int)$slot_key_two[1]);
		    	}
		    }

	    	$delivery_details_in_timestamp = (int)$delivery_date_timestamp+(int)$delivery_time_start+(int)$delivery_time_end;

	    	$unsorted_orders['order_details_html_'.$i] = $delivery_details_in_timestamp;


	    	${'order_details_html_'.$i} = "";
			${'order_details_html_'.$i} .= '<tr data-plugin_url ='.CODEROCKZ_WOO_DELIVERY_URL.' data-order_id='.$order_id.'>';
	        ${'order_details_html_'.$i} .= '<td class="details-control sorting_disabled"></td>';
	        ${'order_details_html_'.$i} .= '<td>#'.$order->get_id().'</td>';
	        $order_created_obj= new DateTime($order->get_date_created());
			$order_created = $order_created_obj->format("F j, Y");
			${'order_details_html_'.$i} .= '<td>'.$order_created.'</td>';

			
		    $delivery_details = "";
		    if(metadata_exists('post', $order_id, 'delivery_date') && get_post_meta($order_id, 'delivery_date', true) !="") {

		    	$delivery_details .= '<p><strong>'.$delivery_date_field_label.':</strong> ' . date($delivery_date_format, strtotime(get_post_meta( $order_id, 'delivery_date', true ))) . '</p>';

		    }

		    if(metadata_exists('post', $order_id, 'pickup_date') && get_post_meta($order_id, 'pickup_date', true) !="") {

		    	$delivery_details .= '<p><strong>'.$pickup_date_field_label.':</strong> ' . date($delivery_date_format, strtotime(get_post_meta( $order_id, 'pickup_date', true ))) . '</p>'; 

		    }

		    if(metadata_exists('post', $order_id, 'delivery_time') && get_post_meta($order_id, 'delivery_time', true) !="") {

		    	if(get_post_meta($order_id, 'delivery_time', true) !="as-soon-as-possible") {
			    	$minutes = get_post_meta($order_id,"delivery_time",true);
			    	$minutes = explode(' - ', $minutes);

		    		if(!isset($minutes[1])) {
		    			$delivery_details .= '<p><strong>'.$delivery_time_field_label.':</strong> ' . date($time_format, strtotime($minutes[0])) . '</p>';
		    		} else {

		    			$delivery_details .= '<p><strong>'.$delivery_time_field_label.':</strong> ' . date($time_format, strtotime($minutes[0])) . ' - ' . date($time_format, strtotime($minutes[1])) . '</p>';  			
		    		}
	    		} else {
	    			$as_soon_as_possible_text = (isset($delivery_time_settings['as_soon_as_possible_text']) && !empty($delivery_time_settings['as_soon_as_possible_text'])) ? stripslashes($delivery_time_settings['as_soon_as_possible_text']) : "As Soon As Possible";
	    			$delivery_details .= '<p><strong>'.$delivery_time_field_label.':</strong> ' . $as_soon_as_possible_text . '</p>';
	    		}
		    	
		    }

		    if(metadata_exists('post', $order_id, 'pickup_time') && get_post_meta($order_id, 'pickup_time', true) !="") {
		    	$pickup_minutes = get_post_meta($order_id,"pickup_time",true);
		    	$pickup_minutes = explode(' - ', $pickup_minutes);

	    		if(!isset($pickup_minutes[1])) {
	    			$delivery_details .= '<p><strong>'.$pickup_time_field_label.':</strong> ' . date($pickup_time_format, strtotime($pickup_minutes[0])) . '</p>';
	    		} else {

	    			$delivery_details .= '<p><strong>'.$pickup_time_field_label.':</strong> ' . date($pickup_time_format, strtotime($pickup_minutes[0])) . ' - ' . date($pickup_time_format, strtotime($pickup_minutes[1])) . '</p>';  			
	    		}
		    	
		    }

		    if(metadata_exists('post', $order_id, 'delivery_pickup') && get_post_meta($order_id, 'delivery_pickup', true) !="") {
				$delivery_details .= '<p><strong>'.$pickup_location_field_label.':</strong> ' . get_post_meta($order_id, 'delivery_pickup', true) . '</p>';
			}

			if(metadata_exists('post', $order_id, 'additional_note') && get_post_meta($order_id, 'additional_note', true) !="") {
				$delivery_details .= '<p><strong>'.$additional_field_label.':</strong> ' . get_post_meta($order_id, 'additional_note', true) . '</p>';
			}

			${'order_details_html_'.$i} .= '<td>'.$delivery_details.'</td>';



			if(metadata_exists('post', $order_id, 'delivery_status') && get_post_meta($order_id, 'delivery_status', true) !="" && get_post_meta($order_id, 'delivery_status', true) =="delivered") {
				if(metadata_exists('post', $order_id, 'delivery_type') && get_post_meta($order_id, 'delivery_type', true) !="" && get_post_meta($order_id, 'delivery_type', true) =="pickup") {
					${'order_details_html_'.$i} .= '<td class="coderockz_woo_delivery_status"><span class="coderockz_woo_delivery_delivered_text">'.$pickup_status_picked_text.'</span></td>';
				} else {
					${'order_details_html_'.$i} .= '<td class="coderockz_woo_delivery_status"><span class="coderockz_woo_delivery_delivered_text">'.$delivery_status_delivered_text.'</span></td>';
				}
				
			} else {

				if(metadata_exists('post', $order_id, 'delivery_type') && get_post_meta($order_id, 'delivery_type', true) !="" && get_post_meta($order_id, 'delivery_type', true) =="pickup") {
					${'order_details_html_'.$i} .= '<td class="coderockz_woo_delivery_status"><span class="coderockz_woo_delivery_not_delivered_text">'.$pickup_status_not_picked_text.'</span></td>';
				} else {
					${'order_details_html_'.$i} .= '<td class="coderockz_woo_delivery_status"><span class="coderockz_woo_delivery_not_delivered_text">'.$delivery_status_not_delivered_text.'</span></td>';
				}
			}


			if($order->get_status() == "completed") {
				${'order_details_html_'.$i} .= '<td class="coderockz_woo_delivery_order_status"><span class="coderockz_woo_delivery_delivered_text">Completed</span></td>';
			} else {
				${'order_details_html_'.$i} .= '<td class="coderockz_woo_delivery_order_status"><span>'.$order->get_status().'</span></td>';
			}
			${'order_details_html_'.$i} .= '<td>'.$order->get_formatted_order_total().'</td>';
			${'order_details_html_'.$i} .= '<td>
										<button class="coderockz-woo-delivery-complete-btn button coderockz-woo-delivery-tooltip" style="margin-right:5px!important;padding-left: 4px!important;" tooltip="Make the '.$delivery_complete_btn_text.' Completed">
											<img src="'.CODEROCKZ_WOO_DELIVERY_URL.'admin/images/delivery_complete.png" alt="" style="width:17px;vertical-align: middle;margin-left: 1px;">
										</button>
										<a href="'.get_bloginfo( 'url' ).'/wp-admin/post.php?post='.$order_id.'&action=edit" target="_blank" class="button coderockz-woo-delivery-tooltip" style="margin-right:5px!important;padding-left: 4px!important;" tooltip="Go to The Order Page">
											<span class="dashicons dashicons-visibility" style="vertical-align:middle!important;"></span>
										</a>
										<button class="coderockz-woo-delivery-order-complete-btn button coderockz-woo-delivery-tooltip" style="margin-right:5px!important;padding-left: 4px!important;" tooltip="Make the Delivery Completed">
											<span class="dashicons dashicons-yes" style="vertical-align:middle!important;"></span>
										</button>
									</td>';
			${'order_details_html_'.$i} .= '</tr>';
			$i=$i+1;
		}

		asort($unsorted_orders);

		foreach ($unsorted_orders as $key => $value) {
			$order_details_html_body .= ${$key};
		}

		$order_details_html = '';
		$order_details_html .= '<table id="coderockz_woo_delivery_report_table" class="display" style="width:100%">';
		$order_details_html .= '<thead>
		            <tr>
		                <th class="details-control sorting_disabled"></th>
		                <th>Order No</th>
		                <th>Order Date</th>
		                <th>Delivery Details</th>
		                <th>Delivery Status</th>
		                <th>Order Status</th>
		                <th>Total</th>
		                <th>Action</th>
		            </tr>
		        </thead>
		        <tbody>';
		$order_details_html .= $order_details_html_body;
		$order_details_html .= '</tbody>';
		$order_details_html .= '<tfoot>
		            <tr>
		                <th class="details-control sorting_disabled"></th>
		                <th>Order No</th>
		                <th>Ordered Date</th>
		                <th>Delivery Details</th>
		                <th>Delivery Status</th>
		                <th>Order Status</th>
		                <th>Total</th>
		                <th>Action</th>
		            </tr>
		        </tfoot>';
		$order_details_html .= '</table>';
		wp_send_json_success($order_details_html);
    }


    public function coderockz_woo_delivery_submit_report_product_quantity() { 
    	check_ajax_referer('coderockz_woo_delivery_nonce');
    	
    	$filtered_date = sanitize_text_field($_POST[ 'filteredDate' ]);
    	$filtered_delivery_type = sanitize_text_field($_POST[ 'filteredDeliveryType' ]);

    	$delivery_date_settings = get_option('coderockz_woo_delivery_date_settings');			
    	$pickup_date_settings = get_option('coderockz_woo_delivery_pickup_date_settings');			
		$delivery_time_settings = get_option('coderockz_woo_delivery_time_settings');
		$pickup_time_settings = get_option('coderockz_woo_delivery_pickup_settings');
		$delivery_pickup_settings = get_option('coderockz_woo_delivery_pickup_location_settings');
		$additional_field_settings = get_option('coderockz_woo_delivery_additional_field_settings');
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

		$delivery_date_field_label = (isset($delivery_date_settings['field_label']) && !empty($delivery_date_settings['field_label'])) ? stripslashes($delivery_date_settings['field_label']) : "Delivery Date";
		$pickup_date_field_label = (isset($pickup_date_settings['pickup_field_label']) && !empty($pickup_date_settings['pickup_field_label'])) ? stripslashes($pickup_date_settings['pickup_field_label']) : "Pickup Date";
		$delivery_time_field_label = (isset($delivery_time_settings['field_label']) && !empty($delivery_time_settings['field_label'])) ? stripslashes($delivery_time_settings['field_label']) : "Delivery Time";
		$pickup_time_field_label = (isset($pickup_time_settings['field_label']) && !empty($pickup_time_settings['field_label'])) ? stripslashes($pickup_time_settings['field_label']) : "Pickup Time";
		$pickup_location_field_label = (isset($pickup_location_settings['field_label']) && !empty($pickup_location_settings['field_label'])) ? stripslashes($pickup_location_settings['field_label']) : "Pickup Location";
		$additional_field_label = (isset($additional_field_settings['field_label']) && !empty($additional_field_settings['field_label'])) ? $additional_field_settings['field_label'] : "Special Note for Delivery";

		if(strpos($filtered_date, ' - ') !== false) {
			$filtered_dates = explode(' - ', $filtered_date);
			$orders = [];
			$delivery_orders = [];
			$pickup_orders = [];
			$period = new DatePeriod(new DateTime($filtered_dates[0]), new DateInterval('P1D'), new DateTime($filtered_dates[1].' +1 day'));
		    foreach ($period as $date) {
		        $dates[] = $date->format("Y-m-d");
		    }
		    foreach ($dates as $date) {
		    	if($filtered_delivery_type == "delivery"){
		    		$args = array(
				        'limit' => -1,
				        'delivery_date' => date('Y-m-d', strtotime($date)),
				        'delivery_type' => "delivery",
				    );
				    $orders_array = wc_get_orders( $args );
				    foreach ($orders_array as $order) {
				    	$orders[] = $order;
				    }
		    	} elseif($filtered_delivery_type == "pickup") {
		    		$args = array(
				        'limit' => -1,
				        'pickup_date' => date('Y-m-d', strtotime($date)),
				        'delivery_type' => "pickup",
				    );
				    $orders_array = wc_get_orders( $args );
				    foreach ($orders_array as $order) {
				    	$orders[] = $order;
				    }
		    	} else {
		    		$args = array(
				        'limit' => -1,
				        'delivery_date' => date('Y-m-d', strtotime($date)),
				    );

				    $orders_array = wc_get_orders( $args );
				    foreach ($orders_array as $order) {
				    	$delivery_orders[] = $order;
				    }

				    $args = array(
				        'limit' => -1,
				        'pickup_date' => date('Y-m-d', strtotime($date)),
				    );

				    $orders_array = wc_get_orders( $args );
				    foreach ($orders_array as $order) {
				    	$pickup_orders[] = $order;
				    }

				    $orders = array_merge($delivery_orders, $pickup_orders);
		    	}
		    	
			    
		    }
			

		} else {

		    if($filtered_delivery_type == "delivery"){
	    		$args = array(
			        'limit' => -1,
			        'delivery_date' => date('Y-m-d', strtotime($filtered_date)),
			        'delivery_type' => "delivery",
			    );
			    $orders = wc_get_orders( $args );
	    	} elseif($filtered_delivery_type == "pickup") {
	    		$args = array(
			        'limit' => -1,
			        'pickup_date' => date('Y-m-d', strtotime($filtered_date)),
			        'delivery_type' => "pickup",
			    );
			    $orders = wc_get_orders( $args );
	    	} else {
	    		$args = array(
			        'limit' => -1,
			        'delivery_date' => date('Y-m-d', strtotime($filtered_date)),
			    );
			    $delivery_orders = wc_get_orders( $args );

			    $args = array(
			        'limit' => -1,
			        'pickup_date' => date('Y-m-d', strtotime($filtered_date)),
			    );
			    $pickup_orders = wc_get_orders( $args );

			    $orders = array_merge($delivery_orders, $pickup_orders);
	    	}
		    
		}
		$product_name = [];
		$product_quantity = [];
		foreach($orders as $order) {
			if ( $order->get_status() != 'pending' ) {
			    foreach ( $order->get_items() as $item_id => $item ) {
				   if($item->get_variation_id() == 0) {
				   		if(array_key_exists($item->get_product_id(),$product_quantity)) {
					   		$product_quantity[$item->get_product_id()] = $product_quantity[$item->get_product_id()]+$item->get_quantity();
					   } else {
					   		$product_quantity[$item->get_product_id()] = $item->get_quantity();
					   }
					   if(!array_key_exists($item->get_product_id(),$product_name)) {
					   		$product_name[$item->get_product_id()] = $item->get_name();
					   }
				   } else {
				   		if(array_key_exists($item->get_variation_id(),$product_quantity)) {
					   		$product_quantity[$item->get_variation_id()] = $product_quantity[$item->get_variation_id()]+$item->get_quantity();
					   } else {
					   		$product_quantity[$item->get_variation_id()] = $item->get_quantity();
					   }
					   if(!array_key_exists($item->get_variation_id(),$product_name)) {
					   		$variation = wc_get_product($item->get_variation_id());
						   	$product_name[$item->get_variation_id()] = $variation->get_formatted_name();
					   }
				   }

				}

			}	
		}

		$order_details_html_body = '';
		foreach($product_name as $id => $name) {
			$order_details_html_body .= '<tr>';
	        $order_details_html_body .= '<td>'.$name.'</td>';
			$order_details_html_body .= '<td>'.$product_quantity[$id].'</td>';
			$order_details_html_body .= '</tr>';
		}

		$order_details_html = '';
		$order_details_html .= '<p style="font-size: 14px;color: #C94926;font-weight: 700;font-style: italic;">Product quantity is not considered if order has pending payment</p>';
		$order_details_html .= '<table id="coderockz_woo_delivery_report_product_quantity_table" style="width:50%">';
		$order_details_html .= '<thead>
		            <tr>
		                <th>Product</th>
		                <th>Quantity</th>
		            </tr>
		        </thead>
		        <tbody>';
		$order_details_html .= $order_details_html_body;
		$order_details_html .= '</tbody>';
		$order_details_html .= '<tfoot>
		            <tr>
		                <th>Product</th>
		                <th>Quantity</th>
		            </tr>
		        </tfoot>';
		$order_details_html .= '</table>';
		wp_send_json_success($order_details_html);
    }

    public function coderockz_woo_delivery_make_order_delivered() {
    	check_ajax_referer('coderockz_woo_delivery_nonce');
    	$order_id = sanitize_text_field($_POST[ 'orderId' ]);
    	update_post_meta($order_id, 'delivery_status', 'delivered');
    	if(metadata_exists('post', $order_id, 'delivery_type') && get_post_meta($order_id, 'delivery_type', true) !="" && get_post_meta($order_id, 'delivery_type', true) =="pickup") {
			$delivery_type = "pickup";
		} else {
			$delivery_type = "delivery";
		}
		
		$localization_settings = get_option('coderockz_woo_delivery_localization_settings');

		$delivery_status_delivered_text = (isset($localization_settings['delivery_status_delivered_text']) && !empty($localization_settings['delivery_status_delivered_text'])) ? stripslashes($localization_settings['delivery_status_delivered_text']) : "Delivery Completed";

		$pickup_status_picked_text = (isset($localization_settings['pickup_status_picked_text']) && !empty($localization_settings['pickup_status_picked_text'])) ? stripslashes($localization_settings['pickup_status_picked_text']) : "Pickup Completed";

		$response=[
			"delivery_type" => $delivery_type,
			"delivery_status_delivered_text" => $delivery_status_delivered_text,
			"pickup_status_picked_text" => $pickup_status_picked_text,
		];
		$response = json_encode($response);
		wp_send_json_success($response);

    }
    public function coderockz_woo_delivery_make_order_delivered_bulk() {
    	if ( ! is_user_logged_in() ) {
			auth_redirect();
			exit;
		}
		check_ajax_referer('coderockz_woo_delivery_nonce');
    	$order_ids = array();
    	if ( isset( $_REQUEST['orderIds'] ) ) {
			if ( wc_user_has_role( get_current_user_id(), 'administrator' ) || wc_user_has_role( get_current_user_id(), 'shop_manager' ) ) {
				$order_ids = sanitize_text_field( wp_unslash( $_REQUEST['orderIds'] ) );
			} else {
				die( 'You are not allowed to make the delivery/pickup completed.' );
			}
		}
		$order_ids = explode( ',', $order_ids );
		foreach ($order_ids as $order_id) {
			update_post_meta($order_id, 'delivery_status', 'delivered');
		}
    	wp_send_json_success();
    }
    public function coderockz_woo_delivery_make_order_complete() {
    	check_ajax_referer('coderockz_woo_delivery_nonce');
    	$order_id = sanitize_text_field($_POST[ 'orderId' ]);
    	$order = wc_get_order( $order_id );
    	$order->update_status( 'completed' );
    	wp_send_json_success();
    }
    public function coderockz_woo_delivery_process_delivery_timezone_form() { 
    	check_ajax_referer('coderockz_woo_delivery_nonce');
		parse_str( $_POST[ 'dateFormData' ], $date_form_data );
		$timezone_form_settings = [];

		$store_location_timezone = sanitize_text_field($date_form_data['coderockz_delivery_time_timezone']);

		$timezone_form_settings['store_location_timezone'] = $store_location_timezone;


		if(get_option('coderockz_woo_delivery_time_settings') == false) {
			update_option('coderockz_woo_delivery_time_settings', $timezone_form_settings);
		} else {
			$timezone_form_settings = array_merge(get_option('coderockz_woo_delivery_time_settings'),$timezone_form_settings);
			update_option('coderockz_woo_delivery_time_settings', $timezone_form_settings);
		}

		wp_send_json_success();
	}
    
    public function coderockz_woo_delivery_process_delivery_date_form() { 
    	check_ajax_referer('coderockz_woo_delivery_nonce');
		
		$date_form_settings = [];

		parse_str( $_POST[ 'dateFormData' ], $date_form_data );

		$enable_delivery_date = !isset($date_form_data['coderockz_enable_delivery_date']) ? false : true;
		
		$delivery_date_mandatory = !isset($date_form_data['coderockz_delivery_date_mandatory']) ? false : true;
		
		$delivery_date_field_label = sanitize_text_field($date_form_data['coderockz_delivery_date_field_label']);
		$delivery_date_field_placeholder = sanitize_text_field($date_form_data['coderockz_delivery_date_field_placeholder']);
		
		$delivery_date_selectable_date = sanitize_text_field($date_form_data['coderockz_delivery_date_selectable_date']);
		$maximum_order_per_day = sanitize_text_field($date_form_data['coderockz_delivery_date_maximum_order_per_day']);
		
		$delivery_date_format = sanitize_text_field($date_form_data['coderockz_delivery_date_format']);

		$delivery_date_calendar_locale = sanitize_text_field($date_form_data['coderockz_delivery_date_calendar_locale']);
		$calendar_theme = sanitize_text_field($date_form_data['coderockz_woo_delivery_calendar_theme']);

		$delivery_week_starts_from = sanitize_text_field($date_form_data['coderockz_delivery_date_week_starts_from']);
		
		$delivery_date_delivery_days="";
		if(isset($date_form_data['coderockz_delivery_date_delivery_days'])) {
			$delivery_days = $this->helper->coderockz_woo_delivery_array_sanitize($date_form_data['coderockz_delivery_date_delivery_days']);
			$delivery_date_delivery_days = implode(',', $delivery_days);
		}

		$same_day_delivery = !isset($date_form_data['coderockz_disable_same_day_delivery']) ? false : true;
		$auto_select_first_date = !isset($date_form_data['coderockz_auto_select_first_date']) ? false : true;
		

		$date_form_settings['enable_delivery_date'] = $enable_delivery_date;
		$date_form_settings['delivery_date_mandatory'] = $delivery_date_mandatory;
		$date_form_settings['field_label'] = $delivery_date_field_label;
		$date_form_settings['field_placeholder'] = $delivery_date_field_placeholder;
		$date_form_settings['selectable_date'] = $delivery_date_selectable_date;
		$date_form_settings['maximum_order_per_day'] = $maximum_order_per_day;
		$date_form_settings['date_format'] = $delivery_date_format;
		$date_form_settings['delivery_days'] = $delivery_date_delivery_days;
		$date_form_settings['calendar_locale'] = $delivery_date_calendar_locale;
		$date_form_settings['calendar_theme'] = $calendar_theme;
		$date_form_settings['week_starts_from'] = $delivery_week_starts_from;
		$date_form_settings['disable_same_day_delivery'] = $same_day_delivery;
		$date_form_settings['auto_select_first_date'] = $auto_select_first_date;
		
		if(get_option('coderockz_woo_delivery_date_settings') == false) {
			update_option('coderockz_woo_delivery_date_settings', $date_form_settings);
		} else {
			$date_form_settings = array_merge(get_option('coderockz_woo_delivery_date_settings'),$date_form_settings);
			update_option('coderockz_woo_delivery_date_settings', $date_form_settings);
		}
		wp_send_json_success();
		
    }

    public function coderockz_woo_delivery_process_delivery_date_delivery_opendays_form() { 
    	check_ajax_referer('coderockz_woo_delivery_nonce');
    	$year_array = [];
    	$opendays_array = [];
    	parse_str( $_POST[ 'dateFormData' ], $date_form_data );
    	foreach($date_form_data as $key => $value) {
		    if (strpos($key, 'coderockz_woo_delivery_delivery_opendays_year_') === 0) {
		        array_push($year_array,sanitize_text_field($value));
		    }
		}
		foreach($year_array as $year) {
			$opendays_months = $this->helper->coderockz_woo_delivery_array_sanitize($date_form_data["coderockz_woo_delivery_delivery_opendays_month_".$year]);
			if(!empty($opendays_months)){
				foreach($opendays_months as $opendays_month) {
					if($opendays_month != "") {
						$opendays_days = sanitize_text_field($date_form_data["coderockz_woo_delivery_delivery_opendays_dates_".$opendays_month."_".$year]);
						if(isset($opendays_days) && $opendays_days != "") {
							$formated_opendays = [];
							$opendays_days = explode(',', $opendays_days);
							foreach($opendays_days as $opendays_day) {
								$formated_opendays[] = sprintf("%02d", $opendays_day);
							}
							$formated_opendays = implode(',', $formated_opendays);
							$opendays_array[$year][$opendays_month] = $formated_opendays;
						}	
					}
				}
			}
			
		}
		$date_form_settings['open_days'] = $opendays_array;
		if(get_option('coderockz_woo_delivery_date_settings') == false) {
			update_option('coderockz_woo_delivery_date_settings', $date_form_settings);
		} else {
			$date_form_settings = array_merge(get_option('coderockz_woo_delivery_date_settings'),$date_form_settings);
			update_option('coderockz_woo_delivery_date_settings', $date_form_settings);
		}
		wp_send_json_success();
		
    }

    public function coderockz_woo_delivery_process_pickup_date_form() { 
    	check_ajax_referer('coderockz_woo_delivery_nonce');
		
		$date_form_settings = [];

		parse_str( $_POST[ 'dateFormData' ], $date_form_data );

		$enable_pickup_date = !isset($date_form_data['coderockz_enable_pickup_date']) ? false : true;
		
		$pickup_date_mandatory = !isset($date_form_data['coderockz_pickup_date_mandatory']) ? false : true;

		$pickup_date_field_label = sanitize_text_field($date_form_data['coderockz_pickup_date_field_label']);
		$pickup_date_field_placeholder = sanitize_text_field($date_form_data['coderockz_delivery_pickup_field_placeholder']);
		
		$pickup_date_selectable_date = sanitize_text_field($date_form_data['coderockz_pickup_date_selectable_date']);

		$maximum_pickup_per_day = sanitize_text_field($date_form_data['coderockz_delivery_date_maximum_pickup_per_day']);
		
		$pickup_date_format = sanitize_text_field($date_form_data['coderockz_pickup_date_format']);

		$pickup_date_calendar_locale = sanitize_text_field($date_form_data['coderockz_pickup_date_calendar_locale']);


		$pickup_week_starts_from = sanitize_text_field($date_form_data['coderockz_pickup_date_week_starts_from']);
		
		$pickup_date_delivery_days="";
		if(isset($date_form_data['coderockz_pickup_date_delivery_days'])) {
			$delivery_days = $this->helper->coderockz_woo_delivery_array_sanitize($date_form_data['coderockz_pickup_date_delivery_days']);
			$pickup_date_delivery_days = implode(',', $delivery_days);
		}

		$same_day_pickup = !isset($date_form_data['coderockz_disable_same_day_pickup']) ? false : true;
		$auto_select_first_pickup_date = !isset($date_form_data['coderockz_auto_select_first_pickup_date']) ? false : true;
		

		$date_form_settings['enable_pickup_date'] = $enable_pickup_date;
		$date_form_settings['pickup_date_mandatory'] = $pickup_date_mandatory;
		$date_form_settings['pickup_field_label'] = $pickup_date_field_label;
		$date_form_settings['pickup_field_placeholder'] = $pickup_date_field_placeholder;
		$date_form_settings['selectable_date'] = $pickup_date_selectable_date;
		$date_form_settings['maximum_pickup_per_day'] = $maximum_pickup_per_day;
		$date_form_settings['date_format'] = $pickup_date_format;
		$date_form_settings['pickup_days'] = $pickup_date_delivery_days;
		$date_form_settings['calendar_locale'] = $pickup_date_calendar_locale;
		$date_form_settings['week_starts_from'] = $pickup_week_starts_from;
		$date_form_settings['disable_same_day_pickup'] = $same_day_pickup;
		$date_form_settings['auto_select_first_pickup_date'] = $auto_select_first_pickup_date;
		
		if(get_option('coderockz_woo_delivery_pickup_date_settings') == false) {
			update_option('coderockz_woo_delivery_pickup_date_settings', $date_form_settings);
		} else {
			$date_form_settings = array_merge(get_option('coderockz_woo_delivery_pickup_date_settings'),$date_form_settings);
			update_option('coderockz_woo_delivery_pickup_date_settings', $date_form_settings);
		}

		$pickup_date_form_settings = [];
		$calendar_theme = sanitize_text_field($date_form_data['coderockz_woo_delivery_pickup_calendar_theme']);

		$pickup_date_form_settings['calendar_theme'] = $calendar_theme;

		if(get_option('coderockz_woo_delivery_date_settings') == false) {
			update_option('coderockz_woo_delivery_date_settings', $pickup_date_form_settings);
		} else {
			$pickup_date_form_settings = array_merge(get_option('coderockz_woo_delivery_date_settings'),$pickup_date_form_settings);
			update_option('coderockz_woo_delivery_date_settings', $pickup_date_form_settings);
		}

		wp_send_json_success();
		
    }


    public function coderockz_woo_delivery_process_delivery_date_pickup_opendays_form() { 
    	check_ajax_referer('coderockz_woo_delivery_nonce');
    	$year_array = [];
    	$opendays_array = [];
    	parse_str( $_POST[ 'dateFormData' ], $date_form_data );
    	foreach($date_form_data as $key => $value) {
		    if (strpos($key, 'coderockz_woo_delivery_pickup_opendays_year_') === 0) {
		        array_push($year_array,sanitize_text_field($value));
		    }
		}
		foreach($year_array as $year) {
			$opendays_months = $this->helper->coderockz_woo_delivery_array_sanitize($date_form_data["coderockz_woo_delivery_pickup_opendays_month_".$year]);
			if(!empty($opendays_months)){
				foreach($opendays_months as $opendays_month) {
					if($opendays_month != "") {
						$opendays_days = sanitize_text_field($date_form_data["coderockz_woo_delivery_pickup_opendays_dates_".$opendays_month."_".$year]);
						if(isset($opendays_days) && $opendays_days != "") {
							$formated_opendays = [];
							$opendays_days = explode(',', $opendays_days);
							foreach($opendays_days as $opendays_day) {
								$formated_opendays[] = sprintf("%02d", $opendays_day);
							}
							$formated_opendays = implode(',', $formated_opendays);
							$opendays_array[$year][$opendays_month] = $formated_opendays;
						}	
					}
				}
			}
			
		}
		$date_form_settings['open_days'] = $opendays_array;
		if(get_option('coderockz_woo_delivery_pickup_date_settings') == false) {
			update_option('coderockz_woo_delivery_pickup_date_settings', $date_form_settings);
		} else {
			$date_form_settings = array_merge(get_option('coderockz_woo_delivery_pickup_date_settings'),$date_form_settings);
			update_option('coderockz_woo_delivery_pickup_date_settings', $date_form_settings);
		}
		wp_send_json_success();
		
    }

    public function coderockz_woo_delivery_process_delivery_date_offdays_form() { 
    	check_ajax_referer('coderockz_woo_delivery_nonce');
    	$year_array = [];
    	$offdays_array = [];
    	parse_str( $_POST[ 'dateFormData' ], $date_form_data );
    	foreach($date_form_data as $key => $value) {
		    if (strpos($key, 'coderockz_woo_delivery_offdays_year_') === 0) {
		        array_push($year_array,sanitize_text_field($value));
		    }
		}
		foreach($year_array as $year) {
			$offdays_months = $this->helper->coderockz_woo_delivery_array_sanitize($date_form_data["coderockz_woo_delivery_offdays_month_".$year]);
			if(!empty($offdays_months)){
				foreach($offdays_months as $offdays_month) {
					if($offdays_month != "") {
						$offdays_days = sanitize_text_field($date_form_data["coderockz_woo_delivery_offdays_dates_".$offdays_month."_".$year]);
						if(isset($offdays_days) && $offdays_days != "") {
							$formated_offdays = [];
							$offdays_days = explode(',', $offdays_days);
							foreach($offdays_days as $offdays_day) {
								$formated_offdays[] = sprintf("%02d", $offdays_day);
							}
							$formated_offdays = implode(',', $formated_offdays);
							$offdays_array[$year][$offdays_month] = $formated_offdays;
						}	
					}
				}
			}
			
		}
		$date_form_settings['off_days'] = $offdays_array;
		if(get_option('coderockz_woo_delivery_date_settings') == false) {
			update_option('coderockz_woo_delivery_date_settings', $date_form_settings);
		} else {
			$date_form_settings = array_merge(get_option('coderockz_woo_delivery_date_settings'),$date_form_settings);
			update_option('coderockz_woo_delivery_date_settings', $date_form_settings);
		}
		wp_send_json_success();
		
    }

    public function coderockz_woo_delivery_category_wise_offdays_form() { 
    	check_ajax_referer('coderockz_woo_delivery_nonce');
		parse_str( $_POST[ 'dateFormData' ], $category_offdays_form_data );
		$category_offdays = [];
		$category_offdays_categorys = $this->helper->coderockz_woo_delivery_array_sanitize($category_offdays_form_data['coderockz_delivery_category_wise_offdays_category']);
		foreach($category_offdays_categorys as $category_offdays_category) {
			$category = str_replace("--"," ", $category_offdays_category);
			$category_offdays[$category] = $this->helper->coderockz_woo_delivery_array_sanitize($category_offdays_form_data['coderockz-delivery-category-wise-offdays-category-weekday-'.$category_offdays_category.'']);
		}

		$category_offdays_form_settings['category_wise_offdays'] = $category_offdays;

		if(get_option('coderockz_woo_delivery_off_days_settings') == false) {
			update_option('coderockz_woo_delivery_off_days_settings', $category_offdays_form_settings);
		} else {
			$category_offdays_form_settings = array_merge(get_option('coderockz_woo_delivery_off_days_settings'),$category_offdays_form_settings);
			update_option('coderockz_woo_delivery_off_days_settings', $category_offdays_form_settings);
		}

		wp_send_json_success();
	}

	public function coderockz_woo_delivery_product_wise_offdays_form() { 
    	check_ajax_referer('coderockz_woo_delivery_nonce');
		parse_str( $_POST[ 'dateFormData' ], $product_offdays_form_data );
		$product_offdays = [];
		$product_offdays_products = $this->helper->coderockz_woo_delivery_array_sanitize($product_offdays_form_data['coderockz_delivery_product_wise_offdays_product']);
		foreach($product_offdays_products as $product_offdays_product) {
			$product_offdays[$product_offdays_product] = $this->helper->coderockz_woo_delivery_array_sanitize($product_offdays_form_data['coderockz-delivery-product-wise-offdays-product-weekday-'.$product_offdays_product.'']);
		}

		$product_offdays_form_settings['product_wise_offdays'] = $product_offdays;

		if(get_option('coderockz_woo_delivery_off_days_settings') == false) {
			update_option('coderockz_woo_delivery_off_days_settings', $product_offdays_form_settings);
		} else {
			$product_offdays_form_settings = array_merge(get_option('coderockz_woo_delivery_off_days_settings'),$product_offdays_form_settings);
			update_option('coderockz_woo_delivery_off_days_settings', $product_offdays_form_settings);
		}

		wp_send_json_success();
	}

    public function coderockz_woo_delivery_zone_wise_offdays_form() { 
    	check_ajax_referer('coderockz_woo_delivery_nonce');
		parse_str( $_POST[ 'dateFormData' ], $zone_offdays_form_data );
		$zone_offdays = [];
		$zone_offdays_zones = $this->helper->coderockz_woo_delivery_array_sanitize($zone_offdays_form_data['coderockz_delivery_zone_wise_offdays_zone']);
		foreach($zone_offdays_zones as $zone_offdays_zone) {
			$zone_code = str_replace("--"," ", $zone_offdays_zone);
			$weekday_array = $this->helper->coderockz_woo_delivery_array_sanitize($zone_offdays_form_data['coderockz-delivery-zone-wise-offdays-zone-weekday-'.$zone_offdays_zone.'']);
			if(!empty($weekday_array) && $zone_code != "") {
				$weekday_string = implode(",",$weekday_array);
				$zone_offdays[$zone_code]['off_days'] = $weekday_string;

				$zone_state_code = [];
				$zone_regions = [];
				$zone_post_code = [];
				$zone = new WC_Shipping_Zone($zone_code);

				$zone_string = $zone->get_formatted_location(50000);
				if(isset($zone_string) && $zone_string != ''){
					$zone_array = explode(", ",$zone_string);
				}
				$zone_locations = $zone->get_zone_locations();
				$zone_locations = $this->helper->objectToArray($zone_locations);
				foreach($zone_locations as $zone_location) {
					if($zone_location['type'] == "state") {
						$position = strpos($zone_location['code'],':');
						$zone_state_code[] = substr($zone_location['code'],($position+1));
					} else if($zone_location['type'] == "postcode") {
						$zone_post_code[] = $zone_location['code'];
					}
				}

				foreach($zone_state_code as $key => $code) {
					$zone_regions[$code] = $zone_array[$key];
				}


				$region_state_code = $zone_regions;
				$region_post_code = $zone_post_code;

				$zone_offdays[$zone_code]['state'] = $region_state_code;
				$zone_offdays[$zone_code]['postcode'] = $region_post_code;
			}

		}

		$zone_offdays_form_settings['zone_wise_offdays'] = $zone_offdays;

		if(get_option('coderockz_woo_delivery_off_days_settings') == false) {
			update_option('coderockz_woo_delivery_off_days_settings', $zone_offdays_form_settings);
		} else {
			$zone_offdays_form_settings = array_merge(get_option('coderockz_woo_delivery_off_days_settings'),$zone_offdays_form_settings);
			update_option('coderockz_woo_delivery_off_days_settings', $zone_offdays_form_settings);
		}

		wp_send_json_success($zone_offdays);
	}

    public function coderockz_woo_delivery_state_wise_offdays_form() { 
    	check_ajax_referer('coderockz_woo_delivery_nonce');
		parse_str( $_POST[ 'dateFormData' ], $state_offdays_form_data );
		$state_offdays = [];
		$state_offdays_states = $this->helper->coderockz_woo_delivery_array_sanitize($state_offdays_form_data['coderockz_delivery_state_wise_offdays_state']);
		foreach($state_offdays_states as $state_offdays_state) {
			$state = str_replace("--"," ", $state_offdays_state);
			if(!empty($state_offdays_form_data['coderockz-delivery-state-wise-offdays-state-weekday-'.$state_offdays_state.'']) && $state != "") {
				$state_offdays[$state] = $this->helper->coderockz_woo_delivery_array_sanitize($state_offdays_form_data['coderockz-delivery-state-wise-offdays-state-weekday-'.$state_offdays_state.'']);
			}

			/*$state_offdays[$state] = $this->helper->coderockz_woo_delivery_array_sanitize($state_offdays_form_data['coderockz-delivery-state-wise-offdays-state-weekday-'.$state_offdays_state.'']);*/
			
		}

		$state_offdays_form_settings['state_wise_offdays'] = $state_offdays;

		if(get_option('coderockz_woo_delivery_off_days_settings') == false) {
			update_option('coderockz_woo_delivery_off_days_settings', $state_offdays_form_settings);
		} else {
			$state_offdays_form_settings = array_merge(get_option('coderockz_woo_delivery_off_days_settings'),$state_offdays_form_settings);
			update_option('coderockz_woo_delivery_off_days_settings', $state_offdays_form_settings);
		}

		wp_send_json_success();
	}

	public function coderockz_woo_delivery_postcode_wise_offdays_form() { 
    	check_ajax_referer('coderockz_woo_delivery_nonce');
		parse_str( $_POST[ 'dateFormData' ], $postcode_offdays_form_data );
		$postcode_offdays = [];
		$postcode_offdays_postcodes = $this->helper->coderockz_woo_delivery_array_sanitize($postcode_offdays_form_data['coderockz_delivery_postcode_wise_offdays_postcode']);
		foreach($postcode_offdays_postcodes as $postcode_offdays_postcode) {
			$postcode = str_replace(array("--","___"),array(" ","..."), $postcode_offdays_postcode);
			if(!empty($postcode_offdays_form_data['coderockz-delivery-postcode-wise-offdays-postcode-weekday-'.$postcode_offdays_postcode.'']) && $postcode != "") {
				$postcode_offdays[$postcode] = $this->helper->coderockz_woo_delivery_array_sanitize($postcode_offdays_form_data['coderockz-delivery-postcode-wise-offdays-postcode-weekday-'.$postcode_offdays_postcode.'']);
			}
			
		}

		$postcode_offdays_form_settings['postcode_wise_offdays'] = $postcode_offdays;

		if(get_option('coderockz_woo_delivery_off_days_settings') == false) {
			update_option('coderockz_woo_delivery_off_days_settings', $postcode_offdays_form_settings);
		} else {
			$postcode_offdays_form_settings = array_merge(get_option('coderockz_woo_delivery_off_days_settings'),$postcode_offdays_form_settings);
			update_option('coderockz_woo_delivery_off_days_settings', $postcode_offdays_form_settings);
		}

		wp_send_json_success();
	}

    public function coderockz_woo_delivery_process_store_closing_form() { 
    	check_ajax_referer('coderockz_woo_delivery_nonce');
		parse_str( $_POST[ 'formData' ], $closing_time_form_data );
		$time_form_settings = [];
		$enable_closing_time = !isset($closing_time_form_data['coderockz_woo_delivery_enable_closing_time']) ? false : true;

		$store_closing_hour = (isset($closing_time_form_data['coderockz_woo_delivery_closing_time_hour']) && $closing_time_form_data['coderockz_woo_delivery_closing_time_hour'] !="") ? sanitize_text_field($closing_time_form_data['coderockz_woo_delivery_closing_time_hour']) : "0";
		
		$store_closing_min = (isset($closing_time_form_data['coderockz_woo_delivery_closing_time_min']) && $closing_time_form_data['coderockz_woo_delivery_closing_time_min'] !="") ? sanitize_text_field($closing_time_form_data['coderockz_woo_delivery_closing_time_min']) : "0"; 

		$store_closing_format = sanitize_text_field($closing_time_form_data['coderockz_woo_delivery_closing_time_format']);
		if($store_closing_format == "am") {
			$store_closing_hour_12 = ($store_closing_hour == "12") ? "0" : $store_closing_hour;
			$store_closing_time = ((int)$store_closing_hour_12 * 60) + (int)$store_closing_min;
		} else {
			$store_closing_hour = ($store_closing_hour == "12") ? "0" : $store_closing_hour;
			$store_closing_time = (((int)$store_closing_hour + 12)*60) + (int)$store_closing_min;
		}

		if($store_closing_format == "am" && $store_closing_hour == "12" && ($store_closing_min =="0"||$store_closing_min =="00")) {
			$store_closing_time = 1440;
		}

		$extended_closing_days = (isset($closing_time_form_data['coderockz_woo_delivery_extend_closing_time']) && $closing_time_form_data['coderockz_woo_delivery_extend_closing_time'] !="") ? sanitize_text_field($closing_time_form_data['coderockz_woo_delivery_extend_closing_time']) : "0";

		$time_form_settings['enable_closing_time'] = $enable_closing_time;
		$time_form_settings['store_closing_time'] = (string)$store_closing_time;
		$time_form_settings['extended_closing_days'] = $extended_closing_days;


		if(get_option('coderockz_woo_delivery_time_settings') == false) {
			update_option('coderockz_woo_delivery_time_settings', $time_form_settings);
		} else {
			$time_form_settings = array_merge(get_option('coderockz_woo_delivery_time_settings'),$time_form_settings);
			update_option('coderockz_woo_delivery_time_settings', $time_form_settings);
		}

		wp_send_json_success();
	}

	public function coderockz_woo_delivery_process_different_store_closing_form() { 
    	check_ajax_referer('coderockz_woo_delivery_nonce');
		parse_str( $_POST[ 'formData' ], $closing_time_form_data );
		$time_form_settings = [];

		$enable_different_closing_time = !isset($closing_time_form_data['coderockz_woo_delivery_enable_different_closing_time']) ? false : true;
		$different_store_closing_time = [];
		$different_extended_closing_day = [];

		$weekday = array("0"=>"Sunday", "1"=>"Monday", "2"=>"Tuesday", "3"=>"Wednesday", "4"=>"Thursday", "5"=>"Friday", "6"=>"Saturday");
        foreach ($weekday as $key => $value) {

        	$different_extended_closing_day[$key] = (isset($closing_time_form_data['coderockz_woo_delivery_extend_closing_time_'.$key]) && $closing_time_form_data['coderockz_woo_delivery_extend_closing_time_'.$key] !="") ? sanitize_text_field($closing_time_form_data['coderockz_woo_delivery_extend_closing_time_'.$key]) : "0";


        	$store_closing_hour_[$key] = (isset($closing_time_form_data['coderockz_woo_delivery_closing_time_hour_'.$key]) && $closing_time_form_data['coderockz_woo_delivery_closing_time_hour_'.$key] !="") ? sanitize_text_field($closing_time_form_data['coderockz_woo_delivery_closing_time_hour_'.$key]) : "0";
	
			$store_closing_min_[$key] = (isset($closing_time_form_data['coderockz_woo_delivery_closing_time_min_'.$key]) && $closing_time_form_data['coderockz_woo_delivery_closing_time_min_'.$key] !="") ? sanitize_text_field($closing_time_form_data['coderockz_woo_delivery_closing_time_min_'.$key]) : "0"; 

			$store_closing_format_[$key] = sanitize_text_field($closing_time_form_data['coderockz_woo_delivery_closing_time_format_'.$key]);
			if($store_closing_format_[$key] == "am") {
				$store_closing_hour_12_[$key] = ($store_closing_hour_[$key] == "12") ? "0" : $store_closing_hour_[$key];
				$store_closing_time_[$key] = ((int)$store_closing_hour_12_[$key] * 60) + (int)$store_closing_min_[$key];
			} else {
				$store_closing_hour_[$key] = ($store_closing_hour_[$key] == "12") ? "0" : $store_closing_hour_[$key];
				$store_closing_time_[$key] = (((int)$store_closing_hour_[$key] + 12)*60) + (int)$store_closing_min_[$key];
			}

			if($store_closing_format_[$key] == "am" && $store_closing_hour_[$key] == "12" && ($store_closing_min_[$key] =="0"||$store_closing_min_[$key] =="00")) {
				$store_closing_time_[$key] = 1440;
			}

			$different_store_closing_time[$key] = $store_closing_time_[$key];
        }

		$time_form_settings['enable_different_closing_time'] = $enable_different_closing_time;
		$time_form_settings['different_store_closing_time'] = $different_store_closing_time;
		$time_form_settings['different_extended_closing_day'] = $different_extended_closing_day;

		if(get_option('coderockz_woo_delivery_time_settings') == false) {
			update_option('coderockz_woo_delivery_time_settings', $time_form_settings);
		} else {
			$time_form_settings = array_merge(get_option('coderockz_woo_delivery_time_settings'),$time_form_settings);
			update_option('coderockz_woo_delivery_time_settings', $time_form_settings);
		}

		wp_send_json_success();
	}

    public function coderockz_woo_delivery_process_delivery_time_form() { 
    	check_ajax_referer('coderockz_woo_delivery_nonce');
		parse_str( $_POST[ 'dateFormData' ], $date_form_data );
		$time_form_settings = [];
		$enable_delivery_time = !isset($date_form_data['coderockz_enable_delivery_time']) ? false : true;
		$delivery_time_mandatory = !isset($date_form_data['coderockz_delivery_time_mandatory']) ? false : true;
		$delivery_time_field_label = sanitize_text_field($date_form_data['coderockz_delivery_time_field_label']);
		$delivery_time_field_placeholder = sanitize_text_field($date_form_data['coderockz_delivery_time_field_placeholder']);
		$disable_current_time_slot = !isset($date_form_data['coderockz_delivery_time_disable_current_time_slot']) ? false : true;
		$delivery_time_format = sanitize_text_field($date_form_data['coderockz_delivery_time_format']);
		$delivery_time_maximum_order = sanitize_text_field($date_form_data['coderockz_delivery_time_maximum_order']);
		$auto_select_first_time = !isset($date_form_data['coderockz_auto_select_first_time']) ? false : true;
		$enable_as_soon_as_possible_option = !isset($date_form_data['coderockz_woo_delivry_as_soon_as_possible_option']) ? false : true;
		$as_soon_as_possible_text = sanitize_text_field($date_form_data['coderockz_woo_delivery_as_soon_as_possible_text']);
		$search_box_hidden = !isset($date_form_data['coderockz_hide_searchbox_time_field_dropdown']) ? false : true;
		$delivery_time_slot_starts_hour = (isset($date_form_data['coderockz_delivery_time_slot_starts_hour']) && $date_form_data['coderockz_delivery_time_slot_starts_hour'] !="") ? sanitize_text_field($date_form_data['coderockz_delivery_time_slot_starts_hour']) : "0";
		
		$delivery_time_slot_starts_min = (isset($date_form_data['coderockz_delivery_time_slot_starts_min']) && $date_form_data['coderockz_delivery_time_slot_starts_min'] !="") ? sanitize_text_field($date_form_data['coderockz_delivery_time_slot_starts_min']) : "0"; 

		$delivery_time_slot_starts_format = sanitize_text_field($date_form_data['coderockz_delivery_time_slot_starts_format']);
		if($delivery_time_slot_starts_format == "am") {
			$delivery_time_slot_starts_hour = ($delivery_time_slot_starts_hour == "12") ? "0" : $delivery_time_slot_starts_hour;
			$delivery_time_slot_starts = ((int)$delivery_time_slot_starts_hour * 60) + (int)$delivery_time_slot_starts_min;
		} else {
			$delivery_time_slot_starts_hour = ($delivery_time_slot_starts_hour == "12") ? "0" : $delivery_time_slot_starts_hour;
			$delivery_time_slot_starts = (((int)$delivery_time_slot_starts_hour + 12)*60) + (int)$delivery_time_slot_starts_min;
		}

		$delivery_time_slot_ends_hour = (isset($date_form_data['coderockz_delivery_time_slot_ends_hour']) && $date_form_data['coderockz_delivery_time_slot_ends_hour'] !="") ? sanitize_text_field($date_form_data['coderockz_delivery_time_slot_ends_hour']) : "0";
		
		$delivery_time_slot_ends_min = (isset($date_form_data['coderockz_delivery_time_slot_ends_min']) && $date_form_data['coderockz_delivery_time_slot_ends_min'] !="") ? sanitize_text_field($date_form_data['coderockz_delivery_time_slot_ends_min']) : "0"; 

		$delivery_time_slot_ends_format = sanitize_text_field($date_form_data['coderockz_delivery_time_slot_ends_format']);

		if($delivery_time_slot_ends_format == "am") {
			$delivery_time_slot_ends_hour_12 = ($delivery_time_slot_ends_hour == "12") ? "0" : $delivery_time_slot_ends_hour;
			$delivery_time_slot_ends = ((int)$delivery_time_slot_ends_hour_12 * 60) + (int)$delivery_time_slot_ends_min;
		} else {
			$delivery_time_slot_ends_hour = ($delivery_time_slot_ends_hour == "12") ? "0" : $delivery_time_slot_ends_hour;
			$delivery_time_slot_ends = (((int)$delivery_time_slot_ends_hour + 12)*60) + (int)$delivery_time_slot_ends_min;
		}

		if($delivery_time_slot_ends_format == "am" && $delivery_time_slot_ends_hour == "12" && ($delivery_time_slot_ends_min =="0"||$delivery_time_slot_ends_min =="00")) {
				$delivery_time_slot_ends = 1440;
		}

		$delivery_time_slot_duration_time = (isset($date_form_data['coderockz_delivery_time_slot_duration_time']) && $date_form_data['coderockz_delivery_time_slot_duration_time'] !="") ? sanitize_text_field($date_form_data['coderockz_delivery_time_slot_duration_time']) : "0";
		$delivery_time_slot_duration_format = sanitize_text_field($date_form_data['coderockz_delivery_time_slot_duration_format']);

		if($delivery_time_slot_duration_format == "hour") {
			$each_time_slot = (int)$delivery_time_slot_duration_time * 60;
			$each_time_slot = $each_time_slot != 0 ? $each_time_slot : "";
		} else {
			$each_time_slot = (int)$delivery_time_slot_duration_time;
			$each_time_slot = $each_time_slot != 0 ? $each_time_slot : "";
		}

		$time_form_settings['enable_delivery_time'] = $enable_delivery_time;
		$time_form_settings['delivery_time_mandatory'] = $delivery_time_mandatory;
		$time_form_settings['field_label'] = $delivery_time_field_label;
		$time_form_settings['field_placeholder'] = $delivery_time_field_placeholder;
		$time_form_settings['time_format'] = $delivery_time_format;
		$time_form_settings['delivery_time_starts'] = (string)$delivery_time_slot_starts;
		$time_form_settings['delivery_time_ends'] = (string)$delivery_time_slot_ends;
		$time_form_settings['each_time_slot'] = (string)$each_time_slot;
		$time_form_settings['max_order_per_slot'] = $delivery_time_maximum_order;
		$time_form_settings['enable_as_soon_as_possible_option'] = $enable_as_soon_as_possible_option;
		$time_form_settings['as_soon_as_possible_text'] = $as_soon_as_possible_text;
		$time_form_settings['disabled_current_time_slot'] = $disable_current_time_slot;
		$time_form_settings['auto_select_first_time'] = $auto_select_first_time;
		$time_form_settings['hide_searchbox'] = $search_box_hidden;

		if(get_option('coderockz_woo_delivery_time_settings') == false) {
			update_option('coderockz_woo_delivery_time_settings', $time_form_settings);
		} else {
			$time_form_settings = array_merge(get_option('coderockz_woo_delivery_time_settings'),$time_form_settings);
			update_option('coderockz_woo_delivery_time_settings', $time_form_settings);
		}

		wp_send_json_success();
	}

	public function coderockz_woo_delivery_process_pickup_time_form() { 
    	check_ajax_referer('coderockz_woo_delivery_nonce');
		parse_str( $_POST[ 'dateFormData' ], $pickup_form_data );
		$pickup_time_form_settings = [];
		$enable_pickup_time = !isset($pickup_form_data['coderockz_enable_pickup_time']) ? false : true;
		$pickup_time_mandatory = !isset($pickup_form_data['coderockz_pickup_time_mandatory']) ? false : true;
		$pickup_time_field_label = sanitize_text_field($pickup_form_data['coderockz_pickup_time_field_label']);
		$pickup_time_field_placeholder = sanitize_text_field($pickup_form_data['coderockz_pickup_time_field_placeholder']);
		$disable_current_time_slot = !isset($pickup_form_data['coderockz_pickup_time_disable_current_time_slot']) ? false : true;
		$pickup_time_format = sanitize_text_field($pickup_form_data['coderockz_pickup_time_format']);
		$pickup_time_maximum_order = sanitize_text_field($pickup_form_data['coderockz_pickup_time_maximum_order']);
		$auto_select_first_time = !isset($pickup_form_data['coderockz_auto_select_first_pickup_time']) ? false : true;
		$search_box_hidden = !isset($pickup_form_data['coderockz_hide_searchbox_pickup_field_dropdown']) ? false : true;
		$pickup_time_slot_starts_hour = (isset($pickup_form_data['coderockz_pickup_time_slot_starts_hour']) && $pickup_form_data['coderockz_pickup_time_slot_starts_hour'] !="") ? sanitize_text_field($pickup_form_data['coderockz_pickup_time_slot_starts_hour']) : "0";
		
		$pickup_time_slot_starts_min = (isset($pickup_form_data['coderockz_pickup_time_slot_starts_min']) && $pickup_form_data['coderockz_pickup_time_slot_starts_min'] !="") ? sanitize_text_field($pickup_form_data['coderockz_pickup_time_slot_starts_min']) : "0"; 

		$pickup_time_slot_starts_format = sanitize_text_field($pickup_form_data['coderockz_pickup_time_slot_starts_format']);
		if($pickup_time_slot_starts_format == "am") {
			$pickup_time_slot_starts_hour = ($pickup_time_slot_starts_hour == "12") ? "0" : $pickup_time_slot_starts_hour;
			$pickup_time_slot_starts = ((int)$pickup_time_slot_starts_hour * 60) + (int)$pickup_time_slot_starts_min;
		} else {
			$pickup_time_slot_starts_hour = ($pickup_time_slot_starts_hour == "12") ? "0" : $pickup_time_slot_starts_hour;
			$pickup_time_slot_starts = (((int)$pickup_time_slot_starts_hour + 12)*60) + (int)$pickup_time_slot_starts_min;
		}

		$pickup_time_slot_ends_hour = (isset($pickup_form_data['coderockz_pickup_time_slot_ends_hour']) && $pickup_form_data['coderockz_pickup_time_slot_ends_hour'] !="") ? sanitize_text_field($pickup_form_data['coderockz_pickup_time_slot_ends_hour']) : "0";
		
		$pickup_time_slot_ends_min = (isset($pickup_form_data['coderockz_pickup_time_slot_ends_min']) && $pickup_form_data['coderockz_pickup_time_slot_ends_min'] !="") ? sanitize_text_field($pickup_form_data['coderockz_pickup_time_slot_ends_min']) : "0"; 

		$pickup_time_slot_ends_format = sanitize_text_field($pickup_form_data['coderockz_pickup_time_slot_ends_format']);

		if($pickup_time_slot_ends_format == "am") {
			$pickup_time_slot_ends_hour_12 = ($pickup_time_slot_ends_hour == "12") ? "0" : $pickup_time_slot_ends_hour;
			$pickup_time_slot_ends = ((int)$pickup_time_slot_ends_hour_12 * 60) + (int)$pickup_time_slot_ends_min;
		} else {
			$pickup_time_slot_ends_hour = ($pickup_time_slot_ends_hour == "12") ? "0" : $pickup_time_slot_ends_hour;
			$pickup_time_slot_ends = (((int)$pickup_time_slot_ends_hour + 12)*60) + (int)$pickup_time_slot_ends_min;
		}

		if($pickup_time_slot_ends_format == "am" && $pickup_time_slot_ends_hour == "12" && ($pickup_time_slot_ends_min =="0"||$pickup_time_slot_ends_min =="00")) {
				$pickup_time_slot_ends = 1440;
		}

		$pickup_time_slot_duration_time = (isset($pickup_form_data['coderockz_pickup_time_slot_duration_time']) && $pickup_form_data['coderockz_pickup_time_slot_duration_time'] !="") ? sanitize_text_field($pickup_form_data['coderockz_pickup_time_slot_duration_time']) : "0";
		$pickup_time_slot_duration_format = sanitize_text_field($pickup_form_data['coderockz_pickup_time_slot_duration_format']);

		if($pickup_time_slot_duration_format == "hour") {
			$each_time_slot = (int)$pickup_time_slot_duration_time * 60;
			$each_time_slot = $each_time_slot != 0 ? $each_time_slot : "";
		} else {
			$each_time_slot = (int)$pickup_time_slot_duration_time;
			$each_time_slot = $each_time_slot != 0 ? $each_time_slot : "";
		}

		$pickup_time_form_settings['enable_pickup_time'] = $enable_pickup_time;
		$pickup_time_form_settings['pickup_time_mandatory'] = $pickup_time_mandatory;
		$pickup_time_form_settings['field_label'] = $pickup_time_field_label;
		$pickup_time_form_settings['field_placeholder'] = $pickup_time_field_placeholder;
		$pickup_time_form_settings['time_format'] = $pickup_time_format;
		$pickup_time_form_settings['pickup_time_starts'] = (string)$pickup_time_slot_starts;
		$pickup_time_form_settings['pickup_time_ends'] = (string)$pickup_time_slot_ends;
		$pickup_time_form_settings['each_time_slot'] = (string)$each_time_slot;
		$pickup_time_form_settings['max_pickup_per_slot'] = $pickup_time_maximum_order;
		$pickup_time_form_settings['disabled_current_pickup_time_slot'] = $disable_current_time_slot;
		$pickup_time_form_settings['auto_select_first_time'] = $auto_select_first_time;
		$pickup_time_form_settings['hide_searchbox'] = $search_box_hidden;

		if(get_option('coderockz_woo_delivery_pickup_settings') == false) {
			update_option('coderockz_woo_delivery_pickup_settings', $pickup_time_form_settings);
		} else {
			$pickup_time_form_settings = array_merge(get_option('coderockz_woo_delivery_pickup_settings'),$pickup_time_form_settings);
			update_option('coderockz_woo_delivery_pickup_settings', $pickup_time_form_settings);
		}

		wp_send_json_success();
	}

	public function coderockz_woo_delivery_process_custom_time_slot_settings() { 
    	check_ajax_referer('coderockz_woo_delivery_nonce');
		parse_str( $_POST[ 'dateFormData' ], $custom_time_form_data );
		$custom_time_form_settings = [];
		$enable_custom_time = !isset($custom_time_form_data['coderockz_woo_delivery_enable_custom_time_slot']) ? false : true;
		
		$custom_time_form_settings['enable_custom_time_slot'] = $enable_custom_time;
		
		if(get_option('coderockz_woo_delivery_time_slot_settings') == false) {
			update_option('coderockz_woo_delivery_time_slot_settings', $custom_time_form_settings);
		} else {
			$custom_time_form_settings = array_merge(get_option('coderockz_woo_delivery_time_slot_settings'),$custom_time_form_settings);
			update_option('coderockz_woo_delivery_time_slot_settings', $custom_time_form_settings);
		}

		wp_send_json_success();
	}

	public function coderockz_woo_delivery_delete_custom_time_slot() { 
    	check_ajax_referer('coderockz_woo_delivery_nonce');
		$time_slot = [];
		$custom_time_form_settings = [];
		
		$custom_time_slot_starts_hour = (isset($_POST['startHour']) && $_POST['startHour'] !="") ? sanitize_text_field($_POST['startHour']) : "0";
		
		$custom_time_slot_starts_min = (isset($_POST['startMin']) && $_POST['startMin'] !="") ? sanitize_text_field($_POST['startMin']) : "0";


		$custom_time_slot_starts_format = sanitize_text_field($_POST['startFormat']);
		if($custom_time_slot_starts_format == "am") {
			$custom_time_slot_starts_hour = ($custom_time_slot_starts_hour == "12") ? "0" : $custom_time_slot_starts_hour;
			$custom_time_slot_starts = ((int)$custom_time_slot_starts_hour * 60) + (int)$custom_time_slot_starts_min;
		} else {
			$custom_time_slot_starts_hour = ($custom_time_slot_starts_hour == "12") ? "0" : $custom_time_slot_starts_hour;
			$custom_time_slot_starts = (((int)$custom_time_slot_starts_hour + 12)*60) + (int)$custom_time_slot_starts_min;
		}

		$custom_time_slot_ends_hour = (isset($_POST['endHour']) && $_POST['endHour'] !="") ? sanitize_text_field($_POST['endHour']) : "0";
		
		$custom_time_slot_ends_min = (isset($_POST['endMin']) && $_POST['endMin'] !="") ? sanitize_text_field($_POST['endMin']) : "0"; 

		$custom_time_slot_ends_format = sanitize_text_field($_POST['endFormat']);

		if($custom_time_slot_ends_format == "am") {
			$custom_time_slot_ends_hour_12 = ($custom_time_slot_ends_hour == "12") ? "0" : $custom_time_slot_ends_hour;
			$custom_time_slot_ends = ((int)$custom_time_slot_ends_hour_12 * 60) + (int)$custom_time_slot_ends_min;
		} else {
			$custom_time_slot_ends_hour = ($custom_time_slot_ends_hour == "12") ? "0" : $custom_time_slot_ends_hour;
			$custom_time_slot_ends = (((int)$custom_time_slot_ends_hour + 12)*60) + (int)$custom_time_slot_ends_min;
		}


		if($custom_time_slot_ends_format == "am" && $custom_time_slot_ends_hour == "12" && ($custom_time_slot_ends_min =="0"||$custom_time_slot_ends_min =="00")) {
			$custom_time_slot_ends = 1440;
		}

	
		$db_custom_time_slot = get_option('coderockz_woo_delivery_time_slot_settings')['time_slot'];
		if(array_key_exists($custom_time_slot_starts.'-'.$custom_time_slot_ends,$db_custom_time_slot)) {
			unset($db_custom_time_slot[$custom_time_slot_starts.'-'.$custom_time_slot_ends]);
			$custom_time_form_settings['time_slot'] = $db_custom_time_slot;
		}


		$custom_time_form_settings = array_merge(get_option('coderockz_woo_delivery_time_slot_settings'),$custom_time_form_settings);
		update_option('coderockz_woo_delivery_time_slot_settings', $custom_time_form_settings);
		

		wp_send_json_success();
    }

	public function coderockz_woo_delivery_add_enable_custom_time_slot() { 
    	check_ajax_referer('coderockz_woo_delivery_nonce');
		$time_slot = [];
		$custom_time_form_settings = [];		

		$old_custom_time_slot_starts_hour = (isset($_POST['oldStartHour']) && $_POST['oldStartHour'] !="") ? sanitize_text_field($_POST['oldStartHour']) : "0";
		
		$old_custom_time_slot_starts_min = (isset($_POST['oldStartMin']) && $_POST['oldStartMin'] !="") ? sanitize_text_field($_POST['oldStartMin']) : "0";

		$old_custom_time_slot_starts_format = (isset($_POST['oldStartFormat']) && $_POST['oldStartFormat'] !="") ? sanitize_text_field($_POST['oldStartFormat']) : "";

		if($old_custom_time_slot_starts_format == "am") {
			$old_custom_time_slot_starts_hour = ($old_custom_time_slot_starts_hour == "12") ? "0" : $old_custom_time_slot_starts_hour;
			$old_custom_time_slot_starts = ((int)$old_custom_time_slot_starts_hour * 60) + (int)$old_custom_time_slot_starts_min;
		} else {
			$old_custom_time_slot_starts_hour = ($old_custom_time_slot_starts_hour == "12") ? "0" : $old_custom_time_slot_starts_hour;
			$old_custom_time_slot_starts = (((int)$old_custom_time_slot_starts_hour + 12)*60) + (int)$old_custom_time_slot_starts_min;
		}
		
		$old_custom_time_slot_ends_hour = (isset($_POST['oldEndHour']) && $_POST['oldEndHour'] !="") ? sanitize_text_field($_POST['oldEndHour']) : "0";
		
		$old_custom_time_slot_ends_min = (isset($_POST['oldEndMin']) && $_POST['oldEndMin'] !="") ? sanitize_text_field($_POST['oldEndMin']) : "0"; 

		$old_custom_time_slot_ends_format = (isset($_POST['oldEndFormat']) && $_POST['oldEndFormat'] !="") ? sanitize_text_field($_POST['oldEndFormat']) : "";

		if($old_custom_time_slot_ends_format == "am") {
			$old_custom_time_slot_ends_hour = ($old_custom_time_slot_ends_hour == "12") ? "0" : $old_custom_time_slot_ends_hour;
			$old_custom_time_slot_ends = ((int)$old_custom_time_slot_ends_hour * 60) + (int)$old_custom_time_slot_ends_min;
		} else {
			$old_custom_time_slot_ends_hour = ($old_custom_time_slot_ends_hour == "12") ? "0" : $old_custom_time_slot_ends_hour;
			$old_custom_time_slot_ends = (((int)$old_custom_time_slot_ends_hour + 12)*60) + (int)$old_custom_time_slot_ends_min;
		} 

		$custom_time_slot_starts_hour = (isset($_POST['startHour']) && $_POST['startHour'] !="") ? sanitize_text_field($_POST['startHour']) : "0";
		
		$custom_time_slot_starts_min = (isset($_POST['startMin']) && $_POST['startMin'] !="") ? sanitize_text_field($_POST['startMin']) : "0";

		$custom_time_slot_starts_format = sanitize_text_field($_POST['startFormat']);

		if($custom_time_slot_starts_format == "am") {
			$custom_time_slot_starts_hour = ($custom_time_slot_starts_hour == "12") ? "0" : $custom_time_slot_starts_hour;
			$custom_time_slot_starts = ((int)$custom_time_slot_starts_hour * 60) + (int)$custom_time_slot_starts_min;
		} else {
			$custom_time_slot_starts_hour = ($custom_time_slot_starts_hour == "12") ? "0" : $custom_time_slot_starts_hour;
			$custom_time_slot_starts = (((int)$custom_time_slot_starts_hour + 12)*60) + (int)$custom_time_slot_starts_min;
		}

		$custom_time_slot_ends_hour = (isset($_POST['endHour']) && $_POST['endHour'] !="") ? sanitize_text_field($_POST['endHour']) : "0";
		
		$custom_time_slot_ends_min = (isset($_POST['endMin']) && $_POST['endMin'] !="") ? sanitize_text_field($_POST['endMin']) : "0"; 

		$custom_time_slot_ends_format = sanitize_text_field($_POST['endFormat']);

		if($custom_time_slot_ends_format == "am") {
			$custom_time_slot_ends_hour_12 = ($custom_time_slot_ends_hour == "12") ? "0" : $custom_time_slot_ends_hour;
			$custom_time_slot_ends = ((int)$custom_time_slot_ends_hour_12 * 60) + (int)$custom_time_slot_ends_min;
		} else {
			$custom_time_slot_ends_hour = ($custom_time_slot_ends_hour == "12") ? "0" : $custom_time_slot_ends_hour;
			$custom_time_slot_ends = (((int)$custom_time_slot_ends_hour + 12)*60) + (int)$custom_time_slot_ends_min;
		}

		if($custom_time_slot_ends_format == "am" && $custom_time_slot_ends_hour == "12" && ($custom_time_slot_ends_min =="0"||$custom_time_slot_ends_min =="00")) {
			$custom_time_slot_ends = 1440;
		}

		$time_slot[$custom_time_slot_starts.'-'.$custom_time_slot_ends]['start'] = (string)$custom_time_slot_starts;
		$time_slot[$custom_time_slot_starts.'-'.$custom_time_slot_ends]['end'] = (string)$custom_time_slot_ends;

		$enable_custom_time_split = !isset($_POST['enableCustomTimeSplit']) || $_POST['enableCustomTimeSplit'] == "unchecked" ? false : true;
		$split_time_slot_duration_time = (isset($_POST['splitDurationTime']) && $_POST['splitDurationTime'] !="") ? sanitize_text_field($_POST['splitDurationTime']) : "0";
		$split_time_slot_duration_format = (isset($_POST['splitDurationFormat']) && $_POST['splitDurationFormat'] !="") ? sanitize_text_field($_POST['splitDurationFormat']) : "min";

		if($split_time_slot_duration_format == "hour") {
			$each_split_time_slot = (int)$split_time_slot_duration_time * 60;
			$each_split_time_slot = $each_split_time_slot != 0 ? $each_split_time_slot : "";
		} else {
			$each_split_time_slot = (int)$split_time_slot_duration_time;
			$each_split_time_slot = $each_split_time_slot != 0 ? $each_split_time_slot : "";
		}
		$enable_custom_splited_time_single = !isset($_POST['enableCustomSplitedTimeSingle']) || $_POST['enableCustomSplitedTimeSingle'] == "unchecked" ? false : true;
		$enable_custom_time_single = !isset($_POST['enableCustomTimeSingle']) || $_POST['enableCustomTimeSingle'] == "unchecked" ? false : true;
		$time_slot[$custom_time_slot_starts.'-'.$custom_time_slot_ends]['enable_split'] = $enable_custom_time_split;
		$time_slot[$custom_time_slot_starts.'-'.$custom_time_slot_ends]['split_slot_duration'] = $each_split_time_slot;
		$time_slot[$custom_time_slot_starts.'-'.$custom_time_slot_ends]['enable_single_splited_slot'] = $enable_custom_splited_time_single;
		$time_slot[$custom_time_slot_starts.'-'.$custom_time_slot_ends]['enable_single_slot'] = $enable_custom_time_single;

		$custom_time_maximum_order = sanitize_text_field($_POST['maxOrder']);
		$time_slot[$custom_time_slot_starts.'-'.$custom_time_slot_ends]['max_order'] = $custom_time_maximum_order;

		$custom_time_slot_fee = sanitize_text_field($_POST['slotFee']);
		$time_slot[$custom_time_slot_starts.'-'.$custom_time_slot_ends]['fee'] = $custom_time_slot_fee;

		$disable_for = (isset($_POST['disableFor']) && !empty($_POST['disableFor'])) ? $_POST['disableFor'] : array();
		
		$state_or_zip_selection = sanitize_text_field($_POST['stateOrZipSelection']);

		if($state_or_zip_selection == 'zone') {
			$region_zone_code = (isset($_POST['regionZoneCode']) && !empty($_POST['regionZoneCode'])) ? $_POST['regionZoneCode'] : array();
			foreach($region_zone_code as $zone_code) {
				$zone_state_code = [];
				$zone = new WC_Shipping_Zone($zone_code);

				$zone_locations = $zone->get_zone_locations();
				$zone_locations = $this->helper->objectToArray($zone_locations);
				foreach($zone_locations as $zone_location) {
					if($zone_location['type'] == "state") {
						$position = strpos($zone_location['code'],':');
						$zone_state_code[] = substr($zone_location['code'],($position+1));
					} else if($zone_location['type'] == "postcode") {
						$zone_post_code[] = $zone_location['code'];
					}
				}
			}

			$region_state_code = $zone_state_code;
			$region_post_code = $zone_post_code;


		} elseif($state_or_zip_selection == 'state') {
			$region_state_code = (isset($_POST['regionStateCode']) && !empty($_POST['regionStateCode'])) ? $_POST['regionStateCode'] : array();
			$region_zone_code = array();
			$region_post_code = array();

		} elseif($state_or_zip_selection == 'postcode') {
			$region_post_code = (isset($_POST['regionPostCode']) && !empty($_POST['regionPostCode'])) ? $_POST['regionPostCode'] : array();
			$region_zone_code = array();
			$region_state_code = array();
		} else {
			$region_zone_code = array();
			$region_state_code = array();
			$region_post_code = array();
		}		

		$custom_time_slot_disable = $this->helper->coderockz_woo_delivery_array_sanitize($disable_for);
		$custom_time_slot_disable_zone_code = $this->helper->coderockz_woo_delivery_array_sanitize($region_zone_code);
		$custom_time_slot_disable_state_code = $this->helper->coderockz_woo_delivery_array_sanitize($region_state_code);
		$custom_time_slot_disable_post_code = $this->helper->coderockz_woo_delivery_array_sanitize($region_post_code);

		$hide_categories = (isset($_POST['hideCategoriesArray']) && !empty($_POST['hideCategoriesArray'])) ? $_POST['hideCategoriesArray'] : array();
		$hide_products = (isset($_POST['hideProductsArray']) && !empty($_POST['hideProductsArray'])) ? $_POST['hideProductsArray'] : array();

		$hide_categories_array = $this->helper->coderockz_woo_delivery_array_sanitize($hide_categories);
		$hide_products_array = $this->helper->coderockz_woo_delivery_array_sanitize($hide_products);

		$time_slot_shown_other_categories_products = !isset($_POST['enableShownOtherCategoriesProducts']) || $_POST['enableShownOtherCategoriesProducts'] == "unchecked" ? false : true;

		$time_slot[$custom_time_slot_starts.'-'.$custom_time_slot_ends]['hide_categories'] = $hide_categories_array;
		$time_slot[$custom_time_slot_starts.'-'.$custom_time_slot_ends]['hide_products'] = $hide_products_array;
		$time_slot[$custom_time_slot_starts.'-'.$custom_time_slot_ends]['time_slot_shown_other_categories_products'] = $time_slot_shown_other_categories_products;

		$time_slot[$custom_time_slot_starts.'-'.$custom_time_slot_ends]['disable_for'] = $custom_time_slot_disable;
		$time_slot[$custom_time_slot_starts.'-'.$custom_time_slot_ends]['more_settings'] = $state_or_zip_selection;
		
		$time_slot[$custom_time_slot_starts.'-'.$custom_time_slot_ends]['disable_zone'] = $custom_time_slot_disable_zone_code;
		$time_slot[$custom_time_slot_starts.'-'.$custom_time_slot_ends]['disable_state'] = $custom_time_slot_disable_state_code;
		$time_slot[$custom_time_slot_starts.'-'.$custom_time_slot_ends]['disable_postcode'] = $custom_time_slot_disable_post_code;

		$enable_added_custom_time = !isset($_POST['enableAddedCustomTime']) || $_POST['enableAddedCustomTime'] == "unchecked" ? false : true;
		$time_slot[$custom_time_slot_starts.'-'.$custom_time_slot_ends]['enable'] = $enable_added_custom_time;
		
		if(get_option('coderockz_woo_delivery_time_slot_settings') == false) {
			$temp_time_slot = [];
			$temp_time_slot['time_slot'] = $time_slot;
			update_option('coderockz_woo_delivery_time_slot_settings', $temp_time_slot);
		} else {

			if(isset(get_option('coderockz_woo_delivery_time_slot_settings')['time_slot']) && count(get_option('coderockz_woo_delivery_time_slot_settings')['time_slot'])>0) {

				$db_custom_time_slot = get_option('coderockz_woo_delivery_time_slot_settings')['time_slot'];
				if($old_custom_time_slot_starts != $custom_time_slot_starts || $old_custom_time_slot_ends != $custom_time_slot_ends) {

					
					if(array_key_exists($old_custom_time_slot_starts.'-'.$old_custom_time_slot_ends,$db_custom_time_slot)) {
						
						unset($db_custom_time_slot[$old_custom_time_slot_starts.'-'.$old_custom_time_slot_ends]);
						
					}
				}

				$time_slot = array_merge($db_custom_time_slot,$time_slot);
				$custom_time_form_settings['time_slot'] = $time_slot;
			} else {
				$custom_time_form_settings['time_slot'] = $time_slot;
			}

			$custom_time_form_settings = array_merge(get_option('coderockz_woo_delivery_time_slot_settings'),$custom_time_form_settings);
			update_option('coderockz_woo_delivery_time_slot_settings', $custom_time_form_settings);
		}

		wp_send_json_success();
	}

	public function coderockz_woo_delivery_process_custom_pickup_slot_settings() { 
    	check_ajax_referer('coderockz_woo_delivery_nonce');
		parse_str( $_POST[ 'dateFormData' ], $custom_time_form_data );
		$custom_pickup_form_settings = [];
		$enable_custom_pickup = !isset($custom_time_form_data['coderockz_woo_delivery_enable_custom_pickup_slot']) ? false : true;
		
		$custom_pickup_form_settings['enable_custom_pickup_slot'] = $enable_custom_pickup;
		
		if(get_option('coderockz_woo_delivery_pickup_slot_settings') == false) {
			update_option('coderockz_woo_delivery_pickup_slot_settings', $custom_pickup_form_settings);
		} else {
			$custom_pickup_form_settings = array_merge(get_option('coderockz_woo_delivery_pickup_slot_settings'),$custom_pickup_form_settings);
			update_option('coderockz_woo_delivery_pickup_slot_settings', $custom_pickup_form_settings);
		}

		wp_send_json_success();
	}

	public function coderockz_woo_delivery_delete_custom_pickup_slot() { 
    	check_ajax_referer('coderockz_woo_delivery_nonce');
		$pickup_slot = [];
		$custom_pickup_form_settings = [];
		
		$custom_pickup_slot_starts_hour = (isset($_POST['pickupStartHour']) && $_POST['pickupStartHour'] !="") ? sanitize_text_field($_POST['pickupStartHour']) : "0";
		
		$custom_pickup_slot_starts_min = (isset($_POST['pickupStartMin']) && $_POST['pickupStartMin'] !="") ? sanitize_text_field($_POST['pickupStartMin']) : "0";


		$custom_pickup_slot_starts_format = sanitize_text_field($_POST['pickupStartFormat']);
		if($custom_pickup_slot_starts_format == "am") {
			$custom_pickup_slot_starts_hour = ($custom_pickup_slot_starts_hour == "12") ? "0" : $custom_pickup_slot_starts_hour;
			$custom_pickup_slot_starts = ((int)$custom_pickup_slot_starts_hour * 60) + (int)$custom_pickup_slot_starts_min;
		} else {
			$custom_pickup_slot_starts_hour = ($custom_pickup_slot_starts_hour == "12") ? "0" : $custom_pickup_slot_starts_hour;
			$custom_pickup_slot_starts = (((int)$custom_pickup_slot_starts_hour + 12)*60) + (int)$custom_pickup_slot_starts_min;
		}

		$custom_pickup_slot_ends_hour = (isset($_POST['pickupEndHour']) && $_POST['pickupEndHour'] !="") ? sanitize_text_field($_POST['pickupEndHour']) : "0";
		
		$custom_pickup_slot_ends_min = (isset($_POST['pickupEndMin']) && $_POST['pickupEndMin'] !="") ? sanitize_text_field($_POST['pickupEndMin']) : "0"; 

		$custom_pickup_slot_ends_format = sanitize_text_field($_POST['pickupEndFormat']);

		if($custom_pickup_slot_ends_format == "am") {
			$custom_pickup_slot_ends_hour_12 = ($custom_pickup_slot_ends_hour == "12") ? "0" : $custom_pickup_slot_ends_hour;
			$custom_pickup_slot_ends = ((int)$custom_pickup_slot_ends_hour_12 * 60) + (int)$custom_pickup_slot_ends_min;
		} else {
			$custom_pickup_slot_ends_hour = ($custom_pickup_slot_ends_hour == "12") ? "0" : $custom_pickup_slot_ends_hour;
			$custom_pickup_slot_ends = (((int)$custom_pickup_slot_ends_hour + 12)*60) + (int)$custom_pickup_slot_ends_min;
		}


		if($custom_pickup_slot_ends_format == "am" && $custom_pickup_slot_ends_hour == "12" && ($custom_pickup_slot_ends_min =="0"||$custom_pickup_slot_ends_min =="00")) {
			$custom_pickup_slot_ends = 1440;
		}

	
		$db_custom_pickup_slot = get_option('coderockz_woo_delivery_pickup_slot_settings')['time_slot'];
		if(array_key_exists($custom_pickup_slot_starts.'-'.$custom_pickup_slot_ends,$db_custom_pickup_slot)) {
			unset($db_custom_pickup_slot[$custom_pickup_slot_starts.'-'.$custom_pickup_slot_ends]);
			$custom_pickup_form_settings['time_slot'] = $db_custom_pickup_slot;
		}


		$custom_pickup_form_settings = array_merge(get_option('coderockz_woo_delivery_pickup_slot_settings'),$custom_pickup_form_settings);
		update_option('coderockz_woo_delivery_pickup_slot_settings', $custom_pickup_form_settings);
		

		wp_send_json_success();
    }

	public function coderockz_woo_delivery_add_enable_custom_pickup_slot() { 
    	check_ajax_referer('coderockz_woo_delivery_nonce');
		$pickup_slot = [];
		$custom_pickup_form_settings = [];		

		$old_custom_pickup_slot_starts_hour = (isset($_POST['pickupOldStartHour']) && $_POST['pickupOldStartHour'] !="") ? sanitize_text_field($_POST['pickupOldStartHour']) : "0";
		
		$old_custom_pickup_slot_starts_min = (isset($_POST['pickupOldStartMin']) && $_POST['pickupOldStartMin'] !="") ? sanitize_text_field($_POST['pickupOldStartMin']) : "0";

		$old_custom_pickup_slot_starts_format = (isset($_POST['pickupOldStartFormat']) && $_POST['pickupOldStartFormat'] !="") ? sanitize_text_field($_POST['pickupOldStartFormat']) : "";


		if($old_custom_pickup_slot_starts_format == "am") {
			$old_custom_pickup_slot_starts_hour = ($old_custom_pickup_slot_starts_hour == "12") ? "0" : $old_custom_pickup_slot_starts_hour;
			$old_custom_pickup_slot_starts = ((int)$old_custom_pickup_slot_starts_hour * 60) + (int)$old_custom_pickup_slot_starts_min;
		} else {
			$old_custom_pickup_slot_starts_hour = ($old_custom_pickup_slot_starts_hour == "12") ? "0" : $old_custom_pickup_slot_starts_hour;
			$old_custom_pickup_slot_starts = (((int)$old_custom_pickup_slot_starts_hour + 12)*60) + (int)$old_custom_pickup_slot_starts_min;
		}
		
		$old_custom_pickup_slot_ends_hour = (isset($_POST['pickupOldEndHour']) && $_POST['pickupOldEndHour'] !="") ? sanitize_text_field($_POST['pickupOldEndHour']) : "0";
		
		$old_custom_pickup_slot_ends_min = (isset($_POST['pickupOldEndMin']) && $_POST['pickupOldEndMin'] !="") ? sanitize_text_field($_POST['pickupOldEndMin']) : "0"; 

		$old_custom_pickup_slot_ends_format = (isset($_POST['pickupOldEndFormat']) && $_POST['pickupOldEndFormat'] !="") ? sanitize_text_field($_POST['pickupOldEndFormat']) : "";

		if($old_custom_pickup_slot_ends_format == "am") {
			$old_custom_pickup_slot_ends_hour = ($old_custom_pickup_slot_ends_hour == "12") ? "0" : $old_custom_pickup_slot_ends_hour;
			$old_custom_pickup_slot_ends = ((int)$old_custom_pickup_slot_ends_hour * 60) + (int)$old_custom_pickup_slot_ends_min;
		} else {
			$old_custom_pickup_slot_ends_hour = ($old_custom_pickup_slot_ends_hour == "12") ? "0" : $old_custom_pickup_slot_ends_hour;
			$old_custom_pickup_slot_ends = (((int)$old_custom_pickup_slot_ends_hour + 12)*60) + (int)$old_custom_pickup_slot_ends_min;
		} 

		$custom_pickup_slot_starts_hour = (isset($_POST['pickupStartHour']) && $_POST['pickupStartHour'] !="") ? sanitize_text_field($_POST['pickupStartHour']) : "0";
		
		$custom_pickup_slot_starts_min = (isset($_POST['pickupStartMin']) && $_POST['pickupStartMin'] !="") ? sanitize_text_field($_POST['pickupStartMin']) : "0";

		$custom_pickup_slot_starts_format = sanitize_text_field($_POST['pickupStartFormat']);

		if($custom_pickup_slot_starts_format == "am") {
			$custom_pickup_slot_starts_hour = ($custom_pickup_slot_starts_hour == "12") ? "0" : $custom_pickup_slot_starts_hour;
			$custom_pickup_slot_starts = ((int)$custom_pickup_slot_starts_hour * 60) + (int)$custom_pickup_slot_starts_min;
		} else {
			$custom_pickup_slot_starts_hour = ($custom_pickup_slot_starts_hour == "12") ? "0" : $custom_pickup_slot_starts_hour;
			$custom_pickup_slot_starts = (((int)$custom_pickup_slot_starts_hour + 12)*60) + (int)$custom_pickup_slot_starts_min;
		}

		$custom_pickup_slot_ends_hour = (isset($_POST['pickupEndHour']) && $_POST['pickupEndHour'] !="") ? sanitize_text_field($_POST['pickupEndHour']) : "0";
		
		$custom_pickup_slot_ends_min = (isset($_POST['pickupEndMin']) && $_POST['pickupEndMin'] !="") ? sanitize_text_field($_POST['pickupEndMin']) : "0"; 

		$custom_pickup_slot_ends_format = sanitize_text_field($_POST['pickupEndFormat']);

		if($custom_pickup_slot_ends_format == "am") {
			$custom_pickup_slot_ends_hour_12 = ($custom_pickup_slot_ends_hour == "12") ? "0" : $custom_pickup_slot_ends_hour;
			$custom_pickup_slot_ends = ((int)$custom_pickup_slot_ends_hour_12 * 60) + (int)$custom_pickup_slot_ends_min;
		} else {
			$custom_pickup_slot_ends_hour = ($custom_pickup_slot_ends_hour == "12") ? "0" : $custom_pickup_slot_ends_hour;
			$custom_pickup_slot_ends = (((int)$custom_pickup_slot_ends_hour + 12)*60) + (int)$custom_pickup_slot_ends_min;
		}

		if($custom_pickup_slot_ends_format == "am" && $custom_pickup_slot_ends_hour == "12" && ($custom_pickup_slot_ends_min =="0"||$custom_pickup_slot_ends_min =="00")) {
			$custom_pickup_slot_ends = 1440;
		}

		$pickup_slot[$custom_pickup_slot_starts.'-'.$custom_pickup_slot_ends]['start'] = (string)$custom_pickup_slot_starts;
		$pickup_slot[$custom_pickup_slot_starts.'-'.$custom_pickup_slot_ends]['end'] = (string)$custom_pickup_slot_ends;

		$enable_custom_pickup_split = !isset($_POST['pickupEnableCustomTimeSplit']) || $_POST['pickupEnableCustomTimeSplit'] == "unchecked" ? false : true;
		$split_pickup_slot_duration_time = (isset($_POST['pickupSplitDurationTime']) && $_POST['pickupSplitDurationTime'] !="") ? sanitize_text_field($_POST['pickupSplitDurationTime']) : "0";
		$split_pickup_slot_duration_format = (isset($_POST['pickupSplitDurationFormat']) && $_POST['pickupSplitDurationFormat'] !="") ? sanitize_text_field($_POST['pickupSplitDurationFormat']) : "min";

		if($split_pickup_slot_duration_format == "hour") {
			$each_split_pickup_slot = (int)$split_pickup_slot_duration_time * 60;
			$each_split_pickup_slot = $each_split_pickup_slot != 0 ? $each_split_pickup_slot : "";
		} else {
			$each_split_pickup_slot = (int)$split_pickup_slot_duration_time;
			$each_split_pickup_slot = $each_split_pickup_slot != 0 ? $each_split_pickup_slot : "";
		}
		$enable_custom_splited_pickup_single = !isset($_POST['pickupEnableCustomSplitedTimeSingle']) || $_POST['pickupEnableCustomSplitedTimeSingle'] == "unchecked" ? false : true;
		$enable_custom_pickup_single = !isset($_POST['pickupEnableCustomTimeSingle']) || $_POST['pickupEnableCustomTimeSingle'] == "unchecked" ? false : true;
		$pickup_slot[$custom_pickup_slot_starts.'-'.$custom_pickup_slot_ends]['enable_split'] = $enable_custom_pickup_split;
		$pickup_slot[$custom_pickup_slot_starts.'-'.$custom_pickup_slot_ends]['split_slot_duration'] = $each_split_pickup_slot;
		$pickup_slot[$custom_pickup_slot_starts.'-'.$custom_pickup_slot_ends]['enable_single_splited_slot'] = $enable_custom_splited_pickup_single;
		$pickup_slot[$custom_pickup_slot_starts.'-'.$custom_pickup_slot_ends]['enable_single_slot'] = $enable_custom_pickup_single;

		$custom_pickup_maximum_order = sanitize_text_field($_POST['pickupMaxOrder']);
		$pickup_slot[$custom_pickup_slot_starts.'-'.$custom_pickup_slot_ends]['max_order'] = $custom_pickup_maximum_order;

		$custom_pickup_slot_fee = sanitize_text_field($_POST['pickupSlotFee']);
		$pickup_slot[$custom_pickup_slot_starts.'-'.$custom_pickup_slot_ends]['fee'] = $custom_pickup_slot_fee;

		$disable_for = (isset($_POST['pickupDisableFor']) && !empty($_POST['pickupDisableFor'])) ? $_POST['pickupDisableFor'] : array();
		
		$state_or_zip_selection = sanitize_text_field($_POST['pickupStateOrZipSelection']);

		if($state_or_zip_selection == 'zone') {
			$region_zone_code = (isset($_POST['pickupRegionZoneCode']) && !empty($_POST['pickupRegionZoneCode'])) ? $_POST['pickupRegionZoneCode'] : array();
			foreach($region_zone_code as $zone_code) {
				$zone_state_code = [];
				$zone = new WC_Shipping_Zone($zone_code);

				$zone_locations = $zone->get_zone_locations();
				$zone_locations = $this->helper->objectToArray($zone_locations);
				foreach($zone_locations as $zone_location) {
					if($zone_location['type'] == "state") {
						$position = strpos($zone_location['code'],':');
						$zone_state_code[] = substr($zone_location['code'],($position+1));
					} else if($zone_location['type'] == "postcode") {
						$zone_post_code[] = $zone_location['code'];
					}
				}
			}

			$region_state_code = $zone_state_code;
			$region_post_code = $zone_post_code;


		} elseif($state_or_zip_selection == 'state') {
			$region_state_code = (isset($_POST['pickupRegionStateCode']) && !empty($_POST['pickupRegionStateCode'])) ? $_POST['pickupRegionStateCode'] : array();
			$region_zone_code = array();
			$region_post_code = array();

		} elseif($state_or_zip_selection == 'postcode') {
			$region_post_code = (isset($_POST['pickupRegionPostCode']) && !empty($_POST['pickupRegionPostCode'])) ? $_POST['pickupRegionPostCode'] : array();
			$region_zone_code = array();
			$region_state_code = array();
		} else {
			$region_zone_code = array();
			$region_state_code = array();
			$region_post_code = array();
		}		

		$custom_pickup_slot_disable = $this->helper->coderockz_woo_delivery_array_sanitize($disable_for);
		$custom_pickup_slot_disable_zone_code = $this->helper->coderockz_woo_delivery_array_sanitize($region_zone_code);
		$custom_pickup_slot_disable_state_code = $this->helper->coderockz_woo_delivery_array_sanitize($region_state_code);
		$custom_pickup_slot_disable_post_code = $this->helper->coderockz_woo_delivery_array_sanitize($region_post_code);

		$pickup_slot[$custom_pickup_slot_starts.'-'.$custom_pickup_slot_ends]['disable_for'] = $custom_pickup_slot_disable;
		$pickup_slot[$custom_pickup_slot_starts.'-'.$custom_pickup_slot_ends]['more_settings'] = $state_or_zip_selection;
		
		$pickup_slot[$custom_pickup_slot_starts.'-'.$custom_pickup_slot_ends]['disable_zone'] = $custom_pickup_slot_disable_zone_code;
		$pickup_slot[$custom_pickup_slot_starts.'-'.$custom_pickup_slot_ends]['disable_state'] = $custom_pickup_slot_disable_state_code;
		$pickup_slot[$custom_pickup_slot_starts.'-'.$custom_pickup_slot_ends]['disable_postcode'] = $custom_pickup_slot_disable_post_code;

		$hide_location = (isset($_POST['hidePickupLocationArray']) && !empty($_POST['hidePickupLocationArray'])) ? $_POST['hidePickupLocationArray'] : array();
		$hide_categories = (isset($_POST['hidePickupCategoriesArray']) && !empty($_POST['hidePickupCategoriesArray'])) ? $_POST['hidePickupCategoriesArray'] : array();
		$hide_products = (isset($_POST['hidePickupProductsArray']) && !empty($_POST['hidePickupProductsArray'])) ? $_POST['hidePickupProductsArray'] : array();

		$hide_location_array = $this->helper->coderockz_woo_delivery_array_sanitize($hide_location);
		$hide_categories_array = $this->helper->coderockz_woo_delivery_array_sanitize($hide_categories);
		$hide_products_array = $this->helper->coderockz_woo_delivery_array_sanitize($hide_products);

		$time_slot_shown_other_categories_products = !isset($_POST['enablePickupShownOtherCategoriesProducts']) || $_POST['enablePickupShownOtherCategoriesProducts'] == "unchecked" ? false : true;

		$pickup_slot[$custom_pickup_slot_starts.'-'.$custom_pickup_slot_ends]['hide_for_pickup_location'] = $hide_location_array;
		$pickup_slot[$custom_pickup_slot_starts.'-'.$custom_pickup_slot_ends]['hide_categories'] = $hide_categories_array;
		$pickup_slot[$custom_pickup_slot_starts.'-'.$custom_pickup_slot_ends]['hide_products'] = $hide_products_array;
		$pickup_slot[$custom_pickup_slot_starts.'-'.$custom_pickup_slot_ends]['time_slot_shown_other_categories_products'] = $time_slot_shown_other_categories_products;

		$enable_added_custom_pickup = !isset($_POST['pickupEnableAddedCustomTime']) || $_POST['pickupEnableAddedCustomTime'] == "unchecked" ? false : true;
		$pickup_slot[$custom_pickup_slot_starts.'-'.$custom_pickup_slot_ends]['enable'] = $enable_added_custom_pickup;
		
		if(get_option('coderockz_woo_delivery_pickup_slot_settings') == false) {
			$temp_pickup_slot = [];
			$temp_pickup_slot['time_slot'] = $pickup_slot;
			update_option('coderockz_woo_delivery_pickup_slot_settings', $temp_pickup_slot);
		} else {

			if(isset(get_option('coderockz_woo_delivery_pickup_slot_settings')['time_slot']) && count(get_option('coderockz_woo_delivery_pickup_slot_settings')['time_slot'])>0) {

				$db_custom_pickup_slot = get_option('coderockz_woo_delivery_pickup_slot_settings')['time_slot'];
				if($old_custom_pickup_slot_starts != $custom_pickup_slot_starts || $old_custom_pickup_slot_ends != $custom_pickup_slot_ends) {

					
					if(array_key_exists($old_custom_pickup_slot_starts.'-'.$old_custom_pickup_slot_ends,$db_custom_pickup_slot)) {
						
						unset($db_custom_pickup_slot[$old_custom_pickup_slot_starts.'-'.$old_custom_pickup_slot_ends]);
						
					}
				}

				$pickup_slot = array_merge($db_custom_pickup_slot,$pickup_slot);
				$custom_pickup_form_settings['time_slot'] = $pickup_slot;
			} else {
				$custom_pickup_form_settings['time_slot'] = $pickup_slot;
			}

			$custom_pickup_form_settings = array_merge(get_option('coderockz_woo_delivery_pickup_slot_settings'),$custom_pickup_form_settings);
			update_option('coderockz_woo_delivery_pickup_slot_settings', $custom_pickup_form_settings);
		}

		wp_send_json_success();
	}

	public function coderockz_woo_delivery_process_pickup_location_form() { 
    	check_ajax_referer('coderockz_woo_delivery_nonce');
		parse_str( $_POST[ 'dateFormData' ], $date_form_data );
		$pickup_location_form_settings = [];
		$enable_pickup_location = !isset($date_form_data['coderockz_enable_pickup_location']) ? false : true;
		$pickup_location_mandatory = !isset($date_form_data['coderockz_pickup_location_mandatory']) ? false : true;
		$pickup_location_field_label = sanitize_text_field($date_form_data['coderockz_pickup_location_field_label']);
		$pickup_location_field_placeholder = sanitize_text_field($date_form_data['coderockz_pickup_location_field_placeholder']);


		$pickup_location_form_settings['enable_pickup_location'] = $enable_pickup_location;
		$pickup_location_form_settings['pickup_location_mandatory'] = $pickup_location_mandatory;
		$pickup_location_form_settings['field_label'] = $pickup_location_field_label;
		$pickup_location_form_settings['field_placeholder'] = $pickup_location_field_placeholder;


		if(get_option('coderockz_woo_delivery_pickup_location_settings') == false) {
			update_option('coderockz_woo_delivery_pickup_location_settings', $pickup_location_form_settings);
		} else {
			$pickup_location_form_settings = array_merge(get_option('coderockz_woo_delivery_pickup_location_settings'),$pickup_location_form_settings);
			update_option('coderockz_woo_delivery_pickup_location_settings', $pickup_location_form_settings);
		}

		wp_send_json_success();
	}

	public function coderockz_woo_delivery_add_pickup_location_form() { 
    	check_ajax_referer('coderockz_woo_delivery_nonce');
		parse_str( $_POST[ 'dateFormData' ], $date_form_data );
		$pickup_location_form_settings = [];
		$location_names = $this->helper->coderockz_woo_delivery_array_sanitize($date_form_data['coderockz-woo-delivery-pickup-location-name']);
		
		$pickup_location_form_settings['pickup_location'] = $location_names;
		
		if(get_option('coderockz_woo_delivery_pickup_location_settings') == false) {
			update_option('coderockz_woo_delivery_pickup_location_settings', $pickup_location_form_settings);
		} else {
			$pickup_location_form_settings = array_merge(get_option('coderockz_woo_delivery_pickup_location_settings'),$pickup_location_form_settings);
			update_option('coderockz_woo_delivery_pickup_location_settings', $pickup_location_form_settings);
		}
		wp_send_json_success();
	}

	public function coderockz_woo_delivery_overall_processing_days_settings_form() { 
    	check_ajax_referer('coderockz_woo_delivery_nonce');
		parse_str( $_POST[ 'dateFormData' ], $processing_days_form_data );

		$overall_processing_days = sanitize_text_field($processing_days_form_data['coderockz_delivery_overall_processing_days']);
		$processing_days_form_settings['overall_processing_days'] = $overall_processing_days;


		if(get_option('coderockz_woo_delivery_processing_days_settings') == false) {
			update_option('coderockz_woo_delivery_processing_days_settings', $processing_days_form_settings);
		} else {
			$processing_days_form_settings = array_merge(get_option('coderockz_woo_delivery_processing_days_settings'),$processing_days_form_settings);
			update_option('coderockz_woo_delivery_processing_days_settings', $processing_days_form_settings);
		}

		wp_send_json_success();
	}

	public function coderockz_woo_delivery_processing_days_settings_form() { 
    	check_ajax_referer('coderockz_woo_delivery_nonce');
		parse_str( $_POST[ 'dateFormData' ], $processing_days_form_data );

		$consider_off_days = !isset($processing_days_form_data['coderockz_delivery_date_processing_days_off_days']) ? false : true;
		
		$consider_weekend = !isset($processing_days_form_data['coderockz_delivery_date_processing_days_weekend_days']) ? false : true;

		$consider_current_day = !isset($processing_days_form_data['coderockz_delivery_date_processing_days_current_day']) ? false : true;

		$processing_days_form_settings['processing_days_consider_off_days'] = $consider_off_days;
		$processing_days_form_settings['processing_days_consider_weekends'] = $consider_weekend;
		$processing_days_form_settings['processing_days_consider_current_day'] = $consider_current_day;


		if(get_option('coderockz_woo_delivery_processing_days_settings') == false) {
			update_option('coderockz_woo_delivery_processing_days_settings', $processing_days_form_settings);
		} else {
			$processing_days_form_settings = array_merge(get_option('coderockz_woo_delivery_processing_days_settings'),$processing_days_form_settings);
			update_option('coderockz_woo_delivery_processing_days_settings', $processing_days_form_settings);
		}

		wp_send_json_success();
	}

	public function coderockz_woo_delivery_category_processing_days_form() { 
    	check_ajax_referer('coderockz_woo_delivery_nonce');
		parse_str( $_POST[ 'dateFormData' ], $processing_days_form_data );
		$processing_days = [];
		$processing_days_categories = $this->helper->coderockz_woo_delivery_array_sanitize($processing_days_form_data['coderockz_delivery_processing_days_categories']);
		foreach($processing_days_categories as $processing_days_category) {
			$category = str_replace("--"," ", $processing_days_category);
			$processing_days[$category] = sanitize_text_field($processing_days_form_data['coderockz-woo-delivery-processing-days-'.$processing_days_category]);
		}
		$enable_category_wise_processing_days = !isset($processing_days_form_data['coderockz_woo_delivery_category_wise_processing_days']) ? false : true;

		$processing_days_form_settings['enable_category_wise_processing_days'] = $enable_category_wise_processing_days;
		$processing_days_form_settings['category_processing_days'] = $processing_days;

		if(get_option('coderockz_woo_delivery_processing_days_settings') == false) {
			update_option('coderockz_woo_delivery_processing_days_settings', $processing_days_form_settings);
		} else {
			$processing_days_form_settings = array_merge(get_option('coderockz_woo_delivery_processing_days_settings'),$processing_days_form_settings);
			update_option('coderockz_woo_delivery_processing_days_settings', $processing_days_form_settings);
		}

		wp_send_json_success();
	}

	public function coderockz_woo_delivery_product_processing_days_form() { 
    	check_ajax_referer('coderockz_woo_delivery_nonce');
		parse_str( $_POST[ 'dateFormData' ], $processing_days_form_data );
		$processing_days = [];
		$processing_days_products = $this->helper->coderockz_woo_delivery_array_sanitize($processing_days_form_data['coderockz_delivery_processing_days_products']);
		foreach($processing_days_products as $processing_days_product) {
			$processing_days[$processing_days_product] = sanitize_text_field($processing_days_form_data['coderockz-woo-delivery-product-processing-days-'.$processing_days_product]);
		}

		$enable_product_wise_processing_days = !isset($processing_days_form_data['coderockz_woo_delivery_product_wise_processing_days']) ? false : true;
		$enable_product_processing_day_quantity = !isset($processing_days_form_data['coderockz_woo_delivery_product_processing_day_quantity']) ? false : true;

		$processing_days_form_settings['enable_product_wise_processing_days'] = $enable_product_wise_processing_days;
		$processing_days_form_settings['enable_product_processing_day_quantity'] = $enable_product_processing_day_quantity;
		$processing_days_form_settings['product_processing_days'] = $processing_days;

		if(get_option('coderockz_woo_delivery_processing_days_settings') == false) {
			update_option('coderockz_woo_delivery_processing_days_settings', $processing_days_form_settings);
		} else {
			$processing_days_form_settings = array_merge(get_option('coderockz_woo_delivery_processing_days_settings'),$processing_days_form_settings);
			update_option('coderockz_woo_delivery_processing_days_settings', $processing_days_form_settings);
		}

		wp_send_json_success();
	}

	public function coderockz_woo_delivery_processing_time_settings_form() { 
    	check_ajax_referer('coderockz_woo_delivery_nonce');
		parse_str( $_POST[ 'processingTimeFormData' ], $processing_time_form_data );

		$disable_timeslot_with_processing_time = !isset($processing_time_form_data['coderockz_woo_delivery_processing_time_disable_timeslot_with_processing_time']) ? false : true;
		$overall_processing_time = (isset($processing_time_form_data['coderockz_woo_delivery_overall_processing_time']) && $processing_time_form_data['coderockz_woo_delivery_overall_processing_time'] !="") ? sanitize_text_field($processing_time_form_data['coderockz_woo_delivery_overall_processing_time']) : "0";
		$overall_processing_time_format = sanitize_text_field($processing_time_form_data['coderockz_woo_delivery_overall_processing_time_format']);


		if($overall_processing_time_format == "hour") {
			$processing_time_minutes = (int)$overall_processing_time * 60;
			$processing_time_minutes = $processing_time_minutes != 0 ? $processing_time_minutes : "";
		} else {
			$processing_time_minutes = (int)$overall_processing_time;
			$processing_time_minutes = $processing_time_minutes != 0 ? $processing_time_minutes : "";
		}		

		$processing_time_form_settings['disable_timeslot_with_processing_time'] = $disable_timeslot_with_processing_time;
		$processing_time_form_settings['overall_processing_time'] = (string)$processing_time_minutes;

		if(get_option('coderockz_woo_delivery_processing_time_settings') == false) {
			update_option('coderockz_woo_delivery_processing_time_settings', $processing_time_form_settings);
		} else {
			$processing_time_form_settings = array_merge(get_option('coderockz_woo_delivery_processing_time_settings'),$processing_time_form_settings);
			update_option('coderockz_woo_delivery_processing_time_settings', $processing_time_form_settings);
		}

		wp_send_json_success();
	}

	public function coderockz_woo_delivery_category_processing_time_form() { 
    	check_ajax_referer('coderockz_woo_delivery_nonce');
		parse_str( $_POST[ 'categoryProcessingTimeFormData' ], $category_processing_time_form_data );
		$processing_time = [];
		$processing_time_categories = $this->helper->coderockz_woo_delivery_array_sanitize($category_processing_time_form_data['coderockz_delivery_processing_time_categories']);
		foreach($processing_time_categories as $processing_time_category) {
			$category = str_replace("--"," ", $processing_time_category);


			$processing_time_duration = (isset($category_processing_time_form_data['coderockz_woo_delivery_category_processing_time-'.$processing_time_category]) && $category_processing_time_form_data['coderockz_woo_delivery_category_processing_time-'.$processing_time_category] !="") ? sanitize_text_field($category_processing_time_form_data['coderockz_woo_delivery_category_processing_time-'.$processing_time_category]) : "0";
			$processing_time_format = sanitize_text_field($category_processing_time_form_data['coderockz_woo_delivery_category_processing_time_format-'.$processing_time_category]);


			if($processing_time_format == "hour") {
				$processing_time_minutes = (int)$processing_time_duration * 60;
				$processing_time_minutes = $processing_time_minutes != 0 ? $processing_time_minutes : "";
			} else {
				$processing_time_minutes = (int)$processing_time_duration;
				$processing_time_minutes = $processing_time_minutes != 0 ? $processing_time_minutes : "";
			}



			$processing_time[$category] = (string)$processing_time_minutes;
		}
		$enable_category_wise_processing_time = !isset($category_processing_time_form_data['coderockz_woo_delivery_category_wise_processing_time']) ? false : true;

		$processing_time_form_settings['enable_category_wise_processing_time'] = $enable_category_wise_processing_time;
		$processing_time_form_settings['category_processing_time'] = $processing_time;

		if(get_option('coderockz_woo_delivery_processing_time_settings') == false) {
			update_option('coderockz_woo_delivery_processing_time_settings', $processing_time_form_settings);
		} else {
			$processing_time_form_settings = array_merge(get_option('coderockz_woo_delivery_processing_time_settings'),$processing_time_form_settings);
			update_option('coderockz_woo_delivery_processing_time_settings', $processing_time_form_settings);
		}

		wp_send_json_success();
	}

	public function coderockz_woo_delivery_product_processing_time_form() { 
    	check_ajax_referer('coderockz_woo_delivery_nonce');
		parse_str( $_POST[ 'productProcessingTimeFormData' ], $product_processing_time_form_data );
		$processing_time = [];
		$processing_time_products = $this->helper->coderockz_woo_delivery_array_sanitize($product_processing_time_form_data['coderockz_delivery_processing_time_products']);
		foreach($processing_time_products as $processing_time_product) {
			$processing_time_duration = (isset($product_processing_time_form_data['coderockz_woo_delivery_product_processing_time-'.$processing_time_product]) && $product_processing_time_form_data['coderockz_woo_delivery_product_processing_time-'.$processing_time_product] !="") ? sanitize_text_field($product_processing_time_form_data['coderockz_woo_delivery_product_processing_time-'.$processing_time_product]) : "0";
			$processing_time_format = sanitize_text_field($product_processing_time_form_data['coderockz_woo_delivery_product_processing_time_format-'.$processing_time_product]);


			if($processing_time_format == "hour") {
				$processing_time_minutes = (int)$processing_time_duration * 60;
				$processing_time_minutes = $processing_time_minutes != 0 ? $processing_time_minutes : "";
			} else {
				$processing_time_minutes = (int)$processing_time_duration;
				$processing_time_minutes = $processing_time_minutes != 0 ? $processing_time_minutes : "";
			}



			$processing_time[$processing_time_product] = (string)$processing_time_minutes;
		}
		$enable_product_wise_processing_time = !isset($product_processing_time_form_data['coderockz_woo_delivery_product_wise_processing_time']) ? false : true;
		$enable_product_processing_time_quantity = !isset($product_processing_time_form_data['coderockz_woo_delivery_product_processing_time_quantity']) ? false : true;

		$processing_time_form_settings['enable_product_wise_processing_time'] = $enable_product_wise_processing_time;
		$processing_time_form_settings['enable_product_processing_time_quantity'] = $enable_product_processing_time_quantity;
		$processing_time_form_settings['product_processing_time'] = $processing_time;

		if(get_option('coderockz_woo_delivery_processing_time_settings') == false) {
			update_option('coderockz_woo_delivery_processing_time_settings', $processing_time_form_settings);
		} else {
			$processing_time_form_settings = array_merge(get_option('coderockz_woo_delivery_processing_time_settings'),$processing_time_form_settings);
			update_option('coderockz_woo_delivery_processing_time_settings', $processing_time_form_settings);
		}

		wp_send_json_success();
	}

	public function coderockz_woo_delivery_process_time_slot_fee() { 
    	check_ajax_referer('coderockz_woo_delivery_nonce');
		parse_str( $_POST[ 'dateFormData' ], $date_form_data );
		$delivery_time_slot_fee_settings = [];
		$time_slot_fees=[];
		$enable_delivery_time_slot_fee = !isset($date_form_data['coderockz_delivery_date_enable_time_slot_fee']) ? false : true;

		$delivery_time_slot_fees = $this->helper->coderockz_woo_delivery_array_sanitize($date_form_data['coderockz_delivery_time_slot']);
		foreach($delivery_time_slot_fees as $delivery_time_slot_fee) {
			$time_slot_fees[$delivery_time_slot_fee] = sanitize_text_field($date_form_data['coderockz-woo-delivery-time-slot-fee-'.$delivery_time_slot_fee]);
		}

		$delivery_time_slot_fee_settings['enable_time_slot_fee'] = $enable_delivery_time_slot_fee;
		$delivery_time_slot_fee_settings['time_slot_fee'] = $time_slot_fees;

		if(get_option('coderockz_woo_delivery_fee_settings') == false) {
			update_option('coderockz_woo_delivery_fee_settings', $delivery_time_slot_fee_settings);
		} else {
			$delivery_time_slot_fee_settings = array_merge(get_option('coderockz_woo_delivery_fee_settings'),$delivery_time_slot_fee_settings);
			update_option('coderockz_woo_delivery_fee_settings', $delivery_time_slot_fee_settings);
		}

		wp_send_json_success();
		
	}

	public function coderockz_woo_delivery_process_pickup_slot_fee() { 
    	check_ajax_referer('coderockz_woo_delivery_nonce');
		parse_str( $_POST[ 'dateFormData' ], $date_form_data );
		$delivery_pickup_slot_fee_settings = [];
		$pickup_slot_fees=[];
		$enable_delivery_pickup_slot_fee = !isset($date_form_data['coderockz_delivery_date_enable_pickup_slot_fee']) ? false : true;

		$delivery_pickup_slot_fees = $this->helper->coderockz_woo_delivery_array_sanitize($date_form_data['coderockz_delivery_pickup_slot']);
		foreach($delivery_pickup_slot_fees as $delivery_pickup_slot_fee) {
			$pickup_slot_fees[$delivery_pickup_slot_fee] = sanitize_text_field($date_form_data['coderockz-woo-delivery-pickup-slot-fee-'.$delivery_pickup_slot_fee]);
		}

		$delivery_pickup_slot_fee_settings['enable_pickup_slot_fee'] = $enable_delivery_pickup_slot_fee;
		$delivery_pickup_slot_fee_settings['pickup_slot_fee'] = $pickup_slot_fees;

		if(get_option('coderockz_woo_delivery_fee_settings') == false) {
			update_option('coderockz_woo_delivery_fee_settings', $delivery_pickup_slot_fee_settings);
		} else {
			$delivery_pickup_slot_fee_settings = array_merge(get_option('coderockz_woo_delivery_fee_settings'),$delivery_pickup_slot_fee_settings);
			update_option('coderockz_woo_delivery_fee_settings', $delivery_pickup_slot_fee_settings);
		}

		wp_send_json_success();
		
	}

	public function coderockz_woo_delivery_process_delivery_date_fee() { 
    	check_ajax_referer('coderockz_woo_delivery_nonce');
		parse_str( $_POST[ 'dateFormData' ], $date_form_data );
		$delivery_date_fee_settings = [];
		$enable_delivery_date_fee = !isset($date_form_data['coderockz_delivery_date_enable_delivery_date_fee']) ? false : true;
		$same_day_fee = sanitize_text_field($date_form_data['coderockz_delivery_date_same_day_fee']);
		$next_day_fee = sanitize_text_field($date_form_data['coderockz_delivery_date_next_day_fee']);
		$day_after_tomorrow_fee = sanitize_text_field($date_form_data['coderockz_delivery_date_day_after_tomorrow_fee']);
		$other_day_fee = sanitize_text_field($date_form_data['coderockz_delivery_date_other_days_fee']);

		$delivery_date_fee_settings['enable_delivery_date_fee'] = $enable_delivery_date_fee;
		$delivery_date_fee_settings['same_day_fee'] = $same_day_fee;
		$delivery_date_fee_settings['next_day_fee'] = $next_day_fee;
		$delivery_date_fee_settings['day_after_tomorrow_fee'] = $day_after_tomorrow_fee;
		$delivery_date_fee_settings['other_days_fee'] = $other_day_fee;

		if(get_option('coderockz_woo_delivery_fee_settings') == false) {
			update_option('coderockz_woo_delivery_fee_settings', $delivery_date_fee_settings);
		} else {
			$delivery_date_fee_settings = array_merge(get_option('coderockz_woo_delivery_fee_settings'),$delivery_date_fee_settings);
			update_option('coderockz_woo_delivery_fee_settings', $delivery_date_fee_settings);
		}

		wp_send_json_success();
		
	}

	public function coderockz_woo_delivery_process_weekday_wise_fee() { 
    	check_ajax_referer('coderockz_woo_delivery_nonce');
		parse_str( $_POST[ 'dateFormData' ], $date_form_data );
		
		$fee_settings = [];
		$weekday_delivery_fee_settings = [];

		$enable_weekday_wise_delivery_fee = !isset($date_form_data['coderockz_woo_delivery_enable_weekday_wise_delivery_fee']) ? false : true;
		$weekday_wise_delivery_fee = [];

		$weekday = array("0"=>"Sunday", "1"=>"Monday", "2"=>"Tuesday", "3"=>"Wednesday", "4"=>"Thursday", "5"=>"Friday", "6"=>"Saturday");
        foreach ($weekday as $key => $value) { 
	
			$weekday_wise_delivery_fee_[$key] = (isset($date_form_data['coderockz_woo_delivery_weekday_wise_fee_'.$key]) && $date_form_data['coderockz_woo_delivery_weekday_wise_fee_'.$key] !="") ? sanitize_text_field($date_form_data['coderockz_woo_delivery_weekday_wise_fee_'.$key]) : ""; 

			$weekday_wise_delivery_fee[$key] = $weekday_wise_delivery_fee_[$key];
        }

        $fee_settings['enable_weekday_wise_delivery_fee'] = $enable_weekday_wise_delivery_fee;
		$fee_settings['weekday_wise_delivery_fee'] = $weekday_wise_delivery_fee;

		if(get_option('coderockz_woo_delivery_fee_settings') == false) {
			update_option('coderockz_woo_delivery_fee_settings', $fee_settings);
		} else {
			$fee_settings = array_merge(get_option('coderockz_woo_delivery_fee_settings'),$fee_settings);
			update_option('coderockz_woo_delivery_fee_settings', $fee_settings);
		}

		wp_send_json_success();
		
	}

	public function coderockz_woo_delivery_process_notify_email() { 
    	check_ajax_referer('coderockz_woo_delivery_nonce');
		parse_str( $_POST[ 'dateFormData' ], $date_form_data );
		$notify_email_form_settings = [];

		$notify_delivery_email_subject = sanitize_text_field($date_form_data['coderockz_woo_delivery_notify_delivery_email_subject']);
		$notify_pickup_email_subject = sanitize_text_field($date_form_data['coderockz_woo_delivery_notify_pickup_email_subject']);
		$send_email_from_email = sanitize_text_field($date_form_data['coderockz_woo_delivery_send_email_from_email']);
		$send_email_from_name = sanitize_text_field($date_form_data['coderockz_woo_delivery_send_email_from_name']);

		$delivery_notify_email_heading = sanitize_text_field($date_form_data['coderockz_woo_delivery_delivery_notify_email_heading']);
		$pickup_notify_email_heading = sanitize_text_field($date_form_data['coderockz_woo_delivery_pickup_notify_email_heading']);

		$notify_email_billing_address_heading = sanitize_text_field($date_form_data['coderockz_woo_delivery_notify_email_billing_address_heading']);
		$notify_email_shipping_address_heading = sanitize_text_field($date_form_data['coderockz_woo_delivery_notify_email_shipping_address_heading']);


		$notify_email_product_text = sanitize_text_field($date_form_data['coderockz_woo_delivery_notify_email_product_text']);
		$notify_email_quantity_text = sanitize_text_field($date_form_data['coderockz_woo_delivery_notify_email_quantity_text']);
		$notify_email_price_text = sanitize_text_field($date_form_data['coderockz_woo_delivery_notify_email_total_text']);


		$notify_email_shipping_text = sanitize_text_field($date_form_data['coderockz_woo_delivery_notify_email_shipping_text']);
		$notify_email_payment_text = sanitize_text_field($date_form_data['coderockz_woo_delivery_notify_email_payment_text']);
		$notify_email_total_text = sanitize_text_field($date_form_data['coderockz_woo_delivery_notify_email_price_text']);


		$notify_email_form_settings['notify_delivery_email_subject'] = $notify_delivery_email_subject;
		$notify_email_form_settings['notify_pickup_email_subject'] = $notify_pickup_email_subject;
		$notify_email_form_settings['send_email_from_email'] = $send_email_from_email;
		$notify_email_form_settings['send_email_from_name'] = $send_email_from_name;
		$notify_email_form_settings['delivery_notify_email_heading'] = $delivery_notify_email_heading;
		$notify_email_form_settings['pickup_notify_email_heading'] = $pickup_notify_email_heading;

		$notify_email_form_settings['notify_email_billing_address_heading'] = $notify_email_billing_address_heading;
		$notify_email_form_settings['notify_email_shipping_address_heading'] = $notify_email_shipping_address_heading;

		$notify_email_form_settings['notify_email_product_text'] = $notify_email_product_text;
		$notify_email_form_settings['notify_email_quantity_text'] = $notify_email_quantity_text;
		$notify_email_form_settings['notify_email_price_text'] = $notify_email_price_text;

		$notify_email_form_settings['notify_email_shipping_text'] = $notify_email_shipping_text;
		$notify_email_form_settings['notify_email_payment_text'] = $notify_email_payment_text;
		$notify_email_form_settings['notify_email_total_text'] = $notify_email_total_text;


		if(get_option('coderockz_woo_delivery_notify_email_settings') == false) {
			update_option('coderockz_woo_delivery_notify_email_settings', $notify_email_form_settings);
		} else {
			$notify_email_form_settings = array_merge(get_option('coderockz_woo_delivery_notify_email_settings'),$notify_email_form_settings);
			update_option('coderockz_woo_delivery_notify_email_settings', $notify_email_form_settings);
		}

		wp_send_json_success();
		
	}

	public function coderockz_woo_delivery_process_additional_field() { 
    	check_ajax_referer('coderockz_woo_delivery_nonce');
		
		$additional_field_form_settings = [];

		parse_str( $_POST[ 'dateFormData' ], $date_form_data );

		$enable_additional_field = !isset($date_form_data['coderockz_enable_additional_field']) ? false : true;
		
		$additional_field_mandatory = !isset($date_form_data['coderockz_additional_field_mandatory']) ? false : true;
		
		$additional_field_field_label = sanitize_text_field($date_form_data['coderockz_additional_field_label']);
		
		$additional_field_ch_limit = sanitize_text_field($date_form_data['coderockz_additional_field_ch_limit']);

		$disable_order_notes = !isset($date_form_data['coderockz_woo_delivery_disable_order_notes']) ? false : true;

		$hide_additional_field_for = (isset($date_form_data['coderockz_woo_delivery_hide_additional_field_for']) && !empty($date_form_data['coderockz_woo_delivery_hide_additional_field_for'])) ? $date_form_data['coderockz_woo_delivery_hide_additional_field_for'] : array();

		$additional_field_form_settings['enable_additional_field'] = $enable_additional_field;
		$additional_field_form_settings['additional_field_mandatory'] = $additional_field_mandatory;
		$additional_field_form_settings['field_label'] = $additional_field_field_label;
		$additional_field_form_settings['character_limit'] = $additional_field_ch_limit;
		$additional_field_form_settings['hide_additional_field_for'] = $hide_additional_field_for;
		$additional_field_form_settings['disable_order_notes'] = $disable_order_notes;
		
		if(get_option('coderockz_woo_delivery_additional_field_settings') == false) {
			update_option('coderockz_woo_delivery_additional_field_settings', $additional_field_form_settings);
		} else {
			$additional_field_form_settings = array_merge(get_option('coderockz_woo_delivery_additional_field_settings'),$additional_field_form_settings);
			update_option('coderockz_woo_delivery_additional_field_settings', $additional_field_form_settings);
		}
		wp_send_json_success();
		
    }

    public function coderockz_woo_delivery_process_delivery_option_settings() { 
    	check_ajax_referer('coderockz_woo_delivery_nonce');
		
		$delivery_option_settings_form_settings = [];

		parse_str( $_POST[ 'formData' ], $form_data );

		$coderockz_enable_option_time_pickup = !isset($form_data['coderockz_enable_option_time_pickup']) ? false : true;

		$delivery_option_field_label = sanitize_text_field($form_data['coderockz_woo_delivery_delivery_option_label']);

		$delivery_field_label = sanitize_text_field($form_data['coderockz_woo_delivery_option_delivery_label']);

		$pickup_field_label = sanitize_text_field($form_data['coderockz_woo_delivery_option_pickup_label']);
		$no_result_notice = sanitize_text_field($form_data['coderockz_woo_delivery_option_no_result_notice']);

		$enable_dynamic_order_type = !isset($form_data['coderockz_woo_delivery_enable_dynamic_order_type']) ? false : true;

		$dynamic_order_type_no_delivery = sanitize_text_field($form_data['coderockz_woo_delivery_dynamic_order_type_no_delivery']);

		$dynamic_order_type_no_pickup = sanitize_text_field($form_data['coderockz_woo_delivery_dynamic_order_type_no_pickup']);
		$dynamic_order_type_no_delivery_pickup = sanitize_text_field($form_data['coderockz_woo_delivery_dynamic_order_type_no_delivery_pickup']);

		$delivery_option_settings_form_settings['enable_option_time_pickup'] = $coderockz_enable_option_time_pickup;
		$delivery_option_settings_form_settings['delivery_option_label'] = $delivery_option_field_label;
		$delivery_option_settings_form_settings['delivery_label'] = $delivery_field_label;
		$delivery_option_settings_form_settings['pickup_label'] = $pickup_field_label;
		$delivery_option_settings_form_settings['no_result_notice'] = $no_result_notice;
		$delivery_option_settings_form_settings['enable_dynamic_order_type'] = $enable_dynamic_order_type;
		$delivery_option_settings_form_settings['dynamic_order_type_no_delivery'] = $dynamic_order_type_no_delivery;
		$delivery_option_settings_form_settings['dynamic_order_type_no_pickup'] = $dynamic_order_type_no_pickup;
		$delivery_option_settings_form_settings['dynamic_order_type_no_delivery_pickup'] = $dynamic_order_type_no_delivery_pickup;

		
		if(get_option('coderockz_woo_delivery_option_delivery_settings') == false) {
			update_option('coderockz_woo_delivery_option_delivery_settings', $delivery_option_settings_form_settings);
		} else {
			$delivery_option_settings_form_settings = array_merge(get_option('coderockz_woo_delivery_option_delivery_settings'),$delivery_option_settings_form_settings);
			update_option('coderockz_woo_delivery_option_delivery_settings', $delivery_option_settings_form_settings);
		}
		wp_send_json_success();
		
    }

    public function coderockz_woo_delivery_delivery_restriction_settings() { 
    	check_ajax_referer('coderockz_woo_delivery_nonce');
		
		$delivery_option_settings_form_settings = [];

		parse_str( $_POST[ 'formData' ], $form_data );

		$enable_delivery_restriction = !isset($form_data['coderockz_woo_delivery_enable_delivery_restriction']) ? false : true;

		$minimum_amount_cart_restriction = sanitize_text_field($form_data['coderockz_woo_delivery_minimum_amount_cart_restriction']);

		$calculating_include_tax = !isset($form_data['coderockz_woo_delivery_calculating_include_tax']) ? false : true;

		$delivery_restriction_notice = sanitize_text_field($form_data['coderockz_woo_delivery_delivery_restriction_notice']);


		$delivery_option_settings_form_settings['enable_delivery_restriction'] = $enable_delivery_restriction;
		$delivery_option_settings_form_settings['minimum_amount_cart_restriction'] = $minimum_amount_cart_restriction;
		$delivery_option_settings_form_settings['calculating_include_tax'] = $calculating_include_tax;
		$delivery_option_settings_form_settings['delivery_restriction_notice'] = $delivery_restriction_notice;
		
		if(get_option('coderockz_woo_delivery_option_delivery_settings') == false) {
			update_option('coderockz_woo_delivery_option_delivery_settings', $delivery_option_settings_form_settings);
		} else {
			$delivery_option_settings_form_settings = array_merge(get_option('coderockz_woo_delivery_option_delivery_settings'),$delivery_option_settings_form_settings);
			update_option('coderockz_woo_delivery_option_delivery_settings', $delivery_option_settings_form_settings);
		}
		wp_send_json_success();
		
    }

    public function coderockz_woo_delivery_free_shipping_restriction_settings() { 
    	check_ajax_referer('coderockz_woo_delivery_nonce');
		
		$delivery_option_settings_form_settings = [];

		parse_str( $_POST[ 'formData' ], $form_data );

		$enable_free_shipping_restriction = !isset($form_data['coderockz_woo_delivery_enable_free_shipping_restriction']) ? false : true;
		$enable_hide_other_shipping_method = !isset($form_data['coderockz_woo_delivery_enable_hide_other_shipping_method']) ? false : true;

		$minimum_amount_shipping_restriction = sanitize_text_field($form_data['coderockz_woo_delivery_minimum_amount_shipping_restriction']);

		$calculating_include_tax_free_shipping = !isset($form_data['coderockz_woo_delivery_calculating_include_tax_free_shipping']) ? false : true;

		$free_shipping_restriction_notice = sanitize_text_field($form_data['coderockz_woo_delivery_free_shipping_restriction_notice']);


		$delivery_option_settings_form_settings['enable_free_shipping_restriction'] = $enable_free_shipping_restriction;
		$delivery_option_settings_form_settings['enable_hide_other_shipping_method'] = $enable_hide_other_shipping_method;
		$delivery_option_settings_form_settings['minimum_amount_shipping_restriction'] = $minimum_amount_shipping_restriction;
		$delivery_option_settings_form_settings['calculating_include_tax_free_shipping'] = $calculating_include_tax_free_shipping;
		$delivery_option_settings_form_settings['free_shipping_restriction_notice'] = $free_shipping_restriction_notice;
		
		if(get_option('coderockz_woo_delivery_option_delivery_settings') == false) {
			update_option('coderockz_woo_delivery_option_delivery_settings', $delivery_option_settings_form_settings);
		} else {
			$delivery_option_settings_form_settings = array_merge(get_option('coderockz_woo_delivery_option_delivery_settings'),$delivery_option_settings_form_settings);
			update_option('coderockz_woo_delivery_option_delivery_settings', $delivery_option_settings_form_settings);
		}
		wp_send_json_success();
		
    }

    public function coderockz_woo_delivery_disable_delivery_facility_days() { 
    	check_ajax_referer('coderockz_woo_delivery_nonce');
		
		$delivery_option_settings_form_settings = [];

		parse_str( $_POST[ 'formData' ], $form_data );

		$disable_delivery_facility_days = (isset($form_data['coderockz_woo_delivery_disable_delivery_facility_days']) && !empty($form_data['coderockz_woo_delivery_disable_delivery_facility_days'])) ? $form_data['coderockz_woo_delivery_disable_delivery_facility_days'] : array();
		$disable_delivery_facility_days = $this->helper->coderockz_woo_delivery_array_sanitize($disable_delivery_facility_days);

		$delivery_option_settings_form_settings['disable_delivery_facility'] = $disable_delivery_facility_days;

		
		if(get_option('coderockz_woo_delivery_option_delivery_settings') == false) {
			update_option('coderockz_woo_delivery_option_delivery_settings', $delivery_option_settings_form_settings);
		} else {
			$delivery_option_settings_form_settings = array_merge(get_option('coderockz_woo_delivery_option_delivery_settings'),$delivery_option_settings_form_settings);
			update_option('coderockz_woo_delivery_option_delivery_settings', $delivery_option_settings_form_settings);
		}
		wp_send_json_success();
		
    }

    public function coderockz_woo_delivery_disable_pickup_facility_days() { 
    	check_ajax_referer('coderockz_woo_delivery_nonce');
		
		$pickup_option_settings_form_settings = [];

		parse_str( $_POST[ 'formData' ], $form_data );

		$disable_pickup_facility_days = (isset($form_data['coderockz_woo_delivery_disable_pickup_facility_days']) && !empty($form_data['coderockz_woo_delivery_disable_pickup_facility_days'])) ? $form_data['coderockz_woo_delivery_disable_pickup_facility_days'] : array();
		$disable_pickup_facility_days = $this->helper->coderockz_woo_delivery_array_sanitize($disable_pickup_facility_days);

		$pickup_option_settings_form_settings['disable_pickup_facility'] = $disable_pickup_facility_days;

		
		if(get_option('coderockz_woo_delivery_option_delivery_settings') == false) {
			update_option('coderockz_woo_delivery_option_delivery_settings', $pickup_option_settings_form_settings);
		} else {
			$pickup_option_settings_form_settings = array_merge(get_option('coderockz_woo_delivery_option_delivery_settings'),$pickup_option_settings_form_settings);
			update_option('coderockz_woo_delivery_option_delivery_settings', $pickup_option_settings_form_settings);
		}
		wp_send_json_success();
		
    }

    public function coderockz_woo_delivery_process_other_settings() { 
    	check_ajax_referer('coderockz_woo_delivery_nonce');
		
		$other_settings_form_settings = [];

		parse_str( $_POST[ 'dateFormData' ], $date_form_data );
		
		$field_position = sanitize_text_field($date_form_data['coderockz_woo_delivery_field_position']);
		$spinner_id = isset($date_form_data['coderockz-woo-delivery-spinner-upload-id']) ? sanitize_text_field($date_form_data['coderockz-woo-delivery-spinner-upload-id']) : "";
		$spinner_background = sanitize_text_field($date_form_data['coderockz_woo_delivery_spinner_animation_background']);
		$other_settings_form_settings['field_position'] = $field_position;
		$other_settings_form_settings['spinner-animation-id'] = $spinner_id;
		$other_settings_form_settings['spinner_animation_background'] = $spinner_background;

		$hide_heading_delivery_section = !isset($date_form_data['coderockz_woo_delivery_hide_heading_delivery_section']) ? false : true;
		$other_settings_form_settings['hide_heading_delivery_section'] = $hide_heading_delivery_section;
		$coderockz_disable_fields_for_downloadable_products = !isset($date_form_data['coderockz_disable_fields_for_downloadable_products']) ? false : true;
		$other_settings_form_settings['disable_fields_for_downloadable_products'] = $coderockz_disable_fields_for_downloadable_products;
		
		$access_shop_manager = !isset($date_form_data['coderockz_woo_delivery_access_shop_manager']) ? false : true;
		
		$other_settings_form_settings['access_shop_manager'] = $access_shop_manager;

		$add_delivery_info_order_note = !isset($date_form_data['coderockz_woo_delivery_add_delivery_info_order_note']) ? false : true;
		$other_settings_form_settings['add_delivery_info_order_note'] = $add_delivery_info_order_note;

		$disable_dynamic_shipping = !isset($date_form_data['coderockz_woo_delivery_disable_dynamic_shipping_methods']) ? false : true;
		$other_settings_form_settings['disable_dynamic_shipping'] = $disable_dynamic_shipping;

		$hide_shipping_address = !isset($date_form_data['coderockz_woo_delivery_hide_shipping_address']) ? false : true;
		$other_settings_form_settings['hide_shipping_address'] = $hide_shipping_address;

		$additional_message = isset($date_form_data['coderockz_woo_delivery_additional_message']) ? sanitize_text_field($date_form_data['coderockz_woo_delivery_additional_message']) : "";
		$other_settings_form_settings['additional_message'] = $additional_message;
		
		if(get_option('coderockz_woo_delivery_other_settings') == false) {
			update_option('coderockz_woo_delivery_other_settings', $other_settings_form_settings);
		} else {
			$other_settings_form_settings = array_merge(get_option('coderockz_woo_delivery_other_settings'),$other_settings_form_settings);
			update_option('coderockz_woo_delivery_other_settings', $other_settings_form_settings);
		}
		wp_send_json_success();
		
    }

    public function coderockz_woo_delivery_process_category_exclusion_settings() { 
    	check_ajax_referer('coderockz_woo_delivery_nonce');
		
		$category_exclusion_form_settings = [];

		parse_str( $_POST[ 'formData' ], $form_data );

		$exclude_categories = (isset($form_data['coderockz_woo_delivery_exclude_product_categories']) && !empty($form_data['coderockz_woo_delivery_exclude_product_categories'])) ? $form_data['coderockz_woo_delivery_exclude_product_categories'] : array();
		$exclude_categories = $this->helper->coderockz_woo_delivery_array_sanitize($exclude_categories);

		$category_exclusion_form_settings['exclude_categories'] = $exclude_categories;
		
		if(get_option('coderockz_woo_delivery_exclude_settings') == false) {
			update_option('coderockz_woo_delivery_exclude_settings', $category_exclusion_form_settings);
		} else {
			$category_exclusion_form_settings = array_merge(get_option('coderockz_woo_delivery_exclude_settings'),$category_exclusion_form_settings);
			update_option('coderockz_woo_delivery_exclude_settings', $category_exclusion_form_settings);
		}
		wp_send_json_success();
		
    }

    public function coderockz_woo_delivery_process_product_exclusion_settings() { 
    	check_ajax_referer('coderockz_woo_delivery_nonce');
		
		$product_exclusion_form_settings = [];

		parse_str( $_POST[ 'formData' ], $form_data );

		$exclude_product = (isset($form_data['coderockz_woo_delivery_exclude_individual_product']) && !empty($form_data['coderockz_woo_delivery_exclude_individual_product'])) ? $form_data['coderockz_woo_delivery_exclude_individual_product'] : array();
		$exclude_product = $this->helper->coderockz_woo_delivery_array_sanitize($exclude_product);

		$product_exclusion_form_settings['exclude_products'] = $exclude_product;
		
		if(get_option('coderockz_woo_delivery_exclude_settings') == false) {
			update_option('coderockz_woo_delivery_exclude_settings', $product_exclusion_form_settings);
		} else {
			$product_exclusion_form_settings = array_merge(get_option('coderockz_woo_delivery_exclude_settings'),$product_exclusion_form_settings);
			update_option('coderockz_woo_delivery_exclude_settings', $product_exclusion_form_settings);
		}
		wp_send_json_success();
		
    }

    public function coderockz_woo_delivery_exclusion_settings_form() { 
    	check_ajax_referer('coderockz_woo_delivery_nonce');

    	$exclusion_form_settings = [];

		parse_str( $_POST[ 'dateFormData' ], $exclusion_settings_form_data );

		$reverse_current_condition = !isset($exclusion_settings_form_data['coderockz_delivery_exclusion_reverse_current_condition']) ? false : true;


		$exclusion_form_settings['reverse_current_condition'] = $reverse_current_condition;

		
		if(get_option('coderockz_woo_delivery_exclude_settings') == false) {
			update_option('coderockz_woo_delivery_exclude_settings', $exclusion_form_settings);
		} else {
			$exclusion_form_settings = array_merge(get_option('coderockz_woo_delivery_exclude_settings'),$exclusion_form_settings);
			update_option('coderockz_woo_delivery_exclude_settings', $exclusion_form_settings);
		}

		wp_send_json_success();
	}
  
    public function coderockz_woo_delivery_process_localization_settings() { 
    	check_ajax_referer('coderockz_woo_delivery_nonce');
		
		$localization_settings_form_settings = [];

		parse_str( $_POST[ 'formData' ], $form_data );
		
		$order_limit_notice = sanitize_text_field($form_data['coderockz_woo_delivery_order_limit_notice']);
		$pickup_limit_notice = sanitize_text_field($form_data['coderockz_woo_delivery_pickup_limit_notice']);
		$delivery_heading_checkout = sanitize_text_field($form_data['coderockz_woo_delivery_delivery_heading_checkout']);
		$select_delivery_date_notice = sanitize_text_field($form_data['coderockz_woo_delivery_select_delivery_date_notice']);
		$select_pickup_date_notice = sanitize_text_field($form_data['coderockz_woo_delivery_select_pickup_date_notice']);
		$select_pickup_date_location_notice = sanitize_text_field($form_data['coderockz_woo_delivery_select_pickup_date_location_notice']);
		$select_pickup_location_notice = sanitize_text_field($form_data['coderockz_woo_delivery_select_pickup_location_notice']);
		$delivery_details_text = sanitize_text_field($form_data['coderockz_woo_delivery_delivery_details_text']);
		$delivery_status_text = sanitize_text_field($form_data['coderockz_woo_delivery_delivery_status_text']);
		$delivery_status_not_delivered_text = sanitize_text_field($form_data['coderockz_woo_delivery_delivery_status_not_delivered_text']);
		$delivery_status_delivered_text = sanitize_text_field($form_data['coderockz_woo_delivery_delivery_status_delivered_text']);
		$pickup_status_not_picked_text = sanitize_text_field($form_data['coderockz_woo_delivery_pickup_status_not_picked_text']);
		$pickup_status_picked_text = sanitize_text_field($form_data['coderockz_woo_delivery_pickup_status_picked_text']);
		$order_metabox_heading = sanitize_text_field($form_data['coderockz_woo_delivery_order_metabox_heading']);

		$checkout_delivery_option_notice = sanitize_text_field($form_data['coderockz_woo_delivery_checkout_delivery_option_notice']);
		$checkout_date_notice = sanitize_text_field($form_data['coderockz_woo_delivery_checkout_date_notice']);
		$checkout_pickup_date_notice = sanitize_text_field($form_data['coderockz_woo_delivery_checkout_pickup_date_notice']);
		$checkout_time_notice = sanitize_text_field($form_data['coderockz_woo_delivery_checkout_time_notice']);
		$checkout_pickup_time_notice = sanitize_text_field($form_data['coderockz_woo_delivery_checkout_pickup_time_notice']);
		$checkout_pickup_notice = sanitize_text_field($form_data['coderockz_woo_delivery_checkout_pickup_notice']);
		$checkout_additional_field_notice = sanitize_text_field($form_data['coderockz_woo_delivery_checkout_additional_field_notice']);
		$delivery_fee_text = sanitize_text_field($form_data['coderockz_woo_delivery_checkout_delivery_fee_text']);
		$pickup_fee_text = sanitize_text_field($form_data['coderockz_woo_delivery_checkout_pickup_fee_text']);
		$sameday_fee_text = sanitize_text_field($form_data['coderockz_woo_delivery_same_day_delivery_fee_text']);
		$nextday_fee_text = sanitize_text_field($form_data['coderockz_woo_delivery_next_day_delivery_fee_text']);
		$day_after_tomorrow_fee_text = sanitize_text_field($form_data['coderockz_woo_delivery_day_after_tomorrow_delivery_fee_text']);
		$other_fee_text = sanitize_text_field($form_data['coderockz_woo_delivery_other_day_delivery_fee_text']);
		$weekday_fee_text = sanitize_text_field($form_data['coderockz_woo_delivery_weekday_fee_text']);

		$localization_settings_form_settings['order_limit_notice'] = $order_limit_notice;
		$localization_settings_form_settings['pickup_limit_notice'] = $pickup_limit_notice;
		$localization_settings_form_settings['delivery_status_text'] = $delivery_status_text;
		$localization_settings_form_settings['delivery_heading_checkout'] = $delivery_heading_checkout;
		$localization_settings_form_settings['delivery_details_text'] = $delivery_details_text;
		$localization_settings_form_settings['select_delivery_date_notice'] = $select_delivery_date_notice;
		$localization_settings_form_settings['select_pickup_date_notice'] = $select_pickup_date_notice;
		$localization_settings_form_settings['select_pickup_date_location_notice'] = $select_pickup_date_location_notice;
		$localization_settings_form_settings['select_pickup_location_notice'] = $select_pickup_location_notice;
		$localization_settings_form_settings['delivery_status_not_delivered_text'] = $delivery_status_not_delivered_text;
		$localization_settings_form_settings['delivery_status_delivered_text'] = $delivery_status_delivered_text;
		$localization_settings_form_settings['pickup_status_not_picked_text'] = $pickup_status_not_picked_text;
		$localization_settings_form_settings['pickup_status_picked_text'] = $pickup_status_picked_text;
		$localization_settings_form_settings['order_metabox_heading'] = $order_metabox_heading;
		$localization_settings_form_settings['checkout_delivery_option_notice'] = $checkout_delivery_option_notice;
		$localization_settings_form_settings['checkout_date_notice'] = $checkout_date_notice;
		$localization_settings_form_settings['checkout_pickup_date_notice'] = $checkout_pickup_date_notice;
		$localization_settings_form_settings['checkout_pickup_time_notice'] = $checkout_pickup_time_notice;
		$localization_settings_form_settings['checkout_time_notice'] = $checkout_time_notice;
		$localization_settings_form_settings['checkout_pickup_notice'] = $checkout_pickup_notice;
		$localization_settings_form_settings['checkout_additional_field_notice'] = $checkout_additional_field_notice;
		$localization_settings_form_settings['delivery_fee_text'] = $delivery_fee_text;
		$localization_settings_form_settings['pickup_fee_text'] = $pickup_fee_text;
		$localization_settings_form_settings['sameday_fee_text'] = $sameday_fee_text;
		$localization_settings_form_settings['nextday_fee_text'] = $nextday_fee_text;
		$localization_settings_form_settings['day_after_tomorrow_fee_text'] = $day_after_tomorrow_fee_text;
		$localization_settings_form_settings['other_fee_text'] = $other_fee_text;
		$localization_settings_form_settings['weekday_fee_text'] = $weekday_fee_text;

		
		if(get_option('coderockz_woo_delivery_localization_settings') == false) {
			update_option('coderockz_woo_delivery_localization_settings', $localization_settings_form_settings);
		} else {
			$localization_settings_form_settings = array_merge(get_option('coderockz_woo_delivery_localization_settings'),$localization_settings_form_settings);
			update_option('coderockz_woo_delivery_localization_settings', $localization_settings_form_settings);
		}
		wp_send_json_success();
		
    }

    /**
	 * Add custom column in orders page in admin panel
	*/
	public function coderockz_woo_delivery_add_custom_fields_orders_list($columns) {
		$delivery_details_text = (isset(get_option('coderockz_woo_delivery_localization_settings')['delivery_details_text']) && !empty(get_option('coderockz_woo_delivery_localization_settings')['delivery_details_text'])) ? stripslashes(get_option('coderockz_woo_delivery_localization_settings')['delivery_details_text']) : "Delivery Details";

		$delivery_status_text = (isset(get_option('coderockz_woo_delivery_localization_settings')['delivery_status_text']) && !empty(get_option('coderockz_woo_delivery_localization_settings')['delivery_status_text'])) ? stripslashes(get_option('coderockz_woo_delivery_localization_settings')['delivery_status_text']) : "Delivery Status";

		$new_columns = [];

		foreach($columns as $name => $value)
		{
			$new_columns[$name] = $value;

			if($name == 'order_status') {
				$new_columns['order_delivery_details'] = $delivery_details_text;
				$new_columns['order_delivery_status'] = $delivery_status_text;
			}
		}
		return $new_columns;
	}

	public function coderockz_woo_delivery_show_custom_fields_data_orders_list($column) {
		global $post;

		$delivery_date_settings = get_option('coderockz_woo_delivery_date_settings');			
		$pickup_date_settings = get_option('coderockz_woo_delivery_pickup_date_settings');			
		$delivery_time_settings = get_option('coderockz_woo_delivery_time_settings');
		$pickup_time_settings = get_option('coderockz_woo_delivery_pickup_settings');
		$delivery_pickup_settings = get_option('coderockz_woo_delivery_pickup_location_settings');
		$additional_field_settings = get_option('coderockz_woo_delivery_additional_field_settings');
		$localization_settings = get_option('coderockz_woo_delivery_localization_settings');

		$delivery_date_field_label = (isset($delivery_date_settings['field_label']) && !empty($delivery_date_settings['field_label'])) ? stripslashes($delivery_date_settings['field_label']) : "Delivery Date";
		$pickup_date_field_label = (isset($pickup_date_settings['pickup_field_label']) && !empty($pickup_date_settings['pickup_field_label'])) ? stripslashes($pickup_date_settings['pickup_field_label']) : "Pickup Date";
		$delivery_time_field_label = (isset($delivery_time_settings['field_label']) && !empty($delivery_time_settings['field_label'])) ? stripslashes($delivery_time_settings['field_label']) : "Delivery Time";
		$pickup_time_field_label = (isset($pickup_time_settings['field_label']) && !empty($pickup_time_settings['field_label'])) ? stripslashes($pickup_time_settings['field_label']) : "Pickup Time";
		$pickup_location_field_label = (isset($pickup_location_settings['field_label']) && !empty($pickup_location_settings['field_label'])) ? stripslashes($pickup_location_settings['field_label']) : "Pickup Location";
		$additional_field_field_label = (isset($additional_field_settings['field_label']) && !empty($additional_field_settings['field_label'])) ? stripslashes($additional_field_settings['field_label']) : "Special Note About Delivery";

		$localization_settings = get_option('coderockz_woo_delivery_localization_settings');
		$delivery_status_not_delivered_text = (isset($localization_settings['delivery_status_not_delivered_text']) && !empty($localization_settings['delivery_status_not_delivered_text'])) ? stripslashes($localization_settings['delivery_status_not_delivered_text']) : "Not Delivered";
		$delivery_status_delivered_text = (isset($localization_settings['delivery_status_delivered_text']) && !empty($localization_settings['delivery_status_delivered_text'])) ? stripslashes($localization_settings['delivery_status_delivered_text']) : "Delivery Completed";
		$pickup_status_not_picked_text = (isset($localization_settings['pickup_status_not_picked_text']) && !empty($localization_settings['pickup_status_not_picked_text'])) ? stripslashes($localization_settings['pickup_status_not_picked_text']) : "Not Picked";
		$pickup_status_picked_text = (isset($localization_settings['pickup_status_picked_text']) && !empty($localization_settings['pickup_status_picked_text'])) ? stripslashes($localization_settings['pickup_status_picked_text']) : "Pickup Completed";

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

		if($column == 'order_delivery_details')
		{
			if(metadata_exists('post', $post->ID, 'delivery_date') && get_post_meta($post->ID, 'delivery_date', true) !="")
			{
				$delivery_date = $this->helper->date_conversion_to_locale(date($delivery_date_format, strtotime(get_post_meta( $post->ID, 'delivery_date', true ))),"delivery");
		    	
		    	echo $delivery_date_field_label.": " . $delivery_date;	
			}

			if(metadata_exists('post', $post->ID, 'pickup_date') && get_post_meta($post->ID, 'pickup_date', true) !="")
			{
				
		    	$pickup_date = $this->helper->date_conversion_to_locale(date($pickup_date_format, strtotime(get_post_meta( $post->ID, 'pickup_date', true ))),"pickup");

		    	echo $pickup_date_field_label.": " . $pickup_date; 	
			}

			if(metadata_exists('post', $post->ID, 'delivery_time') && get_post_meta($post->ID, 'delivery_time', true) !="")
			{
				echo " <br > ";
				if(get_post_meta($post->ID,"delivery_time",true) == "as-soon-as-possible") {
					$as_soon_as_possible_text = (isset($delivery_time_settings['as_soon_as_possible_text']) && !empty($delivery_time_settings['as_soon_as_possible_text'])) ? stripslashes($delivery_time_settings['as_soon_as_possible_text']) : "As Soon As Possible";
					$time_value = $as_soon_as_possible_text;
				} else {
					$times = get_post_meta($post->ID,"delivery_time",true);
					$minutes = explode(' - ', $times);

		    		if(!isset($minutes[1])) {
		    			$time_value = date($time_format, strtotime($minutes[0]));
		    		} else {
		    			$time_value = date($time_format, strtotime($minutes[0])) . ' - ' . date($time_format, strtotime($minutes[1]));
		    		}

	    		}

				echo $delivery_time_field_label.": " . $time_value;

			}


			if(metadata_exists('post', $post->ID, 'pickup_time') && get_post_meta($post->ID, 'pickup_time', true) !="")
			{
				echo " <br > ";
				$pickup_times = get_post_meta($post->ID,"pickup_time",true);
				$pickup_minutes = explode(' - ', $pickup_times);

	    		if(!isset($pickup_minutes[1])) {
	    			$pickup_time_value = date($pickup_time_format, strtotime($pickup_minutes[0]));
	    		} else {

	    			$pickup_time_value = date($pickup_time_format, strtotime($pickup_minutes[0])) . ' - ' . date($pickup_time_format, strtotime($pickup_minutes[1]));
	    		}

				echo $pickup_time_field_label.": " . $pickup_time_value;

			}


			if(metadata_exists('post', $post->ID, 'delivery_pickup') && get_post_meta($post->ID, 'delivery_pickup', true) !="")
			{
				echo "<br >";
				echo $pickup_location_field_label.": " . get_post_meta($post->ID, 'delivery_pickup', true);
			}
		}

		if($column == 'order_delivery_status')
		{

			if(metadata_exists('post', $post->ID, 'delivery_status') && get_post_meta($post->ID, 'delivery_status', true) !="" && get_post_meta($post->ID, 'delivery_status', true) == "delivered")
			{

				if(metadata_exists('post', $post->ID, 'delivery_type') && get_post_meta($post->ID, 'delivery_type', true) !="" && get_post_meta($post->ID, 'delivery_type', true) =="pickup") {
					$delivery_status = '<span class="coderockz_woo_delivery_delivered_text">'.$pickup_status_picked_text.'</span>';
				} else {
					$delivery_status = '<span class="coderockz_woo_delivery_delivered_text">'.$delivery_status_delivered_text.'</span>';
				}

				echo $delivery_status;
			} else {

				if(metadata_exists('post', $post->ID, 'delivery_type') && get_post_meta($post->ID, 'delivery_type', true) !="" && get_post_meta($post->ID, 'delivery_type', true) =="pickup") {
					$delivery_status = '<span class="coderockz_woo_delivery_not_delivered_text">'.$pickup_status_not_picked_text.'</span>';
				} else {
					$delivery_status = '<span class="coderockz_woo_delivery_not_delivered_text">'.$delivery_status_not_delivered_text.'</span>';
				}

				echo $delivery_status;
			}

		}

	}

	public function coderockz_woo_delivery_information_after_shipping_address($order){
	    
		$order_items = $order->get_items();

		$exclude_condition = $this->helper->order_detect_exclude_condition($order_items);

		if(!$exclude_condition) {
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

		$delivery_option_settings = get_option('coderockz_woo_delivery_option_delivery_settings');
	    $order_type_field_label = (isset($delivery_option_settings['delivery_option_label']) && !empty($delivery_option_settings['delivery_option_label'])) ? stripslashes($delivery_option_settings['delivery_option_label']) : "Order Type";
	    $delivery_field_label = (isset($delivery_option_settings['delivery_label']) && !empty($delivery_option_settings['delivery_label'])) ? stripslashes($delivery_option_settings['delivery_label']) : "Delivery";
		$pickup_field_label = (isset($delivery_option_settings['pickup_label']) && !empty($delivery_option_settings['pickup_label'])) ? stripslashes($delivery_option_settings['pickup_label']) : "Pickup";

		$localization_settings = get_option('coderockz_woo_delivery_localization_settings');
		$delivery_status_not_delivered_text = (isset($localization_settings['delivery_status_not_delivered_text']) && !empty($localization_settings['delivery_status_not_delivered_text'])) ? stripslashes($localization_settings['delivery_status_not_delivered_text']) : "Not Delivered";
		$delivery_status_delivered_text = (isset($localization_settings['delivery_status_delivered_text']) && !empty($localization_settings['delivery_status_delivered_text'])) ? stripslashes($localization_settings['delivery_status_delivered_text']) : "Delivery Completed";
		$pickup_status_not_picked_text = (isset($localization_settings['pickup_status_not_picked_text']) && !empty($localization_settings['pickup_status_not_picked_text'])) ? stripslashes($localization_settings['pickup_status_not_picked_text']) : "Not Picked";
		$pickup_status_picked_text = (isset($localization_settings['pickup_status_picked_text']) && !empty($localization_settings['pickup_status_picked_text'])) ? stripslashes($localization_settings['pickup_status_picked_text']) : "Pickup Completed";

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

	    if(metadata_exists('post', $order_id, 'delivery_type') && get_post_meta($order_id, 'delivery_type', true) !="") {
	    	

	    	if(get_post_meta($order_id, 'delivery_type', true) == "delivery") {

	    		echo '<p><strong>'.$order_type_field_label.':</strong> ' . $delivery_field_label . '</p>';

			} elseif(get_post_meta($order_id, 'delivery_type', true) == "pickup") {
				
				echo '<p><strong>'.$order_type_field_label.':</strong> ' . $pickup_field_label . '</p>';
			}

	    }

	    if(metadata_exists('post', $order_id, 'delivery_date') && get_post_meta($order_id, 'delivery_date', true) !="") {

	    	$delivery_date = $this->helper->date_conversion_to_locale(date($delivery_date_format, strtotime(get_post_meta( $order_id, 'delivery_date', true ))),"delivery");

	    	echo '<p><strong>'.$delivery_date_field_label.':</strong> ' . $delivery_date . '</p>';
	    	
	    }

	    if(metadata_exists('post', $order_id, 'pickup_date') && get_post_meta($order_id, 'pickup_date', true) !="") {

	    	$pickup_date = $this->helper->date_conversion_to_locale(date($pickup_date_format, strtotime(get_post_meta( $order_id, 'pickup_date', true ))),"pickup");
	    	echo '<p><strong>'.$pickup_date_field_label.':</strong> ' . $pickup_date . '</p>'; 
	    	
	    }

	    if(metadata_exists('post', $order_id, 'delivery_time') && get_post_meta($order_id, 'delivery_time', true) !="") {
	    	if(get_post_meta($order_id,"delivery_time",true) == "as-soon-as-possible") {
	    		$as_soon_as_possible_text = (isset($delivery_time_settings['as_soon_as_possible_text']) && !empty($delivery_time_settings['as_soon_as_possible_text'])) ? stripslashes($delivery_time_settings['as_soon_as_possible_text']) : "As Soon As Possible";
	    		echo '<p><strong>'.$delivery_time_field_label.':</strong> ' . $as_soon_as_possible_text . '</p>';
	    	} else {
		    	$minutes = get_post_meta($order_id,"delivery_time",true);
		    	$minutes = explode(' - ', $minutes);

	    		if(!isset($minutes[1])) {
	    			echo '<p><strong>'.$delivery_time_field_label.':</strong> ' . date($time_format, strtotime($minutes[0])) . '</p>';
	    		} else {
	    			echo '<p><strong>'.$delivery_time_field_label.':</strong> ' . date($time_format, strtotime($minutes[0])) . ' - ' . date($time_format, strtotime($minutes[1])) . '</p>';    			
	    		}

    		}
	    	
	    }

	    if(metadata_exists('post', $order_id, 'pickup_time') && get_post_meta($order_id, 'pickup_time', true) !="") {
	    	$pickup_minutes = get_post_meta($order_id,"pickup_time",true);
	    	$pickup_minutes = explode(' - ', $pickup_minutes);

    		if(!isset($pickup_minutes[1])) {
    			echo '<p><strong>'.$pickup_time_field_label.':</strong> ' . date($pickup_time_format, strtotime($pickup_minutes[0])) . '</p>';
    		} else {

    			echo '<p><strong>'.$pickup_time_field_label.':</strong> ' . date($pickup_time_format, strtotime($pickup_minutes[0])) . ' - ' . date($pickup_time_format, strtotime($pickup_minutes[1])) . '</p>';			
    		}
	    	
	    }

	    if(metadata_exists('post', $order_id, 'delivery_pickup') && get_post_meta($order_id, 'delivery_pickup', true) !="") {
			echo '<p><strong>'.$pickup_location_field_label.':</strong> ' . get_post_meta($order_id, 'delivery_pickup', true) . '</p>';
		}

		if(metadata_exists('post', $order_id, 'additional_note') && get_post_meta($order_id, 'additional_note', true) !="") {
			echo '<p><strong>'.$additional_field_field_label.':</strong> ' . get_post_meta($order_id, 'additional_note', true) . '</p>';
		}
		
		if(metadata_exists('post', $order_id, 'delivery_status') && get_post_meta($order_id, 'delivery_status', true) !="" && get_post_meta($order_id, 'delivery_status', true) =="delivered") {
			if(metadata_exists('post', $order_id, 'delivery_type') && get_post_meta($order_id, 'delivery_type', true) !="" && get_post_meta($order_id, 'delivery_type', true) =="pickup") {
				echo '<span class="coderockz_woo_delivery_delivered_text">'.$pickup_status_picked_text.'</span>';
			} else {
				echo '<span class="coderockz_woo_delivery_delivered_text">'.$delivery_status_delivered_text.'</span>';
			}
			
		} else {

			if(metadata_exists('post', $order_id, 'delivery_type') && get_post_meta($order_id, 'delivery_type', true) !="" && get_post_meta($order_id, 'delivery_type', true) =="pickup") {
				echo '<span class="coderockz_woo_delivery_not_delivered_text">'.$pickup_status_not_picked_text.'</span>';
			} else {
				echo '<span class="coderockz_woo_delivery_not_delivered_text">'.$delivery_status_not_delivered_text.'</span>';
			}
		}
		} else {
			echo '<span class="coderockz_woo_delivery_not_delivered_text">Order Has Excluded Product/Category</span>';
		}
	    
	}

	public function coderockz_woo_delivery_review_notice() {
	    $options = get_option('coderockz_woo_delivery_review_notice');

	    $activation_time = get_option('coderockz-woo-delivery-pro-activation-time');

	    $notice = '<div class="coderockz-woo-delivery-review-notice notice notice-success is-dismissible">';
        $notice .= '<img class="coderockz-woo-delivery-review-notice-left" src="'.CODEROCKZ_WOO_DELIVERY_URL.'admin/images/woo-delivery-logo.png" alt="coderockz-woo-delivery">';
        $notice .= '<div class="coderockz-woo-delivery-review-notice-right">';
        $notice .= '<p><b>We have worked relentlessly to develop the plugin and it would really appreciate us if you dropped a short review about the plugin. Your review means a lot to us and we are working to make the plugin more awesome. Thanks for using WooCommerce Delivery Date & Time.</b></p>';
        $notice .= '<ul>';
        $notice .= '<li><a val="later" href="#">Remind me later</a></li>';
        $notice .= '<li><a class="coderockz-woo-delivery-review-request-btn" style="font-weight:bold;" val="given" href="#" target="_blank">Review Here</a></li>';
		$notice .= '<li><a val="never" href="#">I would not</a></li>';	        
        $notice .= '</ul>';
        $notice .= '</div>';
        $notice .= '</div>';
        
	    if(!$options && time()>= $activation_time + (60*60*24*15)){
	        echo $notice;
	    } else if(is_array($options)) {
	        if((!array_key_exists('review_notice',$options)) || ($options['review_notice'] =='later' && time()>=($options['updated_at'] + (60*60*24*30) ))){
	            echo $notice;
	        }
	    }
	}

	/*public function coderockz_woo_delivery_notice_of_separate_pickup_date() {

	    $notice = '<div class="coderockz-woo-delivery-review-notice notice notice-success is-dismissible">';
	    $notice .= '<h3 style="color:#07729F">IMPORTANT NOTICE OF WOO DELIVERY</h3>';
	    $notice .= '<h4 style="color:#07729F">This notice is only for users who started using the plugin before version 1.2.48. If you started using the plugin after version 1.2.47, ignore the notice.</h4>';
        $notice .= '<p><b>We introduced completely separate Pickup Date. If you are using delivery date settings as Pickup date, please go to the plugin settings and set pickup date as you want. <span style="color:#DB3035">If you are using the plugin only for delivery then go to the Pickup Date Tab and disable it</span>. Thanks for using WooCommerce Delivery Date & Time.</b></p>';
        $notice .= '</div>';

        echo $notice;

	}*/


	public function coderockz_woo_delivery_save_review_notice() {
	    $notice = sanitize_text_field($_POST['notice']);
	    $value = array();
	    $value['review_notice'] = $notice;
	    $value['updated_at'] = time();

	    update_option('coderockz_woo_delivery_review_notice',$value);
	    wp_send_json_success($value);
	}

	public function coderockz_woo_delivery_get_deactivate_reasons() {

		$reasons = array(
			array(
				'id'          => 'could-not-understand',
				'text'        => 'I couldn\'t understand how to make it work',
				'type'        => 'textarea',
				'placeholder' => 'Would you like us to assist you?'
			),
			array(
				'id'          => 'found-better-plugin',
				'text'        => 'I found a better plugin',
				'type'        => 'text',
				'placeholder' => 'Which plugin?'
			),
			array(
				'id'          => 'not-have-that-feature',
				'text'        => 'I need specific feature that you don\'t support',
				'type'        => 'textarea',
				'placeholder' => 'Could you tell us more about that feature?'
			),
			array(
				'id'          => 'is-not-working',
				'text'        => 'The plugin is not working',
				'type'        => 'textarea',
				'placeholder' => 'Could you tell us a bit more whats not working?'
			),
			array(
				'id'          => 'temporary-deactivation',
				'text'        => 'It\'s a temporary deactivation',
				'type'        => '',
				'placeholder' => ''
			),
			array(
				'id'          => 'other',
				'text'        => 'Other',
				'type'        => 'textarea',
				'placeholder' => 'Could you tell us a bit more?'
			),
		);

		return $reasons;
	}

	public function coderockz_woo_delivery_deactivate_reason_submission(){
		check_ajax_referer('coderockz_woo_delivery_nonce');
		global $wpdb;

		if ( ! isset( $_POST['reason_id'] ) ) { // WPCS: CSRF ok, Input var ok.
			wp_send_json_error();
		}

		$current_user = new WP_User(get_current_user_id());

		$data = array(
			'reason_id'     => sanitize_text_field( $_POST['reason_id'] ), // WPCS: CSRF ok, Input var ok.
			'plugin'        => "Woo Delivery Pro",
			'url'           => home_url(),
			'user_email'    => $current_user->data->user_email,
			'user_name'     => $current_user->data->display_name,
			'reason_info'   => isset( $_REQUEST['reason_info'] ) ? trim( stripslashes( $_REQUEST['reason_info'] ) ) : '',
			'software'      => $_SERVER['SERVER_SOFTWARE'],
			'date'          => time(),
			'php_version'   => phpversion(),
			'mysql_version' => $wpdb->db_version(),
			'wp_version'    => get_bloginfo( 'version' )
		);


		$this->coderockz_woo_delivery_deactivate_send_request( $data);
		wp_send_json_success();

	}

	public function coderockz_woo_delivery_deactivate_send_request( $params) {
		$api_url = "https://coderockz.com/wp-json/coderockz-api/v1/deactivation-reason";
		return  wp_remote_post($api_url, array(
				'method'      => 'POST',
				'timeout'     => 45,
				'redirection' => 5,
				'httpversion' => '1.0',
				'blocking'    => false,
				'headers'     => array( 'user-agent' => 'WooDelivery/' . md5( esc_url( home_url() ) ) . ';' ),
				'body'        => $params,
				'cookies'     => array()
			)
		);
	}

	public function coderockz_woo_delivery_deactivate_scripts() {

		global $pagenow;

		if ( 'plugins.php' != $pagenow ) {
			return;
		}

		$reasons = $this->coderockz_woo_delivery_get_deactivate_reasons();
		?>
		<!--pop up modal-->
		<div class="coderockz_woo_delivery_deactive_plugin-modal" id="coderockz_woo_delivery_deactive_plugin-modal">
			<div class="coderockz_woo_delivery_deactive_plugin-modal-wrap">
				<div class="coderockz_woo_delivery_deactive_plugin-modal-header">
					<h2 style="margin:0;"><span class="dashicons dashicons-testimonial"></span><?php _e( ' QUICK FEEDBACK' ); ?></h2>
				</div>

				<div class="coderockz_woo_delivery_deactive_plugin-modal-body">
					<p style="font-size:15px;font-weight:bold"><?php _e( 'If you have a moment, please share why you are deactivating Our plugin', 'coderockz-woo-delivery' ); ?></p>
					<ul class="reasons">
						<?php foreach ($reasons as $reason) { ?>
							<li data-type="<?php echo esc_attr( $reason['type'] ); ?>" data-placeholder="<?php echo esc_attr( $reason['placeholder'] ); ?>">
								<label><input type="radio" name="selected-reason" value="<?php echo $reason['id']; ?>"> <?php echo $reason['text']; ?></label>
							</li>
						<?php } ?>
					</ul>
				</div>

				<div class="coderockz_woo_delivery_deactive_plugin-modal-footer">
					<a href="#" class="coderockz-woo-delivery-skip-deactivate"><?php _e( 'Skip & Deactivate', 'coderockz-woo-delivery' ); ?></a>
					<div style="float:left">
					<button class="coderockz-woo-delivery-deactivate-button button-primary"><?php _e( 'Submit & Deactivate', 'coderockz-woo-delivery' ); ?></button>
					<button class="coderockz-woo-delivery-cancel-button button-secondary"><?php _e( 'Cancel', 'coderockz-woo-delivery' ); ?></button>
					</div>
				</div>
			</div>
		</div>

		<?php
	}

	public function coderockz_woo_delivery_review_submission(){
	    check_ajax_referer('coderockz_woo_delivery_nonce');
	    global $wpdb;

	    if ( ! isset( $_POST['review'] ) ) { // WPCS: CSRF ok, Input var ok.
	        wp_send_json_error();
	    }

	    $current_user = new WP_User(get_current_user_id());

	    $data = array(
	        'review'     	=> sanitize_textarea_field( $_POST['review'] ), // WPCS: CSRF ok, Input var ok.
	        'rating'        => sanitize_text_field( $_POST['rating'] ),
	        'plugin'        => 'Woo Delivery Pro',
	        'url'           => home_url(),
	        'user_email'    => $current_user->data->user_email,
	        'user_name'     => $current_user->data->display_name,
	        'user_gravator' => get_avatar_url(get_current_user_id()),
	        'date'          => time(),
	    );


	    $notice = sanitize_text_field($_POST['notice']);
	    $value = array();
	    $value['review_notice'] = $notice;
	    $value['updated_at'] = time();
	    update_option('coderockz_woo_delivery_review_notice',$value);


	    $this->coderockz_woo_delivery_review_send_request( $data );
	    wp_send_json_success();

	}

	public function coderockz_woo_delivery_review_send_request( $params ) {
	    $api_url = "https://coderockz.com/wp-json/coderockz-api/v1/customer-review";
	    return  wp_remote_post($api_url, array(
	            'method'      => 'POST',
	            'timeout'     => 45,
	            'redirection' => 5,
	            'httpversion' => '1.0',
	            'blocking'    => false,
	            'headers'     => array( 'user-agent' => 'WooDelivery/' . md5( esc_url( home_url() ) ) . ';' ),
	            'body'        => $params,
	            'cookies'     => array()
	        )
	    );

	}

	public function coderockz_woo_delivery_review_scripts() {

	    if ( !current_user_can( 'manage_options' ) ) {
	        return;
	    }

	    ?>
	    <!--pop up modal-->
	    <div class="coderockz_woo_delivery_customer_review_plugin-modal" id="coderockz_woo_delivery_customer_review_plugin-modal">
	        <div class="coderockz_woo_delivery_customer_review_plugin-modal-wrap">
	        	<div class="coderockz_woo_delivery_customer_review_plugin-modal-header">
					<h2 style="margin:0;"><span class="dashicons dashicons-format-chat"></span><?php _e( ' QUICK REVIEW' ); ?></h2>
				</div>
	            <div class="coderockz_woo_delivery_customer_review_plugin-modal-body">
	                <p style="font-size:15px;font-weight:bold"><?php _e( 'Your review really appreciate us. Thank\'s for reviewing WooCommerce Delivery Date & Time.', 'coderockz-woo-delivery' ); ?></p>
	                <div class="coderockz-woo-delivery-customer-rating-review">
	                    <div class="form-group" id="coderockz-woo-delivery-customer-rating">
	                    </div>
	                    <textarea id="coderockz-woo-delivery-customer-review" placeholder="What do you think about WooCommerce Delivery Date & Time"></textarea>
	                </div>
	            </div>

	            <div class="coderockz_woo_delivery_customer_review_plugin-modal-footer">
	            	<div style="float:right;">
	                <button class="coderockz-woo-delivery-submit-review-button button-primary"><?php _e( 'Submit Review', 'coderockz-woo-delivery' ); ?></button>
	                <button class="coderockz-woo-delivery-cancel-button button-secondary"><?php _e( 'Cancel', 'coderockz-woo-delivery' ); ?></button>
	            	</div>
	            </div>
	        </div>
	    </div>
	    <?php
	}

	public function coderockz_woo_delivery_custom_meta_box() {
		
		$order_metabox_heading = (isset(get_option('coderockz_woo_delivery_localization_settings')['order_metabox_heading']) && !empty(get_option('coderockz_woo_delivery_localization_settings')['order_metabox_heading'])) ? stripslashes(get_option('coderockz_woo_delivery_localization_settings')['order_metabox_heading']) : "Delivery/Pickup Date & Time"; 
		add_meta_box( 'coderockz_woo_delivery_meta_box', __($order_metabox_heading,'coderockz-woo-delivery'), array($this,"coderockz_woo_delivery_meta_box_markup"), 'shop_order', 'normal', 'default', null );
	}

	public function coderockz_woo_delivery_meta_box_markup() {

		// if any timezone data is saved, set default timezone with the data
		$timezone = $this->helper->get_the_timezone();
		date_default_timezone_set($timezone);

		global $post;

		$order = wc_get_order( $post->ID );
		$order_items = $order->get_items();

		$today = date('Y-m-d', time());

		$delivery_date_settings = get_option('coderockz_woo_delivery_date_settings');
		$pickup_date_settings = get_option('coderockz_woo_delivery_pickup_date_settings');
		$delivery_time_settings = get_option('coderockz_woo_delivery_time_settings');
		$pickup_time_settings = get_option('coderockz_woo_delivery_pickup_settings');
		
		$processing_days_settings = get_option('coderockz_woo_delivery_processing_days_settings');

		$enable_delivery_date = (isset($delivery_date_settings['enable_delivery_date']) && !empty($delivery_date_settings['enable_delivery_date'])) ? $delivery_date_settings['enable_delivery_date'] : false;
		$enable_pickup_date = (isset($pickup_date_settings['enable_pickup_date']) && !empty($pickup_date_settings['enable_pickup_date'])) ? $pickup_date_settings['enable_pickup_date'] : false;
		$off_days = (isset($delivery_date_settings['off_days']) && !empty($delivery_date_settings['off_days'])) ? $delivery_date_settings['off_days'] : array();
		
		$consider_off_days = (isset($processing_days_settings['processing_days_consider_off_days']) && !empty($processing_days_settings['processing_days_consider_off_days'])) ? $processing_days_settings['processing_days_consider_off_days'] : false;
		$consider_weekends = (isset($processing_days_settings['processing_days_consider_weekends']) && !empty($processing_days_settings['processing_days_consider_weekends'])) ? $processing_days_settings['processing_days_consider_weekends'] : false;
		$consider_current_day = (isset($processing_days_settings['processing_days_consider_current_day']) && !empty($processing_days_settings['processing_days_consider_current_day'])) ? $processing_days_settings['processing_days_consider_current_day'] : false;
		
		$max_processing_days_array = [];
		$enable_category_processing_days = (isset($processing_days_settings['enable_category_wise_processing_days']) && !empty($processing_days_settings['enable_category_wise_processing_days'])) ? $processing_days_settings['enable_category_wise_processing_days'] : false;
		$category_processing_days = (isset($processing_days_settings['category_processing_days']) && !empty($processing_days_settings['category_processing_days'])) ? $processing_days_settings['category_processing_days'] : array();

		$overall_processing_days = (isset($processing_days_settings['overall_processing_days']) && !empty($processing_days_settings['overall_processing_days'])) ? $processing_days_settings['overall_processing_days'] : "0";

		if($enable_category_processing_days && !empty($category_processing_days)) {			
			
			$order_product_categories = $this->helper->order_product_categories($order_items);

			foreach ($category_processing_days as $key => $value)
			{
				if(in_array(strtolower($key), $order_product_categories))
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
			
			$product_id = $this->helper->order_product_id($order_items);
			foreach ($product_processing_days as $key => $value)
			{
				if(in_array($key, $product_id))
				{
					if($enable_product_processing_day_quantity) {
						foreach ( $order_items as $item ) {

							if( $item->get_variation_id() ) {
						        $product_id = $item->get_variation_id();			        
						    } else {
								$product_id = $item->get_product_id();
							}

						    if($product_id == $key ){
						        $qty =  $item->get_quantity();
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

		$max_processing_time_array = [];
		$processing_time_settings = get_option('coderockz_woo_delivery_processing_time_settings');
		$enable_category_processing_time = (isset($processing_time_settings['enable_category_wise_processing_time']) && !empty($processing_time_settings['enable_category_wise_processing_time'])) ? $processing_time_settings['enable_category_wise_processing_time'] : false;
		$category_processing_time = (isset($processing_time_settings['category_processing_time']) && !empty($processing_time_settings['category_processing_time'])) ? $processing_time_settings['category_processing_time'] : array();

		if($enable_category_processing_time && !empty($category_processing_time)) {						

			$order_product_categories = $this->helper->order_product_categories($order_items);

			foreach ($category_processing_time as $key => $value)
			{
				if(in_array(strtolower($key), $order_product_categories))
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
			
			$product_id = $this->helper->order_product_id($order_items);

			foreach ($product_processing_time as $key => $value)
			{
				if(in_array($key, $product_id))
				{					
					if($enable_product_processing_time_quantity) {
						foreach ( $order_items as $item ) {
							if( $item->get_variation_id() ) {
						        if($item->get_variation_id() == $key ){
							        $qty =  $item->get_quantity();
							        break;
							    }			        
						    } else {
								if($item->get_product_id() == $key ){
							        $qty =  $item->get_quantity();
							        break;
							    }
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

		$disable_week_days_category = [];
		$offdays_settings = get_option('coderockz_woo_delivery_off_days_settings');
		$category_wise_offdays = (isset($offdays_settings['category_wise_offdays']) && !empty($offdays_settings['category_wise_offdays'])) ? $offdays_settings['category_wise_offdays'] : array();
		
		if(!empty($category_wise_offdays)) {
			$order_product_categories = $this->helper->order_product_categories($order_items);

			foreach ($category_wise_offdays as $key => $value)
			{
				if(in_array(strtolower($key), $order_product_categories))
				{
					foreach($value as $off_day) {
						$disable_week_days_category[] = $off_day;
					}
				}
			}
		}

		$disable_week_days_category = array_unique($disable_week_days_category, false);
		$disable_week_days_category = array_values($disable_week_days_category);

		$disable_week_days_category = implode(",",$disable_week_days_category);

		$disable_week_days_product = [];
		$offdays_settings = get_option('coderockz_woo_delivery_off_days_settings');
		$product_wise_offdays = (isset($offdays_settings['product_wise_offdays']) && !empty($offdays_settings['product_wise_offdays'])) ? $offdays_settings['product_wise_offdays'] : array();
		
		if(!empty($product_wise_offdays)) {
			$product_id = $this->helper->order_product_id($order_items);

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

		$disable_week_days_product = implode(",",$disable_week_days_product);

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

		$disable_timeslot_with_processing_time = (isset($processing_time_settings['disable_timeslot_with_processing_time']) && !empty($processing_time_settings['disable_timeslot_with_processing_time'])) ? $processing_time_settings['disable_timeslot_with_processing_time'] : false;

		

		$off_day_dates = [];
		$selectable_start_date = date('Y-m-d H:i:s', time());
		$start_date = new DateTime($selectable_start_date);
		if(count($off_days)) {
			$date = $start_date;
			foreach ($off_days as $year => $months) {
				foreach($months as $month =>$days){
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

			$extended_closing_days = (isset($delivery_time_settings['different_extended_closing_day'][$current_week_day]) && $delivery_time_settings['different_extended_closing_day'][$current_week_day] != "") ? (int)$delivery_time_settings['different_extended_closing_day'][$current_week_day] : 0;

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

		} elseif($enable_closing_time) {
			$store_closing_time = (isset($delivery_time_settings['store_closing_time']) && $delivery_time_settings['store_closing_time'] != "") ? (int)$delivery_time_settings['store_closing_time'] : "";
			$current_time = (date("G")*60)+date("i");

			$extended_closing_days = (isset($delivery_time_settings['extended_closing_days']) && !empty($delivery_time_settings['extended_closing_days'])) ? (int)$delivery_time_settings['extended_closing_days'] : 0;

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

		if(metadata_exists('post', $post->ID, 'delivery_type')) {
	    	$delivery_type = get_post_meta(  $post->ID, 'delivery_type', true );
	    } else {
	    	$delivery_type="delivery";
	    }


		$enable_delivery_time = (isset($delivery_time_settings['enable_delivery_time']) && !empty($delivery_time_settings['enable_delivery_time'])) ? $delivery_time_settings['enable_delivery_time'] : false;
		
		$enable_pickup_time = (isset($pickup_time_settings['enable_pickup_time']) && !empty($pickup_time_settings['enable_pickup_time'])) ? $pickup_time_settings['enable_pickup_time'] : false;

	    $delivery_date_format = (isset($delivery_date_settings['date_format']) && $delivery_date_settings['date_format'] != "") ? $delivery_date_settings['date_format'] : "F j, Y";
	    if(metadata_exists('post', $post->ID, 'delivery_date')) {
	    	$delivery_date = $this->helper->date_conversion_to_locale(date($delivery_date_format, strtotime(get_post_meta( $post->ID, 'delivery_date', true ))),"delivery");
	    } else {
	    	$delivery_date="";
	    }

	    $pickup_date_format = (isset($pickup_date_settings['date_format']) && $pickup_date_settings['date_format'] != "") ? $pickup_date_settings['date_format'] : "F j, Y";
	    if(metadata_exists('post', $post->ID, 'pickup_date')) {
	    	$pickup_date = $this->helper->date_conversion_to_locale(date($pickup_date_format, strtotime(get_post_meta( $post->ID, 'pickup_date', true ))),"pickup");
	    } else {
	    	$pickup_date="";
	    }

	    

	    $time_options = Coderockz_Woo_Delivery_Time_Option::delivery_time_option($delivery_time_settings,"meta_box");
	    $pickup_options = Coderockz_Woo_Delivery_Pickup_Option::pickup_time_option($pickup_time_settings,"meta_box");

	    if(metadata_exists('post', $post->ID, 'delivery_time')) {
	    	$time = get_post_meta($post->ID,"delivery_time",true);
	    } else {
	    	$time="";
	    }

	    if(metadata_exists('post', $post->ID, 'pickup_time')) {
	    	$pickup_time = get_post_meta($post->ID,"pickup_time",true);
	    } else {
	    	$pickup_time="";
	    }

	    $pickup_location_settings = get_option('coderockz_woo_delivery_pickup_location_settings');

		$enable_pickup_location = (isset($pickup_location_settings['enable_pickup_location']) && !empty($pickup_location_settings['enable_pickup_location'])) ? $pickup_location_settings['enable_pickup_location'] : false;
		$pickup_location_options = Coderockz_Woo_Delivery_Pickup_Location_Option::pickup_location_option($pickup_location_settings,"meta_box");
		if(metadata_exists('post', $post->ID, 'delivery_pickup')) {
			$location = get_post_meta($post->ID, 'delivery_pickup', true);
		} else {
			$location="";
		}


		$additional_field_settings = get_option('coderockz_woo_delivery_additional_field_settings');

		$enable_additional_field = (isset($additional_field_settings['enable_additional_field']) && !empty($additional_field_settings['enable_additional_field'])) ? $additional_field_settings['enable_additional_field'] : false;

		$additional_field_character_limit = (isset($additional_field_settings['character_limit']) && !empty($additional_field_settings['character_limit'])) ? $additional_field_settings['character_limit'] : "";

		if(metadata_exists('post', $post->ID, 'additional_note')) {
			$special_note = get_post_meta($post->ID, 'additional_note', true);
		} else {
			$special_note = "";
		}

		if(metadata_exists('post', $post->ID, 'delivery_type')) {
			$delivery_complete_btn_text = ucfirst(get_post_meta($post->ID, 'delivery_type', true));
		} else {
			$delivery_complete_btn_text = "Delivery";
		}

		$order_limit_notice = (isset(get_option('coderockz_woo_delivery_localization_settings')['order_limit_notice']) && !empty(get_option('coderockz_woo_delivery_localization_settings')['order_limit_notice'])) ? "(".stripslashes(get_option('coderockz_woo_delivery_localization_settings')['order_limit_notice']).")" : "(Maximum Order Limit Exceed)";
		$pickup_limit_notice = (isset(get_option('coderockz_woo_delivery_localization_settings')['pickup_limit_notice']) && !empty(get_option('coderockz_woo_delivery_localization_settings')['pickup_limit_notice'])) ? "(".stripslashes(get_option('coderockz_woo_delivery_localization_settings')['pickup_limit_notice']).")" : "(Maximum Pickup Limit Exceed)";

		$delivery_option_settings = get_option('coderockz_woo_delivery_option_delivery_settings');
		$delivery_field_label = (isset($delivery_option_settings['delivery_label']) && !empty($delivery_option_settings['delivery_label'])) ? $delivery_option_settings['delivery_label'] : "Delivery";
		$pickup_field_label = (isset($delivery_option_settings['pickup_label']) && !empty($delivery_option_settings['pickup_label'])) ? $delivery_option_settings['pickup_label'] : "Pickup";

        $meta_box = '<input type="hidden" id="coderockz_woo_delivery_meta_box_order_id" value="' . $post->ID . '" readonly>';
        $meta_box .= '<select style="width:100%;margin:5px auto;" name ="coderockz_woo_delivery_meta_box_delivery_selection_field" id="coderockz_woo_delivery_meta_box_delivery_selection_field">';
    		$delivery_options = ['delivery' => $delivery_field_label,'pickup' => $pickup_field_label];
    		foreach($delivery_options as $key => $value) {
    			$selected = ($key == $delivery_type) ? "selected" : "";
    			$meta_box .= '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
    		}
    		$meta_box .= '</select>';
        
        if($enable_delivery_date) {

        	$delivery_date_field_label = (isset($delivery_date_settings['field_label']) && !empty($delivery_date_settings['field_label'])) ? stripslashes($delivery_date_settings['field_label']) : "Delivery Date";
			

        	$delivery_days = isset($delivery_date_settings['delivery_days']) && $delivery_date_settings['delivery_days'] != "" ? $delivery_date_settings['delivery_days'] : "6,0,1,2,3,4,5";

        	$delivery_date_calendar_locale = (isset($delivery_date_settings['calendar_locale']) && !empty($delivery_date_settings['calendar_locale'])) ? $delivery_date_settings['calendar_locale'] : "default";
			$week_starts_from = (isset($delivery_date_settings['week_starts_from']) && !empty($delivery_date_settings['week_starts_from']))?$delivery_date_settings['week_starts_from']:"0";
		
			$selectable_date = (isset($delivery_date_settings['selectable_date']) && !empty($delivery_date_settings['selectable_date']))?$delivery_date_settings['selectable_date']:365;

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

			$disable_week_days = implode(",",$disable_week_days);
		    $disable_dates = implode("::",$disable_dates);

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

			$special_open_days_dates = implode("::",$special_open_days_dates);

	        $meta_box .= '<input style="width:100%;margin:5px auto;display:none" type="text" id="coderockz_woo_delivery_meta_box_datepicker" placeholder="'.$delivery_date_field_label.'" name="coderockz_woo_delivery_meta_box_datepicker" data-special_open_days_dates="'.$special_open_days_dates.'" data-same_day_delivery="'.$same_day_delivery.'" data-calendar_locale="'.$delivery_date_calendar_locale.'" data-disable_dates="'.$disable_dates.'" data-selectable_dates="'.$selectable_date.'" data-disable_week_days="'.$disable_week_days.'" data-disable_week_days_category="'.$disable_week_days_category.'" data-disable_week_days_product="'.$disable_week_days_product.'" data-week_starts_from="'.$week_starts_from.'" data-date_format="'.$delivery_date_format.'" value="' . $delivery_date . '">';
    	}

    	if($enable_pickup_date) {

    		$pickup_date_field_label = (isset($pickup_date_settings['pickup_field_label']) && !empty($pickup_date_settings['pickup_field_label'])) ? stripslashes($pickup_date_settings['pickup_field_label']) : "Pickup Date";

    		$pickup_days = isset($pickup_date_settings['pickup_days']) && $pickup_date_settings['pickup_days'] != "" ? $pickup_date_settings['pickup_days'] : "6,0,1,2,3,4,5";

    		$pickup_date_calendar_locale = (isset($pickup_date_settings['calendar_locale']) && !empty($pickup_date_settings['calendar_locale'])) ? $pickup_date_settings['calendar_locale'] : "default";
			$pickup_week_starts_from = (isset($pickup_date_settings['week_starts_from']) && !empty($pickup_date_settings['week_starts_from']))?$pickup_date_settings['week_starts_from']:"0";
		
			$pickup_selectable_date = (isset($pickup_date_settings['selectable_date']) && !empty($pickup_date_settings['selectable_date']))?$pickup_date_settings['selectable_date']:365;

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

			$pickup_disable_week_days = implode(",",$pickup_disable_week_days);
		    $pickup_disable_dates = implode("::",$pickup_disable_dates);

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

			$special_open_days_dates_pickup = implode("::",$special_open_days_dates_pickup);

	        $meta_box .= '<input style="width:100%;margin:5px auto;display:none" type="text" id="coderockz_woo_delivery_meta_box_pickup_datepicker" placeholder="'.$pickup_date_field_label.'" name="coderockz_woo_delivery_meta_box_pickup_datepicker" data-special_open_days_dates_pickup="'.$special_open_days_dates_pickup.'" data-same_day_pickup="'.$same_day_pickup.'" data-pickup_calendar_locale="'.$pickup_date_calendar_locale.'" data-pickup_disable_dates="'.$pickup_disable_dates.'" data-pickup_selectable_dates="'.$pickup_selectable_date.'" data-pickup_disable_week_days="'.$pickup_disable_week_days.'" data-pickup_disable_week_days_category="'.$disable_week_days_category.'" data-pickup_disable_week_days_product="'.$disable_week_days_product.'" data-pickup_week_starts_from="'.$pickup_week_starts_from.'" data-pickup_date_format="'.$pickup_date_format.'" value="' . $pickup_date . '">';
    	}


    	if($enable_delivery_time) {
    		$meta_box .= '<select style="width:100%;margin:5px auto;display:none" name ="coderockz_woo_delivery_meta_box_time_field" id="coderockz_woo_delivery_meta_box_time_field" data-order_limit_notice="'.$order_limit_notice.'" data-max_processing_time="'.$max_processing_time.'" data-disable_timeslot_with_processing_time="'.$disable_timeslot_with_processing_time.'">';
    		$meta_box .= '<option value="" disabled="disabled" selected>Select Time Slot</option>';
    		foreach($time_options as $key => $value) {
    			$selected = ($key == $time) ? "selected" : "";
    			$meta_box .= '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
    		}
    		$meta_box .= '</select>';
    	}

    	if($enable_pickup_time) {
    		$meta_box .= '<select style="width:100%;margin:5px auto;display:none" name ="coderockz_woo_delivery_meta_box_pickup_field" id="coderockz_woo_delivery_meta_box_pickup_field" data-pickup_limit_notice="'.$pickup_limit_notice.'" data-max_processing_time="'.$max_processing_time.'" data-disable_timeslot_with_processing_time="'.$disable_timeslot_with_processing_time.'">';
    		$meta_box .= '<option value="" disabled="disabled" selected>Select Pickup Slot</option>';
    		foreach($pickup_options as $key => $value) {
    			$selected = ($key == $pickup_time) ? "selected" : "";
    			$meta_box .= '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
    		}
    		$meta_box .= '</select>';
    	}

    	if($enable_pickup_location) {
    		$meta_box .= '<select style="width:100%;margin:5px auto;display:none" name="coderockz_woo_delivery_pickup_location_field" id="coderockz_woo_delivery_pickup_location_field">';
    		$meta_box .= '<option disabled="disabled" selected>Select Pickup Location</option>';
    		foreach($pickup_location_options as $key => $value) {
    			$selected = ($key == $location) ? "selected" : "";
    			$meta_box .= '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
    		}
    		$meta_box .= '</select>';
    	}
    	if($enable_additional_field) {
    		$meta_box .= '<div class="coderockz-woo-delivery-metabox-additional-field"><textarea style="width:100%;margin:5px auto;" maxlength="'.$additional_field_character_limit.'" data-character_limit="'.$additional_field_character_limit.'" id="coderockz_woo_delivery_meta_box_additional_field_field">'.$special_note.'</textarea></div>';
    	}

    	$meta_box .= '<div class="coderockz-woo-delivery-metabox-update-section" data-plugin-url="'.CODEROCKZ_WOO_DELIVERY_URL.'">';
        $meta_box .= '<a class="coderockz-woo-delivery-metabox-update-btn" href="#" style="margin-right:10px"><button type="button" class="button button-primary">Update</button></a>';
        $meta_box .= '<a class="coderockz-woo-delivery-metabox-update-btn" data-notify="yes" href="#" style="margin-right:10px"><button type="button" class="button button-primary">Update & Notify</button></a>';
        if(metadata_exists('post', $post->ID, 'delivery_type')) {
			if(get_post_meta($post->ID, 'delivery_status', true) == "delivered") {
				$meta_box .= '<a class="coderockz-woo-delivery-metabox-delivery-complete-btn" href="#"><button type="button" class="button" disabled>'.$delivery_complete_btn_text.' Completed</button></a>';
			} else {
				$meta_box .= '<a class="coderockz-woo-delivery-metabox-delivery-complete-btn" href="#"><button type="button" class="button button-secondary">Mark '.$delivery_complete_btn_text.' As Completed</button></a>';
			}
		} else {
			if(get_post_meta($post->ID, 'delivery_status', true) == "delivered") {
				$meta_box .= '<a class="coderockz-woo-delivery-metabox-delivery-complete-btn" href="#"><button type="button" class="button" disabled>'.$delivery_complete_btn_text.' Completed</button></a>';
			} else {
				$meta_box .= '<a class="coderockz-woo-delivery-metabox-delivery-complete-btn" href="#"><button type="button" class="button button-secondary">Mark '.$delivery_complete_btn_text.' As Completed</button></a>';
			}
		}
        
        $meta_box .= '</div>';
        echo $meta_box;
        
	}

	public function coderockz_woo_delivery_save_meta_box_information() {
		check_ajax_referer('coderockz_woo_delivery_nonce');
		$delivery_time_settings = get_option('coderockz_woo_delivery_time_settings');
		// if any timezone data is saved, set default timezone with the data
		$timezone = $this->helper->get_the_timezone();
		date_default_timezone_set($timezone);

		$order_id = sanitize_text_field($_POST['orderId']);

		if($_POST['deliveryOption'] == "delivery") {

			if(isset($_POST['date'])) {
				$en_date = $this->helper->date_conversion(sanitize_text_field($_POST['date']),"delivery");
				update_post_meta( $order_id, 'delivery_date', date("Y-m-d", strtotime($en_date)) );
				delete_post_meta($order_id, 'pickup_date');
			}

			if(isset($_POST['time'])) {
				$time = sanitize_text_field($_POST['time']);
				update_post_meta( $order_id, 'delivery_time', $time );
				update_post_meta( $order_id, 'delivery_type', 'delivery' );
				delete_post_meta($order_id, 'pickup_time');
				delete_post_meta($order_id, 'delivery_pickup');
			}
		} elseif($_POST['deliveryOption'] == "pickup") {

			if(isset($_POST['pickupDate'])) {
				$en_date = $this->helper->date_conversion(sanitize_text_field($_POST['pickupDate']),"pickup");
				update_post_meta( $order_id, 'pickup_date', date("Y-m-d", strtotime($en_date)) );
				delete_post_meta($order_id, 'delivery_date');
			}

			if(isset($_POST['pickupTime'])) {
				$pickup_time = sanitize_text_field($_POST['pickupTime']);
				update_post_meta( $order_id, 'pickup_time', $pickup_time );
				update_post_meta( $order_id, 'delivery_type', 'pickup' );
				delete_post_meta($order_id, 'delivery_time');
			}
			if(isset($_POST['pickup'])) {
				$pickup = sanitize_text_field($_POST['pickup']);
				update_post_meta( $order_id, 'delivery_pickup', $pickup );
			}
		}
		
		if(isset($_POST['additional'])) {
			$additional = sanitize_textarea_field($_POST['additional']);
			update_post_meta( $order_id, 'additional_note', $additional );
		}

		$localization_settings = get_option('coderockz_woo_delivery_localization_settings');
		$delivery_fee_text = (isset($localization_settings['delivery_fee_text']) && !empty($localization_settings['delivery_fee_text'])) ? stripslashes($localization_settings['delivery_fee_text']) : "Delivery Time Slot Fee";
		$pickup_fee_text = (isset($localization_settings['pickup_fee_text']) && !empty($localization_settings['pickup_fee_text'])) ? stripslashes($localization_settings['pickup_fee_text']) : "Pickup Slot Fee";
		$sameday_fee_text = (isset($localization_settings['sameday_fee_text']) && !empty($localization_settings['sameday_fee_text'])) ? stripslashes($localization_settings['sameday_fee_text']) : "Same Day Delivery Fee";
		$nextday_fee_text = (isset($localization_settings['nextday_fee_text']) && !empty($localization_settings['nextday_fee_text'])) ? stripslashes($localization_settings['nextday_fee_text']) : "Next Day Delivery Fee";
		$day_after_tomorrow_fee_text = (isset($localization_settings['day_after_tomorrow_fee_text']) && !empty($localization_settings['day_after_tomorrow_fee_text'])) ? stripslashes($localization_settings['day_after_tomorrow_fee_text']) : "Day After Tomorrow Delivery Fee";
		$other_fee_text = (isset($localization_settings['other_fee_text']) && !empty($localization_settings['other_fee_text'])) ? stripslashes($localization_settings['other_fee_text']) : "Other Day Delivery Fee";

		$weekday_fee_text = (isset($localization_settings['weekday_fee_text']) && !empty($localization_settings['weekday_fee_text'])) ? stripslashes($localization_settings['weekday_fee_text']) : "Weekday Delivery Fee";


		// Delete the previous time slot fee and delivery date fee
		$order = wc_get_order( $order_id  );
		foreach( $order->get_items('fee') as $item_id => $item_fee ){
		    if( $item_fee['name'] == $delivery_fee_text || $item_fee['name'] == $pickup_fee_text || $item_fee['name'] == $sameday_fee_text || $item_fee['name'] == $nextday_fee_text || $item_fee['name'] == $day_after_tomorrow_fee_text || $item_fee['name'] == $other_fee_text || $item_fee['name'] == $weekday_fee_text ) {
		    	$order->get_items('fee')[$item_id]->delete();
		    }
		}

		$order = wc_get_order( $order_id );
		$order->calculate_totals();

		// Add new time slot fee if any
	    $fees_settings = get_option('coderockz_woo_delivery_fee_settings');

		$custom_time_slot_settings = get_option('coderockz_woo_delivery_time_slot_settings');
		$enable_custom_time_slot = (isset($custom_time_slot_settings['enable_custom_time_slot']) && !empty($custom_time_slot_settings['enable_custom_time_slot'])) ? $custom_time_slot_settings['enable_custom_time_slot'] : false;


		$custom_pickup_slot_settings = get_option('coderockz_woo_delivery_pickup_slot_settings');
		$enable_custom_pickup_slot = (isset($custom_pickup_slot_settings['enable_custom_pickup_slot']) && !empty($custom_pickup_slot_settings['enable_custom_pickup_slot'])) ? $custom_pickup_slot_settings['enable_custom_pickup_slot'] : false;


		if($_POST['deliveryOption'] == "delivery") {

			if($time != "") {
	        	if(strpos($time, ' - ') !== false) {
	        		$time = explode(' - ', $time);
					$inserted_data_key_array_one = explode(':', $time[0]);
					$inserted_data_key_array_two = explode(':', $time[1]);
					$time = ((int)$inserted_data_key_array_one[0]*60+(int)$inserted_data_key_array_one[1]).' - '.((int)$inserted_data_key_array_two[0]*60+(int)$inserted_data_key_array_two[1]);
					$inserted_data_key_array = explode(" - ",$time);
	        	} else {
	        		$inserted_data_key_array = [];
	        		$inserted_data_key_array_one = explode(':', $time);
	        		$time = ((int)$inserted_data_key_array_one[0]*60+(int)$inserted_data_key_array_one[1]);
	        		$inserted_data_key_array[] = $time;
	        	}
	    		
			}

			if($enable_custom_time_slot) {
				if(isset($custom_time_slot_settings['time_slot']) && count($custom_time_slot_settings['time_slot'])>0){


			  		foreach($custom_time_slot_settings['time_slot'] as $key => $individual_time_slot) {
			  			if($individual_time_slot['enable']) {
				  			$key = preg_replace('/-/', ' - ', $key);
				  			$key_array = explode(" - ",$key);

					    	if(!empty($time) && ($time == $key || ($inserted_data_key_array[0]>= $key_array[0] && $inserted_data_key_array[0] <= $key_array[1]))) {
					    		if($individual_time_slot["fee"] !="") {
					    			
					    			$fee = array('name' => $delivery_fee_text, 'amount' => $individual_time_slot["fee"], 'taxable' => false, 'tax_class' => '', 'tax' => 0, 'tax_data' => array());
									$item = new WC_Order_Item_Fee();
									$item->set_props( array(
									  'name'      => $fee['name'],
									  'tax_class' => $fee['taxable'] ? $fee['tax_class'] : 0,
									  'total'     => $fee['amount'],
									  'total_tax' => $fee['tax'],
									  'taxes'     => array(
									    'total' => $fee['tax_data'],
									  ),
									  'order_id'  => $order_id,
									) );

									$item->save();
									$order->add_item( $item );
					    		}	
						    }
						}
			  		}
			  	}
			} else {
				if(isset($fees_settings['enable_time_slot_fee']) && $fees_settings['enable_time_slot_fee'] && isset($time))
				{
			    	foreach($fees_settings['time_slot_fee'] as $key => $slot_fee)
			    	{
			    		$key = preg_replace('/-/', ' - ', $key);
				    	if($time == $key) {
					    	$fee = array('name' => $delivery_fee_text, 'amount' => $slot_fee, 'taxable' => false, 'tax_class' => '', 'tax' => 0, 'tax_data' => array());
								$item = new WC_Order_Item_Fee();
								$item->set_props( array(
								  'name'      => $fee['name'],
								  'tax_class' => $fee['taxable'] ? $fee['tax_class'] : 0,
								  'total'     => $fee['amount'],
								  'total_tax' => $fee['tax'],
								  'taxes'     => array(
								    'total' => $fee['tax_data'],
								  ),
								  'order_id'  => $order_id,
								) );
								
								$item->save();
								$order->add_item( $item );
					    }
			    	}
				}
			}


			if (isset($fees_settings['enable_delivery_date_fee']) && $fees_settings['enable_delivery_date_fee'] && isset($en_date) && !empty($en_date))
			{
				$today = date('Y-m-d', time());
				$today_dt = new DateTime($today);
				$tomorrow = $today_dt->modify('+1 day')->format("Y-m-d");
				$today_dt = new DateTime($today);
				$day_after_tomorrow = $today_dt->modify('+2 day')->format("Y-m-d");

				if(date("Y-m-d", strtotime($en_date)) == $today)
				{
					if(isset($fees_settings['same_day_fee']))
					{
		    			$fee = $fees_settings['same_day_fee'];
		    			$day = $sameday_fee_text;
					}
				}
				elseif(date('Y-m-d', strtotime($en_date)) == $tomorrow)
				{
					if(isset($fees_settings['next_day_fee']))
					{
		    			$fee = $fees_settings['next_day_fee'];
		    			$day = $nextday_fee_text;
					}
				}
				elseif(date('Y-m-d', strtotime($en_date)) == $day_after_tomorrow)
				{
					if(isset($fees_settings['day_after_tomorrow_fee']))
					{
		    			$fee = $fees_settings['day_after_tomorrow_fee'];
		    			$day = $day_after_tomorrow_fee_text;
					}
				}
				else
				{
					if(isset($fees_settings['other_days_fee']))
					{
		    			$fee = $fees_settings['other_days_fee'];
		    			$day = $other_fee_text;
					}
				}
				if(isset($fee) && $fee != 0)
				{

			    	$fee = array('name' => $day, 'amount' => $fee, 'taxable' => false, 'tax_class' => '', 'tax' => 0, 'tax_data' => array());
					$item = new WC_Order_Item_Fee();
					$item->set_props( array(
					  'name'      => $fee['name'],
					  'tax_class' => $fee['taxable'] ? $fee['tax_class'] : 0,
					  'total'     => $fee['amount'],
					  'total_tax' => $fee['tax'],
					  'taxes'     => array(
					    'total' => $fee['tax_data'],
					  ),
					  'order_id'  => $order_id,
					) );
					
					$item->save();
					$order->add_item( $item );
				}
			}

			if (isset($fees_settings['enable_weekday_wise_delivery_fee']) && $fees_settings['enable_weekday_wise_delivery_fee'] && isset($en_date) && !empty($en_date))
			{
				$current_week_day = date("w",strtotime($en_date));

				$week_day_fee = (isset($fees_settings['weekday_wise_delivery_fee'][$current_week_day]) && $fees_settings['weekday_wise_delivery_fee'][$current_week_day] != "") ? (int)$fees_settings['weekday_wise_delivery_fee'][$current_week_day] : "";

				if( $week_day_fee != "" && $week_day_fee != 0 )
				{
			    	$fee = array('name' => $weekday_fee_text, 'amount' => $week_day_fee, 'taxable' => false, 'tax_class' => '', 'tax' => 0, 'tax_data' => array());
					$item = new WC_Order_Item_Fee();
					$item->set_props( array(
					  'name'      => $fee['name'],
					  'tax_class' => $fee['taxable'] ? $fee['tax_class'] : 0,
					  'total'     => $fee['amount'],
					  'total_tax' => $fee['tax'],
					  'taxes'     => array(
					    'total' => $fee['tax_data'],
					  ),
					  'order_id'  => $order_id,
					) );
					
					$item->save();
					$order->add_item( $item ); 
				}
			}

		} 

		if ($_POST['deliveryOption'] == "pickup") {

			if($pickup_time != "") {
	        	if(strpos($pickup_time, ' - ') !== false) {
	        		$pickup_time = explode(' - ', $pickup_time);
					$inserted_data_key_array_one = explode(':', $pickup_time[0]);
					$inserted_data_key_array_two = explode(':', $pickup_time[1]);
					$pickup_time = ((int)$inserted_data_key_array_one[0]*60+(int)$inserted_data_key_array_one[1]).' - '.((int)$inserted_data_key_array_two[0]*60+(int)$inserted_data_key_array_two[1]);
					$inserted_data_key_array = explode(" - ",$pickup_time);
	        	} else {
	        		$inserted_data_key_array = [];
	        		$inserted_data_key_array_one = explode(':', $pickup_time);
	        		$pickup_time = ((int)$inserted_data_key_array_one[0]*60+(int)$inserted_data_key_array_one[1]);
	        		$inserted_data_key_array[] = $pickup_time;
	        	}
	    		
			}

			if($enable_custom_pickup_slot) {
				if(isset($custom_pickup_slot_settings['time_slot']) && count($custom_pickup_slot_settings['time_slot'])>0){

					
			  		foreach($custom_pickup_slot_settings['time_slot'] as $key => $individual_pickup_slot) {
			  			if($individual_pickup_slot['enable']) {
				  			$key = preg_replace('/-/', ' - ', $key);
				  			
				  			$key_array = explode(" - ",$key);

					    	if(!empty($pickup_time) && ($pickup_time == $key || ($inserted_data_key_array[0]>= $key_array[0] && $inserted_data_key_array[0] <= $key_array[1]))) {
					    		if($individual_pickup_slot["fee"] !="") {

					    			$fee = array('name' => $pickup_fee_text, 'amount' => $individual_pickup_slot["fee"], 'taxable' => false, 'tax_class' => '', 'tax' => 0, 'tax_data' => array());
									$item = new WC_Order_Item_Fee();
									$item->set_props( array(
									  'name'      => $fee['name'],
									  'tax_class' => $fee['taxable'] ? $fee['tax_class'] : 0,
									  'total'     => $fee['amount'],
									  'total_tax' => $fee['tax'],
									  'taxes'     => array(
									    'total' => $fee['tax_data'],
									  ),
									  'order_id'  => $order_id,
									) );

									$item->save();
									$order->add_item( $item );
					    		}	
						    }
						}
			  		}
			  	}
			} else {
				if(isset($fees_settings['enable_pickup_slot_fee']) && $fees_settings['enable_pickup_slot_fee'] && isset($pickup_time))
				{
			    	foreach($fees_settings['pickup_slot_fee'] as $key => $slot_fee)
			    	{
			    		$key = preg_replace('/-/', ' - ', $key);
				    	if($pickup_time == $key) {
					    	$fee = array('name' => $pickup_fee_text, 'amount' => $slot_fee, 'taxable' => false, 'tax_class' => '', 'tax' => 0, 'tax_data' => array());
								$item = new WC_Order_Item_Fee();
								$item->set_props( array(
								  'name'      => $fee['name'],
								  'tax_class' => $fee['taxable'] ? $fee['tax_class'] : 0,
								  'total'     => $fee['amount'],
								  'total_tax' => $fee['tax'],
								  'taxes'     => array(
								    'total' => $fee['tax_data'],
								  ),
								  'order_id'  => $order_id,
								) );
								
								$item->save();
								$order->add_item( $item );
					    }
			    	}
				}
			}
		}

		$order = wc_get_order( $order_id );
		$order->calculate_totals();
		
		if(sanitize_textarea_field($_POST['notify']) == "yes") {
			$order = wc_get_order( $order_id );
        	
        	$order_email = $order->get_billing_email();

	        $shipping_address = $order->get_formatted_shipping_address();
	        $billing_address = $order->get_formatted_billing_address();
	        $payment_method = $order->get_payment_method_title();
	        $shipping_method = $order->get_shipping_method();

	        $delivery_date_settings = get_option('coderockz_woo_delivery_date_settings');
	        $pickup_date_settings = get_option('coderockz_woo_delivery_pickup_date_settings');
        	$delivery_pickup_settings = get_option('coderockz_woo_delivery_pickup_location_settings');
        	$delivery_time_settings = get_option('coderockz_woo_delivery_time_settings');
        	$pickup_time_settings = get_option('coderockz_woo_delivery_pickup_settings');
        	$pickup_location_settings = get_option('coderockz_woo_delivery_pickup_location_settings');
        	$additional_field_settings = get_option('coderockz_woo_delivery_additional_field_settings');

        	$delivery_date_field_label = (isset($delivery_date_settings['field_label']) && !empty($delivery_date_settings['field_label'])) ? stripslashes($delivery_date_settings['field_label']) : "Delivery Date";
        	$pickup_date_field_label = (isset($pickup_date_settings['pickup_field_label']) && !empty($pickup_date_settings['pickup_field_label'])) ? stripslashes($pickup_date_settings['pickup_field_label']) : "Pickup Date";
        	$delivery_time_field_label = (isset($delivery_time_settings['field_label']) && !empty($delivery_time_settings['field_label'])) ? stripslashes($delivery_time_settings['field_label']) : "Delivery Time";
        	$pickup_time_field_label = (isset($pickup_time_settings['field_label']) && !empty($pickup_time_settings['field_label'])) ? stripslashes($pickup_time_settings['field_label']) : "Pickup Time";
        	$pickup_location_field_label = (isset($pickup_location_settings['field_label']) && !empty($pickup_location_settings['field_label'])) ? stripslashes($pickup_location_settings['field_label']) : "Pickup Location";
        	$additional_field_field_label = (isset($additional_field_settings['field_label']) && !empty($additional_field_settings['field_label'])) ? stripslashes($additional_field_settings['field_label']) : "Special Note About Delivery";

	        $delivery_date_format = (isset($delivery_date_settings['date_format']) && !empty($delivery_date_settings['date_format'])) ? $delivery_date_settings['date_format'] : "F j, Y";
	        $pickup_date_format = (isset($pickup_date_settings['date_format']) && !empty($pickup_date_settings['date_format'])) ? $pickup_date_settings['date_format'] : "F j, Y";

	        $time_format = (isset($delivery_time_settings['time_format']) && !empty($delivery_time_settings['time_format']))?$delivery_time_settings['time_format']:"12";
	        if($time_format == 12) {
	            $time_format = "h:i A";
	        } elseif ($time_format == 24) {
	            $time_format = "H:i";
	        }

	        $pickup_format = (isset($pickup_time_settings['time_format']) && !empty($pickup_time_settings['time_format']))?$pickup_time_settings['time_format']:"12";
	        if($pickup_format == 12) {
	            $pickup_format = "h:i A";
	        } elseif ($pickup_format == 24) {
	            $pickup_format = "H:i";
	        }

	        $notify_email_settings = get_option('coderockz_woo_delivery_notify_email_settings');
	        $delivery_subject = (isset($notify_email_settings['notify_delivery_email_subject']) && $notify_email_settings['notify_delivery_email_subject'] !="") ? stripslashes($notify_email_settings['notify_delivery_email_subject']) : get_bloginfo('name')."-Your Order Information Is Changed!";
        	$pickup_subject = (isset($notify_email_settings['notify_pickup_email_subject']) && $notify_email_settings['notify_pickup_email_subject'] !="") ? stripslashes($notify_email_settings['notify_pickup_email_subject']) : get_bloginfo('name')."-Your Pickup Information Is Changed!";
	        $pickup_notify_email_heading = (isset($notify_email_settings['pickup_notify_email_heading']) && $notify_email_settings['pickup_notify_email_heading'] !="") ? stripslashes($notify_email_settings['pickup_notify_email_heading']) : "Your Pickup Information is Changed!";
	        $delivery_notify_email_heading = (isset($notify_email_settings['delivery_notify_email_heading']) && $notify_email_settings['delivery_notify_email_heading'] !="") ? stripslashes($notify_email_settings['delivery_notify_email_heading']) : "Your Delivery Information is Changed!";


	        $notify_email_product_text = (isset($notify_email_settings['notify_email_product_text']) && $notify_email_settings['notify_email_product_text'] !="") ? stripslashes($notify_email_settings['notify_email_product_text']) : "Product";
	        $notify_email_quantity_text = (isset($notify_email_settings['notify_email_quantity_text']) && $notify_email_settings['notify_email_quantity_text'] !="") ? stripslashes($notify_email_settings['notify_email_quantity_text']) : "Quantity";
	        $notify_email_price_text = (isset($notify_email_settings['notify_email_price_text']) && $notify_email_settings['notify_email_price_text'] !="") ? stripslashes($notify_email_settings['notify_email_price_text']) : "Price";
	        $notify_email_shipping_text = (isset($notify_email_settings['notify_email_shipping_text']) && $notify_email_settings['notify_email_shipping_text'] !="") ? stripslashes($notify_email_settings['notify_email_shipping_text']) : "Shipping Method";
	        $notify_email_payment_text = (isset($notify_email_settings['notify_email_payment_text']) && $notify_email_settings['notify_email_payment_text'] !="") ? stripslashes($notify_email_settings['notify_email_payment_text']) : "Payment Method";
	        $notify_email_total_text = (isset($notify_email_settings['notify_email_total_text']) && $notify_email_settings['notify_email_total_text'] !="") ? stripslashes($notify_email_settings['notify_email_total_text']) : "Total";
	        $notify_email_billing_address_heading = (isset($notify_email_settings['notify_email_billing_address_heading']) && $notify_email_settings['notify_email_billing_address_heading'] !="") ? stripslashes($notify_email_settings['notify_email_billing_address_heading']) : "Billing Address";
	        $notify_email_shipping_address_heading = (isset($notify_email_settings['notify_email_shipping_address_heading']) && $notify_email_settings['notify_email_shipping_address_heading'] !="") ? stripslashes($notify_email_settings['notify_email_shipping_address_heading']) : "Shipping Address";

	        $delivery_type = metadata_exists('post', $order_id, 'delivery_type') ? get_post_meta($order_id, 'delivery_type', true) : "delivery";

	        
	        if(metadata_exists('post', $order_id, 'delivery_date') && get_post_meta($order_id, 'delivery_date', true) !="") {
	            $date = get_post_meta($order_id,"delivery_date",true);
	            $delivery_date = date($delivery_date_format, strtotime($date));
	        }

	        if(metadata_exists('post', $order_id, 'pickup_date') && get_post_meta($order_id, 'pickup_date', true) !="") {
	            $pickup_date = get_post_meta($order_id,"pickup_date",true);
	            $pickup_date = date($pickup_date_format, strtotime($pickup_date));
	        }

	        if(metadata_exists('post', $order_id, 'delivery_time') && get_post_meta($order_id, 'delivery_time', true) !="") {
	            
	        	if(get_post_meta($order_id, 'delivery_time', true) !="as-soon-as-possible") {
	        		$minutes = get_post_meta($order_id,"delivery_time",true);
		            $minutes = explode(' - ', $minutes);

		    		if(!isset($minutes[1])) {
		    			$delivery_time = date($time_format, strtotime($minutes[0]));
		    		} else {

		    			$delivery_time = date($time_format, strtotime($minutes[0])) . ' - ' . date($time_format, strtotime($minutes[1]));
		    		}
	        	} else {
	        		$as_soon_as_possible_text = (isset($delivery_time_settings['as_soon_as_possible_text']) && !empty($delivery_time_settings['as_soon_as_possible_text'])) ? stripslashes($delivery_time_settings['as_soon_as_possible_text']) : "As Soon As Possible";
	        		$delivery_time = $as_soon_as_possible_text;
	        	}
	            

	        }

	        if(metadata_exists('post', $order_id, 'pickup_time') && get_post_meta($order_id, 'pickup_time', true) !="") {
	            $pickup_minutes = get_post_meta($order_id,"pickup_time",true);
	            $pickup_minutes = explode(' - ', $pickup_minutes);

	    		if(!isset($pickup_minutes[1])) {
	    			$pickup_time = date($pickup_format, strtotime($pickup_minutes[0]));
	    		} else {
	    			$pickup_time = date($pickup_format, strtotime($pickup_minutes[0])) . ' - ' . date($pickup_format, strtotime($pickup_minutes[1]));
	    		}

	        }

	        if(metadata_exists('post', $order_id, 'delivery_pickup') && get_post_meta($order_id, 'delivery_pickup', true) !="") {
	            $pickup_location = get_post_meta($order_id, 'delivery_pickup', true);
	        }

	        if(metadata_exists('post', $order_id, 'additional_note') && get_post_meta($order_id, 'additional_note', true) !="") {
	            $additional_note = get_post_meta($order_id, 'additional_note', true);
	        }
	        

	        if($delivery_type == "pickup") {
	            $subject = $pickup_subject;
	            $email_heading = $pickup_notify_email_heading;
	        } else {
	            $subject = $delivery_subject;
	            $email_heading = $delivery_notify_email_heading;
	             
	        }

	        $currency_symbol = get_woocommerce_currency_symbol();

			$email = Coderockz_Woo_Delivery_Email::init()
	        ->to($order_email)
	        ->subject($subject)
	        ->template(CODEROCKZ_WOO_DELIVERY_DIR .'admin/includes/notify_email_template.php', [
	            'order_id' => $order_id,
	            'delivery_type' => $delivery_type,
	            'email_heading' => $email_heading,
            	'notify_email_product_text' => $notify_email_product_text,
            	'notify_email_quantity_text' => $notify_email_quantity_text,
            	'notify_email_price_text' => $notify_email_price_text,
            	'notify_email_shipping_text' => $notify_email_shipping_text,
            	'notify_email_payment_text' => $notify_email_payment_text,
            	'notify_email_total_text' => $notify_email_total_text,
            	'notify_email_billing_address_heading' => $notify_email_billing_address_heading,
            	'notify_email_shipping_address_heading' => $notify_email_shipping_address_heading,
            	'delivery_date_field_label' =>$delivery_date_field_label,
            	'pickup_date_field_label' =>$pickup_date_field_label,
            	'delivery_time_field_label' =>$delivery_time_field_label,
            	'pickup_time_field_label' =>$pickup_time_field_label,
            	'pickup_location_field_label' =>$pickup_location_field_label,
            	'additional_field_field_label' =>$additional_field_field_label,
	            'delivery_date' => isset($delivery_date)?$delivery_date:"",
	            'delivery_time' => isset($delivery_time)?$delivery_time:"",
	            'pickup_date' => isset($pickup_date)?$pickup_date:"",
	            'pickup_time' => isset($pickup_time)?$pickup_time:"",
	            'pickup_location' => isset($pickup_location)?$pickup_location:"",
	            'additional_note' => isset($additional_note)?$additional_note:"",
	            'order_total' => $order->get_formatted_order_total(),
	            'billing_address' => $billing_address,
	            'shipping_address' => $billing_address,
	            'shipping_method' => $shipping_method,
	            'payment_method' => $payment_method,
	            'order' => $order,
                'currency_symbol' => $currency_symbol,
                'order_email' => $order_email
	        ])
	        ->send();
		}

		wp_send_json_success();
	}

	public function coderockz_woo_delivery_meta_box_get_orders() {

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

	    $current_order_for_state_zip = wc_get_order(sanitize_text_field($_POST['orderId']));
	    if(!is_null($current_order_for_state_zip) && $current_order_for_state_zip != false) {
	    	$current_state = $current_order_for_state_zip->get_shipping_state();
			$current_postcode = $current_order_for_state_zip->get_shipping_postcode();
	    }
	    
		$response_delivery = [];

		$delivery_times = [];
		$max_order_per_slot = [];
		$slot_disable_for = [];
		$disable_timeslot['state'] = [];
		$disable_timeslot['postcode'] = [];
		$state_zip_disable_timeslot_all['state'] = [];
		$state_zip_disable_timeslot_all['postcode'] = [];
		$no_state_zip_disable_timeslot_all['nostatezip'] = [];
		
		if($enable_custom_time_slot && isset($custom_time_slot_settings['time_slot']) && count($custom_time_slot_settings['time_slot'])>0){
	  		foreach($custom_time_slot_settings['time_slot'] as $key => $individual_time_slot) {

	  			if($individual_time_slot['enable']) {
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


					if(isset($individual_time_slot['more_settings']) && !empty($individual_time_slot['more_settings']) && $individual_time_slot['more_settings'] == 'zone') {

						if(isset($individual_time_slot['disable_postcode']) && !empty($individual_time_slot['disable_postcode'])){

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
							    	if($this->helper->starts_with($current_postcode,substr($postcode_value, 0, -1))) {
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
							    } elseif(in_array($current_postcode,$postcode_range) || $postcode_value == $current_postcode) {
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
			  			} else {
			  				if((isset($individual_time_slot['disable_state']) && !empty($individual_time_slot['disable_state']) && in_array($current_state,$individual_time_slot['disable_state']))){
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
			  			}

					} else {


			  			if((isset($individual_time_slot['disable_state']) && !empty($individual_time_slot['disable_state']) && in_array($current_state,$individual_time_slot['disable_state']))){
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

			  			if(isset($individual_time_slot['disable_postcode']) && !empty($individual_time_slot['disable_postcode'])){

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
							    	if($this->helper->starts_with($current_postcode,substr($postcode_value, 0, -1))) {
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
							    } elseif(in_array($current_postcode,$postcode_range) || $postcode_value == $current_postcode) {
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
				if($time !="as-soon-as-possible") {
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

	public function coderockz_woo_delivery_meta_box_get_orders_pickup() {

		check_ajax_referer('coderockz_woo_delivery_nonce');
		
		$delivery_time_settings = get_option('coderockz_woo_delivery_time_settings');
		// if any timezone data is saved, set default timezone with the data
		$timezone = $this->helper->get_the_timezone();
		date_default_timezone_set($timezone);

		$pickup_disabled_current_time_slot = (isset($delivery_pickup_settings['disabled_current_pickup_time_slot']) && !empty($delivery_pickup_settings['disabled_current_pickup_time_slot'])) ? $delivery_pickup_settings['disabled_current_pickup_time_slot'] : false;

		$pickup_location_settings = get_option('coderockz_woo_delivery_pickup_location_settings');
		$enable_pickup_location = (isset($pickup_location_settings['enable_pickup_location']) && !empty($pickup_location_settings['enable_pickup_location'])) ? $pickup_location_settings['enable_pickup_location'] : false;

		$custom_pickup_slot_settings = get_option('coderockz_woo_delivery_pickup_slot_settings');
		$enable_custom_pickup_slot = (isset($custom_pickup_slot_settings['enable_custom_pickup_slot']) && !empty($custom_pickup_slot_settings['enable_custom_pickup_slot'])) ? $custom_pickup_slot_settings['enable_custom_pickup_slot'] : false;
		
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

	    $current_order_for_state_zip = wc_get_order(sanitize_text_field($_POST['orderId']));
	    if(!is_null($current_order_for_state_zip) && $current_order_for_state_zip != false) {
	    	$current_state = $current_order_for_state_zip->get_shipping_state();
			$current_postcode = $current_order_for_state_zip->get_shipping_postcode();
	    }

	    $given_location = (isset($_POST['givenLocation']) && $_POST['givenLocation'] !="") ? sanitize_text_field($_POST['givenLocation']) : "";

		$response_pickup = [];

		$pickup_delivery_times = [];
		$pickup_max_order_per_slot = [];
		$pickup_slot_disable_for = [];
		$pickup_disable_timeslot['state'] = [];
		$pickup_disable_timeslot['postcode'] = [];
		$pickup_state_zip_disable_timeslot_all['state'] = [];
		$pickup_state_zip_disable_timeslot_all['postcode'] = [];
		$pickup_no_state_zip_disable_timeslot_all['nostatezip'] = [];
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


					if(isset($individual_pickup_slot['more_settings']) && !empty($individual_pickup_slot['more_settings']) && $individual_pickup_slot['more_settings'] == 'zone') {
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
							    	if($this->helper->starts_with($current_postcode,substr($postcode_value, 0, -1))) {
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
							    } elseif(in_array($current_postcode,$postcode_range) || $postcode_value == $current_postcode) {
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
			  			} else {
			  				if((isset($individual_pickup_slot['disable_state']) && !empty($individual_pickup_slot['disable_state']) && in_array($current_state,$individual_pickup_slot['disable_state']))){
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
			  			}
					} else {

			  			if((isset($individual_pickup_slot['disable_state']) && !empty($individual_pickup_slot['disable_state']) && in_array($current_state,$individual_pickup_slot['disable_state']))){
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
							    	if($this->helper->starts_with($current_postcode,substr($postcode_value, 0, -1))) {
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
							    } elseif(in_array($current_postcode,$postcode_range) || $postcode_value == $current_postcode) {
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

		  			if((isset($individual_pickup_slot['disable_postcode']) && !empty($individual_pickup_slot['disable_postcode']))){
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

	public function coderockz_woo_delivery_get_state_zip_disable_weekday() {
		$current_order_for_state_zip = wc_get_order($_POST['orderId']);

		if(!is_null($current_order_for_state_zip) && $current_order_for_state_zip != false) {
	    	$current_state = $current_order_for_state_zip->get_shipping_state();
			$current_postcode = $current_order_for_state_zip->get_shipping_postcode();
	    }

		$offdays_settings = get_option('coderockz_woo_delivery_off_days_settings');
		$current_state_offdays = [];
		if(isset($offdays_settings['state_wise_offdays']) && !empty($offdays_settings['state_wise_offdays']) && isset($offdays_settings['state_wise_offdays'][$current_state]) && !empty($offdays_settings['state_wise_offdays'][$current_state])) { 
			$current_state_offdays = $offdays_settings['state_wise_offdays'][$current_state];
		}

		if(isset($offdays_settings['zone_wise_offdays']) && !empty($offdays_settings['zone_wise_offdays'])) {

			foreach($offdays_settings['zone_wise_offdays'] as $zone) {
				if(isset($zone['state']) && !empty($zone['state']) && isset($zone['state'][$current_state])) {
					$off_days = explode(",",$zone['off_days']);
					foreach($off_days as $off_day) {
						$current_state_offdays[] = $off_day;
					}					
				}
			}
		}

		$current_state_offdays = array_unique($current_state_offdays, false);
		$current_state_offdays = array_values($current_state_offdays);

		$current_postcode_offdays = [];
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
			    	if($this->helper->starts_with($current_postcode,substr($key, 0, -1))) {
			    		$current_postcode_offdays = [];
						foreach($off_days as $off_day) {
							$current_postcode_offdays[] = $off_day;
						}
			    	}
			    } elseif(in_array($current_postcode,$individual_postcode_range) || $key == $current_postcode) {
					foreach($off_days as $off_day) {
						$current_postcode_offdays[] = $off_day;
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
					    	if($this->helper->starts_with($current_postcode,substr($postcode_value, 0, -1))) {
					    		$off_days = explode(",",$zone['off_days']);
					    		$current_postcode_offdays = [];
								foreach($off_days as $off_day) {
									$current_postcode_offdays[] = $off_day;
								}
					    	}
					    } elseif(in_array($current_postcode,$postcode_range) || $postcode_value ==$current_postcode) {
					    	$off_days = explode(",",$zone['off_days']);
							foreach($off_days as $off_day) {
								$current_postcode_offdays[] = $off_day;
							}
					    }
					}
										
				}
			}
		}

		$current_postcode_offdays = array_unique($current_postcode_offdays, false);
		$current_postcode_offdays = array_values($current_postcode_offdays);
		$current_state_zip_offdays = array_merge($current_state_offdays,$current_postcode_offdays);
		$response = [
			"current_state_zip_offdays" => $current_state_zip_offdays,
		];
		$response = json_encode($response);
		wp_send_json_success($response);
	}

    public function coderockz_woo_delivery_admin_disable_max_delivery_pickup_date() {
    	$delivery_selection = isset($_POST['deliverySelector']) ? sanitize_text_field($_POST['deliverySelector']) : "delivery";
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
	    
		if($delivery_selection == "delivery" && (isset($delivery_date_settings['maximum_order_per_day']) && $delivery_date_settings['maximum_order_per_day'] != "")) {

			$range_last_date = $formated_obj->modify("+40 day")->format("Y-m-d");
			$filtered_date = $range_first_date . ',' . $range_last_date;
			$filtered_dates = explode(',', $filtered_date);
			$period = new DatePeriod(new DateTime($filtered_dates[0]), new DateInterval('P1D'), new DateTime($filtered_dates[1].' +1 day'));


			$max_order_per_day = (isset($delivery_date_settings['maximum_order_per_day']) && $delivery_date_settings['maximum_order_per_day'] != "") ? (int)$delivery_date_settings['maximum_order_per_day'] : 10000000000000;
			foreach ($period as $date) { 
				$args = array(
			        'limit' => -1,
			        'delivery_date' => date('Y-m-d', strtotime($date->format("Y-m-d"))),
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

		} elseif($delivery_selection == "pickup" && (isset($pickup_date_settings['maximum_pickup_per_day']) && $pickup_date_settings['maximum_pickup_per_day'] != "")) {
			
			$range_last_date = $formated_obj->modify("+40 day")->format("Y-m-d");
			$filtered_date = $range_first_date . ',' . $range_last_date;
			$filtered_dates = explode(',', $filtered_date);
			$period = new DatePeriod(new DateTime($filtered_dates[0]), new DateInterval('P1D'), new DateTime($filtered_dates[1].' +1 day'));

			$max_pickup_per_day = (isset($pickup_date_settings['maximum_pickup_per_day']) && $pickup_date_settings['maximum_pickup_per_day'] != "") ? (int)$pickup_date_settings['maximum_pickup_per_day'] : 10000000000000;
			foreach ($period as $date) {
				$args = array(
			        'limit' => -1,
			        'pickup_date' => date('Y-m-d', strtotime($date->format("Y-m-d"))),
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

	public function custom_order_get_items( $items, $order, $types ) {
		
		if( version_compare( get_option( 'woocommerce_version' ), '3.0.0', ">=" ) ) {            
	        $order_id = $order->get_id();
	    } else {
	        $order_id = $order->id;
	    }

	    if(metadata_exists('post', $order_id, 'delivery_type') && get_post_meta($order_id, 'delivery_type', true) !="") {
	    	$delivery_type = get_post_meta( $order_id, 'delivery_type', true );
	    } else {
	    	$delivery_type = "delivery";
	    }

	    global $pagenow;
		if(( $pagenow == 'post.php' ) || (get_post_type() == 'post')) {
		    if($delivery_type == "pickup") {
		    	if ( is_admin() && $types == array('shipping') ) {
				    $items = array();
				}
		    }
		}
		
		return $items;
	}

	public function override_post_meta_box_order( $order ) {
	    return array(
	        'normal' => join( 
	            ",", 
	            array(       // vvv  Arrange here as you desire
	                'order_data',
	                'coderockz_woo_delivery_meta_box',
	                'woocommerce-order-items',
	            )
	        ),
	    );
	}

	// Function to change email address
 
	public function coderockz_woo_delivery_sender_email( $original_email_address ) {
	    $notify_email_settings = get_option('coderockz_woo_delivery_notify_email_settings');
	    $send_email_from_email = isset($notify_email_settings['send_email_from_email']) && $notify_email_settings['send_email_from_email'] != "" ? $notify_email_settings['send_email_from_email'] : get_option( 'admin_email' );

	    return $send_email_from_email;
	}
	 
	// Function to change sender name
	public function coderockz_woo_delivery_sender_name( $original_email_from ) {
	    $notify_email_settings = get_option('coderockz_woo_delivery_notify_email_settings');
	    $send_email_from_name = isset($notify_email_settings['send_email_from_name']) && $notify_email_settings['send_email_from_name'] != "" ? stripslashes($notify_email_settings['send_email_from_name']) : get_bloginfo( 'name' );

	    return $send_email_from_name;
	}

	public function coderockz_woo_delivery_filter_orders_by_delivery() {

		global $typenow;

		if ( 'shop_order' === $typenow ) {

		$delivery_date_filters = [];
		$delivery_date_filters['Today'] = 'today';
		$delivery_date_filters['Tomorrow'] = 'tomorrow';
		$delivery_date_filters['This Week'] = 'week';
		$delivery_date_filters['This Month'] = 'month';
		$delivery_date_filters['Custom'] = 'custom';

		$delivery_types = [];
		$delivery_types['Delivery'] = 'delivery';
		$delivery_types['Pickup'] = 'pickup';

		?>

		<select name="_delivery_type" id="coderockz_woo_delivery_delivery_type_filter">
			<option value=""></option>
			<?php foreach ( $delivery_types as $label => $delivery_type ) : ?>
				<option value="<?php echo esc_attr( $delivery_type ); ?>" <?php echo esc_attr( isset( $_GET['_delivery_type'] ) ? selected( $delivery_type, $_GET['_delivery_type'], false ) : '' ); ?>>
					<?php echo esc_html( $label ); ?>
				</option>
			<?php endforeach; ?>
		</select>

		<select name="_date_filter" id="coderockz_woo_delivery_delivery_date_filter">
			<option value=""></option>
			<?php foreach ( $delivery_date_filters as $label => $date_filter ) : ?>
				<option value="<?php echo esc_attr( $date_filter ); ?>" <?php echo esc_attr( isset( $_GET['_date_filter'] ) ? selected( $date_filter, $_GET['_date_filter'], false ) : '' ); ?>>
					<?php echo esc_html( $label ); ?>
				</option>
			<?php endforeach; ?>
		</select>

		<input style="width:110px;float:left;display:none" id="coderockz_woo_delivery_custom_start_date_filter" name="coderockz_woo_delivery_custom_start_date_filter" type="text" class="regular-text coderockz_woo_delivery_custom_start_date_filter" value="<?php echo (isset($_GET['coderockz_woo_delivery_custom_start_date_filter']) && $_GET['coderockz_woo_delivery_custom_start_date_filter'] != "") ? stripslashes($_GET['coderockz_woo_delivery_custom_start_date_filter']) : "" ?>" placeholder="YYYY-MM-DD"/>

		<input style="width:110px;float:left;display:none" id="coderockz_woo_delivery_custom_end_date_filter" name="coderockz_woo_delivery_custom_end_date_filter" type="text" class="regular-text coderockz_woo_delivery_custom_end_date_filter" value="<?php echo (isset($_GET['coderockz_woo_delivery_custom_end_date_filter']) && $_GET['coderockz_woo_delivery_custom_end_date_filter'] != "") ? stripslashes($_GET['coderockz_woo_delivery_custom_end_date_filter']) : "" ?>" placeholder="YYYY-MM-DD"/>

		<?php
		}

	}

	/**
	 * Modify SQL JOIN for filtering the orders by any coupons used
	 *
 	 * @since 1.0.0
	 *
	 * @param string $join JOIN part of the sql query
	 * @return string $join modified JOIN part of sql query
	 */
	public function coderockz_woo_delivery_add_order_items_join( $join ) {
		global $typenow, $wpdb;

		if ( 'shop_order' === $typenow && isset( $_GET['_date_filter'] ) && !empty( $_GET['_date_filter'] ) && $_GET['_delivery_type'] == "" ) {

			$join .= "LEFT JOIN {$wpdb->prefix}postmeta wpm ON {$wpdb->posts}.ID = wpm.post_id";
		}

		if ( 'shop_order' === $typenow && isset( $_GET['_delivery_type'] ) && !empty( $_GET['_delivery_type'] ) && $_GET['_date_filter'] == "" ) {

			$join .= "LEFT JOIN {$wpdb->prefix}postmeta wpm ON {$wpdb->posts}.ID = wpm.post_id";
		}

		if ( 'shop_order' === $typenow && ( isset( $_GET['_delivery_type'] ) && !empty( $_GET['_delivery_type'] ) ) && ( isset( $_GET['_date_filter'] ) && !empty( $_GET['_date_filter'] ) ) ) {

			$join .= "LEFT JOIN {$wpdb->prefix}postmeta wpm ON {$wpdb->posts}.ID = wpm.post_id";
		}

		return $join;
	}


	/**
	 * Modify SQL WHERE for filtering the orders by any coupons used
	 *
	 * @since 1.0.0
	 *
	 * @param string $where WHERE part of the sql query
	 * @return string $where modified WHERE part of sql query
	 */
	public function coderockz_woo_delivery_add_filterable_where( $where ) {
		global $typenow, $wpdb;

		if ( 'shop_order' === $typenow && isset( $_GET['_delivery_type'] ) && $_GET['_delivery_type'] !="" ) {

			if($_GET['_delivery_type'] == "delivery" && isset( $_GET['_date_filter'] ) && $_GET['_date_filter'] != "") {

				$where .= $wpdb->prepare( " AND wpm.meta_key='%s'",'delivery_date' );
			}

			if($_GET['_delivery_type'] == "pickup" && isset( $_GET['_date_filter'] ) && $_GET['_date_filter'] != "") {

				$where .= $wpdb->prepare( " AND wpm.meta_key='%s'",'pickup_date' );
			}

			if($_GET['_delivery_type'] == "pickup" && $_GET['_date_filter'] == "" && $_GET['coderockz_woo_delivery_custom_start_date_filter'] == "" && $_GET['coderockz_woo_delivery_custom_end_date_filter'] == "") {

				$where .= $wpdb->prepare( " AND wpm.meta_key='%s'",'delivery_type' );
				$where .= $wpdb->prepare( " AND wpm.meta_value='%s'",'pickup' );
			}

		}

		if ( 'shop_order' === $typenow && isset( $_GET['_date_filter'] ) && $_GET['_date_filter'] != "" ) {


			$timezone = $this->helper->get_the_timezone();
			date_default_timezone_set($timezone);
			$delivery_date_settings = get_option('coderockz_woo_delivery_date_settings');


			if($_GET['_date_filter'] == "week") {
				$week_starts_from = (isset($delivery_date_settings['week_starts_from']) && !empty($delivery_date_settings['week_starts_from'])) ? $delivery_date_settings['week_starts_from']:"0";

				switch ($week_starts_from) {
				    case "0":
				        $week_day = "sunday";
				        break;
				    case "1":
				        $week_day = "monday";
				        break;
				    case "2":
				        $week_day = "tuesday";
				        break;
				    case "3":
				        $week_day = "wednesday";
				        break;
				    case "4":
				        $week_day = "thursday";
				        break;
				    case "5":
				        $week_day = "friday";
				        break;
				    case "6":
				        $week_day = "saturday";
				        break;
				}

				$week_start = strtotime("last ".$week_day);
				$week_start = date('w', $week_start)==date('w') ? $week_start+7*86400 : $week_start;

				$week_end = strtotime(date("Y-m-d",$week_start)." +6 days");

				$this_week_start = date("Y-m-d",$week_start);
				$this_week_end = date("Y-m-d",$week_end);

				$get_date_filter = $this_week_start." - ".$this_week_end;
			}	
			

			if($_GET['_date_filter'] == "month") {
				$day_today = strtotime (date('Y-m-d', time()));
			    $this_month_first_day = date ('Y-m-d', strtotime ('first day of this month', $day_today));
			    $this_month_last_day = date ('Y-m-d', strtotime ('last day of this month', $day_today));

			    $get_date_filter = $this_month_first_day." - ".$this_month_last_day;

			}

			if($_GET['_date_filter'] == "today") {
				$get_date_filter = date('Y-m-d', time());
			}

			if($_GET['_date_filter'] == "tomorrow") {
				$today = date('Y-m-d', time());
				$date_obj = new DateTime($today);
				$get_date_filter = $date_obj->modify("+1 day")->format("Y-m-d");
			}

			
			if($_GET['_date_filter'] == "custom") {
				if((isset($_GET['coderockz_woo_delivery_custom_start_date_filter']) && $_GET['coderockz_woo_delivery_custom_start_date_filter'] !="") && (isset($_GET['coderockz_woo_delivery_custom_end_date_filter']) && $_GET['coderockz_woo_delivery_custom_end_date_filter'] !="")) {
					$get_date_filter = $_GET['coderockz_woo_delivery_custom_start_date_filter'].' - '.$_GET['coderockz_woo_delivery_custom_end_date_filter'];
				}

			}

			// Main WHERE query part
			if(strpos($get_date_filter, ' - ') !== false) {

				$filtered_dates = explode(' - ', $get_date_filter);
				$period = new DatePeriod(new DateTime($filtered_dates[0]), new DateInterval('P1D'), new DateTime($filtered_dates[1].' +1 day'));
			    $query = '';
			    $dates = [];
			    foreach ($period as $date) {
			    	$dates [] = $date->format("Y-m-d");
			    	$query .= "wpm.meta_value='%s' OR ";	   	
			    }

			    $final_query = substr($query, 0, -4);

			    $where .= $wpdb->prepare( " AND (".$final_query.")",$dates);
			    

			} else {
				$where .= $wpdb->prepare( " AND wpm.meta_value='%s'", $get_date_filter );
			}
			
		}

		return $where;
	}

	public function coderockz_woo_delivery_get_order_details_for_delivery_calender() {

		$timezone = $this->helper->get_the_timezone();
		date_default_timezone_set($timezone);

		$delivery_time_settings = get_option('coderockz_woo_delivery_time_settings');
		$pickup_time_settings = get_option('coderockz_woo_delivery_pickup_settings');

		$enable_delivery_time = (isset($delivery_time_settings['enable_delivery_time']) && !empty($delivery_time_settings['enable_delivery_time'])) ? $delivery_time_settings['enable_delivery_time'] : false;
	  	
		$enable_pickup_time = (isset($pickup_time_settings['enable_pickup_time']) && !empty($pickup_time_settings['enable_pickup_time'])) ? $pickup_time_settings['enable_pickup_time'] : false;

		$filtered_delivery_type = sanitize_text_field($_POST[ 'filteredDeliveryType' ]);
		$filtered_filter_type = sanitize_text_field($_POST[ 'filteredFilterType' ]);
		$filtered_status_type = $this->helper->coderockz_woo_delivery_array_sanitize($_POST[ 'filteredStatusType' ]);

		$day_today = strtotime (date('Y-m-d', time()));
	    $this_month_first_day = date ('Y-m-d', strtotime ('first day of this month', $day_today));
	    $this_month_last_day = date ('Y-m-d', strtotime ('last day of this month', $day_today));

	    $this_month = $this_month_first_day." - ".$this_month_last_day;

	    $filtered_dates = explode(' - ', $this_month);
		$orders = [];
		$delivery_orders = [];
		$pickup_orders = [];
		$period = new DatePeriod(new DateTime($filtered_dates[0]), new DateInterval('P1D'), new DateTime($filtered_dates[1].' +1 day'));
	    foreach ($period as $date) {
	        $dates[] = $date->format("Y-m-d");
	    }
	    

		if($filtered_filter_type == 'product') {
			$response_orders_by_quantity = [];
			foreach ($dates as $date) {
				
		    	if($filtered_delivery_type == "delivery"){
		    		$product_name = [];
					$product_quantity = [];
		    		$args = array(
				        'limit' => -1,
				        'delivery_date' => date("Y-m-d", strtotime($date)),
				        'delivery_type' => "delivery",
				        'status' => $filtered_status_type
				    );
				    $orders_array = wc_get_orders( $args );
				    foreach ($orders_array as $order) {

					    foreach ( $order->get_items() as $item_id => $item ) {
						   if($item->get_variation_id() == 0) {
						   		if(array_key_exists($item->get_product_id(),$product_quantity)) {
							   		$product_quantity[$item->get_product_id()] = $product_quantity[$item->get_product_id()]+$item->get_quantity();
							   } else {
							   		$product_quantity[$item->get_product_id()] = $item->get_quantity();
							   }
							   if(!array_key_exists($item->get_product_id(),$product_name)) {
							   		$product_name[$item->get_product_id()] = $item->get_name();
							   }
						   } else {
						   		if(array_key_exists($item->get_variation_id(),$product_quantity)) {
							   		$product_quantity[$item->get_variation_id()] = $product_quantity[$item->get_variation_id()]+$item->get_quantity();
							   } else {
							   		$product_quantity[$item->get_variation_id()] = $item->get_quantity();
							   }
							   if(!array_key_exists($item->get_variation_id(),$product_name)) {
							   		$variation = wc_get_product($item->get_variation_id());
							   		$product_name[$item->get_variation_id()] = strip_tags($variation->get_formatted_name());
							   }
						   }

						}

				    }

				    foreach($product_name as $id => $name) {
				    	$title = '';
				    	$temp_orders_quantity = [];
				        $title .= $name.' x '.$product_quantity[$id];
				        $temp_orders_quantity['start'] = $date;
						$temp_orders_quantity['end'] = $date;
						$temp_orders_quantity['title'] = $title;
						$response_orders_by_quantity[] = $temp_orders_quantity;
					}
				    

		    	} elseif($filtered_delivery_type == "pickup") {
		    		$product_name = [];
					$product_quantity = [];
		    		$args = array(
				        'limit' => -1,
				        'pickup_date' => date("Y-m-d", strtotime($date)),
				        'delivery_type' => "pickup",
				        'status' => $filtered_status_type
				    );
				    $orders_array = wc_get_orders( $args );
				    foreach ($orders_array as $order) {
				    	foreach ( $order->get_items() as $item_id => $item ) {
						   if($item->get_variation_id() == 0) {
						   		if(array_key_exists($item->get_product_id(),$product_quantity)) {
							   		$product_quantity[$item->get_product_id()] = $product_quantity[$item->get_product_id()]+$item->get_quantity();
							   } else {
							   		$product_quantity[$item->get_product_id()] = $item->get_quantity();
							   }
							   if(!array_key_exists($item->get_product_id(),$product_name)) {
							   		$product_name[$item->get_product_id()] = $item->get_name();
							   }
						   } else {
						   		if(array_key_exists($item->get_variation_id(),$product_quantity)) {
							   		$product_quantity[$item->get_variation_id()] = $product_quantity[$item->get_variation_id()]+$item->get_quantity();
							   } else {
							   		$product_quantity[$item->get_variation_id()] = $item->get_quantity();
							   }
							   if(!array_key_exists($item->get_variation_id(),$product_name)) {
							   		$variation = wc_get_product($item->get_variation_id());
							   		$product_name[$item->get_variation_id()] = strip_tags($variation->get_formatted_name());
							   }
						   }

						}
				    }

				    foreach($product_name as $id => $name) {
				    	$title = '';
				    	$temp_orders_quantity = [];
				        $title .= $name.' x '.$product_quantity[$id];
				        $temp_orders_quantity['start'] = $date;
						$temp_orders_quantity['end'] = $date;
						$temp_orders_quantity['title'] = $title;
						$response_orders_by_quantity[] = $temp_orders_quantity;
					}
		    	} else {
		    		$product_name = [];
					$product_quantity = [];
					$delivery_orders = [];
					$pickup_orders = [];
		    		$args = array(
				        'limit' => -1,
				        'delivery_date' => date("Y-m-d", strtotime($date)),
				        'status' => $filtered_status_type
				    );

				    $orders_array = wc_get_orders( $args );
				    foreach ($orders_array as $order) {
				    	$delivery_orders[] = $order;
				    }

				    $args = array(
				        'limit' => -1,
				        'pickup_date' => date("Y-m-d", strtotime($date)),
				        'status' => $filtered_status_type
				    );

				    $orders_array = wc_get_orders( $args );
				    foreach ($orders_array as $order) {
				    	$pickup_orders[] = $order;
				    }

				    $orders = array_merge($delivery_orders, $pickup_orders);
		    	
				    foreach ($orders as $order) {
				    	foreach ( $order->get_items() as $item_id => $item ) {
						   if($item->get_variation_id() == 0) {
						   		if(array_key_exists($item->get_product_id(),$product_quantity)) {
							   		$product_quantity[$item->get_product_id()] = $product_quantity[$item->get_product_id()]+$item->get_quantity();
							   } else {
							   		$product_quantity[$item->get_product_id()] = $item->get_quantity();
							   }
							   if(!array_key_exists($item->get_product_id(),$product_name)) {
							   		$product_name[$item->get_product_id()] = $item->get_name();
							   }
						   } else {
						   		if(array_key_exists($item->get_variation_id(),$product_quantity)) {
							   		$product_quantity[$item->get_variation_id()] = $product_quantity[$item->get_variation_id()]+$item->get_quantity();
							   } else {
							   		$product_quantity[$item->get_variation_id()] = $item->get_quantity();
							   }
							   if(!array_key_exists($item->get_variation_id(),$product_name)) {
							   		$variation = wc_get_product($item->get_variation_id());
							   		$product_name[$item->get_variation_id()] = strip_tags($variation->get_formatted_name());
							   }
						   }

						}
				    }

				    foreach($product_name as $id => $name) {
				    	$title = '';
				    	$temp_orders_quantity = [];
				        $title .= $name.' x '.$product_quantity[$id];
				        $temp_orders_quantity['start'] = $date;
						$temp_orders_quantity['end'] = $date;
						$temp_orders_quantity['title'] = $title;
						$response_orders_by_quantity[] = $temp_orders_quantity;
					}


		    	}
			    
		    }

			$final_response = $response_orders_by_quantity;
		
		} else {

			$response_orders = [];
			foreach ($dates as $date) {
		    	if($filtered_delivery_type == "delivery"){
		    		$args = array(
				        'limit' => -1,
				        'delivery_date' => date("Y-m-d", strtotime($date)),
				        'delivery_type' => "delivery",
				        'status' => $filtered_status_type
				    );
				    $orders_array = wc_get_orders( $args );
				    foreach ($orders_array as $order) {
				    	$orders[] = $order;
				    }
		    	} elseif($filtered_delivery_type == "pickup") {
		    		$args = array(
				        'limit' => -1,
				        'pickup_date' => date("Y-m-d", strtotime($date)),
				        'delivery_type' => "pickup",
				        'status' => $filtered_status_type
				    );
				    $orders_array = wc_get_orders( $args );
				    foreach ($orders_array as $order) {
				    	$orders[] = $order;
				    }
		    	} else {
		    		$args = array(
				        'limit' => -1,
				        'delivery_date' => date("Y-m-d", strtotime($date)),
				        'status' => $filtered_status_type
				    );

				    $orders_array = wc_get_orders( $args );
				    foreach ($orders_array as $order) {
				    	$delivery_orders[] = $order;
				    }

				    $args = array(
				        'limit' => -1,
				        'pickup_date' => date("Y-m-d", strtotime($date)),
				        'status' => $filtered_status_type
				    );

				    $orders_array = wc_get_orders( $args );
				    foreach ($orders_array as $order) {
				    	$pickup_orders[] = $order;
				    }

				    $orders = array_merge($delivery_orders, $pickup_orders);
		    	}
			    
		    }

			foreach($orders as $order) {
		    	
		    	$temp_orders = [];
		    	
		    	$date = "";
		    	$time_start="";
		    	$time_end="";
		    	$temp_orders ['title'] = 'Order #'.$order->get_id(); 
		    	$temp_orders ['url'] = get_bloginfo( 'url' ).'/wp-admin/post.php?post='.$order->get_id().'&action=edit';



		    	if(metadata_exists('post', $order->get_id(), 'delivery_date') && get_post_meta($order->get_id(), 'delivery_date', true) !="") {

			    	$date = get_post_meta( $order->get_id(), 'delivery_date', true );

			    }

			    if(metadata_exists('post', $order->get_id(), 'pickup_date') && get_post_meta($order->get_id(), 'pickup_date', true) !="") {

			    	$date = get_post_meta( $order->get_id(), 'pickup_date', true ); 

			    }

			    if(metadata_exists('post', $order->get_id(), 'delivery_time') && get_post_meta($order->get_id(), 'delivery_time', true) !="") {

			    	if(get_post_meta($order->get_id(), 'delivery_time', true) !="as-soon-as-possible") {
				    	$minutes = get_post_meta($order->get_id(),"delivery_time",true);
				    	$minutes = explode(' - ', $minutes);

			    		if(!isset($minutes[1])) {
			    			$time_start = "T".date("H:i", strtotime($minutes[0])).':00';
			    		} else {

			    			$time_start = "T".date("H:i", strtotime($minutes[0])).':00';
			    			$time_end = "T".date("H:i", strtotime($minutes[1])).':00'; 			
			    		}
		    		} else {
		    			$temp_orders ['title'] = 'Order #'.$order->get_id()." (As Soon As Possible)"; 
		    		}
			    	
			    }

			    if(metadata_exists('post', $order->get_id(), 'pickup_time') && get_post_meta($order->get_id(), 'pickup_time', true) !="") {
			    	$pickup_minutes = get_post_meta($order->get_id(),"pickup_time",true);
			    	$pickup_minutes = explode(' - ', $pickup_minutes);

			    	if(!isset($pickup_minutes[1])) {
		    			$time_start = "T".date("H:i", strtotime($pickup_minutes[0])).':00';
		    		} else {

		    			$time_start = "T".date("H:i", strtotime($pickup_minutes[0])).':00';
		    			$time_end = "T".date("H:i", strtotime($pickup_minutes[1])).':00'; 			
		    		}
			    	
			    }

			    if(isset($time_start)) {
			    	$temp_orders['start'] = $date.$time_start;
			    } else {
			    	$temp_orders['start'] = $date;
			    }

			    if(isset($time_end)) {
			    	$temp_orders['end'] = $date.$time_end;
			    } else {
			    	$temp_orders['end'] = $date;
			    }

			    $response_orders [] = $temp_orders;

		    }


			$final_response = $response_orders;
		} 


	    $response=[
			"orders" => $final_response,
			"timezone" => $timezone,
		];

		wp_send_json_success($response);
	}


	public function coderockz_woo_delivery_plugin_settings_export() {

       global $wpdb;

       $table = 'options';// table name
       $file = 'woo_delivery_plugin_settings'; // csv file name
       $csv_output = "";
       $results = $wpdb->get_results("SELECT option_name, option_value FROM $wpdb->prefix$table WHERE option_name LIKE '%coderockz_woo_delivery_%'",ARRAY_A );

       if(count($results) > 0){
          foreach($results as $result){
          $result = array_values($result);
          $result = implode(", ", $result);
          $csv_output .= $result."\n";
        }
      }

      $filename = $file."_".date("Y-m-d_H-i",time());
      header("Content-type: application/vnd.ms-excel");
      header("Content-disposition: csv" . date("Y-m-d") . ".csv");
      header( "Content-disposition: filename=".$filename.".csv");
      print $csv_output;
      exit;

    }

    public function coderockz_woo_delivery_process_reset_plugin_settings() {
    	global $wpdb;
		$plugin_options = $wpdb->get_results( "SELECT option_name FROM $wpdb->options WHERE option_name LIKE 'coderockz&_woo&_delivery&_%' ESCAPE '&'" );
		foreach( $plugin_options as $option ) {
		    delete_option( $option->option_name );
		}

		wp_send_json_success();
    }


    public function coderockz_woo_delivery_main_layout() {
        include_once CODEROCKZ_WOO_DELIVERY_DIR . '/admin/partials/coderockz-woo-delivery-admin-display.php';
    }

    public function coderockz_woo_delivery_delivery_calendar() {
        include_once CODEROCKZ_WOO_DELIVERY_DIR . '/admin/partials/coderockz-woo-delivery-delivery-calendar-display.php';
    }

    public function coderockz_change_the_date_time () {
		
		if(get_option('coderockz_woo_delivery_different_closing_time') == false) {
			$different_extended_closing_date = [];
			$time_form_settings = [];
			$weekday = array("0"=>"Sunday", "1"=>"Monday", "2"=>"Tuesday", "3"=>"Wednesday", "4"=>"Thursday", "5"=>"Friday", "6"=>"Saturday");
	        foreach ($weekday as $key => $value) {
	        	$different_extended_closing_date[$key] = get_option('coderockz_woo_delivery_time_settings')['extended_closing_days'];

	        }

	        $time_form_settings['different_extended_closing_day'] = $different_extended_closing_date;

	        $time_form_settings = array_merge(get_option('coderockz_woo_delivery_time_settings'),$time_form_settings);
			update_option('coderockz_woo_delivery_time_settings', $time_form_settings);
			
			update_option('coderockz_woo_delivery_different_closing_time','completed');
		}

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


    }

}
