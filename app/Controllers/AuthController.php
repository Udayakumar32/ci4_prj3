<?php

namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\UserModel;

class AuthController extends BaseController
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

        // Model-level validation
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

        // Hash password & insert
        $data['password']    = password_hash($data['password'], PASSWORD_BCRYPT);
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
        // Already authenticated — go to dashboard
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
        $remember = (bool)        $this->request->getPost('remember');

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

        // ── STEP 3: Find user by email ────────────────────────────
        $user = $model->where('email', $email)->first();

        if (! $user) {
            return redirect()->to(base_url('login'))->withInput()
                ->with('errors', ['email' => 'No account found with this email address.']);
        }

        // ── STEP 4: Verify password ───────────────────────────────
        if (! password_verify($password, $user['password'])) {
            return redirect()->to(base_url('login'))->withInput()
                ->with('errors', ['password' => 'The password you entered is incorrect.']);
        }

        // ── STEP 5: Create session ────────────────────────────────
        $this->createUserSession($user);

        // ── STEP 6: Remember me cookie (30 days) ──────────────────
        if ($remember) {
            $this->setRememberMeCookie($user['id']);
        }

        return redirect()->to(base_url('dashboard'));
    }

    // =========================================================================
    //  LOGOUT
    // =========================================================================

    /** GET /logout */
    public function logout()
    {
        session()->destroy();

        // Clear remember-me cookie if present
        if (isset($_COOKIE['remember_token'])) {
            delete_cookie('remember_token');
        }

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
            'email'       => $user['email'],
            'user_type'   => $user['user_type'],
            'profile_pic' => $user['profile_pic'] ?? null,
            'logged_in'   => true,
        ]);
    }

    /**
     * Set a 30-day secure HttpOnly cookie for "Remember me".
     * Production note: store a hashed version of $token in a
     * `remember_tokens` DB table and verify it on every auto-login.
     */
    private function setRememberMeCookie(int $userId): void
    {
        $token = bin2hex(random_bytes(32));   // 64-char secure random token

        set_cookie([
            'name'     => 'remember_token',
            'value'    => $userId . '|' . $token,
            'expire'   => 30 * 24 * 60 * 60,  // 30 days in seconds
            'secure'   => true,                // HTTPS only
            'httponly' => true,                // JS cannot read it
            'samesite' => 'Lax',
        ]);
    }
}