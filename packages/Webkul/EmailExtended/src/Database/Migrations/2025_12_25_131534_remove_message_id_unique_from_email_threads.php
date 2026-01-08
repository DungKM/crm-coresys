<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveMessageIdUniqueFromEmailThreads extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('email_threads', function (Blueprint $table) {
            $table->dropUnique('email_threads_message_id_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('email_threads', function (Blueprint $table) {
            $table->unique('message_id', 'email_threads_message_id_unique');
        });
    }
}