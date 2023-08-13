<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

class PaymentController extends Controller
{


    public $_api_context;
    public function __construct()
    {

        /** PayPal api context **/
//        $paypal_conf = Config::get('paypal');
//        $paypal_conf = \config('SubscriptionPlan.panel.paypal');
        $this->_api_context = new ApiContext(new OAuthTokenCredential(
                config('SubscriptionPlan.panel.paypal.client_id'),
                config('SubscriptionPlan.panel.paypal.secret'))
        );

        $this->_api_context->setConfig(config('SubscriptionPlan.panel.paypal.settings'));

    }


    public function payWithpaypal(Request $request)
    {

        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $item_1 = new Item();

        $item_1->setName('Item 1') /** item name **/
        ->setCurrency('USD')
            ->setQuantity(1)
            ->setPrice(10); /** unit price **/

        $item_list = new ItemList();
        $item_list->setItems(array($item_1));

        $amount = new Amount();
        $amount->setCurrency('USD')
            ->setTotal(10);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($item_list)
            ->setDescription('Your transaction description');
        $userToken = '?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiNWIwMGRhYWU0MjNmOGI5NzliOWQyZmE1NTg4Yjk2MmUwZTBjOWQzYzc5NWNjOWQzMDc4YThiMjc2OTUxZmY2MzcyODU4MTgzYTY3OTc0NDEiLCJpYXQiOjE2NDM3MDYxMTMsIm5iZiI6MTY0MzcwNjExMywiZXhwIjoxNjc1MjQyMTEzLCJzdWIiOiIzIiwic2NvcGVzIjpbXX0.M-y5zGmvQhanoK8Uqbthg7qJ2VrjpxgT_boeZw5-Lg0G_1QmuMuT4DzKrKwT5hCQFjD4c87kMrohDcOw-7uffJYF0i7YCIEikDQ0lw0mux7nwX3syMW6g0cpBhSrLQFfSHxm3eyO7WFxUBZZjaLcGZgglWDzrwQH50tOkXo65_kktDlwOP5ouAMmC9sWhjAWtEZML7pAInlOjGjK2_zfyHtbx2l7pT3MDzEQBY8xnpBDyIziOvWQuo77KZWY1YkDyvCYXwneTtrgaPDBuiJrDe_oMxtrL-Ajy3KFKpFVSrUeUaGZr8pxMr8iuBfPV4kIRtQTDoIiTBboRhkvKKV6fkpVAwZlJki_4lbpbZkAPIxgUNJMmEFPXgDqoVkQvgf8EzWCnOf12K21FYBurYy_rwOzbgRHMSqsKuBiSLvhOoZ0L9V5DBGpLABDlSujIxfMhGef-Rm1YplUS5EevTszTGEfkAUjEFVqSLc9lMlHgPtw0z2Ly9IyQqh-kYpb0CgNNL0SjxGeJm6Jb15D6lNoa5hRM-ZggXp_ew5mIiDe_yL8zGNKkriUkiLgV4rjmHCx3RwtXqKChRrjBBHhKV7k1bpPPoq0YPppQWwjuZytVQ7AW4EZsVmHSmmOd-_rQ2WBdS1jp75QgStsuVEa1qf0MUVDYfT4MuXPwJiQFKwNTq4';
        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl(route('payment.status','success').$userToken) /** Specify return URL **/
        ->setCancelUrl(route('payment.status','success').$userToken);

        $payment = new Payment();
        $payment->setIntent('Sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirect_urls)
            ->setTransactions(array($transaction));
//        dd($payment);
//        $payment->create($this->_api_context);
        /** dd($payment->create($this->_api_context));exit; **/
        try {

            $payment->create($this->_api_context);

        } catch (\Exception $ex) {
            dd($ex->getMessage());
        }

        foreach ($payment->getLinks() as $link) {

            if ($link->getRel() == 'approval_url') {

                $redirect_url = $link->getHref();
                break;

            }

        }

        /** add payment ID to session **/

        if (isset($redirect_url)) {

            /** redirect to paypal **/
            return $redirect_url;
            return Redirect::away($redirect_url);

        }

        return Redirect::route('paywithpaypal');

    }

    public function getPaymentStatus(Request $request,$status)
    {

        dd($status);
        if (empty($request->get('PayerID')) || empty($request->get('token'))) {
            return Redirect::away('http://127.0.0.1:8000/Failed');
            return ApiResponseClass::errorMsgResponse('Payment failed');
        }

        $payment = Payment::get($request->paymentId, $this->_api_context);
        $execution = new PaymentExecution();
        $execution->setPayerId($request->get('PayerID'));

        /**Execute the payment **/
        $result = $payment->execute($execution, $this->_api_context);

        if ($result->getState() == 'approved') {
            $subscription = new Subscription();
            $subscription->user_id= $user_id;
            $subscription->payment_id= $result->id;
            $subscription->payer_id= $result->payer->payer_info->payer_id;
            $subscription->payer_email= $result->payer->payer_info->email;
            $subscription->payer_name= $result->payer->payer_info->first_name.' '.$result->payer->payer_info->last_name;
            $subscription->cart= $result->getCart();
            $subscription->amount= $result->transactions[0]->amount->total;
            if(ServicesClass::checkUserSubscription($user_id)== null) {
                $subscription->expiration_date = Carbon::now()->addDays(30)->toDateTime();
            }
            else{
                $subscription->expiration_date = Carbon::tomorrow()->addDays(30)->toDateTime();
            }
            $subscription->save();
            return Redirect::away('http://127.0.0.1:8000/welcome');
            return ApiResponseClass::successResponse($subscription);

        }
        return Redirect::away('http://127.0.0.1:8000/Failed');
        return ApiResponseClass::errorMsgResponse('Payment failed');


    }


}
