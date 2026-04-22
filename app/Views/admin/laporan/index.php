<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<!-- Filter Section -->
<div class="glass-card mb-4">
    <div class="chart-card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h5><i class="fas fa-filter me-2"></i>Filter Laporan</h5>
        <div class="d-flex gap-2">
            <a href="<?= base_url('/admin/laporan/export') . '?' . http_build_query(array_filter($filters ?? [])) ?>" class="btn btn-sm btn-outline-accent">
                <i class="fas fa-download me-1"></i> Export CSV
            </a>
        </div>
    </div>
    <form method="GET" action="<?= base_url('/admin/laporan') ?>" class="p-3">
        <div class="row g-3 align-items-end">
            <div class="col-md-2">
                <label class="form-label small text-muted">Kategori</label>
                <select class="form-select glass-input" name="kategori">
                    <option value="">Semua</option>
                    <?php foreach ($kategoriList as $kat): ?>
                        <option value="<?= $kat ?>" <?= ($filters['kategori'] ?? '') === $kat ? 'selected' : '' ?>><?= $kat ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small text-muted">Status</label>
                <select class="form-select glass-input" name="status">
                    <option value="">Semua</option>
                    <option value="baru" <?= ($filters['status'] ?? '') === 'baru' ? 'selected' : '' ?>>Baru</option>
                    <option value="diproses" <?= ($filters['status'] ?? '') === 'diproses' ? 'selected' : '' ?>>Diproses</option>
                    <option value="selesai" <?= ($filters['status'] ?? '') === 'selesai' ? 'selected' : '' ?>>Selesai</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small text-muted">Dari Tanggal</label>
                <input type="date" class="form-control glass-input" name="start_date" value="<?= $filters['start_date'] ?? '' ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label small text-muted">Sampai Tanggal</label>
                <input type="date" class="form-control glass-input" name="end_date" value="<?= $filters['end_date'] ?? '' ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label small text-muted">Lokasi</label>
                <input type="text" class="form-control glass-input" name="lokasi" value="<?= $filters['lokasi'] ?? '' ?>" placeholder="Cari lokasi...">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-hero-primary w-100"><i class="fas fa-search me-1"></i> Filter</button>
            </div>
        </div>
    </form>
</div>

<!-- Reports Table -->
<div class="glass-card">
    <div class="chart-card-header">
        <h5><i class="fas fa-list me-2"></i>Daftar Laporan (<?= count($laporan) ?>)</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-dark-custom">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Pelapor</th>
                    <th>Kategori</th>
                    <th>Lokasi</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>WA</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($laporan)): ?>
                    <tr><td colspan="8" class="text-center text-muted py-4">
                        <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                        Tidak ada laporan ditemukan.
                    </td></tr>
                <?php else: ?>
                    <?php foreach ($laporan as $i => $row): ?>
                        <tr class="<?= $row['is_urgent'] ? 'urgent-row' : '' ?>">
                            <td><?= $i + 1 ?></td>
                            <td>
                                <span class="fw-semibold"><?= esc($row['nama_pelapor']) ?></span>
                                <?php if ($row['is_urgent']): ?>
                                    <span class="badge badge-urgent ms-1" title="DARURAT"><i class="fas fa-bolt"></i></span>
                                <?php endif; ?>
                            </td>
                            <td><span class="badge badge-kategori"><?= esc($row['kategori']) ?></span></td>
                            <td class="text-truncate" style="max-width: 180px" title="<?= esc($row['lokasi']) ?>"><?= esc($row['lokasi']) ?></td>
                            <td class="text-nowrap"><?= date('d M Y, H:i', strtotime($row['tanggal'])) ?></td>
                            <td>
                                <?php
                                $statusClass = match($row['status']) {
                                    'baru'     => 'badge-status-baru',
                                    'diproses' => 'badge-status-proses',
                                    'selesai'  => 'badge-status-selesai',
                                    default    => 'badge-status-baru',
                                };
                                ?>
                                <span class="badge <?= $statusClass ?>"><?= ucfirst($row['status']) ?></span>
                            </td>
                            <td>
                                <?php if ($row['wa_notified']): ?>
                                    <span class="text-success" title="Notifikasi terkirim"><i class="fas fa-check-circle"></i></span>
                                <?php else: ?>
                                    <span class="text-muted" title="Belum terkirim"><i class="fas fa-minus-circle"></i></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="<?= base_url('/admin/laporan/' . $row['id']) ?>" class="btn btn-sm btn-outline-accent" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <form action="<?= base_url('/admin/laporan/delete/' . $row['id']) ?>" method="POST" 
                                          onsubmit="return confirm('Yakin ingin menghapus laporan ini?')">
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

<?= $this->endSection() ?>
