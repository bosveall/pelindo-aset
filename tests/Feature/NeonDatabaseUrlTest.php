<?php

namespace Tests\Feature;

use Illuminate\Support\ConfigurationUrlParser;
use Tests\TestCase;

class NeonDatabaseUrlTest extends TestCase
{
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

    public function test_it_adds_neon_endpoint_and_verified_tls_options_to_legacy_connection_urls(): void
    {
        $this->setEnvironment('DB_URL', 'postgresql://demo:secret%3Avalue@ep-example-123.us-east-1.aws.neon.tech/demo');
        $this->setEnvironment('DATABASE_URL', '');
        $this->setEnvironment('DB_SSLMODE', '');

        $databaseConfig = require base_path('config/database.php');
        $connection = (new ConfigurationUrlParser)->parseConfiguration([
            'url' => $databaseConfig['connections']['pgsql']['url'],
        ]);

        $this->assertSame('endpoint=ep-example-123$secret:value', $connection['password']);
        $this->assertSame('verify-full', $connection['sslmode']);
    }

    public function test_it_preserves_non_neon_connection_urls(): void
    {
        $url = 'postgresql://demo:secret@db.example.com/demo?sslmode=require';
        $this->setEnvironment('DB_URL', $url);
        $this->setEnvironment('DATABASE_URL', '');

        $databaseConfig = require base_path('config/database.php');

        $this->assertSame($url, $databaseConfig['connections']['pgsql']['url']);
    }

    private function setEnvironment(string $key, string $value): void
    {
        if (! array_key_exists($key, $this->originalEnvironment)) {
            $this->originalEnvironment[$key] = getenv($key);
        }

        putenv("{$key}={$value}");
        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
    }
}
