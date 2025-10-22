@extends('admin.admin_master')
@section('admin')
    <div class="content">
        <!-- Start Content-->
        <div class="container-xxl">
            <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                <div class="flex-grow-1">
                    <h4 class="fs-18 fw-semibold m-0">Upcoming Expired Products</h4>
                    <p class="text-muted mb-0">Products that have expired or will expire within 30 days</p>
                </div>
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <a href="{{ route('add.product') }}" class="btn btn-secondary">Add Product</a>
                    </ol>
                </div>
            </div>
            <!-- Datatables  -->
            
            {{-- Debug information (remove after testing) --}}
            @if(session('updated_product_id'))
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="alert alert-success">
                            <h6><i class="fas fa-check-circle"></i> Update Successful!</h6>
                            <p><strong>Product ID:</strong> {{ session('updated_product_id') }}</p>
                            <p><strong>New Expiry Date:</strong> {{ session('new_expiry_date') ? \Carbon\Carbon::parse(session('new_expiry_date'))->format('M d, Y') : 'No Date' }}</p>
                            <p><strong>Replacement Record ID:</strong> {{ session('replacement_record_id') }}</p>
                        </div>
                    </div>
                </div>
            @endif
            
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Products Requiring Attention (Next 30 Days)</h5>
                            <div>
                                <span class="badge bg-danger me-1">Expired</span>
                                <span class="badge bg-danger text-white me-1">This Week</span>
                                <span class="badge bg-warning text-dark me-1">Soon</span>
                                <span class="badge bg-info">This Month</span>
                            </div>
                        </div><!-- end card header -->
                        <div class="card-body">
                            <table id="datatable" class="table table-bordered dt-responsive table-responsive nowrap">
                                <thead>
                                    <tr>
                                        <th>Sl</th>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>Code</th>
                                        <th>Warehouse</th>
                                        <th>Price</th>
                                        <th>In Stock</th>
                                        <th>Expired On</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($upcomingExpiredProducts as $key => $item)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>
                                                @php
                                                    $primaryImage =
                                                        $item->images->first()->image ?? '/upload/no_image.jpg';
                                                @endphp
                                                <img src="{{ asset($primaryImage) }}" alt="img" width="40px">
                                            </td>
                                            <td>{{ $item->name }}</td>
                                            <td><code>{{ $item->code }}</code></td>
                                            <td>{{ $item['warehouse']['name'] }}</td>
                                            <td>${{ number_format($item->price, 2) }}</td>
                                            <td>
                                                @if ($item->product_qty <= 3)
                                                    <span class="badge text-bg-danger">{{ $item->product_qty }}</span>
                                                @else
                                                    <span class="badge text-bg-secondary">{{ $item->product_qty }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($item->expired_date)
                                                    {{ \Carbon\Carbon::parse($item->expired_date)->format('M d, Y') }}
                                                @else
                                                    <span class="text-muted">No Date</span>
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $expiredDate = $item->expired_date ? \Carbon\Carbon::parse($item->expired_date) : null;
                                                    $now = \Carbon\Carbon::now();
                                                    $daysDiff = $expiredDate ? intval($now->diffInDays($expiredDate, false)) : 0; // days until expiry (negative if past)
                                                @endphp
                                                
                                                @if($expiredDate && $expiredDate->isPast())
                                                    <span class="badge bg-danger">Expired</span>
                                                @elseif($expiredDate && $daysDiff <= 7)
                                                    <span class="badge bg-danger text-white">Expires This Week</span>
                                                @elseif($expiredDate && $daysDiff <= 15)
                                                    <span class="badge bg-warning text-dark">Expires Soon</span>
                                                @elseif($expiredDate && $expiredDate->isSameMonth($now))
                                                    <span class="badge bg-info">Expires This Month</span>
                                                @elseif($expiredDate && $daysDiff <= 30)
                                                    <span class="badge bg-primary">Expires Next Month</span>
                                                @else
                                                    <span class="badge bg-secondary">No Expiry</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('replacement-product.create', ['old_product_id' => $item->id]) }}"
                                                    class="btn btn-sm" 
                                                    style="background-color: #28a745; color: white; border: 1px solid #28a745; padding: 5px 10px; text-decoration: none;"
                                                    title="Update expiry date for: {{ $item->name }}">
                                                    Update Expiry
                                                </a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="10" class="text-center py-4">
                                                <div class="text-muted">
                                                    <i class="fas fa-check-circle fa-2x mb-2"></i>
                                                    <p>No upcoming expired products found.</p>
                                                    <small>All products are fresh and within their expiry dates!</small>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- container-fluid -->
    </div> <!-- content -->
    
    <style>
        .badge {
            font-size: 0.875em;
        }
        .table td {
            vertical-align: middle;
        }
        .btn-warning {
            color: #fff;
        }
        .btn-warning:hover {
            background-color: #e0a800;
            border-color: #d39e00;
        }
    </style>

    <script>
        $(document).ready(function() {
            // Initialize DataTable with custom options
            $('#datatable').DataTable({
                "order": [[ 7, "asc" ]], // Sort by expiry date
                "pageLength": 25,
                "language": {
                    "emptyTable": "No upcoming expired products found - all products are fresh!",
                    "info": "Showing _START_ to _END_ of _TOTAL_ products requiring attention",
                    "infoEmpty": "No products requiring attention",
                    "search": "Search products:"
                },
                "columnDefs": [
                    { "orderable": false, "targets": [1, 9] }, // Disable sorting for image and action columns
                    { "width": "60px", "targets": [0, 1] },
                    { "width": "100px", "targets": [9] }
                ]
            });

            // Add tooltips to status badges
            $('[data-bs-toggle="tooltip"]').tooltip();
        });
    </script>
@endsection
