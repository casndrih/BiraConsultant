<!-- Signin modal -->
<?php
    $emailLoginEnabled = \App\Services\AuthMethodSettings::emailEnabled();
    $googleLoginEnabled = \App\Services\AuthMethodSettings::googleEnabled();
?>
<div id="signin-modal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative w-full max-w-md max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white dark:bg-black rounded-lg shadow">
            <button type="button" class="qj-modal-close absolute top-3 right-2.5 text-black dark:text-white bg-transparent rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" data-modal-hide="signin-modal">
            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                <span class="sr-only">Close modal</span>
            </button>
            <div class="px-6 py-3 lg:px-8">
                <h3 class="mb-1 text-2xl font-medium text-black dark:text-white">Welcome!</h3>
                <p class="mb-4 text-sm text-gray-600 dark:text-gray-400">Sign in to continue to your QJobs account.</p>
                @if($googleLoginEnabled)
                    <a href="{{ route('auth.google.redirect', ['platform' => 'web']) }}" class="mb-4 w-full flex items-center justify-center gap-3 rounded-full border border-gray-300 dark:border-gray-700 bg-white dark:bg-custom-dark px-5 py-3 text-sm font-medium text-gray-900 dark:text-white hover:bg-gray-50 dark:hover:bg-gray-800">
                        <span class="text-lg font-bold text-green-600">G</span>
                        Continue with Google
                    </a>
                @endif
                @if($googleLoginEnabled && $emailLoginEnabled)
                    <div class="mb-4 flex items-center gap-3 text-xs tracking-wider text-gray-400"><span class="h-px flex-1 bg-gray-200 dark:bg-gray-700"></span>OR CONTINUE WITH EMAIL<span class="h-px flex-1 bg-gray-200 dark:bg-gray-700"></span></div>
                @endif
                @if($emailLoginEnabled)
                <form class="space-y-6" action="{{ URL::to('/signin') }}" method="POST">
                    @csrf
                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2.5 rounded-lg text-sm">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div>
                        <label for="signin-email" class="block mb-2 text-sm font-medium text-black dark:text-white">Email</label>
                        <input type="email" name="email" id="signin-email" value="{{ old('email') }}" autocomplete="email" class="bg-gray-100 dark:bg-custom-dark text-black dark:text-white text-sm rounded-full focus:ring-yellow-300 block w-full p-2.5 placeholder-gray-500 dark:placeholder-gray-400" placeholder="your@email.com" required>
                    </div>
                    <div>
                        <label for="signin-password" class="block mb-2 text-sm font-medium text-black dark:text-white">Password</label>
                        <input type="password" name="password" id="signin-password" autocomplete="current-password" placeholder="••••••••" class="bg-gray-100 dark:bg-custom-dark text-black dark:text-white text-sm rounded-full focus:ring-yellow-300 block w-full p-2.5 placeholder-gray-500 dark:placeholder-gray-400" required>
                    </div>
                    <div class="flex justify-between">
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                            <input id="remember" name="remember" type="checkbox" class="qj-modal-choice w-5 h-5 bg-gray-100 dark:bg-custom-dark rounded">
                            </div>
                            <label for="remember" class="ml-2 text-sm text-black dark:text-white">Remember me</label>
                        </div>
                        <a data-modal-target="reset-modal" data-modal-show="reset-modal" data-modal-hide="signin-modal" class="qj-modal-accent-text text-sm hover:underline cursor-pointer">Lost Password</a>
                    </div>

                    @include('components.captcha')

                    <button type="submit" class="qj-modal-primary w-full text-medium font-medium rounded-full px-5 py-2.5 text-center">Sign in to your account</button>

                    <div class="text-sm text-gray-600 dark:text-gray-400">
                      Not signup yet? <a data-modal-target="signup-modal" data-modal-show="signup-modal" data-modal-hide="signin-modal" class="qj-modal-accent-text hover:underline cursor-pointer">Create account</a>
                    </div>
                    </form>
                @elseif(!$googleLoginEnabled)
                    <p class="text-sm text-gray-600 dark:text-gray-400">Sign-in is not currently available. Contact the site administrator.</p>
                @endif
                    </div>
                    </div>
                    </div>
                    </div>
                                 
@if($emailLoginEnabled)
<!-- Signup modal -->
<div id="signup-modal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
<div class="relative w-full max-w-md max-h-full">

