<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
body {
    font-family: 'DejaVu Sans', Arial, sans-serif;
    font-size: 11px;
    color: #1a1a2e;
    background: #fff;
}

/* ── Header ── */
.header {
    background: linear-gradient(135deg, #6B4CE6, #5538D4);
    color: #fff;
    padding: 24px 28px 20px;
    margin-bottom: 0;
}
.header-top {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 12px;
}
.app-name {
    font-size: 13px;
    font-weight: bold;
    letter-spacing: 1px;
    opacity: 0.85;
    text-transform: uppercase;
}
.report-date {
    font-size: 10px;
    opacity: 0.7;
    text-align: right;
}
.couple-names {
    font-size: 22px;
    font-weight: bold;
    letter-spacing: -0.5px;
    margin-bottom: 4px;
}
.couple-names .amp { color: #FFD700; margin: 0 6px; }
.event-label {
    font-size: 10px;
    opacity: 0.75;
    letter-spacing: 1.5px;
    text-transform: uppercase;
}

/* ── Stats strip ── */
.stats-strip {
    background: #f8f9fd;
    border-bottom: 2px solid #e5e7eb;
    padding: 14px 28px;
    display: flex;
    gap: 0;
}
.stat-item {
    flex: 1;
    text-align: center;
    border-right: 1px solid #e5e7eb;
    padding: 0 12px;
}
.stat-item:last-child { border-right: none; }
.stat-num {
    font-size: 20px;
    font-weight: bold;
    color: #6B4CE6;
    display: block;
}
.stat-label {
    font-size: 9px;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-top: 2px;
    display: block;
}

/* ── Section ── */
.section {
    padding: 16px 28px 0;
}
.section-title {
    font-size: 10px;
    font-weight: bold;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 10px;
    padding-bottom: 6px;
    border-bottom: 1px solid #e5e7eb;
}

/* ── Summary cards ── */
.summary-grid {
    display: flex;
    gap: 10px;
    margin-bottom: 16px;
}
.summary-card {
    flex: 1;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 10px 12px;
    border-left-width: 3px;
}
.summary-card-num {
    font-size: 18px;
    font-weight: bold;
    display: block;
    margin-bottom: 2px;
}
.summary-card-label {
    font-size: 9px;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.summary-card-rate {
    font-size: 9px;
    color: #9ca3af;
    margin-top: 3px;
}

/* ── Table ── */
table {
    width: 100%;
    border-collapse: collapse;
    font-size: 10px;
}
thead tr {
    background: #6B4CE6;
    color: #fff;
}
thead th {
    padding: 8px 10px;
    text-align: left;
    font-weight: bold;
    font-size: 9px;
    letter-spacing: 0.5px;
    text-transform: uppercase;
}
tbody tr:nth-child(even) { background: #f8f9fd; }
tbody tr:nth-child(odd)  { background: #fff; }
tbody td {
    padding: 7px 10px;
    border-bottom: 1px solid #f3f4f6;
    vertical-align: middle;
}
.no-col { width: 32px; color: #9ca3af; text-align: center; }
.name-col { font-weight: 600; color: #1a1a2e; }
.cat-badge {
    display: inline-block;
    padding: 2px 7px;
    border-radius: 100px;
    font-size: 8px;
    font-weight: bold;
    letter-spacing: 0.3px;
}
.cat-family   { background: #ede9fe; color: #6B4CE6; }
.cat-friend   { background: #d1fae5; color: #059669; }
.cat-colleague{ background: #fff7ed; color: #d97706; }
.status-badge {
    display: inline-block;
    padding: 2px 7px;
    border-radius: 100px;
    font-size: 8px;
    font-weight: bold;
}
.status-yes { background: #d1fae5; color: #059669; }
.status-no  { background: #f3f4f6; color: #9ca3af; }
.time-text { font-size: 9px; color: #6b7280; }

/* ── Footer ── */
.footer {
    margin-top: 20px;
    padding: 12px 28px;
    border-top: 1px solid #e5e7eb;
    display: flex;
    justify-content: space-between;
    font-size: 9px;
    color: #9ca3af;
}

/* ── Page break ── */
.page-break { page-break-after: always; }
</style>
</head>
<body>

{{-- ── HEADER ── --}}
<div class="header">
    <div class="header-top">
        <span class="app-name">nikahin · Laporan Tamu</span>
        <span class="report-date">Dicetak: {{ now()->locale('id')->isoFormat('D MMMM YYYY, HH:mm') }} WIB</span>
    </div>
    <div class="couple-names">
        {{ $invitation->bride_name }}
        <span class="amp">&amp;</span>
        {{ $invitation->groom_name }}
    </div>
    <div class="event-label">Undangan Pernikahan · {{ \Carbon\Carbon::parse($invitation->reception_date)->locale('id')->isoFormat('D MMMM YYYY') }}</div>
</div>

{{-- ── STATS STRIP ── --}}
<div class="stats-strip">
    <div class="stat-item">
        <span class="stat-num">{{ $stats['total'] }}</span>
        <span class="stat-label">Total Tamu</span>
    </div>
    <div class="stat-item">
        <span class="stat-num" style="color:#059669">{{ $stats['checked_in'] }}</span>
        <span class="stat-label">Check-in</span>
    </div>
    <div class="stat-item">
        <span class="stat-num" style="color:#A855F7">{{ $stats['souvenir'] }}</span>
        <span class="stat-label">Souvenir</span>
    </div>
    <div class="stat-item">
        <span class="stat-num" style="color:#EF4444">{{ $stats['not_checked_in'] }}</span>
        <span class="stat-label">Belum Hadir</span>
    </div>
    <div class="stat-item">
        <span class="stat-num">{{ $stats['check_in_rate'] }}%</span>
        <span class="stat-label">Kehadiran</span>
    </div>
</div>

{{-- ── SUMMARY BY CATEGORY ── --}}
<div class="section" style="margin-top:16px">
    <div class="section-title">Ringkasan per Kategori</div>
    <div class="summary-grid">
        @foreach(['family' => ['Keluarga','#6B4CE6'], 'friend' => ['Teman','#059669'], 'colleague' => ['Rekan','#d97706']] as $cat => [$label, $color])
        @php
            $catTotal     = $guests->where('category', $cat)->count();
            $catCheckedIn = $guests->where('category', $cat)->whereNotNull('checked_in_at')->count();
            $catRate      = $catTotal > 0 ? round(($catCheckedIn / $catTotal) * 100) : 0;
        @endphp
        <div class="summary-card" style="border-left-color:{{ $color }}">
            <span class="summary-card-num" style="color:{{ $color }}">{{ $catTotal }}</span>
            <span class="summary-card-label">{{ $label }}</span>
            <div class="summary-card-rate">{{ $catCheckedIn }} hadir ({{ $catRate }}%)</div>
        </div>
        @endforeach
    </div>
</div>

{{-- ── GUEST TABLE ── --}}
<div class="section">
    <div class="section-title">Daftar Tamu Lengkap ({{ $guests->count() }} orang)</div>
    <table>
        <thead>
            <tr>
                <th class="no-col">#</th>
                <th>Nama</th>
                <th>Kategori</th>
                <th>WhatsApp</th>
                <th>Check-in</th>
                <th>Souvenir</th>
            </tr>
        </thead>
        <tbody>
            @foreach($guests as $i => $guest)
            <tr>
                <td class="no-col">{{ $i + 1 }}</td>
                <td class="name-col">{{ $guest->name }}</td>
                <td>
                    @php $catClass = 'cat-' . $guest->category; @endphp
                    <span class="cat-badge {{ $catClass }}">
                        {{ ['family'=>'Keluarga','friend'=>'Teman','colleague'=>'Rekan'][$guest->category] ?? $guest->category }}
                    </span>
                </td>
                <td class="time-text">{{ $guest->whatsapp_number ?: '—' }}</td>
                <td>
                    @if($guest->checked_in_at)
                        <span class="status-badge status-yes">✓ Hadir</span>
                        <div class="time-text">{{ $guest->checked_in_at->format('H:i') }}</div>
                    @else
                        <span class="status-badge status-no">Belum</span>
                    @endif
                </td>
                <td>
                    @if($guest->souvenir_taken_at)
                        <span class="status-badge" style="background:#f3e8ff;color:#A855F7">✓ Diambil</span>
                        <div class="time-text">{{ $guest->souvenir_taken_at->format('H:i') }}</div>
                    @else
                        <span class="status-badge status-no">Belum</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{-- ── RSVP SECTION ── --}}
@if($rsvps->count() > 0)
<div class="section" style="margin-top:20px">
    <div class="section-title">Ucapan & Doa ({{ $rsvps->count() }})</div>
    <table>
        <thead>
            <tr>
                <th class="no-col">#</th>
                <th>Nama</th>
                <th>Ucapan</th>
                <th style="width:80px">Waktu</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rsvps as $i => $rsvp)
            <tr>
                <td class="no-col">{{ $i + 1 }}</td>
                <td class="name-col">{{ $rsvp->name }}</td>
                <td style="font-style:italic;color:#374151">{{ $rsvp->message }}</td>
                <td class="time-text">{{ $rsvp->created_at->locale('id')->isoFormat('D MMM, HH:mm') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

{{-- ── FOOTER ── --}}
<div class="footer">
    <span>nikahin — Undangan Digital</span>
    <span>{{ $invitation->bride_name }} &amp; {{ $invitation->groom_name }} · {{ \Carbon\Carbon::parse($invitation->reception_date)->locale('id')->isoFormat('D MMMM YYYY') }}</span>
</div>

</body>
</html>
