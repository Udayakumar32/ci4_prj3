<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel; // Make sure to import your model

class DashboardController extends BaseController
{
    public function index()
    {
        // 1. Auth Check
       if (!session()->get('logged_in')) {
        return redirect()->to(base_url('login'));
    }

    // Add these headers to prevent browser from caching the dashboard
    $this->response->setHeader('Cache-Control', 'no-store, max-age=0, no-cache');
    $this->response->setHeader('Pragma', 'no-cache');
    $this->response->setHeader('Expires', 'Sat, 26 Jul 1997 05:00:00 GMT'); // Date in the past

        // 2. Fetch Users excluding the current one
        $model = new UserModel();
        $currentUserId = session()->get('user_id'); 
        
        $data['users'] = $model->where('id !=', $currentUserId)->findAll();

        // 3. Pass data to the view
        return view('dashboard', $data);
    }
}
