<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
class CreateBaseLevelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('base_levels', function (Blueprint $table) {
            $table->id();


            $table->string('name');
            $table->boolean('is_default')->default(1);

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
        Schema::dropIfExists('base_levels');
    }
}
