@extends('admin.admin_master')
@section('admin')
    <div class="content">

        <!-- Start Content-->
        <div class="container-xxl">

            <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                <div class="flex-grow-1">
                    <h4 class="fs-18 fw-semibold m-0">Showing Service Bill</h4>
                </div>

                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <a href="{{ route('service-bill.index') }}" class="btn btn-secondary">Back Service Bill</a>
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
                            <h4>Service Bill Details</h4>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Customer Name</th>
                                    <td>{{ $bill->customer_name }}</td>
                                </tr>
                                <tr>
                                    <th>Customer Mobile</th>
                                    <td>{{ $bill->mobile }}</td>
                                </tr>
                                <tr>
                                    <th>Bill Date</th>
                                    <td>{{ $bill->bill_date }}</td>
                                </tr>
                                <tr>
                                    <th>Discount (%)</th>
                                    <td>{{ $bill->discount }}</td>
                                </tr>
                                <tr>
                                    <th>Subtotal</th>
                                    <td>{{ number_format($bill->subtotal, 2) }}</td>
                                </tr>

                            </table>

                            <h5>Service Items</h5>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Item Name</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($bill->items as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $item->qty }}</td>
                                            <td>{{ number_format($item->price, 2) }}</td>
                                            <td>{{ number_format($item->total, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>

                    </div>
                </div>
            </div>
        </div> <!-- container-fluid -->
    </div> <!-- content -->
@endsection
