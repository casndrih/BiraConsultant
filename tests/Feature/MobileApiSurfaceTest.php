<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MobileApiSurfaceTest extends TestCase
{
    use RefreshDatabase;
    public function test_mobile_user_api_routes_are_registered(): void
    {
        $routeNames = [
            'api.v1.auth.login', 'api.v1.auth.register', 'api.v1.auth.options', 'api.v1.auth.me',
            'api.v1.jobs.index', 'api.v1.jobs.show', 'api.v1.jobs.status',
            'api.v1.companies.index', 'api.v1.companies.show',
            'api.v1.home.jobs', 'api.v1.home.companies', 'api.v1.meta',
            'api.v1.user.dashboard', 'api.v1.user.profile', 'api.v1.user.applications',
            'api.v1.user.skills.add', 'api.v1.user.work-experiences.add',
            'api.v1.user.companies.follow', 'api.v1.user.jobs.apply', 'api.v1.user.jobs.save',
            'api.v1.user.saved-jobs', 'api.v1.user.notifications', 'api.v1.user.profile.update',
        ];

        foreach ($routeNames as $routeName) {
            $this->assertTrue(Route::has($routeName), "Missing API route [{$routeName}].");
        }
    }

    public function test_company_and_admin_api_routes_are_registered(): void
    {
        $routeNames = [
            'api.v1.company.dashboard', 'api.v1.company.applications.index',
            'api.v1.company.applications.stage', 'api.v1.company.jobs.index',
            'api.v1.company.jobs.store', 'api.v1.company.jobs.update', 'api.v1.company.jobs.toggle',
            'api.v1.admin.dashboard', 'api.v1.admin.jobs.index', 'api.v1.admin.jobs.approve',
            'api.v1.admin.companies.index', 'api.v1.admin.companies.approve',
            'api.v1.admin.applications.index', 'api.v1.admin.users.index',
            'api.v1.admin.identity-verification.index', 'api.v1.admin.blogs.index',
            'api.v1.admin.settings.index', 'api.v1.admin.settings.update',
        ];

        foreach ($routeNames as $routeName) {
            $this->assertTrue(Route::has($routeName), "Missing API route [{$routeName}].");
        }
    }

    public function test_company_and_admin_dashboard_apis_require_authentication(): void
    {
        $this->getJson('/api/v1/company/dashboard')->assertUnauthorized();
        $this->getJson('/api/v1/admin/dashboard')->assertUnauthorized();
        $this->getJson('/api/v1/user/profile')->assertUnauthorized();
    }
}
