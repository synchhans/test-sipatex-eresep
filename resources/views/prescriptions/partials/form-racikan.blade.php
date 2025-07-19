<form action="{{ route('resep.draft.tambahRacikan') }}" method="POST" class="space-y-6 pt-4 border-t border-gray-200">
    @csrf
     <div>
        <label for="nama_racikan" class="block text-sm font-semibold text-gray-700 mb-2">2. Beri Nama & Jumlah Racikan</label>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <input type="text" id="nama_racikan" name="nama_racikan" required placeholder="Contoh: Racikan Demam Anak" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm px-3 py-2 focus:border-blue-500 focus:ring-blue-500">
            <input type="number" id="jumlah_racikan" name="jumlah_racikan" value="1" min="1" required placeholder="Jumlah Dibuat" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm px-3 py-2 focus:border-blue-500 focus:ring-blue-500">
        </div>
    </div>

    <div class="space-y-3">
        <label class="block text-sm font-semibold text-gray-700">3. Pilih Komponen Obat (minimal 2)</label>
        <div class="space-y-2" id="komponen-wrapper">
        </div>
        <button type="button" id="tambah-komponen" class="text-sm font-semibold text-blue-600 hover:text-blue-800 transition-colors">+ Tambah Komponen Lain</button>
    </div>

    <div>
        <label for="signa_select_racikan" class="block text-sm font-semibold text-gray-700 mb-2">4. Tentukan Aturan Pakai</label>
        <select name="signa_id" id="signa_select_racikan" required></select>
    </div>

    <div class="pt-2">
        <button type="submit" class="w-full md:w-auto inline-flex items-center justify-center px-6 py-2.5 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 shadow-sm"><svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" /></svg>Tambah Racikan ke Draft</button>
    </div>
</form>