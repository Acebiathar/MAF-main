@extends('layouts.app')

@section('title', 'Create Account | Medfinder')

@section('content')
<div class="row justify-content-center py-5">
  <div class="col-lg-8">
    <div class="card shadow border-0 rounded-4">
      <div class="card-body p-4 p-md-5">
        <div class="text-center mb-4">
          <h4 class="fw-bold mb-1">Create Your MAF Account</h4>
          <p class="text-muted small mb-0">Join our network of patients and pharmacies in Uganda</p>
        </div>

        <form method="POST" action="/register" novalidate>
          @csrf
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label small fw-bold text-secondary">Full Name</label>
              <input
                class="form-control bg-light border-0"
                name="name"
                value="{{ old('name') }}"
                placeholder="Enter your full name"
                required>
            </div>

            <div class="col-md-6">
              <label class="form-label small fw-bold text-secondary">Email Address</label>
              <input
                class="form-control bg-light border-0"
                type="email"
                name="email"
                value="{{ old('email') }}"
                placeholder="example@mail.com"
                required>
            </div>

            <div class="col-md-6">
              <label class="form-label small fw-bold text-secondary">Password</label>
              <input
                class="form-control bg-light border-0"
                type="password"
                name="password"
                placeholder="Minimum 8 characters"
                required>
            </div>

            <div class="col-md-6">
              <label class="form-label small fw-bold text-secondary">I am a...</label>
              <select class="form-select bg-light border-0" name="role" id="roleSelect">
                <option value="patient" {{ old('role', 'patient') === 'patient' ? 'selected' : '' }}>
                  Patient (Searching for medicine)
                </option>
                <option value="pharmacist" {{ old('role') === 'pharmacist' ? 'selected' : '' }}>
                  Pharmacist (Listing inventory)
                </option>
              </select>
            </div>
          </div>

          <div id="pharmacyFields" class="row g-3 mt-2 pt-3 border-top d-none">
            <div class="col-12">
              <h6 class="fw-bold text-primary mb-3">Pharmacy Verification Details</h6>
            </div>

            <div class="col-md-6">
              <label class="form-label small fw-bold">Pharmacy Name</label>
              <input
                class="form-control"
                name="pharmacy_name"
                id="pharmacy_name"
                value="{{ old('pharmacy_name') }}"
                placeholder="Registered pharmacy name">
            </div>

            <div class="col-md-6">
              <label class="form-label small fw-bold">NDA License Number</label>
              <input
                class="form-control"
                name="license_number"
                id="license_number"
                value="{{ old('license_number') }}"
                placeholder="e.g. NDA/1234/2026">
            </div>

            <div class="col-md-6">
              <label class="form-label small fw-bold">Physical Location</label>
              <input
                class="form-control"
                name="location"
                id="location"
                value="{{ old('location') }}"
                placeholder="Town or district">
            </div>

            <div class="col-md-6">
              <label class="form-label small fw-bold">Business Phone</label>
              <input
                class="form-control"
                name="phone"
                id="phone"
                value="{{ old('phone') }}"
                placeholder="+256...">
            </div>

            <div class="col-12">
              <div class="alert alert-warning py-2 small mb-0">
                <<<<<<< HEAD
                  <i class="bi bi-info-circle me-2"></i>Note: Pharmacist accounts require verification by our Admin team before you can list medicine.
                  <i class="bi bi-info-circle me-2"></i>
                  Pharmacist accounts require admin approval before inventory appears in patient searches.
              </div>
            </div>
          </div>

          <button class="btn btn-primary w-100 mt-4 py-2 fw-bold shadow-sm" type="submit">
            Create My Account
          </button>

          <div class="text-center mt-3 small">
            Already registered?
            <a href="/login" class="fw-bold text-decoration-none">Log In Here</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

@section('scripts')
<script>
  function togglePharmacyFields() {
    const roleSelect = document.getElementById('roleSelect');
    const pharmacyFields = document.getElementById('pharmacyFields');
    const pharmacistInputs = pharmacyFields?.querySelectorAll('input');
    const isPharmacist = roleSelect?.value === 'pharmacist';

    if (!pharmacyFields || !pharmacistInputs) return;

    pharmacyFields.classList.toggle('d-none', !isPharmacist);
    pharmacistInputs.forEach((input) => {
      input.required = isPharmacist;
    });
  }

  document.addEventListener('DOMContentLoaded', function() {
    const roleSelect = document.getElementById('roleSelect');
    if (roleSelect) {
      roleSelect.addEventListener('change', togglePharmacyFields);
    }
    togglePharmacyFields();
  });
</script>
@endsection
>>>>>>> 264cb12a1125063bd77d4034c2b7e7385f0e1bdf