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
        Schema::create('resep_racikan_item', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resep_item_id')->constrained('resep_item')->onDelete('cascade');
            $table->unsignedInteger('obatalkes_id');
            $table->integer('jumlah');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resep_racikan_item');
    }
};
