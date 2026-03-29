@extends('layouts.app')

@section('content')
<section class="hero rounded-4 p-4 p-md-5 mb-4 shadow-sm" style="background: #f8f9fa;">
  <div class="row align-items-center">
    <div class="col-lg-7">
      <p class="badge bg-primary-subtle text-primary mb-2">Medicine Availability Finder</p>
      <h1 class="fw-bold mb-3">Find medicine near you faster.</h1>
      <p class="lead text-secondary">Search trusted pharmacies, compare prices, see availability, and get alternatives instantly.</p>
      
      <form class="d-flex gap-2 mt-3" method="get" action="/">
        <input class="form-control form-control-lg" type="search" name="q" placeholder="Search for a medicine..." value="{{ $query }}" required>
        <button class="btn btn-lg btn-primary" type="submit">Search</button>
      </form>
      <div class="small text-muted mt-2">Try Panadol, Paracetamol, Insulin…</div>
    </div>

    <div class="col-lg-5 mt-4 mt-lg-0">
      <div id="tips" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner rounded-3 shadow-sm">
          <div class="carousel-item active p-4 bg-white">
            <h5 class="text-primary">Stay hydrated</h5>
            <p class="mb-0 text-muted">Drink enough water daily to help your medicine work effectively.</p>
          </div>
          <div class="carousel-item p-4 bg-white">
            <h5 class="text-primary">Finish the dose</h5>
            <p class="mb-0 text-muted">Always finish prescribed medication even if you feel better.</p>
          </div>
          <div class="carousel-item p-4 bg-white">
            <h5 class="text-primary">Store safely</h5>
            <p class="mb-0 text-muted">Keep medicines away from heat and out of reach of children.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

@if ($query !== '')
<section class="mb-4">
  <div class="d-flex justify-content-between align-items-center mb-2">
    <h4 class="fw-semibold mb-0">Results for "{{ $query }}"</h4>
  </div>
  @if(count($results) > 0)
    <div class="table-responsive card shadow-sm">
      <table class="table align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>Medicine</th>
            <th>Pharmacy</th>
            <th>Price (UGX)</th>
            <th>Status</th>
            <th>Quantity</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          @foreach ($results as $item)
          <tr>
            <td><strong>{{ $item->medicine_name }}</strong></td>
            <td>
                {{ $item->pharmacy_name }}
                <div class="small text-muted">{{ $item->pharmacy_location }}</div>
            </td>
            <td>{{ number_format((float)($item->price ?? 0), 0) }}</td>
            <td>
              @if (($item->stock_status ?? '') === 'in_stock')
                <span class="badge bg-success">In stock</span>
              @else
                <span class="badge bg-secondary">Out of stock</span>
              @endif
            </td>
            <td>{{ (int)$item->quantity }}</td>
            <td>
              <form method="post" action="/reserve/{{ $item->id }}" class="d-flex gap-1">
                @csrf
                <input type="hidden" name="note" value="Request from search">
                <button class="btn btn-sm btn-primary" type="submit">Reserve</button>
              </form>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  @else
    <div class="alert alert-info border-0 shadow-sm">No exact matches yet. Try an alternative below.</div>
  @endif
</section>
@endif

<section class="mb-5">
  <div class="row g-3 align-items-center mb-2">
    <div class="col"><h5 class="fw-semibold mb-0">Alternatives</h5></div>
    <div class="col-auto text-muted small">Same category suggestions</div>
  </div>
  <div class="row g-3">
    @forelse ($alternatives as $med)
      <div class="col-md-3">
        <div class="card h-100 shadow-sm border-0">
          <div class="card-body">
            <div class="fw-bold text-primary">{{ $med->name }}</div>
            <div class="text-muted small">{{ $med->category ?? 'General' }}</div>
            <p class="small mt-2 text-secondary">{{ $med->description ?? '' }}</p>
          </div>
        </div>
      </div>
    @empty
      <div class="col-12 text-muted">Enter a medicine to see suggested alternatives.</div>
    @endforelse
  </div>
</section>

<section class="mb-5">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-semibold mb-0">Nearby pharmacies</h5>
  </div>
  <div class="row g-3">
    @foreach ($pharmacies as $pharmacy)
    <div class="col-md-3">
      <div class="card h-100 shadow-sm border-0">
        <div class="card-body text-center">
          <div class="fw-semibold">{{ $pharmacy->name }}</div>
          <div class="small text-muted">{{ $pharmacy->location }}</div>
          <span class="badge bg-success-subtle text-success mt-2">Approved</span>
        </div>
      </div>
    </div>
    @endforeach
  </div>
</section>
@endsection