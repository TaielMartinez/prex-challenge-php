<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OauthClient;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        User::firstOrCreate([
            'id' => 1,
        ], [
            'email' => 'test@example.com',
            'name' => 'Test User',
            'password' => bcrypt('password'),
        ]);

        OauthClient::firstOrCreate([
            'id' => '9cb35545-3b0c-4afd-bf58-5a5d0104b107',
        ], [
            'user_id' => 1,
            'name' => 'Test Client',
            'secret' => 'zS1yJ197AySVSAnfY9LLrDw0p7qw9vf4dJlgTjvS',
            'provider' => null,
            'redirect' => 'http://localhost',
            'personal_access_client' => 0,
            'password_client' => 1,
            'revoked' => 0,
            'created_at' => '2024-08-06 07:33:56',
            'updated_at' => '2024-08-06 07:33:56',
        ]);
    }
}
