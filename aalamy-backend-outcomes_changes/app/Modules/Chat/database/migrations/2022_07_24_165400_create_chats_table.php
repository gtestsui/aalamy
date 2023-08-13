<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateChatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chats', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('first_user_id');
            $table->foreign('first_user_id')->references('id')->on('users')->onDelete('cascade');


            $table->unsignedBigInteger('second_user_id');
            $table->foreign('second_user_id')->references('id')->on('users')->onDelete('cascade');

            $table->integer('unread_message_count_from_first')->default(0);
            $table->integer('unread_message_count_from_second')->default(0);

            $table->boolean('it_seen_from_first')->default(true);
            $table->boolean('it_seen_from_second')->default(true);

            $table->json('deleted_by')->nullable();


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
        Schema::dropIfExists('chats');
    }
}
