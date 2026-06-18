<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('procedures', function (Blueprint $table) {
            $table->json('grades_data')->nullable()->after('certificate_file_path');
        });
    }

    public function down()
    {
        Schema::table('procedures', function (Blueprint $table) {
            $table->dropColumn('grades_data');
        });
    }
};