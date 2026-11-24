<?php

namespace Database\Seeders;

use App\Models\BiraConsultantApplication;
use App\Models\BiraConsultantApplicationStatus;
use App\Models\BiraConsultantBlog;
use App\Models\BiraConsultantBlogCategory;
use App\Models\BiraConsultantCompany;
use App\Models\BiraConsultantCountry;
use App\Models\BiraConsultantJob;
use App\Models\BiraConsultantJobCategory;
use App\Models\BiraConsultantJobExperienceLevel;
use App\Models\BiraConsultantJobSkill;
use App\Models\BiraConsultantJobType;
use App\Models\BiraConsultantSetting;
use App\Models\BiraConsultantUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $demoPassword = env('BIRA_DEMO_PASSWORD');

        if (app()->environment('production') && empty($demoPassword)) {
            throw new \RuntimeException('BIRA_DEMO_PASSWORD must be configured before seeding demo accounts in production.');
        }

        $password = Hash::make($demoPassword ?: Str::random(48));

        $png = BiraConsultantCountry::updateOrCreate(['iso' => 'PG'], [
            'name' => 'Papua New Guinea', 'iso3' => 'PNG', 'dial' => '+675',
            'currency' => 'PGK', 'currency_name' => 'Papua New Guinean Kina',
        ]);
        $countries = [
            ['name' => 'Australia', 'iso' => 'AU', 'iso3' => 'AUS', 'dial' => '+61', 'currency' => 'AUD', 'currency_name' => 'Australian Dollar'],
            ['name' => 'Solomon Islands', 'iso' => 'SB', 'iso3' => 'SLB', 'dial' => '+677', 'currency' => 'SBD', 'currency_name' => 'Solomon Islands Dollar'],
            ['name' => 'Fiji', 'iso' => 'FJ', 'iso3' => 'FJI', 'dial' => '+679', 'currency' => 'FJD', 'currency_name' => 'Fijian Dollar'],
            ['name' => 'Vanuatu', 'iso' => 'VU', 'iso3' => 'VUT', 'dial' => '+678', 'currency' => 'VUV', 'currency_name' => 'Vanuatu Vatu'],
            ['name' => 'New Zealand', 'iso' => 'NZ', 'iso3' => 'NZL', 'dial' => '+64', 'currency' => 'NZD', 'currency_name' => 'New Zealand Dollar'],
        ];
        foreach ($countries as $country) {
            BiraConsultantCountry::updateOrCreate(['iso' => $country['iso']], $country);
        }

        $admin = BiraConsultantUser::updateOrCreate(['email' => 'admin@biraconsultant.test'], [
            'name' => 'BiraConsultant Administrator', 'slug' => 'biraconsultant-administrator',
            'username' => 'bira-admin', 'password' => $password, 'role' => 1,
            'gender' => '0', 'country_id' => $png->id, 'email_verified_at' => $now,
            'referral_code' => 'BIRAADMIN',
        ]);

        $admin->forceFill(['email_verified_at' => $now])->save();

        $categories = [];
        foreach (['Administration', 'Accounting & Finance', 'Engineering', 'Information Technology', 'Healthcare', 'Education', 'Construction', 'Sales & Marketing', 'Hospitality & Tourism', 'Transport & Logistics'] as $name) {
            $slug = Str::slug($name);
            $categories[$name] = BiraConsultantJobCategory::firstOrCreate(['slug' => $slug], ['name' => $name]);
        }
        $types = [];
        foreach (['Full-time', 'Part-time', 'Contract', 'Casual', 'Internship'] as $name) {
            $types[$name] = BiraConsultantJobType::firstOrCreate(['name' => $name]);
        }
        $levels = [];
        foreach (['Entry Level', 'Mid Level', 'Senior Level', 'Management', 'Graduate'] as $name) {
            $levels[$name] = BiraConsultantJobExperienceLevel::firstOrCreate(['name' => $name]);
        }
        $skills = [];
        foreach (['Communication', 'Microsoft Office', 'Project Management', 'Customer Service', 'Accounting', 'Networking', 'PHP', 'Laravel', 'JavaScript', 'Electrical Engineering', 'Data Analysis', 'Leadership'] as $name) {
            $skills[$name] = BiraConsultantJobSkill::firstOrCreate(['name' => $name]);
        }

        $statuses = [
            1 => ['Received', 'bg-blue-100 text-blue-800'],
            2 => ['Under Review', 'bg-yellow-100 text-yellow-800'],
            3 => ['Interview', 'bg-purple-100 text-purple-800'],
            4 => ['Job Offer', 'bg-indigo-100 text-indigo-800'],
            5 => ['Hired', 'bg-green-100 text-green-800'],
            6 => ['Rejected', 'bg-red-100 text-red-800'],
        ];
        foreach ($statuses as $id => [$label, $color]) {
            BiraConsultantApplicationStatus::updateOrCreate(['id' => $id], ['label' => $label, 'color_class' => $color]);
        }

        $jobSeekers = [];
        $people = [
            ['Martha Kila', 'martha.kila@biraconsultant.test', 'martha-kila'],
            ['Peter Wama', 'peter.wama@biraconsultant.test', 'peter-wama'],
            ['Lydia Saro', 'lydia.saro@biraconsultant.test', 'lydia-saro'],
            ['Daniel Kora', 'daniel.kora@biraconsultant.test', 'daniel-kora'],
            ['Ruth Mako', 'ruth.mako@biraconsultant.test', 'ruth-mako'],
            ['Simon Gari', 'simon.gari@biraconsultant.test', 'simon-gari'],
            ['Anna Buka', 'anna.buka@biraconsultant.test', 'anna-buka'],
            ['James Nori', 'james.nori@biraconsultant.test', 'james-nori'],
            ['Grace Toma', 'grace.toma@biraconsultant.test', 'grace-toma'],
            ['Michael Ravu', 'michael.ravu@biraconsultant.test', 'michael-ravu'],
            ['Helen Kila', 'helen.kila@biraconsultant.test', 'helen-kila'],
            ['Joseph Daro', 'joseph.daro@biraconsultant.test', 'joseph-daro'],
        ];
        foreach ($people as $i => [$name, $email, $slug]) {
            $jobSeekers[] = BiraConsultantUser::updateOrCreate(['email' => $email], [
                'name' => $name, 'slug' => $slug, 'username' => str_replace('-', '.', $slug),
                'password' => $password, 'role' => 0, 'gender' => (string) ($i % 2),
                'birthday' => now()->subYears(22 + ($i % 15))->toDateString(),
                'country_id' => $png->id, 'state' => ['Morobe', 'National Capital District', 'Eastern Highlands', 'Western Highlands'][$i % 4],
                'email_verified_at' => $now, 'referral_code' => strtoupper(Str::random(8)),
                'bio' => 'Sample BiraConsultant job seeker profile for production workflow testing.',
            ]);
            $jobSeekers[array_key_last($jobSeekers)]->forceFill(['email_verified_at' => $now])->save();
        }

        $companyData = [
            ['Kumul Petroleum Holdings', 'kumul-petroleum-holdings', 'Energy & Resources', 'Port Moresby', 'admin@kumulpetroleum.example'],
            ['Bank South Pacific', 'bank-south-pacific', 'Banking & Finance', 'Port Moresby', 'careers@bsp.example'],
            ['Lae City Pharmacy', 'lae-city-pharmacy', 'Healthcare', 'Lae', 'jobs@laecitypharmacy.example'],
            ['Highlands Engineering Services', 'highlands-engineering-services', 'Engineering', 'Mt Hagen', 'hr@highlandsengineering.example'],
        ];
        $companyLogos = [
            'kumul-petroleum-holdings' => 'https://kumulpetroleum.com/wp-content/uploads/2023/09/KPHL_Main_logo.png',
            'bank-south-pacific' => 'images/companies/bsp-logo.svg',
            'lae-city-pharmacy' => 'https://cpl.com.pg/wp-content/uploads/2026/07/City-Pharmacy-Brand-Logo-Pantones.pdf-image-002-768x202.png',
            'highlands-engineering-services' => 'images/companies/highlands-engineering-services.svg',
        ];

        $companies = [];
        foreach ($companyData as $i => [$name, $slug, $industry, $city, $email]) {
            $ownerEmail = 'company'.($i + 1).'@biraconsultant.test';
            $owner = BiraConsultantUser::updateOrCreate(['email' => $ownerEmail], [
                'name' => $name.' Recruitment', 'slug' => 'company-owner-'.($i + 1),
                'username' => 'company-owner-'.($i + 1), 'password' => $password,
                'role' => 2, 'gender' => '0', 'country_id' => $png->id,
                'email_verified_at' => $now, 'referral_code' => strtoupper(Str::random(8)),
            ]);
            $owner->forceFill(['email_verified_at' => $now])->save();
            $companies[] = BiraConsultantCompany::updateOrCreate(['slug' => $slug], [
                'name' => $name, 'industry' => $industry, 'location' => $city.', Papua New Guinea',
                'city' => $city, 'country_id' => $png->id, 'user_id' => $owner->id,
                'email' => $email, 'phone' => '+675 7000 '.str_pad((string) ($i + 1), 4, '0', STR_PAD_LEFT),
                'logo' => $companyLogos[$slug] ?? null,
                'website' => 'https://example.com', 'description' => 'Synthetic sample company profile for validating BiraConsultant company and recruitment workflows.',
                'founded_year' => 2000 + $i, 'size' => ['201-500', '501-1000', '11-50', '51-200'][$i],
                'is_approved' => true, 'verification_status' => 1, 'verified_at' => $now,
                'verified_by' => $admin->id,
            ]);
        }

        $jobSpecs = [
            ['Senior Systems Administrator', 'Information Technology', 'Full-time', 'Senior Level', 0],
            ['Accounts Officer', 'Accounting & Finance', 'Full-time', 'Mid Level', 1],
            ['Graduate Electrical Engineer', 'Engineering', 'Full-time', 'Graduate', 3],
            ['Registered Nurse', 'Healthcare', 'Full-time', 'Mid Level', 2],
            ['Project Coordinator', 'Administration', 'Contract', 'Mid Level', 0],
            ['IT Support Technician', 'Information Technology', 'Full-time', 'Entry Level', 2],
            ['Marketing Officer', 'Sales & Marketing', 'Full-time', 'Mid Level', 1],
            ['Logistics Supervisor', 'Transport & Logistics', 'Full-time', 'Management', 3],
            ['Graduate Finance Assistant', 'Accounting & Finance', 'Internship', 'Graduate', 1],
            ['Construction Site Supervisor', 'Construction', 'Contract', 'Senior Level', 3],
            ['Hotel Front Office Officer', 'Hospitality & Tourism', 'Casual', 'Entry Level', 2],
            ['Primary School Teacher', 'Education', 'Full-time', 'Mid Level', 0],
        ];
        $jobs = [];
        foreach ($jobSpecs as $i => [$title, $category, $type, $level, $companyIndex]) {
            $slug = Str::slug($title).'-'.($i + 1);
            $job = BiraConsultantJob::updateOrCreate(['slug' => $slug], [
                'title' => $title, 'description' => 'This is a synthetic sample vacancy created to test BiraConsultant job discovery, job detail, application and company dashboard workflows. The successful applicant will meet the role requirements and work collaboratively with the team.',
                'requirements' => 'Relevant qualification or equivalent experience; strong communication skills; ability to work safely and professionally.',
                'how_to_apply' => 'Submit your application through the BiraConsultant application form.',
                'company_id' => $companies[$companyIndex]->id, 'category_id' => $categories[$category]->id,
                'location' => $companies[$companyIndex]->city.', Papua New Guinea',
                'job_type_id' => $types[$type]->id, 'salary' => 'K'.(35000 + $i * 5000).' - K'.(50000 + $i * 5000).' per annum',
                'experience_level_id' => $levels[$level]->id,
                'deadline' => now()->addDays(30 + $i)->toDateString(), 'status' => true,
                'approval_status' => $i < 10 ? 'approved' : 'pending',
                'approved_at' => $i < 10 ? $now : null,
                'approved_by' => $i < 10 ? $admin->id : null, 'duration' => 30,
            ]);
            $jobs[] = $job;
            $job->skills()->syncWithoutDetaching(array_slice(array_map(fn ($skill) => $skill->id, array_values($skills)), $i % 5, 3));
        }

        foreach ($jobSeekers as $i => $user) {
            $user->skills()->syncWithoutDetaching(array_slice(array_map(fn ($skill) => $skill->id, array_values($skills)), $i % 6, 3));
            $user->interests()->syncWithoutDetaching([$categories[array_keys($categories)[$i % count($categories)]]->id]);
            $user->workExperiences()->firstOrCreate([
                'company_name' => ['Lae Business Services', 'Morobe Trading', 'PNG Skills Centre'][$i % 3],
                'position' => ['Administrative Assistant', 'Customer Service Officer', 'Junior Technician'][$i % 3],
            ], [
                'description' => 'Synthetic sample employment history for profile testing.',
                'start_date' => now()->subYears(3)->toDateString(),
                'end_date' => now()->subMonths(6)->toDateString(),
            ]);
            $user->savedJobs()->syncWithoutDetaching([$jobs[$i % count($jobs)]->id]);
            $user->followingCompanies()->syncWithoutDetaching([$companies[$i % count($companies)]->id]);
            $job = $jobs[$i % 8];
            BiraConsultantApplication::firstOrCreate(
                ['user_id' => $user->id, 'job_id' => $job->id],
                ['status_id' => ($i % 6) + 1, 'reviewed_at' => $i % 6 === 0 ? null : $now->copy()->subDays($i), 'cover_letter' => 'I am applying for this opportunity because my skills and experience align with the role. I would welcome the opportunity to discuss my application and demonstrate how I can contribute to your organisation.']
            );
        }

        $blogCategory = BiraConsultantBlogCategory::firstOrCreate(['slug' => 'career-advice'], [
            'name' => 'Career Advice', 'description' => 'Career guidance and job-search resources.',
        ]);
        BiraConsultantBlog::firstOrCreate(['slug' => 'preparing-for-your-next-interview'], [
            'title' => 'Preparing for Your Next Interview',
            'excerpt' => 'Practical steps to prepare for a successful job interview.',
            'content' => 'Research the employer, review the role requirements, prepare examples of your achievements, and arrive on time. Ask thoughtful questions and follow up professionally after the interview.',
            'author_id' => $admin->id, 'category_id' => $blogCategory->id,
            'status' => 'published', 'published_at' => $now,
        ]);

        $settings = [
            'site_name' => 'BiraConsultant',
            'maintenance_mode' => '0',
            'contact_email' => 'info@biraconsultants.com',
            'contact_phone' => '+675',
            'default_country' => 'PG',
        ];
        foreach ($settings as $key => $value) {
            BiraConsultantSetting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
