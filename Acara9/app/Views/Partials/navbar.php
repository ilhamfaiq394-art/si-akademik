<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="/si-akademik/public/mahasiswa">SI Akademik</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <a class="nav-link" href="/si-akademik/public/mahasiswa">Data Mahasiswa</a>
        </li>
        <!-- Menu Tambahan Acara 8 -->
        <li class="nav-item">
          <a class="nav-link" href="/si-akademik/public/prodi">Data Prodi</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/si-akademik/public/matakuliah">Data Mata Kuliah</a>
        </li>
      </ul>
      
      <!-- Tampilkan tombol Logout jika session logged_in bernilai true -->
      <?php if (!empty($_SESSION['logged_in'])): ?>
        <div class="d-flex align-items-center gap-3">
          <span class="text-light small">
            Halo, <strong><?= htmlspecialchars($_SESSION['user_name'] ?? 'Admin'); ?></strong>
          </span>
          <a href="/si-akademik/public/logout" class="btn btn-outline-danger btn-sm">Logout</a>
        </div>
      <?php endif; ?>
    </div>
  </div>
</nav>