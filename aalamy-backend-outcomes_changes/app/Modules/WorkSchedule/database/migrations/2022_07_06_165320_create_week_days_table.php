<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
class CreateWeekDaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('week_days', function (Blueprint $table) {
            $table->id();

            $table->string('name');

            $table->timestamps();
        });

        DB::table('week_days')->insert([
            ['name' => 'sun' ],
            ['name' => 'mon' ],
            ['name' => 'tus' ],
            ['name' => 'wed' ],
            ['name' => 'thi' ],
            ['name' => 'fri' ],
            ['name' => 'sat' ],
        ]);


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('week_days');
    }
}
