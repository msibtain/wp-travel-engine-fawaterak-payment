<?php
/*
Plugin Name: Fawaterak Payment gateway
Plugin URI: https://innovisionlab.com
Description: Fawaterak Payment gateway for WP Travel Engine 
Author: innovisionlab
Version: 1.0.0
Author URI: https://innovisionlab.com
*/

include( __DIR__ . '/fawaterak_api.php' );

class FawaterakGateway
{
    function __construct() 
    {
        add_filter('wp_travel_engine_available_payment_gateways', [$this, 'i_gateway_add']);
        add_filter('wpte_settings_get_global_tabs', [$this, 'i_gateway_settings']);
        add_filter('the_content', [$this, 'i_change_content']);

        add_action('wte_payment_gateway_fawaterak_gateway', [$this, 'i_gateway_redirect'], 10, 3);
        
    }

    function i_gateway_add($gateways_list)
    {
        $gateways_list['fawaterak_gateway'] = [
            'label'        => __( 'Credit or Debit Card', 'wp-travel-engine' ),
			'input_class'  => '',
			'public_label' => '',
			'icon_url'     => '',
			'info_text'    => __( 'Credit or Debit Card.', 'wp-travel-engine' ),
        ];

        return $gateways_list;
    }

    function i_gateway_settings($global_tabs)
    {
        $global_tabs['wpte-payment']['sub_tabs']['fawaterak_gateway'] = [
            'label' => 'Credit or Debit Card',
            'content_path' => plugin_dir_path( __FILE__ ) . 'settings/fawaterak_settings.php',
        ];
        
        return $global_tabs;
    }

    function i_gateway_redirect($payment_id, $payment_mode, $payment_method)
    {
        # Generate Invoice link and redirect user to that link;
        $wp_travel_engine_settings = get_option( 'wp_travel_engine_settings' );
        $api_key        = $wp_travel_engine_settings['fawaterak_gateway'];
        $gateway_mode   = $wp_travel_engine_settings['fawaterak_gateway_mode'];

        $billing_info   = get_post_meta($payment_id, "billing_info", true);
        $booking_id     = get_post_meta($payment_id, 'booking_id', true );

        $package_name = $package_price = "";
        $order_trips = get_post_meta($booking_id, "order_trips", true);
        foreach ($order_trips as $order_trip)
        {
            $package_name = $order_trip['title'];
            $package_price = $order_trip['cost'];
        }
        
        $customer = [
            'first_name' => $billing_info['fname'],
            'last_name' => $billing_info['lname'],
            'email' => $billing_info['email'],
            'phone' => $billing_info['phone_number'],
            'address' => $billing_info['address'] . ' ' . $billing_info['city'] . ' ' . $billing_info['country']
        ];
        $items = [
            'name' => $package_name,
            'quantity' => 1
        ];
        $success_url = home_url() . "/?action=fawaterak_return&mode=success&payment_id=" . $payment_id;
        $fail_url = home_url() . "/?action=fawaterak_return&mode=fail&payment_id=" . $payment_id;
        $pending_url = home_url() . "/?action=fawaterak_return&mode=pending&payment_id=" . $payment_id;

        $FawaterakAPI = new FawaterakAPI($api_key, $gateway_mode);
        $invoice_link = $FawaterakAPI->generateInvoiceLink($package_price, 'USD', $customer, $items, $success_url, $fail_url, $pending_url);

        if ($invoice_link)
        {
            wp_redirect( $invoice_link );
            exit;
        }

        echo "here";
        exit;
        
    }

    function i_change_content($content) 
    {
        if ( isset($_GET['action']) && ($_GET['action'] === "fawaterak_return") )
        {
            ob_start();

            $mode       = $_GET['mode'];
            $payment_id = $_GET['payment_id'];
            $booking_id = get_post_meta( $payment_id, 'booking_id', true );

            if ($mode === "success")
            {
                include('views/success.php');
            }

            if ($mode === "fail")
            {
                include('views/fail.php');
            }

            if ($mode === "pending")
            {
                include('views/pending.php');
            }

            $content = ob_get_clean();
        }

        return $content;
    }

    
}

new FawaterakGateway();

if (!function_exists('p_r'))
{
	function p_r($s)
	{
		echo "<pre>";
		print_r($s);
		echo "</pre>";
	}
}