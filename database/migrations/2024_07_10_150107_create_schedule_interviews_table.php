<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScheduleInterviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedule_interviews', function (Blueprint $table) {
            $table->id();
            $table->integer('job_id');
            $table->integer('candidate_id');
            $table->integer('interviewer_id');
            $table->string('interview_type');
            $table->text('interviewer_notes');
            $table->text('candidate_notes');
            $table->string('interview_date');
            $table->string('interview_time');
            $table->string('interview_level');
            $table->softDeletes();
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
        Schema::dropIfExists('schedule_interviews');
    }
}
