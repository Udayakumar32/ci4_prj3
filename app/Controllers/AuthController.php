<?php

namespace App\Controllers;

use App\Models\UserModel;

class AuthController extends BaseController
{
    public function login()
    {
        if (session()->get('isLoggedIn')) {
        return redirect()->to('dashboard');
    }
        return view('login');
        
    }

    public function register()
    {
        if (session()->get('isLoggedIn')) {
        return redirect()->to('dashboard');
    }
        return view('register');
    }

//    public function store()
// {
//     helper(['form']);
//     $model = new \App\Models\UserModel();

//     // 1. Collect the RAW data from the form
//     $data = [
//         'username'     => $this->request->getPost('username'),
//         'email'        => $this->request->getPost('email'),
//         'password'     => $this->request->getPost('password'), // RAW password here
//         'phone_number' => $this->request->getPost('phone'),
//         'gender'       => $this->request->getPost('gender'),
//         'user_type'    => 'user'
//     ];

//     // 2. Validate the RAW data (this checks your 8-32 character rule and Regex)
//     if (!$model->validate($data)) {
//         return redirect()->back()->withInput()->with('errors', $model->errors());
//     }

//     // 3. ONLY NOW do we hash the password for the database
//     $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

//     // 4. Handle Profile Picture
//     $file = $this->request->getFile('profile_pic');
//     if ($file && $file->isValid() && !$file->hasMoved()) {
//         $newName = $file->getRandomName();
//         $file->move('uploads/profiles', $newName);
//         $data['profile_pic'] = $newName;
//     }

//     // 5. Final Insert (We use insert() instead of save() to be safe)
//     if ($model->insert($data)) {
//         return redirect()->to('/login')->with('success', 'Registration successful! You can now login.');
//     } else {
//         return redirect()->back()->withInput()->with('error', 'Database failed to save.');
//     }
// }

public function loginAuth()
    {
        $session = session();
        $model = new UserModel();
        
        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');

        $validation = \Config\Services::validation();
    $validation->setRules([
        'email'    => 'required|valid_email',
        'password' => 'required'
    ]);

    if (!$validation->withRequest($this->request)->run()) {
        return redirect()->back()->withInput()->with('errors', $validation->getErrors());
    }

        // 1. Fetch user by email
        $user = $model->where('email', $email)->first();

        if ($user) {
            // 2. Verify hashed password
            if (password_verify($password, $user['password'])) {
                
                // 3. Set Session Data
                $sessionData = [
                    'id'         => $user['id'],
                    'username'   => $user['username'],
                    'email'      => $user['email'],
                    'user_type'  => $user['user_type'],
                    'isLoggedIn' => true,
                ];
                $session->set($sessionData);

                return redirect()->to('/dashboard');
            } else {
                return redirect()->back()->with('error', 'The password you entered is incorrect.');
            }
        } else {
            return redirect()->back()->with('error', 'No account found with that email.');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }

public function store()
{
    helper(['form']);
    $model = new \App\Models\UserModel();

    // 1. Collect Data
    $data = [
        'username'     => $this->request->getPost('username'),
        'email'        => $this->request->getPost('email'),
        'password'     => $this->request->getPost('password'), // RAW for validation
        'phone_number' => $this->request->getPost('phone'),
        'gender'       => $this->request->getPost('gender'),
        'user_type'    => 'user',
    ];

    // 2. Validate RAW data
    if (!$model->validate($data)) {
        return redirect()->back()->withInput()->with('errors', $model->errors());
    }

    // 3. Hash AFTER validation
    $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

    // 4. Handle Image
    $file = $this->request->getFile('profile_pic');
    if ($file && $file->isValid()) {
        $newName = $file->getRandomName();
        $file->move('uploads/profiles', $newName);
        $data['profile_pic'] = $newName;
    }

    // 5. Try the Insert with Query Builder (to bypass model restrictions)
    $db = \Config\Database::connect();
    $builder = $db->table('users');
    
    if ($builder->insert($data)) {
        return redirect()->to('/login')->with('success', 'Registration successful!');
    } else {
        // If this fails, print the actual SQL error to the screen
        $error = $db->error();
        exit("SQL Error (" . $error['code'] . "): " . $error['message']);
    }
}
}
