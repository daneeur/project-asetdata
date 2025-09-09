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
        Schema::table('barang', function (Blueprint $table) {
            // Hapus kolom keterangan yang tidak digunakan
            $table->dropColumn('keterangan');
            
            // Tambah kolom baru yang diperlukan
            $table->foreignId('kategori_id')->constrained('kategori')->onDelete('cascade');
            $table->text('spesifikasi');
            $table->enum('kondisi', ['Baik', 'Rusak Ringan', 'Rusak Berat']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barang', function (Blueprint $table) {
            $table->string('keterangan');
            $table->dropForeign(['kategori_id']);
            $table->dropColumn(['kategori_id', 'spesifikasi', 'kondisi']);
        });
    }
};
