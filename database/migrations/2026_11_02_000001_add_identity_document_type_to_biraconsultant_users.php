<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('biraconsultant_users', 'identity_document_type')) {
            Schema::table('biraconsultant_users', function (Blueprint $table) {
                $table->string('identity_document_type', 32)->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('biraconsultant_users', 'identity_document_type')) {
            Schema::table('biraconsultant_users', function (Blueprint $table) {
                $table->dropColumn('identity_document_type');
            });
        }
    }
};
