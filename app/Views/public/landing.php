<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>

<!-- Hero Section -->
<section class="hero-section" id="hero">
    <!-- Animated Background Elements -->
    <div class="hero-bg-effects">
        <div class="floating-orb orb-1"></div>
        <div class="floating-orb orb-2"></div>
        <div class="floating-orb orb-3"></div>
        <div class="grid-overlay"></div>
    </div>

    <div class="container position-relative">
        <div class="row align-items-center min-vh-100 py-5">
            <div class="col-lg-7">
                <div class="hero-content" data-aos="fade-up">
                    <div class="hero-badge mb-3">
                        <span class="badge-glow"><i class="fas fa-broadcast-tower me-2"></i>Platform Darurat Aktif</span>
                    </div>
                    <h1 class="hero-title">
                        Sistem Pelaporan<br>
                        <span class="gradient-text">Bencana Real-Time</span>
                    </h1>
                    <p class="hero-subtitle">
                        Laporkan bencana di sekitar Anda dengan cepat dan mudah. 
                        Notifikasi otomatis via WhatsApp langsung terkirim ke tim penanggulangan bencana.
                    </p>
                    <div class="hero-actions">
                        <a href="<?= base_url('/lapor') ?>" class="btn btn-hero-primary" id="ctaButton">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Laporkan Bencana
                            <div class="btn-pulse"></div>
                        </a>
                        <a href="#howItWorks" class="btn btn-hero-secondary">
                            <i class="fas fa-info-circle me-2"></i>
                            Cara Kerja
                        </a>
                    </div>

                    <!-- Stats -->
                    <div class="hero-stats mt-5">
                        <div class="stat-item">
                            <div class="stat-number" data-count="<?= $totalLaporan ?? 0 ?>"><?= $totalLaporan ?? 0 ?></div>
                            <div class="stat-label">Laporan Masuk</div>
                        </div>
                        <div class="stat-divider"></div>
                        <div class="stat-item">
                            <div class="stat-number">24/7</div>
                            <div class="stat-label">Sistem Aktif</div>
                        </div>
                        <div class="stat-divider"></div>
                        <div class="stat-item">
                            <div class="stat-number"><i class="fab fa-whatsapp text-success"></i></div>
                            <div class="stat-label">Notifikasi Instan</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5 d-none d-lg-block">
                <div class="hero-illustration">
                    <div class="illustration-card glass-card">
                        <div class="card-icon pulse-icon">
                            <i class="fas fa-satellite-dish"></i>
                        </div>
                        <h4>Monitoring Aktif</h4>
                        <p class="text-muted small">Pemantauan bencana real-time 24 jam</p>
                        <div class="mini-chart">
                            <div class="chart-bar" style="height: 40%"></div>
                            <div class="chart-bar" style="height: 70%"></div>
                            <div class="chart-bar active" style="height: 90%"></div>
                            <div class="chart-bar" style="height: 50%"></div>
                            <div class="chart-bar" style="height: 65%"></div>
                            <div class="chart-bar" style="height: 45%"></div>
                            <div class="chart-bar" style="height: 80%"></div>
                        </div>
                    </div>
                    <div class="illustration-card glass-card card-float-1">
                        <div class="d-flex align-items-center gap-2">
                            <div class="notif-icon bg-danger-soft">
                                <i class="fas fa-bell text-danger"></i>
                            </div>
                            <div>
                                <span class="fw-semibold small">Alert Baru!</span><br>
                                <span class="text-muted" style="font-size: 0.75rem">Banjir - Jakarta Selatan</span>
                            </div>
                        </div>
                    </div>
                    <div class="illustration-card glass-card card-float-2">
                        <div class="d-flex align-items-center gap-2">
                            <div class="notif-icon bg-success-soft">
                                <i class="fab fa-whatsapp text-success"></i>
                            </div>
                            <div>
                                <span class="fw-semibold small">Notifikasi Terkirim</span><br>
                                <span class="text-muted" style="font-size: 0.75rem">5 kontak diberitahu</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section class="how-section" id="howItWorks">
    <div class="container py-5">
        <div class="text-center mb-5">
            <span class="section-badge"><i class="fas fa-cogs me-2"></i>Cara Kerja</span>
            <h2 class="section-title mt-3">Proses Pelaporan <span class="gradient-text">Mudah & Cepat</span></h2>
            <p class="section-subtitle">Hanya dalam 3 langkah, laporan Anda akan sampai ke tim penanggulangan bencana</p>
        </div>

        <div class="row g-4 justify-content-center">
            <div class="col-md-4">
                <div class="step-card glass-card text-center">
                    <div class="step-number">01</div>
                    <div class="step-icon">
                        <i class="fas fa-edit"></i>
                    </div>
                    <h4>Isi Formulir</h4>
                    <p class="text-muted">Lengkapi informasi bencana termasuk lokasi, kategori, dan deskripsi detail</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="step-card glass-card text-center">
                    <div class="step-number">02</div>
                    <div class="step-icon">
                        <i class="fas fa-paper-plane"></i>
                    </div>
                    <h4>Kirim Laporan</h4>
                    <p class="text-muted">Data laporan tersimpan aman di database dan langsung diproses</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="step-card glass-card text-center">
                    <div class="step-number">03</div>
                    <div class="step-icon">
                        <i class="fab fa-whatsapp"></i>
                    </div>
                    <h4>Notifikasi Otomatis</h4>
                    <p class="text-muted">Pesan WhatsApp otomatis terkirim ke seluruh tim terdaftar</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Category Section -->
<section class="category-section">
    <div class="container py-5">
        <div class="text-center mb-5">
            <span class="section-badge"><i class="fas fa-layer-group me-2"></i>Kategori</span>
            <h2 class="section-title mt-3">Jenis Bencana yang <span class="gradient-text">Dapat Dilaporkan</span></h2>
        </div>

        <div class="row g-3 justify-content-center">
            <?php
            $categories = [
                ['icon' => 'fa-water', 'name' => 'Banjir', 'color' => '#3b82f6'],
                ['icon' => 'fa-house-crack', 'name' => 'Gempa', 'color' => '#f59e0b'],
                ['icon' => 'fa-fire', 'name' => 'Kebakaran', 'color' => '#ef4444'],
                ['icon' => 'fa-mountain', 'name' => 'Longsor', 'color' => '#8b5cf6'],
                ['icon' => 'fa-water', 'name' => 'Tsunami', 'color' => '#06b6d4'],
                ['icon' => 'fa-wind', 'name' => 'Angin Topan', 'color' => '#10b981'],
                ['icon' => 'fa-triangle-exclamation', 'name' => 'Lainnya', 'color' => '#6366f1'],
            ];
            foreach ($categories as $cat): ?>
                <div class="col-6 col-md-3 col-lg-auto">
                    <div class="category-card glass-card" style="--cat-color: <?= $cat['color'] ?>">
                        <div class="category-icon">
                            <i class="fas <?= $cat['icon'] ?>"></i>
                        </div>
                        <span class="category-name"><?= $cat['name'] ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
    <div class="container py-5">
        <div class="cta-card glass-card text-center">
            <div class="cta-bg-glow"></div>
            <h2 class="mb-3">Lihat Bencana di Sekitar Anda?</h2>
            <p class="text-muted mb-4">Segera laporkan untuk penanganan cepat dan tepat.</p>
            <a href="<?= base_url('/lapor') ?>" class="btn btn-hero-primary btn-lg">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Laporkan Sekarang
            </a>
        </div>
    </div>
</section>

<?= $this->endSection() ?>
