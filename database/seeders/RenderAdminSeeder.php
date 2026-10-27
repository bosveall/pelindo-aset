<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use RuntimeException;

class RenderAdminSeeder extends Seeder
{
    public function run(): void
    {
        if (User::query()->exists()) {
            return;
        }

        $name = getenv('RENDER_ADMIN_NAME') ?: '';
        $email = getenv('RENDER_ADMIN_EMAIL') ?: '';
        $password = getenv('RENDER_ADMIN_PASSWORD') ?: '';

        if ($name === '' || filter_var($email, FILTER_VALIDATE_EMAIL) === false || strlen($password) < 16) {
            throw new RuntimeException(
                'Set RENDER_ADMIN_NAME, a valid RENDER_ADMIN_EMAIL, and a RENDER_ADMIN_PASSWORD of at least 16 characters.'
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
