<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * KontakModel - Handles WhatsApp contact management
 */
class KontakModel extends Model
{
    protected $table            = 'kontak';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['nama', 'nomor_wa', 'is_active'];
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    // Validation rules
    protected $validationRules = [
        'nama'     => 'required|min_length[2]|max_length[255]',
        'nomor_wa' => 'required|min_length[10]|max_length[20]|regex_match[/^[0-9+]+$/]',
    ];

    protected $validationMessages = [
        'nama' => [
            'required'   => 'Nama kontak wajib diisi.',
            'min_length' => 'Nama minimal 2 karakter.',
        ],
        'nomor_wa' => [
            'required'    => 'Nomor WhatsApp wajib diisi.',
            'min_length'  => 'Nomor WhatsApp minimal 10 digit.',
            'max_length'  => 'Nomor WhatsApp maksimal 20 digit.',
            'regex_match' => 'Nomor WhatsApp hanya boleh berisi angka dan tanda +.',
        ],
    ];

    /**
     * Get all active contacts
     */
    public function getActiveContacts(): array
    {
        return $this->where('is_active', 1)->findAll();
    }

    /**
     * Get comma-separated list of active WA numbers
     */
    public function getActiveNumbers(): string
    {
        $contacts = $this->getActiveContacts();
        $numbers = array_column($contacts, 'nomor_wa');
        return implode(',', $numbers);
    }

    /**
     * Get total contacts count
     */
    public function getTotalKontak(): int
    {
        return $this->countAllResults();
    }
}
