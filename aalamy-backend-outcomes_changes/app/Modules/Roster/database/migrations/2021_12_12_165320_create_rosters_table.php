<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
class CreateRostersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rosters', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('class_info_id');
            $table->foreign('class_info_id')->references('id')->on('class_infos')->onDelete('cascade');

            $table->unsignedBigInteger('created_by_school_id')->nullable();
            $table->foreign('created_by_school_id')->references('id')->on('schools')->onDelete('cascade');

            $table->unsignedBigInteger('created_by_teacher_id')->nullable();
            $table->foreign('created_by_teacher_id')->references('id')->on('teachers')->onDelete('cascade');

            $table->unsignedBigInteger('created_by_educator_id')->nullable();
            $table->foreign('created_by_educator_id')->references('id')->on('educators')->onDelete('cascade');


            $table->string('name');
            $table->string('color');
            $table->text('description');
            $table->string('code')->unique();
            $table->boolean('is_closed')->default(0);

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
        Schema::dropIfExists('rosters');
    }
}
