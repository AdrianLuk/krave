<?php

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