<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\SubscriptionPlan\Http\Controllers\Classes\ModuleMigrationServices;
use App\Http\Controllers\Classes\ApplicationModules;

class CreateModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->integer('identify');
            $table->string('name');
            $table->text('description');
            $table->enum('usage_type',configFromModule('panel.modules_usage_types',ApplicationModules::SUBSCRIPTION_PLAN_MODULE_NAME));
//            $table->integer('number')->nullable();
            $table->boolean('is_active')->default(1);
            $table->enum('type',configFromModule('panel.module_types',ApplicationModules::SUBSCRIPTION_PLAN_MODULE_NAME));
            $table->timestamps();
        });

       ModuleMigrationServices::createRequiredData();

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('modules');
    }
}
