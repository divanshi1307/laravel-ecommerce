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
        Schema::table('product_attributes', function (Blueprint $table) {
            $table->unsignedBigInteger('baby_weight_id')->nullable()->after('attribute_value');
            $table->unsignedBigInteger('age_group_id')->nullable()->after('baby_weight_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_attributes', function (Blueprint $table) {
            $table->dropColumn(['baby_weight_id', 'age_group_id']);
        });
    }
};
