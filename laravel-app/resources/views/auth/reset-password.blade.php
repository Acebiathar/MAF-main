@extends('layouts.app')
@section('title', 'Choose a New Password | MedFinder')
@section('content')
<div class="mx-auto py-5" style="width: 100%; max-width: 480px;">
  <div class="card shadow border-0 rounded-4"><div class="card-body p-4 p-sm-5">
    <h1 class="h4 fw-bold">Choose a new password</h1><p class="text-muted small">Use at least 8 characters. This updates the password for your existing account.</p>
    @if($errors->any())<div class="alert alert-danger" role="alert">{{ $errors->first() }}</div>@endif
    <form method="POST" action="{{ route('password.update') }}">@csrf
      <input type="hidden" name="token" value="{{ $token }}">
      <label class="form-label" for="reset-email">Email address</label><input class="form-control mb-3" id="reset-email" name="email" type="email" value="{{ $email }}" readonly required>
      @foreach(['password' => 'New password', 'password_confirmation' => 'Confirm new password'] as $field => $label)
        <label class="form-label" for="reset-{{ $field }}">{{ $label }}</label><div class="input-group mb-3"><input class="form-control" id="reset-{{ $field }}" name="{{ $field }}" type="password" minlength="8" autocomplete="new-password" required><button class="btn btn-light" type="button" data-password-toggle="reset-{{ $field }}" aria-controls="reset-{{ $field }}" aria-label="Show password" aria-pressed="false"><i class="bi bi-eye" aria-hidden="true"></i></button></div>
      @endforeach
      <button class="btn btn-primary w-100">Save new password</button>
    </form>
    <a class="d-block text-center small mt-3" href="{{ route('password.request') }}">Request a new reset link</a>
  </div></div>
</div>
@endsection
