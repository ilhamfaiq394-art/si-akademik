<div class="card shadow-sm border-0">
    <div class="card-header bg-warning text-dark py-3">
        <h5 class="mb-0 fw-bold">Edit Program Studi</h5>
    </div>
    <div class="card-body p-4">
        <form action="/si-akademik/public/prodi/update/<?= $prodi['id']; ?>" method="POST">
            
            <div class="mb-3">
                <label for="kode" class="form-label">Kode Prodi <span class="text-danger">*</span></label>
                <input type="text" 
                       class="form-control" 
                       id="kode" 
                       name="kode" 
                       value="<?= htmlspecialchars($prodi['kode'] ?? ''); ?>" 
                       required>
            </div>

            <div class="mb-3">
                <label for="nama" class="form-label">Nama Program Studi <span class="text-danger">*</span></label>
                <input type="text" 
                       class="form-control" 
                       id="nama" 
                       name="nama" 
                       value="<?= htmlspecialchars($prodi['nama'] ?? ''); ?>" 
                       required>
            </div>

            <div class="mb-4">
                <label for="jenjang" class="form-label">Jenjang <span class="text-danger">*</span></label>
                <select class="form-select" id="jenjang" name="jenjang" required>
                    <option value="" disabled>-- Pilih Jenjang --</option>
                    <?php 
                    $jenjangOptions = ['D3', 'D4', 'S1', 'S2'];
                    foreach ($jenjangOptions as $j): 
                    ?>
                        <option value="<?= $j; ?>" <?= ($prodi['jenjang'] ?? '') === $j ? 'selected' : ''; ?>>
                            <?= $j; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="/si-akademik/public/prodi" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-warning text-dark fw-bold">Perbarui Data</button>
            </div>

        </form>
    </div>
</div>