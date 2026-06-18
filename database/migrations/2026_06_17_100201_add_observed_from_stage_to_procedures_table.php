<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('procedures', function (Blueprint $table) {
            $table->string('observed_from_stage')->nullable()->after('observations');
        });
    }

    public function down()
    {
        Schema::table('procedures', function (Blueprint $table) {
            $table->dropColumn('observed_from_stage');
        });
    }
};