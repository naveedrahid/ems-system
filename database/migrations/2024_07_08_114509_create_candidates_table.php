<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCandidatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            $table->integer('job_id');
            $table->integer('is_applied')->default(0);
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('age');
            $table->string('city');
            $table->string('gender');
            $table->string('marital_status');
            $table->string('total_experience');
            $table->string('current_salary');
            $table->string('expected_salary');
            $table->string('switching_reason');
            $table->string('notice_period');
            $table->string('datetime');
            $table->string('linkdin')->nullable();
            $table->string('github')->nullable();
            $table->string('behance')->nullable();
            $table->string('Website')->nullable();
            $table->string('resume', 255)->nullab3le();
            $table->string('cover_letter', 255)->nullable();
            $table->string('application_status')->nullable();
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
        Schema::dropIfExists('candidates');
    }
}
