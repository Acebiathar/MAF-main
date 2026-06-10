@extends('layouts.dashboard')

@section('content')
<div class="container-fluid p-4">
    <div class="row m-0 mb-4">
        <div class="col-12 p-0">
            <div class="welcome-banner p-4 rounded-4 text-white bg-primary position-relative overflow-hidden shadow-sm">
                <h2 class="fw-bold mb-1">Account Management</h2>
                <p class="m-0 text-white-50">Keep your login credentials and personal records updated.</p>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-4">
            <form method="POST" action="/account">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-muted">Full Name</label>
                        <input type="text" name="name" class="form-control rounded-3" value="{{ $user->name }}" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-muted">Email Address</label>
                        <input type="email" name="email" class="form-control rounded-3" value="{{ $user->email }}" required>
                    </div>

                    <div class="col-12">
                        <hr class="text-muted my-3">
                        <h5 class="fw-bold mb-1 text-dark">Change Password</h5>
                        <p class="small text-muted mb-3">Leave empty if you do not want to alter your password credentials.</p>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label small fw-bold text-muted">New Password</label>
                        <input type="password" name="password" class="form-control rounded-3" placeholder="••••••••">
                    </div>

                    <div class="col-12 mt-4 text-end">
                        <button type="submit" class="btn btn-primary rounded-3 px-4 py-2">
                            Save Changes
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection