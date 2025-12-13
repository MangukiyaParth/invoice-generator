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
         if (!Schema::hasTable('invoice_items')) {
            Schema::create('invoice_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('invoice_id')->constrained('invoices')->onDelete('cascade');
                $table->string('description')->nullable();
                $table->integer('hsn')->nullable();
                $table->decimal('quantity',15, 2)->default(0.00);
                $table->decimal('rate', 15, 2)->default(0.00);
                $table->string('tax_type')->default('none');
                $table->decimal('igst', 15, 2)->default(0.00);
                $table->decimal('sgst', 15, 2)->default(0.00);
                $table->decimal('cgst', 15, 2)->default(0.00);
                $table->decimal('total_amount', 15, 2)->default(0.00);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
    }
};
