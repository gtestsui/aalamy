<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Classes\ApplicationModules;
use Illuminate\Support\Facades\Schema;
class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();

            $table->string('logo');

            $table->timestamps();
        });


        DB::table('settings')->insert([
           'logo' => configFromModule('panel.logo_inner_path',ApplicationModules::SETTING_MODULE_NAME),
        ]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
}
