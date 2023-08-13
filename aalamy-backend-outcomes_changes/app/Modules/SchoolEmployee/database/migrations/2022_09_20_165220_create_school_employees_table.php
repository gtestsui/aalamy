<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use \App\Http\Controllers\Classes\ApplicationModules;
class CreateSchoolEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('school_employees', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('teacher_id')->nullable();
            $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('cascade');

            $table->unsignedBigInteger('school_id')->nullable();
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');

            $table->enum('type',configFromModule('panel.employee_types',ApplicationModules::SCHOOL_EMPLOYEE_MODULE_NAME));
            $table->string('fname');
            $table->string('lname');
            $table->string('father_name');
            $table->string('mother_name');
            $table->string('grandfather_name')->nullable();
            $table->string('place_of_birth');
            $table->date('date_of_birth');
            $table->enum('gender',['male','female']);
            $table->enum('original_state',configFromModule('panel.original_states',ApplicationModules::SCHOOL_EMPLOYEE_MODULE_NAME));
            $table->string('place_of_registration');//مكان القيد
            $table->string('number_of_registration');//رقم القيد
            $table->enum('nationality',configFromModule('panel.employee_nationalities',ApplicationModules::SCHOOL_EMPLOYEE_MODULE_NAME));
            $table->string('identifier_number');
            $table->string('phone_code')->nullable();
            $table->string('phone_iso_code')->nullable();
            $table->string('phone_number')->nullable()->unique();
            $table->string('address');
            $table->enum('marriage_state',[
                'married',//مؤهل
                'unmarried'//اعزب
            ]);
            $table->text('job_info')->nullable();
            $table->text('experience')->nullable();
            $table->text('computer_skills')->nullable();
            $table->boolean('added_manually_by_school')->default(true);




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
        Schema::dropIfExists('school_employees');
    }
}
