@extends('layouts.app')

@section('title', 'Pharmacy & Medicine Inventory')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <div>
    <h4 class="fw-bold mb-1"><i class="bx bx-capsule me-2 text-primary"></i> Pharmacy Medicine Inventory</h4>
    <p class="text-muted mb-0">Track batches, stock levels, unit pricing, and low-inventory alerts</p>
  </div>
  <a href="{{ route('pharmacy.create') }}" class="btn btn-primary">
    <i class="bx bx-plus me-1"></i> Add New Medicine
  </a>
</div>

<!-- Stats -->
<div class="row g-3 mb-4">
  <div class="col-md-3 col-6">
    <div class="card border-0 bg-primary text-white shadow-sm">
      <div class="card-body p-3 d-flex justify-content-between align-items-center">
        <div>
          <span class="small text-white-50">Total Medicines</span>
          <h3 class="fw-bold mb-0 text-white">{{ $stats['total_medicines'] }}</h3>
        </div>
        <i class="bx bx-capsule fs-1 text-white-50"></i>
      </div>
    </div>
  </div>

  <div class="col-md-3 col-6">
    <div class="card border-0 bg-success text-white shadow-sm">
      <div class="card-body p-3 d-flex justify-content-between align-items-center">
        <div>
          <span class="small text-white-50">Total Units in Stock</span>
          <h3 class="fw-bold mb-0 text-white">{{ $stats['total_stock'] }}</h3>
        </div>
        <i class="bx bx-box fs-1 text-white-50"></i>
      </div>
    </div>
  </div>

  <div class="col-md-3 col-6">
    <div class="card border-0 bg-danger text-white shadow-sm">
      <div class="card-body p-3 d-flex justify-content-between align-items-center">
        <div>
          <span class="small text-white-50">Low Stock Warnings</span>
          <h3 class="fw-bold mb-0 text-white">{{ $stats['low_stock'] }}</h3>
        </div>
        <i class="bx bx-error-alt fs-1 text-white-50"></i>
      </div>
    </div>
  </div>

  <div class="col-md-3 col-6">
    <div class="card border-0 bg-warning text-white shadow-sm">
      <div class="card-body p-3 d-flex justify-content-between align-items-center">
        <div>
          <span class="small text-white-50">Expiring Soon (2 mos)</span>
          <h3 class="fw-bold mb-0 text-white">{{ $stats['expiring_soon'] }}</h3>
        </div>
        <i class="bx bx-time fs-1 text-white-50"></i>
      </div>
    </div>
  </div>
</div>

<!-- Search & Filters -->
<div class="card mb-4">
  <div class="card-body">
    <form action="{{ route('pharmacy.index') }}" method="GET" class="row g-3">
      <div class="col-md-5">
        <label class="form-label fw-semibold">Search Medicine or Generic Formula</label>
        <div class="input-group">
          <span class="input-group-text"><i class="bx bx-search"></i></span>
          <input type="text" name="search" class="form-control" placeholder="e.g. Panadol, Augmentin, Paracetamol..." value="{{ request('search') }}">
        </div>
      </div>
      <div class="col-md-3">
        <label class="form-label fw-semibold">Category</label>
        <select name="category" class="form-select">
          <option value="">All Categories</option>
          @foreach($categories as $cat)
            <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2">
        <label class="form-label fw-semibold">Stock Status</label>
        <select name="stock_status" class="form-select">
          <option value="">All</option>
          <option value="low" {{ request('stock_status') == 'low' ? 'selected' : '' }}>Low Stock Alert</option>
          <option value="in" {{ request('stock_status') == 'in' ? 'selected' : '' }}>In Stock</option>
          <option value="out" {{ request('stock_status') == 'out' ? 'selected' : '' }}>Out of Stock</option>
        </select>
      </div>
      <div class="col-md-2 d-flex align-items-end gap-2">
        <button type="submit" class="btn btn-primary w-100"><i class="bx bx-filter"></i> Filter</button>
        @if(request()->hasAny(['search', 'category', 'stock_status']))
          <a href="{{ route('pharmacy.index') }}" class="btn btn-outline-secondary" title="Clear Filters"><i class="bx bx-reset"></i></a>
        @endif
      </div>
    </form>
  </div>
</div>

<!-- Medicine Inventory Table -->
<div class="card">
  <div class="table-responsive text-nowrap">
    <table class="table table-hover align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th>Medicine Name</th>
          <th>Category</th>
          <th>Batch #</th>
          <th>Expiry Date</th>
          <th>Cost / Sell Price</th>
          <th>Current Stock</th>
          <th class="text-center">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($medicines as $med)
          @php
            $isLowStock = ($med->stock_quantity <= $med->alert_quantity);
          @endphp
          <tr class="{{ $isLowStock ? 'table-warning' : '' }}">
            <td>
              <div class="fw-bold text-dark">{{ $med->name }}</div>
              <small class="text-muted">{{ $med->generic_name }}</small>
            </td>
            <td>
              <span class="badge bg-label-info">{{ $med->category }}</span>
              <small class="text-muted d-block">{{ $med->dosage_form }}</small>
            </td>
            <td><code>{{ $med->batch_number }}</code></td>
            <td>
              {{ \Carbon\Carbon::parse($med->expiry_date)->format('M Y') }}
              @if(\Carbon\Carbon::parse($med->expiry_date)->isPast())
                <span class="badge bg-danger ms-1">Expired</span>
              @endif
            </td>
            <td>
              <small class="text-muted">Cost: ${{ number_format($med->cost_price, 2) }}</small>
              <div class="fw-bold text-success">${{ number_format($med->selling_price, 2) }}</div>
            </td>
            <td>
              <span class="fw-bold fs-6 {{ $isLowStock ? 'text-danger' : 'text-success' }}">
                {{ $med->stock_quantity }}
              </span>
              @if($isLowStock)
                <span class="badge bg-danger ms-1">Low (Alert: {{ $med->alert_quantity }})</span>
              @endif
            </td>
            <td class="text-center">
              <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#addStockModal{{ $med->id }}" title="Add Stock Units">
                <i class="bx bx-plus"></i> Stock
              </button>
              <a href="{{ route('pharmacy.edit', $med->id) }}" class="btn btn-sm btn-outline-secondary" title="Edit">
                <i class="bx bx-edit"></i>
              </a>

              <!-- Add Stock Modal -->
              <div class="modal fade" id="addStockModal{{ $med->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content text-start">
                    <form action="{{ route('pharmacy.stock', $med->id) }}" method="POST">
                      @csrf
                      <div class="modal-header">
                        <h5 class="modal-title fw-bold">Replenish Stock: {{ $med->name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                      </div>
                      <div class="modal-body">
                        <p class="text-muted small">Current stock in inventory: <strong>{{ $med->stock_quantity }} units</strong></p>
                        <div class="mb-3">
                          <label class="form-label fw-semibold">Quantity to Add <span class="text-danger">*</span></label>
                          <input type="number" name="quantity" class="form-control" min="1" placeholder="e.g. 50" required>
                        </div>
                        <div class="mb-3">
                          <label class="form-label fw-semibold">New Batch Number (Optional)</label>
                          <input type="text" name="batch_number" class="form-control" value="{{ $med->batch_number }}">
                        </div>
                        <div class="mb-3">
                          <label class="form-label fw-semibold">New Expiry Date (Optional)</label>
                          <input type="date" name="expiry_date" class="form-control" value="{{ $med->expiry_date }}">
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Update Stock</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="text-center py-5 text-muted">
              <i class="bx bx-capsule fs-1 d-block mb-2"></i>
              No medicines found matching criteria.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($medicines->hasPages())
    <div class="card-footer py-2">
      {{ $medicines->links('pagination::bootstrap-5') }}
    </div>
  @endif
</div>
@endsection
