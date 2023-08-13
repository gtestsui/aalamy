<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use App\Http\Controllers\Classes\ApplicationModules;
use Illuminate\Support\Facades\Schema;
class CreateStudentAchievementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_achievements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');

            $table->unsignedBigInteger('user_id');//who add the achievements
            $table->foreign('user_id')->references('id')->on('users');


            $table->string('title');
            $table->text('description');
            $table->string('file');
            $table->enum('file_type',configFromModule('panel.achievements_file_types',ApplicationModules::STUDENT_ACHIEVEMENT_MODULE_NAME));
//            $table->boolean('is_published')->default(false);
            $table->boolean('is_published_by_educator')->default(false);
            $table->boolean('is_published_by_school')->default(false);

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
        Schema::dropIfExists('student_achievements');
    }
}
