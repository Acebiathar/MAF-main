      <div class="dashboard-topbar d-flex flex-column flex-lg-row align-items-lg-center gap-3 justify-content-between">
        @unless($hasDashboardHeader)
        <form action="/" method="GET" class="dashboard-search flex-grow-1">
          <i class="bi bi-search"></i>
          <input
            type="text"
            name="search"
            class="form-control form-control-lg"
            placeholder="{{ $dashboardSearchPlaceholder }}">
        </form>
        @endunless

        <div class="d-flex align-items-center gap-3 justify-content-between justify-content-sm-end w-100 w-lg-auto">
          
          <div class="dropdown">
            <button class="dashboard-bell dropdown-toggle" type="button" id="bellDropdown" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false" aria-label="Notifications">
              <i class="bi {{ $hasDashboardHeader ? 'bi-bell' : 'bi-bell-fill' }}"></i>
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
            <a href="#" class="dashboard-profile-btn dropdown-toggle text-decoration-none" id="profileDropdown" aria-label="Open account menu" data-bs-toggle="dropdown" aria-expanded="false">
              <div class="dashboard-avatar">@if($hasDashboardHeader)<i class="bi bi-person-fill" aria-hidden="true"></i>@else{{ $initials ?: 'MF' }}@endif</div>
              
              <div class="{{ $hasDashboardHeader ? 'dashboard-profile-copy' : 'd-none d-sm-block' }} text-start">
                <div class="fw-semibold text-dark small lh-1 mb-1">{{ $isPharmacyDashboard ? ($pharmacy->name ?? $profileName) : $profileName }}</div>
                <div class="text-muted extra-small" style="font-size: 0.75rem;">{{ $profileRole }}</div>
              </div>
              @if($hasDashboardHeader)<i class="bi bi-chevron-down dashboard-profile-chevron" aria-hidden="true"></i>@endif
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

