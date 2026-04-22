<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * AuthFilter - Protects admin routes by checking session authentication
 */
class AuthFilter implements FilterInterface
{
    /**
     * Check if user is logged in before accessing admin pages
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // If not logged in, redirect to login page
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/auth/login')->with('error', 'Silakan login terlebih dahulu.');
        }
    }

    /**
     * After filter - not used but required by interface
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No action needed after response
    }
}
