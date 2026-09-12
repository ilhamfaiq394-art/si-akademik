<div class="card shadow-sm border-0">
    <div class="card-header bg-primary text-white py-3">
        <h5 class="mb-0 fw-bold">Tambah Program Studi</h5>
    </div>
    <div class="card-body p-4">
        <form action="/si-akademik/public/prodi/store" method="POST">
            
            <div class="mb-3">
                <label for="kode" class="form-label">Kode Prodi <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="kode" name="kode" placeholder="Contoh: TIF" required>
            </div>

            <div class="mb-3">
                <label for="nama" class="form-label">Nama Program Studi <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="nama" name="nama" placeholder="Contoh: Teknik Informatika" required>
            </div>

            <div class="mb-4">
                <label for="jenjang" class="form-label">Jenjang <span class="text-danger">*</span></label>
                <select class="form-select" id="jenjang" name="jenjang" required>
                    <option value="" selected disabled>-- Pilih Jenjang --</option>
                    <option value="D3">D3</option>
                    <option value="D4">D4</option>
                    <option value="S1">S1</option>
                    <option value="S2">S2</option>
                </select>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="/si-akademik/public/prodi" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary fw-bold">Simpan Data</button>
            </div>

        </form>
    </div>
</div>