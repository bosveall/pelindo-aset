<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('asets', function (Blueprint $table) {
            $table->dropColumn('harga');
        });

        Schema::table('pemeliharaans', function (Blueprint $table) {
            $table->dropColumn('biaya');
            $table->date('jadwal_pemeliharaan')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('asets', function (Blueprint $table) {
            $table->decimal('harga', 15, 2)->default(0);
        });

        Schema::table('pemeliharaans', function (Blueprint $table) {
            $table->decimal('biaya', 15, 2)->default(0);
            $table->dropColumn('jadwal_pemeliharaan');
        });
    }
};
