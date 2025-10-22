<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductReplacement;
use App\Models\ProductCategory;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Brand;
use App\Models\WareHouse;
use Carbon\Carbon;

class ReplaceProductController extends Controller
{
    public function index()
    {
        $replacements = ProductReplacement::with(['oldProduct', 'newProduct', 'supplier'])
            ->orderBy('replacement_date', 'desc')
            ->get();
            
        return view('admin.backend.product-replacements.index', compact('replacements'));
    }

    public function show($id)
    {
        try {
            $replacement = ProductReplacement::with([
                'oldProduct', 
                'newProduct', 
                'supplier',
                'originalCategory',
                'originalBrand', 
                'originalWarehouse'
            ])->findOrFail($id);
                
            return view('admin.backend.product-replacements.show', compact('replacement'));
        } catch (\Exception $e) {
            $notification = array(
                'message' => 'ERROR: Replacement record not found or has been deleted.',
                'alert-type' => 'error'
            );
            
            return redirect()->route('replacement.history')->with($notification);
        }
    }

    public function create(Request $request)
    {
        $categories = ProductCategory::latest()->get();
        $brands = Brand::all();
        $suppliers = Supplier::all();
        $warehouses = WareHouse::all();
        $oldProductId = $request->get('old_product_id');
        $replaceing_product = Product::find($oldProductId);

        // Get expired products (products where expired_date is past or will expire within 30 days)
        $expiredProducts = Product::where(function($query) {
            $query->where('expired_date', '<', now())
                  ->orWhere('expired_date', '<=', now()->addDays(30)); // Include products expiring within 30 days
        })
        ->whereNotNull('expired_date')
        ->where('product_qty', '>', 0) // Only products with stock
        ->get();

        // Get all fresh/available products for replacement (exclude expired ones)
        $activeProducts = Product::where(function($query) {
            $query->where('expired_date', '>', now())
                  ->orWhereNull('expired_date'); // Products without expiry date
        })
        ->where('product_qty', '>', 0) // Only products with stock
        ->get();

        // All products for backward compatibility
        $products = Product::all();

        return view('admin.backend.product-replacements.create', compact(
            'replaceing_product', 'warehouses', 'brands', 'categories', 'suppliers', 
            'products', 'oldProductId', 'expiredProducts', 'activeProducts'
        ));
    }
    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'old_product_id' => 'required|exists:products,id',
            'new_product_id' => 'required|exists:products,id|different:old_product_id',
            'replacement_date' => 'required|date',
            'note' => 'nullable|string',
        ]);

        // Additional validation to prevent same product replacement
        if ($request->old_product_id == $request->new_product_id) {
            return redirect()->back()->withErrors([
                'new_product_id' => 'New product must be different from the old product.'
            ])->withInput();
        }

        // Get both products
        $oldProduct = Product::find($request->old_product_id);
        $newProduct = Product::find($request->new_product_id);
        
        if (!$oldProduct || !$newProduct) {
            return redirect()->back()->withErrors([
                'error' => 'One or both selected products could not be found.'
            ])->withInput();
        }
        
        // Store old product details for historical tracking
        $oldProductName = $oldProduct->name;
        $oldProductCode = $oldProduct->code;
        $oldExpiryDate = $oldProduct->expired_date;
        $oldProductPrice = $oldProduct->price;
        $oldProductCategoryId = $oldProduct->category_id;
        $oldProductBrandId = $oldProduct->brand_id;
        $oldProductWarehouseId = $oldProduct->warehouse_id;

        // Check if old product is actually expired or expiring within 30 days
        if ($oldProduct->expired_date && $oldProduct->expired_date > now()->addDays(30)) {
            return redirect()->back()->withErrors([
                'old_product_id' => 'Selected old product is not expired or expiring within 30 days.'
            ])->withInput();
        }

        // Update the old product with ALL details from the new product
        $oldProduct->update([
            'name' => $newProduct->name,
            'code' => $newProduct->code,
            'expired_date' => $newProduct->expired_date,
            'price' => $newProduct->price,
            'category_id' => $newProduct->category_id,
            'brand_id' => $newProduct->brand_id,
            'warehouse_id' => $newProduct->warehouse_id,
            'product_qty' => $oldProduct->product_qty, // Keep original quantity
            // Add any other fields you want to update
        ]);
        
        // Refresh the model to ensure we have the updated data
        $oldProduct->refresh();

        // Create replacement record for tracking
        $replacement = ProductReplacement::create([
            'supplier_id' => $request->supplier_id,
            'old_product_id' => $request->old_product_id,
            'new_product_id' => $request->new_product_id,
            'replacement_date' => $request->replacement_date,
            'note' => ($request->note ? $request->note . ' | ' : '') . 
                     'Original Product: "' . $oldProductName . '" (' . $oldProductCode . ') ' .
                     'replaced with "' . $newProduct->name . '" (' . $newProduct->code . ')',
            // Store original product details
            'original_name' => $oldProductName,
            'original_code' => $oldProductCode,
            'original_expired_date' => $oldExpiryDate,
            'original_price' => $oldProductPrice,
            'original_category_id' => $oldProductCategoryId,
            'original_brand_id' => $oldProductBrandId,
            'original_warehouse_id' => $oldProductWarehouseId,
        ]);

        $notification = array(
            'message' => 'SUCCESS: Product replaced! "' . $oldProductName . '" (ID: ' . $oldProduct->id . ') has been updated to "' . $newProduct->name . '". New expiry: ' . 
                        ($newProduct->expired_date ? Carbon::parse($newProduct->expired_date)->format('M d, Y') : 'No Date'),
            'alert-type' => 'success'
        );
        
        return redirect()->route('expired.product')->with($notification)->with([
            'updated_product_id' => $oldProduct->id,
            'new_expiry_date' => $oldProduct->expired_date,
            'replacement_record_id' => $replacement->id,
            'old_product_name' => $oldProductName,
            'new_product_name' => $newProduct->name
        ]);
    }
}
