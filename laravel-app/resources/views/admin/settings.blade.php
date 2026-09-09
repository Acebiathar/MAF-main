<section class="admin-card admin-settings">
  <div class="admin-card-heading"><h2>Account & Security</h2></div><p class="admin-muted">Keep your administrator details up to date. Your current password is required to save changes.</p>
  <form method="POST" action="/admin/settings">@csrf
    <div class="row g-3">
      <div class="col-md-6"><label class="form-label" for="admin-name">Full name</label><input class="form-control" id="admin-name" name="name" value="{{ old('name', $currentUser->name) }}" autocomplete="name" required></div>
      <div class="col-md-6"><label class="form-label" for="admin-email">Email address</label><input class="form-control" id="admin-email" name="email" type="email" value="{{ old('email', $currentUser->email) }}" autocomplete="email" required></div>
      @foreach(['current_password' => 'Current password', 'password' => 'New password (optional)', 'password_confirmation' => 'Confirm new password'] as $field => $label)
        <div class="col-12"><label class="form-label" for="admin-{{ $field }}">{{ $label }}</label><div class="input-group"><input class="form-control" id="admin-{{ $field }}" name="{{ $field }}" type="password" autocomplete="{{ $field === 'current_password' ? 'current-password' : 'new-password' }}" @if($field === 'current_password') required @else minlength="8" @endif><button class="btn btn-light" type="button" data-password-toggle="admin-{{ $field }}" aria-controls="admin-{{ $field }}" aria-label="Show password" aria-pressed="false"><i class="bi bi-eye" aria-hidden="true"></i></button></div></div>
      @endforeach
      <div class="col-12"><button class="btn btn-primary">Save settings</button></div>
    </div>
  </form>
</section>