<!-- Modal content -->
<div class="relative bg-white dark:bg-black rounded-lg shadow">
<button type="button" class="qj-modal-close absolute top-3 right-2.5 text-black dark:text-white bg-transparent rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" data-modal-hide="signup-modal">
<svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
<span class="sr-only">Close modal</span>
</button>
<div class="px-6 py-3 lg:px-8 max-h-[80vh] overflow-y-auto">
<h3 class="mb-1 text-2xl font-medium text-black dark:text-white">Register</h3>
<p class="mb-4 text-sm text-gray-600 dark:text-gray-400">Create account to get started</p>
<form class="space-y-4" action="{{ URL::to('/signup') }}" method="POST" id="signupForm">
@csrf

@if ($errors->any())
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2.5 rounded-lg text-sm">
    <p class="font-medium mb-1">Please fix the following:</p>
    <ul class="list-disc list-inside space-y-1">
        @foreach ($errors->all() as $err)
            <li>{{ $err }}</li>
        @endforeach
    </ul>
</div>
@endif

<!-- Account Type Selector -->
<div>
    <label class="block mb-2 text-sm font-medium text-black dark:text-white">Account Type</label>
    <div class="flex gap-4">
        <label class="flex items-center gap-2 cursor-pointer">
            <input type="radio" name="role" value="0" class="qj-modal-choice w-4 h-4 bg-gray-100 dark:bg-custom-dark border-gray-300 dark:border-gray-600 cursor-pointer" @checked(old('role', '0') == '0') onchange="toggleAccountType(0)">
            <span class="text-sm text-black dark:text-white">🎓 Applicant</span>
        </label>
        <label class="flex items-center gap-2 cursor-pointer">
            <input type="radio" name="role" value="2" class="qj-modal-choice w-4 h-4 bg-gray-100 dark:bg-custom-dark border-gray-300 dark:border-gray-600 cursor-pointer" @checked(old('role') == '2') onchange="toggleAccountType(2)">
            <span class="text-sm text-black dark:text-white">🏢 Company</span>
        </label>
    </div>
</div>

<!-- Company Name (shared field for both, label changes) -->
<div id="company-name-row" class="{{ old('role') == '2' ? '' : 'hidden' }}">
    <label for="company_name" class="block mb-1 text-sm font-medium text-black dark:text-white">Company Name</label>
    <input type="text" name="company_name" id="company_name" value="{{ old('company_name') }}" class="bg-gray-100 dark:bg-custom-dark text-black dark:text-white text-sm rounded-full focus:ring-yellow-300 block w-full p-2.5 placeholder-gray-500 dark:placeholder-gray-400" placeholder="e.g. Tree">
    <p id="domain-hint" class="text-xs text-gray-500 dark:text-gray-400 mt-1 hidden">Email will use domain: <span class="qj-modal-accent-text font-medium">@<span id="domain-text">example</span>.com</span></p>
</div>

<div id="name-row">
    <label for="name" id="name-label" class="block mb-1 text-sm font-medium text-black dark:text-white">Full Name</label>
    <input type="text" name="name" id="name" value="{{ old('name') }}" autocomplete="name" class="bg-gray-100 dark:bg-custom-dark text-black dark:text-white text-sm rounded-full focus:ring-yellow-300 block w-full p-2.5 placeholder-gray-500 dark:placeholder-gray-400" placeholder="John Doe" required>
</div>

<div>
    <label for="username" class="block mb-1 text-sm font-medium text-black dark:text-white">Username</label>
    <input type="text" name="username" id="username" value="{{ old('username') }}" autocomplete="username" class="bg-gray-100 dark:bg-custom-dark text-black dark:text-white text-sm rounded-full focus:ring-yellow-300 block w-full p-2.5 placeholder-gray-500 dark:placeholder-gray-400" placeholder="johndoe" required minlength="3" pattern="[A-Za-z0-9._\-]+">
    <span id="username-error" class="text-red-500 text-xs font-medium hidden">Username already taken</span>
    <span class="text-xs text-gray-500 dark:text-gray-400">Letters, numbers, dots, dashes and underscores only.</span>
</div>

<!-- Date of birth & Gender (applicant only) -->
<div id="dob-row" class="{{ old('role') == '2' ? '' : 'hidden' }}">
    <label for="birthday" class="block mb-1 text-sm font-medium text-black dark:text-white">Date of birth</label>
    <input type="date" id="birthday" name="birthday" value="{{ old('birthday') }}" max="{{ \Carbon\Carbon::today()->toDateString() }}" class="bg-gray-100 dark:bg-custom-dark text-black dark:text-white text-sm rounded-full focus:ring-yellow-300 block w-full p-2.5 placeholder-gray-500 dark:placeholder-gray-400">
