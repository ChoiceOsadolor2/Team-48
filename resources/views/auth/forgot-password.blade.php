<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Forgot Password</title>

    <link rel="stylesheet" href="/styles/Login,SIgn-up.css">
    <link rel="stylesheet" href="/styles/style.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    <style>
      .field-error {
        display: none;
        margin-top: 8px;
        font-size: 14px;
        color: #ff4d4d;
      }

      .field-success {
        margin-top: 8px;
        font-size: 14px;
        color: #ffa825;
        text-align: center;
      }

      .input-error {
        border-color: rgba(255, 77, 77, 0.9) !important;
        box-shadow: 0 0 0 2px rgba(255, 77, 77, 0.15);
      }

      /* Keep /forgot-password navbar metrics identical to login/register */
      body.forgot-page nav > a {
        white-space: nowrap;
      }

      body.forgot-page #icons {
        align-items: center;
      }

      body.forgot-page #icons > svg,
      body.forgot-page #icons > span,
      body.forgot-page #icons > .user-menu {
        display: inline-flex;
        align-items: center;
        line-height: 1;
      }

      body.forgot-page .basket_count {
        line-height: 1;
      }

      body.forgot-page #theme-toggle-button + label {
        line-height: 1;
      }
    </style>
</head>

<body class="login-page forgot-page">
<header></header>

<div class="login-box">
    <form method="POST" action="{{ route('password.email') }}" novalidate>
        @csrf
        <h1>Forgot Password</h1>

        <div class="input-box">
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                placeholder="Email"
                required
                autofocus
                class="@error('email') input-error @enderror"
            >
            <i class="bx bx-envelope"></i>
        </div>

        @error('email')
            <p class="field-error" style="display:block;">{{ $message }}</p>
        @enderror

        @if (session('status'))
            <p class="field-success">{{ session('status') }}</p>
        @endif

        <button type="submit" class="login-button">Email Password Reset Link</button>

        <div class="register-link">
            <p><a href="/pages/login.html">Back to Login</a></p>
        </div>
    </form>
</div>

<script src="/scripts/header.js"></script>
<script src="/scripts/animations.js" defer></script>

</body>
</html>
