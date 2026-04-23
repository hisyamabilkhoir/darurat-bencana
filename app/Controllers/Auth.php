<?php

namespace App\Controllers;

use App\Models\UserModel;

/**
 * Auth Controller - Handles admin login/logout
 */
class Auth extends BaseController
{
    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * Display login page
     */
    public function login()
    {
        // If already logged in, redirect to dashboard
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/admin/dashboard');
        }

        $data = [
            'title' => 'Login Admin - Darurat Bencana',
        ];

        return view('auth/login', $data);
    }

    /**
     * Process login form
     */
    public function processLogin()
    {
        $rules = [
            'username' => 'required',
            'password' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/auth/login')
                             ->withInput()
                             ->with('error', 'Username dan password wajib diisi.');
        }

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $user = $this->userModel->authenticate($username, $password);

        if ($user) {
            // Set session data
            session()->set([
                'userId'     => $user['id'],
                'username'   => $user['username'],
                'email'      => $user['email'],
                'role'       => $user['role'],
                'isLoggedIn' => true,
            ]);

            return redirect()->to('/admin/dashboard')
                             ->with('success', 'Selamat datang, ' . $user['username'] . '!');
        }

        return redirect()->to('/auth/login')
                         ->withInput()
                         ->with('error', 'Username atau password salah.');
    }

    /**
     * Logout and destroy session
     */
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/auth/login')
                         ->with('success', 'Anda telah berhasil logout.');
    }
}
