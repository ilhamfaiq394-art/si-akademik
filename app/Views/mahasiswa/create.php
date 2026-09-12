<?php
// Tangkap data input lama jika ada error validasi
$old = $_SESSION['old_input'] ?? [];
unset($_SESSION['old_input']);
?>

<!-- --- FLASH MESSAGE ERROR VALIDASI --- -->
<?php if (!empty($_SESSION['flash_message'])): ?>
    <div class="alert alert-<?= $_SESSION['flash_message']['type']; ?> alert-dismissible fade show mb-3" role="alert">
        <?= $_SESSION['flash_message']['message']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['flash_message']); ?>
<?php endif; ?>

<div class="card shadow-sm border-0">
    <div class="card-header bg-primary text-white py-3">
        <h5 class="mb-0 fw-bold">Tambah Mahasiswa</h5>
    </div>
    <div class="card-body p-4">
        <form action="/si-akademik/public/mahasiswa/store" method="POST">
            
            <!-- NIM -->
            <div class="mb-3">
                <label for="nim" class="form-label">NIM <span class="text-danger">*</span></label>
                <input type="text" 
                       class="form-control" 
                       id="nim" 
                       name="nim" 
                       required 
                       placeholder="Contoh: E41253154" 
                       value="<?= htmlspecialchars($old['nim'] ?? ''); ?>">
            </div>

            <!-- Nama -->
            <div class="mb-3">
                <label for="nama" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text" 
                       class="form-control" 
                       id="nama" 
                       name="nama" 
                       required 
                       placeholder="Masukkan nama lengkap" 
                       value="<?= htmlspecialchars($old['nama'] ?? ''); ?>">
            </div>

            <!-- Email -->
            <div class="mb-3">
                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                <input type="email" 
                       class="form-control" 
                       id="email" 
                       name="email" 
                       required 
                       placeholder="contoh@gmail.com" 
                       value="<?= htmlspecialchars($old['email'] ?? ''); ?>">
            </div>

            <!-- Program Studi -->
            <div class="mb-3">
                <label for="prodi_id" class="form-label">Program Studi <span class="text-danger">*</span></label>
                <select class="form-select" id="prodi_id" name="prodi_id" required>
                    <option value="" disabled <?= empty($old['prodi_id']) ? 'selected' : ''; ?>>-- Pilih Program Studi --</option>
                    <?php if (!empty($prodiList)): ?>
                        <?php foreach ($prodiList as $p): ?>
                            <option value="<?= $p['id']; ?>" <?= (isset($old['prodi_id']) && $old['prodi_id'] == $p['id']) ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($p['nama_prodi'] ?? $p['nama'] ?? ''); ?>
                                <?= !empty($p['jenjang']) ? ' (' . htmlspecialchars($p['jenjang']) . ')' : ''; ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <!-- Angkatan -->
            <div class="mb-3">
                <label for="angkatan" class="form-label">Angkatan <span class="text-danger">*</span></label>
                <input type="number" 
                       class="form-control" 
                       id="angkatan" 
                       name="angkatan" 
                       required 
                       placeholder="Contoh: 2024" 
                       value="<?= htmlspecialchars($old['angkatan'] ?? ''); ?>">
            </div>

            <!-- Status -->
            <div class="mb-4">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status">
                    <option value="aktif" <?= (isset($old['status']) && $old['status'] === 'aktif') || empty($old['status']) ? 'selected' : ''; ?>>Aktif</option>
                    <option value="cuti" <?= (isset($old['status']) && $old['status'] === 'cuti') ? 'selected' : ''; ?>>Cuti</option>
                    <option value="lulus" <?= (isset($old['status']) && $old['status'] === 'lulus') ? 'selected' : ''; ?>>Lulus</option>
                </select>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="/si-akademik/public/mahasiswa" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary fw-bold">Simpan Data</button>
            </div>

        </form>
    </div>
</div>