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
            <a href="{{ route('resep.print', $resep->id) }}" target="_blank" class="flex-1 w-full inline-flex items-center justify-center px-4 py-2 bg-gray-700 text-white text-sm font-semibold rounded-lg hover:bg-gray-800 transition-colors shadow-sm">
                <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5 2.75C5 1.784 5.784 1 6.75 1h6.5c.966 0 1.75.784 1.75 1.75v3.552c.377.046.752.12 1.126.224a2.25 2.25 0 011.603 2.102v8.622a2.25 2.25 0 01-2.25 2.25H4.25A2.25 2.25 0 012 19.25V10.628a2.25 2.25 0 011.603-2.102c.374-.104.75-.178 1.126-.224V2.75zM8.5 4.5a.75.75 0 00-1.5 0v1a.75.75 0 001.5 0v-1zM6.75 5.5a1.75 1.75 0 00-1.75 1.75v.231c.376-.105.754-.18 1.126-.225A2.5 2.5 0 018.5 7.5v-.25A1.75 1.75 0 006.75 5.5zM11.5 7.5c.621 0 1.192.174 1.624.456a.75.75 0 00.932-1.018A4 4 0 0011.5 6a.75.75 0 000 1.5zM4.25 18V9.173a.75.75 0 01.503-.701l.334-.105a3.998 3.998 0 013.823 0l.334.105A.75.75 0 019.75 9.173V18H4.25z" clip-rule="evenodd" /></svg>
                Cetak
            </a>
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