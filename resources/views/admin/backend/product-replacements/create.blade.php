@extends('admin.admin_master')
@section('admin')

<div class="content d-flex flex-column flex-column-fluid">
   <div class="d-flex flex-column-fluid">
      <div class="container-fluid my-0">
        <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
            <div class="flex-grow-1">
                <h2 class="fs-22 fw-semibold m-0">Update Product Expiry Date</h2>
                <p class="text-muted mb-0">Update expired product with new expiry date from fresh stock</p>
            </div>

            <div class="text-end">
                <ol class="breadcrumb m-0 py-0">
                     <a href="{{ route('expired.product') }}" class="btn btn-dark">Back</a>
                </ol>
            </div>
        </div>
         <div class="card">
            <div class="card-body">
                {{-- Process Summary --}}
                <div class="alert alert-info mb-4">
                    <h6><i class="fas fa-info-circle"></i> What This Process Does:</h6>
                    <ol class="mb-0">
                        <li><strong>Replaces old product:</strong> Updates the old product with new product details</li>
                        <li><strong>Copies all details:</strong> Name, code, expiry date, and other details from new product</li>
                        <li><strong>Updates same record:</strong> No new product added, just existing product updated</li>
                        <li><strong>Result:</strong> Old product becomes the new product with fresh details</li>
                    </ol>
                </div>

                <form action="{{ route('replacement.store') }}" method="POST">
                        @csrf

                        {{-- Supplier --}}
                        <div class="mb-3">
                            <label for="supplier_id" class="form-label">Supplier <span class="text-danger">*</span></label>
                            <select name="supplier_id" id="supplier_id" class="form-control" required>
                                <option value="">-- Select Supplier --</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" {{ $replaceing_product->supplier_id == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Old Product --}}
                        <div class="mb-3">
                            <label for="old_product_id" class="form-label">Old Product (Expired) <span class="text-danger">*</span></label>
                            <select name="old_product_id" id="old_product_id" class="form-control" required>
                                <option value="">-- Select Expired Product --</option>
                                @foreach ($expiredProducts as $product)
                                    <option value="{{ $product->id }}"
                                        data-expiry="{{ $product->expired_date }}"
                                        {{ isset($oldProductId) && $oldProductId == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }} ({{ $product->code }}) - Expired: {{ $product->expired_date ? \Carbon\Carbon::parse($product->expired_date)->format('M d, Y') : 'No Date' }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Only showing products that have expired or are about to expire</small>
                            
                            {{-- Old Product Details --}}
                            <div id="old_product_details" class="mt-2 p-3 bg-light rounded d-none">
                                <h6 class="text-danger">Selected Expired Product Details:</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Name:</strong> <span id="old_product_name">-</span></p>
                                        <p class="mb-1"><strong>Code:</strong> <span id="old_product_code">-</span></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Expiry Date:</strong> <span id="old_product_expiry" class="text-danger">-</span></p>
                                        <p class="mb-1"><strong>Status:</strong> <span id="old_product_status" class="badge bg-danger">Expired</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- New Product (Source for Replacement) --}}
                        <div class="mb-3">
                            <label for="new_product_id" class="form-label">New Product (Replace With) <span class="text-danger">*</span></label>
                            <select name="new_product_id" id="new_product_id" class="form-control" required>
                                <option value="">-- Select New Product to Replace With --</option>
                                @foreach ($activeProducts as $product)
                                    <option value="{{ $product->id }}" 
                                            data-name="{{ $product->name }}"
                                            data-code="{{ $product->code }}"
                                            data-expiry="{{ $product->expired_date }}"
                                            data-price="{{ $product->price }}"
                                            data-category="{{ $product->category_id }}"
                                            data-brand="{{ $product->brand_id }}">
                                        {{ $product->name }} ({{ $product->code }}) 
                                        @if($product->expired_date)
                                            - Expires: {{ \Carbon\Carbon::parse($product->expired_date)->format('M d, Y') }}
                                        @else
                                            - No Expiry Date
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">The old product will be updated with this product's details</small>
                            
                            {{-- New Product Details --}}
                            <div id="new_product_details" class="mt-2 p-3 bg-light rounded d-none">
                                <h6 class="text-success">New Product Details (Will Replace Old Product):</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Name:</strong> <span id="new_product_name">-</span></p>
                                        <p class="mb-1"><strong>Code:</strong> <span id="new_product_code">-</span></p>
                                        <p class="mb-1"><strong>Price:</strong> $<span id="new_product_price">-</span></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Expiry Date:</strong> <span id="new_product_expiry">-</span></p>
                                        <p class="mb-1"><strong>Status:</strong> <span id="new_product_status" class="badge bg-success">Fresh</span></p>
                                    </div>
                                </div>
                                <div class="alert alert-warning mt-2">
                                    <i class="fas fa-exclamation-triangle"></i> The old product will be completely updated with these details.
                                </div>
                            </div>
                        </div>

                        {{-- Replacement Date --}}
                        <div class="mb-3">
                            <label for="replacement_date" class="form-label">Replacement Date <span class="text-danger">*</span></label>
                            <input type="date" name="replacement_date" class="form-control" required>
                            <small class="text-muted">Date when the product replacement is performed</small>
                        </div>

                        {{-- Note --}}
                        <div class="mb-3">
                            <label for="note" class="form-label">Note</label>
                            <textarea name="note" id="note" class="form-control" rows="3" placeholder="Write any note about this expiry date update (optional)..."></textarea>
                        </div>

                        {{-- Submit --}}
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Replace Product</button>
                        </div>
                    </form>
                </div>
         </div>
      </div>
   </div>
