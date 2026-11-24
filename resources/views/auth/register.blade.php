@extends('app')

@section('title', 'Create an Account')

@section('header')
    @include('layouts.header')
@endsection

@section('content')
<div class="qj-content-page">
    <section class="qj-content-hero">
        <div class="qj-content-hero__inner">
            <span class="qj-content-hero__eyebrow">Join BiraConsultant</span>
            <h1 class="qj-content-hero__title">Create your account</h1>
            <p class="qj-content-hero__subtitle">Find opportunities or connect with talent as an employer.</p>
        </div>
    </section>
    <div class="qj-content-wrap qj-content-wrap--narrow">
        <div class="max-w-2xl mx-auto qj-content-panel">
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg text-sm mb-5">{{ session('error') }}</div>
            @endif
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg text-sm mb-5">
                    <p class="font-medium mb-1">Please fix the following:</p>
                    <ul class="list-disc list-inside space-y-1">@foreach ($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul>
                </div>
            @endif
            @if($emailRegistrationEnabled)
            <form class="space-y-4" action="{{ route('signUp') }}" method="POST" id="signupForm">
                @csrf
                <div>
                    <label class="block mb-2 text-sm font-medium">Account type</label>
                    <div class="flex flex-wrap gap-5">
                        <label class="inline-flex items-center gap-2"><input type="radio" name="role" value="0" @checked(old('role','0') == '0') onchange="toggleAccountType(0)"> Applicant</label>
                        <label class="inline-flex items-center gap-2"><input type="radio" name="role" value="2" @checked(old('role') == '2') onchange="toggleAccountType(2)"> Company</label>
                    </div>
                </div>
                <div id="company-name-row" class="{{ old('role') == '2' ? '' : 'hidden' }}">
                    <label for="company_name" class="block mb-1 text-sm font-medium">Company name</label>
                    <input class="qj-content-form-control" type="text" name="company_name" id="company_name" value="{{ old('company_name') }}" placeholder="Company name">
                </div>
                <div>
                    <label for="name" id="name-label" class="block mb-1 text-sm font-medium">{{ old('role') == '2' ? 'Contact person name' : 'Full name' }}</label>
                    <input class="qj-content-form-control" type="text" name="name" id="name" value="{{ old('name') }}" autocomplete="name" required>
                </div>
                <div>
                    <label for="username" class="block mb-1 text-sm font-medium">Username</label>
                    <input class="qj-content-form-control" type="text" name="username" id="username" value="{{ old('username') }}" autocomplete="username" minlength="3" pattern="[A-Za-z0-9._-]+" required>
                    <p id="username-error" class="hidden text-red-500 text-xs mt-1">Username already taken.</p>
                </div>
                <div id="dob-row" class="{{ old('role') == '2' ? 'hidden' : '' }}">
                    <label for="birthday" class="block mb-1 text-sm font-medium">Date of birth</label>
                    <input class="qj-content-form-control" type="date" name="birthday" id="birthday" value="{{ old('birthday') }}" max="{{ \Carbon\Carbon::today()->toDateString() }}">
                </div>
                <div id="gender-row" class="{{ old('role') == '2' ? 'hidden' : '' }}">
                    <label for="gender" class="block mb-1 text-sm font-medium">Gender</label>
                    <select class="qj-content-form-control" name="gender" id="gender">
                        <option value="0" @selected(old('gender','0') == '0')>Male</option>
                        <option value="1" @selected(old('gender') == '1')>Female</option>
                    </select>
                </div>
                <div id="country-row" class="{{ old('role') == '2' ? 'hidden' : '' }}">
                    <label for="country_id" class="block mb-1 text-sm font-medium">Country</label>
                    <select class="qj-content-form-control" name="country_id" id="country_id">
                        <option value="">Select your country</option>
                        @foreach($countries as $country)
                            <option value="{{ $country->id }}" @selected(old('country_id') == $country->id)>{{ $country->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="signup-email" class="block mb-1 text-sm font-medium">Email</label>
                    <input class="qj-content-form-control" type="email" name="email" id="signup-email" value="{{ old('email') }}" autocomplete="email" required>
                    <p id="email-error" class="hidden text-red-500 text-xs mt-1">Email already registered.</p>
                    <p id="company-email-preview" class="hidden text-xs text-gray-500 mt-1"></p>
                </div>
                <div>
                    <label for="signup-password" class="block mb-1 text-sm font-medium">Password</label>
                    <input class="qj-content-form-control" type="password" name="password" id="signup-password" autocomplete="new-password" minlength="8" required>
                    <p class="text-xs text-gray-500 mt-1">At least 8 characters with upper and lower case letters and a number.</p>
                </div>
                <div>
                    <label for="password_confirmation" class="block mb-1 text-sm font-medium">Confirm password</label>
                    <input class="qj-content-form-control" type="password" name="password_confirmation" id="password_confirmation" autocomplete="new-password" required>
                    <p id="password-error" class="hidden text-red-500 text-xs mt-1">Passwords do not match.</p>
                </div>
                <label class="flex items-start gap-2 text-sm">
                    <input id="agree" name="agree" type="checkbox" value="1" required @checked(old('agree'))>
                    <span>I agree to the <a href="{{ route('privacy') }}" class="qj-auth-link">Privacy Policy</a> and <a href="{{ route('terms') }}" class="qj-auth-link">Terms & Conditions</a>.</span>
                </label>
                @include('components.captcha')
                <button id="btnreg" type="submit" class="qj-content-button w-full">Create account</button>
            </form>
            @else
                <p class="text-sm text-gray-600 dark:text-gray-300">Email registration is currently disabled. Please use an enabled sign-in method or contact the administrator.</p>
            @endif
            <p class="text-sm text-gray-600 dark:text-gray-300 mt-5">Already have an account? <a href="{{ route('login') }}" class="qj-auth-link">Sign in</a>.</p>
        </div>
    </div>
</div>
@endsection

@section('footer')
    @include('layouts.footer')
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const role = document.querySelector('input[name="role"]:checked');
    if (role) toggleAccountType(Number(role.value));

    const username = document.getElementById('username');
    const email = document.getElementById('signup-email');
    const usernameError = document.getElementById('username-error');
    const emailError = document.getElementById('email-error');
    const password = document.getElementById('signup-password');
    const confirmation = document.getElementById('password_confirmation');
    const passwordError = document.getElementById('password-error');
    let usernameTimer, emailTimer;

    username?.addEventListener('input', function () {
        clearTimeout(usernameTimer);
        usernameError.classList.add('hidden');
        if (this.value.length < 3) return;
        usernameTimer = setTimeout(() => fetch('/check-username?username=' + encodeURIComponent(this.value)).then(r => r.json()).then(d => { if (d.exists) usernameError.classList.remove('hidden'); }).catch(() => {}), 450);
    });
    email?.addEventListener('input', function () {
        clearTimeout(emailTimer);
        emailError.classList.add('hidden');
        if (!this.value.includes('@')) return;
        emailTimer = setTimeout(() => fetch('/check-email?email=' + encodeURIComponent(this.value)).then(r => r.json()).then(d => { if (d.exists) emailError.classList.remove('hidden'); }).catch(() => {}), 450);
    });
    const checkPassword = () => passwordError.classList.toggle('hidden', !confirmation.value || password.value === confirmation.value);
    password?.addEventListener('input', checkPassword);
    confirmation?.addEventListener('input', checkPassword);

    document.getElementById('signupForm')?.addEventListener('submit', function (event) {
        if (password.value !== confirmation.value) {
            event.preventDefault();
            passwordError.classList.remove('hidden');
            return;
        }
        const button = document.getElementById('btnreg');
        if (button.dataset.submitted === '1') { event.preventDefault(); return; }
        button.dataset.submitted = '1';
        button.disabled = true;
        button.textContent = 'Creating account...';
    });
});

function toggleAccountType(role) {
    const company = role === 2;
    document.getElementById('company-name-row').classList.toggle('hidden', !company);
    document.getElementById('dob-row').classList.toggle('hidden', company);
    document.getElementById('gender-row').classList.toggle('hidden', company);
    document.getElementById('country-row').classList.toggle('hidden', company);
    document.getElementById('name-label').textContent = company ? 'Contact person name' : 'Full name';

    const birthday = document.getElementById('birthday');
    const gender = document.getElementById('gender');
    const country = document.getElementById('country_id');
    const companyName = document.getElementById('company_name');
    birthday.required = !company;
    gender.required = !company;
    country.required = !company;
    companyName.required = company;

    const email = document.getElementById('signup-email');
    const username = document.getElementById('username');
    const preview = document.getElementById('company-email-preview');
    const updateCompany = () => {
        const slug = companyName.value.trim().toLowerCase().replace(/[^a-z0-9]/g, '');
        if (slug) {
            email.value = 'contact@' + slug + '.com';
            preview.textContent = 'Company account email: ' + email.value;
            preview.classList.remove('hidden');
            username.value = slug;
        } else {
            preview.classList.add('hidden');
        }
    };
    if (company) {
        email.readOnly = true;
        updateCompany();
        companyName.oninput = updateCompany;
    } else {
        email.readOnly = false;
        preview.classList.add('hidden');
        if (email.value.startsWith('contact@')) email.value = '';
    }
}
</script>
@endsection
