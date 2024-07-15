<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInterviewerRemarksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('interviewer_remarks', function (Blueprint $table) {
            $table->id();
            $table->integer('job_id');
            $table->integer('candidate_id');
            $table->integer('interviewer_id');
            $table->integer('schedule_interview_id');
            $table->string('status');
            $table->text('selected_notes')->nullable();
            $table->text('rejected_notes')->nullable();
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
        Schema::dropIfExists('interviewer_remarks');
    }
}
