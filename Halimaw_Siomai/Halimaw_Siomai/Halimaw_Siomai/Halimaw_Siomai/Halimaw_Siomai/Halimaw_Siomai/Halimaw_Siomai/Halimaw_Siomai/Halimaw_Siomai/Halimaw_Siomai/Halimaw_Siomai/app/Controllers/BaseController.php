<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

abstract class BaseController extends Controller
{
    protected $session;
    protected $request;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $this->session = service('session');
        $this->request = $request;

        // Redirect logged-in users away from login pages
        $this->redirectLoggedInUser();
    }

    /**
     * Enforce login and optional role.
     */
    protected function checkLogin($role = null)
    {
        $path = trim($this->request->getUri()->getPath(), '/');
        $isAdminArea = ($role === 'admin') || str_starts_with($path, 'admin');
        $loginPage = $isAdminArea ? '/admin/login' : '/login';

        // Allow public login/auth routes to be accessible by guests
        $publicPaths = [
            'admin/login',
            'admin/authenticate',
            'admin/logout',
            'login',
            'authenticate',
            'logout',
            'register',
        ];
        foreach ($publicPaths as $p) {
            if ($p !== '' && strpos($path, trim($p, '/')) === 0) {
                // It's a public path — do not enforce login here
                return;
            }
        }

        if (! $this->session->get('logged_in')) {
            return redirect()->to($loginPage)->with('error', 'Please log in first')->send();
        }

        if ($role && $this->session->get('role') !== $role) {
            // Logged in but wrong role — send to appropriate dashboard
            $userRole = $this->session->get('role');
            if ($userRole === 'admin') {
                return redirect()->to('/admin/dashboard')->with('error', 'Access denied')->send();
            }
            return redirect()->to('/user/dashboard')->with('error', 'Access denied')->send();
        }
    }

    protected function checkAdmin()
    {
        $this->checkLogin('admin');
    }

    protected function checkUser()
    {
        $this->checkLogin('user');
    }

    /**
     * If already logged in and visiting a login page, send to dashboard.
     */
    protected function redirectLoggedInUser()
    {
        $current = trim($this->request->getUri()->getPath(), '/');
        $session = session();

        if (! $session->get('logged_in')) {
            return; // guests — do nothing (prevents redirect loop)
        }

        $role = $session->get('role') ?? null;
        if ($role === 'admin' && in_array($current, ['admin/login', 'login', ''], true)) {
            return redirect()->to('/admin/dashboard')->send();
        }
        if ($role === 'user' && in_array($current, ['login','user/login',''], true)) {
            return redirect()->to('/user/dashboard')->send();
        }
    }
}
