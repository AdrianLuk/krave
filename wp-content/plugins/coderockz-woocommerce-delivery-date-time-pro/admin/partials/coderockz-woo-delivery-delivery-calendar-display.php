<?php

$delivery_types = [];
$delivery_types['All'] = 'all';
$delivery_types['Delivery'] = 'delivery';
$delivery_types['Pickup'] = 'pickup';

$other_settings = get_option('coderockz_woo_delivery_other_settings');
$spinner_animation_id = (isset($other_settings['spinner-animation-id']) && !empty($other_settings['spinner-animation-id'])) ? $other_settings['spinner-animation-id'] : "";

if($spinner_animation_id != "") {

	$spinner_url = wp_get_attachment_image_src($spinner_animation_id,'full', true);
	$full_size_spinner_animation_path = $spinner_url[0];
} else {
	$full_size_spinner_animation_path = CODEROCKZ_WOO_DELIVERY_URL.'public/images/loading.gif';
}

$spinner_animation_background = (isset($other_settings['spinner_animation_background']) && !empty($other_settings['spinner_animation_background'])) ? $this->helper->hex2rgb($other_settings['spinner_animation_background']) : array('red' => 31, 'green' => 158, 'blue' => 96);

echo "<div data-animation_background='".json_encode($spinner_animation_background)."' data-animation_path='".$full_size_spinner_animation_path."' id='coderockz_woo_delivery_calendar_filter_section'>";
?>

	<div id="coderockz_woo_delivery_calendar_filter">
		<span class="dashicons dashicons-filter" style="color: #bbb;vertical-align: middle;marging-right: 20px;margin-left: 5px;"></span>
		<select id="coderockz_woo_delivery_calendar_delivery_type_filter">
			<option value=""></option>
			<?php foreach ( $delivery_types as $label => $delivery_type ) : ?>
				<option value="<?php echo esc_attr( $delivery_type ); ?>">
					<?php echo esc_html( $label ); ?>
				</option>
			<?php endforeach; ?>
		</select>
		<select id="coderockz_woo_delivery_calendar_filter_type_filter">
			<option value=""></option>
			<option value="order_type">Order</option>
			<option value="product">Product</option>
		</select>
		<select id="coderockz_woo_delivery_calendar_order_status_filter" class="coderockz_woo_delivery_calendar_order_status_filter" multiple>
			<option value="pending">Pending payment</option>
			<option value="processing">Processing</option>
			<option value="on-hold">On hold</option>
			<option value="completed">Completed</option>
			<option value="refunded">Refunded</option>
			<option value="failed">Failed</option>
			<option value="cancelled">Cancelled</option>
		</select>
	</div>
	<div id='coderockz-woo-delivery-delivery-calendar'>
		
	</div>
</div>