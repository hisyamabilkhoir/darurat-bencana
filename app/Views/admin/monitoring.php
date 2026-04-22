<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<!-- Controls -->
<div class="glass-card mb-4">
    <div class="chart-card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h5><i class="fas fa-satellite-dish me-2"></i>Monitoring Real-Time</h5>
        <div class="d-flex align-items-center gap-3">
            <div class="d-flex align-items-center gap-2">
                <span class="monitoring-dot pulse-dot"></span>
                <span class="small text-success">Live</span>
            </div>
            <div class="form-check form-switch mb-0">
                <input class="form-check-input" type="checkbox" id="autoRefreshToggle" checked>
                <label class="form-check-label small" for="autoRefreshToggle">Auto Refresh (15s)</label>
            </div>
            <button class="btn btn-sm btn-outline-accent" onclick="refreshData()">
                <i class="fas fa-sync-alt me-1"></i> Refresh
            </button>
        </div>
    </div>
    <div class="p-3">
        <div class="row g-3 align-items-end">
            <div class="col-md-4">
                <input type="text" class="form-control glass-input" id="searchInput" placeholder="Cari pelapor, lokasi, kategori...">
            </div>
            <div class="col-md-3">
                <select class="form-select glass-input" id="filterKategori">
                    <option value="">Semua Kategori</option>
                    <option value="Banjir">Banjir</option>
                    <option value="Gempa">Gempa</option>
                    <option value="Kebakaran">Kebakaran</option>
                    <option value="Longsor">Longsor</option>
                    <option value="Tsunami">Tsunami</option>
                    <option value="Angin Topan">Angin Topan</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select glass-input" id="filterStatus">
                    <option value="">Semua Status</option>
                    <option value="baru">Baru</option>
                    <option value="diproses">Diproses</option>
                    <option value="selesai">Selesai</option>
                </select>
            </div>
            <div class="col-md-2">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="filterUrgent">
                    <label class="form-check-label small" for="filterUrgent">
                        <i class="fas fa-bolt text-warning"></i> Urgent Only
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Monitoring Table -->
<div class="glass-card">
    <div class="table-responsive">
        <table class="table table-dark-custom" id="monitoringTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Pelapor</th>
                    <th>Kategori</th>
                    <th>Lokasi</th>
                    <th>Deskripsi</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="monitoringBody">
                <?php foreach ($laporan as $i => $row): ?>
                    <tr class="monitoring-row <?= $row['is_urgent'] ? 'urgent-row' : '' ?>" 
                        data-kategori="<?= esc($row['kategori']) ?>" 
                        data-status="<?= esc($row['status']) ?>"
                        data-urgent="<?= $row['is_urgent'] ?>"
                        data-search="<?= strtolower(esc($row['nama_pelapor'] . ' ' . $row['lokasi'] . ' ' . $row['kategori'])) ?>">
                        <td><?= $i + 1 ?></td>
                        <td>
                            <span class="fw-semibold"><?= esc($row['nama_pelapor']) ?></span>
                            <?php if ($row['is_urgent']): ?>
                                <span class="badge badge-urgent ms-1"><i class="fas fa-bolt"></i></span>
                            <?php endif; ?>
                        </td>
                        <td><span class="badge badge-kategori"><?= esc($row['kategori']) ?></span></td>
                        <td class="text-truncate" style="max-width: 150px"><?= esc($row['lokasi']) ?></td>
                        <td class="text-truncate" style="max-width: 200px"><?= esc($row['deskripsi']) ?></td>
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
                            <a href="<?= base_url('/admin/laporan/' . $row['id']) ?>" class="btn btn-sm btn-outline-accent">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Last Updated -->
    <div class="p-3 text-end">
        <small class="text-muted">
            <i class="fas fa-clock me-1"></i>
            Terakhir diperbarui: <span id="lastUpdated"><?= date('H:i:s') ?></span>
        </small>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let autoRefreshInterval;
    const autoRefreshToggle = document.getElementById('autoRefreshToggle');
    const searchInput = document.getElementById('searchInput');
    const filterKategori = document.getElementById('filterKategori');
    const filterStatus = document.getElementById('filterStatus');
    const filterUrgent = document.getElementById('filterUrgent');

    // Start auto-refresh
    function startAutoRefresh() {
        autoRefreshInterval = setInterval(refreshData, 15000);
    }

    // Stop auto-refresh
    function stopAutoRefresh() {
        clearInterval(autoRefreshInterval);
    }

    // Toggle auto-refresh
    autoRefreshToggle.addEventListener('change', function() {
        if (this.checked) {
            startAutoRefresh();
        } else {
            stopAutoRefresh();
        }
    });

    // Initialize auto-refresh
    startAutoRefresh();

    // Client-side filtering
    function filterTable() {
        const search = searchInput.value.toLowerCase();
        const kategori = filterKategori.value;
        const status = filterStatus.value;
        const urgentOnly = filterUrgent.checked;

        document.querySelectorAll('.monitoring-row').forEach(row => {
            const matchSearch = !search || row.dataset.search.includes(search);
            const matchKategori = !kategori || row.dataset.kategori === kategori;
            const matchStatus = !status || row.dataset.status === status;
            const matchUrgent = !urgentOnly || row.dataset.urgent === '1';

            row.style.display = (matchSearch && matchKategori && matchStatus && matchUrgent) ? '' : 'none';
        });
    }

    searchInput.addEventListener('input', filterTable);
    filterKategori.addEventListener('change', filterTable);
    filterStatus.addEventListener('change', filterTable);
    filterUrgent.addEventListener('change', filterTable);
});

// Refresh data from server
function refreshData() {
    fetch('<?= base_url('/admin/monitoring/latest') ?>')
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                document.getElementById('lastUpdated').textContent = new Date().toLocaleTimeString('id-ID');
            }
        })
        .catch(err => console.log('Refresh failed:', err));
}
</script>
<?= $this->endSection() ?>
