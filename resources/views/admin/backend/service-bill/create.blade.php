@extends('admin.admin_master')
@section('admin')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <div class="content">
        <!-- Start Content-->
        <div class="container-xxl">

            <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                <div class="flex-grow-1">
                    <h4 class="fs-18 fw-semibold m-0">Add Customer</h4>
                </div>

                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item active">Add Customer</li>
                    </ol>
                </div>
            </div>
            <!-- Form Validation -->
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Add Customer</h5>
                        </div><!-- end card header -->

                        <div class="card-body">
                            <form id="myForm" action="{{ route('service-bill.store') }}" method="post" class="row g-3"
                                enctype="multipart/form-data">
                                @csrf


                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label>Customer Name</label>
                                        <input type="text" name="customer_name" class="form-control" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label>Mobile No</label>
                                        <input type="text" name="mobile" class="form-control" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label>Date</label>
                                        <input type="date" name="bill_date" class="form-control" required>
                                    </div>
                                </div>

                                <table class="table table-bordered" id="serviceTable">
                                    <thead>
                                        <tr>
                                            <th>Item Name</th>
                                            <th>Quantity</th>
                                            <th>Price</th>
                                            <th>Total</th>
                                            <th><button type="button" class="btn btn-sm btn-success"
                                                    onclick="addRow()">Add</button></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><input type="text" name="items[0][name]" class="form-control" required>
                                            </td>
                                            <td><input type="number" name="items[0][qty]" class="form-control qty"
                                                    required></td>
                                            <td><input type="number" name="items[0][price]" class="form-control price"
                                                    required></td>
                                            <td><input type="number" name="items[0][total]" class="form-control total"
                                                    readonly></td>
                                            <td><button type="button" class="btn btn-sm btn-danger"
                                                    onclick="removeRow(this)">Delete</button></td>
                                        </tr>
                                    </tbody>
                                </table>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label>Discount (%)</label>
                                        <input type="number" name="discount" class="form-control" value="0">
                                    </div>
                                    <div class="col-md-6">
                                        <label>Subtotal</label>
                                        <input type="number" name="subtotal" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button class="btn btn-primary" type="submit">Save Change</button>
                                </div>
                            </form>
                        </div> <!-- end card-body -->
                    </div> <!-- end card-->
                </div> <!-- end col -->
            </div>
        </div> <!-- container-fluid -->
    </div>
    <script>
let rowIndex = 1;

function addRow() {
  const table = document.getElementById("serviceTable").getElementsByTagName('tbody')[0];
  const newRow = table.insertRow();

  newRow.innerHTML = `
    <td><input type="text" name="items[${rowIndex}][name]" class="form-control" required></td>
    <td><input type="number" name="items[${rowIndex}][qty]" class="form-control qty" required></td>
    <td><input type="number" name="items[${rowIndex}][price]" class="form-control price" required></td>
    <td><input type="number" name="items[${rowIndex}][total]" class="form-control total" readonly></td>
    <td><button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this)">Delete</button></td>
  `;
  rowIndex++;
  attachListeners();
}

function removeRow(button) {
  const row = button.closest("tr");
  row.remove();
  calculateSubtotal();
}

function attachListeners() {
  const qtyInputs = document.querySelectorAll(".qty");
  const priceInputs = document.querySelectorAll(".price");
  const discountInput = document.querySelector("[name='discount']");

  qtyInputs.forEach(input => {
    input.addEventListener("input", calculateRowTotal);
  });

  priceInputs.forEach(input => {
    input.addEventListener("input", calculateRowTotal);
  });

  // 🔥 Add this line to trigger subtotal recalculation when discount changes
  if (discountInput) {
    discountInput.addEventListener("input", calculateSubtotal);
  }
}


function calculateRowTotal() {
  const row = this.closest("tr");
  const qty = parseFloat(row.querySelector(".qty").value) || 0;
  const price = parseFloat(row.querySelector(".price").value) || 0;
  const total = qty * price;
  row.querySelector(".total").value = total.toFixed(2);
  calculateSubtotal();
}

function calculateSubtotal() {
  let subtotal = 0;
  document.querySelectorAll(".total").forEach(input => {
    subtotal += parseFloat(input.value) || 0;
  });

  const discount = parseFloat(document.querySelector("[name='discount']").value) || 0;
  const discountedTotal = subtotal - (subtotal * discount / 100);

  document.querySelector("[name='subtotal']").value = discountedTotal.toFixed(2);
}

// Attach listeners on initial load
document.addEventListener("DOMContentLoaded", attachListeners);
</script>
@endsection
