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
            $table->foreignId('store_id')->nullable()->constrained('stores')->onDelete('cascade');
            
            // Basic product info
            $table->string('title', 500)->nullable();
            $table->string('asin', 150)->unique();
            $table->string('url_amazon', 255)->nullable();
            $table->string('image', 1500)->nullable();
            $table->string('slug')->unique()->nullable();
            
            // Pricing and stock info
            $table->string('buy_box_percentage_amazon_30_days', 50)->nullable();
            $table->integer('buy_box_eligible_offer_count_new_fba')->nullable();
            $table->decimal('amazon_current_price', 10, 2)->nullable();
            $table->integer('amazon_stock')->nullable();
            $table->decimal('list_price_current', 10, 2)->nullable();
            $table->decimal('list_price_30_days_avg', 10, 2)->nullable();
            $table->integer('live_offers_fba')->nullable();
            $table->integer('live_offers_fbm')->nullable();
            
            // Categories
            $table->string('categories_root', 255)->nullable();
            $table->string('categories_sub', 255)->nullable();
            $table->text('categories_tree')->nullable();
            
            // Product details
            $table->string('launchpad', 100)->nullable();
            $table->string('manufacturer', 255)->nullable();
            $table->string('unit_count_value', 100)->nullable();
            $table->string('unit_count_type', 100)->nullable();
            $table->string('material', 255)->nullable();
            $table->string('item_type', 255)->nullable();
            $table->integer('number_of_items')->nullable();
            
            // Video info
            $table->integer('video_count')->nullable();
            $table->boolean('has_main_video')->nullable();
            $table->text('main_videos')->nullable();
            $table->text('additional_videos')->nullable();
            
            // Dimensions and weight
            $table->decimal('package_dimension_cm3', 10, 2)->nullable();
            $table->decimal('package_weight_g', 10, 2)->nullable();
            $table->integer('package_quantity')->nullable();
            $table->decimal('item_dimension_cm3', 10, 2)->nullable();
            $table->decimal('item_length_cm', 10, 2)->nullable();
            $table->decimal('item_width_cm', 10, 2)->nullable();
            $table->decimal('item_height_cm', 10, 2)->nullable();
            $table->decimal('item_weight_g', 10, 2)->nullable();
            
            // Other product attributes
            $table->boolean('batteries_included')->nullable();
            $table->string('hazardous_materials', 255)->nullable();
            
            // Description
            $table->text('description')->nullable();
            
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
