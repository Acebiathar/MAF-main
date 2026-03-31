@extends('layouts.app')

@section('content')
<div class="row justify-content-center align-items-center" style="min-height: 70vh;">
  <div class="col-md-5">
    <div class="card shadow border-0 rounded-4">
      <div class="card-body p-5">
        <div class="text-center mb-4">
            <h4 class="fw-bold mb-1">Welcome!</h4>
            <p class="text-muted small">Sign in to manage your medicine requests</p>
        </div>

        <form method="POST" action="/login">
          @csrf
          <div class="mb-3">
            <label class="form-label small fw-bold text-secondary">Email Address</label>
            <input class="form-control bg-light border-0 py-2" type="email" name="email" placeholder="name@example.com" required autofocus>
          </div>

          <div class="mb-4">
            <label class="form-label small fw-bold text-secondary">Password</label>
            <input class="form-control bg-light border-0 py-2" type="password" name="password" placeholder="********" required>
          </div>

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
            <span class="opacity-75">Your dashboard will load once your license is verified.</span>
        </p>
    </div>
  </div>
</div>
@endsection