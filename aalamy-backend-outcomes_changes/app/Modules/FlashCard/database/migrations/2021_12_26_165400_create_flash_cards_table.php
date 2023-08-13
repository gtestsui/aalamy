<?php

use App\Http\Controllers\Classes\ApplicationModules;
use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
class CreateFlashCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flash_cards', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('assignment_id');
            $table->foreign('assignment_id')->references('id')->on('assignments')->onDelete('cascade');


            $table->smallInteger('display_time_in_seconds')->default(30);
            $table->tinyInteger('success_percentage');
            $table->smallInteger('quiz_time');
            $table->enum('quiz_time_type',configFromModule('panel.quiz_time_types',ApplicationModules::FLASH_CARD_MODULE_NAME))->default(configFromModule('panel.default_quiz_time_types',ApplicationModules::FLASH_CARD_MODULE_NAME));

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
        Schema::dropIfExists('flash_cards');
    }
}
