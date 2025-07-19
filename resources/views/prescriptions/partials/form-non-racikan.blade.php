<form action="{{ route('resep.draft.tambahNonRacikan') }}" method="POST" class="space-y-6 pt-4 border-t border-gray-200">
    @csrf
    <div>
        <label for="obat_select_non_racikan" class="block text-sm font-semibold text-gray-700 mb-2">2. Cari & Pilih Obat</label>
        <select id="obat_select_non_racikan" name="obatalkes_id" required></select>
    </div>
    
    <div>
        <label for="jumlah_nonracikan" class="block text-sm font-semibold text-gray-700 mb-2">3. Tentukan Jumlah & Aturan Pakai</label>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <input type="number" name="jumlah" id="jumlah_nonracikan" value="1" min="1" required class="block w-full border-gray-300 rounded-md shadow-sm h-[42px] text-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Jumlah...">
            <select name="signa_id" id="signa_select_non_racikan" required></select>
        </div>
    </div>

    <div class="pt-2">
        <button type="submit" class="w-full md:w-auto inline-flex items-center justify-center px-6 py-2.5 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" /></svg>
            Tambah ke Draft
        </button>
    </div>
</form>