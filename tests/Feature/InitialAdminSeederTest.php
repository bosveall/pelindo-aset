<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\InitialAdminSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use RuntimeException;
use Tests\TestCase;

class InitialAdminSeederTest extends TestCase
{
    use RefreshDatabase;

    private array $originalEnvironment = [];

    protected function tearDown(): void
    {
        foreach ($this->originalEnvironment as $key => $value) {
            if ($value === false) {
                putenv($key);
                unset($_ENV[$key], $_SERVER[$key]);
                continue;
            }

            putenv("{$key}={$value}");
            $_ENV[$key] = $value;
            $_SERVER[$key] = $value;
        }

        parent::tearDown();
    }

    public function test_it_creates_the_initial_admin_from_environment_variables(): void
    {
        $this->setEnvironment('INITIAL_ADMIN_NAME', 'Demo Admin');
        $this->setEnvironment('INITIAL_ADMIN_EMAIL', 'demo-admin@example.test');
        $this->setEnvironment('INITIAL_ADMIN_PASSWORD', 'a-long-demo-password-123');

        $this->seed(InitialAdminSeeder::class);

        $admin = User::where('email', 'demo-admin@example.test')->firstOrFail();

        $this->assertSame('admin', $admin->role);
        $this->assertTrue(Hash::check('a-long-demo-password-123', $admin->password));
    }

    public function test_it_requires_secure_environment_credentials_for_an_empty_database(): void
    {
        $this->setEnvironment('INITIAL_ADMIN_NAME', '');
        $this->setEnvironment('INITIAL_ADMIN_EMAIL', '');
        $this->setEnvironment('INITIAL_ADMIN_PASSWORD', '');

        $this->expectException(RuntimeException::class);

        $this->seed(InitialAdminSeeder::class);
    }

    private function setEnvironment(string $key, string $value): void
    {
        $this->originalEnvironment[$key] = getenv($key);
        putenv("{$key}={$value}");
        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
    }
}
