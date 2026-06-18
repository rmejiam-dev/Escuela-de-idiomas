<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pre_enrollments', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('email');
            $table->string('phone');
            $table->string('identification_number');
            $table->string('program_interest');
            $table->text('message')->nullable();
            $table->string('status')->default('pending');
            $table->ipAddress('request_ip');
            $table->text('captcha_response')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pre_enrollments');
    }
};