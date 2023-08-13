<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('address_id')->nullable();
            $table->foreign('address_id')->references('id')->on('addresses');

            $table->string('fname');
            $table->string('lname');
            $table->string('email')->unique()->nullable();
            $table->string('password')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('verified_code')->nullable();
            $table->dateTime('verified_code_created_at')->nullable();
            $table->boolean('verified_status')->default(0);
            $table->string('image')->nullable();
            $table->date('date_of_birth');
            $table->enum('gender',config('User.panel.genders'));
            $table->enum('account_type',['superAdmin','educator','parent','student','school']);
            $table->string('phone_code')->nullable();
            $table->string('phone_iso_code')->nullable();
            $table->string('phone_number')->nullable()->unique();
            $table->enum('account_id',['email','phone','google'])->default('email');
            $table->text('login_service_id')->nullable();
            $table->string('unique_username')->unique();
            $table->rememberToken();

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
        Schema::dropIfExists('users');
    }
}
