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
        if (!Schema::hasTable('pengajuan')) {
            Schema::create('pengajuan', function (Blueprint $table) {
                $table->id();
                $table->foreignId('asset_id')->nullable()->constrained('assets')->nullOnDelete();
                $table->string('nama_pengaju')->nullable();
                $table->text('catatan')->nullable();
                $table->enum('status', ['pending','diterima','ditolak'])->default('pending');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan');
    }
};
