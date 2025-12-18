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
        Schema::table('products', function (Blueprint $table) {
            $table->string('bottom_images')->nullable()->after('images');
            $table->string('tags')->nullable()->after('bottom_images');
            $table->date('manufacture_date')->nullable()->after('tags');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('bottom_images','tags','manufacture_date');
        });
    }
};
