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
                            <label class="form-label small fw-bold">Medicine Name</label>
                            <select class="form-select bg-light border-0" name="medicine_id" required>
                                <option value="" selected disabled>Select a medicine...</option>
                                @foreach ($medicines as $med)
                                    <option value="{{ $med->id }}">
                                        {{ $med->name }} ({{ $med->category }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold">Price (UGX)</label>
                                <input class="form-control bg-light border-0" type="number" name="price" min="0" step="100" placeholder="e.g. 5000" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold">Quantity</label>
                                <input class="form-control bg-light border-0" type="number" name="quantity" min="0" step="1" placeholder="e.g. 50" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold">Stock Status</label>
                            <select class="form-select bg-light border-0" name="stock_status">
                                <option value="in_stock">Available (In Stock)</option>
                                <option value="out_of_stock">Unavailable (Out of Stock)</option>
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
                    <div