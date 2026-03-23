<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'username',
        'email',
        'password',
        'phone_number',
        'gender',
        'user_type',
        'profile_pic'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'username' => 'required|min_length[2]|max_length[21]|alpha',
        'email' => 'required|max_length[50]|valid_email|is_unique[users.email]',
        'password' => 'required|min_length[8]|max_length[32]|regex_match[/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&]).{8,}$/]',
        'gender' => 'permit_empty',
        'phone_number' => 'required|numeric|min_length[10]|max_length[12]',
        'profile_pic' => 'permit_empty'
    ];
    protected $validationMessages   = [    
  'username' => [
    'required'   => 'A username is required to create your account.',
    'min_length' => 'Your username must be at least 2 characters long.',
    'max_length' => 'Your username cannot be longer than 21 characters.',
    'alpha'      => 'Your username can only contain letters. Numbers, spaces, and symbols are not allowed.'
],
'email' => [
    'required'    => 'An email address is required to create your account.',
    'max_length'  => 'Your email address cannot exceed 50 characters.',
    'valid_email' => 'Please enter a valid email address (e.g., name@example.com).',
    'is_unique'   => 'This email is already registered. Please try logging in instead.'
], 
'password' => [
    'required'    => 'A password is required to secure your account.',
    'min_length'  => 'Your password must be at least 8 characters long.',
    'max_length'  => 'Your password cannot exceed 32 characters.',
    'regex_match' => 'Your password must include at least one uppercase letter, one lowercase letter, one number, and one special character (like @, $, !, %, *, ?, or &).'
],
'phone_number' => [
    'required'   => 'A phone number is required so we can contact you.',
    'numeric'    => 'Please enter only numbers. Do not include spaces, dashes, or brackets.',
    'min_length' => 'The phone number is too short. It must be at least 10 digits.',
    'max_length' => 'The phone number is too long. It cannot exceed 12 digits.'
],
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
}
