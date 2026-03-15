<!DOCTYPE html>
<html lang="en" class="home">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Veltrix</title>
    <link rel="icon" href="/assets/MainLogo.png" type="image/png">

    <link rel="stylesheet" href="/styles/Login,SIgn-up.css">
    <link rel="stylesheet" href="/styles/style.css">
    <link rel="stylesheet" href="/styles/footer.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/1165876da6.js" crossorigin="anonymous"></script>

    <style>
      .field-error {
        display: none;
        position: absolute;
        left: 0;
        right: 0;
        bottom: -24px;
        font-size: 16px;
        color: #ffa825;
        font-family: 'MiniPixel', sans-serif;
        text-align: center;
      }

      .field-success {
        margin-top: 10px;
        font-size: 16px;
        color: #ffa825;
        font-family: 'MiniPixel', sans-serif;
        text-align: center;
      }
    </style>
</head>

<body class="login-page auth-login-page forgot-password-page">
<header></header>

<div class="auth-page-shell">
<h1 class="login-page-title">Forgot Password</h1>
<div class="login-box">
    <form id="forgotPasswordForm" method="POST" action="{{ route('password.email') }}" novalidate autocomplete="off">
        @csrf
        <p class="field-success" style="margin-top: 0; margin-bottom: 18px; color: #ffffff;">
            Enter your email and we'll send you a temporary password.
        </p>
        <div class="input-box">
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                placeholder="Email"
                required
                autofocus
                autocomplete="off"
                autocapitalize="off"
                autocorrect="off"
                spellcheck="false"
            >
            <i class="bx bx-envelope"></i>
            <p id="forgotEmailError" class="field-error" @if($errors->has('email')) style="display:block;" @endif>
                {{ $errors->first('email') }}
            </p>
        </div>

        @if (session('status'))
            <p class="field-success">{{ session('status') }}</p>
        @endif

        <button type="submit" class="login-button">Email Temporary Password</button>

        <div class="register-link">
            <p><a href="/pages/login.html">Back to Login</a></p>
        </div>
    </form>
</div>
</div>

<div id="footer"></div>

<script src="/scripts/header.js"></script>
<script src="/scripts/footer.js"></script>
<script src="/scripts/animations.js" defer></script>
<script>
const forgotEmailInput = document.getElementById("email");
const forgotEmailError = document.getElementById("forgotEmailError");
const forgotForm = document.getElementById("forgotPasswordForm");

function showFieldError(el, message) {
    if (!el) return;
    el.textContent = message || "";
    if (message) {
        el.style.display = "block";
        el.style.animation = "none";
        void el.offsetWidth;
        el.style.animation = "fadeSlideUp 0.3s ease";
    } else {
        el.style.display = "none";
        el.style.animation = "none";
    }
}

if (forgotEmailInput) {
    forgotEmailInput.addEventListener("input", () => showFieldError(forgotEmailError, ""));
}

if (forgotForm) {
    forgotForm.addEventListener("submit", function (e) {
        const email = forgotEmailInput ? forgotEmailInput.value.trim() : "";
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        showFieldError(forgotEmailError, "");
        if (!email) {
            e.preventDefault();
            showFieldError(forgotEmailError, "Empty Field");
        } else if (!emailPattern.test(email)) {
            e.preventDefault();
            showFieldError(forgotEmailError, "Please enter a valid email format");
        }
    });
}
</script>

</body>
</html>
