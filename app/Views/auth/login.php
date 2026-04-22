<!DOCTYPE html>
<html lang="id" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Login - Darurat Bencana') ?></title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
</head>
<body class="login-page">

    <!-- Background Effects -->
    <div class="login-bg-effects">
        <div class="floating-orb orb-1"></div>
        <div class="floating-orb orb-2"></div>
        <div class="floating-orb orb-3"></div>
        <div class="grid-overlay"></div>
    </div>

    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-5 col-lg-4">
                <!-- Login Card -->
                <div class="glass-card login-card">
                    <!-- Logo -->
                    <div class="text-center mb-4">
                        <div class="login-logo">
                            <div class="brand-icon brand-icon-lg mx-auto">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                        </div>
                        <h3 class="mt-3 fw-bold">Darurat<span class="text-accent">Bencana</span></h3>
                        <p class="text-muted small">Login ke Dashboard Admin</p>
                    </div>

                    <!-- Flash Messages -->
                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger-custom mb-3">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success-custom mb-3">
                            <i class="fas fa-check-circle me-2"></i>
                            <?= session()->getFlashdata('success') ?>
                        </div>
                    <?php endif; ?>

                    <!-- Login Form -->
                    <form action="<?= base_url('/auth/login') ?>" method="POST" id="loginForm">
                        <?= csrf_field() ?>

                        <div class="form-floating-custom mb-3">
                            <label for="username"><i class="fas fa-user me-2"></i>Username</label>
                            <input type="text" class="form-control glass-input" id="username" name="username" 
                                   placeholder="Masukkan username" value="<?= old('username') ?>" required autofocus>
                        </div>

                        <div class="form-floating-custom mb-4">
                            <label for="password"><i class="fas fa-lock me-2"></i>Password</label>
                            <div class="position-relative">
                                <input type="password" class="form-control glass-input" id="password" name="password" 
                                       placeholder="Masukkan password" required>
                                <button type="button" class="password-toggle" id="togglePassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-hero-primary w-100 btn-lg" id="loginBtn">
                            <span class="btn-text"><i class="fas fa-sign-in-alt me-2"></i>Masuk</span>
                            <span class="btn-loading d-none">
                                <span class="spinner-border spinner-border-sm me-2"></span>Memproses...
                            </span>
                        </button>
                    </form>

                    <div class="text-center mt-4">
                        <a href="<?= base_url('/') ?>" class="text-muted small">
                            <i class="fas fa-arrow-left me-1"></i> Kembali ke Beranda
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const pwd = document.getElementById('password');
            const icon = this.querySelector('i');
            if (pwd.type === 'password') {
                pwd.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                pwd.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });

        // Login loading state
        document.getElementById('loginForm').addEventListener('submit', function() {
            const btn = document.getElementById('loginBtn');
            btn.querySelector('.btn-text').classList.add('d-none');
            btn.querySelector('.btn-loading').classList.remove('d-none');
            btn.disabled = true;
        });
    </script>
</body>
</html>
