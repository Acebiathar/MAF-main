@extends('layouts.app')

@section('content')
<h3 class="fw-bold mb-3">{{ $pharmacy->name }} dashboard</h3>
<div class="d-flex align-items-center gap-2 mb-3">
  <div class="alert alert-info mb-0">Status: {{ ucfirst($pharmacy->status) }}. Phone {{ $pharmacy->phone }} | Location {{ $pharmacy->location }}</div>
  <a class="btn btn-sm btn-outline-primary" href="{{ url('/pharmacist/requests') }}">View requests</a>
  </div>
<div class="row g-4">
  <div class="col-lg-5">
    <div class="card shadow-sm">
      <div class="card-body">
        <h5 class="fw-semibold">Add / update stock</h5>
        <form method="post" action="{{ url('/pharmacist/add') }}">
          @csrf
          <div class="mb-3">
            <label class="form-label">Medicine</label>
            <select class="form-select" name="medicine_id" required>
              @foreach($medicines as $med)
              <option value="{{ $med->id }}">{{ $med->name }} ({{ $med->category }})</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Price (UGX)</label>
            <input class="form-control" type="number" name="price" min="0" step="100" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Quantity</label>
            <input class="form-control" type="number" name="quantity" min="0" step="1" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Status</label>
            <select class="form-select" name="stock_status">
              <option value="in_stock">In stock</option>
              <option value="out_of_stock">Out of stock</option>
            </select>
          </div>
          <button class="btn btn-primary w-100" type="submit">Save</button>
        </form>
      </div>
    </div>
  </div>
  <div class="col-lg-7">
    <div class="card shadow-sm">
      <div class="card-body">
        <h5 class="fw-semibold mb-3">Inventory</h5>
        <div class="table-responsive">
          <table class="table align-middle mb-0">
            <thead class="table-light">
              <tr><th>Medicine</th><th>Price</th><th>Status</th><th>Qty</th></tr>
            </thead>
            <tbody>
              @foreach($inventory as $item)
              <tr>
                <td>{{ $item->medicine_name }}</td>
                <td>{{ number_format($item->price ?? 0, 0) }}</td>
                <td>{!! $item->stock_status === 'in_stock' ? '<span class="badge bg-success">In stock</span>' : '<span class="badge bg-secondary">Out</span>' !!}</td>
                <td>{{ (int)$item->quantity }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