</div>

<div id="gender-row" class="{{ old('role') == '2' ? '' : 'hidden' }}">
    <label for="gender" class="block mb-1 text-sm font-medium text-black dark:text-white">Gender</label>
    <select name="gender" id="gender" class="bg-gray-100 dark:bg-custom-dark text-black dark:text-white text-sm rounded-full focus:ring-yellow-300 block w-full p-2.5">
    <option value="0" @selected(old('gender', '0') == '0')>Male</option>
    <option value="1" @selected(old('gender') == '1')>Female</option>
    </select>
</div>

<div>
    <label for="country_id" class="block mb-1 text-sm font-medium text-black dark:text-white">Country</label>
    <select name="country_id" id="country_id" class="bg-gray-100 dark:bg-custom-dark text-black dark:text-white text-sm rounded-full focus:ring-yellow-300 block w-full p-2.5" required>
    <option value="">Select your country</option>
    @foreach (\App\Models\QJobsCountry::orderBy('name')->get(['id', 'name']) as $country)
        <option value="{{ $country->id }}" @selected(old('country_id') == $country->id)>{{ $country->name }}</option>
    @endforeach
    </select>
</div>

<div>
    <label for="signup-email" class="block mb-1 text-sm font-medium text-black dark:text-white">Email</label>
    <div id="email-wrapper" class="relative">
        <input type="email" name="email" id="signup-email" value="{{ old('role') == '2' ? '' : old('email') }}" autocomplete="email" @disabled(old('role') == '2') class="bg-gray-100 dark:bg-custom-dark text-black dark:text-white text-sm rounded-full focus:ring-yellow-300 block w-full p-2.5 placeholder-gray-500 dark:placeholder-gray-400" placeholder="your@email.com" required>
        <span id="email-domain-suffix" class="hidden absolute right-3 top-1/2 -translate-y-1/2 text-sm text-gray-500 dark:text-gray-400 pointer-events-none truncate max-w-[120px]"></span>
    </div>
    <!-- Company email inline layout -->
    <div id="company-email-fields" class="{{ old('role') == '2' ? 'flex' : 'hidden' }} items-center gap-0 mt-1">
        <div class="flex items-center bg-gray-100 dark:bg-custom-dark rounded-full px-3 py-2 flex-1 min-w-0">
            <span id="company-email-prefix" contenteditable="true" class="text-sm text-black dark:text-white outline-none min-w-[40px] max-w-[120px] truncate" style="word-break: break-all;">{{ old('role') == '2' ? 'contact' : '' }}</span>
            <span class="text-sm text-gray-400 flex-shrink-0 mx-0.5">@</span>
            <span id="company-email-domain" class="text-sm text-gray-400 flex-shrink-0 truncate"></span>
        </div>
        <input type="hidden" name="email" id="signup-email-full" value="{{ old('role') == '2' ? old('email') : '' }}" @disabled(old('role') != '2')>
    </div>
    <span id="email-error" class="text-red-500 text-xs font-medium hidden">Email already registered</span>
</div>

<div>
    <label for="signup-password" class="block mb-1 text-sm font-medium text-black dark:text-white">Password</label>
    <input type="password" name="password" id="signup-password" autocomplete="new-password" placeholder="••••••••" class="bg-gray-100 dark:bg-custom-dark text-black dark:text-white text-sm rounded-full focus:ring-yellow-300 block w-full p-2.5 placeholder-gray-500 dark:placeholder-gray-400" required minlength="8">
    <span class="text-xs text-gray-500 dark:text-gray-400">At least 8 characters with upper & lower case letters and a number.</span>
</div>

<div>
    <label for="password_confirmation" class="block mb-1 text-sm font-medium text-black dark:text-white">Confirm password</label>
    <input type="password" name="password_confirmation" id="password_confirmation" autocomplete="new-password" placeholder="••••••••" class="bg-gray-100 dark:bg-custom-dark text-black dark:text-white text-sm rounded-full focus:ring-yellow-300 block w-full p-2.5 placeholder-gray-500 dark:placeholder-gray-400" required>
    <span id="password-error" class="text-red-500 text-xs font-medium hidden">Passwords do not match</span>
</div>

