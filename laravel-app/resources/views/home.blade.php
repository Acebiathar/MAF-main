@extends('layouts.app')

@section('content')
<section class="hero rounded-4 p-4 p-md-5 mb-4 shadow-sm" style="background: #f8f9fa;">
  <div class="row align-items-center">
    <div class="col-lg-7">
      <p class="badge bg-primary-subtle text-primary mb-2">Medicine Availability Finder</p>
      <h1 class="fw-bold mb-3">Find medicine near you faster.</h1>
      <p class="lead text-secondary">Manage your list of medicines below. You can edit the names directly before searching.</p>

      <div class="input-group input-group-lg mb-4">
        <input type="text" id="itemInput" class="form-control" placeholder="Add a medicine (e.g. Panadol)..." onkeypress="if(event.key === 'Enter') addItem()">
        <button class="btn btn-primary" type="button" onclick="addItem()">Add to List</button>
      </div>

      <form action="/" method="GET" id="searchForm">
        <div id="editableItemList" class="mb-3">
          </div>
        
        <button type="submit" class="btn btn-lg btn-success w-100 shadow-sm" id="searchBtn" style="display: none;">
          <i class="bi bi-search me-2"></i>Search All Items
        </button>
      </form>
    </div>

    <div class="col-lg-5 mt-4 mt-lg-0">
      <div id="tips" class="carousel slide h-100" data-bs-ride="carousel">
        <div class="carousel-inner rounded-4 shadow-sm h-100">

          <div class="carousel-item active h-100" data-bs-interval="5000">
            <div class="carousel-image-container" style="background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('/images/hydrate.jpg');">
              <div class="p-4 h-100 d-flex flex-column justify-content-end">
                <h5 class="text-white fw-bold">Stay Hydrated</h5>
                <p class="mb-0 text-white-50 small">Drink enough water daily to help your medicine work effectively.</p>
              </div>
            </div>
          </div>

          <div class="carousel-item h-100" data-bs-interval="5000">
            <div class="carousel-image-container" style="background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('/images/dosage.jpg');">
              <div class="p-4 h-100 d-flex flex-column justify-content-end">
                <h5 class="text-white fw-bold">Finish the Dose</h5>
                <p class="mb-0 text-white-50 small">Always finish prescribed medication even if you feel better.</p>
              </div>
            </div>
          </div>

          <div class="carousel-item h-100" data-bs-interval="5000">
            <div class="carousel-image-container" style="background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('/images/storage.jpg');">
              <div class="p-4 h-100 d-flex flex-column justify-content-end">
                <h5 class="text-white fw-bold">Store Safely</h5>
                <p class="mb-0 text-white-50 small">Keep medicines away from heat and out of reach of children.</p>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>

    <style>
      .carousel-image-container {
        height: 320px;
        /* Adjust this to match your search box height */
        background-size: cover;
        background-position: center;
        transition: transform 0.5s ease;
      }

      #tips .carousel-item {
        height: 320px;
      }

      /* Subtle hover effect */
      .carousel-image-container:hover {
        transform: scale(1.02);
      }
    </style>

    <script>
 function addItem() {
    const input = document.getElementById('itemInput');
    const list = document.getElementById('editableItemList');
    const value = input.value.trim();

    if (value === "") return;

    const itemId = 'item_' + Date.now();

    // Create a row container
    const div = document.createElement('div');
    // Removed the icon span and kept just the input and the delete button
    div.className = "input-group mb-2 shadow-sm animate__animated animate__fadeIn";
    div.id = itemId;

    div.innerHTML = `
      <input type="text" name="item_names[]" class="form-control bg-white" value="${value}" placeholder="Medicine name">
      <button class="btn btn-outline-danger" type="button" onclick="document.getElementById('${itemId}').remove(); updateUI();">
        <i class="bi bi-trash"></i>
      </button>
    `;

    list.appendChild(div);
    input.value = "";
    input.focus();
    updateUI();
  }

  function updateUI() {
    const list = document.getElementById('editableItemList');
    const btn = document.getElementById('searchBtn');
    const count = list.children.length;
    btn.style.display = count > 0 ? 'block' : 'none';
    btn.innerHTML = `<i class="bi bi-search me-2"></i>Search ${count} Item${count > 1 ? 's' : ''}`;
  }
</script>

  </div>
</section>

@if ($query !== '')
<section class="mb-4">
  <div class="d-flex justify-content-between align-items-center mb-2">
    <h4 class="fw-semibold mb-0">Results for "{{ $query }}"</h4>
  </div>
 @if(count($results) > 0)
  <div class="table-responsive card shadow-sm border-0">
    <table class="table align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th>Medicine</th>
          <th>Price (UGX)</th>
          <th>Status</th>
          <th>Quantity</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        {{-- 1. Group results by Pharmacy Name --}}
        @foreach ($results->groupBy('pharmacy_name') as $pharmacyName => $meds)
        
        {{-- 2. Header for each Pharmacy --}}
        <tr class="table-secondary">
          <td colspan="5" class="py-2 px-3">
             <div class="d-flex justify-content-between align-items-center">
                <div>
                   <i class="bi bi-shop text-primary me-2"></i>
                   <strong class="text-dark">{{ $pharmacyName }}</strong>
                   <span class="small text-muted ms-2">({{ $meds->first()->pharmacy_location }})</span>
                </div>
                {{-- Show how many items from the search list are found here --}}
                <span class="badge bg-primary rounded-pill">
                  {{ $meds->count() }} items available
                </span>
             </div>
          </td>
        </tr>

        {{-- 3. List of medicines found at THIS specific pharmacy --}}
        @foreach ($meds as $item)
        <tr>
          <td class="ps-4">
            <strong>{{ $item->medicine_name }}</strong>
          </td>
          <td>{{ number_format((float)($item->price ?? 0), 0) }}</td>
          <td>
            @if ((int)$item->quantity > 0)
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
    <div class="col">
      <h5 class="fw-semibold mb-0">Alternatives</h5>
    </div>
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