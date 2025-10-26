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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('product name')->nullable();
            $table->longText('description')->default('product description')->nullable();
            $table->string('image')->default(null)->nullable();
            $table->string('file')->default(null)->nullable();
            $table->boolean('hide')->default(false)->nullable();
            $table->string('seo_name')->default('product seo_name')->nullable();
            $table->string('seo_description')->default('product description')->nullable();
            $table->softDeletes();
            $table->foreignId('subcategory_id')->constrained()->on('subcategories');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};