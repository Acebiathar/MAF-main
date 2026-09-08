@extends('layouts.app')

@section('fullwidth')
@php
  $dashboardSearchPlaceholder = trim($__env->yieldContent('dashboard_search_placeholder')) ?: 'Search medicines';
  $notificationBadge = trim($__env->yieldContent('dashboard_notification_badge')) ?: '0';
  $profileName = $currentUser->name ?? 'Account';
  $profileRole = isset($currentUser->role) ? ucfirst($currentUser->role) : 'User';
  $isPharmacyDashboard = request()->is('pharmacist', 'pharmacist/*');
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
    display: flex;
    flex-direction: column;
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

  .dashboard-sidebar-footer {
    margin-top: auto;
    padding-top: 1rem;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
  }

  .dashboard-sidebar-logout {
    align-items: center;
    border-radius: 14px;
    color: rgba(233, 242, 255, 0.86);
    display: flex;
    gap: 0.75rem;
    padding: 0.8rem 1rem;
    text-decoration: none;
    transition: background 0.2s ease, color 0.2s ease;
  }

  .dashboard-sidebar-logout:hover {
    background: rgba(220, 53, 69, 0.16);
    color: #fff;
  }

  .dashboard-sidebar-logout i {
    color: #ff8794;
    font-size: 1.05rem;
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
    padding: 1.5rem 2rem;
    margin: 0;
    display: flex;
    flex-direction: column;
  }

  .dashboard-content > .row {
    margin-left: -0.75rem;
    margin-right: -0.75rem;
  }

  .dashboard-content > .row [class*='col-'] {
    padding-left: 0.75rem;
    padding-right: 0.75rem;
  }

  .dashboard-stats-row,
  .dashboard-actions-row {
    display: grid;
    gap: 1.25rem !important;
    margin-left: 0;
    margin-right: 0;
    width: 100%;
  }

  .dashboard-stats-row {
    grid-template-columns: repeat(4, minmax(0, 1fr));
  }

  .dashboard-actions-row {
    grid-template-columns: repeat(3, minmax(0, 1fr));
  }

  .dashboard-stats-row > [class*='col-'],
  .dashboard-actions-row > [class*='col-'] {
    max-width: none;
    padding-left: 0;
    padding-right: 0;
    width: auto;
  }

  .dashboard-stats-row > [class*='col-'] > .dashboard-stat,
  .dashboard-actions-row > [class*='col-'] > .dashboard-action-card {
    height: 100%;
    min-width: 0;
  }

  .dashboard-stats-row > [class*='col-'] > .dashboard-stat {
    min-height: 220px;
    width: 100% !important;
  }

  .dashboard-panel,
  .dashboard-stat,
  .dashboard-action-card,
  .dashboard-table-card,
  .dashboard-notice {
    background: rgba(255, 255, 255, 0.84);
    border: 1px solid rgba(16, 35, 60, 0.08);
    backdrop-filter: blur(10px);
    box-shadow: 0 14px 34px rgba(19, 44, 76, 0.08);
    border-radius: 24px;
  }

  /* Critical Layer Fix: Forces the Topbar layout row container onto a higher visual plane */
  .dashboard-topbar {
    background: rgba(255, 255, 255, 0.84);
    border: 1px solid rgba(16, 35, 60, 0.08);
    backdrop-filter: blur(10px);
    box-shadow: 0 14px 34px rgba(19, 44, 76, 0.08);
    border-radius: 24px;
    padding: 1rem 1.5rem;
    margin-bottom: 1.5rem;
    position: relative;
    z-index: 2000 !important; 
  }

  .dashboard-search {
    position: relative;
  }

  .dashboard-search .bi-search {
    position: absolute;
    top: 50%;
    left: 1.25rem;
    transform: translateY(-50%);
    color: #6c7c93;
  }

  .dashboard-search input {
    border-radius: 999px;
    padding-left: 2.8rem;
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
    color: #10233c;
    transition: background 0.2s;
  }

  .dashboard-bell:hover {
    background: #e4efff;
  }

  .dashboard-bell-badge {
    position: absolute;
    top: -4px;
    right: -4px;
    min-width: 20px;
    height: 20px;
    border-radius: 999px;
    background: #dc3545;
    color: #fff;
    font-size: 0.7rem;
    display: grid;
    place-items: center;
    padding: 0 0.25rem;
  }

  .dropdown {
    position: relative;
  }

  .dashboard-profile-btn {
    background: #f7faff;
    border-radius: 20px;
    padding: 0.5rem 0.75rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    border: 1px solid transparent;
    transition: all 0.2s;
    text-decoration: none !important;
  }

  .dashboard-profile-btn:hover {
    background: #edf3fe;
    border-color: rgba(16, 35, 60, 0.05);
  }

  .dropdown-toggle::after {
    display: none !important;
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
    padding: 2.25rem 2rem;
    background: linear-gradient(135deg, #123f77 0%, #0d6efd 55%, #7fc8ff 100%);
    color: #fff;
    margin-bottom: 1.5rem;
    border-radius: 24px;
    overflow: hidden;
    position: relative;
    z-index: 10;
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
    padding: 1.5rem;
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

  .dashboard-table-card {
    padding: 1.5rem;
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
    
    .dashboard-content {
      padding: 1.5rem 1rem;
    }

    .dashboard-stats-row {
      grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .dashboard-actions-row {
      grid-template-columns: repeat(2, minmax(0, 1fr));
    }
  }

  @media (max-width: 767.98px) {
    .dashboard-hero,
    .dashboard-topbar,
    .dashboard-stat,
    .dashboard-action-card,
    .dashboard-table-card,
    .dashboard-notice,
    .dashboard-panel {
      border-radius: 20px;
    }

    .dashboard-stats-row,
    .dashboard-actions-row {
      grid-template-columns: 1fr;
    }

    .dashboard-stats-row > [class*='col-'] > .dashboard-stat {
      min-height: 165px;
    }
  }
  .dashboard-content { min-width: 0; }
  .dashboard-notification-menu { width: min(390px, calc(100vw - 40px)); max-height: 70vh; overflow-y: auto; }
  .dashboard-notification-menu .dashboard-notice { width: 100% !important; padding: 1rem; }
  .dashboard-content .dashboard-table-card { width: 100% !important; }
  .pharmacy-workspace .dashboard-nav .nav-link { border: 0; border-radius: 8px; padding: .85rem 1rem; font-size: .9rem; }
  .pharmacy-workspace .dashboard-nav .nav-link:hover { transform: none; }
  .pharmacy-workspace .dashboard-hero h1 { font-size: 1.65rem; }
  .pharmacy-workspace .dashboard-notice { width: 100% !important; }
  .pharmacy-workspace .dashboard-stat { min-height: 150px !important; }
  .pharmacy-workspace .dashboard-topbar { z-index: 1050; }
</style>

<div class="dashboard-shell {{ $isPharmacyDashboard ? 'pharmacy-workspace' : '' }}">
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
        @if($isPharmacyDashboard)
          @include('pharmacy.sidebar')
        @else
          @yield('dashboard_sidebar')
        @endif
      </div>

      <div class="dashboard-sidebar-footer">
        <a href="{{ route('logout') }}" class="dashboard-sidebar-logout">
          <i class="bi bi-box-arrow-right"></i>
          <span class="fw-semibold">Logout</span>
        </a>
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

        <div class="d-flex align-items-center gap-3 justify-content-between justify-content-sm-end w-100 w-lg-auto">
          
          <div class="dropdown">
            <button class="dashboard-bell dropdown-toggle" type="button" id="bellDropdown" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false" aria-label="Notifications">
              <i class="bi bi-bell-fill"></i>
              @if($notificationBadge > 0)
                <span class="dashboard-bell-badge" id="live-bell-count">{{ $notificationBadge }}</span>
              @endif
            </button>
            <div class="dropdown-menu dropdown-menu-end shadow border-0 p-3 dashboard-notification-menu" aria-labelledby="bellDropdown">
              <h6 class="fw-bold border-bottom pb-3">Notifications</h6>
              @hasSection('dashboard_notifications')
                @yield('dashboard_notifications')
              @else
                <p class="small text-muted mb-0">No pending alerts.</p>
              @endif
            </div>
          </div>

          <div class="dropdown">
            <a href="#" class="dashboard-profile-btn dropdown-toggle text-decoration-none" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
              <div class="dashboard-avatar">{{ $initials ?: 'MF' }}</div>
              
              <div class="d-none d-sm-block text-start">
                <div class="fw-semibold text-dark small lh-1 mb-1">{{ $profileName }}</div>
                <div class="text-muted extra-small" style="font-size: 0.75rem;">{{ $profileRole }}</div>
              </div>
            </a>
    
            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2 p-2 rounded-3" aria-labelledby="profileDropdown" style="min-width: 220px; position: absolute; z-index: 3000 !important;">
              <li class="p-2 border-bottom mb-2 bg-light rounded-2">
                <div class="fw-bold text-dark small">{{ $profileName }}</div>
                <small class="text-primary fw-semibold" style="font-size: 0.72rem;">{{ $profileRole }} Control Panel</small>
              </li>
              
             <li>
  <a class="dropdown-item d-flex align-items-center py-2 px-3 rounded-2" href="{{ $isPharmacyDashboard ? url('/pharmacist/settings') : route('profile.settings') }}">
    <i class="bi bi-gear text-muted me-2.5 fs-5"></i> Account Settings
  </a>
</li>
              
              <li><hr class="dropdown-divider my-2"></li>
              
              <li>
                <a class="dropdown-item d-flex align-items-center py-2 px-3 text-danger rounded-2" href="/logout">
                  <i class="bi bi-box-arrow-right me-2.5 fs-5"></i> Logout
                </a>
              </li>
            </ul>
          </div>
          
        </div>
      </div>

      <div class="dashboard-hero text-white mb-4">
        <small class="text-white-50 text-uppercase fw-bold tracking-wider fs-7">Welcome Section</small>
        <h1 class="fw-bold mt-1 mb-2">@yield('dashboard_title')</h1>
        <p class="mb-0 text-white-50">@yield('dashboard_subtitle')</p>
        
        @hasSection('dashboard_welcome_meta')
          <div class="mt-3">
            @yield('dashboard_welcome_meta')
          </div>
        @endif
      </div>

      @hasSection('dashboard_stats')
        <div class="row g-3 mb-4 dashboard-stats-row">
          @yield('dashboard_stats')
        </div>
      @endif

      @hasSection('dashboard_actions')
        <div class="row g-3 mb-4 dashboard-actions-row">
          @yield('dashboard_actions')
        </div>
      @endif

      <div class="row g-4">
        <div class="col-12">
          @if($errors->any())
            <div class="alert alert-danger" role="alert"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
          @endif
          @yield('dashboard_main')
        </div>
      </div>
      
    </div>
  </div>
</div>
@endsection