<div class="flex items-start">
    <div class="flex items-center h-5">
        <input id="agree" name="agree" type="checkbox" class="qj-modal-choice w-5 h-5 bg-gray-100 dark:bg-custom-dark rounded" @checked(old('agree')) required>
    </div>
    <label for="agree" class="ml-2 text-sm text-black dark:text-white">I agree to the <a href="{{ URL::to('/privacy') }}" class="qj-modal-accent-text hover:underline">Privacy Policy</a> & <a href="{{ URL::to('/terms') }}" class="qj-modal-accent-text hover:underline">T&C</a> of QJobs.</label>
</div>

@include('components.captcha')

<button id="btnreg" type="submit" class="qj-modal-primary w-full text-medium font-medium rounded-full px-5 py-2.5 text-center">Register account</button>

<div class="text-sm text-gray-600 dark:text-gray-400">
  Already Signup? <a data-modal-target="signin-modal" data-modal-show="signin-modal" data-modal-hide="signup-modal" class="qj-modal-accent-text hover:underline cursor-pointer">Signin to account</a>
</div>
</form>
</div>
</div>
</div>
</div>
         
@endif

<!-- Password Reset modal -->
<div id="reset-modal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
<div class="relative w-full max-w-md max-h-full">
<!-- Modal content -->
<div class="relative bg-white dark:bg-black rounded-lg shadow">
    <button type="button" class="qj-modal-close absolute top-3 right-2.5 text-black dark:text-white bg-transparent rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" data-modal-hide="reset-modal">
    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
    <span class="sr-only">Close modal</span>
    </button>
    <div class="px-6 py-3 lg:px-8">
    <h3 class="mb-1 text-2xl font-medium text-black dark:text-white">Account Recovery</h3>
    <p class="mb-4 text-sm text-gray-600 dark:text-gray-400">Enter your email address to reset your password</p>
    <form class="space-y-6" action="{{ URL::to('/forgotpassword') }}" method="POST" id="resetForm">
    @csrf
    <div>
        <label for="reset-email" class="block mb-2 text-sm font-medium text-black dark:text-white">Email</label>
        <input type="email" name="email" id="reset-email" autocomplete="email" class="bg-gray-100 dark:bg-custom-dark text-black dark:text-white text-sm rounded-full focus:ring-yellow-300 block w-full p-2.5 placeholder-gray-500 dark:placeholder-gray-400" placeholder="your@email.com" required>
    </div>

    @include('components.captcha')

    <button type="submit" class="qj-modal-primary w-full text-medium font-medium rounded-full px-5 py-2.5 text-center">Reset password</button>
                  
    <div class="text-sm text-gray-600 dark:text-gray-400">
     Not signup yet? <a data-modal-target="signup-modal" data-modal-show="signup-modal" data-modal-hide="reset-modal" class="qj-modal-accent-text hover:underline cursor-pointer">Create account</a>
    </div>
    </form>
    </div>
    </div>
    </div>
    </div>

