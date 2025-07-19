<?php

namespace App\Http\Controllers;

use App\Models\ObatAlkes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ResepController extends Controller
{
    public function create(Request $request)
    {
        $draftItems = $request->session()->get('resep.items', []);

        return view('prescriptions.create', [
            'draftItems' => $draftItems,
            'mode' => $request->session()->get('resep.mode', 'non_racikan'),
        ]);
    }

    public function gantiMode(Request $request, $mode)
    {
        if (in_array($mode, ['non_racikan', 'racikan'])) {
            $request->session()->put('resep.mode', $mode);
        }
        return redirect()->route('resep.buat');
    }

    public function tambahNonRacikan(Request $request)
    {
        $validated = $request->validate([
            'obatalkes_id' => 'required|integer|exists:obatalkes_m,obatalkes_id',
            'jumlah' => 'required|integer|min:1',
            'signa_id' => 'required|integer|exists:signa_m,signa_id',
        ]);

        $newItem = [
            'uuid' => (string) Str::uuid(),
            'jenis' => 'non_racikan',
            'obatalkes_id' => $validated['obatalkes_id'],
            'jumlah' => $validated['jumlah'],
            'signa_id' => $validated['signa_id'],
        ];

        if (!$this->isStockSufficient($request, [$newItem])) {
            return back()->with('error', 'Jumlah obat melebihi stok yang tersedia.');
        }

        $items = $request->session()->get('resep.items', []);

        $items[] = $newItem;

        $request->session()->put('resep.items', $items);

        Log::info('Item non-racikan ditambahkan ke draft.', [
            'obat_id' => $newItem['obatalkes_id'],
            'jumlah' => $newItem['jumlah'],
            'session_id' => $request->session()->getId()
        ]);

        return redirect()->route('resep.buat')->with('success', 'Obat non-racikan ditambahkan ke draft.');
    }

    public function tambahRacikan(Request $request)
    {
        $validated = $request->validate([
            'nama_racikan' => 'required|string|max:255',
            'jumlah_racikan' => 'required|integer|min:1',
            'signa_id' => 'required|integer|exists:signa_m,signa_id',
            'komponen_obat_id' => 'required|array|min:2',
            'komponen_jumlah' => 'required|array|min:2',
            'komponen_obat_id.*' => 'required|integer|exists:obatalkes_m,obatalkes_id',
            'komponen_jumlah.*' => 'required|integer|min:1',
        ]);

        $komponen = [];
        for ($i = 0; $i < count($validated['komponen_obat_id']); $i++) {
            $komponen[] = [
                'obatalkes_id' => $validated['komponen_obat_id'][$i],
                'jumlah' => $validated['komponen_jumlah'][$i]
            ];
        }

        $newItem = [
            'uuid' => (string) Str::uuid(),
            'jenis' => 'racikan',
            'nama_racikan' => $validated['nama_racikan'],
            'jumlah_racikan' => $validated['jumlah_racikan'],
            'signa_id' => $validated['signa_id'],
            'komponen' => $komponen
        ];

        if (!$this->isStockSufficient($request, [$newItem])) {
            return back()->with('error', 'Jumlah salah satu komponen racikan melebihi stok tersedia.');
        }

        $items = $request->session()->get('resep.items', []);

        $items[] = $newItem;

        $request->session()->put('resep.items', $items);

        Log::info('Item racikan ditambahkan ke draft.', [
            'nama_racikan' => $newItem['nama_racikan'],
            'jumlah_dibuat' => $newItem['jumlah_racikan'],
            'session_id' => $request->session()->getId()
        ]);

        return redirect()->route('resep.buat')->with('success', 'Obat racikan ditambahkan ke draft.');
    }

    public function hapusItem(Request $request, $uuid)
    {
        $items = $request->session()->get('resep.items', []);
        $itemDihapus = collect($items)->firstWhere('uuid', $uuid);
        $newItems = array_filter($items, fn($item) => $item['uuid'] !== $uuid);
        $request->session()->put('resep.items', array_values($newItems));

        Log::info('Item dihapus dari draft.', [
            'item_detail' => $itemDihapus,
            'session_id' => $request->session()->getId()
        ]);

        return redirect()->route('resep.buat')->with('success', 'Item berhasil dihapus dari draft.');
    }

    // helper
    private function calculateUsedStock(array $draftItems): array
    {
        $usedStock = [];
        foreach ($draftItems as $item) {
            if ($item['jenis'] === 'non_racikan') {
                $obatId = $item['obatalkes_id'];
                $usedStock[$obatId] = ($usedStock[$obatId] ?? 0) + $item['jumlah'];
            } else if ($item['jenis'] === 'racikan') {
                foreach ($item['komponen'] as $comp) {
                    $obatId = $comp['obatalkes_id'];
                    $usedStock[$obatId] = ($usedStock[$obatId] ?? 0) + ($comp['jumlah'] * $item['jumlah_racikan']);
                }
            }
        }
        return $usedStock;
    }

    private function isStockSufficient(Request $request, array $newItems): bool
    {
        $draftItems = $request->session()->get('resep.items', []);
        $allItems = array_merge($draftItems, $newItems);
        $usedStock = $this->calculateUsedStock($allItems);

        $obatIds = array_keys($usedStock);
        if (empty($obatIds)) return true;

        $dbStocks = ObatAlkes::whereIn('obatalkes_id', $obatIds)->pluck('stok', 'obatalkes_id');

        foreach ($usedStock as $obatId => $needed) {
            if ($needed > ($dbStocks[$obatId] ?? 0)) {
                return false;
            }
        }
        return true;
    }
    // helper end
}
