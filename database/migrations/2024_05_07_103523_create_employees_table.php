<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('department_id');
            $table->foreignId('designation_id');
            $table->foreignId('employee_type_id');
            $table->foreignId('shift_id');
            $table->date('date_of_birth');
            $table->date('joining_date');
            $table->string('fater_name');
            $table->string('city')->nullable();
            $table->string('address')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('emergency_phone_number')->nullable();
            $table->string('emergency_person_name')->nullable();
            $table->string('employee_img')->nullable();
            $table->enum('gender', ['male', 'female'])->default('male');
            $table->string('employee_id_card_num')->unique();
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
        Schema::dropIfExists('employees');
    }
}
