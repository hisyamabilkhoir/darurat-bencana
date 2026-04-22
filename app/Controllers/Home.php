<?php

namespace App\Controllers;

use App\Models\LaporanModel;
use App\Libraries\FonteService;

/**
 * Home Controller - Handles public-facing pages
 */
class Home extends BaseController
{
    protected LaporanModel $laporanModel;

    public function __construct()
    {
        $this->laporanModel = new LaporanModel();
    }

    /**
     * Landing page
     */
    public function index(): string
    {
        $data = [
            'title'        => 'Darurat Bencana - Platform Pelaporan Bencana',
            'totalLaporan' => $this->laporanModel->getTotalLaporan(),
        ];

        return view('public/landing', $data);
    }

    /**
     * Display report form
     */
    public function lapor(): string
    {
        $data = [
            'title'     => 'Laporkan Bencana - Darurat Bencana',
            'kategori'  => LaporanModel::getKategoriList(),
        ];

        return view('public/lapor', $data);
    }

    /**
     * Process report form submission
     */
    public function submitLaporan()
    {
        // Validation rules
        $rules = [
            'nama_pelapor' => 'required|min_length[3]|max_length[255]',
            'kategori'     => 'required|in_list[Banjir,Gempa,Kebakaran,Longsor,Tsunami,Angin Topan,Lainnya]',
            'lokasi'       => 'required|min_length[5]|max_length[255]',
            'deskripsi'    => 'required|min_length[10]',
        ];

        $messages = [
            'nama_pelapor' => [
                'required'   => 'Nama pelapor wajib diisi.',
                'min_length' => 'Nama pelapor minimal 3 karakter.',
            ],
            'kategori' => [
                'required' => 'Kategori bencana wajib dipilih.',
                'in_list'  => 'Kategori bencana tidak valid.',
            ],
            'lokasi' => [
                'required'   => 'Lokasi kejadian wajib diisi.',
                'min_length' => 'Lokasi minimal 5 karakter.',
            ],
            'deskripsi' => [
                'required'   => 'Deskripsi detail wajib diisi.',
                'min_length' => 'Deskripsi minimal 10 karakter.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return $this->response->setJSON([
                'success' => false,
                'errors'  => $this->validator->getErrors(),
            ]);
        }

        // Handle file upload
        $fotoName = null;
        $foto = $this->request->getFile('foto');
        if ($foto && $foto->isValid() && !$foto->hasMoved()) {
            // Validate file
            $validationRules = [
                'foto' => 'uploaded[foto]|max_size[foto,2048]|is_image[foto]|mime_in[foto,image/jpg,image/jpeg,image/png,image/webp]',
            ];
            if (!$this->validate($validationRules)) {
                return $this->response->setJSON([
                    'success' => false,
                    'errors'  => ['foto' => 'File harus berupa gambar (JPG/PNG/WebP) dan maksimal 2MB.'],
                ]);
            }

            $fotoName = $foto->getRandomName();
            $foto->move(FCPATH . 'uploads/laporan', $fotoName);
        }

        // Prepare data
        $laporanData = [
            'nama_pelapor' => esc($this->request->getPost('nama_pelapor')),
            'kategori'     => $this->request->getPost('kategori'),
            'lokasi'       => esc($this->request->getPost('lokasi')),
            'deskripsi'    => esc($this->request->getPost('deskripsi')),
            'foto'         => $fotoName,
            'tanggal'      => date('Y-m-d H:i:s'),
            'is_urgent'    => $this->request->getPost('is_urgent') ? 1 : 0,
            'status'       => 'baru',
            'wa_notified'  => 0,
        ];

        // Save to database
        $this->laporanModel->insert($laporanData);
        $insertId = $this->laporanModel->getInsertID();

        // Send WhatsApp notification
        $waResult = ['status' => false];
        try {
            $fonteService = new FonteService();
            $waResult = $fonteService->sendDisasterNotification($laporanData);

            // Update wa_notified status
            if (isset($waResult['status']) && $waResult['status'] === true) {
                $this->laporanModel->update($insertId, ['wa_notified' => 1]);
            }
        } catch (\Exception $e) {
            log_message('error', 'WhatsApp notification failed: ' . $e->getMessage());
        }

        return $this->response->setJSON([
            'success'    => true,
            'message'    => 'Laporan bencana berhasil dikirim! Terima kasih atas partisipasi Anda.',
            'wa_status'  => $waResult['status'] ?? false,
        ]);
    }
}
