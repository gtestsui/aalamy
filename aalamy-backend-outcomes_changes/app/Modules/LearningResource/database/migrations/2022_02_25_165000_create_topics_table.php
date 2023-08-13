<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use App\Http\Controllers\Classes\ApplicationModules;
use Illuminate\Support\Facades\Schema;

class CreateTopicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('topics', function (Blueprint $table) {
            $table->id();

//            $table->enum('share_types',configFromModule('panel.learning_resource_read_share_types',ApplicationModules::LEARNING_RESOURCE_MODULE_NAME));
            $table->enum('read_share_type',configFromModule('panel.learning_resource_read_share_types',ApplicationModules::LEARNING_RESOURCE_MODULE_NAME));
            $table->enum('write_share_type',configFromModule('panel.learning_resource_write_share_types',ApplicationModules::LEARNING_RESOURCE_MODULE_NAME))
                ->default(configFromModule('panel.learning_resource_read_share_types.private',ApplicationModules::LEARNING_RESOURCE_MODULE_NAME));
//            $table->json('share_types');


            $table->unsignedBigInteger('topic_id')->nullable();
            $table->foreign('topic_id')->references('id')->on('topics')->onDelete('cascade');


            //the real user who add the topic
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedBigInteger('school_id')->nullable();
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');

            $table->unsignedBigInteger('teacher_id')->nullable();
            $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('cascade');

            $table->unsignedBigInteger('educator_id')->nullable();
            $table->foreign('educator_id')->references('id')->on('educators')->onDelete('cascade');


            $table->string('name');

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
        Schema::dropIfExists('topics');
    }
}
