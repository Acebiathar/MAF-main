<div class="row justify-content-center">
  <div class="col-lg-8">
    <div class="card shadow-sm">
      <div class="card-body p-4">
        <h4 class="fw-semibold mb-3">Create Account</h4>
        <form method="post">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Full name</label>
              <input class="form-control" name="name" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Email</label>
              <input class="form-control" type="email" name="email" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Password</label>
              <input class="form-control" type="password" name="password" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Role</label>
              <select class="form-select" name="role" id="roleSelect">
                <option value="patient">Patient</option>
                <option value="pharmacist">Pharmacist</option>
              </select>
            </div>
          </div>

          <div id="pharmacyFields" class="row g-3 mt-3 d-none">
            <div class="col-md-6">
              <label class="form-label">Pharmacy name</label>
              <input class="form-control" name="pharmacy_name">
            </div>
            <div class="col-md-6">
              <label class="form-label">License number</label>
              <input class="form-control" name="license">
            </div>
            <div class="col-md-6">
              <label class="form-label">Location</label>
              <input class="form-control" name="location">
            </div>
            <div class="col-md-6">
              <label class="form-label">Phone</label>
              <input class="form-control" name="phone">
            </div>
            <div class="col-12 text-muted small">Admin will review and approve pharmacy accounts.</div>
          </div>

          <button class="btn btn-primary w-100 mt-3" type="submit">Sign up</button>
          <div class="text-center small mt-2">Already registered? <a href="/login">Login</a></div>
        </form>
      </div>
    </div>
  </div>
</div>
