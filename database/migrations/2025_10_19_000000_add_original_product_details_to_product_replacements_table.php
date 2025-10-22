<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('product_replacements', function (Blueprint $table) {
            $table->string('original_name')->nullable()->after('note');
            $table->string('original_code')->nullable()->after('original_name');
            $table->date('original_expired_date')->nullable()->after('original_code');
            $table->decimal('original_price', 8, 2)->nullable()->after('original_expired_date');
            $table->foreignId('original_category_id')->nullable()->after('original_price');
            $table->foreignId('original_brand_id')->nullable()->after('original_category_id');
            $table->foreignId('original_warehouse_id')->nullable()->after('original_brand_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_replacements', function (Blueprint $table) {
            $table->dropColumn([
                'original_name',
                'original_code', 
                'original_expired_date',
                'original_price',
                'original_category_id',
                'original_brand_id',
                'original_warehouse_id'
            ]);
        });
    }
};