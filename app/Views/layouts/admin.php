<!DOCTYPE html>
<html lang="id" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Admin Dashboard - Darurat Bencana Platform">
    <title><?= esc($title ?? 'Admin - Darurat Bencana') ?></title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
</head>
<body class="admin-page">

    <!-- Sidebar -->
    <aside class="admin-sidebar" id="adminSidebar">
        <!-- Sidebar Header -->
        <div class="sidebar-header">
            <div class="d-flex align-items-center gap-2">
                <div class="brand-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <span class="brand-text sidebar-text">Darurat<span class="text-accent">Bencana</span></span>
            </div>
            <button class="sidebar-toggle-btn d-lg-none" id="closeSidebar">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Navigation -->
        <nav class="sidebar-nav">
            <div class="nav-section">
                <span class="nav-section-title sidebar-text">MENU UTAMA</span>
                <a href="<?= base_url('/admin/dashboard') ?>" class="nav-item <?= ($activePage ?? '') === 'dashboard' ? 'active' : '' ?>">
                    <i class="fas fa-chart-pie"></i>
                    <span class="sidebar-text">Dashboard</span>
                </a>
                <a href="<?= base_url('/admin/laporan') ?>" class="nav-item <?= ($activePage ?? '') === 'laporan' ? 'active' : '' ?>">
                    <i class="fas fa-file-alt"></i>
                    <span class="sidebar-text">Laporan</span>
                </a>
                <a href="<?= base_url('/admin/kontak') ?>" class="nav-item <?= ($activePage ?? '') === 'kontak' ? 'active' : '' ?>">
                    <i class="fas fa-address-book"></i>
                    <span class="sidebar-text">Kontak WA</span>
                </a>
                <a href="<?= base_url('/admin/monitoring') ?>" class="nav-item <?= ($activePage ?? '') === 'monitoring' ? 'active' : '' ?>">
                    <i class="fas fa-satellite-dish"></i>
                    <span class="sidebar-text">Monitoring</span>
                </a>
            </div>

            <div class="nav-section">
                <span class="nav-section-title sidebar-text">LAINNYA</span>
                <a href="<?= base_url('/') ?>" class="nav-item" target="_blank">
                    <i class="fas fa-external-link-alt"></i>
                    <span class="sidebar-text">Halaman Publik</span>
                </a>
                <a href="<?= base_url('/auth/logout') ?>" class="nav-item text-danger-custom">
                    <i class="fas fa-sign-out-alt"></i>
                    <span class="sidebar-text">Logout</span>
                </a>
            </div>
        </nav>

        <!-- Sidebar Footer -->
        <div class="sidebar-footer">
            <div class="user-info">
                <div class="user-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <div class="sidebar-text">
                    <span class="user-name"><?= esc(session()->get('username') ?? 'Admin') ?></span>
                    <span class="user-role">Administrator</span>
                </div>
            </div>
        </div>
    </aside>

    <!-- Sidebar Overlay (mobile) -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Main Content -->
    <div class="admin-content" id="adminContent">
        <!-- Top Bar -->
        <header class="admin-topbar">
            <div class="d-flex align-items-center gap-3">
                <button class="sidebar-toggle" id="toggleSidebar">
                    <i class="fas fa-bars"></i>
                </button>
                <div>
                    <h1 class="topbar-title"><?= esc($title ?? 'Dashboard') ?></h1>
                </div>
            </div>
            <div class="d-flex align-items-center gap-3">
                <span class="topbar-date">
                    <i class="fas fa-calendar-alt me-1"></i>
                    <?= date('d M Y') ?>
                </span>
                <button class="sidebar-collapse-btn d-none d-lg-flex" id="collapseSidebar" title="Toggle Sidebar">
                    <i class="fas fa-chevron-left"></i>
                </button>
            </div>
        </header>

        <!-- Page Content -->
        <div class="admin-page-content">
            <!-- Toast Notifications -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="toast-notification toast-success" id="flashToast">
                    <i class="fas fa-check-circle"></i>
                    <span><?= session()->getFlashdata('success') ?></span>
                    <button class="toast-close" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="toast-notification toast-error" id="flashToast">
                    <i class="fas fa-exclamation-circle"></i>
                    <span><?= session()->getFlashdata('error') ?></span>
                    <button class="toast-close" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>
                </div>
            <?php endif; ?>

            <?= $this->renderSection('content') ?>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JS -->
    <script src="<?= base_url('js/app.js') ?>"></script>

    <?= $this->renderSection('scripts') ?>
</body>
</html>
