<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class UserSeeder extends Seeder
{
    public function run()
    {
        $faker = Factory::create();
        $encrypter = \Config\Services::encrypter();

        // --- 1. Create an Admin User (So you can test admin features) ---
        $adminData = [
            'username'     => 'admin',
            'email'        => base64_encode($encrypter->encrypt('admin@example.com')),
            'password'     => password_hash('admin123', PASSWORD_BCRYPT),
            'phone_number' => '1234567890',
            'gender'       => 'male',
            'user_type'    => 'admin',
            'created_at'   => date('Y-m-d H:i:s'),
        ];
        $this->db->table('users')->insert($adminData);

        // --- 2. Create Regular Users using Faker ---
        for ($i = 0; $i < 20; $i++) {
            $plainEmail = $faker->unique()->safeEmail;
            
            $userData = [
                'username'     => $faker->userName,
                // Email must be encrypted just like in your AuthController
                'email'        => base64_encode($encrypter->encrypt($plainEmail)),
                'password'     => password_hash('user123', PASSWORD_BCRYPT),
                'phone_number' => $faker->phoneNumber,
                'gender'       => $faker->randomElement(['male', 'female', 'other']),
                'user_type'    => 'user',
                'created_at'   => date('Y-m-d H:i:s'),
                'last_seen'    => $faker->dateTimeThisMonth()->format('Y-m-d H:i:s'),
            ];

            $this->db->table('users')->insert($userData);
        }
    }
}