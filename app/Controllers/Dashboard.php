<?php

namespace App\Controllers;

use App\Models\LaporanModel;
use App\Models\KontakModel;

/**
 * Dashboard Controller - Admin dashboard, reports management, CSV export
 */
class Dashboard extends BaseController
{
    protected LaporanModel $laporanModel;
    protected KontakModel $kontakModel;

    public function __construct()
    {
        $this->laporanModel = new LaporanModel();
        $this->kontakModel  = new KontakModel();
    }

    /**
     * Dashboard overview with stats and charts
     */
    public function index(): string
    {
        $data = [
            'title'          => 'Dashboard - Darurat Bencana',
            'totalLaporan'   => $this->laporanModel->getTotalLaporan(),
            'totalBaru'      => $this->laporanModel->getCountByStatus('baru'),
            'totalDiproses'  => $this->laporanModel->getCountByStatus('diproses'),
            'totalSelesai'   => $this->laporanModel->getCountByStatus('selesai'),
            'totalKontak'    => $this->kontakModel->getTotalKontak(),
            'kategoriStats'  => $this->laporanModel->getCountByKategori(),
            'dailyReports'   => $this->laporanModel->getDailyReports(30),
            'recentReports'  => $this->laporanModel->getRecentReports(5),
            'activePage'     => 'dashboard',
        ];

        return view('admin/dashboard', $data);
    }

    /**
     * List all reports with filters
     */
    public function laporan(): string
    {
        $kategori  = $this->request->getGet('kategori');
        $startDate = $this->request->getGet('start_date');
        $endDate   = $this->request->getGet('end_date');
        $lokasi    = $this->request->getGet('lokasi');
        $status    = $this->request->getGet('status');

        $data = [
            'title'        => 'Manajemen Laporan - Darurat Bencana',
            'laporan'      => $this->laporanModel->getFiltered($kategori, $startDate, $endDate, $lokasi, $status),
            'kategoriList' => LaporanModel::getKategoriList(),
            'filters'      => [
                'kategori'   => $kategori,
                'start_date' => $startDate,
                'end_date'   => $endDate,
                'lokasi'     => $lokasi,
                'status'     => $status,
            ],
            'activePage'   => 'laporan',
        ];

        return view('admin/laporan/index', $data);
    }

    /**
     * View single report detail
     */
    public function detailLaporan(int $id): string
    {
        $laporan = $this->laporanModel->find($id);

        if (!$laporan) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Laporan tidak ditemukan.');
        }

        $data = [
            'title'      => 'Detail Laporan #' . $id . ' - Darurat Bencana',
            'laporan'    => $laporan,
            'activePage' => 'laporan',
        ];

        return view('admin/laporan/detail', $data);
    }

    /**
     * Delete a report
     */
    public function deleteLaporan(int $id)
    {
        $laporan = $this->laporanModel->find($id);

        if (!$laporan) {
            return redirect()->to('/admin/laporan')->with('error', 'Laporan tidak ditemukan.');
        }

        // Delete associated photo if exists
        if ($laporan['foto'] && file_exists(FCPATH . 'uploads/laporan/' . $laporan['foto'])) {
            unlink(FCPATH . 'uploads/laporan/' . $laporan['foto']);
        }

        $this->laporanModel->delete($id);

        return redirect()->to('/admin/laporan')->with('success', 'Laporan berhasil dihapus.');
    }

    /**
     * Update report status
     */
    public function updateStatus(int $id)
    {
        $status = $this->request->getPost('status');

        if (!in_array($status, ['baru', 'diproses', 'selesai'])) {
            return redirect()->back()->with('error', 'Status tidak valid.');
        }

        $this->laporanModel->update($id, ['status' => $status]);

        return redirect()->back()->with('success', 'Status laporan berhasil diperbarui.');
    }

    /**
     * Export reports to CSV
     */
    public function exportCsv()
    {
        $kategori  = $this->request->getGet('kategori');
        $startDate = $this->request->getGet('start_date');
        $endDate   = $this->request->getGet('end_date');

        $laporan = $this->laporanModel->getFiltered($kategori, $startDate, $endDate);

        $filename = 'laporan_bencana_' . date('Y-m-d_H-i-s') . '.csv';

        // Set headers for CSV download
        $this->response->setHeader('Content-Type', 'text/csv; charset=utf-8');
        $this->response->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');

        // BOM for Excel UTF-8 compatibility
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

        // CSV header
        fputcsv($output, [
            'No', 'Nama Pelapor', 'Kategori', 'Lokasi', 'Deskripsi',
            'Tanggal', 'Status', 'Urgent', 'Notifikasi WA'
        ]);

        // CSV data rows
        foreach ($laporan as $index => $row) {
            fputcsv($output, [
                $index + 1,
                $row['nama_pelapor'],
                $row['kategori'],
                $row['lokasi'],
                $row['deskripsi'],
                $row['tanggal'],
                ucfirst($row['status']),
                $row['is_urgent'] ? 'Ya' : 'Tidak',
                $row['wa_notified'] ? 'Terkirim' : 'Belum',
            ]);
        }

        fclose($output);
        $this->response->send();
        exit;
    }

    /**
     * API endpoint for dashboard chart data
     */
    public function getStats()
    {
        $data = [
            'totalLaporan'  => $this->laporanModel->getTotalLaporan(),
            'totalBaru'     => $this->laporanModel->getCountByStatus('baru'),
            'totalDiproses' => $this->laporanModel->getCountByStatus('diproses'),
            'totalSelesai'  => $this->laporanModel->getCountByStatus('selesai'),
            'totalKontak'   => $this->kontakModel->getTotalKontak(),
            'kategoriStats' => $this->laporanModel->getCountByKategori(),
            'dailyReports'  => $this->laporanModel->getDailyReports(30),
        ];

        return $this->response->setJSON($data);
    }
}
