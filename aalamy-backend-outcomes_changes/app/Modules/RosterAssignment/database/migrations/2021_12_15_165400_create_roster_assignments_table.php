<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
class CreateRosterAssignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roster_assignments', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('assignment_id')->nullable();
            $table->foreign('assignment_id')->references('id')->on('assignments')->onDelete('cascade');

            $table->unsignedBigInteger('roster_id')->nullable();
            $table->foreign('roster_id')->references('id')->on('rosters')->onDelete('cascade');


            $table->boolean('is_locked')->default(config('Assignment.panel.roster_assignment.is_locked_default'));
            $table->boolean('is_hidden')->default(config('Assignment.panel.roster_assignment.is_hidden_default'));
            $table->boolean('prevent_request_help')->default(config('Assignment.panel.roster_assignment.prevent_request_help_default'));
            $table->boolean('display_mark')->default(config('Assignment.panel.roster_assignment.display_mark_default'));
            $table->boolean('is_auto_saved')->default(config('Assignment.panel.roster_assignment.is_auto_saved_default'));
            $table->boolean('prevent_moved_between_pages')->default(config('Assignment.panel.roster_assignment.prevent_moved_between_pages_default'));
            $table->boolean('is_shuffling')->default(config('Assignment.panel.roster_assignment.is_shuffling_default'));



            $table->dateTime('start_date');
            $table->dateTime('expiration_date');

            $table->boolean('deleted')->default(0);
            $table->boolean('deleted_by_cascade')->default(0);
            $table->dateTime('deleted_at')->nullable();
            $table->timestamps();

//            $table->unique(['assignment_id', 'roster_id'],'ro_ass_id');
        });



    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roster_assignments');
    }
}
