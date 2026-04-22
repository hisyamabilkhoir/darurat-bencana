<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="row g-4">
    <!-- Report Detail -->
    <div class="col-lg-8">
        <div class="glass-card">
            <div class="chart-card-header d-flex justify-content-between align-items-center">
                <h5><i class="fas fa-file-alt me-2"></i>Detail Laporan #<?= $laporan['id'] ?></h5>
                <a href="<?= base_url('/admin/laporan') ?>" class="btn btn-sm btn-outline-accent">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>

            <div class="p-4">
                <!-- Urgent Badge -->
                <?php if ($laporan['is_urgent']): ?>
                    <div class="alert alert-danger-custom mb-4">
                        <i class="fas fa-bolt me-2"></i>
                        <strong>LAPORAN DARURAT</strong> — Memerlukan penanganan segera!
                    </div>
                <?php endif; ?>

                <!-- Detail Grid -->
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="detail-item">
                            <label class="detail-label"><i class="fas fa-user me-2"></i>Nama Pelapor</label>
                            <p class="detail-value"><?= esc($laporan['nama_pelapor']) ?></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-item">
                            <label class="detail-label"><i class="fas fa-layer-group me-2"></i>Kategori</label>
                            <p class="detail-value"><span class="badge badge-kategori"><?= esc($laporan['kategori']) ?></span></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-item">
                            <label class="detail-label"><i class="fas fa-map-marker-alt me-2"></i>Lokasi</label>
                            <p class="detail-value"><?= esc($laporan['lokasi']) ?></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-item">
                            <label class="detail-label"><i class="fas fa-calendar-alt me-2"></i>Tanggal</label>
                            <p class="detail-value"><?= date('d M Y, H:i', strtotime($laporan['tanggal'])) ?> WIB</p>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="detail-item">
                            <label class="detail-label"><i class="fas fa-align-left me-2"></i>Deskripsi</label>
                            <p class="detail-value"><?= nl2br(esc($laporan['deskripsi'])) ?></p>
                        </div>
                    </div>
                </div>

                <!-- Photo -->
                <?php if ($laporan['foto']): ?>
                    <div class="detail-item mt-4">
                        <label class="detail-label"><i class="fas fa-camera me-2"></i>Foto</label>
                        <div class="detail-photo">
                            <img src="<?= base_url('uploads/laporan/' . $laporan['foto']) ?>" 
                                 alt="Foto Laporan" class="img-fluid rounded-3">
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Side Panel -->
    <div class="col-lg-4">
        <!-- Status Card -->
        <div class="glass-card mb-4">
            <div class="chart-card-header">
                <h5><i class="fas fa-info-circle me-2"></i>Status & Info</h5>
            </div>
            <div class="p-4">
                <!-- Current Status -->
                <div class="mb-3">
                    <label class="detail-label">Status Saat Ini</label>
                    <?php
                    $statusClass = match($laporan['status']) {
                        'baru'     => 'badge-status-baru',
                        'diproses' => 'badge-status-proses',
                        'selesai'  => 'badge-status-selesai',
                        default    => 'badge-status-baru',
                    };
                    ?>
                    <p><span class="badge <?= $statusClass ?> fs-6"><?= ucfirst($laporan['status']) ?></span></p>
                </div>

                <!-- Update Status -->
                <form action="<?= base_url('/admin/laporan/status/' . $laporan['id']) ?>" method="POST">
                    <?= csrf_field() ?>
                    <label class="detail-label mb-2">Ubah Status</label>
                    <div class="d-flex gap-2">
                        <select name="status" class="form-select glass-input">
                            <option value="baru" <?= $laporan['status'] === 'baru' ? 'selected' : '' ?>>Baru</option>
                            <option value="diproses" <?= $laporan['status'] === 'diproses' ? 'selected' : '' ?>>Diproses</option>
                            <option value="selesai" <?= $laporan['status'] === 'selesai' ? 'selected' : '' ?>>Selesai</option>
                        </select>
                        <button type="submit" class="btn btn-hero-primary btn-sm px-3">
                            <i class="fas fa-save"></i>
                        </button>
                    </div>
                </form>

                <hr class="border-secondary my-3">

                <!-- WA Status -->
                <div class="mb-3">
                    <label class="detail-label">Notifikasi WhatsApp</label>
                    <?php if ($laporan['wa_notified']): ?>
                        <p class="text-success"><i class="fas fa-check-circle me-1"></i> Terkirim</p>
                    <?php else: ?>
                        <p class="text-muted"><i class="fas fa-minus-circle me-1"></i> Belum terkirim</p>
                    <?php endif; ?>
                </div>

                <!-- Timestamps -->
                <div class="mb-3">
                    <label class="detail-label">Dibuat</label>
                    <p class="small text-muted"><?= date('d M Y, H:i:s', strtotime($laporan['created_at'])) ?></p>
                </div>
                <div>
                    <label class="detail-label">Diperbarui</label>
                    <p class="small text-muted mb-0"><?= date('d M Y, H:i:s', strtotime($laporan['updated_at'])) ?></p>
                </div>

                <hr class="border-secondary my-3">

                <!-- Delete -->
                <form action="<?= base_url('/admin/laporan/delete/' . $laporan['id']) ?>" method="POST"
                      onsubmit="return confirm('Yakin ingin menghapus laporan ini? Tindakan ini tidak dapat dibatalkan.')">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-outline-danger w-100">
                        <i class="fas fa-trash me-1"></i> Hapus Laporan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
