<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="row g-4">
    <!-- Add Contact Form -->
    <div class="col-lg-4">
        <div class="glass-card">
            <div class="chart-card-header">
                <h5><i class="fas fa-plus-circle me-2"></i>Tambah Kontak</h5>
            </div>
            <div class="p-4">
                <!-- Validation Errors -->
                <?php if (session()->getFlashdata('errors')): ?>
                    <div class="alert alert-danger-custom mb-3">
                        <ul class="mb-0 ps-3">
                            <?php foreach (session()->getFlashdata('errors') as $err): ?>
                                <li><?= $err ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('/admin/kontak/store') ?>" method="POST">
                    <?= csrf_field() ?>

                    <div class="form-floating-custom mb-3">
                        <label for="nama"><i class="fas fa-user me-2"></i>Nama Kontak</label>
                        <input type="text" class="form-control glass-input" id="nama" name="nama" 
                               placeholder="Masukkan nama kontak" value="<?= old('nama') ?>" required>
                    </div>

                    <div class="form-floating-custom mb-3">
                        <label for="nomor_wa"><i class="fab fa-whatsapp me-2"></i>Nomor WhatsApp</label>
                        <input type="text" class="form-control glass-input" id="nomor_wa" name="nomor_wa" 
                               placeholder="081234567890" value="<?= old('nomor_wa') ?>" required
                               pattern="[0-9+]+" title="Hanya angka dan +">
                        <small class="text-muted">Format: 081234567890 atau +6281234567890</small>
                    </div>

                    <button type="submit" class="btn btn-hero-primary w-100">
                        <i class="fas fa-save me-1"></i> Simpan Kontak
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Contacts List -->
    <div class="col-lg-8">
        <div class="glass-card">
            <div class="chart-card-header">
                <h5><i class="fas fa-address-book me-2"></i>Daftar Kontak WhatsApp (<?= count($kontak) ?>)</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-dark-custom">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Nomor WA</th>
                            <th>Status</th>
                            <th>Ditambahkan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($kontak)): ?>
                            <tr><td colspan="6" class="text-center text-muted py-4">
                                <i class="fas fa-address-book fa-2x mb-2 d-block"></i>
                                Belum ada kontak terdaftar.
                            </td></tr>
                        <?php else: ?>
                            <?php foreach ($kontak as $i => $row): ?>
                                <tr>
                                    <td><?= $i + 1 ?></td>
                                    <td class="fw-semibold"><?= esc($row['nama']) ?></td>
                                    <td>
                                        <i class="fab fa-whatsapp text-success me-1"></i>
                                        <?= esc($row['nomor_wa']) ?>
                                    </td>
                                    <td>
                                        <?php if ($row['is_active']): ?>
                                            <span class="badge badge-status-selesai">Aktif</span>
                                        <?php else: ?>
                                            <span class="badge badge-status-baru">Nonaktif</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= date('d M Y', strtotime($row['created_at'])) ?></td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <button class="btn btn-sm btn-outline-accent" 
                                                    onclick="openEditModal(<?= htmlspecialchars(json_encode($row)) ?>)" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form action="<?= base_url('/admin/kontak/delete/' . $row['id']) ?>" method="POST"
                                                  onsubmit="return confirm('Yakin ingin menghapus kontak ini?')">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content glass-modal">
            <div class="modal-header border-0">
                <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Edit Kontak</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="editForm" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="form-floating-custom mb-3">
                        <label for="edit_nama"><i class="fas fa-user me-2"></i>Nama</label>
                        <input type="text" class="form-control glass-input" id="edit_nama" name="nama" required>
                    </div>
                    <div class="form-floating-custom mb-3">
                        <label for="edit_nomor_wa"><i class="fab fa-whatsapp me-2"></i>Nomor WhatsApp</label>
                        <input type="text" class="form-control glass-input" id="edit_nomor_wa" name="nomor_wa" required>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="edit_is_active" name="is_active" value="1">
                        <label class="form-check-label" for="edit_is_active">Kontak Aktif</label>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-hero-primary"><i class="fas fa-save me-1"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function openEditModal(data) {
    document.getElementById('edit_nama').value = data.nama;
    document.getElementById('edit_nomor_wa').value = data.nomor_wa;
    document.getElementById('edit_is_active').checked = data.is_active == 1;
    document.getElementById('editForm').action = '<?= base_url('/admin/kontak/update/') ?>' + data.id;
    new bootstrap.Modal(document.getElementById('editModal')).show();
}
</script>
<?= $this->endSection() ?>
