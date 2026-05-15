<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
             {{ __('Leaderboard') }} — {{ $competition->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            {{-- Live indicator --}}
            <div class="mb-4 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="relative flex h-3 w-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                    </span>
                    <span class="text-sm text-gray-500" id="lastUpdated">Live — updating every 5s</span>
                </div>
                <select id="roundFilter" class="rounded-md border-gray-300 text-sm shadow-sm focus:border-gray-300 focus:ring focus:ring-gray-200">
                    <option value="">Global (All Rounds)</option>
                    @foreach($rounds as $round)
                        <option value="{{ $round->id }}">{{ $round->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Podium --}}
            <div class="mb-8">
                <div class="flex justify-center items-end gap-4" id="podiumContainer"></div>
            </div>

            {{-- Leaderboard Table --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-800 text-white text-xs uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-4 w-16 text-center">Rank</th>
                            <th class="px-6 py-4">Peserta</th>
                            <th class="px-6 py-4 text-center">Judge Score</th>
                            <th class="px-6 py-4 text-center">Time Bonus</th>
                            <th class="px-6 py-4 text-center">Total</th>
                        </tr>
                    </thead>
                    <tbody id="leaderboardBody"></tbody>
                </table>
                <div id="emptyState" class="hidden p-12 text-center text-gray-400">
                    <p class="text-lg font-semibold">Belum ada data</p>
                    <p class="text-sm">Leaderboard akan terisi setelah submisi dinilai.</p>
                </div>
            </div>

            {{-- Legend --}}
            <div class="mt-4 bg-white rounded-lg shadow-sm p-4 text-xs text-gray-500">
                <p><strong>Judge Score:</strong> Nilai rata-rata dari semua juri (max 100).</p>
                <p><strong>Time Bonus:</strong> Bonus waktu otomatis — 1/3 tercepat dari total pendaftar mendapat bonus 5→1 pts. Revisi tidak merubah bonus waktu yang sudah didapat.</p>
                <p><strong>Total:</strong> Judge Score + Time Bonus (max 105).</p>
            </div>
        </div>
    </div>

    <style>
        .score-flash { animation: flash 1s ease-out; }
        @keyframes flash { 0%,100% { background-color: transparent; } 50% { background-color: #fef3c7; } }
        .podium-card { transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1); }
        .podium-card:hover { transform: translateY(-4px); }
    </style>

    <script>
        const API_URL = "{{ route('leaderboard.api', $competition) }}";
        let previousData = {};
        let pollTimer = null;

        const rc = {
            1: { text: 'text-yellow-700', badge: 'bg-yellow-400', emoji: '🥇', pH: 'h-40', pBg: 'bg-yellow-400', bg: 'bg-yellow-50/50' },
            2: { text: 'text-gray-700', badge: 'bg-gray-400', emoji: '🥈', pH: 'h-32', pBg: 'bg-gray-300', bg: 'bg-gray-50/50' },
            3: { text: 'text-orange-700', badge: 'bg-orange-400', emoji: '🥉', pH: 'h-24', pBg: 'bg-orange-300', bg: 'bg-orange-50/50' },
        };

        function badge(rank) {
            if (rank <= 3) return `<span class="inline-flex items-center justify-center w-8 h-8 rounded-full ${rc[rank].badge} text-white font-bold text-sm shadow">${rank}</span>`;
            return `<span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-200 text-gray-600 font-semibold text-sm">${rank}</span>`;
        }

        function renderPodium(entries) {
            const c = document.getElementById('podiumContainer');
            const top3 = entries.slice(0, 3);
            if (!top3.length) { c.innerHTML = ''; return; }
            const order = [];
            if (top3[1]) order.push(top3[1]);
            if (top3[0]) order.push(top3[0]);
            if (top3[2]) order.push(top3[2]);
            c.innerHTML = order.map(e => {
                const r = rc[e.rank] || rc[3];
                return `<div class="podium-card flex flex-col items-center">
                    <div class="text-3xl mb-2">${r.emoji}</div>
                    <div class="w-12 h-12 rounded-full bg-gray-200 border border-gray-300 flex items-center justify-center text-gray-700 font-bold text-lg mb-2 shadow-sm">${e.name.charAt(0).toUpperCase()}</div>
                    <p class="font-bold text-gray-800 text-sm text-center max-w-[120px] truncate">${e.name}</p>
                    <p class="text-xs text-gray-500">${e.judge_score.toFixed(1)} + ${e.time_bonus.toFixed(1)}</p>
                    <p class="text-lg font-extrabold ${r.text}">${e.total_score.toFixed(2)}</p>
                    <div class="${r.pH} w-24 ${r.pBg} rounded-t-sm mt-2 flex items-center justify-center border border-b-0 border-black/5">
                        <span class="text-white font-bold text-2xl">#${e.rank}</span>
                    </div>
                </div>`;
            }).join('');
        }

        function renderTable(entries) {
            const tbody = document.getElementById('leaderboardBody');
            const empty = document.getElementById('emptyState');
            if (!entries.length) { tbody.innerHTML = ''; empty.classList.remove('hidden'); return; }
            empty.classList.add('hidden');
            tbody.innerHTML = entries.map(e => {
                const prev = previousData[e.name];
                let anim = '';
                if (prev && prev.total_score !== e.total_score) anim = 'score-flash';
                const bg = e.rank <= 3 ? (rc[e.rank]?.bg || '') : (e.rank % 2 === 0 ? 'bg-gray-50' : '');
                
                let rankChangeIndicator = '';
                if (e.previous_rank) {
                    if (e.previous_rank > e.rank) {
                        const diff = e.previous_rank - e.rank;
                        rankChangeIndicator = `<span class="text-xs text-green-500 font-bold flex flex-col items-center leading-none" title="Naik ${diff} posisi"><span class="text-[10px]">▲</span><span>${diff}</span></span>`;
                    } else if (e.previous_rank < e.rank) {
                        const diff = e.rank - e.previous_rank;
                        rankChangeIndicator = `<span class="text-xs text-red-500 font-bold flex flex-col items-center leading-none" title="Turun ${diff} posisi"><span class="text-[10px]">▼</span><span>${diff}</span></span>`;
                    } else {
                        rankChangeIndicator = `<span class="text-xs text-gray-400 font-bold ml-1" title="Posisi tetap">-</span>`;
                    }
                }

                return `<tr class="${bg} ${anim} border-b border-gray-100 hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            ${badge(e.rank)}
                            ${rankChangeIndicator}
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-gray-100 border border-gray-200 flex items-center justify-center text-gray-600 font-bold text-sm">${e.name.charAt(0).toUpperCase()}</div>
                            <div><p class="font-semibold text-gray-800">${e.name}</p><p class="text-xs text-gray-500">${e.type === 'team' ? 'Tim' : 'Individu'}</p></div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center font-semibold text-gray-800">${e.judge_score.toFixed(1)}<span class="text-xs text-gray-400">/100</span></td>
                    <td class="px-6 py-4 text-center"><span class="px-2 py-0.5 rounded text-xs font-semibold ${e.time_bonus > 0 ? 'bg-green-50 border border-green-200 text-green-700' : 'bg-gray-50 border border-gray-200 text-gray-500'}">+${e.time_bonus.toFixed(1)}</span></td>
                    <td class="px-6 py-4 text-center text-lg font-bold ${e.rank <= 3 ? (rc[e.rank]?.text || 'text-gray-900') : 'text-gray-900'}">${e.total_score.toFixed(2)}</td>
                </tr>`;
            }).join('');
            previousData = {};
            entries.forEach(e => { previousData[e.name] = { rank: e.rank, total_score: e.total_score }; });
        }

        async function fetchLeaderboard() {
            try {
                const roundId = document.getElementById('roundFilter').value;
                const url = roundId ? `${API_URL}?round_id=${roundId}` : API_URL;
                const res = await fetch(url, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } });
                if (!res.ok) throw new Error();
                const data = await res.json();
                renderPodium(data.entries);
                renderTable(data.entries);
                document.getElementById('lastUpdated').textContent = `Live — updated ${new Date().toLocaleTimeString()}`;
            } catch (e) {
                document.getElementById('lastUpdated').textContent = '⚠️ Connection error — retrying...';
            }
        }

        fetchLeaderboard();
        pollTimer = setInterval(fetchLeaderboard, 5000);
        document.getElementById('roundFilter').addEventListener('change', () => { previousData = {}; fetchLeaderboard(); });
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) clearInterval(pollTimer);
            else { fetchLeaderboard(); pollTimer = setInterval(fetchLeaderboard, 5000); }
        });
    </script>
</x-app-layout>
