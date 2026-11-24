<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Pagination\Paginator;
use App\Models\BiraConsultantSetting;
use Throwable;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
     Schema::defaultStringLength(191);
     Paginator::defaultView('vendor.pagination.tailwind');

     /*
     |----------------------------------------------------------------------
     | Named rate limiters
     |----------------------------------------------------------------------
     | The framework's default guest throttle key is domain|ip only (no route
     | URI), so plain `throttle:5,1` on several auth routes would share ONE
     | bucket per IP. These named limiters key by route path so each auth
     | endpoint has its own brute-force budget.
     */
     RateLimiter::for('auth', function (Request $request) {
         return Limit::perMinute(5)->by('auth|'.$request->path().'|'.($request->user()?->getAuthIdentifier() ?: $request->ip()));
     });

     RateLimiter::for('lookup', function (Request $request) {
         return Limit::perMinute(30)->by('lookup|'.$request->path().'|'.$request->ip());
     });

     
     /*
     |----------------------------------------------------------------------
     | Database-managed mail settings
     |----------------------------------------------------------------------
     | Admin > Settings > Email stores credentials in the settings table.
     | Apply them before the mail manager is resolved so contact forms and
     | notifications use the current administrator-configured transport.
     */
     try {
         if (Schema::hasTable('biraconsultant_settings')) {
             $mailSettings = BiraConsultantSetting::whereIn('key', [
                 'mail_driver', 'mail_host', 'mail_port', 'mail_username',
                 'mail_password', 'mail_encryption', 'mail_from_address', 'mail_from_name',
             ])->pluck('value', 'key');

             $driver = $mailSettings->get('mail_driver');
             if (in_array($driver, ['smtp', 'sendmail', 'mailgun', 'ses', 'log', 'array'], true)) {
                 config(['mail.default' => $driver]);
             }

             $smtp = config('mail.mailers.smtp', []);
             foreach ([
                 'host' => 'mail_host',
                 'username' => 'mail_username',
                 'password' => 'mail_password',
             ] as $configKey => $settingKey) {
                 $value = $mailSettings->get($settingKey);
                 if ($value !== null && $value !== '') {
                     $smtp[$configKey] = $value;
                 }
             }

             $port = $mailSettings->get('mail_port');
             if ($port !== null && $port !== '' && ctype_digit((string) $port)) {
                 $smtp['port'] = (int) $port;
             }

             if ($mailSettings->has('mail_encryption')) {
                 $smtp['encryption'] = $mailSettings->get('mail_encryption') ?: null;
             }

             config(['mail.mailers.smtp' => $smtp]);

             $fromAddress = $mailSettings->get('mail_from_address');
             $fromName = $mailSettings->get('mail_from_name');
             if ($fromAddress) {
                 config(['mail.from.address' => $fromAddress]);
             }
             if ($fromName) {
                 config(['mail.from.name' => $fromName]);
             }
         }
     } catch (Throwable $exception) {
         // Keep the environment-based mail configuration available during
         // first installation or while the settings database is unavailable.
         report($exception);
     }
    }
}
