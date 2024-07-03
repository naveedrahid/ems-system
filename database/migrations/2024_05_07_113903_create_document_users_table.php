<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('nic_front');
            $table->string('nic_back');
            $table->string('resume')->nullable();
            $table->string('payslip')->nullable();
            $table->string('experience_letter')->nullable();
            $table->string('bill')->nullable();
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
        Schema::dropIfExists('document_users');
    }
}
