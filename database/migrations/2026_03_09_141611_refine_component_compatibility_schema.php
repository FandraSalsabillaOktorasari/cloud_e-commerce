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
        // 1. Update Products Table
        Schema::table('products', function (Blueprint $table) {
            $table->renameColumn('socket', 'socket_type');
            $table->renameColumn('ram_type', 'memory_type');
            $table->renameColumn('tdp', 'tdp_watts');
        });

        // 2. Update Categories Table
        Schema::table('categories', function (Blueprint $table) {
            $table->boolean('is_pc_component')->default(false)->after('slug');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->renameColumn('socket_type', 'socket');
            $table->renameColumn('memory_type', 'ram_type');
            $table->renameColumn('tdp_watts', 'tdp');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('is_pc_component');
        });
    }
};
