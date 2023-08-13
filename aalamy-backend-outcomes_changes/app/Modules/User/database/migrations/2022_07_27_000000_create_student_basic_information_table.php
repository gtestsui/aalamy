<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentBasicInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_basic_information', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');

            $table->string('father_fname');
            $table->string('mother_fname');
            $table->string('mother_lname')->nullable();
            $table->string('grandfather_name')->nullable();
            $table->string('place_of_birth');
            $table->string('place_of_birth_image')->nullable();
            $table->string('place_of_registration');//مكان القيد
            $table->string('number_of_registration');//رقم القيد
            $table->enum('religion',['muslim','christian']);
            $table->string('passport_or_residence_card_number')->nullable();


            $table->string('address');
            $table->string('residence_type')->nullable();
            $table->enum('residence_ownership',['own','rent'])->nullable();
            $table->integer('distance_between_residence_and_school')->nullable();
            $table->enum('process_of_going_to_school',['walking'])->nullable();
            $table->string('telephone')->nullable();
            $table->string('mobile')->nullable();

            $table->enum('curriculum_type',['category_a','category_b'])->nullable();
            $table->boolean('sons_of_martyrs')->nullable();
            $table->string('coming_from_school_name')->nullable();//المدرسة القادم منها
            $table->enum('student_situation',['stat1'])->nullable();//وضع التلميذ
            $table->boolean('alhasakah_foreigners')->nullable();
            $table->boolean('inclusion_of_people_with_disabilities')->nullable();
            $table->boolean('muffled')->nullable();
            $table->boolean('outstanding_test')->nullable();
            $table->string('notes')->nullable();
            $table->boolean('first_year')->nullable();//مستجد او معيد


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
        Schema::dropIfExists('student_basic_information');
    }
}
