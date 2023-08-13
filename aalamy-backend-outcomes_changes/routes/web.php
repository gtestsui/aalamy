<?php

use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//

Route::get('/test', function () {
    return 'ssdasd';
});

Route::get('get-by-access-token',function (){
//    dd('s');
   dd(Socialite::driver('google')
       ->userFromToken('ya29.A0ARrdaM9B75nVGaXDy7ygIHJMSg6nNNBRWMXAfOf9_1-9EOWgOTuP52_9t-_eowiICf6sxtQeBw_hZ7d7MGmRYzatmU80iQ4gegOYMM5tOSr0YTd9MarvXvktjAoxtF8MKoHpXpTQVqzjhNvFc2Oa48BouxSs'));

//   return Socialite::driver('google')->redirect();
});


    Route::get('pay-test','PaymentController@payWithpaypal');
    Route::get('success',function (){
        dd('success');
    });
    Route::get('failed',function (){
        dd('failed');
    });

Route::get('login-by-google',function (){

   return Socialite::driver('google')->redirect();

});


Route::get('auth/google/callback',function (){
    dd(Socialite::driver('google')->user());
});

