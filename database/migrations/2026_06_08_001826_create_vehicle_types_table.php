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
    Schema::create('vehicle_types', function (Blueprint $table) {
        $table->id();
        $table->enum('jenis', ['motorcycle', 'car', 'other']);
        $table->integer('perjam_pertama');
        $table->integer('perjam_berikutnya');
        $table->integer('max_perhari');
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_types');
    }
};
