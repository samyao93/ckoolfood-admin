<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\Models\Order;

class BkashPaymentController extends Controller
{
    private $base_url;
    private $app_key;
    private $app_secret;
    private $username;
    private $password;

    public function __construct()
    {
        $config=\App\CentralLogics\Helpers::get_business_settings('bkash');
        // You can import it from your Database
        $bkash_app_key = $config['api_key']; // bKash Merchant API APP KEY
        $bkash_app_secret = $config['api_secret']; // bKash Merchant API APP SECRET
        $bkash_username = $config['username']; // bKash Merchant API USERNAME
        $bkash_password = $config['password']; // bKash Merchant API PASSWORD
        $bkash_base_url = (env('APP_MODE') == 'live') ? 'https://checkout.pay.bka.sh/v1.2.0-beta' : 'https://checkout.sandbox.bka.sh/v1.2.0-beta';

        $this->app_key = $bkash_app_key;
        $this->app_secret = $bkash_app_secret;
        $this->username = $bkash_username;
        $this->password = $bkash_password;
        $this->base_url = $bkash_base_url;
    }

    public function getToken()
    {
        session()->forget('bkash_token');

        $request_data = array(
            'app_key' => $this->app_key,
            'app_secret' => $this->app_secret
        );
        $url = curl_init('https://tokenized.sandbox.bka.sh/v1.2.0-beta/tokenized/checkout/token/grant');
        $request_data_json = json_encode($request_data);
        $header = array(
            'Content-Type:application/json',
            'username:'.$this->username,
            'password:'.$this->password
        );
        curl_setopt($url, CURLOPT_HTTPHEADER, $header);
        curl_setopt($url, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($url, CURLOPT_POSTFIELDS, $request_data_json);
        curl_setopt($url, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($url, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);

        $resultdata = curl_exec($url);
        curl_close($url);

        $response = json_decode($resultdata, true);

        if (array_key_exists('msg', $response)) {
            return $response;
        }

        session()->put('bkash_token', $response['id_token']);

        return $response;
    }

    // public function createPayment(Request $request)
    // {

    //     $token = session()->get('bkash_token');

    //     $request['intent'] = 'sale';
    //     $request['currency'] = 'BDT';
    //     $request['merchantInvoiceNumber'] = rand();

    //     $url = curl_init("$this->base_url/checkout/payment/create");
    //     $request_data_json = json_encode($request->all());
    //     $header = array(
    //         'Content-Type:application/json',
    //         "authorization: $token",
    //         "x-app-key: $this->app_key"
    //     );

    //     curl_setopt($url, CURLOPT_HTTPHEADER, $header);
    //     curl_setopt($url, CURLOPT_CUSTOMREQUEST, "POST");
    //     curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
    //     curl_setopt($url, CURLOPT_POSTFIELDS, $request_data_json);
    //     curl_setopt($url, CURLOPT_FOLLOWLOCATION, 1);
    //     curl_setopt($url, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
    //     $resultdata = curl_exec($url);
    //     curl_close($url);
    //     return json_decode($resultdata, true);
    // }

    // public function executePayment(Request $request)
    // {
    //     $token = session()->get('bkash_token');

    //     $paymentID = $request->paymentID;
    //     $url = curl_init("$this->base_url/checkout/payment/execute/" . $paymentID);
    //     $header = array(
    //         'Content-Type:application/json',
    //         "authorization:$token",
    //         "x-app-key:$this->app_key"
    //     );

    //     curl_setopt($url, CURLOPT_HTTPHEADER, $header);
    //     curl_setopt($url, CURLOPT_CUSTOMREQUEST, "POST");
    //     curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
    //     curl_setopt($url, CURLOPT_FOLLOWLOCATION, 1);
    //     $resultdata = curl_exec($url);
    //     curl_close($url);
    //     return json_decode($resultdata, true);
    // }

    // public function queryPayment(Request $request)
    // {
    //     $token = session()->get('bkash_token');
    //     $paymentID = $request->payment_info['payment_id'];

    //     $url = curl_init("$this->base_url/checkout/payment/query/" . $paymentID);
    //     $header = array(
    //         'Content-Type:application/json',
    //         "authorization:$token",
    //         "x-app-key:$this->app_key"
    //     );

    //     curl_setopt($url, CURLOPT_HTTPHEADER, $header);
    //     curl_setopt($url, CURLOPT_CUSTOMREQUEST, "GET");
    //     curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
    //     curl_setopt($url, CURLOPT_FOLLOWLOCATION, 1);
    //     $resultdata = curl_exec($url);
    //     curl_close($url);
    //     return json_decode($resultdata, true);
    // }

    public function make_tokenize_payment(Request $request)
    {
        $order = Order::with(['details','customer'])->where(['id' => $request->order_id])->first();
        $response = self::getToken();
        $auth = $response['id_token'];
        $callbackURL = route('bkash-success');

        $requestbody = array(
            'mode' => '0011',
            'amount' => (string)$order->order_amount,
            'currency' => 'BDT',
            'intent' => 'sale',
            'payerReference' => (string)$order->customer->phone,
            'merchantInvoiceNumber' => 'commonPayment001',
            'callbackURL' => $callbackURL
        );
        $url = curl_init('https://tokenized.sandbox.bka.sh/v1.2.0-beta/tokenized/checkout/create');
        $requestbodyJson = json_encode($requestbody);

        $header = array(
            'Content-Type:application/json',
            'Authorization:' . $auth,
            'X-APP-Key:' . $this->app_key
        );

        curl_setopt($url, CURLOPT_HTTPHEADER, $header);
        curl_setopt($url, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($url, CURLOPT_POSTFIELDS, $requestbodyJson);
        curl_setopt($url, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($url, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        $resultdata = curl_exec($url);
        curl_close($url);
        echo $resultdata;

        $obj = json_decode($resultdata);
        return redirect()->away($obj->{'bkashURL'});
    }

    public function bkashSuccess(Request $request)
    {
        $order = Order::with(['details'])->where(['id' => session('order_id'), 'user_id'=>session('customer_id')])->first();
        if ($request->paymentID != null && $request->status == 'success') {
            $order->transaction_reference = $request->paymentID;
            $order->payment_method = 'bkash';
            $order->payment_status = 'paid';
            $order->order_status = 'confirmed';
            $order->confirmed = now();
            $order->save();
            Helpers::send_order_notification($order);
            if ($order->callback != null) {
                return redirect($order->callback . '&status=success');
            }else{
                return \redirect()->route('payment-success');
            }
        }

        $order->order_status = 'failed';
        $order->failed = now();
        $order->save();
        if ($order->callback != null) {
            return redirect($order->callback . '&status=fail');
        }else{
            return \redirect()->route('payment-fail');
        }
    }
}

