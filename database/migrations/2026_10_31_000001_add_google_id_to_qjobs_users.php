<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('qjobs_users') && !Schema::hasColumn('qjobs_users', 'google_id')) {
            Schema::table('qjobs_users', function (Blueprint $table) {
                $table->string('google_id')->nullable()->unique();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('qjobs_users') && Schema::hasColumn('qjobs_users', 'google_id')) {
            Schema::table('qjobs_users', function (Blueprint $table) {
                $table->dropUnique(['google_id']);
                $table->dropColumn('google_id');
            });
        }
    }
};
