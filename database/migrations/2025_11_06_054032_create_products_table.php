<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('subcategory_id')->nullable();
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->unsignedBigInteger('gst_id')->nullable();

            $table->string('title');
            $table->string('product_item_code')->nullable();
            $table->enum('product_type', ['simple', 'variant'])->default('simple');
            $table->longText('description')->nullable();
            $table->longText('specifications')->nullable(); 
            $table->string('images')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->decimal('special_price', 10, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('highlights')->nullable();
            $table->integer('sort_no')->default(0);
            $table->integer('stock_quantity')->nullable()->default(0);
            $table->enum('stock_status', ['in_stock', 'out_of_stock'])->nullable()->default('in_stock');
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('seo_image')->nullable();
            $table->string('meta_tags')->nullable();
            $table->timestamps();
            $table->softDeletes();
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
