<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->enum('type',['kid','young']);
            $table->string('parent_email')->nullable();
            $table->string('parent_code')->unique();
//            $table->unsignedBigInteger('parent_id')->unique(); //we dont need this because the kid maybe have 2 parents account mom and dad
            $table->boolean('is_active')->default(1);

            $table->unsignedBigInteger('created_by_teacher')->nullable();
            $table->foreign('created_by_teacher')->references('id')->on('teachers');

            $table->unsignedBigInteger('created_by_school')->nullable();
            $table->foreign('created_by_school')->references('id')->on('schools');

            $table->unsignedBigInteger('created_by_educator')->nullable();
            $table->foreign('created_by_educator')->references('id')->on('educators');

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
        Schema::dropIfExists('students');
    }
}
