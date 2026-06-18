<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('procedures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('certificate_type');
            $table->string('student_name');
            $table->string('student_identification');
            $table->date('birth_date');
            $table->string('program');
            $table->integer('study_period')->nullable();
            $table->decimal('final_grades_average', 5, 2)->nullable();
            $table->string('status');
            $table->text('observations')->nullable();
            $table->timestamp('received_at')->nullable();
            $table->timestamp('secretary_approved_at')->nullable();
            $table->timestamp('finance_approved_at')->nullable();
            $table->timestamp('academic_reviewed_at')->nullable();
            $table->timestamp('signed_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('generated_at')->nullable();
            $table->string('certificate_file_path')->nullable();
            $table->string('document_hash', 64)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('procedures');
    }
};