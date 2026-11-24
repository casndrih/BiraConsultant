@php
    $recaptchaEnabled = \App\Models\BiraConsultantSetting::getValue('recaptcha_enabled', '0') === '1';
    $recaptchaSiteKey = \App\Models\BiraConsultantSetting::getValue('recaptcha_site_key', '');
@endphp

@if($recaptchaEnabled && $recaptchaSiteKey)
<div class="my-4">
    <div class="g-recaptcha" data-sitekey="{{ $recaptchaSiteKey }}"></div>
</div>
@endif

@if($recaptchaEnabled && $recaptchaSiteKey)
@push('scripts')
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endpush
@endif