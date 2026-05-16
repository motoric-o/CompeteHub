<x-app-layout>
    @section('title', 'Dashboard Juri — CompeteHub')
    <x-slot name="header">
        <h2 style="font-size: 1.5rem; font-weight: 800; color: var(--foreground, #000000); margin: 0; letter-spacing: -0.02em;">
            Dashboard Juri
        </h2>
    </x-slot>

    <div style="padding: 2rem 0;">
        <!-- Welcome card -->
        <div style="background: var(--card, #ffffff); border: 1px solid var(--border, #000000); border-radius: var(--radius, 1rem); padding: 1.75rem 2rem; margin-bottom: 2rem; display: flex; align-items: center; gap: 1.5rem;">
            <div style="width: 64px; height: 64px; border-radius: 50%; background: var(--secondary, #14b8a6); color: var(--secondary-foreground, #ffffff); display: flex; align-items: center; justify-content: center; font-size: 1.75rem; font-weight: 800; flex-shrink: 0;">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div>
                <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--foreground, #000000); margin: 0 0 0.25rem;">Selamat datang, {{ auth()->user()->name }}!</h3>
                <p style="font-size: 0.9rem; color: var(--muted-foreground, #333333); margin: 0;">Kamu login sebagai <strong>Juri</strong>. Mulai proses penilaian di bawah.</p>
            </div>
        </div>

        <!-- Action cards -->
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.25rem;">
            <a href="{{ route('judge.submissions.index') }}"
                style="display: block; background: var(--card, #ffffff); border: 1px solid var(--border, #000000); border-radius: var(--radius, 1rem); padding: 1.5rem; text-decoration: none; transition: transform 0.2s, box-shadow 0.2s;"
                onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 24px rgba(0,0,0,0.05)';"
                onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                <h4 style="font-size: 1.1rem; font-weight: 700; color: var(--foreground, #000000); margin: 0 0 0.5rem;">Daftar Tugas Penilaian</h4>
                <p style="font-size: 0.875rem; color: var(--muted-foreground, #333333); margin: 0;">Lihat kompetisi dan round yang ditugaskan kepadamu untuk dinilai.</p>
            </a>

            <a href="{{ route('leaderboards.list') }}"
                style="display: block; background: var(--card, #ffffff); border: 1px solid var(--border, #000000); border-radius: var(--radius, 1rem); padding: 1.5rem; text-decoration: none; transition: transform 0.2s, box-shadow 0.2s;"
                onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 24px rgba(0,0,0,0.05)';"
                onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                <h4 style="font-size: 1.1rem; font-weight: 700; color: var(--foreground, #000000); margin: 0 0 0.5rem;">Leaderboard</h4>
                <p style="font-size: 0.875rem; color: var(--muted-foreground, #333333); margin: 0;">Pantau hasil penilaian dan peringkat akhir kompetisi.</p>
            </a>
        </div>
    </div>
</x-app-layout>
