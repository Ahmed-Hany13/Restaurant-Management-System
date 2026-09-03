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
        Schema::table('offers', function (Blueprint $table) {
            $table->json('applicable_days')->nullable()->after('end_time');
            $table->boolean('display_on_menu')->default(true)->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('offers', function (Blueprint $table) {
            if (Schema::hasColumn('offers', 'applicable_days')) {
                $table->dropColumn('applicable_days');
            }
            if (Schema::hasColumn('offers', 'display_on_menu')) {
                $table->dropColumn('display_on_menu');
            }
        });
    }
};
