<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
class CreateDiscussionCornerPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discussion_corner_posts', function (Blueprint $table) {
            $table->id();

            //who add the post
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');


            $table->unsignedBigInteger('school_id')->nullable();
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');

            $table->unsignedBigInteger('educator_id')->nullable();
            $table->foreign('educator_id')->references('id')->on('educators')->onDelete('cascade');


            $table->mediumText('text');
//            $table->enum('priority',config('DiscussionCorner.panel.post_priority_values'));
            $table->tinyInteger('priority');
            $table->boolean('is_approved')->default(0);
            $table->enum('user_type',config('DiscussionCorner.panel.accounts_can_create_post'));

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
        Schema::dropIfExists('discussion_corner_posts');
    }
}
