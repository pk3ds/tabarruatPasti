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
        Schema::create('tabarruats', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pemohon');
            $table->string('pasti');
            $table->json('nama_arwah'); // Store multiple deceased names as JSON
            $table->boolean('sudah_sumbangan')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tabarruats');
    }
};
