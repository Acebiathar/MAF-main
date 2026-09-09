@extends('layouts.app')
@section('title', 'Reset Password | MedFinder')
@section('content')
<div class="mx-auto py-5" style="width: 100%; max-width: 480px;">
  <div class="card shadow border-0 rounded-4"><div class="card-body p-4 p-sm-5">
    <h1 class="h4 fw-bold">Forgot your password?</h1><p class="text-muted small">Enter your account email to request a password reset link.</p>
    @if($errors->any())<div class="alert alert-danger" role="alert">{{ $errors->first() }}</div>@endif
    <form method="POST" action="{{ route('password.email') }}">@csrf
      <label class="form-label" for="reset-email">Email address</label><input class="form-control mb-3" id="reset-email" name="email" type="email" autocomplete="email" value="{{ old('email') }}" required>
      <button class="btn btn-primary w-100">Send reset link</button>
    </form>
    <a class="d-block text-center small mt-3" href="/login">Back to sign in</a>
  </div></div>
</div>
@endsection
