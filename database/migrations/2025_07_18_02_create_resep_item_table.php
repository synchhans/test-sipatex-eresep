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
        Schema::create('resep_item', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resep_id')->constrained('resep')->onDelete('cascade');
            $table->enum('jenis', ['non_racikan', 'racikan']);
            $table->unsignedInteger('obatalkes_id')->nullable();
            $table->integer('jumlah');
            $table->string('nama_racikan')->nullable();
            $table->unsignedInteger('signa_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resep_item');
    }
};