</div>

<!-- JavaScript for Product Selection and Expiry Date Handling -->
<script>
    // Product details data (populated from server)
    const expiredProducts = @json($expiredProducts ?? []);
    const activeProducts = @json($activeProducts ?? []);

    // Handle old product selection
    document.getElementById('old_product_id').addEventListener('change', function() {
        const selectedId = this.value;
        const detailsDiv = document.getElementById('old_product_details');
        
        if (selectedId) {
            const product = expiredProducts.find(p => p.id == selectedId);
            if (product) {
                document.getElementById('old_product_name').textContent = product.name;
                document.getElementById('old_product_code').textContent = product.code;
                document.getElementById('old_product_expiry').textContent = product.expired_date ? 
                    new Date(product.expired_date).toLocaleDateString('en-US', { 
                        year: 'numeric', month: 'short', day: 'numeric' 
                    }) : 'No Date';
                detailsDiv.classList.remove('d-none');
            }
        } else {
            detailsDiv.classList.add('d-none');
        }
    });

    // Handle new product selection
    document.getElementById('new_product_id').addEventListener('change', function() {
        const selectedId = this.value;
        const detailsDiv = document.getElementById('new_product_details');
        
        if (selectedId) {
            const option = this.options[this.selectedIndex];
            const productName = option.dataset.name;
            const productCode = option.dataset.code;
            const productExpiry = option.dataset.expiry;
            const productPrice = option.dataset.price;
            
            document.getElementById('new_product_name').textContent = productName;
            document.getElementById('new_product_code').textContent = productCode;
            document.getElementById('new_product_price').textContent = productPrice || '0.00';
            document.getElementById('new_product_expiry').textContent = productExpiry ? 
                new Date(productExpiry).toLocaleDateString('en-US', { 
                    year: 'numeric', month: 'short', day: 'numeric' 
                }) : 'No Expiry Date';
            detailsDiv.classList.remove('d-none');
        } else {
            detailsDiv.classList.add('d-none');
        }
    });

    // Set today's date as default for replacement date
    document.addEventListener('DOMContentLoaded', function() {
        const today = new Date().toISOString().split('T')[0];
        document.querySelector('input[name="replacement_date"]').value = today;
        
        // If old product is pre-selected, show its details
        const oldProductSelect = document.getElementById('old_product_id');
        if (oldProductSelect.value) {
            oldProductSelect.dispatchEvent(new Event('change'));
        }
    });

    // Form validation
    document.querySelector('form').addEventListener('submit', function(e) {
        const oldProductId = document.getElementById('old_product_id').value;
        const newProductId = document.getElementById('new_product_id').value;
        
        if (!oldProductId) {
            e.preventDefault();
            alert('Error: Please select an expired product!');
            return false;
        }
        
        if (!newProductId) {
            e.preventDefault();
            alert('Error: Please select a product to replace with!');
            return false;
        }
        
        if (oldProductId === newProductId) {
            e.preventDefault();
            alert('Error: Old product and new product cannot be the same!');
            return false;
        }
        
        // Confirm replacement
        const confirmReplace = confirm('This will replace the old product with the new product details. Are you sure?');
        if (!confirmReplace) {
            e.preventDefault();
            return false;
        }
    });
</script>
@endsection
