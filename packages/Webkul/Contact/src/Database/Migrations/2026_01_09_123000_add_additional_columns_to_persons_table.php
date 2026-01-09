<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('persons', function (Blueprint $table) {
            $table->string('gender')->nullable()->after('job_title');
            $table->text('address')->nullable()->after('gender');
            $table->text('hobbies')->nullable()->after('address');
            $table->date('date_of_birth')->nullable()->after('hobbies');
            $table->string('occupation')->nullable()->after('date_of_birth');
            $table->decimal('income', 15, 2)->nullable()->after('occupation');
            $table->text('habits_and_behaviors')->nullable()->after('income');
            $table->text('needs_and_pain_points')->nullable()->after('habits_and_behaviors');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('persons', function (Blueprint $table) {
            $table->dropColumn([
                'gender',
                'address',
                'hobbies',
                'date_of_birth',
                'occupation',
                'income',
                'habits_and_behaviors',
                'needs_and_pain_points',
            ]);
        });
    }
};
