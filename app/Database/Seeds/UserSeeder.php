<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class UserSeeder extends Seeder
{
    public function run()
    {
        $faker     = Factory::create();
        $encrypter = \Config\Services::encrypter();

        // --- 1. Admin User ---
        $adminData = [
            'username'     => 'Admin',
            'email'        => base64_encode($encrypter->encrypt('admin@example.com')),
            'password'     => password_hash('admin123', PASSWORD_BCRYPT),
            'phone_number' => '1234567890',
            'gender'       => 'male',
            'user_type'    => 'admin',
            'created_at'   => date('Y-m-d H:i:s'),
        ];
        $this->db->table('users')->insert($adminData);

        // --- 2. Regular Users ---
        for ($i = 0; $i < 20; $i++) {
            $plainEmail = $faker->unique()->safeEmail;

            // Alpha only username (letters only, 2–21 chars)
            $username = substr(preg_replace('/[^a-zA-Z]/', '', $faker->firstName . $faker->lastName), 0, 21);
            if (strlen($username) < 2) {
                $username = 'User' . $faker->randomLetter . $faker->randomLetter;
            }

            // Exactly 10 numeric digits
            $phone = (string) $faker->numerify('##########'); // 10 # = 10 digits

            $userData = [
                'username'     => $username,
                'email'        => base64_encode($encrypter->encrypt($plainEmail)),
                'password'     => password_hash('user123', PASSWORD_BCRYPT),
                'phone_number' => $phone,
                'gender'       => $faker->randomElement(['male', 'female', 'other']),
                'user_type'    => 'user',
                'created_at'   => date('Y-m-d H:i:s'),
            ];

            $this->db->table('users')->insert($userData);
        }
    }
}