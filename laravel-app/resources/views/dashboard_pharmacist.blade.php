@extends('layouts.app')

@section('content')
<div class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0 text-dark">{{ $pharmacy->name }} Dashboard</h3>
        <a class="btn btn-primary shadow-sm" href="/pharmacist/requests">
            <i class="bi bi-bell-fill me-2"></i>View Requests
        </a>
    </div>

    <div class="alert alert-info border-0 shadow-sm d-flex align-items-center mb-4">
        <div class="me-auto">
            <span class="badge bg-primary me-2">Status: {{ ucfirst($pharmacy->status) }}</span>
            <span class="text-dark"><i class="bi bi-telephone"></i> {{ $pharmacy->phone }}</span>
            <span class="mx-2">|</span>
            <span class="text-dark"><i class="bi bi-geo-alt"></i> {{ $pharmacy->location }}</span>
        </div>
    </div>

    <div class="row g-4">
        {{-- Stock Management Form --}}
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">Add / Update Stock</h5>
                    <form method="POST" action="/pharmacist/add">
                        @csrf
                      <div class="mb-3">
    <label for="medicine_name" class="form-label">Medicine Name</label>
    <input class="form-control" list="medicineOptions" name="medicine_name" id="medicine_name" placeholder="Type to search or add new..." required>
    <datalist id="medicineOptions">
        @foreach($all_medicines as $med)
            <option value="{{ $med->name }}">
        @endforeach
    </datalist>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label>Price (UGX)</label>
        <input type="number" name="price" class="form-control" placeholder="e.g. 5000" required>
    </div>
    <div class="col-md-6 mb-3">
        <label>Quantity</label>
        <input type="number" name="stock_quantity" class="form-control" placeholder="e.g. 50" required>
    </div>
</div>

<div class="mb-3">
    <label>Stock Status</label>
    <select name="status" class="form-select">
        <option value="Available">Available (In Stock)</option>
        <option value="Out of Stock">Out of Stock</option>
        <option value="Limited">Limited Stock</option>
    </select>
</div>

                        <button class="btn btn-primary w-100 fw-bold py-2 shadow-sm" type="submit">
                            Save Stock Update
                        </button>
                    </form>
                </div>
            </div>
        </div>

       {{-- Inventory Table --}}
<div class="col-lg-7">
    <div class="card border-0 shadow-sm h-100">
        <div class="card-body p-4">
            <h5 class="fw-bold mb-3">Live Inventory</h5>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Medicine</th>
                            <th>Price (UGX)</th>
                            <th>Qty</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($inventory as $item)
                            <tr>
                                <td class="fw-bold">{{ $item->medicine_name }}</td>
                                <td>{{ number_format($item->price) }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>
                                    <span class="badge {{ $item->stock_status === 'in_stock' ? 'bg-success' : 'bg-danger' }}">
                                        {{ str_replace('_', ' ', $item->stock_status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">No medicines in inventory yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>