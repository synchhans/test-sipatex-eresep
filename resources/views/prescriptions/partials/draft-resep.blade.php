<div class="lg:col-span-2">
    <div class="bg-white p-6 rounded-xl shadow-lg sticky top-24">
        <h3 class="text-xl font-bold text-gray-800 mb-4 border-b pb-3 flex justify-between items-center">
            <span>Draft Resep</span>
            @if(count($draftItems) > 0)
                <span class="text-sm bg-blue-100 text-blue-800 font-bold px-2 py-1 rounded-full">{{ count($draftItems) }}</span>
            @endif
        </h3>
        
        @if (empty($draftItems))
            <div class="text-center py-10">
                <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                <p class="mt-2 text-sm font-medium text-gray-500">Draft resep masih kosong.</p>
            </div>
        @else
            @php
                $draftObatIds = [];
                $draftSignaIds = [];
                foreach($draftItems as $item) {
                    $draftSignaIds[] = $item['signa_id'];
                    if($item['jenis'] == 'non_racikan') $draftObatIds[] = $item['obatalkes_id'];
                    else foreach($item['komponen'] as $comp) $draftObatIds[] = $comp['obatalkes_id'];
                }
                $obatLookup = App\Models\ObatAlkes::whereIn('obatalkes_id', array_unique($draftObatIds))->pluck('obatalkes_nama', 'obatalkes_id');
                $signaLookup = App\Models\Signa::whereIn('signa_id', array_unique($draftSignaIds))->pluck('signa_nama', 'signa_id');
            @endphp
            <div class="flow-root max-h-[60vh] overflow-y-auto pr-2">
                <ul role="list" class="-my-4 divide-y divide-gray-200">
                    @foreach ($draftItems as $item)
                        <li class="flex items-center py-4 space-x-3">
                            <div class="flex-shrink-0">
                                <span class="w-10 h-10 flex items-center justify-center rounded-full text-white font-bold text-base {{ $item['jenis'] == 'racikan' ? 'bg-purple-500' : 'bg-green-500' }}">
                                    {{ $item['jenis'] == 'racikan' ? 'R' : 'N' }}
                                </span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900 truncate">
                                    {{ $item['jenis'] == 'racikan' ? $item['nama_racikan'] : ($obatLookup[$item['obatalkes_id']] ?? 'Obat tidak ditemukan') }}
                                </p>
                                <p class="text-xs text-gray-500 truncate">
                                    {{ $signaLookup[$item['signa_id']] ?? 'Signa tidak ditemukan' }}
                                </p>
                            </div>
                            <div class="flex-shrink-0 text-sm font-medium text-gray-600">
                                x{{ $item['jenis'] == 'racikan' ? $item['jumlah_racikan'] : $item['jumlah'] }}
                            </div>
                            <a href="{{ route('resep.draft.hapusItem', $item['uuid']) }}" class="flex-shrink-0 text-gray-400 hover:text-red-600 p-1 rounded-full transition-colors">
                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" /></svg>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
            <form action="{{ route('resep.store') }}" method="POST" class="mt-6">
                @csrf
                <button type="submit" class="w-full inline-flex justify-center items-center px-6 py-3 bg-green-600 text-white font-bold rounded-lg hover:bg-green-700 shadow-lg transition-colors">
                    <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" /></svg>
                    Simpan & Proses Resep
                </button>
            </form>
        @endif
    </div>
</div>