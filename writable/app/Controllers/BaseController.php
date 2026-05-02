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

    protected function checkLogin($role = null)
    {
        if (!$this->session->get('logged_in')) {
            $loginPage = ($role === 'admin') ? '/admin/login' : '/login';
            return redirect()->to($loginPage)->with('error', 'Please login first')->send();
        }

        if ($role && $this->session->get('role') !== $role) {
            $userRole = $this->session->get('role');
            if ($userRole === 'admin') {
                return redirect()->to('/admin/dashboard')->with('error', 'Access denied')->send();
            } else {
                return redirect()->to('/user/dashboard')->with('error', 'Access denied')->send();
            }
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

    protected function redirectLoggedInUser()
    {
        $currentUri = $this->request->getUri()->getPath(); // ✅ Fixed here
        $loggedIn = $this->session->get('logged_in');
        $role = $this->session->get('role');

        if ($loggedIn) {
            if ($role === 'admin' && ($currentUri === 'admin/login' || $currentUri === 'login')) {
                return redirect()->to('/admin/dashboard')->send();
            }
            if ($role === 'user' && ($currentUri === 'login' || $currentUri === 'user/login')) {
                return redirect()->to('/user/dashboard')->send();
            }
        }
    }
}
