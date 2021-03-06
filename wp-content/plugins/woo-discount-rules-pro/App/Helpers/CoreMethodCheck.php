<?php

namespace WDRPro\App\Helpers;

use Wdr\App\Helpers\Helper;
use Wdr\App\Helpers\Woocommerce;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class CoreMethodCheck
{
    public static function getConvertedFixedPrice($value, $type = ''){
        if(method_exists('\Wdr\App\Helpers\Woocommerce', 'getConvertedFixedPrice')){
            return Woocommerce::getConvertedFixedPrice($value, $type);
        }
        return $value;
    }

    public static function create_nonce($action = -1){
        if(method_exists('\Wdr\App\Helpers\Helper', 'create_nonce')){
            return Helper::create_nonce($action);
        }
        return '';
    }

    public static function validateRequest($method){
        if(method_exists('\Wdr\App\Helpers\Helper', 'validateRequest')){
            return Helper::validateRequest($method);
        }
        return false;
    }

    public static function hasAdminPrivilege(){
        if(method_exists('\Wdr\App\Helpers\Helper', 'hasAdminPrivilege')){
            return Helper::hasAdminPrivilege();
        }
        return false;
    }
}