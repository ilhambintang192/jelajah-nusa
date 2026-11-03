<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        // 1. Halaman Beranda Utama (Landing Page)
        $homeResponse = $this->get('/');
        $homeResponse->assertStatus(200);
        $homeResponse->assertSee('Jelajah Nusa');
        $homeResponse->assertSee('Open Trip &amp; Custom Trip', false);

        // 2. Halaman Katalog Paket Wisata
        $catalogResponse = $this->get(route('paket.index'));
        $catalogResponse->assertStatus(200);
        $catalogResponse->assertSee('Katalog Paket Wisata');
    }
}
