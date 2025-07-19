<?php

use App\Models\ObatAlkes;
use App\Models\Signa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/search-obat', function (Request $request) {
    $searchTerm = $request->query('q', '');

    if (empty($searchTerm)) {
        return response()->json([]);
    }

    $draftItems = session()->get('resep.items', []);

    $usedStock = [];
    foreach ($draftItems as $item) {
        if ($item['jenis'] === 'non_racikan') {
            $usedStock[$item['obatalkes_id']] = ($usedStock[$item['obatalkes_id']] ?? 0) + $item['jumlah'];
        } elseif ($item['jenis'] === 'racikan') {
            foreach ($item['komponen'] as $comp) {
                $usedStock[$comp['obatalkes_id']] = ($usedStock[$comp['obatalkes_id']] ?? 0) + ($comp['jumlah'] * $item['jumlah_racikan']);
            }
        }
    }

    $results = ObatAlkes::where('is_active', 1)
        ->where('stok', '>', 0)
        ->where('obatalkes_nama', 'LIKE', "%{$searchTerm}%")
        ->orderByRaw("CASE WHEN obatalkes_nama LIKE '{$searchTerm}%' THEN 1 ELSE 2 END, obatalkes_nama") // Prioritaskan yang dimulai dengan search term
        ->limit(50)
        ->get()
        ->map(function ($obat) use ($usedStock) {
            $stokTersedia = $obat->stok - ($usedStock[$obat->obatalkes_id] ?? 0);
            if ($stokTersedia > 0) {
                return [
                    'value' => $obat->obatalkes_id,
                    'label' => "{$obat->obatalkes_nama} (Stok: {$stokTersedia})",
                ];
            }
            return null;
        })
        ->filter()->values();

    return response()->json($results);
});

Route::get('/search-signa', function (Request $request) {
    $searchTerm = $request->query('q', '');

    if (empty($searchTerm)) {
        return response()->json([]);
    }

    $results = Signa::where('is_active', 1)
        ->where('signa_nama', 'LIKE', "%{$searchTerm}%")
        ->orderBy('signa_nama')
        ->limit(50)
        ->get()
        ->map(function ($signa) {
            return [
                'value' => $signa->signa_id,
                'label' => $signa->signa_nama,
            ];
        });

    return response()->json($results);
});
