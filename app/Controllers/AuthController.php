<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class AuthController extends Controller
{
    // =========================================================================
    //  REGISTER
    // =========================================================================

    /** GET /register */
    public function register()
    {
        if (session()->get('logged_in')) {
            return redirect()->to(base_url('dashboard'));
        }
        return view('register');
    }

    /** POST /register */
    public function store()
    {
        $model = new UserModel();

        $data = [
            'username'     => $this->request->getPost('username'),
            'email'        => $this->request->getPost('email'),
            'phone_number' => $this->request->getPost('phone'),
            'gender'       => $this->request->getPost('gender'),
            'password'     => $this->request->getPost('password'),
        ];

        // Model-level validation (validate with plain email before encrypting)
        if (! $model->validate($data)) {
            return redirect()->back()->withInput()->with('errors', $model->errors());
        }

        // Confirm password
        if ($data['password'] !== $this->request->getPost('confirm_password')) {
            return redirect()->back()->withInput()
                ->with('errors', ['confirm_password' => 'Passwords do not match. Please try again.']);
        }

        // Optional profile picture
        $profilePicName = null;
        $file = $this->request->getFile('profile_pic');

        if ($file && $file->isValid() && ! $file->hasMoved()) {
            $rules = [
                'profile_pic' => [
                    'rules'  => 'is_image[profile_pic]|max_size[profile_pic,2048]|ext_in[profile_pic,png,jpg,jpeg]',
                    'errors' => [
                        'is_image'  => 'The uploaded file must be a valid image.',
                        'max_size'  => 'Profile picture must be under 2 MB.',
                        'ext_in'    => 'Only PNG, JPG and JPEG images are allowed.',
                    ],
                ],
            ];
            if (! $this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }
            $newName = $file->getRandomName();
            $file->move(WRITEPATH . 'uploads/profiles', $newName);
            $profilePicName = $newName;
        }

        // Hash password + encrypt email before storing in DB
        $data['password']    = password_hash($data['password'], PASSWORD_BCRYPT);
        $data['email']       = $this->encryptEmail($data['email']);
        $data['user_type']   = 'user';
        $data['profile_pic'] = $profilePicName;

        if (! $model->skipValidation(true)->insert($data)) {
            return redirect()->back()->withInput()->with('error', 'Something went wrong. Please try again.');
        }

        return redirect()->to(base_url('login'))->with('success', 'Account created successfully! Please sign in.');
    }

    // =========================================================================
    //  LOGIN
    // =========================================================================

    /** GET /login */
    public function login()
    {
        if (session()->get('logged_in')) {
            return redirect()->to(base_url('dashboard'));
        }
        return view('login');
    }

    /** POST /login */
    public function authenticate()
    {
        $model    = new UserModel();
        $email    = trim((string) $this->request->getPost('email'));
        $password = (string)      $this->request->getPost('password');

        // ── STEP 1: Empty field check — never reach DB if blank ───
        $fieldErrors = [];
        if ($email === '')    { $fieldErrors['email']    = 'Please enter your email address.'; }
        if ($password === '') { $fieldErrors['password'] = 'Please enter your password.'; }

        if (! empty($fieldErrors)) {
            return redirect()->to(base_url('login'))->withInput()->with('errors', $fieldErrors);
        }

        // ── STEP 2: Email format ──────────────────────────────────
        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return redirect()->to(base_url('login'))->withInput()
                ->with('errors', ['email' => 'Please enter a valid email address (e.g. name@example.com).']);
        }

        // ── STEP 3: Find user — fetch all & match decrypted email ─
        // We cannot use WHERE email = ? directly because the DB stores
        // encrypted values. So we fetch all users and decrypt each one
        // until we find a match.
        $allUsers = $model->findAll();
        $user     = null;

        foreach ($allUsers as $row) {
            $decrypted = $this->decryptEmail($row['email']);
            if ($decrypted === $email) {
                $user = $row;
                break;
            }
        }

        if (! $user) {
            return redirect()->to(base_url('login'))->withInput()
                ->with('errors', ['email' => 'No account found with this email address.']);
        }

        // ── STEP 4: Verify password ───────────────────────────────
        if (! password_verify($password, $user['password'])) {
            return redirect()->to(base_url('login'))->withInput()
                ->with('errors', ['password' => 'The password you entered is incorrect.']);
        }

        // ── STEP 5: Create session (store decrypted email) ────────
        $user['email'] = $email; // use the plain email in session
        $this->createUserSession($user);

        
        return redirect()->to(base_url('dashboard'));
    }

    // =========================================================================
    //  LOGOUT
    // =========================================================================

    /** GET /logout */
    public function logout()
    {
        session()->destroy();

        return redirect()->to(base_url('login'))->with('success', 'You have been logged out successfully.');
    }

    // =========================================================================
    //  PRIVATE HELPERS
    // =========================================================================

    /**
     * Write all required keys into the CI4 session.
     * Called after every successful credential check.
     */
    private function createUserSession(array $user): void
    {
        session()->set([
            'user_id'     => $user['id'],
            'username'    => $user['username'],
            'email'       => $user['email'],   // plain decrypted email
            'user_type'   => $user['user_type'],
            'profile_pic' => $user['profile_pic'] ?? null,
            'logged_in'   => true,
        ]);
    }

    /**
     * Encrypt an email using CI4's Encryption service.
     * Returns a base64-encoded encrypted string safe for DB storage.
     */
    private function encryptEmail(string $email): string
    {
        $encrypter = \Config\Services::encrypter();
        return base64_encode($encrypter->encrypt($email));
    }

    /**
     * Decrypt a previously encrypted email.
     * Returns the original plain-text email, or empty string on failure.
     */
    private function decryptEmail(string $encryptedEmail): string
    {
        try {
            $encrypter = \Config\Services::encrypter();
            return $encrypter->decrypt(base64_decode($encryptedEmail));
        } catch (\Exception $e) {
            return ''; // decryption failed (e.g. old plain-text email in DB)
        }
    }
}