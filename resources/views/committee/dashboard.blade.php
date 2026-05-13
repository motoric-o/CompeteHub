@extends('layouts.app')

@section('title', 'Dashboard Panitia — CompeteHub')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Dashboard Panitia</h1>
        <p class="page-subtitle">Selamat datang kembali, {{ auth()->user()->name }}!</p>
    </div>
</div>

<div class="section animate-in">
    <div class="card" style="margin-bottom: 2rem; border-color: var(--color-primary-light); background: linear-gradient(to right, #f8fffb, #ffffff);">
        <div style="display: flex; align-items: center; gap: 1.5rem;">
            <div class="nav-avatar" style="width: 64px; height: 64px; font-size: 1.5rem;">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            <div>
                <h2 style="margin: 0; font-size: 1.25rem;">{{ auth()->user()->name }}</h2>
                <p class="text-muted" style="margin: 0.25rem 0 0;">Anda memiliki akses penuh untuk mengelola kompetisi dan verifikasi peserta.</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-3">
        <!-- Manage Competitions -->
        <a href="{{ route('committee.management.competitions.index') }}" class="card dashboard-card">
            <div class="icon-box" style="background: var(--color-primary-light); color: var(--color-primary-dark);">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            </div>
            <h3 class="card-title">Kelola Kompetisi</h3>
            <p class="text-muted" style="font-size: 0.85rem;">Buat, edit, dan atur seluruh parameter kompetisi Anda.</p>
        </a>

        <!-- Registration Forms -->
        <a href="{{ route('committee.management.competitions.index') }}" class="card dashboard-card">
            <div class="icon-box" style="background: #eef2ff; color: #4f46e5;">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <h3 class="card-title">Formulir Pendaftaran</h3>
            <p class="text-muted" style="font-size: 0.85rem;">Desain formulir pendaftaran dinamis dengan form builder.</p>
        </a>

        <!-- Verify Registrations -->
        <a href="{{ route('committee.management.competitions.index') }}" class="card dashboard-card">
            <div class="icon-box" style="background: #fffbeb; color: #d97706;">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <h3 class="card-title">Verifikasi Peserta</h3>
            <p class="text-muted" style="font-size: 0.85rem;">Tinjau dokumen, verifikasi pembayaran, dan setujui peserta.</p>
        </a>
    </div>
</div>

<style>
    .dashboard-card {
        display: block;
        text-decoration: none;
        color: inherit;
    }
    .dashboard-card:hover {
        transform: translateY(-5px);
        border-color: var(--color-primary);
        box-shadow: var(--shadow-lg);
    }
    .icon-box {
        width: 48px;
        height: 48px;
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.25rem;
        transition: transform var(--transition);
    }
    .dashboard-card:hover .icon-box {
        transform: scale(1.1);
    }
</style>
@endsection

