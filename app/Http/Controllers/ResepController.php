<?php

namespace App\Http\Controllers;

use App\Models\ObatAlkes;
use App\Models\Resep;
use App\Models\ResepItem;
use App\Models\ResepRacikanItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

    public function store(Request $request)
    {
        $draftItems = $request->session()->get('resep.items', []);
        if (empty($draftItems)) {
            return back()->with('error', 'Resep tidak boleh kosong.');
        }

        DB::beginTransaction();
        try {
            $resep = Resep::create(['nomor_resep' => 'RSP-' . strtoupper(Str::random(8)) . '-' . time()]);

            Log::info("Membuat resep baru dengan No. {$resep->nomor_resep}", ['id' => $resep->id]);

            foreach ($draftItems as $item) {
                if ($item['jenis'] === 'non_racikan') {
                    $this->processNonRacikanItem($resep->id, $item);
                } elseif ($item['jenis'] === 'racikan') {
                    $this->processRacikanItem($resep->id, $item);
                }
            }

            DB::commit();
            $request->session()->forget('resep');

            Log::info("Resep No. {$resep->nomor_resep} berhasil disimpan.", ['id' => $resep->id]);

            return redirect()->route('resep.show', $resep->id)->with('success', 'Resep berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menyimpan resep: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'draft_items' => $draftItems
            ]);
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    private function processNonRacikanItem($resepId, $item)
    {
        ResepItem::create([
            'resep_id' => $resepId,
            'jenis' => 'non_racikan',
            'obatalkes_id' => $item['obatalkes_id'],
            'jumlah' => $item['jumlah'],
            'signa_id' => $item['signa_id'],
        ]);
        ObatAlkes::find($item['obatalkes_id'])->decrement('stok', $item['jumlah']);
    }

    private function processRacikanItem($resepId, $item)
    {
        $resepItem = ResepItem::create([
            'resep_id' => $resepId,
            'jenis' => 'racikan',
            'nama_racikan' => $item['nama_racikan'],
            'jumlah' => $item['jumlah_racikan'],
            'signa_id' => $item['signa_id'],
        ]);
        foreach ($item['komponen'] as $component) {
            ResepRacikanItem::create([
                'resep_item_id' => $resepItem->id,
                'obatalkes_id' => $component['obatalkes_id'],
                'jumlah' => $component['jumlah'],
            ]);
            $totalDeduction = $component['jumlah'] * $item['jumlah_racikan'];
            ObatAlkes::find($component['obatalkes_id'])->decrement('stok', $totalDeduction);
        }
    }

    public function show(Resep $resep)
    {
        $resep->load('items.obat', 'items.signa', 'items.racikanItems.obat');
        return view('prescriptions.show', ['resep' => $resep]);
    }

    public function printPdf(Resep $resep)
    {
        $resep->load('items.obat', 'items.signa', 'items.racikanItems.obat');
        $pdf = Pdf::loadView('prescriptions.pdf', ['resep' => $resep]);
        return $pdf->stream('resep-' . $resep->nomor_resep . '.pdf');
    }
}
