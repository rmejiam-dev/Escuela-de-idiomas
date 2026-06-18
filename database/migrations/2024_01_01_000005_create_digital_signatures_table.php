<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('digital_signatures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('procedure_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained();
            $table->string('signer_name');
            $table->string('signer_position');
            $table->string('signature_image_path');
            $table->string('signature_hash');
            $table->timestamp('signed_at');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('digital_signatures');
    }
};