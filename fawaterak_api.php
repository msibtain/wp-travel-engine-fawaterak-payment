<?php
class FawaterakAPI
{

    private $api_key;
    private $api_url;

    function __construct($api_key, $mode = 'test')
    {
        $this->api_key = $api_key;

        if ($mode === "test")
        {
            $this->api_url = "https://staging.fawaterk.com/";
        }

        if ($mode === "live")
        {
            $this->api_url = "https://app.fawaterk.com/";
        }
    }

    public function generateInvoiceLink(
        $amount, 
        $currency = 'USD',
        $customer,
        $items,
        $success_url,
        $fail_url,
        $pending_url
        )
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->api_url . 'api/v2/createInvoiceLink',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>'{
            "cartTotal": "'.$amount.'",
            "currency": "'.$currency.'",
            "customer": {
                "first_name": "'.$customer['first_name'].'",
                "last_name": "'.$customer['last_name'].'",
                "email": "'.$customer['email'].'",
                "phone": "'.$customer['phone'].'",
                "address": "'.$customer['address'].'"
            },
            "redirectionUrls": {
                 "successUrl" : "'.$success_url.'",
                 "failUrl": "'.$fail_url.'",
                 "pendingUrl": "'.$pending_url.'"   
            },
            "cartItems": [
                {
                    "name": "'.$items['name'].'",
                    "price": "'.$amount.'",
                    "quantity": "'.$items['quantity'].'"
                }
            ]
        }',
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->api_key
          ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);

        $objResponse = json_decode($response);
        
        if ($objResponse->status === "success")
        {
            return $objResponse->data->url;
        }
        else
        {
            p_r($objResponse);
            exit;
        }

        return false;
        
    }
}