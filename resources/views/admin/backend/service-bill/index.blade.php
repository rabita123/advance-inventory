@extends('admin.admin_master')
@section('admin')
    <div class="content">

        <!-- Start Content-->
        <div class="container-xxl">

            <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                <div class="flex-grow-1">
                    <h4 class="fs-18 fw-semibold m-0">All Service Bill</h4>
                </div>

                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <a href="{{ route('service-bill.create') }}" class="btn btn-secondary">Add Service Bill</a>
                    </ol>
                </div>
            </div>

            <!-- Datatables  -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                        </div><!-- end card header -->
                        <div class="card-body">
                            <table id="datatable" class="table table-bordered dt-responsive table-responsive nowrap">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Customer</th>
                                        <th>Mobile</th>
                                        <th>Date</th>
                                        <th>Discount (%)</th>
                                        <th>Subtotal</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($bills as $bill)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $bill->customer_name }}</td>
                                            <td>{{ $bill->mobile }}</td>
                                            <td>{{ $bill->bill_date }}</td>
                                            <td>{{ $bill->discount }}</td>
                                            <td>{{ number_format($bill->subtotal, 2) }}</td>
                                            <td>
                                                <a href="{{ route('service-bill.show', $bill->id) }}" title="Show"
                                                    class="btn btn-sm btn-info"><span class="mdi mdi-eye-circle mdi-18px"></span></a>
                                                <a href="{{ route('service-bill.edit', $bill->id) }}" title="Edit"
                                                    class="btn btn-sm btn-warning"><span class="mdi mdi-book-edit mdi-18px"></span></a>
                                                    <a href="{{ route('service-bill.invoice',$bill->id) }}" title="Invoice"
                                                        class="btn btn-primary btn-sm"> <span class="mdi mdi-download-circle mdi-18px"></span> </a>
                                                <a href="{{ route('service-bill.destroy', $bill->id) }}" title="Delete" id="delete"
                                                    class="btn btn-sm btn-danger"><span class="mdi mdi-delete-circle  mdi-18px"></span></a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7">No service bills found.</td>
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
@endsection
