<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light dark">
    <title>Reset password · BiraConsultant</title>
    <style>
        :root { color-scheme: light dark; --bg:#f2f4f5; --card:#fff; --ink:#121522; --muted:#626873; --line:#e2e6ef; --green:#78c65a; }
        @media(prefers-color-scheme:dark) { :root { --bg:#000; --card:#1b1f27; --ink:#f3f4f6; --muted:#9ca3af; --line:#2e333d; --green:#8bd36d; } }
        * { box-sizing:border-box; } body { margin:0; min-height:100vh; display:grid; place-items:center; padding:24px; background:var(--bg); color:var(--ink); font:16px system-ui,sans-serif; }
        main { width:min(100%,440px); background:var(--card); border-radius:24px; padding:30px; box-shadow:0 12px 36px #00000012; }
        .brand { width:58px; height:58px; border-radius:50%; display:grid; place-items:center; background:var(--green); color:#fff; font-size:27px; font-weight:800; }
        h1 { margin:22px 0 8px; font-size:25px; } p { color:var(--muted); line-height:1.5; }
        label { display:block; margin:16px 0 7px; font-size:14px; font-weight:600; }
        input { width:100%; height:50px; border:1px solid var(--line); border-radius:14px; padding:0 14px; background:var(--bg); color:var(--ink); font:inherit; }
        button { width:100%; min-height:50px; margin-top:22px; border:0; border-radius:25px; background:var(--green); color:#101114; font:600 15px system-ui,sans-serif; cursor:pointer; }
        .error { color:#d43d55; font-size:13px; margin-top:6px; }
    </style>
</head>
<body>
<main>
    <div class="brand" aria-hidden="true">Q</div>
    <h1>Reset your password</h1>
    <p>Choose a new password for your BiraConsultant account.</p>
    @if ($errors->any())
        <div class="error">{{ $errors->first() }}</div>
    @endif
    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <label for="email">Email address</label>
        <input id="email" name="email" type="email" autocomplete="email" value="{{ old('email', $email) }}" required>
        <label for="password">New password</label>
        <input id="password" name="password" type="password" autocomplete="new-password" minlength="8" required>
        <label for="password_confirmation">Confirm new password</label>
        <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" minlength="8" required>
        <button type="submit">Update password</button>
    </form>
</main>
</body>
</html>
