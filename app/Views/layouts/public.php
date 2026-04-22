<!DOCTYPE html>
<html lang="id" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Darurat Bencana - Platform pelaporan bencana real-time dengan notifikasi WhatsApp otomatis.">
    <title><?= esc($title ?? 'Darurat Bencana') ?></title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
</head>
<body class="public-page">

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark glass-nav fixed-top" id="mainNav">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="<?= base_url('/') ?>">
                <div class="brand-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <span class="brand-text">Darurat<span class="text-accent">Bencana</span></span>
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center gap-2">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('/') ?>"><i class="fas fa-home me-1"></i> Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('/lapor') ?>"><i class="fas fa-exclamation-triangle me-1"></i> Lapor</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-glow btn-sm px-3" href="<?= base_url('/auth/login') ?>">
                            <i class="fas fa-lock me-1"></i> Admin
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        <?= $this->renderSection('content') ?>
    </main>

    <!-- Footer -->
    <footer class="glass-footer">
        <div class="container">
            <div class="row align-items-center py-4">
                <div class="col-md-6">
                    <div class="d-flex align-items-center gap-2">
                        <div class="brand-icon-sm">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <span class="fw-semibold">Darurat<span class="text-accent">Bencana</span></span>
                    </div>
                    <p class="text-muted small mt-2 mb-0">Platform pelaporan bencana dengan notifikasi real-time.</p>
                </div>
                <div class="col-md-6 text-md-end mt-3 mt-md-0">
                    <p class="text-muted small mb-0">&copy; <?= date('Y') ?> Darurat Bencana. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JS -->
    <script src="<?= base_url('js/app.js') ?>"></script>

    <?= $this->renderSection('scripts') ?>
</body>
</html>
