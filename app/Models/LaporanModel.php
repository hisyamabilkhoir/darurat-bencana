<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * LaporanModel - Handles disaster report CRUD and statistics
 */
class LaporanModel extends Model
{
    protected $table            = 'laporan';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'nama_pelapor', 'kategori', 'lokasi', 'deskripsi',
        'foto', 'tanggal', 'is_urgent', 'status', 'wa_notified'
    ];
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    // Validation rules for report submission
    protected $validationRules = [
        'nama_pelapor' => 'required|min_length[3]|max_length[255]',
        'kategori'     => 'required|in_list[Banjir,Gempa,Kebakaran,Longsor,Tsunami,Angin Topan,Lainnya]',
        'lokasi'       => 'required|min_length[5]|max_length[255]',
        'deskripsi'    => 'required|min_length[10]',
    ];

    protected $validationMessages = [
        'nama_pelapor' => [
            'required'   => 'Nama pelapor wajib diisi.',
            'min_length' => 'Nama pelapor minimal 3 karakter.',
        ],
        'kategori' => [
            'required' => 'Kategori bencana wajib dipilih.',
            'in_list'  => 'Kategori bencana tidak valid.',
        ],
        'lokasi' => [
            'required'   => 'Lokasi wajib diisi.',
            'min_length' => 'Lokasi minimal 5 karakter.',
        ],
        'deskripsi' => [
            'required'   => 'Deskripsi detail wajib diisi.',
            'min_length' => 'Deskripsi minimal 10 karakter.',
        ],
    ];

    /**
     * Get total reports count
     */
    public function getTotalLaporan(): int
    {
        return $this->countAllResults();
    }

    /**
     * Get count by status
     */
    public function getCountByStatus(string $status): int
    {
        return $this->where('status', $status)->countAllResults();
    }

    /**
     * Get reports count grouped by category
     */
    public function getCountByKategori(): array
    {
        return $this->select('kategori, COUNT(*) as total')
                    ->groupBy('kategori')
                    ->orderBy('total', 'DESC')
                    ->findAll();
    }

    /**
     * Get daily report counts for the last N days
     */
    public function getDailyReports(int $days = 30): array
    {
        return $this->select('DATE(tanggal) as date, COUNT(*) as total')
                    ->where('tanggal >=', date('Y-m-d', strtotime("-{$days} days")))
                    ->groupBy('DATE(tanggal)')
                    ->orderBy('date', 'ASC')
                    ->findAll();
    }

    /**
     * Get filtered reports
     */
    public function getFiltered(?string $kategori = null, ?string $startDate = null, ?string $endDate = null, ?string $lokasi = null, ?string $status = null): array
    {
        $builder = $this->builder();

        if ($kategori) {
            $builder->where('kategori', $kategori);
        }
        if ($startDate) {
            $builder->where('DATE(tanggal) >=', $startDate);
        }
        if ($endDate) {
            $builder->where('DATE(tanggal) <=', $endDate);
        }
        if ($lokasi) {
            $builder->like('lokasi', $lokasi);
        }
        if ($status) {
            $builder->where('status', $status);
        }

        return $builder->orderBy('tanggal', 'DESC')->get()->getResultArray();
    }

    /**
     * Get recent reports for dashboard
     */
    public function getRecentReports(int $limit = 5): array
    {
        return $this->orderBy('tanggal', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * Get all disaster categories
     */
    public static function getKategoriList(): array
    {
        return ['Banjir', 'Gempa', 'Kebakaran', 'Longsor', 'Tsunami', 'Angin Topan', 'Lainnya'];
    }
}
