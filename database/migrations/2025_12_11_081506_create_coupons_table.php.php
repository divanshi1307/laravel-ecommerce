<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code', 250)->unique();
            $table->string('description', 250)->nullable();
            $table->string('amt', 150); 
            $table->enum('type', ['fixed', 'percent']);
            $table->integer('uses_per_user')->nullable();
            $table->string('min_order_total', 150)->nullable();  
            $table->string('max_discount', 150)->nullable();
            $table->integer('status')->default(1);
            $table->integer('public')->default(1);
            $table->integer('product_specific')->default(0);
            $table->unsignedBigInteger('product_id')->nullable();
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
