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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('order_number')->after('id');
            $table->string('status')->default('pending')->after('order_number');
            $table->string('customer_name')->nullable()->after('status');
            $table->string('phone')->nullable()->after('customer_name');
            $table->integer('guest_count')->nullable()->after('phone');
            $table->decimal('unit_price', 8, 2)->default(0)->after('menu_item_id');
            $table->boolean('offer_applied')->default(false)->after('unit_price');
            $table->decimal('discount_amount', 8, 2)->default(0)->after('offer_applied');
            $table->text('special_instructions')->nullable()->after('discount_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'order_number',
                'status',
                'customer_name',
                'phone',
                'guest_count',
                'unit_price',
                'offer_applied',
                'discount_amount',
                'special_instructions',
            ]);
        });
    }
};
