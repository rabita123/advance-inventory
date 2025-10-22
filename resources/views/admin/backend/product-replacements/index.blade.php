@extends('admin.admin_master')

@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

<div class="page-content"> 
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Product Replacement History</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">Replacement History</li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->

    <div class="card">
        <div class="card-body">
            <div class="d-lg-flex align-items-center mb-4 gap-3">
                <div class="position-relative">
                    <input type="text" class="form-control ps-5 radius-30" placeholder="Search Replacements"> 
                    <span class="position-absolute top-50 product-show translate-middle-y"><i class="bx bx-search"></i></span>
                </div>
                <div class="ms-auto">
                    <a href="{{ route('replacement-product.create') }}" class="btn btn-primary radius-30 mt-2 mt-lg-0">
                        <i class="bx bxs-plus-square"></i>Add New Replacement
                    </a>
                </div>
            </div>

            <div class="table-responsive">
                <table id="datatable" class="table table-bordered dt-responsive table-responsive nowrap">
                    <thead>
                        <tr>
                            <th>Sl</th>
                            <th>Replacement Date</th>
                            <th>Old Product</th>
                            <th>New Product</th>
                            <th>Supplier</th>
                            <th>Status</th>
                            <th>Note</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($replacements as $key => $replacement)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ \Carbon\Carbon::parse($replacement->replacement_date)->format('M d, Y') }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @php
                                        $oldProductImage = '/upload/no_image.jpg';
                                    @endphp
                                    <img src="{{ asset($oldProductImage) }}" alt="Old Product" width="40px" class="me-2">
                                    <div>
                                        @if($replacement->original_name)
                                            <strong>{{ $replacement->original_name }}</strong><br>
                                            <small class="text-muted">Code: {{ $replacement->original_code }}</small>
                                        @else
                                            <strong>{{ $replacement->oldProduct ? $replacement->oldProduct->name : 'Product Deleted' }}</strong><br>
                                            <small class="text-muted">Code: {{ $replacement->oldProduct ? $replacement->oldProduct->code : 'N/A' }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @php
                                        $newProductImage = '/upload/no_image.jpg';
                                        if($replacement->newProduct && $replacement->newProduct->images && $replacement->newProduct->images->first()) {
                                            $newProductImage = $replacement->newProduct->images->first()->image;
                                        }
                                    @endphp
                                    <img src="{{ asset($newProductImage) }}" alt="New Product" width="40px" class="me-2">
                                    <div>
                                        <strong>{{ $replacement->newProduct ? $replacement->newProduct->name : 'Product Deleted' }}</strong><br>
                                        <small class="text-muted">Code: {{ $replacement->newProduct ? $replacement->newProduct->code : 'N/A' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $replacement->supplier ? $replacement->supplier->name : 'Supplier Deleted' }}</td>
                            <td>
                                @php
                                    $createdDate = \Carbon\Carbon::parse($replacement->created_at);
                                    $now = \Carbon\Carbon::now();
                                    $daysDiff = intval($createdDate->diffInDays($now, false));
                                @endphp
                                
                                @if($daysDiff == 0)
                                    <span class="badge bg-success">Replaced Today</span>
                                @elseif($daysDiff <= 7)
                                    <span class="badge bg-info">Replaced {{ $daysDiff }} days ago</span>
                                @else
                                    <span class="badge bg-secondary">Replaced {{ $daysDiff }} days ago</span>
                                @endif
                            </td>
                            <td>
                                @if($replacement->note)
                                    <span class="text-truncate d-inline-block" style="max-width: 200px;" title="{{ $replacement->note }}">
                                        {{ $replacement->note }}
                                    </span>
                                @else
                                    <span class="text-muted">No note</span>
                                @endif
                            </td>
                            <td>
                                <a title="View Details" href="{{ route('replacement.show', $replacement->id) }}" class="btn btn-info btn-sm">
                                    <span class="mdi mdi-eye-circle mdi-18px"></span>
                                </a>
                                
                                <a title="View Updated Product" href="{{ route('details.product', $replacement->old_product_id) }}" class="btn btn-success btn-sm">
                                    <span class="mdi mdi-package-variant mdi-18px"></span>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function (){
        $('#datatable').DataTable({
            "pageLength": 25,
            "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            "order": [[ 1, "desc" ]], // Order by replacement date descending
            "columnDefs": [
                { "orderable": false, "targets": [7] } // Disable ordering on action column
            ]
        });
    });
</script>

@endsection