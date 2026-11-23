<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('biraconsultant_countries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('iso', 2)->nullable()->unique();
            $table->string('iso3', 3)->nullable();
            $table->string('dial', 10)->nullable();
            $table->string('currency', 3)->nullable();
            $table->string('currency_name')->nullable();
            $table->timestamps();
        });

        Schema::create('biraconsultant_users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->nullable()->unique();
            $table->string('username', 50)->nullable()->unique();
            $table->unsignedTinyInteger('gender')->default(0);
            $table->string('email')->unique();
            $table->string('google_id')->nullable()->unique();
            $table->string('phone', 30)->nullable();
            $table->string('password');
            $table->unsignedTinyInteger('role')->default(0)->index();
            $table->string('image')->nullable();
            $table->string('cover')->nullable();
            $table->text('bio')->nullable();
            $table->string('website')->nullable();
            $table->string('high_school')->nullable();
            $table->string('college_university')->nullable();
            $table->string('doc')->nullable();
            $table->date('birthday')->nullable();
            $table->foreignId('country_id')->nullable()->constrained('biraconsultant_countries')->nullOnDelete();
            $table->string('state')->nullable();
            $table->string('referral_code', 32)->nullable()->unique();
            $table->string('email_verification_token')->nullable()->index();
            $table->string('nid_number')->nullable();
            $table->string('identity_document_type')->nullable();
            $table->string('nid_front_path')->nullable();
            $table->string('nid_back_path')->nullable();
            $table->unsignedTinyInteger('identity_verified')->default(0);
            $table->timestamp('identity_verified_at')->nullable();
            $table->text('identity_rejection_reason')->nullable();
            $table->string('phone_verification_code')->nullable();
            $table->boolean('phone_verified')->default(false);
            $table->timestamp('phone_verified_at')->nullable();
            $table->boolean('notify_email')->default(true);
            $table->boolean('notify_followed_company_jobs')->default(true);
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->index(['role', 'created_at']);
        });

        Schema::create('biraconsultant_job_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->timestamps();
        });

        Schema::create('biraconsultant_job_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('biraconsultant_job_experience_levels', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('biraconsultant_job_skills', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('biraconsultant_application_status', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->string('label');
            $table->string('color_class')->default('bg-gray-100 text-gray-700');
            $table->timestamps();
        });

        Schema::create('biraconsultant_companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->nullable()->unique();
            $table->string('website')->nullable();
            $table->string('logo')->nullable();
            $table->string('industry')->nullable();
            $table->string('location')->nullable();
            $table->string('city')->nullable();
            $table->foreignId('country_id')->nullable()->constrained('biraconsultant_countries')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('biraconsultant_users')->nullOnDelete();
            $table->string('address')->nullable();
            $table->string('email')->nullable();
            $table->string('phone', 30)->nullable();
            $table->text('description')->nullable();
            $table->unsignedSmallInteger('founded_year')->nullable();
            $table->string('size', 50)->nullable();
            $table->boolean('is_approved')->default(false)->index();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('registration_number')->nullable();
            $table->string('registration_doc_path')->nullable();
            $table->string('tax_id_number')->nullable();
            $table->string('tax_doc_path')->nullable();
            $table->unsignedTinyInteger('verification_status')->default(0);
            $table->text('verification_rejection_reason')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('biraconsultant_users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('biraconsultant_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->nullable()->unique();
            $table->longText('description');
            $table->longText('requirements')->nullable();
            $table->longText('responsibilities')->nullable();
            $table->longText('how_to_apply')->nullable();
            $table->foreignId('company_id')->constrained('biraconsultant_companies')->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('biraconsultant_job_categories')->nullOnDelete();
            $table->string('location')->nullable();
            $table->foreignId('job_type_id')->nullable()->constrained('biraconsultant_job_types')->nullOnDelete();
            $table->string('salary', 100)->nullable();
            $table->foreignId('experience_level_id')->nullable()->constrained('biraconsultant_job_experience_levels')->nullOnDelete();
            $table->date('deadline')->nullable();
            $table->boolean('status')->default(true)->index();
            $table->string('approval_status', 20)->default('pending')->index();
            $table->text('rejection_reason')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('biraconsultant_users')->nullOnDelete();
            $table->unsignedSmallInteger('duration')->default(30);
            $table->timestamps();
            $table->index(['approval_status', 'status', 'deadline']);
        });

        Schema::create('biraconsultant_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('biraconsultant_users')->cascadeOnDelete();
            $table->foreignId('job_id')->constrained('biraconsultant_jobs')->cascadeOnDelete();
            $table->unsignedBigInteger('status_id')->default(1);
            $table->timestamp('reviewed_at')->nullable();
            $table->longText('cover_letter')->nullable();
            $table->string('cv_path')->nullable();
            $table->string('resume_path')->nullable();
            $table->string('cover_letter_path')->nullable();
            $table->timestamps();
            $table->foreign('status_id')->references('id')->on('biraconsultant_application_status')->restrictOnDelete();
            $table->unique(['user_id', 'job_id']);
        });

        Schema::create('biraconsultant_blog_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('biraconsultant_blogs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('content');
            $table->string('featured_image')->nullable();
            $table->foreignId('author_id')->nullable()->constrained('biraconsultant_users')->nullOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('biraconsultant_blog_categories')->nullOnDelete();
            $table->string('status', 20)->default('draft')->index();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });

        Schema::create('biraconsultant_user_work_experiences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('biraconsultant_users')->cascadeOnDelete();
            $table->string('company_name');
            $table->string('position');
            $table->text('description')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->timestamps();
        });

        foreach ([
            ['biraconsultant_user_skills', 'user_id', 'skill_id', 'biraconsultant_job_skills'],
            ['biraconsultant_user_category_interests', 'user_id', 'category_id', 'biraconsultant_job_categories'],
            ['biraconsultant_job_skill', 'job_id', 'skill_id', 'biraconsultant_job_skills'],
        ] as [$name, $left, $right, $related]) {
            Schema::create($name, function (Blueprint $table) use ($left, $right, $related) {
                $table->foreignId($left)->constrained(str_starts_with($left, 'user_') || $left === 'user_id' ? 'biraconsultant_users' : 'biraconsultant_jobs')->cascadeOnDelete();
                $table->foreignId($right)->constrained($related)->cascadeOnDelete();
                $table->primary([$left, $right]);
            });
        }

        Schema::create('biraconsultant_saved_jobs', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained('biraconsultant_users')->cascadeOnDelete();
            $table->foreignId('job_id')->constrained('biraconsultant_jobs')->cascadeOnDelete();
            $table->timestamps();
            $table->primary(['user_id', 'job_id']);
        });

        Schema::create('biraconsultant_company_followers', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained('biraconsultant_users')->cascadeOnDelete();
            $table->foreignId('company_id')->constrained('biraconsultant_companies')->cascadeOnDelete();
            $table->timestamps();
            $table->primary(['user_id', 'company_id']);
        });

        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->morphs('notifiable');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });

        Schema::create('cache', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->mediumText('value');
            $table->integer('expiration')->index();
        });

        Schema::create('cache_locks', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->string('owner');
            $table->integer('expiration')->index();
        });

        Schema::create('jobs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('queue')->index();
            $table->longText('payload');
            $table->unsignedTinyInteger('attempts');
            $table->unsignedInteger('reserved_at')->nullable();
            $table->unsignedInteger('available_at');
            $table->unsignedInteger('created_at');
        });

        Schema::create('job_batches', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->integer('total_jobs');
            $table->integer('pending_jobs');
            $table->integer('failed_jobs');
            $table->longText('failed_job_ids');
            $table->mediumText('options')->nullable();
            $table->integer('cancelled_at')->nullable();
            $table->integer('created_at');
            $table->integer('finished_at')->nullable();
        });

        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at')->useCurrent();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        Schema::create('biraconsultant_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        foreach ([
            'biraconsultant_settings', 'sessions', 'failed_jobs', 'job_batches', 'jobs', 'cache_locks', 'cache', 'notifications', 'biraconsultant_company_followers',
            'biraconsultant_saved_jobs', 'biraconsultant_job_skill',
            'biraconsultant_user_category_interests', 'biraconsultant_user_skills',
            'biraconsultant_user_work_experiences', 'biraconsultant_blogs',
            'biraconsultant_blog_categories', 'biraconsultant_applications',
            'biraconsultant_jobs', 'biraconsultant_companies',
            'biraconsultant_application_status', 'biraconsultant_job_skills',
            'biraconsultant_job_experience_levels', 'biraconsultant_job_types',
            'biraconsultant_job_categories', 'biraconsultant_users',
            'biraconsultant_countries',
        ] as $table) {
            Schema::dropIfExists($table);
        }
    }
};
