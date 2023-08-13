<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\SubscriptionPlan\Http\Controllers\Classes\SubscriptionPlanModuleMigrationServices;

class CreateSubscriptionPlanModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscription_plan_modules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subscription_plan_id');
            $table->foreign('subscription_plan_id')->references('id')->on('subscription_plans')->onDelete('cascade');

            $table->unsignedBigInteger('module_id');
            $table->foreign('module_id')->references('id')->on('modules')->onDelete('cascade');

            $table->mediumInteger('number')->nullable();
            $table->boolean('can_use')->nullable();


            $table->timestamps();
        });

        SubscriptionPlanModuleMigrationServices::createRequiredData();


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscription_plan_modules');
    }
}
