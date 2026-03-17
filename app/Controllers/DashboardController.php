<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class DashboardController extends BaseController
{
    public function index()
    {
        if (! session()->get('logged_in')) {
            return redirect()->to(base_url('login'))
                             ->with('error', 'Please sign in to access the dashboard.');
        }

        return view('dashboard');
    }
}
