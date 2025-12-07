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
        Schema::table('cart_items', function (Blueprint $table) {
            $table->unsignedBigInteger('variant_id')->nullable()->after('product_id');
            $table->decimal('price', 10, 2)->nullable()->after('variant_id');
            $table->decimal('original_price', 10, 2)->nullable()->after('price');
            $table->integer('discount')->nullable()->after('original_price');
            $table->string('image')->nullable()->after('discount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropColumn([
                'variant_id',
                'price',
                'original_price',
                'discount',
                'image'
            ]);
        });
    }
};
