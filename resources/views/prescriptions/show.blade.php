@extends('layouts.app')

@section('title', 'Detail Resep ' . $resep->nomor_resep)

@section('content')
<div class="bg-white p-6 md:p-8 rounded-xl shadow-lg max-w-3xl mx-auto">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center border-b-2 border-gray-200 pb-4 mb-6 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Detail Resep Digital</h2>
            <div class="flex items-center space-x-4 text-sm text-gray-500 mt-1">
                <span>No: <span class="font-semibold text-gray-700">{{ $resep->nomor_resep }}</span></span>
                <span class="text-gray-300">|</span>
                <span>Tanggal: <span class="font-semibold text-gray-700">{{ $resep->created_at->format('d F Y') }}</span></span>
            </div>
        </div>
        <div class="flex space-x-2 w-full md:w-auto shrink-0">
            <a href="{{ route('resep.buat') }}" class="flex-1 w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                 <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" /></svg>
                Resep Baru
            </a>
        </div>
    </div>
    
    <div class="space-y-6">
        @foreach($resep->items as $item)
            <div class="bg-gray-50/70 p-4 rounded-lg border border-gray-200">
                <div class="flex items-start space-x-4">
                    <div class="text-3xl font-serif font-bold text-gray-400">R/</div>
                    
                    <div class="flex-1">
                        @if($item->jenis === 'non_racikan')
                            <p class="font-bold text-lg text-gray-800">{{ $item->obat->obatalkes_nama }}</p>
                            <p class="text-sm text-gray-500">Jumlah: <span class="font-medium text-gray-700">{{ $item->jumlah }}</span></p>
                        @else
                            <p class="font-bold text-lg text-gray-800">{{ $item->nama_racikan }}</p>
                             <div class="mt-2 pl-4 border-l-2 border-gray-200">
                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Komponen</p>
                                <ul class="text-sm text-gray-600 space-y-1">
                                    @foreach($item->racikanItems as $racikanItem)
                                        <li><span class="font-medium">{{ $racikanItem->obat->obatalkes_nama }}</span> <span class="text-gray-500">(sebanyak {{ $racikanItem->jumlah }})</span></li>
                                    @endforeach
                                </ul>
                            </div>
                            <p class="text-sm text-gray-500 mt-2">Jumlah Racikan Dibuat: <span class="font-medium text-gray-700">{{ $item->jumlah }}</span></p>
                        @endif
                        
                        <div class="mt-3 flex items-start space-x-2 text-sm">
                            <span class="font-semibold text-gray-700">S.</span>
                            <span class="text-gray-600">{{ $item->signa->signa_nama }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection