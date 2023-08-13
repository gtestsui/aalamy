<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
class CreateUserCompletedStepsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_completed_steps', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');//who add the level
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');


            $table->integer('last_step_index');
            $table->string('data')->nullable();

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
        Schema::dropIfExists('user_completed_steps');
    }
}
