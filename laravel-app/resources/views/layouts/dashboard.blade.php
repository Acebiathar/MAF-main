@extends('layouts.app')

@section('fullwidth')
@php
  $dashboardSearchPlaceholder = trim($__env->yieldContent('dashboard_search_placeholder')) ?: 'Search medicines';
  $notificationBadge = trim($__env->yieldContent('dashboard_notification_badge')) ?: '0';
  $profileName = $currentUser->name ?? 'Account';
  $profileRole = isset($currentUser->role) ? ucfirst($currentUser->role) : 'User';
  $initials = collect(explode(' ', trim($profileName)))->filter()->map(fn ($part) => strtoupper(substr($part, 0, 1)))->take(2)->implode('');
@endphp

<style>
  .dashboard-shell {
    min-height: 100vh;
    background:
      radial-gradient(circle at top left, rgba(13, 110, 253, 0.16), transparent 35%),
      linear-gradient(180deg, #f6f9ff 0%, #eef3fb 100%);
    display: flex;
    margin: 0;
    padding: 0;
  }

  .dashboard-frame {
    display: grid;
    grid-template-columns: 280px minmax(0, 1fr);
    min-height: 100vh;
    width: 100%;
    margin: 0;
    padding: 0;
  }

  .dashboard-sidebar {
    background: linear-gradient(180deg, #10233c 0%, #17355c 100%);
    color: #e9f2ff;
    padding: 2rem 1.25rem;
    position: sticky;
    top: 0;
    height: 100vh;
    margin: 0;
    border-right: 1px solid rgba(255, 255, 255, 0.08);
    overflow-y: auto;
  }

  .dashboard-brand {
    display: flex;
    align-items: center;
    gap: 0.9rem;
    margin-bottom: 2rem;
  }

  .dashboard-brand-mark {
    width: 52px;
    height: 52px;
    border-radius: 18px;
    display: grid;
    place-items: center;
    background: linear-gradient(135deg, #4fb3ff 0%, #9bd0ff 100%);
    color: #10233c;
    font-size: 1.35rem;
  }

  .dashboard-brand-copy small,
  .dashboard-sidebar-note,
  .dashboard-nav .nav-link small {
    color: rgba(233, 242, 255, 0.7);
  }

  .dashboard-nav {
    gap: 0.55rem;
  }

  .dashboard-nav .nav-link {
    border-radius: 18px;
    padding: 0.95rem 1rem;
    color: #e9f2ff;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid transparent;
    transition: all 0.2s ease;
  }

  .dashboard-nav .nav-link:hover,
  .dashboard-nav .nav-link.active {
    background: rgba(255, 255, 255, 0.12);
    color: #fff;
    border-color: rgba(255, 255, 255, 0.12);
    transform: translateX(4px);
  }

  .dashboard-nav-main {
    display: flex;
    align-items: center;
    gap: 0.85rem;
  }

  .dashboard-content {
    padding: 1.5rem 0;
    margin: 0;
    display: flex;
    flex-direction: column;
  }

  .dashboard-content > .row {
    width: calc(100vw - 280px);
    margin-left: -1.5rem;
  }

  .dashboard-content > .row [class*='col-'] {
    padding-left: 1.5rem;
    padding-right: 1.5rem;
  }

  .dashboard-topbar,
  .dashboard-panel,
  .dashboard-stat,
  .dashboard-action-card,
  .dashboard-table-card,
  .dashboard-notice {
    background: rgba(255, 255, 255, 0.84);
    border: 1px solid rgba(16, 35, 60, 0.08);
    backdrop-filter: blur(10px);
    box-shadow: 0 14px 34px rgba(19, 44, 76, 0.08);
  }

  .dashboard-topbar,
  .dashboard-panel,
  .dashboard-stat,
  .dashboard-action-card,
  .dashboard-table-card,
  .dashboard-notice {
    border-radius: 24px;
  }

  .dashboard-topbar {
    padding: 1rem 1.5rem;
    margin-bottom: 1.5rem;
    margin-left: 0;
    margin-right: 0;
  }

  .dashboard-search {
    position: relative;
  }

  .dashboard-search .bi-search {
    position: absolute;
    top: 50%;
    left: 1rem;
    transform: translateY(-50%);
    color: #6c7c93;
  }

  .dashboard-search input {
    border-radius: 999px;
    padding-left: 2.7rem;
    border: 1px solid rgba(16, 35, 60, 0.08);
    background: #f7faff;
  }

  .dashboard-bell {
    width: 48px;
    height: 48px;
    border-radius: 18px;
    border: 0;
    background: #f2f7ff;
    position: relative;
  }

  .dashboard-bell-badge {
    position: absolute;
    top: -6px;
    right: -4px;
    min-width: 22px;
    height: 22px;
    border-radius: 999px;
    background: #dc3545;
    color: #fff;
    font-size: 0.72rem;
    display: grid;
    place-items: center;
    padding: 0 0.35rem;
  }

  .dashboard-profile {
    background: #f7faff;
    border-radius: 20px;
    padding: 0.5rem 0.75rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
  }

  .dashboard-avatar {
    width: 44px;
    height: 44px;
    border-radius: 16px;
    background: linear-gradient(135deg, #0d6efd 0%, #66b2ff 100%);
    color: #fff;
    display: grid;
    place-items: center;
    font-weight: 700;
  }

  .dashboard-hero {
    padding: 1.75rem 1.5rem;
    background: linear-gradient(135deg, #123f77 0%, #0d6efd 55%, #7fc8ff 100%);
    color: #fff;
    margin-bottom: 1.5rem;
    margin-left: 0;
    margin-right: 0;
    overflow: hidden;
    position: relative;
  }

  .dashboard-hero::after {
    content: '';
    position: absolute;
    inset: auto -60px -60px auto;
    width: 220px;
    height: 220px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.1);
  }

  .dashboard-hero p {
    max-width: 720px;
    color: rgba(255, 255, 255, 0.82);
  }

  .dashboard-stat,
  .dashboard-action-card,
  .dashboard-notice,
  .dashboard-panel {
    padding: 1.25rem;
  }

  .dashboard-stat-icon,
  .dashboard-action-icon {
    width: 52px;
    height: 52px;
    border-radius: 18px;
    display: grid;
    place-items: center;
    font-size: 1.15rem;
    background: rgba(13, 110, 253, 0.12);
    color: #0d6efd;
  }

  .dashboard-stat-value {
    font-size: 1.9rem;
    font-weight: 700;
    color: #10233c;
  }

  .dashboard-action-card {
    height: 100%;
  }

  .dashboard-action-card a,
  .dashboard-action-card button {
    border-radius: 999px;
  }

  .dashboard-table-card .table {
    margin-bottom: 0;
  }

  .dashboard-table-card .table thead th {
    border-top: 0;
    white-space: nowrap;
  }

  .dashboard-notice-list {
    display: grid;
    gap: 1rem;
  }

  .dashboard-notice-item {
    border-radius: 18px;
    background: #f7faff;
    padding: 1rem;
    border: 1px solid rgba(16, 35, 60, 0.06);
  }

  .dashboard-notice-item h6 {
    color: #10233c;
  }

  .dashboard-notice-item p {
    color: #607089;
    margin-bottom: 0;
  }

  @media (max-width: 1199.98px) {
    .dashboard-frame {
      grid-template-columns: 1fr;
    }

    .dashboard-sidebar {
      position: static;
      height: auto;
    }
  }

  @media (max-width: 767.98px) {
    .dashboard-content {
      padding: 1rem 0;
    }

    .dashboard-content > .row {
      width: 100vw;
      margin-left: -1rem;
    }

    .dashboard-content > .row [class*='col-'] {
      padding-left: 1rem;
      padding-right: 1rem;
    }

    .dashboard-topbar {
      margin-left: 0;
      margin-right: 0;
      padding-left: 1rem;
      padding-right: 1rem;
    }

    .dashboard-hero {
      margin-left: 0;
      margin-right: 0;
      padding-left: 1rem;
      padding-right: 1rem;
    }

    .dashboard-hero,
    .dashboard-topbar,
    .dashboard-stat,
    .dashboard-action-card,
    .dashboard-table-card,
    .dashboard-notice,
    .dashboard-panel {
      border-radius: 20px;
    }
  }
</style>

<div class="dashboard-shell">
  <div class="dashboard-frame">
    <aside class="dashboard-sidebar">
      <div class="dashboard-brand">
        <div class="dashboard-brand-mark">
          <i class="bi bi-grid-1x2-fill"></i>
        </div>
        <div class="dashboard-brand-copy">
          <div class="fw-bold">Medfinder Ug</div>
        </div>
      </div>

      <div class="dashboard-nav nav flex-column mb-4">
        @yield('dashboard_sidebar')
      </div>
 </aside>

    <div class="dashboard-content">
      <div class="dashboard-topbar d-flex flex-column flex-lg-row align-items-lg-center gap-3 justify-content-between">
        <form action="/" method="GET" class="dashboard-search flex-grow-1">
          <i class="bi bi-search"></i>
          <input
            type="text"
            name="item_names[]"
            class="form-control form-control-lg"
            placeholder="{{ $dashboardSearchPlaceholder }}">
        </form>

        <div class="d-flex align-items-center gap-3">
          <button class="dashboard-bell" type="button" aria-label="Notifications">
            <i class="bi bi-bell-fill"></i>
            <span class="dashboard-bell-badge">{{ $notificationBadge }}</span>
          </button>

          <div class="dashboard-profile">
            <div class="dashboard-avatar">{{ $initials ?: 'MF' }}</div>
            <div>
              <div class="fw-semibold text-dark">{{ $profileName }}</div>
              <div class="small text-muted">{{ $profileRole }}</div>
            </div>
          </div>
        </div>
      </div>

      <div class="dashboard-panel dashboard-hero">
        <div class="d-flex flex-column flex-xl-row gap-3 justify-content-between align-items-xl-center">
          <div>
            <div class="text-uppercase small fw-semibold opacity-75 mb-2">Welcome Section</div>
            <h1 class="h2 fw-bold mb-2">@yield('dashboard_title')</h1>
            <p class="mb-0">@yield('dashboard_subtitle')</p>
          </div>
          @hasSection('dashboard_welcome_meta')
            <div class="text-xl-end">
              @yield('dashboard_welcome_meta')
            </div>
          @endif
        </div>
      </div>

      <div class="row g-3 mb-4">
        @yield('dashboard_stats')
      </div>

      <div class="row g-3 mb-4">
        @yield('dashboard_actions')
      </div>

      <div class="row g-4">
        <div class="col-12 col-xl-8">
          @yield('dashboard_main')
        </div>
        <div class="col-12 col-xl-4">
          @yield('dashboard_notifications')
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
