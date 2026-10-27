<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use RuntimeException;

class InitialAdminSeeder extends Seeder
{
    public function run(): void
    {
        if (User::query()->exists()) {
            return;
        }

        $name = getenv('INITIAL_ADMIN_NAME') ?: '';
        $email = getenv('INITIAL_ADMIN_EMAIL') ?: '';
        $password = getenv('INITIAL_ADMIN_PASSWORD') ?: '';

        if ($name === '' || filter_var($email, FILTER_VALIDATE_EMAIL) === false || strlen($password) < 16) {
            throw new RuntimeException(
                'Set INITIAL_ADMIN_NAME, a valid INITIAL_ADMIN_EMAIL, and an INITIAL_ADMIN_PASSWORD of at least 16 characters.'
            );
        }

        User::create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'role' => 'admin',
        ]);
    }
}
