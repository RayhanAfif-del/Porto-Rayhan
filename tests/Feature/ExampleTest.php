<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Tes tidak membutuhkan hasil build Vite (public/build).
        $this->withoutVite();
    }

    public function test_home_page_renders(): void
    {
        $this->seed();

        $this->get('/')
            ->assertOk()
            ->assertSee('Building Digital Experiences That Matter.')
            ->assertSee('SIJAKA')
            ->assertSee('Selected Works');
    }

    public function test_unpublished_projects_are_hidden(): void
    {
        Project::create([
            'title' => 'Draft Rahasia', 'slug' => 'draft', 'description' => 'x',
            'category' => 'web', 'published' => false,
        ]);

        $this->get('/')->assertOk()->assertDontSee('Draft Rahasia');
    }

    public function test_contact_form_stores_message(): void
    {
        $this->post('/contact', [
            'name' => 'Budi', 'email' => 'budi@example.com',
            'subject' => 'Kolaborasi', 'message' => 'Halo, saya ingin berkolaborasi.',
        ])->assertRedirect()->assertSessionHas('contact_success');

        $this->assertDatabaseCount('contact_messages', 1);
    }

    public function test_contact_form_validates_and_rejects_honeypot(): void
    {
        $this->post('/contact', ['name' => '', 'email' => 'bukan-email'])
            ->assertSessionHasErrors(['name', 'email', 'subject', 'message']);

        $this->post('/contact', [
            'name' => 'Bot', 'email' => 'bot@example.com', 'subject' => 'Spam',
            'message' => 'Pesan spam panjang sekali.', 'website' => 'http://spam.test',
        ])->assertSessionHasErrors('website');

        $this->assertSame(0, ContactMessage::count());
    }
}
