<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <style>
        .login-field {
            margin-bottom: 1.1rem;
        }

        .login-field label {
            display: block;
            font-size: 12px;
            font-weight: 500;
            color: #6b7a99;
            margin-bottom: 6px;
            letter-spacing: 0.3px;
        }

        .login-field input {
            width: 100%;
            background: #151c2c;
            border: 1px solid #2a3450;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 14px;
            color: #e8edf5;
            outline: none;
            transition: border-color 0.2s;
            font-family: 'Figtree', sans-serif;
            box-sizing: border-box;
        }

        .login-field input:focus {
            border-color: #6366f1;
        }

        .login-field input::placeholder {
            color: #3a4460;
        }

        .login-field .text-red-600 {
            color: #f87171;
            font-size: 12px;
            margin-top: 4px;
        }

        .login-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 1rem 0 1.5rem;
        }

        .login-remember {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: #6b7a99;
            cursor: pointer;
        }

        .login-remember input[type="checkbox"] {
            accent-color: #6366f1;
            width: 14px;
            height: 14px;
        }

        .login-forgot {
            font-size: 13px;
            color: #6366f1;
            text-decoration: none;
        }

        .login-forgot:hover {
            color: #818cf8;
        }

        .login-btn {
            width: 100%;
            padding: 11px;
            background: linear-gradient(90deg, #6366f1, #8b5cf6);
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            color: #ffffff;
            cursor: pointer;
            font-family: 'Figtree', sans-serif;
            letter-spacing: 0.2px;
            transition: opacity 0.2s;
        }

        .login-btn:hover {
            opacity: 0.9;
        }
    </style>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email -->
        <div class="login-field">
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                   placeholder="you@example.com" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="login-field">
            <label for="password">Password</label>
            <input id="password" type="password" name="password"
                   placeholder="••••••••" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember me + Forgot password -->
        <div class="login-row">
            <label class="login-remember">
                <input type="checkbox" name="remember" id="remember_me" />
                Remember me
            </label>

            @if (Route::has('password.request'))
                <a class="login-forgot" href="{{ route('password.request') }}">
                    Forgot password?
                </a>
            @endif
        </div>

        <button type="submit" class="login-btn">Log in</button>
    </form>
</x-guest-layout>