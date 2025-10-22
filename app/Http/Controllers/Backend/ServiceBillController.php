<?php

namespace App\Http\Controllers\Backend;

use App\Models\ServiceBill;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Dompdf\Options;
use Barryvdh\DomPDF\Facade\Pdf;

class ServiceBillController extends Controller
{
    public function index()
    {
       $bills = ServiceBill::with('items')->latest()->get(); // eager load items if needed
       return view('admin.backend.service-bill.index', compact('bills'));
    }

    public function create()
    {
        return view('admin.backend.service-bill.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string',
            'mobile' => 'required|string',
            'bill_date' => 'required|date',
            'discount' => 'nullable|numeric',
            'subtotal' => 'required|numeric',
            'signature' => 'nullable|string',
            'items.*.name' => 'required|string',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.total' => 'required|numeric|min:0',
        ]);

        $bill = ServiceBill::create([
            'customer_name' => $validated['customer_name'],
            'mobile' => $validated['mobile'],
            'bill_date' => $validated['bill_date'],
            'discount' => $validated['discount'] ?? 0,
            'subtotal' => $validated['subtotal'],
            'signature' => $validated['signature'] ?? null,
        ]);

        foreach ($validated['items'] as $item) {
            $bill->items()->create($item);
        }
        $notification = array(
            'message' => 'Service bill created successfully!',
            'alert-type' => 'success'
         );
        return redirect()->route('service-bill.index')->with($notification);
    }
    public function show($id)
    {
        $bill = ServiceBill::with('items')->findOrFail($id);
        return view('admin.backend.service-bill.show', compact('bill'));
    }
    public function invoice($id){
        $bill = ServiceBill::with('items')->findOrFail($id);
        $options = new Options();
        $options->set('defaultFont', 'solaimanlipi');
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('fontDir', storage_path('fonts'));
        $options->set('fontCache', storage_path('fonts'));
        $pdf = Pdf::loadView('admin.backend.service-bill.invoice',compact('bill'));
        return $pdf->download('bill-of-'.$bill->mobile.'.pdf');
    }
    public function edit($id)
    {
        $bill = ServiceBill::with('items')->findOrFail($id);
        return view('admin.backend.service-bill.edit', compact('bill'));
    }
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string',
            'mobile' => 'required|string',
            'bill_date' => 'required|date',
            'discount' => 'nullable|numeric',
            'subtotal' => 'required|numeric',
            'items.*.name' => 'required|string',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.total' => 'required|numeric|min:0',
        ]);

        $bill = ServiceBill::findOrFail($id);
        $bill->update([
            'customer_name' => $validated['customer_name'],
            'mobile' => $validated['mobile'],
            'bill_date' => $validated['bill_date'],
            'discount' => $validated['discount'] ?? 0,
            'subtotal' => $validated['subtotal'],
        ]);

        // Delete old items and recreate
        $bill->items()->delete();
        foreach ($validated['items'] as $item) {
            $bill->items()->create($item);
        }
        $notification = array(
            'message' => 'Service bill updated successfully!',
            'alert-type' => 'success'
         );
        return redirect()->route('service-bill.index')->with($notification);
    }

    public function destroy($id)
    {
        $bill = ServiceBill::findOrFail($id);
        $bill->delete(); // This will also delete related items if you’ve set up cascading
        $notification = array(
            'message' => 'Service bill deleted successfully!',
            'alert-type' => 'success'
         );
        return redirect()->route('service-bill.index')->with($notification);
    }


}
