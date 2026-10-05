<?php

if (!function_exists('get_fulltime')) {

    function get_fulltime($date, $format = 'd, M Y @ h:i a') {
        $new_date = new \DateTime($date);
        return $new_date->format($format);
    }

}

if (!function_exists('get_date')) {

    function get_date($date) {
        return get_fulltime($date, 'd, M Y');
    }

}

if (!function_exists('get_price')) {

    function get_price($price, $symbol = null) {
        return currency_symbol() . number_format($price);
    }

}

if (!function_exists('currency_symbol')) {

    function currency_symbol() {
        return 'PKR ';
    }

}

if (!function_exists("newCount")) {

    function newCount($array) {
        if (is_array($array) || is_object($array)) {
            return count($array);
        } else {
            return 0;
        }
    }

}

if (!function_exists('dummy_image')) {

    function dummy_image($type = null) {
        switch ($type) {
            case 'categories':
                return asset('admin_assets/images/category_dummy.jpg');
            case 'services':
                return asset('admin_assets/images/small/img-7.jpg');
            case 'user':
                return asset('admin_assets/images/users/user-6.jpg');
            default:
                return asset('admin_assets/images/category_dummy.jpg');
        }
    }

}

if (!function_exists('check_file')) {

    function check_file($file = null, $type = null) {
        if ($file && $file != '' && file_exists($file)) {
            return asset($file);
        } else {
            return dummy_image($type);
        }
    }

}

if (!function_exists('encode')) {

    function hashids_encode($str) {
        return \Hashids::encode($str);
    }

}

if (!function_exists('hashids_decode')) {

    function hashids_decode($str) {
        try {
            return \Hashids::decode($str)[0];
        } catch (Exception $e) {
            return abort(404);
        }
    }

}

if (!function_exists('order_status_colors')) {

    function order_status_colors($ind) {
        $arr = array(
            'booked' => 'warning',
            'assigned' => 'danger',
            'received' => 'info',
            'shipped' => 'success',
        );

        return $arr[$ind] ?? 'danger';
    }

}

if (!function_exists('user_type_colors')) {

    function user_type_colors($ind) {
        $arr = array(
            'booker' => 'blue',
            'admin' => 'dark',
            'agent' => 'pink',
            'manager' => 'primary',
        );

        return $arr[$ind] ?? 'danger';
    }

}


if (!function_exists('remainingDays')) {

    function remainingDays($date) {
        $now = time();
// or your date as well strtotime("2020-02-11")
     //  
        $your_date =  strtotime($date);
   
        $datediff = $now - $your_date;

        return round($datediff / (60 * 60 * 24));
    }
}