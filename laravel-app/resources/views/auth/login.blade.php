@extends('layouts.app')

@section('content')
<div class="row justify-content-center align-items-center" style="min-height: 70vh;">
  <div class="w-100" style="max-width: 480px;">
    <div class="card shadow border-0 rounded-4">
      <div class="card-body p-4 p-sm-5">
        <div class="text-center mb-4">
            <h4 class="fw-bold mb-1">Welcome!</h4>
            <p class="text-muted small">Sign in to manage your medicine requests</p>
        </div>

        @if($errors->any())
          <div class="alert alert-danger" role="alert"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
        @endif
        <form method="POST" action="/login">
          @csrf
          <div class="mb-3">
            <label class="form-label small fw-bold text-secondary">Email Address</label>
            <input class="form-control bg-light border-0 py-2" type="email" name="email" value="{{ old('email') }}" autocomplete="email" placeholder="name@example.com" required autofocus>
          </div>

          <div class="mb-4">
            <label for="login-password" class="form-label small fw-bold text-secondary">Password</label>
            <div class="input-group">
              <input id="login-password" class="form-control bg-light border-0 py-2" type="password" name="password" autocomplete="current-password" placeholder="********" required>
              <button type="button" class="btn btn-light text-secondary px-3" data-password-toggle="login-password" aria-controls="login-password" aria-label="Show password" aria-pressed="false" title="Show password"><i class="bi bi-eye" aria-hidden="true"></i></button>
            </div>
          </div>

          <div class="text-end mb-3"><a class="small" href="{{ route('password.request') }}">Forgot password?</a></div>
          <button class="btn btn-primary w-100 py-2 fw-bold shadow-sm" type="submit">
            Sign In
          </button>
          
          <div class="text-center mt-4">
            <span class="small text-muted">Don't have an account?</span> 
            <a href="/register" class="small fw-bold text-decoration-none">Create an account</a>
          </div>
        </form>
      </div>
    </div>
    
    <div class="text-center mt-4">
        <p class="small text-muted">
            Logging in as a Pharmacist? <br> 
            <span class="opacity-75">We’ll open the dashboard for your account automatically.</span>
        </p>
    </div>
  </div>
</div>
@endsection
