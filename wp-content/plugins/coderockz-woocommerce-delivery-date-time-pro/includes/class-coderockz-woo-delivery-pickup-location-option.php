<?php
if( !class_exists( 'Coderockz_Woo_Delivery_Pickup_Location_Option' ) ) {
	
	class Coderockz_Woo_Delivery_Pickup_Location_Option {

		public static function pickup_location_option($pickup_location_settings, $meta_box=null) {
			
			$pickup_locations = (isset($pickup_location_settings['pickup_location']) && !empty($pickup_location_settings['pickup_location'])) ? $pickup_location_settings['pickup_location'] : [];
			$pickup_location = [];
		
			if(is_null($meta_box)){
				$pickup_location[''] = '';
			}
			
			foreach($pickup_locations as $pickup)
			{
				$pickup_location[$pickup] = stripslashes($pickup);
			}

			return $pickup_location;
		}
	}
}