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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->string('reservation_number')->unique();
            $table->string('customer_name');
            $table->string('phone', 20);
            $table->unsignedInteger('guest_count');
            $table->string('reservation_type');
            $table->foreignId('table_id')->constrained()->onDelete('cascade');
            $table->string('table_type')->nullable();
            $table->date('reservation_date')->nullable();
            $table->time('reservation_time')->nullable();
            $table->decimal('duration_hours', 3, 1)->nullable();
            $table->string('special_occasion')->nullable();
            $table->text('notes')->nullable();
            $table->string('status')->default('confirmed');
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
