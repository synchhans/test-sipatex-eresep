@extends('layouts.app')
@section('title', 'Buat Resep Baru')
@section('content')

<div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
    
    <div class="lg:col-span-3">
        <div class="bg-white p-6 rounded-xl shadow-lg space-y-6">
            <div>
                <h2 class="text-2xl font-bold mb-1 text-gray-800">Formulir Resep</h2>
                <p class="text-sm text-gray-500">Pilih jenis, cari obat, lalu tambahkan ke draft resep di samping.</p>
            </div>

            <div class="border-t border-gray-200"></div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">1. Pilih Jenis Obat</label>
                <div class="flex">
                    <a href="{{ route('resep.draft.gantiMode', 'non_racikan') }}" class="px-5 py-2.5 text-sm font-semibold transition-all duration-200 rounded-l-lg {{ $mode == 'non_racikan' ? 'bg-blue-600 text-white shadow-md z-10' : 'bg-gray-100 text-gray-600 hover:bg-gray-200 border border-gray-200' }}">Obat Non-Racikan</a>
                    <a href="{{ route('resep.draft.gantiMode', 'racikan') }}" class="px-5 py-2.5 text-sm font-semibold transition-all duration-200 rounded-r-lg {{ $mode == 'racikan' ? 'bg-blue-600 text-white shadow-md z-10' : 'bg-gray-100 text-gray-600 hover:bg-gray-200 border border-gray-200 border-l-0' }}">Obat Racikan</a>
                </div>
            </div>
            
            @if ($mode == 'non_racikan')
                @include('prescriptions.partials.form-non-racikan')
            @else
                @include('prescriptions.partials.form-racikan')
            @endif

        </div>
    </div>

    @include('prescriptions.partials.draft-resep')
    
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    
    const initChoicesWithApi = (element, apiUrl, placeholder, searchPlaceholder) => {
        if (!element) return null;
        
        const choices = new Choices(element, {
            itemSelectText: 'Pilih',
            searchResultLimit: 50,
            placeholder: true,
            placeholderValue: placeholder,
            searchPlaceholderValue: searchPlaceholder,
            loadingText: 'Mencari...',
            noResultsText: 'Tidak ada hasil ditemukan',
            noChoicesText: 'Mulai ketik untuk mencari',
            removeItemButton: false
        });

        element.addEventListener('search', function(event) {
            const searchTerm = event.detail.value.trim();
            if (searchTerm.length < 1) {
                choices.clearChoices();
                choices.setChoices([{ value: '', label: 'Ketik untuk mulai mencari...', disabled: true }], 'value', 'label', true);
                return;
            }
            
            console.log(`Mencari di ${apiUrl} dengan: "${searchTerm}"`);

            choices.clearChoices(); 
            choices.showDropdown();
            choices.setChoices([{ value: '', label: 'Mencari...', disabled: true }], 'value', 'label', true);

            fetch(`${apiUrl}?q=${searchTerm}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log(`Data diterima dari ${apiUrl}:`, data);
                    choices.clearChoices();
                    if(data.length > 0){
                        choices.setChoices(data, 'value', 'label', true);
                    } else {
                        choices.setChoices([{ value: '', label: 'Tidak ada hasil ditemukan', disabled: true }], 'value', 'label', true);
                    }
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                    choices.clearChoices();
                    choices.setChoices([{ value: '', label: 'Gagal memuat data. Periksa koneksi.', disabled: true }], 'value', 'label', true);
                });
        });
        return choices;
    };
    
    const mode = "{{ $mode }}";
    const obatApiUrl = "{{ url('/api/search-obat') }}";
    const signaApiUrl = "{{ url('/api/search-signa') }}";

    if (mode === 'non_racikan') {
        initChoicesWithApi(
            document.getElementById('obat_select_non_racikan'),
            obatApiUrl, 
            '-- Ketik untuk mencari obat --', 
            'Ketik nama obat...'
        );
        initChoicesWithApi(
            document.getElementById('signa_select_non_racikan'), 
            signaApiUrl,
            '-- Ketik untuk mencari signa --',
            'Ketik aturan pakai...'
        );
    }
    
    if (mode === 'racikan') {
        initChoicesWithApi(
            document.getElementById('signa_select_racikan'), 
            signaApiUrl,
            '-- Ketik untuk mencari signa --',
            'Ketik aturan pakai...'
        );
        
        const wrapper = document.getElementById('komponen-wrapper');
        const addButton = document.getElementById('tambah-komponen');
        let componentCount = 0;
        
        const createComponentRow = () => {
            const index = componentCount++;
            
            const newItem = document.createElement('div');
            newItem.className = 'komponen-item flex flex-col md:flex-row items-stretch md:items-center space-y-2 md:space-y-0 space-x-0 md:space-x-2 p-2 bg-gray-50 rounded-md';
            newItem.innerHTML = `
                <div class="flex-1 w-full"><select name="komponen_obat_id[]" required id="komponen_obat_${index}"></select></div>
                <div class="flex items-center space-x-2 w-full md:w-auto">
                    <input type="number" name="komponen_jumlah[]" value="1" min="1" required placeholder="Qty" class="w-full md:w-24 border-gray-300 rounded-md shadow-sm h-[42px] text-sm">
                    <button type="button" class="px-3 h-[42px] text-white rounded-md transition-colors ${index < 2 ? 'bg-gray-400 cursor-not-allowed' : 'bg-red-500 hover:bg-red-600'}" ${index < 2 ? 'disabled' : ''}>×</button>
                </div>`;
            wrapper.appendChild(newItem);
            
            const choicesInstance = initChoicesWithApi(
                newItem.querySelector('select'), 
                obatApiUrl,
                '-- Cari komponen obat --',
                'Ketik nama obat...'
            );

            newItem.querySelector('button').addEventListener('click', () => {
                if (wrapper.querySelectorAll('.komponen-item').length > 2) {
                    choicesInstance.destroy(); newItem.remove();
                }
            });
        };
        createComponentRow(); 
        createComponentRow();
        addButton.addEventListener('click', createComponentRow);
    }
});
</script>
@endsection