<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Srmklive\PayPal\Services\ExpressCheckout;

class PaypalController extends Controller
{

    public function goPayment()
    {
        return view('products\welcome');
    }
    public function  payment(){

        $data = [
            'items' => [
                [
                    'name' => 'Apple',
                    'price'=> 100,
                    'description' => 'Macbook pro 14 inch',
                    'qty' => 1
                ]
            ]
        ];
        $data['invoice_id'] =  1;
        $data['invoice_description'] = "order invoice";

        $data['return_url'] = route('payment.success');
        $data['cancel_url'] = route('cancel');
        $data['total'] = 100;

        $provider = new ExpressCheckout;

        $response = $provider->setExpressCheckout($data);
        $response = $provider->setExpressCheckout($data,true);

        return redirect($response['paypal_link']);
    }

    public function cancel (){
        dd('You are cancelled this payment');
    }

    public function success(Request $request){
        $provider = new ExpressCheckout;
        $response = $provider->getExpressCheckoutDetails($request->token);
        if(in_array(strtoupper($response['ACK']), ['SUCCESS','SUCESSWITHWARNING'])){
            dd('Your payment was successful thanks!');
        }
        else
            dd('please try again later');
    }
}
