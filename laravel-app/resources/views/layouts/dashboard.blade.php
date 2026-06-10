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

        <div class="d-flex align-items-center gap-3 justify-content-between justify-content-sm-end w-100 w-lg-auto">
          
          <div class="dropdown">
            <button class="dashboard-bell dropdown-toggle" type="button" id="bellDropdown" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Notifications">
              <i class="bi bi-bell-fill"></i>
              @if($notificationBadge > 0)
                <span class="dashboard-bell-badge" id="live-bell-count">{{ $notificationBadge }}</span>
              @endif
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2 p-0 rounded-3" aria-labelledby="bellDropdown" style="width: 310px; max-height: 380px; overflow-y: auto;">
              <li class="p-3 border-bottom bg-light rounded-top d-flex justify-content-between align-items-center">
                <span class="fw-bold small text-dark">Recent Notifications</span>
                @if($notificationBadge > 0)
                  <a href="#" id="clear-notifications-btn" class="text-primary extra-small text-decoration-none fw-semibold" style="font-size: 0.75rem;">Mark all read</a>
                @endif
              </li>
              <div id="dropdown-notification-list">
                @forelse(optional($currentUser)->unreadNotifications ? $currentUser->unreadNotifications->take(5) : [] as $notification)
                  <li>
                    <a class="dropdown-item p-3 border-bottom d-flex align-items-start text-wrap" href="{{ $notification->data['action_url'] ?? '#' }}">
                      <div class="me-2">
                        <span class="badge @if($profileRole == 'Admin') bg-danger-subtle text-danger @else bg-primary-subtle text-primary @endif rounded-circle p-2">
                          <i class="bi bi-info-circle-fill"></i>
                        </span>
                      </div>
                      <div>
                        <p class="mb-0 small fw-medium text-dark" style="line-height: 1.3;">{{ $notification->data['message'] }}</p>
                        <small class="text-muted extra-small" style="font-size: 0.7rem;">{{ $notification->created_at->diffForHumans() }}</small>
                      </div>
                    </a>
                  </li>
                @empty
                  <li class="p-4 text-center text-muted small">No pending alerts</li>
                @endforelse
              </div>
            </ul>
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
  <a class="dropdown-item d-flex align-items-center py-2 px-3 rounded-2" href="{{ route('profile.settings') }}">
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