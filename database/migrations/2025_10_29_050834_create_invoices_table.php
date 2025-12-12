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
        if (!Schema::hasTable('invoices')) {
            Schema::create('invoices', function (Blueprint $table) {
                $table->id();
                $table->string('invoice_number');
                $table->integer('max_number')->default(0);
                $table->date('invoice_date');
                $table->date('due_date');
                $table->foreignId('company')->constrained('companies')->onDelete('cascade');
                $table->foreignId('customer')->constrained('customers')->onDelete('cascade');
                $table->string('currency');
                $table->string('terms')->nullable();
                $table->decimal('paid_amount', 8, 2)->default(0.00);
                $table->integer('type')->default(1);
                $table->integer('created_by')->default(0);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
