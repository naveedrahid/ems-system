<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('created_by');
            $table->string('title');
            $table->integer('department_id');
            $table->integer('designation_id');
            $table->integer('shift_id');
            $table->string('employment_type');
            $table->text('description');
            $table->string('location');
            $table->string('salary_range');
            $table->string('closing_date');
            $table->string('job_img')->nullable();
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
        Schema::dropIfExists('jobs');
    }
}
