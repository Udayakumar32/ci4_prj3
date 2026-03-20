<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class DashboardController extends BaseController
{
    private $encrypter;

    public function __construct()
    {
        $this->encrypter = \Config\Services::encrypter();
    }

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

    private function decryptEmail($encryptedEmail)
    {
        if (empty($encryptedEmail)) return 'N/A';
        try {
            return $this->encrypter->decrypt(base64_decode($encryptedEmail));
        } catch (\Exception $e) {
            return $encryptedEmail;
        }
    }

    public function index()
    {
        $this->authGuard();

        $model         = new UserModel();
        $currentUserId = session()->get('user_id');
        $currentUser   = $model->find($currentUserId);

        if ($currentUser) {
            $currentUser['email'] = $this->decryptEmail($currentUser['email']);

            session()->set([
                'username'     => $currentUser['username']     ?? null,
                'email'        => $currentUser['email']        ?? null,
                'phone_number' => $currentUser['phone_number'] ?? null,
                'gender'       => $currentUser['gender']       ?? null,
                'user_type'    => $currentUser['user_type']    ?? 'user',
                'created_at'   => $currentUser['created_at']   ?? null,
            ]);
        }

        $data['currentUser'] = $currentUser;

        // NO more $users loop here — DataTables fetches via Ajax
        return view('dashboard', $data);
    }

    // ─────────────────────────────────────────────────────
    //  DATATABLE — Ajax endpoint (only called by DataTables)
    // ─────────────────────────────────────────────────────


    public function datatable()
    {
        $this->authGuard();

        $model = new UserModel();

        // 1. Parameters sent automatically by DataTables
        $draw     = (int) $this->request->getPost('draw');
        $start    = (int) $this->request->getPost('start');   // row offset
        $length   = (int) $this->request->getPost('length');  // rows per page
        $search   = trim($this->request->getPost('search')['value'] ?? '');
        $orderCol = (int) ($this->request->getPost('order')[0]['column'] ?? 0);
        $orderDir = $this->request->getPost('order')[0]['dir'] ?? 'asc';

        // 2. Custom date range filter
        $dateFrom = $this->request->getPost('dateFrom') ?? '';
        $dateTo   = $this->request->getPost('dateTo')   ?? '';

        // 3. Map column index → DB column
        $columns     = ['id', 'username', 'email', 'gender', 'phone_number', 'user_type', 'created_at'];
        $orderColumn = $columns[$orderCol] ?? 'id';
        $orderDir    = in_array($orderDir, ['asc', 'desc']) ? $orderDir : 'asc';

        $loggedInUserId = (int) session()->get('user_id');
        $isAdmin        = session()->get('user_type') === 'admin';

        // 4. Total records — excludes logged-in user, never changes
        $totalRecords = $model
            ->where('id !=', $loggedInUserId)
            ->countAllResults();

        // 5. Build filtered query
        $builder = $model->where('id !=', $loggedInUserId);

        // 5a. Search filter
        if (!empty($search)) {
            $builder = $builder
                ->groupStart()
                ->like('username',       $search)
                ->orLike('gender',       $search)
                ->orLike('phone_number', $search)
                ->orLike('user_type',    $search)
                ->groupEnd();
        }

        // 5b. Date range filter
        if (!empty($dateFrom)) {
            $builder = $builder->where('DATE(created_at) >=', $dateFrom);
        }
        if (!empty($dateTo)) {
            $builder = $builder->where('DATE(created_at) <=', $dateTo);
        }

        // 6. Total after filtering
        $totalFiltered = $builder->countAllResults(false);

        // 7. Fetch only the rows needed for this page
        $users = $builder
            ->orderBy($orderColumn, $orderDir)
            ->limit($length, $start)
            ->findAll();

        // 8. Build response rows
        $data = [];
        $i    = $start + 1;   // row number continues across pages

        foreach ($users as $u) {
            // Decrypt email
            $email = $this->decryptEmail($u['email']);

            // Action buttons
            if ($isAdmin) {
                $actions =
                    '<button class="btn-act btn-edit-u"
                             data-bs-toggle="modal"
                             data-bs-target="#editModal"
                             data-id="'       . $u['id']                            . '"
                             data-username="' . esc($u['username'])                  . '"
                             data-phone="'    . esc($u['phone_number'] ?? '')        . '"
                             data-gender="'   . esc(strtolower($u['gender'] ?? ''))  . '">
                        <i class="fas fa-edit"></i> Edit
                     </button>
                     <button class="btn-act btn-delete-u btn-delete-trigger"
                             data-id="'       . $u['id']            . '"
                             data-username="' . esc($u['username']) . '">
                        <i class="fas fa-trash"></i> Delete
                     </button>';
            } else {
                $actions = '<span class="badge bg-secondary" style="font-size:11px;">View Only</span>';
            }

            // Role badge
            $roleBadge =
                '<span class="badge" style="background:'
                . (($u['user_type'] ?? '') === 'admin' ? 'var(--primary)' : 'var(--accent)')
                . ';font-size:11px;">'
                . esc(ucfirst($u['user_type'] ?? 'user'))
                . '</span>';

            $data[] = [
                'id'           => $i++,
                'username'     => esc($u['username']),
                'email'        => esc($email),
                'gender'       => esc(ucfirst($u['gender'] ?? 'N/A')),
                'phone_number' => esc($u['phone_number'] ?? 'N/A'),
                'user_type'    => $roleBadge,
                'created_at'   => date('M d, Y', strtotime($u['created_at'])),
                'actions'      => $actions,
            ];
        }

        // 9. Return JSON to DataTables
        return $this->response->setJSON([
            'draw'            => $draw,
            'recordsTotal'    => $totalRecords,
            'recordsFiltered' => $totalFiltered,
            'data'            => $data,
            'csrfHash'        => csrf_hash(),  // refresh token for next request
        ]);
    }

    public function update($id = null)
    {
        $this->authGuard();

        // 1. Admin check
        if (session()->get('user_type') !== 'admin') {
            return redirect()->to(base_url('dashboard'))
                ->with('error', 'Unauthorized action.');
        }

        // 2. ID check
        if (!$id) {
            return redirect()->to(base_url('dashboard'))
                ->with('error', 'User ID is missing.');
        }

        // 3. Prevent editing other admins
        $model      = new UserModel();
        $targetUser = $model->find((int)$id);

        if (!$targetUser) {
            return redirect()->to(base_url('dashboard'))
                ->with('error', 'User not found.');
        }

        if ($targetUser['user_type'] === 'admin') {
            return redirect()->to(base_url('dashboard'))
                ->with('error', 'You cannot edit another admin account.');
        }

        // 4. Validate inputs
        $username = trim($this->request->getPost('username'));
        $phone    = trim($this->request->getPost('phone_number'));
        $gender   = $this->request->getPost('gender');

        if (empty($username)) {
            return redirect()->to(base_url('dashboard'))
                ->with('error', 'Username cannot be empty.');
        }

        if (strlen($username) < 3) {
            return redirect()->to(base_url('dashboard'))
                ->with('error', 'Username must be at least 3 characters.');
        }

        // 5. Build update data
        $updateData = [
            'username'     => $username,
            'phone_number' => $phone  ?: null,
            'gender'       => $gender ?: null,
        ];

        // 6. Execute update
        if ($model->update((int)$id, $updateData)) {
            return redirect()->to(base_url('dashboard'))
                ->with('success', 'User updated successfully.');
        }

        // Get exact validation error messages from model
        $errors = $model->errors();

        if (!empty($errors)) {
            // Return first validation error message to user
            $firstError = array_values($errors)[0];
            return redirect()->to(base_url('dashboard'))
                ->with('error', $firstError);
        }

        return redirect()->to(base_url('dashboard'))
            ->with('error', 'Could not update user. Please try again.');
    }
public function updateProfile()
{
    $this->authGuard();

    $userId = (int) session()->get('user_id');
    $model  = new UserModel();

    $username = trim($this->request->getPost('username'));
    $phone    = trim($this->request->getPost('phone_number'));
    $gender   = $this->request->getPost('gender');

    // Validate username
    if (empty($username)) {
        return redirect()->to(base_url('dashboard'))
                         ->with('error', 'A username is required.');
    }
    if (strlen($username) < 2) {
        return redirect()->to(base_url('dashboard'))
                         ->with('error', 'Your username must be at least 2 characters long.');
    }
    if (strlen($username) > 21) {
        return redirect()->to(base_url('dashboard'))
                         ->with('error', 'Your username cannot be longer than 21 characters.');
    }
    if (!ctype_alpha($username)) {
        return redirect()->to(base_url('dashboard'))
                         ->with('error', 'Your username can only contain letters.');
    }

    // Validate phone if provided
    if (!empty($phone)) {
        if (!preg_match('/^[0-9\s\+\-\(\)]+$/', $phone)) {
            return redirect()->to(base_url('dashboard'))
                             ->with('error', 'Phone number contains invalid characters.');
        }
        if (strlen(preg_replace('/\D/', '', $phone)) < 10) {
            return redirect()->to(base_url('dashboard'))
                             ->with('error', 'Phone number must be at least 10 digits.');
        }
    }

    $updateData = [
        'username'     => $username,
        'phone_number' => $phone  ?: null,
        'gender'       => $gender ?: null,
    ];

    // Handle profile image upload
    $image = $this->request->getFile('profile_image');
    if ($image && $image->isValid() && !$image->hasMoved()) {

        // Validate image type and size
        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
        if (!in_array($image->getMimeType(), $allowedTypes)) {
            return redirect()->to(base_url('dashboard'))
                             ->with('error', 'Only JPG, PNG or WEBP images are allowed.');
        }
        if ($image->getSize() > 2 * 1024 * 1024) {
            return redirect()->to(base_url('dashboard'))
                             ->with('error', 'Image must be under 2MB.');
        }

        // Create upload folder if it doesn't exist
        $uploadPath = FCPATH . 'uploads/profiles/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        // Delete old image if exists
        $currentUser = $model->find($userId);
        if (!empty($currentUser['profile_image'])) {
            $oldFile = $uploadPath . $currentUser['profile_image'];
            if (file_exists($oldFile)) unlink($oldFile);
        }

        // Save new image with unique name
        $newName = 'profile_' . $userId . '_' . time() . '.' . $image->getExtension();
        $image->move($uploadPath, $newName);
        $updateData['profile_image'] = $newName;
    }

    $model->skipValidation(true);

    if ($model->update($userId, $updateData)) {
        // Refresh session with new values
        session()->set([
            'username'     => $username,
            'phone_number' => $phone  ?: null,
            'gender'       => $gender ?: null,
        ]);

        return redirect()->to(base_url('dashboard'))
                         ->with('success', 'Profile updated successfully.');
    }

    return redirect()->to(base_url('dashboard'))
                     ->with('error', 'Could not update profile. Please try again.');
}
    public function delete($id = null)
    {
        $this->authGuard();

        if (session()->get('user_type') !== 'admin') {
            return redirect()->to(base_url('dashboard'))
                ->with('error', 'You do not have permission to delete users.');
        }

        if (!$id) {
            return redirect()->to(base_url('dashboard'))
                ->with('error', 'User ID is missing.');
        }

        // Prevent deleting own account
        if ((int)$id === (int)session()->get('user_id')) {
            return redirect()->to(base_url('dashboard'))
                ->with('error', 'You cannot delete your own account.');
        }

        // Prevent deleting other admins
        $model      = new UserModel();
        $targetUser = $model->find((int)$id);

        if (!$targetUser) {
            return redirect()->to(base_url('dashboard'))
                ->with('error', 'User not found.');
        }

        if ($targetUser['user_type'] === 'admin') {
            return redirect()->to(base_url('dashboard'))
                ->with('error', 'You cannot delete another admin account.');
        }

        if ($model->delete((int)$id)) {
            return redirect()->to(base_url('dashboard'))
                ->with('success', 'User deleted successfully.');
        }

        return redirect()->to(base_url('dashboard'))
            ->with('error', 'Could not delete user. Please try again.');
    }

    public function exportCSV()
    {
        $this->authGuard();

        if (session()->get('user_type') !== 'admin') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Unauthorized action.');
        }

        $model = new UserModel();
        $users = $model->where('id !=', session()->get('user_id'))->findAll();

        $filename = 'users_' . date('Y-m-d') . '.csv';

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['#', 'Username', 'Email', 'Gender', 'Phone', 'Role']);

        $i = 1;
        foreach ($users as $user) {
            fputcsv($output, [
                $i++,
                $user['username'],
                $this->decryptEmail($user['email']),
                $user['gender']       ?? 'N/A',
                $user['phone_number'] ?? 'N/A',
                $user['user_type']    ?? 'user',
            ]);
        }

        fclose($output);
        exit;
    }
}
