<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<!-- Dashboard Stats Cards -->
<div class="row g-4 mb-4">
    <!-- Total Laporan -->
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card glass-card">
            <div class="stat-card-body">
                <div class="stat-card-icon bg-primary-soft">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="stat-card-info">
                    <span class="stat-card-label">Total Laporan</span>
                    <h3 class="stat-card-value"><?= $totalLaporan ?></h3>
                </div>
            </div>
            <div class="stat-card-footer">
                <i class="fas fa-chart-line me-1"></i> Semua laporan masuk
            </div>
        </div>
    </div>

    <!-- Laporan Baru -->
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card glass-card">
            <div class="stat-card-body">
                <div class="stat-card-icon bg-warning-soft">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <div class="stat-card-info">
                    <span class="stat-card-label">Laporan Baru</span>
                    <h3 class="stat-card-value"><?= $totalBaru ?></h3>
                </div>
            </div>
            <div class="stat-card-footer">
                <i class="fas fa-clock me-1"></i> Menunggu ditangani
            </div>
        </div>
    </div>

    <!-- Sedang Diproses -->
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card glass-card">
            <div class="stat-card-body">
                <div class="stat-card-icon bg-info-soft">
                    <i class="fas fa-spinner"></i>
                </div>
                <div class="stat-card-info">
                    <span class="stat-card-label">Diproses</span>
                    <h3 class="stat-card-value"><?= $totalDiproses ?></h3>
                </div>
            </div>
            <div class="stat-card-footer">
                <i class="fas fa-cog me-1"></i> Sedang ditangani
            </div>
        </div>
    </div>

    <!-- Total Kontak -->
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card glass-card">
            <div class="stat-card-body">
                <div class="stat-card-icon bg-success-soft">
                    <i class="fab fa-whatsapp"></i>
                </div>
                <div class="stat-card-info">
                    <span class="stat-card-label">Kontak WA</span>
                    <h3 class="stat-card-value"><?= $totalKontak ?></h3>
                </div>
            </div>
            <div class="stat-card-footer">
                <i class="fas fa-address-book me-1"></i> Kontak terdaftar
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row g-4 mb-4">
    <!-- Donut Chart: Kategori -->
    <div class="col-lg-5">
        <div class="glass-card chart-card">
            <div class="chart-card-header">
                <h5><i class="fas fa-chart-pie me-2"></i>Statistik Kategori</h5>
            </div>
            <div class="chart-card-body">
                <canvas id="kategoriChart" height="280"></canvas>
            </div>
        </div>
    </div>

    <!-- Line Chart: Daily Reports -->
    <div class="col-lg-7">
        <div class="glass-card chart-card">
            <div class="chart-card-header">
                <h5><i class="fas fa-chart-line me-2"></i>Laporan Per Hari (30 Hari Terakhir)</h5>
            </div>
            <div class="chart-card-body">
                <canvas id="dailyChart" height="280"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Recent Reports -->
<div class="glass-card">
    <div class="chart-card-header d-flex justify-content-between align-items-center">
        <h5><i class="fas fa-clock me-2"></i>Laporan Terbaru</h5>
        <a href="<?= base_url('/admin/laporan') ?>" class="btn btn-sm btn-outline-accent">
            Lihat Semua <i class="fas fa-arrow-right ms-1"></i>
        </a>
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
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($recentReports)): ?>
                    <tr><td colspan="7" class="text-center text-muted py-4">Belum ada laporan masuk.</td></tr>
                <?php else: ?>
                    <?php foreach ($recentReports as $i => $row): ?>
                        <tr class="<?= $row['is_urgent'] ? 'urgent-row' : '' ?>">
                            <td><?= $i + 1 ?></td>
                            <td>
                                <span class="fw-semibold"><?= esc($row['nama_pelapor']) ?></span>
                                <?php if ($row['is_urgent']): ?>
                                    <span class="badge badge-urgent ms-1"><i class="fas fa-bolt"></i></span>
                                <?php endif; ?>
                            </td>
                            <td><span class="badge badge-kategori"><?= esc($row['kategori']) ?></span></td>
                            <td class="text-truncate" style="max-width: 200px"><?= esc($row['lokasi']) ?></td>
                            <td><?= date('d M Y, H:i', strtotime($row['tanggal'])) ?></td>
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
                                <a href="<?= base_url('/admin/laporan/' . $row['id']) ?>" class="btn btn-sm btn-outline-accent">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Color palette
    const colors = ['#3b82f6', '#8b5cf6', '#06b6d4', '#f59e0b', '#ef4444', '#10b981', '#6366f1'];
    const colorsAlpha = colors.map(c => c + '40');

    // ============================================
    // Donut Chart: Kategori
    // ============================================
    const kategoriData = <?= json_encode($kategoriStats) ?>;
    const kategoriLabels = kategoriData.map(item => item.kategori);
    const kategoriValues = kategoriData.map(item => parseInt(item.total));

    new Chart(document.getElementById('kategoriChart'), {
        type: 'doughnut',
        data: {
            labels: kategoriLabels,
            datasets: [{
                data: kategoriValues,
                backgroundColor: colors.slice(0, kategoriLabels.length),
                borderColor: 'transparent',
                borderWidth: 0,
                hoverOffset: 10,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '65%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color: '#94a3b8',
                        padding: 15,
                        usePointStyle: true,
                        font: { family: 'Inter', size: 12 }
                    }
                }
            },
            animation: {
                animateRotate: true,
                duration: 1500,
                easing: 'easeInOutQuart'
            }
        }
    });

    // ============================================
    // Line Chart: Daily Reports
    // ============================================
    const dailyData = <?= json_encode($dailyReports) ?>;
    const dailyLabels = dailyData.map(item => {
        const d = new Date(item.date);
        return d.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
    });
    const dailyValues = dailyData.map(item => parseInt(item.total));

    new Chart(document.getElementById('dailyChart'), {
        type: 'line',
        data: {
            labels: dailyLabels,
            datasets: [{
                label: 'Laporan',
                data: dailyValues,
                borderColor: '#06b6d4',
                backgroundColor: (context) => {
                    const ctx = context.chart.ctx;
                    const gradient = ctx.createLinearGradient(0, 0, 0, 280);
                    gradient.addColorStop(0, 'rgba(6, 182, 212, 0.3)');
                    gradient.addColorStop(1, 'rgba(6, 182, 212, 0.0)');
                    return gradient;
                },
                borderWidth: 2,
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#06b6d4',
                pointBorderColor: '#0f0f23',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                x: {
                    grid: { color: 'rgba(148, 163, 184, 0.08)' },
                    ticks: { color: '#64748b', font: { family: 'Inter', size: 11 } }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(148, 163, 184, 0.08)' },
                    ticks: { 
                        color: '#64748b', 
                        font: { family: 'Inter', size: 11 },
                        stepSize: 1
                    }
                }
            },
            animation: {
                duration: 2000,
                easing: 'easeInOutQuart'
            }
        }
    });
});
</script>
<?= $this->endSection() ?>
