<?php
require_once CODEROCKZ_WOO_DELIVERY_DIR . 'includes/class-coderockz-woo-delivery-helper.php';
require_once CODEROCKZ_WOO_DELIVERY_DIR . 'admin/libs/PHP_XLSXWriter/xlsxwriter.class.php';
	if ( isset($_REQUEST['_wpnonce']) && wp_verify_nonce($_REQUEST['_wpnonce'], 'coderockz_woo_delivery_nonce' ) && isset($_POST['coderockz-woo-delivery-export-excel-btn']) ) {

		$filtered_date = sanitize_text_field($_POST['coderockz-woo-delivery-export-excel-date']);
    	$filtered_delivery_type = sanitize_text_field($_POST[ 'coderockz-woo-delivery-export-excel-type' ]);

    	$delivery_date_settings = get_option('coderockz_woo_delivery_date_settings');			
    	$pickup_date_settings = get_option('coderockz_woo_delivery_pickup_date_settings');			
		$delivery_time_settings = get_option('coderockz_woo_delivery_time_settings');
		$pickup_time_settings = get_option('coderockz_woo_delivery_pickup_settings');
		$delivery_pickup_settings = get_option('coderockz_woo_delivery_pickup_location_settings');
		$additional_field_settings = get_option('coderockz_woo_delivery_additional_field_settings');
		// if any timezone data is saved, set default timezone with the data
		$helper = new Coderockz_Woo_Delivery_Helper();
		$timezone = $helper->get_the_timezone();
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


		$unsorted_orders = [];
		foreach($orders as $order) {
			if( version_compare( get_option( 'woocommerce_version' ), '3.0.0', ">=" ) ) {            
		        $order_id = $order->get_id();
		    } else {
		        $order_id = $order->id;
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

	    	$unsorted_orders[$order_id] = $delivery_details_in_timestamp;
		}

		asort($unsorted_orders);

		$filename = "sampleFilename.xlsx";
		header('Content-disposition: attachment; filename="'.XLSXWriter::sanitize_filename($filename).'"');
		header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
		header('Content-Transfer-Encoding: binary');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		ob_clean();
		flush();
		
		$header = array(
		    'Order No'=>'string',
		    'Order Date'=>'string',
		    'Order Status'=>'string',  
		    'Delivery Details'=>'string',  
		    'Delivery Status'=>'string',  
		    'Billing Address'=>'string',  
		    'Shipping Address'=>'string',  
		    'Products'=>'string',  
		    'Total'=>'string',  
		    'Payment Method'=>'string',  
		    'Customer Note'=>'string',  
		);

		$excel_sheet_data = [];
		foreach ($unsorted_orders as $order_id => $value) {
			
			$single_data = [];
			$order = wc_get_order($order_id);

			$single_data [] = '#'.$order->get_id();

		    $order_created_obj= new DateTime($order->get_date_created());
			$single_data [] = $order_created_obj->format("F j, Y");

			$single_data [] = $order->get_status();

			$delivery_details = "";
		    if(metadata_exists('post', $order_id, 'delivery_date') && get_post_meta($order_id, 'delivery_date', true) !="") {

		    	$delivery_details .= $delivery_date_field_label.': ' . date($delivery_date_format, strtotime(get_post_meta( $order_id, 'delivery_date', true ))) . "\n";

		    }

		    if(metadata_exists('post', $order_id, 'pickup_date') && get_post_meta($order_id, 'pickup_date', true) !="") {

		    	$delivery_details .= $pickup_date_field_label.': ' . date($delivery_date_format, strtotime(get_post_meta( $order_id, 'pickup_date', true ))) . "\n"; 

		    }

		    if(metadata_exists('post', $order_id, 'delivery_time') && get_post_meta($order_id, 'delivery_time', true) !="") {

		    	if(get_post_meta($order_id, 'delivery_time', true) !="as-soon-as-possible") {
			    	$minutes = get_post_meta($order_id,"delivery_time",true);
			    	$minutes = explode(' - ', $minutes);

		    		if(!isset($minutes[1])) {
		    			$delivery_details .= $delivery_time_field_label.': ' . date($time_format, strtotime($minutes[0])) . "\n";
		    		} else {

		    			$delivery_details .= $delivery_time_field_label.': ' . date($time_format, strtotime($minutes[0])) . ' - ' . date($time_format, strtotime($minutes[1])) . "\n";  			
		    		}
	    		} else {
	    			$as_soon_as_possible_text = (isset($delivery_time_settings['as_soon_as_possible_text']) && !empty($delivery_time_settings['as_soon_as_possible_text'])) ? stripslashes($delivery_time_settings['as_soon_as_possible_text']) : "As Soon As Possible";
	    			$delivery_details .= $delivery_time_field_label.': ' . $as_soon_as_possible_text . "\n";
	    		}
		    	
		    }

		    if(metadata_exists('post', $order_id, 'pickup_time') && get_post_meta($order_id, 'pickup_time', true) !="") {
		    	$pickup_minutes = get_post_meta($order_id,"pickup_time",true);
		    	$pickup_minutes = explode(' - ', $pickup_minutes);

	    		if(!isset($pickup_minutes[1])) {
	    			$delivery_details .= $pickup_time_field_label.': ' . date($pickup_time_format, strtotime($pickup_minutes[0])) . "\n";
	    		} else {

	    			$delivery_details .= $pickup_time_field_label.': ' . date($pickup_time_format, strtotime($pickup_minutes[0])) . ' - ' . date($pickup_time_format, strtotime($pickup_minutes[1])) . "\n";  			
	    		}
		    	
		    }

		    if(metadata_exists('post', $order_id, 'delivery_pickup') && get_post_meta($order_id, 'delivery_pickup', true) !="") {
				$delivery_details .= $pickup_location_field_label.': ' . get_post_meta($order_id, 'delivery_pickup', true) . "\n";
			}

			if(metadata_exists('post', $order_id, 'additional_note') && get_post_meta($order_id, 'additional_note', true) !="") {
				$delivery_details .= $additional_field_label.': ' . get_post_meta($order_id, 'additional_note', true) . "\n";
			}

			$single_data [] = $delivery_details;


			if(metadata_exists('post', $order_id, 'delivery_status') && get_post_meta($order_id, 'delivery_status', true) !="" && get_post_meta($order_id, 'delivery_status', true) =="delivered") {
				if(metadata_exists('post', $order_id, 'delivery_type') && get_post_meta($order_id, 'delivery_type', true) !="" && get_post_meta($order_id, 'delivery_type', true) =="pickup") {
					$delivery_status = $pickup_status_picked_text;
				} else {
					$delivery_status = $delivery_status_delivered_text;
				}
				
			} else {

				if(metadata_exists('post', $order_id, 'delivery_type') && get_post_meta($order_id, 'delivery_type', true) !="" && get_post_meta($order_id, 'delivery_type', true) =="pickup") {
					$delivery_status = $pickup_status_not_picked_text;
				} else {
					$delivery_status = $delivery_status_not_delivered_text;
				}
			}

			$single_data [] = $delivery_status;

			$billing_address = "";
			$billing_address .= $order->get_billing_first_name().' '.$order->get_billing_last_name()."\n";
			if($order->get_billing_company()) {
				$billing_address .= $order->get_billing_company()."\n";
			}
			$billing_address .= $order->get_billing_address_1()."\n";
			if($order->get_billing_address_2()) {
				$billing_address .= $order->get_billing_address_2()."\n";
			}
			
			    $billing_address .= $order->get_billing_city()."\n";
			    $billing_address .= $order->get_billing_state();
			if($order->get_billing_postcode()) {
				$billing_address .= '-'.$order->get_billing_postcode()."\n";
			}
			$billing_address .= $order->get_billing_country()."\n";
			$billing_address .= 'Mobile: '.$order->get_billing_phone()."\n";
			$billing_address .= 'Email: '.$order->get_billing_email()."\n";

			$single_data [] = $billing_address;

			$shipping_address = "";
			if($order->get_formatted_shipping_address()){
		    	$shipping_address .= $order->get_shipping_first_name().' '.$order->get_shipping_last_name()."\n";
				if($order->get_shipping_company()) {
					$shipping_address .= $order->get_shipping_company()."\n";
				}
				$shipping_address .= $order->get_shipping_address_1()."\n";
				if($order->get_shipping_address_2()) {
					$shipping_address .= $order->get_shipping_address_2()."\n";
				}
				
				$shipping_address .= $order->get_shipping_city()."\n";
				$shipping_address .= $order->get_shipping_state();
				if($order->get_shipping_postcode()) {
					$shipping_address .= '-'.$order->get_shipping_postcode()."\n";
				}
				$shipping_address .= $order->get_shipping_country()."\n";
			}

			$single_data [] = $shipping_address;


			$i=1;
			$product_details = "";
			foreach ($order->get_items() as $item) {
				$product_details .= $i.'. ';
				$product_details .= $helper->product_name_length($item->get_name());
				$product_details .= '   '.$helper->format_price($order->get_item_total( $item ),$order->get_id()).'x';
				$product_details .= $item->get_quantity().'=';
				$product_details .= $helper->format_price($item->get_total(),$order->get_id());
				$product_details .= "\n";
				$i = $i+1;
			}

			$single_data [] = $product_details;

			$single_data [] = $order->get_currency() . $order->get_total();
			
			$single_data [] = $order->get_payment_method_title();
			
			$single_data [] = $order->get_customer_note();

			$excel_sheet_data [] = $single_data;
			

		}

		$writer = new XLSXWriter(); // Initialize XLSXWriter() class




		$sheet_name = 'Sheet1';
		$writer = new XLSXWriter();

		$writer->writeSheetHeader($sheet_name, $header, $col_options = ['widths'=>[10,15,15,30,15,30,30,30,10,20,30], 'font-style' => 'bold', 'fill'=>'#ccc'] );
		foreach ($excel_sheet_data as $row) {
			$writer->writeSheetRow($sheet_name, $row );
		}

		$writer->writeToStdOut(); 
		exit;

	}

	$delivery_date_settings = get_option('coderockz_woo_delivery_date_settings');

	/*This week range*/	
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

	$week_end = strtotime(date("F j, Y",$week_start)." +6 days");

	$this_week_start = date("F j, Y",$week_start);
	$this_week_end = date("F j, Y",$week_end);

	$this_week = $this_week_start." - ".$this_week_end;
	/*This week range*/

	/*This Month range*/
	$day_today = strtotime (date('F j, Y', time()));
    $this_month_first_day = date ('F j, Y', strtotime ('first day of this month', $day_today));
    $this_month_last_day = date ('F j, Y', strtotime ('last day of this month', $day_today));

    $this_month = $this_month_first_day." - ".$this_month_last_day;

	/*This Month range*/

	$today = date('F j, Y', time());
	$date_obj = new DateTime($today);
	$tomorrow = $date_obj->modify("+1 day")->format("F j, Y");

	$order_details_html = '';


?>
<div class="coderockz-woo-delivery-card">
	<p class="coderockz-woo-delivery-card-header"><?php _e('Delivery Reports', 'coderockz-woo-delivery'); ?></p>
	<div class="coderockz-woo-delivery-card-body">
		<div class="coderockz-woo-delivery-current-filter-section">
			
			<div style="float:left;">
				<span class="coderockz-woo-delivery-current-filter" data-filter_text="Today <span style='color:#bbb'> (<?php echo $today; ?>)</span>">Select Date</span>
				<span class="coderockz-woo-delivery-filter-change-btn dashicons dashicons-arrow-down-alt2"></span>
			</div>
			<div style="float:right;">
				
			</div>
		</div>
		<div class="coderockz_woo_delivery_report_filter_modal" id="coderockz_woo_delivery_report_filter_modal">
			<div class="coderockz_woo_delivery_report_filter_modal_wrap">
				<div class="coderockz_woo_delivery_report_filter_modal_header">
					<p class="coderockz_woo_delivery_report_filter_modal_header_text" style="margin:0;">Today <span style="color:#bbb"> (<?php echo $today; ?>)</span></p>
				</div>

				<div class="coderockz_woo_delivery_report_filter_modal_body">
					<div class="coderockz-woo-delivery-report-header-wrapper">    
						<div class="coderockz-woo-delivery-report-header-delivery-type">
						<label style="margin-right: 5px;">
						    <input type="radio" name="coderockz-woo-delivery-report-filter-delivery-type" value="all" checked/>All
						</label>
						<label style="margin-right: 5px;">
						    <input type="radio" name="coderockz-woo-delivery-report-filter-delivery-type" value="delivery"/>Delivery
						</label>
						<label>
						    <input type="radio" name="coderockz-woo-delivery-report-filter-delivery-type" value="pickup"/>Pickup
						</label>
						</div>
						<div class="coderockz-woo-delivery-report-header-radio-btn">
							<input type="radio" id="coderockz-woo-delivery-report-header-radio-btn-1" name="coderockz-woo-delivery-report-filter-value" value="<?php echo $today; ?>" checked/>
							<label for="coderockz-woo-delivery-report-header-radio-btn-1"></label>
							<span>Today <span style="color:#bbb"> (<?php echo $today; ?>)</span></span>
						</div>

						<div class="coderockz-woo-delivery-report-header-radio-btn">
							<input type="radio" id="coderockz-woo-delivery-report-header-radio-btn-2" name="coderockz-woo-delivery-report-filter-value" value="<?php echo $tomorrow; ?>"/>
							<label for="coderockz-woo-delivery-report-header-radio-btn-2"></label>
							<span>Tomorrow <span style="color:#bbb"> (<?php echo $tomorrow; ?>)</span></span>
						</div>

						<div class="coderockz-woo-delivery-report-header-radio-btn">
							<input type="radio" id="coderockz-woo-delivery-report-header-radio-btn-3" name="coderockz-woo-delivery-report-filter-value" value="<?php echo $this_week; ?>"/>
							<label for="coderockz-woo-delivery-report-header-radio-btn-3"></label>
							<span>This Week <span style="color:#bbb"> (<?php echo $this_week; ?>)</span></span>
						</div>

						<div class="coderockz-woo-delivery-report-header-radio-btn">
							<input type="radio" id="coderockz-woo-delivery-report-header-radio-btn-4" name="coderockz-woo-delivery-report-filter-value" value="<?php echo $this_month; ?>"/>
							<label for="coderockz-woo-delivery-report-header-radio-btn-4"></label>
							<span>This Month <span style="color:#bbb"> (<?php echo $this_month; ?>)</span>
						</div>
						<div class="coderockz-woo-delivery-report-header-radio-btn">
							<input type="radio" id="coderockz-woo-delivery-report-header-radio-btn-5" name="coderockz-woo-delivery-report-filter-value" value=""/>
							<label for="coderockz-woo-delivery-report-header-radio-btn-5"></label>
							<span>Custom Date Range<span style="color:#bbb"> (Max 30 Days)</span></span>
						</div>
					</div>
					<div class="coderockz-woo-delivery-date-range-wrapper">

					</div>
				</div>

				<div class="coderockz_woo_delivery_report_filter_modal_footer">
					<form style="display: none;" action="" method="post" id ="coderockz_woo_delivery_export_form_submit">
                        <?php wp_nonce_field('coderockz_woo_delivery_nonce'); ?>

                        <input style="display:none;visibility:hidden;" class="button-primary" type="text" name="coderockz-woo-delivery-export-excel-type" value="all" />
                        <input style="display:none;visibility:hidden;" class="button-primary" type="text" name="coderockz-woo-delivery-export-excel-date" value="<?php echo $today; ?>" />

                        <input class="button-primary" type="submit" name="coderockz-woo-delivery-export-excel-btn" value="<?php _e('Export', 'coderockz-woo-delivery'); ?>" />
                    </form>

					<button class="coderockz-woo-delivery-report-product-quantity-button button-primary">Product Quantity</button>
					<button class="coderockz-woo-delivery-report-filter-apply-button button-primary">Delivery Details</button>
					<button class="coderockz-woo-delivery-report-filter-cancel-button button-secondary">Cancel</button>
				</div>
			</div>
		</div>

		<div class="coderockz_woo_delivery_report_result">
			<table id="coderockz_woo_delivery_report_table" class="display" style="width:100%">
		        <thead>
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
		        <tbody>
		        	<?php echo $order_details_html; ?>
	        	</tbody>
		        <tfoot>
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
		        </tfoot>
		    </table>
		</div>
	</div>
</div>