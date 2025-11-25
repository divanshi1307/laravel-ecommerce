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
        Schema::create('product_attributes_relations', function (Blueprint $table) {
            $table->id();
            $table->text('json')->nullable();                      
            $table->string('value')->nullable();                   
            $table->string('value_attribute_ids')->nullable(); 
            $table->integer('quantity')->default(0);                   
            $table->decimal('original_price', 10, 2)->nullable();   
            $table->decimal('price', 10, 2)->nullable();          
            $table->string('image')->nullable();                  
            $table->unsignedBigInteger('product_id');            
            $table->boolean('is_default')->default(0);            
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_attributes_relations');
    }
};
