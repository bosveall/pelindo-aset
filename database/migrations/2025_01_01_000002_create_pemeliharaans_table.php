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
        Schema::create('pemeliharaans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aset_id')->constrained()->onDelete('cascade');
            $table->dateTime('tanggal')->nullable();
            $table->string('jenis')->nullable();
            $table->text('keterangan')->nullable();
            $table->decimal('biaya', 15, 2)->default(0);
            $table->enum('status', ['selesai', 'proses', 'menunggu'])->default('menunggu');
            $table->foreignId('petugas_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemeliharaans');
    }
};
