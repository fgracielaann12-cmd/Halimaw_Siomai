<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AdminAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $path = trim((string) $request->getUri()->getPath(), '/');

        // allow admin login/authenticate to avoid loop
        if ($path === 'admin/login' || $path === 'admin/authenticate') {
            return;
        }

        $session = session();
        if (! $session->get('logged_in') || $session->get('role') !== 'admin') {
            return redirect()->to('/admin/login');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // no-op
    }
}