{{-- Hidden triggers so JS can programmatically open a modal via Flowbite's own wiring --}}
<button id="open-signup-trigger" data-modal-toggle="signup-modal" class="hidden" tabindex="-1" aria-hidden="true"></button>
<button id="open-signin-trigger" data-modal-toggle="signin-modal" class="hidden" tabindex="-1" aria-hidden="true"></button>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // --- Server-side validation failed: reopen the right modal so errors are visible ---
    @if ($errors->any())
        @if (old('role') !== null || old('name') || old('company_name'))
            document.getElementById('open-signup-trigger').click();
        @elseif (old('email'))
            document.getElementById('open-signin-trigger').click();
        @endif
    @endif

    // --- Load countries into signup dropdown (server-rendered options are the fallback) ---
    const countrySelect = document.getElementById('country_id');
    if (countrySelect && countrySelect.options.length <= 1) {
        fetch('/api/countries')
            .then(r => r.json())
            .then(data => {
                countrySelect.innerHTML = '<option value="">Select your country</option>';
                data.forEach(c => {
                    countrySelect.innerHTML += '<option value="' + c.id + '">' + c.name + '</option>';
                });
            })
            .catch(() => {
                countrySelect.innerHTML = '<option value="">Failed to load countries</option>';
            });
    }

    // --- Username availability check ---
    const usernameInput = document.getElementById('username');
    const usernameError = document.getElementById('username-error');
    let usernameTimer;
    if (usernameInput) {
        usernameInput.addEventListener('input', function() {
            clearTimeout(usernameTimer);
            usernameError.classList.add('hidden');
            if (this.value.length < 3) return;
            usernameTimer = setTimeout(() => {
                fetch('/check-username?username=' + encodeURIComponent(this.value))
                    .then(r => r.json())
                    .then(data => {
                        if (data.exists) usernameError.classList.remove('hidden');
                    });
            }, 500);
        });
    }

    // --- Email availability check ---
    const emailInput = document.getElementById('signup-email');
    const emailError = document.getElementById('email-error');
    let emailTimer;
    if (emailInput) {
        emailInput.addEventListener('input', function() {
            clearTimeout(emailTimer);
            emailError.classList.add('hidden');
            if (!this.value.includes('@')) return;
            emailTimer = setTimeout(() => {
                fetch('/check-email?email=' + encodeURIComponent(this.value))
                    .then(r => r.json())
                    .then(data => {
                        if (data.exists) emailError.classList.remove('hidden');
                    });
            }, 500);
        });
    }

    // --- Password match check ---
    const passInput = document.getElementById('signup-password');
    const passConfirm = document.getElementById('password_confirmation');
    const passError = document.getElementById('password-error');
    function checkPasswordMatch() {
        if (passConfirm.value && passInput.value !== passConfirm.value) {
            passError.classList.remove('hidden');
        } else {
            passError.classList.add('hidden');
        }
    }
    if (passInput && passConfirm) {
        passInput.addEventListener('input', checkPasswordMatch);
        passConfirm.addEventListener('input', checkPasswordMatch);
    }

    // --- Signup form: validate + prevent double submission ---
    const signupForm = document.getElementById('signupForm');
    if (signupForm) {
        signupForm.addEventListener('submit', function(e) {
            if (!document.getElementById('agree').checked) {
                e.preventDefault();
                alert('Please agree to the Privacy Policy & Terms.');
                return;
            }
            if (passInput.value !== passConfirm.value) {
                e.preventDefault();
                passError.classList.remove('hidden');
                return;
            }
            const btn = document.getElementById('btnreg');
            if (btn.dataset.submitted === '1') {
                e.preventDefault();
                return;
            }
            btn.dataset.submitted = '1';
            btn.disabled = true;
            btn.innerHTML = 'Creating account...';
            setTimeout(function() { // re-enable if navigation was blocked
                btn.dataset.submitted = '0';
                btn.disabled = false;
                btn.innerHTML = 'Register account';
            }, 8000);
        });
    }

    // --- Reset form: show success message ---
    const resetForm = document.getElementById('resetForm');
    if (resetForm) {
        resetForm.addEventListener('submit', function(e) {
            const btn = this.querySelector('button[type="submit"]');
            btn.disabled = true;
            btn.innerHTML = 'Sending...';
        });
    }

    // --- Restore company mode on load when returning after a validation error ---
    @if (old('role') == '2')
        toggleAccountType(2, true);
    @endif
});

