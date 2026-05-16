<x-guest-layout>
    <div style="text-align: center; margin-bottom: 1.5rem;">
        <h2 style="font-size: 1.5rem; font-weight: 800; color: var(--foreground, #000000); margin: 0 0 0.5rem; letter-spacing: -0.02em;">Buat Akun Baru</h2>
        <p style="font-size: 0.875rem; color: var(--muted-foreground, #333333); margin: 0;">Bergabung dengan CompeteHub dan mulai perjalananmu.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" style="display: flex; flex-direction: column; gap: 1.1rem;">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" style="display: block; font-size: 0.875rem; font-weight: 600; color: var(--foreground, #000000); margin-bottom: 0.4rem;">Nama Lengkap</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                placeholder="John Doe" class="form-input"
                style="width: 100%; padding: 0.75rem 1rem; background: #ffffff; border: 1px solid var(--border, #000000); border-radius: calc(var(--radius, 1rem) - 0.25rem); font-family: inherit; font-size: 0.9rem; color: var(--foreground, #000000); box-sizing: border-box; outline: none; transition: border-color 0.2s, box-shadow 0.2s;">
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email -->
        <div>
            <label for="email" style="display: block; font-size: 0.875rem; font-weight: 600; color: var(--foreground, #000000); margin-bottom: 0.4rem;">Alamat Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                placeholder="anda@email.com" class="form-input"
                style="width: 100%; padding: 0.75rem 1rem; background: #ffffff; border: 1px solid var(--border, #000000); border-radius: calc(var(--radius, 1rem) - 0.25rem); font-family: inherit; font-size: 0.9rem; color: var(--foreground, #000000); box-sizing: border-box; outline: none; transition: border-color 0.2s, box-shadow 0.2s;">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Role -->
        <div>
            <label for="role" style="display: block; font-size: 0.875rem; font-weight: 600; color: var(--foreground, #000000); margin-bottom: 0.4rem;">Daftar Sebagai</label>
            <select id="role" name="role" class="form-input"
                style="width: 100%; padding: 0.75rem 1rem; background: #ffffff; border: 1px solid var(--border, #000000); border-radius: calc(var(--radius, 1rem) - 0.25rem); font-family: inherit; font-size: 0.9rem; color: var(--foreground, #000000); box-sizing: border-box; outline: none; cursor: pointer; transition: border-color 0.2s;">
                <option value="participant" {{ old('role') === 'participant' ? 'selected' : '' }}>Peserta (Participant)</option>
                <option value="committee" {{ old('role') === 'committee' ? 'selected' : '' }}>Panitia / Penyelenggara</option>
                <option value="judge" {{ old('role') === 'judge' ? 'selected' : '' }}>Juri</option>
            </select>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        <!-- Password -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem;">
            <div>
                <label for="password" style="display: block; font-size: 0.875rem; font-weight: 600; color: var(--foreground, #000000); margin-bottom: 0.4rem;">Password</label>
                <input id="password" type="password" name="password" required autocomplete="new-password"
                    placeholder="••••••••" class="form-input"
                    style="width: 100%; padding: 0.75rem 1rem; background: #ffffff; border: 1px solid var(--border, #000000); border-radius: calc(var(--radius, 1rem) - 0.25rem); font-family: inherit; font-size: 0.9rem; color: var(--foreground, #000000); box-sizing: border-box; outline: none; transition: border-color 0.2s, box-shadow 0.2s;">
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>
            <div>
                <label for="password_confirmation" style="display: block; font-size: 0.875rem; font-weight: 600; color: var(--foreground, #000000); margin-bottom: 0.4rem;">Konfirmasi</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                    placeholder="••••••••" class="form-input"
                    style="width: 100%; padding: 0.75rem 1rem; background: #ffffff; border: 1px solid var(--border, #000000); border-radius: calc(var(--radius, 1rem) - 0.25rem); font-family: inherit; font-size: 0.9rem; color: var(--foreground, #000000); box-sizing: border-box; outline: none; transition: border-color 0.2s, box-shadow 0.2s;">
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-full">
            Buat Akun
        </button>

        <p style="text-align: center; font-size: 0.875rem; color: var(--muted-foreground, #333333); margin: 0;">
            Sudah punya akun?
            <a href="{{ route('login') }}"
                style="font-weight: 700; color: var(--accent, #f59e0b); text-decoration: none;"
                onmouseover="this.style.opacity='0.7';" onmouseout="this.style.opacity='1';">
                Masuk
            </a>
        </p>
    </form>
</x-guest-layout>
