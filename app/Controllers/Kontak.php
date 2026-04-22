<?php

namespace App\Controllers;

use App\Models\KontakModel;

/**
 * Kontak Controller - CRUD for WhatsApp contacts
 */
class Kontak extends BaseController
{
    protected KontakModel $kontakModel;

    public function __construct()
    {
        $this->kontakModel = new KontakModel();
    }

    /**
     * List all contacts
     */
    public function index(): string
    {
        $data = [
            'title'      => 'Manajemen Kontak - Darurat Bencana',
            'kontak'     => $this->kontakModel->orderBy('created_at', 'DESC')->findAll(),
            'activePage' => 'kontak',
        ];

        return view('admin/kontak/index', $data);
    }

    /**
     * Store new contact
     */
    public function store()
    {
        $rules = [
            'nama'     => 'required|min_length[2]|max_length[255]',
            'nomor_wa' => 'required|min_length[10]|max_length[20]|regex_match[/^[0-9+]+$/]',
        ];

        $messages = [
            'nama' => [
                'required'   => 'Nama kontak wajib diisi.',
                'min_length' => 'Nama minimal 2 karakter.',
            ],
            'nomor_wa' => [
                'required'    => 'Nomor WhatsApp wajib diisi.',
                'min_length'  => 'Nomor WhatsApp minimal 10 digit.',
                'regex_match' => 'Nomor WhatsApp hanya boleh berisi angka.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->to('/admin/kontak')
                             ->withInput()
                             ->with('errors', $this->validator->getErrors());
        }

        $this->kontakModel->insert([
            'nama'      => esc($this->request->getPost('nama')),
            'nomor_wa'  => $this->request->getPost('nomor_wa'),
            'is_active' => 1,
        ]);

        return redirect()->to('/admin/kontak')->with('success', 'Kontak berhasil ditambahkan.');
    }

    /**
     * Update existing contact
     */
    public function update(int $id)
    {
        $kontak = $this->kontakModel->find($id);
        if (!$kontak) {
            return redirect()->to('/admin/kontak')->with('error', 'Kontak tidak ditemukan.');
        }

        $rules = [
            'nama'     => 'required|min_length[2]|max_length[255]',
            'nomor_wa' => 'required|min_length[10]|max_length[20]|regex_match[/^[0-9+]+$/]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/admin/kontak')
                             ->withInput()
                             ->with('errors', $this->validator->getErrors());
        }

        $this->kontakModel->update($id, [
            'nama'      => esc($this->request->getPost('nama')),
            'nomor_wa'  => $this->request->getPost('nomor_wa'),
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
        ]);

        return redirect()->to('/admin/kontak')->with('success', 'Kontak berhasil diperbarui.');
    }

    /**
     * Delete contact
     */
    public function delete(int $id)
    {
        $kontak = $this->kontakModel->find($id);
        if (!$kontak) {
            return redirect()->to('/admin/kontak')->with('error', 'Kontak tidak ditemukan.');
        }

        $this->kontakModel->delete($id);

        return redirect()->to('/admin/kontak')->with('success', 'Kontak berhasil dihapus.');
    }
}
