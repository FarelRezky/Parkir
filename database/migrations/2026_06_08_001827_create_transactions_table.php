<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('transactions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('id_lokasi')->constrained('locations')->onDelete('cascade');
        $table->string('no_tiket', 255);
        $table->string('no_polisi', 15)->nullable();
        $table->foreignId('id_jenis')->constrained('vehicle_types')->onDelete('cascade');
        
        // Pastikan dua baris ini ADA di file kamu
        $table->dateTime('masuk');
        $table->dateTime('keluar')->nullable(); 
        
        $table->integer('perjam_pertama');
        $table->integer('perjam_berikutnya');
        $table->integer('max_perhari');
        $table->integer('total_jam')->nullable();
        $table->integer('total_bayar')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
