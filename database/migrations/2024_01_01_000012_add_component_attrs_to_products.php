<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('component_type')->nullable()->after('brand');     // cpu, motherboard, ram, gpu, storage, psu, case
            $table->string('socket')->nullable()->after('component_type');    // AM5, LGA1700, etc.
            $table->string('chipset')->nullable()->after('socket');           // B650, Z790
            $table->string('ram_type')->nullable()->after('chipset');         // DDR4, DDR5
            $table->string('form_factor')->nullable()->after('ram_type');     // ATX, mATX, ITX
            $table->integer('tdp')->nullable()->after('form_factor');         // thermal design power in watts
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['component_type', 'socket', 'chipset', 'ram_type', 'form_factor', 'tdp']);
        });
    }
};