// --- Toggle account type (Applicant / Company) ---
// restoreState: skip re-seeding values when restoring after a server-side error.
function toggleAccountType(role, restoreState) {
    const companyRow = document.getElementById('company-name-row');
    const companyInput = document.getElementById('company_name');
    const nameRow = document.getElementById('name-row');
    const nameLabel = document.getElementById('name-label');
    const nameInput = document.getElementById('name');
    const dobRow = document.getElementById('dob-row');
    const genderRow = document.getElementById('gender-row');
    const genderSelect = document.getElementById('gender');
    const domainHint = document.getElementById('domain-hint');
    const emailInput = document.getElementById('signup-email');
    const usernameInput = document.getElementById('username');
    const companyEmailFields = document.getElementById('company-email-fields');
    const companyEmailPrefix = document.getElementById('company-email-prefix');
    const companyEmailDomain = document.getElementById('company-email-domain');
    const signupEmailFull = document.getElementById('signup-email-full');

    if (role === 2) {
        // ===== COMPANY MODE =====
        companyRow.classList.remove('hidden');
        companyInput.setAttribute('required', 'required');

        // Hide personal fields
        dobRow.classList.add('hidden');
        genderRow.classList.add('hidden');
        genderSelect.removeAttribute('required');

        // Rename "Full Name" → "Contact Person Name"
        nameLabel.textContent = 'Contact Person Name';
        nameInput.placeholder = 'e.g. John Doe';

        // Show domain hint
        domainHint.classList.remove('hidden');

        // Switch to company email layout: disable the normal input (so exactly one
        // "email" field is submitted) and show the inline layout instead.
        emailInput.classList.add('hidden');
        emailInput.removeAttribute('required');
        emailInput.setAttribute('disabled', 'disabled');
        companyEmailFields.classList.remove('hidden');
        companyEmailFields.classList.add('flex');
        signupEmailFull.removeAttribute('disabled');
        signupEmailFull.setAttribute('required', 'required');

        // Function to update company email
        function updateCompanyEmail() {
            const companyVal = companyInput.value.trim().toLowerCase().replace(/[^a-z0-9]/g, '');
            const prefixVal = companyEmailPrefix.textContent.trim();
            if (companyVal.length > 0) {
                companyEmailDomain.textContent = companyVal + '.com';
                document.getElementById('domain-text').textContent = companyVal;
                signupEmailFull.value = (prefixVal ? prefixVal : '') + '@' + companyVal + '.com';
            } else {
                companyEmailDomain.textContent = '';
                signupEmailFull.value = '';
            }
        }

        // Bind sync listeners only once (repeated toggles must not stack handlers).
        if (!companyInput.dataset.bound) {
            companyInput.dataset.bound = '1';
            companyInput.addEventListener('input', function() {
                if (document.querySelector('input[name="role"]:checked').value !== '2') return;
                const val = this.value.trim().toLowerCase().replace(/[^a-z0-9]/g, '');
                usernameInput.value = val;
                updateCompanyEmail();
            });
            companyEmailPrefix.addEventListener('input', updateCompanyEmail);
            companyEmailPrefix.addEventListener('paste', function(e) {
                e.preventDefault();
                const text = (e.clipboardData || window.clipboardData).getData('text/plain').replace(/[^a-z0-9._-]/gi, '');
                document.execCommand('insertText', false, text);
                setTimeout(updateCompanyEmail, 0);
            });
        }

        if (!restoreState) {
            // Auto-set default prefix
            companyEmailPrefix.textContent = 'contact';
        }
        updateCompanyEmail();

    } else {
        // ===== APPLICANT MODE =====
        companyRow.classList.add('hidden');
        companyInput.removeAttribute('required');

        // Show personal fields
        dobRow.classList.remove('hidden');
        genderRow.classList.remove('hidden');
        genderSelect.setAttribute('required', 'required');

        // Restore "Full Name"
        nameLabel.textContent = 'Full Name';
        nameInput.placeholder = 'John Doe';

        // Hide domain hint
        domainHint.classList.add('hidden');

        // Restore normal email input; disable the hidden company email field
        emailInput.classList.remove('hidden');
        emailInput.removeAttribute('disabled');
        emailInput.setAttribute('required', 'required');
        emailInput.placeholder = 'your@email.com';
        companyEmailFields.classList.add('hidden');
        companyEmailFields.classList.remove('flex');
        signupEmailFull.setAttribute('disabled', 'disabled');
        signupEmailFull.removeAttribute('required');

        // Auto-generate username from full name (bound once)
        if (!nameInput.dataset.bound) {
            nameInput.dataset.bound = '1';
            nameInput.addEventListener('input', function() {
                if (document.querySelector('input[name="role"]:checked').value !== '0') return;
                const val = this.value.trim().toLowerCase().replace(/\s+/g, '.').replace(/[^a-z0-9.]/g, '');
                usernameInput.value = val || '';
            });
        }
    }
}
</script>

{{-- Re-open the relevant auth modal after a server-side validation failure,
    otherwise the rendered error list stays hidden and the user gets no feedback. --}}
@if ($errors->any())
@php
    $authErrorModal = (old('name') || old('role') || old('birthday') || old('username'))
        ? 'signup-modal' : 'signin-modal';
@endphp
<script>
    (function () {
        function openAuthErrorModal() {
            var modal = document.getElementById('{{ $authErrorModal }}');
            var trigger = document.querySelector('[data-modal-show="{{ $authErrorModal }}"]');
            if (modal && trigger && modal.classList.contains('hidden')) {
                trigger.click();
            }
        }
        // Run after Flowbite (loaded later in the page) has bound its modal triggers.
        if (document.readyState === 'complete') {
            setTimeout(openAuthErrorModal, 50);
        } else {
            window.addEventListener('load', function () { setTimeout(openAuthErrorModal, 50); });
        }
    })();
</script>
@endif
