@extends('admin.admin_master')

@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

<div class="page-content"> 
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Replacement Details</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('replacement.history') }}">Replacement History</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Details</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <a href="{{ route('replacement.history') }}" class="btn btn-secondary">
                <i class="bx bx-arrow-back"></i> Back to History
            </a>
        </div>
    </div>
    <!--end breadcrumb-->

    @if(!$replacement)
        <div class="alert alert-danger">
            <h5>Error</h5>
            <p>The replacement record you're looking for could not be found. It may have been deleted.</p>
            <a href="{{ route('replacement.history') }}" class="btn btn-primary">Back to Replacement History</a>
        </div>
    @else

    <div class="row">
        <!-- Explanation Card -->
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6><i class="bx bx-info-circle"></i> Product Replacement History</h6>
                        <p class="mb-0">
                            This page shows the replacement details: <strong>"Original Product"</strong> displays the product as it was before replacement (Mac), 
                            and <strong>"Source Product"</strong> shows the product that provided the new details (Asus). 
                            The original product was updated with the source product's information.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Replacement Summary Card -->
        <div class="col-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bx bx-refresh text-primary"></i> Replacement Summary
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Replacement ID:</strong></td>
                                    <td>#{{ $replacement->id }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Replacement Date:</strong></td>
                                    <td>{{ \Carbon\Carbon::parse($replacement->replacement_date)->format('M d, Y') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Supplier:</strong></td>
                                    <td>{{ $replacement->supplier ? $replacement->supplier->name : 'Supplier Deleted' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Created At:</strong></td>
                                    <td>{{ \Carbon\Carbon::parse($replacement->created_at)->format('M d, Y h:i A') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            @if($replacement->note)
                            <div class="alert alert-info">
                                <strong>Note:</strong><br>
                                {{ $replacement->note }}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Original Product Details (Before Replacement) -->
        <div class="col-12 col-lg-6">
            <div class="card h-100">
                <div class="card-header bg-danger text-white">
                    <h5 class="card-title mb-0">
                        <i class="bx bx-package"></i> Original Product (Before Replacement)
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        @php
                            // For original product, we'll use a placeholder since we don't store original images
                            $originalProductImage = '/upload/no_image.jpg';
                        @endphp
                        <img src="{{ asset($originalProductImage) }}" alt="Original Product" class="img-fluid rounded" style="max-height: 200px;">
                    </div>
                    
                    @if($replacement->original_name)
                    <table class="table table-borderless">
                        <tr>
                            <td><strong>Name:</strong></td>
                            <td>{{ $replacement->original_name }}</td>
                        </tr>
                        <tr>
                            <td><strong>Code:</strong></td>
                            <td><code>{{ $replacement->original_code }}</code></td>
                        </tr>
                        <tr>
                            <td><strong>Category:</strong></td>
                            <td>{{ $replacement->originalCategory->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Brand:</strong></td>
                            <td>{{ $replacement->originalBrand->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Warehouse:</strong></td>
                            <td>{{ $replacement->originalWarehouse->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Price:</strong></td>
                            <td>${{ number_format($replacement->original_price, 2) }}</td>
                        </tr>
                        <tr>
                            <td><strong>Original Expiry:</strong></td>
                            <td>
                                @if($replacement->original_expired_date)
                                    {{ \Carbon\Carbon::parse($replacement->original_expired_date)->format('M d, Y') }}
                                    @php
                                        $expiredDate = \Carbon\Carbon::parse($replacement->original_expired_date);
                                        $now = \Carbon\Carbon::now();
                                        $daysDiff = intval($now->diffInDays($expiredDate, false));
                                    @endphp
                                    
                                    @if($expiredDate->isPast())
                                        <span class="badge bg-danger ms-2">Was Expired {{ abs($daysDiff) }} days ago</span>
                                    @else
                                        <span class="badge bg-warning ms-2">Would expire in {{ $daysDiff }} days</span>
                                    @endif
                                @else
                                    <span class="text-muted">No Expiry Date</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                    @else
                    <div class="alert alert-warning">
                        <strong>Warning:</strong> Original product details not available. This may be an old replacement record.
                    </div>
                    @endif

                    <div class="text-center mt-3">
                        <span class="badge bg-danger fs-6">This was the original product before replacement</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Source Product Details (What it was replaced with) -->
        <div class="col-12 col-lg-6">
            <div class="card h-100">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0">
                        <i class="bx bx-package"></i> Source Product (Used for Update)
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        @php
                            $newProductImage = '/upload/no_image.jpg';
                            if($replacement->newProduct && $replacement->newProduct->images && $replacement->newProduct->images->first()) {
                                $newProductImage = $replacement->newProduct->images->first()->image;
                            }
                        @endphp
                        <img src="{{ asset($newProductImage) }}" alt="New Product" class="img-fluid rounded" style="max-height: 200px;">
                    </div>
                    
                    @if($replacement->newProduct)
                    <table class="table table-borderless">
                        <tr>
                            <td><strong>Name:</strong></td>
                            <td>{{ $replacement->newProduct->name }}</td>
                        </tr>
                        <tr>
                            <td><strong>Code:</strong></td>
                            <td><code>{{ $replacement->newProduct->code }}</code></td>
                        </tr>
                        <tr>
                            <td><strong>Category:</strong></td>
                            <td>{{ $replacement->newProduct->category->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Brand:</strong></td>
                            <td>{{ $replacement->newProduct->brand->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Warehouse:</strong></td>
                            <td>{{ $replacement->newProduct->warehouse->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Price:</strong></td>
                            <td>${{ number_format($replacement->newProduct->price, 2) }}</td>
                        </tr>
                        <tr>
                            <td><strong>Expiry Date:</strong></td>
                            <td>
                                @if($replacement->newProduct->expired_date)
                                    {{ \Carbon\Carbon::parse($replacement->newProduct->expired_date)->format('M d, Y') }}
                                    @php
                                        $expiredDate = \Carbon\Carbon::parse($replacement->newProduct->expired_date);
                                        $now = \Carbon\Carbon::now();
                                        $daysDiff = intval($now->diffInDays($expiredDate, false));
                                    @endphp
                                    
                                    @if($expiredDate->isPast())
                                        <span class="badge bg-danger ms-2">Expired {{ abs($daysDiff) }} days ago</span>
                                    @else
                                        <span class="badge bg-success ms-2">{{ $daysDiff }} days left</span>
                                    @endif
                                @else
                                    <span class="text-muted">No Expiry Date</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                    @else
                    <div class="alert alert-warning">
                        <strong>Warning:</strong> The source product has been deleted from the system.
                    </div>
                    @endif

                    <div class="text-center mt-3">
                        <span class="badge bg-info fs-6">Source product data used for the update</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="row mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center">
                    @if($replacement && $replacement->old_product_id)
                        <a href="{{ route('details.product', $replacement->old_product_id) }}" class="btn btn-primary me-2">
                            <i class="bx bx-show"></i> View Updated Product
                        </a>
                        <a href="{{ route('replacement-product.create', ['old_product_id' => $replacement->old_product_id]) }}" class="btn btn-warning me-2">
                            <i class="bx bx-refresh"></i> Replace Again
                        </a>
                    @endif
                    <a href="{{ route('replacement.history') }}" class="btn btn-secondary">
                        <i class="bx bx-list-ul"></i> Back to History
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

@endsection