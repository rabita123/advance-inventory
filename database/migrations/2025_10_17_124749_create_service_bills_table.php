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
        Schema::create('service_bills', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->string('mobile')->nullable();
            $table->date('bill_date');
            $table->decimal('discount', 5, 2)->default(0);
            $table->decimal('subtotal', 10, 2);
            $table->string('signature')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_bills');
    }
};
