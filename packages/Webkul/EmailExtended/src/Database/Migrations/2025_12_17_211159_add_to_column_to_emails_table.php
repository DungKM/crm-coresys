<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('emails', function (Blueprint $table) {
            $table->json('to')->nullable()->after('from');
        });
    }

    public function down()
    {
        Schema::table('emails', function (Blueprint $table) {
            $table->dropColumn('to');
        });
    }
};
