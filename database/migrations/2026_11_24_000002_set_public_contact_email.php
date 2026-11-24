<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Apply the owner-confirmed public contact address during the production rollout.
        $now = now();

        if (DB::table('biraconsultant_settings')->where('key', 'contact_email')->exists()) {
            DB::table('biraconsultant_settings')
                ->where('key', 'contact_email')
                ->update([
                    'value' => 'info@biraconsultant.com',
                    'updated_at' => $now,
                ]);
        } else {
            DB::table('biraconsultant_settings')->insert([
                'key' => 'contact_email',
                'value' => 'info@biraconsultant.com',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        // Keep the administrator-managed contact address when rolling back.
    }
};
