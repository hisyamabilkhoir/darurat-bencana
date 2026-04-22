<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>

<section class="report-section">
    <div class="container py-5" style="margin-top: 80px;">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Header -->
                <div class="text-center mb-4">
                    <span class="section-badge"><i class="fas fa-exclamation-triangle me-2"></i>Form Pelaporan</span>
                    <h2 class="section-title mt-3">Laporkan <span class="gradient-text">Bencana</span></h2>
                    <p class="section-subtitle">Isi formulir di bawah ini untuk melaporkan bencana di lokasi Anda</p>
                </div>

                <!-- Report Form -->
                <div class="glass-card form-card">
                    <form id="reportForm" enctype="multipart/form-data">
                        <!-- Nama Pelapor -->
                        <div class="form-floating-custom mb-4">
                            <label for="nama_pelapor"><i class="fas fa-user me-2"></i>Nama Pelapor</label>
                            <input type="text" class="form-control glass-input" id="nama_pelapor" name="nama_pelapor" 
                                   placeholder="Masukkan nama lengkap Anda" required minlength="3" maxlength="255">
                            <div class="invalid-feedback" id="error_nama_pelapor"></div>
                        </div>

                        <!-- Kategori Bencana -->
                        <div class="form-floating-custom mb-4">
                            <label for="kategori"><i class="fas fa-layer-group me-2"></i>Kategori Bencana</label>
                            <select class="form-select glass-input" id="kategori" name="kategori" required>
                                <option value="">-- Pilih Kategori Bencana --</option>
                                <?php foreach ($kategori as $kat): ?>
                                    <option value="<?= $kat ?>"><?= $kat ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback" id="error_kategori"></div>
                        </div>

                        <!-- Lokasi -->
                        <div class="form-floating-custom mb-4">
                            <label for="lokasi"><i class="fas fa-map-marker-alt me-2"></i>Lokasi Kejadian</label>
                            <input type="text" class="form-control glass-input" id="lokasi" name="lokasi" 
                                   placeholder="Masukkan lokasi detail kejadian" required minlength="5" maxlength="255">
                            <div class="invalid-feedback" id="error_lokasi"></div>
                        </div>

                        <!-- Deskripsi -->
                        <div class="form-floating-custom mb-4">
                            <label for="deskripsi"><i class="fas fa-align-left me-2"></i>Deskripsi Detail</label>
                            <textarea class="form-control glass-input" id="deskripsi" name="deskripsi" 
                                      rows="5" placeholder="Jelaskan kondisi bencana secara detail..." required minlength="10"></textarea>
                            <div class="invalid-feedback" id="error_deskripsi"></div>
                        </div>

                        <!-- Upload Foto -->
                        <div class="form-floating-custom mb-4">
                            <label><i class="fas fa-camera me-2"></i>Upload Foto (Opsional)</label>
                            <div class="upload-area" id="uploadArea">
                                <input type="file" class="d-none" id="foto" name="foto" accept="image/jpeg,image/png,image/webp">
                                <div class="upload-placeholder" id="uploadPlaceholder">
                                    <i class="fas fa-cloud-upload-alt fa-2x mb-2"></i>
                                    <p class="mb-1">Klik atau seret foto ke sini</p>
                                    <small class="text-muted">JPG, PNG, WebP (maks. 2MB)</small>
                                </div>
                                <div class="upload-preview d-none" id="uploadPreview">
                                    <img id="previewImage" src="" alt="Preview">
                                    <button type="button" class="btn btn-sm btn-remove-preview" id="removePreview">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Urgent Toggle -->
                        <div class="form-floating-custom mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_urgent" name="is_urgent" value="1">
                                <label class="form-check-label" for="is_urgent">
                                    <i class="fas fa-bolt me-1 text-warning"></i>
                                    Tandai sebagai <strong>DARURAT</strong>
                                </label>
                            </div>
                        </div>

                        <!-- Auto Date -->
                        <div class="form-floating-custom mb-4">
                            <div class="auto-date glass-card-inner">
                                <i class="fas fa-calendar-alt me-2 text-accent"></i>
                                <span>Tanggal Laporan: <strong id="currentDate"><?= date('d M Y, H:i') ?> WIB</strong></span>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-hero-primary w-100 btn-lg" id="submitBtn">
                            <span class="btn-text">
                                <i class="fas fa-paper-plane me-2"></i>Kirim Laporan
                            </span>
                            <span class="btn-loading d-none">
                                <span class="spinner-border spinner-border-sm me-2"></span>Mengirim...
                            </span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Toast Container -->
