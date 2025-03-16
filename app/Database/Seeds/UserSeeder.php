<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name' => 'admin',
                'email' => 'admin@example.com',
                'password' => password_hash('password123', PASSWORD_DEFAULT),
            ],
            [
                'username' => 'user1',
                'email' => 'user1@example.com',
                'password' => password_hash('user1234', PASSWORD_DEFAULT),
            ],
        ];

        // Insert data into the 'user' table
        $this->db->table('users')->insertBatch($data);
    }

}
