<x-guest-layout>
    <div style="text-align: center; margin-bottom: 2rem;">
        <h2 style="font-size: 1.5rem; font-weight: 800; color: var(--foreground, #000000); margin: 0 0 0.5rem; letter-spacing: -0.02em;">Masuk ke Akun Anda</h2>
        <p style="font-size: 0.875rem; color: var(--muted-foreground, #333333); margin: 0;">Selamat datang kembali! Masukkan detail Anda.</p>
    </div>

    <x-auth-session-status style="margin-bottom: 1rem;" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" style="display: flex; flex-direction: column; gap: 1.25rem;">
        @csrf

        <div>
            <label for="email" style="display: block; font-size: 0.875rem; font-weight: 600; color: var(--foreground, #000000); margin-bottom: 0.5rem;">Alamat Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                placeholder="anda@email.com" class="form-input"
                style="width: 100%; padding: 0.75rem 1rem; background: var(--background, #f7f9f3); border: 1px solid var(--border, #000000); border-radius: calc(var(--radius, 1rem) - 0.25rem); font-family: inherit; font-size: 0.9rem; color: var(--foreground, #000000); box-sizing: border-box; outline: none; transition: border-color 0.2s, box-shadow 0.2s;">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <label for="password" style="display: block; font-size: 0.875rem; font-weight: 600; color: var(--foreground, #000000); margin-bottom: 0.5rem;">Password</label>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                placeholder="••••••••" class="form-input"
                style="width: 100%; padding: 0.75rem 1rem; background: var(--background, #f7f9f3); border: 1px solid var(--border, #000000); border-radius: calc(var(--radius, 1rem) - 0.25rem); font-family: inherit; font-size: 0.9rem; color: var(--foreground, #000000); box-sizing: border-box; outline: none; transition: border-color 0.2s, box-shadow 0.2s;">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div style="display: flex; align-items: center; justify-content: space-between;">
            <label for="remember_me" style="display: inline-flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                <input id="remember_me" type="checkbox" name="remember"
                    style="width: 16px; height: 16px; accent-color: var(--primary, #4f46e5); cursor: pointer;">
                <span style="font-size: 0.875rem; color: var(--muted-foreground, #333333);">Ingat saya</span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}"
                    style="font-size: 0.875rem; font-weight: 600; color: var(--primary, #4f46e5); text-decoration: none; transition: opacity 0.2s;"
                    onmouseover="this.style.opacity='0.7';" onmouseout="this.style.opacity='1';">
                    Lupa password?
                </a>
            @endif
        </div>

        <button type="submit" class="btn btn-primary w-full">
            Masuk
        </button>

        <p style="text-align: center; font-size: 0.875rem; color: var(--muted-foreground, #333333); margin: 0;">
            Belum punya akun?
            <a href="{{ route('register') }}"
                style="font-weight: 700; color: var(--primary, #4f46e5); text-decoration: none;"
                onmouseover="this.style.opacity='0.7';" onmouseout="this.style.opacity='1';">
                Daftar di sini
            </a>
        </p>
    </form>
</x-guest-layout>