<div class="toast-container-custom" id="toastContainer"></div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('reportForm');
    const submitBtn = document.getElementById('submitBtn');
    const uploadArea = document.getElementById('uploadArea');
    const fileInput = document.getElementById('foto');
    const uploadPlaceholder = document.getElementById('uploadPlaceholder');
    const uploadPreview = document.getElementById('uploadPreview');
    const previewImage = document.getElementById('previewImage');
    const removePreview = document.getElementById('removePreview');

    // Upload area click
    uploadArea.addEventListener('click', function(e) {
        if (e.target !== removePreview && !removePreview.contains(e.target)) {
            fileInput.click();
        }
    });

    // Drag & Drop
    uploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.classList.add('drag-over');
    });
    uploadArea.addEventListener('dragleave', function() {
        this.classList.remove('drag-over');
    });
    uploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        this.classList.remove('drag-over');
        if (e.dataTransfer.files.length) {
            fileInput.files = e.dataTransfer.files;
            showPreview(e.dataTransfer.files[0]);
        }
    });

    // File input change
    fileInput.addEventListener('change', function() {
        if (this.files.length) {
            showPreview(this.files[0]);
        }
    });

    // Show image preview
    function showPreview(file) {
        if (file.size > 2 * 1024 * 1024) {
            showToast('Ukuran file maksimal 2MB!', 'error');
            return;
        }
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImage.src = e.target.result;
            uploadPlaceholder.classList.add('d-none');
            uploadPreview.classList.remove('d-none');
        };
        reader.readAsDataURL(file);
    }

    // Remove preview
    removePreview.addEventListener('click', function(e) {
        e.stopPropagation();
        fileInput.value = '';
        previewImage.src = '';
        uploadPreview.classList.add('d-none');
        uploadPlaceholder.classList.remove('d-none');
    });

    // Form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        // Clear previous errors
        document.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
        document.querySelectorAll('.glass-input').forEach(el => el.classList.remove('is-invalid'));

        // Show loading
        submitBtn.querySelector('.btn-text').classList.add('d-none');
        submitBtn.querySelector('.btn-loading').classList.remove('d-none');
        submitBtn.disabled = true;

        const formData = new FormData(form);

        fetch('<?= base_url('/lapor/submit') ?>', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                form.reset();
                // Reset preview
                previewImage.src = '';
                uploadPreview.classList.add('d-none');
                uploadPlaceholder.classList.remove('d-none');
            } else if (data.errors) {
                Object.keys(data.errors).forEach(key => {
                    const input = document.getElementById(key);
                    const errorDiv = document.getElementById('error_' + key);
                    if (input) input.classList.add('is-invalid');
                    if (errorDiv) errorDiv.textContent = data.errors[key];
                });
                showToast('Mohon periksa kembali data yang diisi.', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Terjadi kesalahan. Silakan coba lagi.', 'error');
        })
        .finally(() => {
            submitBtn.querySelector('.btn-text').classList.remove('d-none');
            submitBtn.querySelector('.btn-loading').classList.add('d-none');
            submitBtn.disabled = false;
        });
    });

    // Toast notification
    function showToast(message, type = 'success') {
        const container = document.getElementById('toastContainer');
        const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
        const toast = document.createElement('div');
        toast.className = `toast-notification toast-${type} show`;
        toast.innerHTML = `<i class="fas ${icon}"></i><span>${message}</span><button class="toast-close" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>`;
        container.appendChild(toast);
        setTimeout(() => toast.remove(), 5000);
    }
});
</script>
<?= $this->endSection() ?>
