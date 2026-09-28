<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\RunInSeparateProcess;
use Tests\TestCase;

class ProductionReadinessTest extends TestCase
{
    #[RunInSeparateProcess]
    public function test_public_homepage_and_admin_login_page_are_reachable(): void
    {
        $this->get('/')->assertOk();
        $this->get('/admin')->assertOk();
    }

    #[RunInSeparateProcess]
    public function test_invalid_webhook_payload_returns_controlled_client_error(): void
    {
        $this->get('/ipn/blockchain')
            ->assertStatus(400)
            ->assertJsonPath('status', 'error');
    }

    #[RunInSeparateProcess]
    public function test_public_root_contains_entrypoint_and_storage_link(): void
    {
        $this->assertFileExists(public_path('index.php'));
        $this->assertTrue(is_link(public_path('storage')));
        $this->assertFileExists(public_path('assets'));
    }
}