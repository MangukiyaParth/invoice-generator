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
        if (!Schema::hasTable('companies')) {
            Schema::create('companies', function (Blueprint $table) {
                $table->id();
                $table->string('logo');
                $table->string('name');
                $table->string('email');
                $table->longText('address');
                $table->string('city');
                $table->string('state');
                $table->string('country');
                $table->string('zip_code');
                $table->string('gst_number');
                $table->string('currency');
                $table->string('lut_number')->nullable();
                $table->string('euid_number')->nullable();
                $table->string('terms_conditions')->nullable();
                $table->string('notes')->nullable();
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
        Schema::dropIfExists('companies');
    }
};
