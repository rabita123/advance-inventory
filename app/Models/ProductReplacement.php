<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductReplacement extends Model
{
    protected $guarded = [];
    
    protected $fillable = [
        'supplier_id',
        'old_product_id', 
        'new_product_id',
        'replacement_date',
        'note',
        'original_name',
        'original_code',
        'original_expired_date',
        'original_price',
        'original_category_id',
        'original_brand_id',
        'original_warehouse_id'
    ];
    
    public function oldProduct()
    {
        return $this->belongsTo(Product::class, 'old_product_id');
    }

    public function newProduct()
    {
        return $this->belongsTo(Product::class, 'new_product_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function originalCategory()
    {
        return $this->belongsTo(ProductCategory::class, 'original_category_id');
    }

    public function originalBrand()
    {
        return $this->belongsTo(Brand::class, 'original_brand_id');
    }

    public function originalWarehouse()
    {
        return $this->belongsTo(WareHouse::class, 'original_warehouse_id');
    }
}
