<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductCategory;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Supplier;
use App\Models\Brand;
use App\Models\WareHouse;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Carbon\Carbon;

class ProductController extends Controller
{
    public function AllCategory(){
        $category = ProductCategory::latest()->get();
        return view('admin.backend.category.all_category',compact('category'));
    }


    public function StoreCategory(Request $request){

        ProductCategory::insert([
            'category_name' => $request->category_name,
            'category_slug' => strtolower(str_replace(' ','-',$request->category_name)),
        ]);

        $notification = array(
            'message' => 'ProductCategory Inserted Successfully',
            'alert-type' => 'success'
         );
         return redirect()->back()->with($notification);

    }


     public function EditCategory($id){
        $category = ProductCategory::find($id);
        return response()->json($category);
     }


      public function UpdateCategory(Request $request){
        $cat_id = $request->cat_id;

        ProductCategory::find($cat_id)->update([
            'category_name' => $request->category_name,
            'category_slug' => strtolower(str_replace(' ','-',$request->category_name)),
        ]);

        $notification = array(
            'message' => 'ProductCategory Updated Successfully',
            'alert-type' => 'success'
         );
         return redirect()->back()->with($notification);

    }


    public function DeleteCategory($id){

        ProductCategory::find($id)->delete();
        $notification = array(
            'message' => 'ProductCategory Delete Successfully',
            'alert-type' => 'success'
         );
         return redirect()->back()->with($notification);

    }


    ///// Add Product all Methods


    public function AllProduct(){
        $allData = Product::orderBy('id','desc')->get();
        return view('admin.backend.product.product_list',compact('allData'));
    }


    public function AddProduct(){
        $categories = ProductCategory::all();
        $brands = Brand::all();
        $suppliers = Supplier::all();
        $warehouses = WareHouse::all();
        return view('admin.backend.product.add_product',compact('categories','brands','suppliers','warehouses'));
    }


    public function StoreProduct(Request $request){

        try {
            $product = Product::create([
                'name' => $request->name,
                'code' => $request->code,
                'category_id' => $request->category_id,
                'brand_id' => $request->brand_id,
                'warehouse_id' => $request->warehouse_id,
                'supplier_id' => $request->supplier_id,
                'price' => $request->price,
                'stock_alert' => $request->stock_alert,
                'note' => $request->note,
                'product_qty' => $request->product_qty,
                'status' => $request->status,
                'expired_date' => $request->expired_date,
                'created_at' => now(),
            ]);

            $product_id = $product->id;

        /// Multiple Image Upload
        if ($request->hasFile('image')) {
           foreach($request->file('image') as $img) {
           $manager = new ImageManager(new Driver());
           $name_gen = hexdec(uniqid()).'.'.$img->getClientOriginalExtension();
           $imgs = $manager->read(file_get_contents($img));
           $imgs->resize(150,150)->save(public_path('upload/productimg/'.$name_gen));
           $save_url = 'upload/productimg/'.$name_gen;

           ProductImage::create([
            'product_id' => $product_id,
            'image' => $save_url
           ]);
           }
        }

        $notification = array(
            'message' => 'Product Inserted Successfully',
            'alert-type' => 'success'
         );
         return redirect()->route('all.product')->with($notification);

        } catch (\Exception $e) {
            $notification = array(
                'message' => 'Error creating product: ' . $e->getMessage(),
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification)->withInput();
        }

    }


    public function EditProduct($id){
        $editData = Product::find($id);
        $categories = ProductCategory::all();
        $brands = Brand::all();
        $suppliers = Supplier::all();
        $warehouses = WareHouse::all();
        $multiimg = ProductImage::where('product_id',$id)->get();
        return view('admin.backend.product.edit_product',compact('categories','brands','suppliers','warehouses','editData','multiimg'));
    }


     public function UpdateProduct(Request $request){
        $pro_id = $request->id;

        $product = Product::findOrFail($pro_id);

        $product->name = $request->name;
        $product->code = $request->code;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;
        $product->price = $request->price;
        $product->stock_alert = $request->stock_alert;
        $product->note = $request->note;
        $product->warehouse_id = $request->warehouse_id;
        $product->supplier_id = $request->supplier_id;
        $product->product_qty = $request->product_qty;
        $product->status = $request->status;
        $product->expired_date = $request->expired_date;
        $product->save();

        if ($request->hasFile('image')) {
            foreach($request->file('image') as $img) {
            $manager = new ImageManager(new Driver());
            $name_gen = hexdec(uniqid()).'.'.$img->getClientOriginalExtension();
            $imgs = $manager->read(file_get_contents($img));
            $imgs->resize(150,150)->save(public_path('upload/productimg/'.$name_gen));

            $product->images()->create([
                'image' => 'upload/productimg/'.$name_gen
            ]);

            }
         }


        if ($request->has('remove_image')) {
            foreach($request->remove_image as $removeImageId) {
                $img = ProductImage::find($removeImageId);
                if ($img ) {
                    if (file_exists(public_path($img->image))) {
                       unlink(public_path($img->image));
                    }
                    $img->delete();
                }
            }
        }

        $notification = array(
            'message' => 'Product Updaetd Successfully',
            'alert-type' => 'success'
         );
         return redirect()->route('all.product')->with($notification);

     }


    public function DeleteProduct($id){
        $product = Product::findOrFail($id);

        /// Delete associated images
        $images = ProductImage::where('product_id',$id)->get();
        foreach($images as $img){
            $imagePath = public_path($img->image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        // Delete image from records
        ProductImage::where('product_id',$id)->delete();

        // Delete the product
        $product->delete();

           $notification = array(
            'message' => 'Product Deleted Successfully',
            'alert-type' => 'success'
         );
         return redirect()->back()->with($notification);

    }
    public function DetailsProduct($id){
        $product = Product::findOrFail($id);
        return view('admin.backend.product.details_product',compact('product'));
    }
    public function getUpcomingExpiredProductList(){
        $upcomingExpiredProducts = Product::whereDate('expired_date', '<=', Carbon::now()->addDays(30))
            ->where('product_qty', '>', 0)
            ->orderBy('expired_date', 'asc')
            ->get();
            
        // Add additional data for each product
        foreach ($upcomingExpiredProducts as $product) {
            $product->days_until_expiry = $product->expired_date ? 
                Carbon::parse($product->expired_date)->diffInDays(Carbon::now(), false) : null;
            $product->is_already_expired = $product->expired_date && 
                Carbon::parse($product->expired_date)->isPast();
        }
        
        return view('admin.backend.product.upcoming-expired-product', compact('upcomingExpiredProducts'));
    }
}
