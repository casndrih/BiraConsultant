<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $settings = [
            'site_name' => 'Bira Consultants',
            'site_description' => 'Your Gateway to Better Career Opportunities',
            'contact_email' => 'info@biraconsultants.com',
            'contact_phone' => '+675 321 0000',
            'jobs_per_page' => '12',
            'enable_registration' => '1',
            'enable_company_registration' => '1',
            'site_logo' => '/images/logo.png',
            'default_language' => 'en',
            'timezone' => 'Pacific/Port_Moresby',
            'date_format' => 'd/m/Y',
            'default_company_logo' => '/images/company-default.png',
            'max_companies_per_user' => '3',
            'maintenance_mode' => '0',
        ];

        foreach ($settings as $key => $value) {
            DB::table('biraconsultant_settings')->updateOrInsert(
                ['key' => $key],
                ['value' => $value, 'updated_at' => $now, 'created_at' => $now]
            );
        }

        // Minimal country reference row so initial PNG registrations have a valid option.
        DB::table('biraconsultant_countries')->updateOrInsert(
            ['iso' => 'PG'],
            [
                'name' => 'Papua New Guinea',
                'iso3' => 'PNG',
                'dial' => '+675',
                'currency' => 'PGK',
                'currency_name' => 'Papua New Guinean Kina',
                'updated_at' => $now,
                'created_at' => $now,
            ]
        );

        $jobCategories = [
            'Administration', 'Accounting & Finance', 'Agriculture',
            'Construction', 'Education & Training', 'Engineering',
            'Healthcare & Medical', 'Hospitality & Tourism', 'Human Resources',
            'Information Technology', 'Logistics & Transport', 'Manufacturing',
            'Marketing & Communications', 'Mining & Resources', 'Retail & Sales',
            'Security', 'Trades & Services',
        ];

        foreach ($jobCategories as $name) {
            DB::table('biraconsultant_job_categories')->updateOrInsert(
                ['slug' => \Illuminate\Support\Str::slug($name)],
                ['name' => $name, 'updated_at' => $now, 'created_at' => $now]
            );
        }

        foreach (['Full Time', 'Part Time', 'Contract', 'Casual', 'Internship', 'Temporary'] as $name) {
            DB::table('biraconsultant_job_types')->updateOrInsert(
                ['name' => $name],
                ['updated_at' => $now, 'created_at' => $now]
            );
        }

        foreach (['No Experience', 'Entry Level', 'Mid Level', 'Senior Level', 'Executive'] as $name) {
            DB::table('biraconsultant_job_experience_levels')->updateOrInsert(
                ['name' => $name],
                ['updated_at' => $now, 'created_at' => $now]
            );
        }

        foreach (['News', 'Career Advice', 'Employer Insights'] as $name) {
            DB::table('biraconsultant_blog_categories')->updateOrInsert(
                ['slug' => \Illuminate\Support\Str::slug($name)],
                ['name' => $name, 'description' => null, 'updated_at' => $now, 'created_at' => $now]
            );
        }

        $statuses = [
            [1, 'Applied', 'bg-blue-100 text-blue-700'],
            [2, 'Under Review', 'bg-yellow-100 text-yellow-700'],
            [3, 'Shortlisted', 'bg-indigo-100 text-indigo-700'],
            [4, 'Interview', 'bg-purple-100 text-purple-700'],
            [5, 'Offered', 'bg-green-100 text-green-700'],
            [6, 'Hired', 'bg-emerald-100 text-emerald-700'],
        ];

        foreach ($statuses as [$id, $label, $color]) {
            DB::table('biraconsultant_application_status')->updateOrInsert(
                ['id' => $id],
                ['label' => $label, 'color_class' => $color, 'updated_at' => $now, 'created_at' => $now]
            );
        }
    }
}
