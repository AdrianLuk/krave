<?php

if( !class_exists( 'Coderockz_Woo_Delivery_Helper' ) ) {

	class Coderockz_Woo_Delivery_Helper {

		public function coderockz_woo_delivery_array_sanitize($array) {
		    $newArray = array();
		    if (count($array)>0) {
		        foreach ($array as $key => $value) {
		            if (is_array($value)) {
		                foreach ($value as $key2 => $value2) {
		                    if (is_array($value2)) {
		                        foreach ($value2 as $key3 => $value3) {
		                            $newArray[$key][$key2][$key3] = sanitize_text_field($value3);
		                        }
		                    } else {
		                        $newArray[$key][$key2] = sanitize_text_field($value2);
		                    }
		                }
		            } else {
		                $newArray[$key] = sanitize_text_field($value);
		            }
		        }
		    }
		    return $newArray;
		}

		public function hex2rgb( $colour ) {
	        if ( $colour[0] == '#' ) {
	                $colour = substr( $colour, 1 );
	        }
	        if ( strlen( $colour ) == 6 ) {
	                list( $r, $g, $b ) = array( $colour[0] . $colour[1], $colour[2] . $colour[3], $colour[4] . $colour[5] );
	        } elseif ( strlen( $colour ) == 3 ) {
	                list( $r, $g, $b ) = array( $colour[0] . $colour[0], $colour[1] . $colour[1], $colour[2] . $colour[2] );
	        } else {
	                return false;
	        }
	        $r = hexdec( $r );
	        $g = hexdec( $g );
	        $b = hexdec( $b );
	        return array( 'red' => $r, 'green' => $g, 'blue' => $b );
		}

		public function containsDecimal( $value ) {
			$value = (string)$value;
		    if ( strpos( $value, "." ) !== false ) {
		        return true;
		    }
		    return false;
		}

		public function objectToArray($d) {
		    foreach($d as $key => $value) {
			    $d[$key] = (array) $value;
			}
			return $d;
		}

		// Function to check string starting with given substring 
		public function starts_with ($string, $startString) { 
		    $len = strlen($startString); 
		    return (substr($string, 0, $len) === $startString); 
		}

		public function detect_plugin_settings_page() {
			global $wp;  
			$current_url = home_url(add_query_arg(array($_GET), $wp->request));
			if (strpos($current_url, "coderockz-woo-delivery-settings")!==false){
			    return true;
			}
		}

		public function detect_delivery_calendar_page() {
			global $wp;  
			$current_url = home_url(add_query_arg(array($_GET), $wp->request));
			if (strpos($current_url, "coderockz-woo-delivery-delivery-calendar")!==false){
			    return true;
			}
		}

		public function currency_exchange_value( $currency_value ) {
	        if ( class_exists('WOOCS') ) {
	            $currency_value = apply_filters('woocs_exchange_value', $currency_value);
	        }
	       
	        return $currency_value;
	    }


		public function cart_total() {
			$delivery_option_settings = get_option('coderockz_woo_delivery_option_delivery_settings'); 

			$enable_including_tax = (isset($delivery_option_settings['calculating_include_tax']) && !empty($delivery_option_settings['calculating_include_tax'])) ? $delivery_option_settings['calculating_include_tax'] : false;

			if($enable_including_tax && wc_prices_include_tax()) {
				$cart_total_price = WC()->cart->get_cart_contents_total() + WC()->cart->get_cart_contents_tax();
			} else {
				$cart_total_price = WC()->cart->get_cart_contents_total();
			}
			/*$cart_total_price = wc_prices_include_tax() ? WC()->cart->get_cart_contents_total() + WC()->cart->get_cart_contents_tax() : WC()->cart->get_cart_contents_total();*/
			

			return $cart_total_price;
		}


		public function get_the_timezone() {
			
			$delivery_time_settings = get_option('coderockz_woo_delivery_time_settings');
			if(isset($delivery_time_settings['store_location_timezone']) &&
			$delivery_time_settings['store_location_timezone'] != "") {
				return $delivery_time_settings['store_location_timezone'];
			} else {
				// If site timezone string exists, return it.
				$timezone = get_option( 'timezone_string' );
				if ( $timezone ) {
					return $timezone;
				}

				// Get UTC offset, if it isn't set then return UTC.
				$utc_offset = intval( get_option( 'gmt_offset', 0 ) );
				if ( 0 === $utc_offset ) {
					return 'UTC';
				}

				// Adjust UTC offset from hours to seconds.
				$utc_offset *= 3600;

				// Attempt to guess the timezone string from the UTC offset.
				$timezone = timezone_name_from_abbr( '', $utc_offset );
				if ( $timezone ) {
					return $timezone;
				}

				// Last try, guess timezone string manually.
				foreach ( timezone_abbreviations_list() as $abbr ) {
					foreach ( $abbr as $city ) {
						// WordPress restrict the use of date(), since it's affected by timezone settings, but in this case is just what we need to guess the correct timezone.
						if ( (bool) date( 'I' ) === (bool) $city['dst'] && $city['timezone_id'] && intval( $city['offset'] ) === $utc_offset ) { // phpcs:ignore WordPress.DateTime.RestrictedFunctions.date_date
							return $city['timezone_id'];
						}
					}
				}

				// Fallback to UTC.
				return 'UTC';
			}
			
		}

		
		public function check_virtual_downloadable_products() {
			// By default, no virtual or downloadable product
			$has_virtual_downloadable_products = false;
			  
			// Default virtual products number
			$virtual_products = 0;

			// Default downloadable products number
			$downloadable_products = 0;
			  
			// Get all products in cart
			$products = WC()->cart->get_cart();
			  
			// Loop through cart products
			foreach( $products as $product ) {
				  
				// Get product ID and '_virtual' post meta
				$product_id = $product['product_id'];
				$is_virtual = get_post_meta( $product_id, '_virtual', true );
				  
				// Update $has_virtual_product if product is virtual
				if( $is_virtual == 'yes' ) {
					$virtual_products += 1;
				}

				$is_downloadable = get_post_meta( $product_id, '_downloadable', true );
				  
				// Update $has_virtual_product if product is virtual
				if( $is_downloadable == 'yes' ) {
					$downloadable_products += 1;
				}
			  		
			}

			$total_virtual_downloadable_products = $virtual_products + $downloadable_products;

			if( count($products) == $virtual_products || count($products) == $downloadable_products || count($products) == $total_virtual_downloadable_products) {
			 	$has_virtual_downloadable_products = true;
			}

			return $has_virtual_downloadable_products;
		}

		public function array_sort_by_column(&$arr, $col, $dir = SORT_ASC) {
		    $sort_col = array();
		    foreach ($arr as $key=> $row) {
		        $sort_col[$key] = $row;
		    }

		    array_multisort($sort_col, $dir, $arr);
		    return $sort_col;
		}

		public function checkout_product_categories($exclude_checking=false) {
			$product_cat = [];
			foreach ( WC()->cart->get_cart() as $cart_item ) {
				if($cart_item['data']->get_parent_id()) {
					$variable_product = $cart_item['data']->get_parent_id();
					$terms = get_the_terms( $variable_product, 'product_cat' );
				} else {
					$terms = get_the_terms( $cart_item['data']->get_id(), 'product_cat' );
				}


				foreach ($terms as $term) {
					$product_cat[] = $term->name;
				}
			}
			$checkout_product_categories = array_unique(array_values($product_cat));
			if($exclude_checking == false) {
				$checkout_product_categories = array_map('strtolower', $checkout_product_categories);
			}
			
			return $checkout_product_categories;
		}

		public function checkout_product_id() {
			$product_id = [];
			foreach ( WC()->cart->get_cart() as $cart_item ) {
				$product_id[] = $cart_item['data']->get_id();
			}
			return $product_id;
		}

		public function order_product_categories($order_items, $exclude_checking=false) {
			$product_cat = [];
			foreach ( $order_items as $item ) { 
				$terms = get_the_terms( $item->get_product_id(), 'product_cat' );
				foreach ($terms as $term) {
					$product_cat[] = $term->name;
				}
			}

			// preparing product categories data from cart
			$order_product_categories = array_unique(array_values($product_cat));
			if($exclude_checking == false) {
				$order_product_categories = array_map('strtolower', $order_product_categories);
			}

			return $order_product_categories;
		}

		public function order_product_id($order_items) {
			$product_id = [];
			foreach ( $order_items as $item ) {
			    if( $item->get_variation_id() ) {
			        $product_id[] = $item->get_variation_id();			        
			    } else {
					$product_id[] = $item->get_product_id();
				}

			}
			return $product_id;
		}

		public function detect_exclude_condition() {
			$exclude_settings = get_option('coderockz_woo_delivery_exclude_settings');

			$exclude_categories = (isset($exclude_settings['exclude_categories']) && !empty($exclude_settings['exclude_categories'])) ? $exclude_settings['exclude_categories'] : array();

			$exclude_products = (isset($exclude_settings['exclude_products']) && !empty($exclude_settings['exclude_products'])) ? $exclude_settings['exclude_products'] : array();

			$reverse_current_condition = (isset($exclude_settings['reverse_current_condition']) && !empty($exclude_settings['reverse_current_condition'])) ? $exclude_settings['reverse_current_condition'] : false;

			$checkout_product_categories = $this->checkout_product_categories(true);
			$checkout_products = $this->checkout_product_id();

			$exclude_categories_condition = (count(array_intersect($checkout_product_categories, $exclude_categories)) <= count($checkout_product_categories)) && count(array_intersect($checkout_product_categories, $exclude_categories))>0;
  			
  			$exclude_products_condition = (count(array_intersect($checkout_products, $exclude_products)) <= count($checkout_products)) && count(array_intersect($checkout_products, $exclude_products))>0;

			if($exclude_categories_condition) {
				$exclude_condition = true;
				if($reverse_current_condition && count($checkout_product_categories) > 1) {
	  				$exclude_condition = !$exclude_condition;
	  			}
			} elseif($exclude_products_condition) {
				$exclude_condition = true;
				if($reverse_current_condition && count($checkout_products) > 1) {
	  				$exclude_condition = !$exclude_condition;
	  			}
			} else {
				$exclude_condition = false;
			}

			return $exclude_condition;
		}


		public function order_detect_exclude_condition($order_items) {
			$exclude_settings = get_option('coderockz_woo_delivery_exclude_settings');

			$exclude_categories = (isset($exclude_settings['exclude_categories']) && !empty($exclude_settings['exclude_categories'])) ? $exclude_settings['exclude_categories'] : array();

			$exclude_products = (isset($exclude_settings['exclude_products']) && !empty($exclude_settings['exclude_products'])) ? $exclude_settings['exclude_products'] : array();

			$reverse_current_condition = (isset($exclude_settings['reverse_current_condition']) && !empty($exclude_settings['reverse_current_condition'])) ? $exclude_settings['reverse_current_condition'] : false;

			$order_product_categories = $this->order_product_categories($order_items,true);
			$order_products = $this->order_product_id($order_items);

			$exclude_categories_condition = (count(array_intersect($order_product_categories, $exclude_categories)) <= count($order_product_categories)) && count(array_intersect($order_product_categories, $exclude_categories))>0;
  			
  			$exclude_products_condition = (count(array_intersect($order_products, $exclude_products)) <= count($order_products)) && count(array_intersect($order_products, $exclude_products))>0;

			if($exclude_categories_condition) {
				$exclude_condition = true;
				if($reverse_current_condition && count($order_product_categories) > 1) {
	  				$exclude_condition = !$exclude_condition;
	  			}
			} elseif($exclude_products_condition) {
				$exclude_condition = true;
				if($reverse_current_condition && count($order_products) > 1) {
	  				$exclude_condition = !$exclude_condition;
	  			}
			} else {
				$exclude_condition = false;
			}

			return $exclude_condition;
		}

		/*public function site_url() {
		    if(isset($_SERVER['HTTPS'])){
		        $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
		    }
		    else{
		        $protocol = "http";
		    }
		    if(!is_multisite()) {
			    if($_SERVER['HTTP_HOST'] =="localhost") {
			    	return filter_var($protocol . "://" . $_SERVER['HTTP_HOST']. dirname($_SERVER['PHP_SELF']),
	                FILTER_SANITIZE_URL);
			    } else {
			    	return filter_var($protocol . "://" . $_SERVER['HTTP_HOST'],
	                FILTER_SANITIZE_URL);
			    }
			} else {
				return get_bloginfo( 'url' );
			}
		    
		}*/

		public function format_price($price, $orderId = null) {
	        return sprintf(get_woocommerce_price_format(), get_woocommerce_currency_symbol(wc_get_order($orderId)->get_currency()), $price);
	    }

	    public function get_product_image($id) {
	        $image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $id ), 'single-post-thumbnail' );
			$image = '<img src="'.$image_url[0].'" style="width:40px;margin-right: 10px;vertical-align: middle;">';
	            return $image;
			return $image;
	    }

	    public function product_name_length($name) { 
	    	$product_title_length = 30;
	        if(strlen($name)>$product_title_length){
	            $name =substr($name,0,$product_title_length). "...";
	            return $name;
	        } else {
	            return $name;
	        }
	    }

		public function date_conversion( $date, $delivery_type="delivery" ) {
			$arMonths = ["يناير","فبراير","مارس","أبريل","مايو","يونيو","يوليو","أغسطس","سبتمبر","أكتوبر","نوفمبر","ديسمبر"];
			$atMonths = ["Jänner","Februar","März","April","Mai","Juni","Juli","August","September","Oktober","November","Dezember"];
		    $azMonths = ["Yanvar","Fevral","Mart","Aprel","May","İyun","İyul","Avqust","Sentyabr","Oktyabr","Noyabr","Dekabr"];
			$beMonths = ["Студзень","Люты","Сакавік","Красавік","Травень","Чэрвень","Ліпень","Жнівень","Верасень","Кастрычнік","Лістапад","Снежань"];
			$bgMonths = ["Януари","Февруари","Март","Април","Май","Юни","Юли","Август","Септември","Октомври","Ноември","Декември"];
		    $bnMonths = ["জানুয়ারী","ফেব্রুয়ারী","মার্চ","এপ্রিল","মে","জুন","জুলাই","আগস্ট","সেপ্টেম্বর","অক্টোবর","নভেম্বর","ডিসেম্বর"];
		    $bsMonths = ["Januar","Februar","Mart","April","Maj","Juni","Juli","Avgust","Septembar","Oktobar","Novembar","Decembar"];
		    $catMonths = ["Gener","Febrer","Març","Abril","Maig","Juny","Juliol","Agost","Setembre","Octubre","Novembre","Desembre"];
		    $csMonths = ["Leden","Únor","Březen","Duben","Květen","Červen","Červenec","Srpen","Září","Říjen","Listopad","Prosinec"];
		    $cyMonths = ["Ionawr","Chwefror","Mawrth","Ebrill","Mai","Mehefin","Gorffennaf","Awst","Medi","Hydref","Tachwedd","Rhagfyr"];
		    $daMonths = ["januar","februar","marts","april","maj","juni","juli","august","september","oktober","november","december"];
		    $deMonths = ["Januar","Februar","März","April","Mai","Juni","Juli","August","September","Oktober","November","Dezember"];
		    $defaultMonths = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
		    $eoMonths = ["januaro","februaro","marto","aprilo","majo","junio","julio","aŭgusto","septembro","oktobro","novembro","decembro"];
		    $esMonths = ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
		    $etMonths = ["Jaanuar","Veebruar","Märts","Aprill","Mai","Juuni","Juuli","August","September","Oktoober","November","Detsember"];
		    $enMonths = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
		    $fiMonths = ["Tammikuu", "Helmikuu", "Maaliskuu", "Huhtikuu", "Toukokuu", "Kesäkuu", "Heinäkuu", "Elokuu", "Syyskuu", "Lokakuu", "Marraskuu", "Joulukuu"];
		    $foMonths = ["Januar", "Februar", "Mars", "Apríl", "Mai", "Juni", "Juli", "August", "Septembur", "Oktobur", "Novembur", "Desembur"];
		    $frMonths = ["janvier", "février", "mars", "avril", "mai", "juin", "juillet", "août", "septembre", "octobre", "novembre", "décembre"];
		    $faMonths = ["ژانویه", "فوریه", "مارس", "آوریل", "مه", "ژوئن", "ژوئیه", "اوت", "سپتامبر", "اکتبر", "نوامبر", "دسامبر"];
		    $gaMonths = ["Eanáir", "Feabhra", "Márta", "Aibreán", "Bealtaine", "Meitheamh", "Iúil", "Lúnasa", "Meán Fómhair", "Deireadh Fómhair", "Samhain", "Nollaig"];
		    $grMonths = ["Ιανουάριος", "Φεβρουάριος", "Μάρτιος", "Απρίλιος", "Μάιος", "Ιούνιος", "Ιούλιος", "Αύγουστος", "Σεπτέμβριος", "Οκτώβριος", "Νοέμβριος", "Δεκέμβριος"];
		    $heMonths = ["ינואר", "פברואר", "מרץ", "אפריל", "מאי", "יוני", "יולי", "אוגוסט", "ספטמבר", "אוקטובר", "נובמבר", "דצמבר"];
		    $hiMonths = ["जनवरी ", "फरवरी", "मार्च", "अप्रेल", "मई", "जून", "जूलाई", "अगस्त ", "सितम्बर", "अक्टूबर", "नवम्बर", "दिसम्बर"];
		    $hrMonths = ["Siječanj", "Veljača", "Ožujak", "Travanj", "Svibanj", "Lipanj", "Srpanj", "Kolovoz", "Rujan", "Listopad", "Studeni", "Prosinac"];
		    $huMonths = ["Január", "Február", "Március", "Április", "Május", "Június", "Július", "Augusztus", "Szeptember", "Október", "November", "December"];
		    $idMonths = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
		    $isMonths = ["Janúar", "Febrúar", "Mars", "Apríl", "Maí", "Júní", "Júlí", "Ágúst", "September", "Október", "Nóvember", "Desember"];
		    $itMonths = ["Gennaio", "Febbraio", "Marzo", "Aprile", "Maggio", "Giugno", "Luglio", "Agosto", "Settembre", "Ottobre", "Novembre", "Dicembre"];
		    $jaMonths = ["1月", "2月", "3月", "4月", "5月", "6月", "7月", "8月", "9月", "10月", "11月", "12月"];
		    $kaMonths = ["იანვარი", "თებერვალი", "მარტი", "აპრილი", "მაისი", "ივნისი", "ივლისი", "აგვისტო", "სექტემბერი", "ოქტომბერი", "ნოემბერი", "დეკემბერი"];
		    $kmMonths = ["មករា", "កុម្ភះ", "មីនា", "មេសា", "ឧសភា", "មិថុនា", "កក្កដា", "សីហា", "កញ្ញា", "តុលា", "វិច្ឆិកា", "ធ្នូ"];
		    $koMonths = ["1월", "2월", "3월", "4월", "5월", "6월", "7월", "8월", "9월", "10월", "11월", "12월"];
		    $kzMonths = ["Қаңтар", "Ақпан", "Наурыз", "Сәуiр", "Мамыр", "Маусым", "Шiлде", "Тамыз", "Қыркүйек", "Қазан", "Қараша", "Желтоқсан"];
		    $ltMonths = ["Sausis", "Vasaris", "Kovas", "Balandis", "Gegužė", "Birželis", "Liepa", "Rugpjūtis", "Rugsėjis", "Spalis", "Lapkritis", "Gruodis"];
		    $lvMonths = ["Janvāris", "Februāris", "Marts", "Aprīlis", "Maijs", "Jūnijs", "Jūlijs", "Augusts", "Septembris", "Oktobris", "Novembris", "Decembris"];
		    $mkMonths = ["Јануари", "Февруари", "Март", "Април", "Мај", "Јуни", "Јули", "Август", "Септември", "Октомври", "Ноември", "Декември"];
		    $mnMonths = ["Нэгдүгээр сар", "Хоёрдугаар сар", "Гуравдугаар сар", "Дөрөвдүгээр сар", "Тавдугаар сар", "Зургаадугаар сар", "Долдугаар сар", "Наймдугаар сар", "Есдүгээр сар", "Аравдугаар сар", "Арваннэгдүгээр сар", "Арванхоёрдугаар сар"];
		    $msMonths = ["Januari", "Februari", "Mac", "April", "Mei", "Jun", "Julai", "Ogos", "September", "Oktober", "November", "Disember"];
		    $myMonths = ["ဇန်နဝါရီ", "ဖေဖော်ဝါရီ", "မတ်", "ဧပြီ", "မေ", "ဇွန်", "ဇူလိုင်", "သြဂုတ်", "စက်တင်ဘာ", "အောက်တိုဘာ", "နိုဝင်ဘာ", "ဒီဇင်ဘာ"];
		    $nlMonths = ["januari", "februari", "maart", "april", "mei", "juni", "juli", "augustus", "september", "oktober", "november", "december"];
		    $noMonths = ["Januar", "Februar", "Mars", "April", "Mai", "Juni", "Juli", "August", "September", "Oktober", "November", "Desember"];
		    $paMonths = ["ਜਨਵਰੀ", "ਫ਼ਰਵਰੀ", "ਮਾਰਚ", "ਅਪ੍ਰੈਲ", "ਮਈ", "ਜੂਨ", "ਜੁਲਾਈ", "ਅਗਸਤ", "ਸਤੰਬਰ", "ਅਕਤੂਬਰ", "ਨਵੰਬਰ", "ਦਸੰਬਰ"];
		    $plMonths = ["Styczeń", "Luty", "Marzec", "Kwiecień", "Maj", "Czerwiec", "Lipiec", "Sierpień", "Wrzesień", "Październik", "Listopad", "Grudzień"];
		    $ptMonths = ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"];
		    $roMonths = ["Ianuarie", "Februarie", "Martie", "Aprilie", "Mai", "Iunie", "Iulie", "August", "Septembrie", "Octombrie", "Noiembrie", "Decembrie"];
		    $ruMonths = ["Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"];
		    $skMonths = [ "Január", "Február", "Marec", "Apríl", "Máj", "Jún", "Júl", "August", "September", "Október", "November", "December"];
		    $slMonths = ["Januar", "Februar", "Marec", "April", "Maj", "Junij", "Julij", "Avgust", "September", "Oktober", "November", "December"];
		    $siMonths = ["ජනවාරි", "පෙබරවාරි", "මාර්තු", "අප්‍රේල්", "මැයි", "ජුනි", "ජූලි", "අගෝස්තු", "සැප්තැම්බර්", "ඔක්තෝබර්", "නොවැම්බර්", "දෙසැම්බර්"];
		    $sqMonths = ["Janar", "Shkurt", "Mars", "Prill", "Maj", "Qershor", "Korrik", "Gusht", "Shtator", "Tetor", "Nëntor", "Dhjetor"];
		    $srcyrMonths = ["Јануар", "Фебруар", "Март", "Април", "Мај", "Јун", "Јул", "Август", "Септембар", "Октобар", "Новембар", "Децембар"];
		    $srMonths = ["Januar", "Februar", "Mart", "April", "Maj", "Jun", "Jul", "Avgust", "Septembar", "Oktobar", "Novembar", "Decembar"];
		    $svMonths = ["Januari", "Februari", "Mars", "April", "Maj", "Juni", "Juli", "Augusti", "September", "Oktober", "November", "December"];
		    $thMonths = ["มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"];
		    $trMonths = ["Ocak", "Şubat", "Mart", "Nisan", "Mayıs", "Haziran", "Temmuz", "Ağustos", "Eylül", "Ekim", "Kasım", "Aralık"];
		    $ukMonths = ["Січень", "Лютий", "Березень", "Квітень", "Травень", "Червень", "Липень", "Серпень", "Вересень", "Жовтень", "Листопад", "Грудень"];
		    $uzMonths = ["Январ", "Феврал", "Март", "Апрел", "Май", "Июн", "Июл", "Август", "Сентябр", "Октябр", "Ноябр", "Декабр"];
		    $uzlatnMonths = ["Yanvar", "Fevral", "Mart", "Aprel", "May", "Iyun", "Iyul", "Avgust", "Sentabr", "Oktabr", "Noyabr", "Dekabr"];
		    $vnMonths = ["Tháng một", "Tháng hai", "Tháng ba", "Tháng tư", "Tháng năm", "Tháng sáu", "Tháng bảy", "Tháng tám", "Tháng chín", "Tháng mười", "Tháng 11", "Tháng 12"];
		    $zhMonths = ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"];
		    
		    
		    if($delivery_type == "delivery") {
		    	$delivery_date_settings = get_option('coderockz_woo_delivery_date_settings');
				$calendar_locale = (isset($delivery_date_settings['calendar_locale']) && !empty($delivery_date_settings['calendar_locale'])) ? $delivery_date_settings['calendar_locale'] : "default";
		    } elseif ($delivery_type == "pickup") {
		    	$pickup_date_settings = get_option('coderockz_woo_delivery_pickup_date_settings');
				$calendar_locale = (isset($pickup_date_settings['calendar_locale']) && !empty($pickup_date_settings['calendar_locale'])) ? $pickup_date_settings['calendar_locale'] : "default";
		    }
		    

			switch ($calendar_locale) {
			    case "ar":
			        return str_replace($arMonths, $enMonths, $date);
			        break;
			    case "at":
			        return str_replace($atMonths, $enMonths, $date);
			        break;
			    case "az":
			        return str_replace($azMonths, $enMonths, $date);
			        break;
			    case "be":
			        return str_replace($beMonths, $enMonths, $date);
			        break;
			    case "bg":
			        return str_replace($bgMonths, $enMonths, $date);
			        break;
			    case "bn":
			        return str_replace($bnMonths, $enMonths, $date);
			        break;
			    case "bs":
			        return str_replace($bsMonths, $enMonths, $date);
			        break;
			    case "cat":
			        return str_replace($catMonths, $enMonths, $date);
			        break;
			    case "cs":
			        return str_replace($csMonths, $enMonths, $date);
			        break;
			    case "cy":
			        return str_replace($cyMonths, $enMonths, $date);
			        break;
			    case "da":
			        return str_replace($daMonths, $enMonths, $date);
			        break;
			    case "de":
			        return str_replace($deMonths, $enMonths, $date);
			        break;
			    case "default":
			        return str_replace($defaultMonths, $enMonths, $date);
			        break;
			    case "eo":
			        return str_replace($eoMonths, $enMonths, $date);
			        break;
			    case "es":
			        return str_replace($esMonths, $enMonths, $date);
			        break;
			    case "et":
			        return str_replace($etMonths, $enMonths, $date);
			        break;
			    case "fi":
			        return str_replace($fiMonths, $enMonths, $date);
			        break;
			    case "fo":
			        return str_replace($foMonths, $enMonths, $date);
			        break;
			    case "fr":
			        return str_replace($frMonths, $enMonths, $date);
			        break;
			    case "fa":
			        return str_replace($faMonths, $enMonths, $date);
			        break;
			    case "ga":
			        return str_replace($gaMonths, $enMonths, $date);
			        break;
			    case "gr":
			        return str_replace($grMonths, $enMonths, $date);
			        break;
			    case "he":
			        return str_replace($heMonths, $enMonths, $date);
			        break;
			    case "hi":
			        return str_replace($hiMonths, $enMonths, $date);
			        break;
			    case "hr":
			        return str_replace($hrMonths, $enMonths, $date);
			        break;
			    case "hu":
			        return str_replace($huMonths, $enMonths, $date);
			        break;
			    case "id":
			        return str_replace($idMonths, $enMonths, $date);
			        break;
			    case "is":
			        return str_replace($isMonths, $enMonths, $date);
			        break;
			    case "it":
			        return str_replace($itMonths, $enMonths, $date);
			        break;
			    case "ja":
			        return str_replace($jaMonths, $enMonths, $date);
			        break;
			    case "ka":
			        return str_replace($kaMonths, $enMonths, $date);
			        break;
			    case "km":
			        return str_replace($kmMonths, $enMonths, $date);
			        break;
			    case "ko":
			        return str_replace($koMonths, $enMonths, $date);
			        break;
			    case "kz":
			        return str_replace($kzMonths, $enMonths, $date);
			        break;
			    case "lt":
			        return str_replace($ltMonths, $enMonths, $date);
			        break;
			    case "lv":
			        return str_replace($lvMonths, $enMonths, $date);
			        break;
			    case "mk":
			        return str_replace($mkMonths, $enMonths, $date);
			        break;
			    case "mn":
			        return str_replace($mnMonths, $enMonths, $date);
			        break;
			    case "ms":
			        return str_replace($msMonths, $enMonths, $date);
			        break;
			    case "my":
			        return str_replace($myMonths, $enMonths, $date);
			        break;
			    case "nl":
			        return str_replace($nlMonths, $enMonths, $date);
			        break;
			    case "no":
			        return str_replace($noMonths, $enMonths, $date);
			        break;
			    case "pa":
			        return str_replace($paMonths, $enMonths, $date);
			        break;
			    case "pl":
			        return str_replace($plMonths, $enMonths, $date);
			        break;
			    case "pt":
			        return str_replace($ptMonths, $enMonths, $date);
			        break;
			    case "ro":
			        return str_replace($roMonths, $enMonths, $date);
			        break;
			    case "ru":
			        return str_replace($ruMonths, $enMonths, $date);
			        break;
			    case "sk":
			        return str_replace($skMonths, $enMonths, $date);
			        break;
			    case "sl":
			        return str_replace($slMonths, $enMonths, $date);
			        break;
			    case "si":
			        return str_replace($siMonths, $enMonths, $date);
			        break;
			    case "sq":
			        return str_replace($sqMonths, $enMonths, $date);
			        break;
			    case "sr-cyr":
			        return str_replace($srcyrMonths, $enMonths, $date);
			        break;
			    case "sr":
			        return str_replace($srMonths, $enMonths, $date);
			        break;
			    case "sv":
			        return str_replace($svMonths, $enMonths, $date);
			        break;
			    case "th":
			        return str_replace($thMonths, $enMonths, $date);
			        break;
			    case "tr":
			        return str_replace($trMonths, $enMonths, $date);
			        break;
			    case "uk":
			        return str_replace($ukMonths, $enMonths, $date);
			        break;
			    case "uz":
			        return str_replace($uzMonths, $enMonths, $date);
			        break;
			    case "uz-latn":
			        return str_replace($uzlatnMonths, $enMonths, $date);
			        break;
			    case "vn":
			        return str_replace($vnMonths, $enMonths, $date);
			        break;
			    case "zh":
			        return str_replace($zhMonths, $enMonths, $date);
			        break;

			}
		    
		}

		public function date_conversion_to_locale( $date, $delivery_type="delivery" ) {
			$arMonths = ["يناير","فبراير","مارس","أبريل","مايو","يونيو","يوليو","أغسطس","سبتمبر","أكتوبر","نوفمبر","ديسمبر"];
			$atMonths = ["Jänner","Februar","März","April","Mai","Juni","Juli","August","September","Oktober","November","Dezember"];
		    $azMonths = ["Yanvar","Fevral","Mart","Aprel","May","İyun","İyul","Avqust","Sentyabr","Oktyabr","Noyabr","Dekabr"];
			$beMonths = ["Студзень","Люты","Сакавік","Красавік","Травень","Чэрвень","Ліпень","Жнівень","Верасень","Кастрычнік","Лістапад","Снежань"];
			$bgMonths = ["Януари","Февруари","Март","Април","Май","Юни","Юли","Август","Септември","Октомври","Ноември","Декември"];
		    $bnMonths = ["জানুয়ারী","ফেব্রুয়ারী","মার্চ","এপ্রিল","মে","জুন","জুলাই","আগস্ট","সেপ্টেম্বর","অক্টোবর","নভেম্বর","ডিসেম্বর"];
		    $bsMonths = ["Januar","Februar","Mart","April","Maj","Juni","Juli","Avgust","Septembar","Oktobar","Novembar","Decembar"];
		    $catMonths = ["Gener","Febrer","Març","Abril","Maig","Juny","Juliol","Agost","Setembre","Octubre","Novembre","Desembre"];
		    $csMonths = ["Leden","Únor","Březen","Duben","Květen","Červen","Červenec","Srpen","Září","Říjen","Listopad","Prosinec"];
		    $cyMonths = ["Ionawr","Chwefror","Mawrth","Ebrill","Mai","Mehefin","Gorffennaf","Awst","Medi","Hydref","Tachwedd","Rhagfyr"];
		    $daMonths = ["januar","februar","marts","april","maj","juni","juli","august","september","oktober","november","december"];
		    $deMonths = ["Januar","Februar","März","April","Mai","Juni","Juli","August","September","Oktober","November","Dezember"];
		    $defaultMonths = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
		    $eoMonths = ["januaro","februaro","marto","aprilo","majo","junio","julio","aŭgusto","septembro","oktobro","novembro","decembro"];
		    $esMonths = ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
		    $etMonths = ["Jaanuar","Veebruar","Märts","Aprill","Mai","Juuni","Juuli","August","September","Oktoober","November","Detsember"];
		    $enMonths = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
		    $fiMonths = ["Tammikuu", "Helmikuu", "Maaliskuu", "Huhtikuu", "Toukokuu", "Kesäkuu", "Heinäkuu", "Elokuu", "Syyskuu", "Lokakuu", "Marraskuu", "Joulukuu"];
		    $foMonths = ["Januar", "Februar", "Mars", "Apríl", "Mai", "Juni", "Juli", "August", "Septembur", "Oktobur", "Novembur", "Desembur"];
		    $frMonths = ["janvier", "février", "mars", "avril", "mai", "juin", "juillet", "août", "septembre", "octobre", "novembre", "décembre"];
		    $faMonths = ["ژانویه", "فوریه", "مارس", "آوریل", "مه", "ژوئن", "ژوئیه", "اوت", "سپتامبر", "اکتبر", "نوامبر", "دسامبر"];
		    $gaMonths = ["Eanáir", "Feabhra", "Márta", "Aibreán", "Bealtaine", "Meitheamh", "Iúil", "Lúnasa", "Meán Fómhair", "Deireadh Fómhair", "Samhain", "Nollaig"];
		    $grMonths = ["Ιανουάριος", "Φεβρουάριος", "Μάρτιος", "Απρίλιος", "Μάιος", "Ιούνιος", "Ιούλιος", "Αύγουστος", "Σεπτέμβριος", "Οκτώβριος", "Νοέμβριος", "Δεκέμβριος"];
		    $heMonths = ["ינואר", "פברואר", "מרץ", "אפריל", "מאי", "יוני", "יולי", "אוגוסט", "ספטמבר", "אוקטובר", "נובמבר", "דצמבר"];
		    $hiMonths = ["जनवरी ", "फरवरी", "मार्च", "अप्रेल", "मई", "जून", "जूलाई", "अगस्त ", "सितम्बर", "अक्टूबर", "नवम्बर", "दिसम्बर"];
		    $hrMonths = ["Siječanj", "Veljača", "Ožujak", "Travanj", "Svibanj", "Lipanj", "Srpanj", "Kolovoz", "Rujan", "Listopad", "Studeni", "Prosinac"];
		    $huMonths = ["Január", "Február", "Március", "Április", "Május", "Június", "Július", "Augusztus", "Szeptember", "Október", "November", "December"];
		    $idMonths = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
		    $isMonths = ["Janúar", "Febrúar", "Mars", "Apríl", "Maí", "Júní", "Júlí", "Ágúst", "September", "Október", "Nóvember", "Desember"];
		    $itMonths = ["Gennaio", "Febbraio", "Marzo", "Aprile", "Maggio", "Giugno", "Luglio", "Agosto", "Settembre", "Ottobre", "Novembre", "Dicembre"];
		    $jaMonths = ["1月", "2月", "3月", "4月", "5月", "6月", "7月", "8月", "9月", "10月", "11月", "12月"];
		    $kaMonths = ["იანვარი", "თებერვალი", "მარტი", "აპრილი", "მაისი", "ივნისი", "ივლისი", "აგვისტო", "სექტემბერი", "ოქტომბერი", "ნოემბერი", "დეკემბერი"];
		    $kmMonths = ["មករា", "កុម្ភះ", "មីនា", "មេសា", "ឧសភា", "មិថុនា", "កក្កដា", "សីហា", "កញ្ញា", "តុលា", "វិច្ឆិកា", "ធ្នូ"];
		    $koMonths = ["1월", "2월", "3월", "4월", "5월", "6월", "7월", "8월", "9월", "10월", "11월", "12월"];
		    $kzMonths = ["Қаңтар", "Ақпан", "Наурыз", "Сәуiр", "Мамыр", "Маусым", "Шiлде", "Тамыз", "Қыркүйек", "Қазан", "Қараша", "Желтоқсан"];
		    $ltMonths = ["Sausis", "Vasaris", "Kovas", "Balandis", "Gegužė", "Birželis", "Liepa", "Rugpjūtis", "Rugsėjis", "Spalis", "Lapkritis", "Gruodis"];
		    $lvMonths = ["Janvāris", "Februāris", "Marts", "Aprīlis", "Maijs", "Jūnijs", "Jūlijs", "Augusts", "Septembris", "Oktobris", "Novembris", "Decembris"];
		    $mkMonths = ["Јануари", "Февруари", "Март", "Април", "Мај", "Јуни", "Јули", "Август", "Септември", "Октомври", "Ноември", "Декември"];
		    $mnMonths = ["Нэгдүгээр сар", "Хоёрдугаар сар", "Гуравдугаар сар", "Дөрөвдүгээр сар", "Тавдугаар сар", "Зургаадугаар сар", "Долдугаар сар", "Наймдугаар сар", "Есдүгээр сар", "Аравдугаар сар", "Арваннэгдүгээр сар", "Арванхоёрдугаар сар"];
		    $msMonths = ["Januari", "Februari", "Mac", "April", "Mei", "Jun", "Julai", "Ogos", "September", "Oktober", "November", "Disember"];
		    $myMonths = ["ဇန်နဝါရီ", "ဖေဖော်ဝါရီ", "မတ်", "ဧပြီ", "မေ", "ဇွန်", "ဇူလိုင်", "သြဂုတ်", "စက်တင်ဘာ", "အောက်တိုဘာ", "နိုဝင်ဘာ", "ဒီဇင်ဘာ"];
		    $nlMonths = ["januari", "februari", "maart", "april", "mei", "juni", "juli", "augustus", "september", "oktober", "november", "december"];
		    $noMonths = ["Januar", "Februar", "Mars", "April", "Mai", "Juni", "Juli", "August", "September", "Oktober", "November", "Desember"];
		    $paMonths = ["ਜਨਵਰੀ", "ਫ਼ਰਵਰੀ", "ਮਾਰਚ", "ਅਪ੍ਰੈਲ", "ਮਈ", "ਜੂਨ", "ਜੁਲਾਈ", "ਅਗਸਤ", "ਸਤੰਬਰ", "ਅਕਤੂਬਰ", "ਨਵੰਬਰ", "ਦਸੰਬਰ"];
		    $plMonths = ["Styczeń", "Luty", "Marzec", "Kwiecień", "Maj", "Czerwiec", "Lipiec", "Sierpień", "Wrzesień", "Październik", "Listopad", "Grudzień"];
		    $ptMonths = ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"];
		    $roMonths = ["Ianuarie", "Februarie", "Martie", "Aprilie", "Mai", "Iunie", "Iulie", "August", "Septembrie", "Octombrie", "Noiembrie", "Decembrie"];
		    $ruMonths = ["Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"];
		    $skMonths = [ "Január", "Február", "Marec", "Apríl", "Máj", "Jún", "Júl", "August", "September", "Október", "November", "December"];
		    $slMonths = ["Januar", "Februar", "Marec", "April", "Maj", "Junij", "Julij", "Avgust", "September", "Oktober", "November", "December"];
		    $siMonths = ["ජනවාරි", "පෙබරවාරි", "මාර්තු", "අප්‍රේල්", "මැයි", "ජුනි", "ජූලි", "අගෝස්තු", "සැප්තැම්බර්", "ඔක්තෝබර්", "නොවැම්බර්", "දෙසැම්බර්"];
		    $sqMonths = ["Janar", "Shkurt", "Mars", "Prill", "Maj", "Qershor", "Korrik", "Gusht", "Shtator", "Tetor", "Nëntor", "Dhjetor"];
		    $srcyrMonths = ["Јануар", "Фебруар", "Март", "Април", "Мај", "Јун", "Јул", "Август", "Септембар", "Октобар", "Новембар", "Децембар"];
		    $srMonths = ["Januar", "Februar", "Mart", "April", "Maj", "Jun", "Jul", "Avgust", "Septembar", "Oktobar", "Novembar", "Decembar"];
		    $svMonths = ["Januari", "Februari", "Mars", "April", "Maj", "Juni", "Juli", "Augusti", "September", "Oktober", "November", "December"];
		    $thMonths = ["มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"];
		    $trMonths = ["Ocak", "Şubat", "Mart", "Nisan", "Mayıs", "Haziran", "Temmuz", "Ağustos", "Eylül", "Ekim", "Kasım", "Aralık"];
		    $ukMonths = ["Січень", "Лютий", "Березень", "Квітень", "Травень", "Червень", "Липень", "Серпень", "Вересень", "Жовтень", "Листопад", "Грудень"];
		    $uzMonths = ["Январ", "Феврал", "Март", "Апрел", "Май", "Июн", "Июл", "Август", "Сентябр", "Октябр", "Ноябр", "Декабр"];
		    $uzlatnMonths = ["Yanvar", "Fevral", "Mart", "Aprel", "May", "Iyun", "Iyul", "Avgust", "Sentabr", "Oktabr", "Noyabr", "Dekabr"];
		    $vnMonths = ["Tháng một", "Tháng hai", "Tháng ba", "Tháng tư", "Tháng năm", "Tháng sáu", "Tháng bảy", "Tháng tám", "Tháng chín", "Tháng mười", "Tháng 11", "Tháng 12"];
		    $zhMonths = ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"];
		    
		    if($delivery_type == "delivery") {
		    	$delivery_date_settings = get_option('coderockz_woo_delivery_date_settings');
				$calendar_locale = (isset($delivery_date_settings['calendar_locale']) && !empty($delivery_date_settings['calendar_locale'])) ? $delivery_date_settings['calendar_locale'] : "default";
		    } elseif ($delivery_type == "pickup") {
		    	$pickup_date_settings = get_option('coderockz_woo_delivery_pickup_date_settings');
				$calendar_locale = (isset($pickup_date_settings['calendar_locale']) && !empty($pickup_date_settings['calendar_locale'])) ? $pickup_date_settings['calendar_locale'] : "default";
		    }

			switch ($calendar_locale) {
			    case "ar":
			        return str_replace($enMonths, $arMonths, $date);
			        break;
			    case "at":
			        return str_replace($enMonths, $atMonths, $date);
			        break;
			    case "az":
			        return str_replace($enMonths, $azMonths, $date);
			        break;
			    case "be":
			        return str_replace($enMonths, $beMonths, $date);
			        break;
			    case "bg":
			        return str_replace($enMonths, $bgMonths, $date);
			        break;
			    case "bn":
			        return str_replace($enMonths, $bnMonths, $date);
			        break;
			    case "bs":
			        return str_replace($enMonths, $bsMonths, $date);
			        break;
			    case "cat":
			        return str_replace($enMonths, $catMonths, $date);
			        break;
			    case "cs":
			        return str_replace($enMonths, $csMonths, $date);
			        break;
			    case "cy":
			        return str_replace($enMonths, $cyMonths, $date);
			        break;
			    case "da":
			        return str_replace($enMonths, $daMonths, $date);
			        break;
			    case "de":
			        return str_replace($enMonths, $deMonths, $date);
			        break;
			    case "default":
			        return str_replace($enMonths, $defaultMonths, $date);
			        break;
			    case "eo":
			        return str_replace($enMonths, $eoMonths, $date);
			        break;
			    case "es":
			        return str_replace($enMonths, $esMonths, $date);
			        break;
			    case "et":
			        return str_replace($enMonths, $etMonths, $date);
			        break;
			    case "fi":
			        return str_replace($enMonths, $fiMonths, $date);
			        break;
			    case "fo":
			        return str_replace($enMonths, $foMonths, $date);
			        break;
			    case "fr":
			        return str_replace($enMonths, $frMonths, $date);
			        break;
			    case "fa":
			        return str_replace($enMonths, $faMonths, $date);
			        break;
			    case "ga":
			        return str_replace($enMonths, $gaMonths, $date);
			        break;
			    case "gr":
			        return str_replace($enMonths, $grMonths, $date);
			        break;
			    case "he":
			        return str_replace($enMonths, $heMonths, $date);
			        break;
			    case "hi":
			        return str_replace($enMonths, $hiMonths, $date);
			        break;
			    case "hr":
			        return str_replace($enMonths, $hrMonths, $date);
			        break;
			    case "hu":
			        return str_replace($enMonths, $huMonths, $date);
			        break;
			    case "id":
			        return str_replace($enMonths, $idMonths, $date);
			        break;
			    case "is":
			        return str_replace($enMonths, $isMonths, $date);
			        break;
			    case "it":
			        return str_replace($enMonths, $itMonths, $date);
			        break;
			    case "ja":
			        return str_replace($enMonths, $jaMonths, $date);
			        break;
			    case "ka":
			        return str_replace($enMonths, $kaMonths, $date);
			        break;
			    case "km":
			        return str_replace($enMonths, $kmMonths, $date);
			        break;
			    case "ko":
			        return str_replace($enMonths, $koMonths, $date);
			        break;
			    case "kz":
			        return str_replace($enMonths, $kzMonths, $date);
			        break;
			    case "lt":
			        return str_replace($enMonths, $ltMonths, $date);
			        break;
			    case "lv":
			        return str_replace($enMonths, $lvMonths, $date);
			        break;
			    case "mk":
			        return str_replace($enMonths, $mkMonths, $date);
			        break;
			    case "mn":
			        return str_replace($enMonths, $mnMonths, $date);
			        break;
			    case "ms":
			        return str_replace($enMonths, $msMonths, $date);
			        break;
			    case "my":
			        return str_replace($enMonths, $myMonths, $date);
			        break;
			    case "nl":
			        return str_replace($enMonths, $nlMonths, $date);
			        break;
			    case "no":
			        return str_replace($enMonths, $noMonths, $date);
			        break;
			    case "pa":
			        return str_replace($enMonths, $paMonths, $date);
			        break;
			    case "pl":
			        return str_replace($enMonths, $plMonths, $date);
			        break;
			    case "pt":
			        return str_replace($enMonths, $ptMonths, $date);
			        break;
			    case "ro":
			        return str_replace($enMonths, $roMonths, $date);
			        break;
			    case "ru":
			        return str_replace($enMonths, $ruMonths, $date);
			        break;
			    case "sk":
			        return str_replace($enMonths, $skMonths, $date);
			        break;
			    case "sl":
			        return str_replace($enMonths, $slMonths, $date);
			        break;
			    case "si":
			        return str_replace($enMonths, $siMonths, $date);
			        break;
			    case "sq":
			        return str_replace($enMonths, $sqMonths, $date);
			        break;
			    case "sr-cyr":
			        return str_replace($enMonths, $srcyrMonths, $date);
			        break;
			    case "sr":
			        return str_replace($enMonths, $srMonths, $date);
			        break;
			    case "sv":
			        return str_replace($enMonths, $svMonths, $date);
			        break;
			    case "th":
			        return str_replace($enMonths, $thMonths, $date);
			        break;
			    case "tr":
			        return str_replace($enMonths, $trMonths, $date);
			        break;
			    case "uk":
			        return str_replace($enMonths, $ukMonths, $date);
			        break;
			    case "uz":
			        return str_replace($enMonuzs, $thMonths, $date);
			        break;
			    case "uz-latn":
			        return str_replace($enMonths, $uzlatnMonths, $date);
			        break;
			    case "vn":
			        return str_replace($enMonths, $vnMonths, $date);
			        break;
			    case "zh":
			        return str_replace($enMonuzs, $zhMonths, $date);
			        break;

			}
		    
		}		

	}

}