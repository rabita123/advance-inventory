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
            $table->date('new_product_code')->nullable()->after('replacement_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_replacements', function (Blueprint $table) {
            $table->dropColumn('new_product_code');
        });
    }
};
