<?php

namespace App\Controllers;

use App\Models\LaporanModel;

/**
 * Monitoring Controller - Real-time report monitoring
 */
class Monitoring extends BaseController
{
    protected LaporanModel $laporanModel;

    public function __construct()
    {
        $this->laporanModel = new LaporanModel();
    }

    /**
     * Monitoring page
     */
    public function index(): string
    {
        $data = [
            'title'      => 'Monitoring Laporan - Darurat Bencana',
            'laporan'    => $this->laporanModel->orderBy('tanggal', 'DESC')->findAll(),
            'activePage' => 'monitoring',
        ];

        return view('admin/monitoring', $data);
    }

    /**
     * AJAX endpoint: Get latest reports for auto-refresh
     */
    public function getLatestReports()
    {
        $laporan = $this->laporanModel->orderBy('tanggal', 'DESC')->findAll();

        return $this->response->setJSON([
            'success' => true,
            'data'    => $laporan,
            'count'   => count($laporan),
        ]);
    }
}
