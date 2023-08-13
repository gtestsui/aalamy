<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
class CreateSubscriptionPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->boolean('is_paid');
            $table->float('cost');
            $table->enum('billing_cycle',config('SubscriptionPlan.panel.billing_cycles'));
            $table->integer('billing_cycle_days')->nullable();
            $table->enum('type',['school','educator']);
            $table->boolean('is_active')->default(1);
            $table->timestamps();
        });

        \DB::table('subscription_plans')->insert([
            [
                'name' => 'free plan',
                'description' => 'description',
                'is_paid' => false,
                'cost' => 0.0,
                'billing_cycle' => config('SubscriptionPlan.panel.billing_cycles.free'),
                'billing_cycle_days' => config('SubscriptionPlan.panel.billing_cycles_in_days.free'),
                'type' => 'school',
            ],
            [
                'name' => 'free plan',
                'description' => 'description',
                'is_paid' => false,
                'cost' => 0.0,
                'billing_cycle' => config('SubscriptionPlan.panel.billing_cycles.free'),
                'billing_cycle_days' => config('SubscriptionPlan.panel.billing_cycles_in_days.free'),
                'type' => 'educator',
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscription_plans');
    }
}
