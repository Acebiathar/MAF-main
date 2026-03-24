@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
  <div class="col-md-6">
    <div class="card shadow-sm">
      <div class="card-body p-4">
        <h4 class="fw-semibold mb-3">Login</h4>
        <form method="post">
          @csrf
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input class="form-control" type="email" name="email" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Password</label>
            <input class="form-control" type="password" name="password" required>
          </div>
          <button class="btn btn-primary w-100" type="submit">Sign in</button>
          <div class="text-center small mt-2">No account? <a href="{{ url('/register') }}">Create one</a></div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
