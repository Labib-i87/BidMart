<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id('pid');
            $table->string('product_name');
            $table->string('description');
            $table->enum('status', array('offline', 'online', 'bidding', 'carted', 'sold'))->default('online');
            $table->string('image_path')->unique();
            $table->decimal('start_price');
            $table->decimal('current_price')->default(0);
            $table->decimal('buyout_price');
            $table->integer('sold_by');
            $table->integer('bought_by')->default(0);
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