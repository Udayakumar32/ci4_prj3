<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class DashboardController extends BaseController
{
    // ─────────────────────────────────────────────────────────────
    // Shared auth + no-cache guard
    // ─────────────────────────────────────────────────────────────
    private function authGuard(): void
    {
        if (!session()->get('logged_in')) {
            redirect()->to(base_url('login'))->send();
            exit;
        }

        $this->response->setHeader('Cache-Control', 'no-store, max-age=0, no-cache');
        $this->response->setHeader('Pragma', 'no-cache');
        $this->response->setHeader('Expires', 'Sat, 26 Jul 1997 05:00:00 GMT');
    }

    // ─────────────────────────────────────────────────────────────
    // GET  /dashboard
    // ─────────────────────────────────────────────────────────────
    public function index()
    {
        $this->authGuard();

        $model         = new UserModel();
        $currentUserId = session()->get('user_id');

        // Fetch the logged-in user's own full record so My Profile
        // always shows the latest DB values (not just session data).
        $currentUser = $model->find($currentUserId);

        // Refresh session with latest DB values in case anything changed
        // Using ?? null safely in case your table doesn't have every column yet
        if ($currentUser) {
            session()->set([
                'username'     => $currentUser['username']     ?? null,
                'email'        => $currentUser['email']        ?? null,
                'phone_number' => $currentUser['phone_number'] ?? null,
                'gender'       => $currentUser['gender']       ?? null,
                'role'         => $currentUser['role']         ?? session()->get('role') ?? 'user',
                'created_at'   => $currentUser['created_at']  ?? null,
            ]);
        }

        $data['users']       = $model->where('id !=', $currentUserId)->findAll();
        $data['currentUser'] = $currentUser;

        return view('dashboard', $data);
    }

    // ─────────────────────────────────────────────────────────────
    // POST  /users/update/{id}   (admin only)
    // ─────────────────────────────────────────────────────────────
    public function update($id = null)
    {
        $this->authGuard();

        if (session()->get('role') !== 'admin') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Unauthorized action.');
        }

        if (!$id) {
            return redirect()->to(base_url('dashboard'))->with('error', 'Invalid user ID.');
        }

        $model = new UserModel();

        $rules = [
            'username'     => 'required|min_length[3]|max_length[100]',
            'email'        => 'required|valid_email',
            'phone_number' => 'permit_empty|max_length[20]',
            'gender'       => 'permit_empty|in_list[male,female,other]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('error', implode('<br>', $this->validator->getErrors()));
        }

        $updateData = [
            'username'     => $this->request->getPost('username'),
            'email'        => $this->request->getPost('email'),
            'phone_number' => $this->request->getPost('phone_number'),
            'gender'       => $this->request->getPost('gender'),
        ];

        $model->update($id, $updateData);

        return redirect()->to(base_url('dashboard'))->with('success', 'User updated successfully.');
    }

    // ─────────────────────────────────────────────────────────────
    // POST  /users/delete/{id}   (admin only)
    // ─────────────────────────────────────────────────────────────
    public function delete($id = null)
    {
        $this->authGuard();

        if (session()->get('role') !== 'admin') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Unauthorized action.');
        }

        if (!$id) {
            return redirect()->to(base_url('dashboard'))->with('error', 'Invalid user ID.');
        }

        // Prevent admin from deleting themselves
        if ((int)$id === (int)session()->get('user_id')) {
            return redirect()->to(base_url('dashboard'))->with('error', 'You cannot delete your own account.');
        }

        $model = new UserModel();
        $model->delete($id);

        return redirect()->to(base_url('dashboard'))->with('success', 'User deleted successfully.');
    }

    // ─────────────────────────────────────────────────────────────
    // GET  /users/export/csv     (admin only) — server-side fallback
    // ─────────────────────────────────────────────────────────────
    public function exportCSV()
    {
        $this->authGuard();

        if (session()->get('role') !== 'admin') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Unauthorized action.');
        }

        $model         = new UserModel();
        $currentUserId = session()->get('user_id');
        $users         = $model->where('id !=', $currentUserId)->findAll();

        $filename = 'users_' . date('Y-m-d') . '.csv';

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['#', 'Username', 'Email', 'Gender', 'Phone', 'Role', 'Registered Date']);

        $i = 1;
        foreach ($users as $user) {
            fputcsv($output, [
                $i++,
                $user['username'],
                $user['email']        ?? 'N/A',
                $user['gender']       ?? 'N/A',
                $user['phone_number'] ?? 'N/A',
                $user['role']         ?? session()->get('role') ?? 'user',
                date('M d, Y', strtotime($user['created_at'])),
            ]);
        }

        fclose($output);
        exit;
    }
}