<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="page-title">Log Notifikasi</h2>
                <p class="text-sm text-muted-foreground mt-1">{{ $competition->name }}</p>
            </div>
            <a href="{{ route('committee.command-center.show', $competition) }}" class="btn btn-secondary text-sm">
                ← Command Center
            </a>
        </div>
    </x-slot>

    <div class="py-6 space-y-6">

        {{-- ── SUMMARY CARDS ────────────────────────────────────────────── --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="card text-center">
                <div class="text-2xl font-black">{{ $summary['total'] }}</div>
                <div class="text-xs text-muted-foreground mt-1">Total Notifikasi</div>
            </div>
            <div class="card text-center">
                <div class="text-2xl font-black" style="color: var(--success)">{{ $summary['sent'] }}</div>
                <div class="text-xs text-muted-foreground mt-1">Terkirim</div>
            </div>
            <div class="card text-center">
                <div class="text-2xl font-black" style="color: var(--destructive)">{{ $summary['failed'] }}</div>
                <div class="text-xs text-muted-foreground mt-1">Gagal</div>
            </div>
            <div class="card text-center">
                <div class="text-2xl font-black">{{ count($summary['event_types']) }}</div>
                <div class="text-xs text-muted-foreground mt-1">Jenis Event</div>
            </div>
        </div>

        {{-- ── FILTERS ──────────────────────────────────────────────────── --}}
        <div class="card">
            <form method="GET" action="{{ route('committee.notification-log.index', $competition) }}"
                  class="flex flex-wrap gap-4 items-end">
                <div>
                    <label class="block text-xs font-semibold text-muted-foreground mb-1">Jenis Event</label>
                    <select name="event_type"
                            style="padding: 6px 12px; border-radius: 6px; border: 1px solid var(--border); background: var(--card); color: var(--foreground); font-size: 0.875rem;">
                        <option value="">Semua Event</option>
                        @foreach($eventTypes as $key => $label)
                        <option value="{{ $key }}" {{ request('event_type') === $key ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-muted-foreground mb-1">Status</label>
                    <select name="status"
                            style="padding: 6px 12px; border-radius: 6px; border: 1px solid var(--border); background: var(--card); color: var(--foreground); font-size: 0.875rem;">
                        <option value="">Semua Status</option>
                        <option value="sent" {{ request('status') === 'sent' ? 'selected' : '' }}>Terkirim</option>
                        <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Gagal</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary text-sm">Filter</button>
                @if(request('event_type') || request('status'))
                <a href="{{ route('committee.notification-log.index', $competition) }}" class="btn btn-secondary text-sm">Reset</a>
                @endif
            </form>
        </div>

        {{-- ── LOG TABLE ────────────────────────────────────────────────── --}}
        <div class="card" style="padding: 0; overflow: hidden;">
            @if($logs->isEmpty())
            <div class="text-center py-12 text-muted-foreground">
                <div class="text-4xl mb-3">📭</div>
                <p class="text-sm">Belum ada notifikasi yang tercatat.</p>
            </div>
            @else
            <div class="overflow-x-auto">
                <table style="width:100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: var(--muted); border-bottom: 2px solid var(--border);">
                            <th class="text-left py-3 px-4 text-xs font-semibold text-muted-foreground uppercase tracking-wider">Waktu</th>
                            <th class="text-left py-3 px-4 text-xs font-semibold text-muted-foreground uppercase tracking-wider">Event</th>
                            <th class="text-left py-3 px-4 text-xs font-semibold text-muted-foreground uppercase tracking-wider">Penerima</th>
                            <th class="text-left py-3 px-4 text-xs font-semibold text-muted-foreground uppercase tracking-wider">Subject</th>
                            <th class="text-left py-3 px-4 text-xs font-semibold text-muted-foreground uppercase tracking-wider">Status</th>
                            <th class="text-left py-3 px-4 text-xs font-semibold text-muted-foreground uppercase tracking-wider">Dikirim Oleh</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($logs as $log)
                        <tr style="border-bottom: 1px solid var(--border);" class="hover:bg-muted/50 transition-colors">
                            <td class="py-3 px-4">
                                <div class="text-sm font-medium">{{ $log->created_at->format('d M Y') }}</div>
                                <div class="text-xs text-muted-foreground">{{ $log->created_at->format('H:i:s') }}</div>
                            </td>
                            <td class="py-3 px-4">
                                <span class="text-xs px-2 py-1 rounded-full font-semibold" style="background: var(--muted); border: 1px solid var(--border);">
                                    {{ $eventTypes[$log->event_type] ?? $log->event_type }}
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <div class="text-sm">{{ $log->recipient_email ?? '—' }}</div>
                            </td>
                            <td class="py-3 px-4">
                                <div class="text-sm text-muted-foreground" style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                    {{ $log->subject ?? '—' }}
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                @if($log->status === 'sent')
                                <span class="text-xs px-2 py-1 rounded-full font-semibold"
                                      style="background: rgba(34,197,94,0.1); color: var(--success); border: 1px solid rgba(34,197,94,0.3);">
                                    ✓ Terkirim
                                </span>
                                @elseif($log->status === 'failed')
                                <span class="text-xs px-2 py-1 rounded-full font-semibold"
                                      style="background: rgba(239,68,68,0.1); color: var(--destructive); border: 1px solid rgba(239,68,68,0.3);"
                                      title="{{ $log->failure_reason }}">
                                    ✗ Gagal
                                </span>
                                @else
                                <span class="text-xs px-2 py-1 rounded-full font-semibold"
                                      style="background: var(--muted); color: var(--muted-foreground);">
                                    ⏳ Pending
                                </span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                <div class="text-sm text-muted-foreground">
                                    {{ $log->triggeredBy?->name ?? 'Sistem' }}
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($logs->hasPages())
            <div class="px-4 py-3 border-t" style="border-color: var(--border);">
                {{ $logs->appends(request()->query())->links() }}
            </div>
            @endif
            @endif
        </div>

    </div>
</x-app-layout>
