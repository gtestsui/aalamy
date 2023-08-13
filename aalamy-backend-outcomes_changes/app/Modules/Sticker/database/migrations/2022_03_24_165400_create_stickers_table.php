<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Http\Controllers\Classes\FileSystemServicesClass;
class CreateStickersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stickers', function (Blueprint $table) {
            $table->id();

            //who create the sticker
            $table->unsignedBigInteger('school_id')->nullable();
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');

            $table->unsignedBigInteger('teacher_id')->nullable();
            $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('cascade');

            $table->unsignedBigInteger('educator_id')->nullable();
            $table->foreign('educator_id')->references('id')->on('educators')->onDelete('cascade');

            $table->string('name');
            $table->string('icon');
            $table->integer('mark');


            $table->boolean('deleted')->default(0);
            $table->boolean('deleted_by_cascade')->default(0);
            $table->dateTime('deleted_at')->nullable();

            $table->timestamps();
        });


        DB::table('stickers')->insert([
            'name' => 'default sticker 1',
            'icon' => FileSystemServicesClass::getDefaultStoragePathInsideDisk().
                '/default-stickers'.
                '/default-sticker-1.jpg',
            'mark' => 2,
        ]);

        DB::table('stickers')->insert([
            'name' => 'default sticker 2',
            'icon' => FileSystemServicesClass::getDefaultStoragePathInsideDisk().
                '/default-stickers'.
                '/default-sticker-2.jpg',
            'mark' => 2,
        ]);


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stickers');
    }
}
