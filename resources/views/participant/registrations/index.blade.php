<x-app-layout>
    <x-slot name="header">
        <h2 class="page-title">{{ __('Pendaftaranku') }}</h2>
        <p class="page-subtitle">Daftar kompetisi yang telah kamu ikuti.</p>
    </x-slot>

    <div class="py-6">
        <div class="card overflow-hidden" style="padding: 0;">
            @if($registrations->count() > 0)
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; text-align: left;">
                        <thead>
                            <tr style="border-bottom: 1px solid var(--border); background: var(--muted);">
                                <th style="padding: 1rem 1.5rem; font-weight: 700; color: var(--foreground);">Kompetisi</th>
                                <th style="padding: 1rem 1.5rem; font-weight: 700; color: var(--foreground);">Tanggal Daftar</th>
                                <th style="padding: 1rem 1.5rem; font-weight: 700; color: var(--foreground);">Status</th>
                                <th style="padding: 1rem 1.5rem; font-weight: 700; color: var(--foreground); text-align: right;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($registrations as $reg)
                                <tr style="border-bottom: 1px solid var(--border);" onmouseover="this.style.background='var(--muted)'" onmouseout="this.style.background='transparent'">
                                    <td style="padding: 1rem 1.5rem;">
                                        <div style="font-weight: 700; color: var(--foreground);">{{ $reg->competition->name }}</div>
                                    </td>
                                    <td style="padding: 1rem 1.5rem; color: var(--muted-foreground); font-size: 0.9rem;">
                                        {{ $reg->created_at->format('d M Y, H:i') }}
                                        <div style="font-size: 0.75rem;">{{ $reg->created_at->diffForHumans() }}</div>
                                    </td>
                                    <td style="padding: 1rem 1.5rem;">
                                        <span style="padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 700; border: 1px solid var(--border); display: inline-block;
                                            {{ $reg->status === 'payment_ok' ? 'background: var(--success, #22c55e); color: #fff;' :
                                               ($reg->status === 'rejected' ? 'background: var(--danger, #ef4444); color: #fff;' : 'background: var(--accent, #f59e0b); color: #000;') }}">
                                            {{ ucfirst(str_replace('_', ' ', $reg->status)) }}
                                        </span>
                                    </td>
                                    <td style="padding: 1rem 1.5rem; text-align: right;">
                                        <a href="{{ route('participant.registrations.show', [$reg->competition, $reg]) }}"
                                           class="btn btn-sm" style="background: transparent; border: 1px solid var(--accent); color: var(--accent); font-weight: 700;"
                                           onmouseover="this.style.background='var(--accent)'; this.style.color='#000';"
                                           onmouseout="this.style.background='transparent'; this.style.color='var(--accent)';">
                                            Lihat Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div style="padding: 3rem; text-align: center; color: var(--muted-foreground);">
                    <svg style="width: 48px; height: 48px; margin: 0 auto 1rem; opacity: 0.5;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    <p style="margin-bottom: 1.5rem; font-size: 1.1rem;">Kamu belum mendaftar di kompetisi apapun.</p>
                    <a href="{{ route('participant.competitions.index') }}" class="btn btn-primary">
                        Cari Kompetisi
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
