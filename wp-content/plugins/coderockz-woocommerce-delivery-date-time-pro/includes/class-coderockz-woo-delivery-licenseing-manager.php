<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Coderockz_Woo_Delivery_Licensing_Manager' ) ) {
    class Coderockz_Woo_Delivery_Licensing_Manager {
        
        private $vars;

        public function __construct() {
            $this->vars = array (
                // The plugin file, if this array is defined in the plugin
                'plugin_file' => __FILE__,

                // The current version of the plugin.
                // Also need to change in readme.txt and plugin header.
                'version' => '1.0.0',

                // The main URL of your store for license verification
                'store_url' => 'https://coderockz.com',

                // Your name
                'author' => 'CodeRockz',

                // The URL to renew or purchase a license
                'purchase_url' => 'https://coderockz.com/downloads/woocommerce-delivery-date-time/',

                // The URL of your contact page
                'contact_url' => 'https://coderockz.com/contact',

                // This should match the download name exactly
                'item_name' => 'WooCommerce Delivery Date Time Plugin (Woo Delivery)',

                // The option names to store the license key and activation status
                'license_key' => 'coderockz-woo-delivery-license-key',
                'license_status' => 'coderockz-woo-delivery-license-status',

                // Option group param for the settings api
                'option_group' => 'coderockz-woo-delivery-license',

                // The plugin settings admin page slug
                'admin_page_slug' => 'coderockz-woo-delivery-settings',

                // If using add_menu_page, this is the parent slug to add a submenu item underneath.
                // Otherwise we'll add our own parent menu item.
                'parent_menu_slug' => '',

                // The translatable title of the plugin
                'plugin_title' => __( 'WooCommerce Delivery Date Time', 'coderockz-woo-delivery' ),

                // Title of the settings page with activation key
                'settings_page_title' => __( 'Settings', 'coderockz-woo-delivery' ),

                // If this plugin depends on another plugin to be installed,
                // we can either check that a class exists or plugin is active.
                // Only one is needed.
                'dependent_class_to_check' => '', // name of class to verify...
                'dependent_plugin' => '', // ...or plugin name for is_plugin_active() call
                'dependent_plugin_title' => __( 'Dependent Plugin Name', 'coderockz-woo-delivery' ),
            );

            /*add_action( 'admin_menu', array( $this, 'license_menu' ), 99 );*/
            add_action( 'plugins_loaded', array( $this, 'coderockz_woo_delivery_license_notice' ) );
            add_action( 'admin_init', array( $this, 'register_option' ) );
            add_action( 'admin_init', array( $this, 'activate_license' ) );
            add_action( 'admin_init', array( $this, 'deactivate_license' ) );
            add_action( 'admin_init', array( $this, 'check_license' ) );
        }

        public function get_var( $var ) {
            if ( isset( $this->vars[ $var ] ) )
                return $this->vars[ $var ];
            return false;
        }

    	/**
    	 * Show an error message that license needs to be activated
    	 */
        public function coderockz_woo_delivery_license_notice() {
            if ( 'valid' != get_option( $this->get_var( 'license_status' ) ) ) {
                if ( ( ! isset( $_GET['page'] ) or $this->get_var( 'admin_page_slug' ) != $_GET['page'] ) ) {
                    add_action( 'admin_notices', function() {
                        echo '<div class="error"><img style="width:50px;margin-top:5px;" src="'.CODEROCKZ_WOO_DELIVERY_URL.'admin/images/woo-delivery-logo.png" alt="woocommerce-delivery-date-time"><p style="display:inline-block;vertical-align: top;margin-top: 15px;margin-left:5px;font-weight: 600;">' .
                             sprintf( __( 'The %s license needs to be activated. %sActivate Now%s', 'coderockz-woo-delivery' ), $this->get_var( 'plugin_title' ), '<a href="' . admin_url( 'admin.php?page=' . $this->get_var( 'admin_page_slug' ) ) . '">', '</a>' ) .
                             '</p></div>';
                    } );
                } else {
                    add_action( 'admin_notices', function() {
                        echo '<div class="notice"><img style="width:50px;margin-top:5px;" src="'.CODEROCKZ_WOO_DELIVERY_URL.'admin/images/woo-delivery-logo.png" alt="woocommerce-delivery-date-time"><p style="display:inline-block;vertical-align: top;margin-top: 15px;margin-left:5px;font-weight: 600;">' .
                             sprintf( __( 'WooCommerce Delivery Date Time License key invalid. Need a license? %sPurchase Now%s', 'coderockz-woo-delivery' ),'<a target="_blank" href="' . $this->get_var( 'purchase_url' ) . '">', '</a>' ) .
                             '</p></div>';
                    } );
                }
            }

            /**
             * If your plugin depends on another plugin, adds a condition to verify
             * if that plugin is installed.
             */
            if ( ( $this->get_var( 'dependent_class_to_check' ) and ! class_exists( $this->get_var( 'dependent_class_to_check' ) ) ) or
                 ( $this->get_var( 'dependent_plugin' ) and ! is_plugin_active( $this->get_var( 'dependent_plugin' ) ) ) ) {
                add_action( 'admin_notices', function() {
                    echo '<div class="error"><img style="width:50px;margin-top:5px;" src="'.CODEROCKZ_WOO_DELIVERY_URL.'admin/images/woo-delivery-logo.png" alt="woocommerce-delivery-date-time"><p style="display:inline-block;vertical-align: top;margin-top: 15px;margin-left:5px;font-weight: 600;">' .
                         sprintf( __( 'The %s plugin requires %s to be installed and activated', 'coderockz-woo-delivery' ), $this->get_var( 'plugin_title' ), $this->get_var( 'dependent_plugin_title' ) ) .
                         '</p></div>';
                } );
            }

        }

        public function register_option() {
            // creates our settings in the options table
            register_setting( $this->get_var( 'option_group' ), $this->get_var( 'license_key' ), array( $this, 'sanitize_license' ) );
        }

        public function sanitize_license( $new ) {
            $old = get_option( $this->get_var( 'license_key' ) );
            if ( $old && $old != $new ) {
                delete_option( $this->get_var( 'license_status' ) ); // new license has been entered, so must reactivate
            }
            return $new;
        }

        public function activate_license() {
            // listen for our activate button to be clicked
            if ( isset( $_POST[ $this->get_var( 'option_group' ) . '_activate' ] ) ) {
                // run a quick security check
                if ( ! check_admin_referer( $this->get_var( 'option_group' ) . '_nonce', $this->get_var( 'option_group' ) . '_nonce' ) )
                    return; // get out if we didn't click the Activate button

                // save the license key to the database
                update_option( $this->get_var( 'license_key' ), $_POST[$this->get_var( 'license_key' )] );

                // retrieve the license from the database
                $license = trim( get_option( $this->get_var( 'license_key' ) ) );

                // data to send in our API request
                $api_params = array(
                    'edd_action'=> 'activate_license',
                    'license' 	=> $license,
                    'item_name' => urlencode( $this->get_var( 'item_name' ) ), // the name of our product in EDD
                    'url'       => home_url()
                );

                // Call the custom API.
                $response = wp_remote_post( $this->get_var( 'store_url' ), array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

                // make sure the response came back okay
                if ( is_wp_error( $response ) ) {
    	            add_settings_error(
    		            $this->get_var( 'option_group' ),
    		            'activate',
    		            __( 'There was an error activating the license, please verify your license is correct and try again or contact support.', 'coderockz-woo-delivery' )
    	            );
    	            return false;
                }

                // decode the license data
                $license_data = json_decode( wp_remote_retrieve_body( $response ) );

                // $license_data->license will be either "valid" or "invalid"
                update_option( $this->get_var( 'license_status' ), $license_data->license );
    	        if ( 'valid' != $license_data->license ) {
    		        add_settings_error(
    			        $this->get_var( 'option_group' ),
    			        'activate',
    			        __( 'There was an error activating the license, please verify your license is correct and try again or contact support.', 'coderockz-woo-delivery' )
    		        );
    	        }
            }
        }

        public function deactivate_license() {
            // listen for our activate button to be clicked
            if ( isset( $_POST[ $this->get_var( 'option_group' ) . '_deactivate'] ) ) {
                // run a quick security check
                if( ! check_admin_referer( $this->get_var( 'option_group' ) . '_nonce', $this->get_var( 'option_group' ) . '_nonce' ) )
                    return; // get out if we didn't click the Activate button

                // retrieve the license from the database
                $license = trim( get_option( $this->get_var( 'license_key' ) ) );

                // data to send in our API request
                $api_params = array(
                    'edd_action'=> 'deactivate_license',
                    'license' 	=> $license,
                    'item_name' => urlencode( $this->get_var( 'item_name' ) ), // the name of our product in EDD
                    'url'       => home_url()
                );

                // Call the custom API.
                $response = wp_remote_post( $this->get_var( 'store_url' ), array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

                // make sure the response came back okay
                if ( is_wp_error( $response ) ) {
    	            add_settings_error(
    		            $this->get_var( 'option_group' ),
    		            'deactivate',
    		            __( 'There was an error deactivating the license, please try again or contact support.', 'coderockz-woo-delivery' )
                    );
                    return false;
                }

                // decode the license data
                $license_data = json_decode( wp_remote_retrieve_body( $response ) );

                // $license_data->license will be either "deactivated" or "failed"
    	        if ( 'deactivated' == $license_data->license ) {
    		        add_settings_error(
    			        $this->get_var( 'option_group' ),
    			        'deactivate',
    			        __( 'License deactivated', 'coderockz-woo-delivery' )
    		        );
    		        delete_option( $this->get_var( 'license_status' ) );
    	        } else {
    		        add_settings_error(
    			        $this->get_var( 'option_group' ),
    			        'deactivate',
    			        __( 'Unable to deactivate license, please try again or contact support.', 'amazing-linker' )
    		        );
    	        }
            }
        }

        public function check_license() {
            if ( get_transient( $this->get_var( 'license_status' ) . '_checking' ) )
                return;

            $license = trim( get_option( $this->get_var( 'license_key' ) ) );

            $api_params = array(
                'edd_action' => 'check_license',
                'license' => $license,
                'item_name' => urlencode( $this->get_var( 'item_name' ) ),
                'url'       => home_url()
            );

            // Call the custom API.
            $response = wp_remote_post(
                $this->get_var( 'store_url' ),
                array(
                    'timeout' => 15,
                    'sslverify' => false,
                    'body' => $api_params
                )
            );

            if ( is_wp_error( $response ) )
                return false;

            $license_data = json_decode(
                wp_remote_retrieve_body( $response )
            );

            if ( $license_data->license != 'valid' ) {
                delete_option( $this->get_var( 'license_status' ) );
            }

            // Set to check again in 12 hours
            set_transient(
                $this->get_var( 'license_status' ) . '_checking',
                $license_data,
                ( 60 * 60 * 12 )
            );
        }
    }

}