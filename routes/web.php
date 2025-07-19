<?php

use App\Http\Controllers\ResepController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('resep.buat');
});

Route::get('/resep/buat', [ResepController::class, 'create'])->name('resep.buat');

Route::prefix('resep-draft')->name('resep.draft.')->group(function () {
    Route::get('/ganti-mode/{mode}', [ResepController::class, 'gantiMode'])->name('gantiMode');
    Route::post('/tambah-nonracikan', [ResepController::class, 'tambahNonRacikan'])->name('tambahNonRacikan');
    Route::post('/tambah-racikan', [ResepController::class, 'tambahRacikan'])->name('tambahRacikan');
    Route::get('/hapus/{uuid}', [ResepController::class, 'hapusItem'])->name('hapusItem');
});

Route::post('/resep', [ResepController::class, 'store'])->name('resep.store');
Route::get('/resep/{resep}', [ResepController::class, 'show'])->name('resep.show');
