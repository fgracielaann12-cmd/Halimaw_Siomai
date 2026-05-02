<?php
namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        $path = $request->getUri()->getPath();

        // ✅ Skip the login & register pages
        if (in_array($path, ['login', 'register', 'admin/login'])) {
            return;
        }

        // ✅ Not logged in → redirect to login
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        // ✅ Role check (if arguments passed)
        if (!empty($arguments)) {
            $requiredRole = is_array($arguments) ? $arguments[0] : $arguments;

            if ($session->get('role') !== $requiredRole) {
                if ($session->get('role') === 'admin') {
                    return redirect()->to('/admin/dashboard');
                } else {
                    return redirect()->to('/user/dashboard');
                }
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // noop
    }
}
