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
        Schema::create('menu_sections', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('display_order')->default(0);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
        Schema::create('menu_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('menu_section_id')->constrained()->cascadeOnDelete();
            $table->text('description')->nullable();
            $table->integer('display_order')->default(0);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
        Schema::create('menu_subcategories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('item_count')->default(0);
            $table->integer('display_order')->default(0);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->foreignId('menu_category_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->decimal('price', 8, 2);
            $table->foreignId('menu_subcategory_id')->constrained()->cascadeOnDelete();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->boolean('has_offer')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_sections');
        Schema::dropIfExists('menu_categories');
        Schema::dropIfExists('menu_subcategories');
        Schema::dropIfExists('menu_items');
    }
};
