<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();

            // if the page is null that mean its blank(empty)page
            $table->unsignedBigInteger('assignment_id')->nullable();
            $table->foreign('assignment_id')->references('id')->on('assignments')->onDelete('cascade');

            //if the default page will not be an empty blank so will use this
//            $table->unsignedBigInteger('default_empty_page_id')->nullable();
//            $table->foreign('default_empty_page_id')->references('id')->on('default_empty_pages')->onDelete('cascade');


            $table->string('page')->nullable();
            $table->boolean('is_empty')->default(false);
            $table->boolean('is_hidden')->default(false);
            $table->boolean('is_locked')->default(false);
            //I don't know what this is
            $table->time('timer')->nullable();
            $table->mediumInteger('order')->nullable();


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
        Schema::dropIfExists('pages');
    }
}
