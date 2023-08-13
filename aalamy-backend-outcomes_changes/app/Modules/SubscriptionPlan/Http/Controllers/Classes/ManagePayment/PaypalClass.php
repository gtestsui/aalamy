<?php

namespace Modules\SubscriptionPlan\Http\Controllers\Classes\ManagePayment;


use App\Exceptions\ErrorMsgException;
use App\Exceptions\ErrorPaymentException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Modules\SubscriptionPlan\Http\Controllers\Classes\SubscriptionPlanServices;
use Modules\SubscriptionPlan\Http\Controllers\Classes\UserSubscribeClass;
use Modules\SubscriptionPlan\Http\DTO\SubscribedUserData;
use Modules\SubscriptionPlan\Models\SubscriptionPlan;
use Modules\SubscriptionPlan\Models\UserSubscription;
use Modules\SubscriptionPlan\Models\UserSubscriptionPaymentDetail;
use Modules\User\Models\User;
use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use App\Http\Controllers\Controller;
use PayPal\Rest\ApiContext;

class PaypalClass implements ManagePaymentInterface
{


    public $_api_context;
    public function __construct()
    {

        /** PayPal api context **/
        $this->_api_context = new ApiContext(new OAuthTokenCredential(
                config('SubscriptionPlan.panel.paypal.client_id'),
                config('SubscriptionPlan.panel.paypal.secret'))
        );

        $this->_api_context->setConfig(config('SubscriptionPlan.panel.paypal.settings'));

    }


    public function prepareRedirectUrl(SubscribedUserData $subscribedUserData,
                                       SubscriptionPlan $plan,
                                       UserSubscription $userSubscribe) : string

    {

        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

//        $item_1 = new Item();
//
//        $item_1->setName('subscribe') /** item name **/
//            ->setCurrency('USD')
//            ->setQuantity(1)
//            ->setPrice(10); /** unit price **/
//
//        $item_list = new ItemList();
//        $item_list->setItems(array($item_1));

        $amount = new Amount();
        $amount->setCurrency('USD')
            ->setTotal($plan->cost);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
//            ->setItemList($item_list)
            ->setDescription('Your classkit subscription payment');

        $redirect_urls = $this->prepareRedirectUrlsAfterPay($userSubscribe,$subscribedUserData);

        $payment = new Payment();
        $payment->setIntent('Sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirect_urls)
            ->setTransactions(array($transaction));
        /** dd($payment->create($this->_api_context));exit; **/
        try {

            $payment->create($this->_api_context);

        } catch (\Exception $ex) {
            throw new ErrorMsgException($ex->getMessage());
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
        }

        throw new ErrorMsgException();

    }

    public function prepareRedirectUrlsAfterPay($userSubscribe,$subscribedUserData){
        $encryptedUserSubId = SubscriptionPlanServices::encrypt($userSubscribe->id);
        $encryptedUserToken = SubscriptionPlanServices::encrypt($subscribedUserData->access_token);

        $queryParams = '?access_token='.$encryptedUserToken.
            '&user_sub_id='.$encryptedUserSubId;

        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl(
            route('payment.status','success').$queryParams
        ) /** Specify return URL **/
        ->setCancelUrl(
            route('payment.status','failed').$queryParams
        );
        return $redirect_urls;
    }


    public function getPaymentStatus(Request $request,$status)
    {

        $this->checkExistReturnedDataFromPayment($request);
        SubscriptionPlanServices::decryptReturnedDataFromPayment($request);
        $user = auth()->guard('api')->user();
        $userSubscribe = UserSubscription::find($request->user_subscribe_id);
        $this->checkValidReturnedDataFromPayment($user,$userSubscribe);


        $payment = Payment::get($request->paymentId, $this->_api_context);
        $execution = new PaymentExecution();
        $execution->setPayerId($request->get('PayerID'));

        /** Execute the payment **/
        $result = $payment->execute($execution, $this->_api_context);
        if ($result->getState() == 'approved') {
            $this->success($result,$user,$userSubscribe);
            return true;
//            return view('SubscriptionPlan::success-payment-status');
        }
        return false;
//        return view('SubscriptionPlan::failed-payment-status');

    }

    public function checkValidReturnedDataFromPayment($user,$userSubscribe){
        if(is_null($user) || is_null($userSubscribe) || $user->id != $userSubscribe->user_id)
            throw new ErrorPaymentException();

    }

    public function checkExistReturnedDataFromPayment($request){
        if (empty($request->get('PayerID'))
            || empty($request->get('token'))
            ||empty($request->user_sub_id)
        ) {
            throw new ErrorPaymentException();
        }
    }


    public function success($result,$user,$userSubscribe){
        $userSubscribeClass = new UserSubscribeClass($user);
        if($userSubscribe->isConfirmed())
            throw new ErrorPaymentException();

        $userSubscribe = $userSubscribeClass->confirmSubscribe($userSubscribe);

        $UserSubscriptionPaymentDetail = UserSubscriptionPaymentDetail::create([
            'user_subscription_id' => $userSubscribe->id,
            'payment_id' => $result->id,
            'payer_id' => $result->payer->payer_info->payer_id,
            'payer_email' => $result->payer->payer_info->email,
            'payer_name' => $result->payer->payer_info->first_name.' '.$result->payer->payer_info->last_name,
            'cart' => $result->getCart(),
            'amount' => $result->transactions[0]->amount->total,
        ]);

    }


}
