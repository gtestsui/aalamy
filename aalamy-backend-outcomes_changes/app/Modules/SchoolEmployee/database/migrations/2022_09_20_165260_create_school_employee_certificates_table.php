<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
class CreateSchoolEmployeeCertificatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('school_employee_certificates', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('school_employee_id')->nullable();
            $table->foreign('school_employee_id')->references('id')->on('school_employees')->onDelete('cascade');


            $table->string('certificate');
            $table->enum('file_type',['picture','pdf']);


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
        Schema::dropIfExists('school_employee_certificates');
    }
}
