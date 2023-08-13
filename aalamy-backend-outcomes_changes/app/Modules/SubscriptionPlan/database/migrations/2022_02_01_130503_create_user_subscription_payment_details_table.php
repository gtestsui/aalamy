<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserSubscriptionPaymentDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_subscription_payment_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_subscription_id');
            $table->foreign('user_subscription_id')->references('id')->on('user_subscriptions')->onDelete('cascade');

            $table->string('payment_id');
            $table->string('payer_id');
            $table->string('payer_email');
            $table->string('payer_name');
            $table->string('cart');
            $table->string('amount');

            $table->boolean('deleted')->default(0);
            $table->boolean('deleted_by_cascade')->default(0);
            $table->dateTime('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_subscription_payment_details');
    }
}
