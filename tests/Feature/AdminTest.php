<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use App\Models\Experience;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    private function admin(): User
    {
        return User::factory()->create(['is_admin' => true]);
    }

    public function test_login_page_renders_with_masked_ip(): void
    {
        $this->get('/admin/login', ['REMOTE_ADDR' => '103.84.22.9'])
            ->assertOk()
            ->assertSee('Masuk ke Konsol')
            ->assertDontSee('103.84.22.9');
    }

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
        $this->get('/admin/projects')->assertRedirect('/admin/login');
    }

    public function test_non_admin_users_are_forbidden(): void
    {
        $this->actingAs(User::factory()->create())->get('/admin')->assertForbidden();
    }

    public function test_admin_can_view_dashboard(): void
    {
        $this->actingAs($this->admin())
            ->get('/admin')
            ->assertOk()
            ->assertSee('Dashboard');
    }

    public function test_admin_can_log_in(): void
    {
        $user = User::factory()->create(['is_admin' => true, 'password' => 'rahasia-panjang-123']);

        $this->post('/admin/login', ['email' => $user->email, 'password' => 'rahasia-panjang-123'])
            ->assertRedirect('/admin');
        $this->assertAuthenticatedAs($user);
    }

    public function test_non_admin_cannot_log_in(): void
    {
        $user = User::factory()->create(['password' => 'rahasia-panjang-123']);

        $this->post('/admin/login', ['email' => $user->email, 'password' => 'rahasia-panjang-123'])
            ->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_admin_can_view_create_project_form(): void
    {
        $this->actingAs($this->admin())
            ->get('/admin/projects/create')
            ->assertOk()
            ->assertSee('Kategori Proyek')
            ->assertSee('Live Card Inspector');
    }

    public function test_admin_can_create_project_with_image(): void
    {
        Storage::fake('public');

        $this->actingAs($this->admin())->post('/admin/projects', [
            'title' => 'Proyek Baru',
            'description' => 'Deskripsi proyek.',
            'category' => 'web',
            'technologies' => 'Laravel, MySQL',
            'published' => '1',
            'image' => UploadedFile::fake()->image('shot.jpg'),
        ])->assertRedirect('/admin/projects');

        $project = Project::firstWhere('slug', 'proyek-baru');
        $this->assertNotNull($project);
        $this->assertSame(['Laravel', 'MySQL'], $project->technologies);
        $this->assertStringStartsWith('storage/uploads/', $project->image);
    }

    public function test_admin_can_toggle_project_publication_status(): void
    {
        $project = Project::create([
            'title' => 'Test Toggle',
            'slug' => 'test-toggle',
            'description' => 'Desc',
            'category' => 'web',
            'published' => true,
        ]);

        $this->actingAs($this->admin())
            ->patch("/admin/projects/{$project->id}/toggle-publish")
            ->assertRedirect();

        $this->assertFalse($project->fresh()->published);
    }

    public function test_project_rejects_non_http_urls(): void
    {
        $this->actingAs($this->admin())->post('/admin/projects', [
            'title' => 'X', 'description' => 'Y', 'category' => 'web',
            'demo_url' => 'javascript:alert(1)',
        ])->assertSessionHasErrors('demo_url');
    }

    public function test_admin_can_read_and_delete_message(): void
    {
        $message = ContactMessage::create([
            'name' => 'Budi', 'email' => 'budi@example.com', 'subject' => 'Halo', 'message' => 'Isi pesan panjang.',
        ]);
        $admin = $this->admin();

        $this->actingAs($admin)->get("/admin/messages/{$message->id}")->assertOk()->assertSee('Isi pesan panjang.');
        $this->assertNotNull($message->fresh()->read_at);

        $this->actingAs($admin)->delete("/admin/messages/{$message->id}")->assertRedirect('/admin/messages');
        $this->assertDatabaseCount('contact_messages', 0);
    }

    public function test_admin_can_view_settings(): void
    {
        $this->actingAs($this->admin())
            ->get('/admin/settings')
            ->assertOk()
            ->assertSee('Pengaturan Konten');
    }

    public function test_admin_can_update_settings(): void
    {
        $this->actingAs($this->admin())
            ->post('/admin/settings', [
                'settings' => [
                    'identity.name' => 'Muhammad Rayhan Updated',
                ],
            ])
            ->assertRedirect('/admin/settings?tab=identity')
            ->assertSessionHas('status');

        $this->assertSame('Muhammad Rayhan Updated', setting('identity.name'));
    }

    public function test_admin_can_manage_experiences(): void
    {
        $admin = $this->admin();

        // View index
        $this->actingAs($admin)
            ->get('/admin/experiences')
            ->assertOk()
            ->assertSee('Kelola Pengalaman');

        // Create
        $this->actingAs($admin)
            ->post('/admin/experiences', [
                'role' => 'Web Developer Intern',
                'company' => 'Agency Digital',
                'period' => '2024 — Sekarang',
                'description' => 'Mengembangkan antarmuka dan API.',
                'skills' => 'Laravel, Tailwind CSS, Vue',
                'sort_order' => 1,
            ])
            ->assertRedirect('/admin/experiences');

        $this->assertDatabaseHas('experiences', [
            'role' => 'Web Developer Intern',
            'company' => 'Agency Digital',
        ]);

        $experience = Experience::firstWhere('role', 'Web Developer Intern');
        $this->assertSame(['Laravel', 'Tailwind CSS', 'Vue'], $experience->skills);

        // Edit form
        $this->actingAs($admin)
            ->get("/admin/experiences/{$experience->id}/edit")
            ->assertOk()
            ->assertSee('Edit Riwayat Pengalaman');

        // Update
        $this->actingAs($admin)
            ->put("/admin/experiences/{$experience->id}", [
                'role' => 'Lead Frontend Developer',
                'company' => 'Agency Digital',
                'period' => '2024 — 2025',
                'skills' => 'React, TypeScript',
            ])
            ->assertRedirect('/admin/experiences');

        $this->assertDatabaseHas('experiences', [
            'id' => $experience->id,
            'role' => 'Lead Frontend Developer',
        ]);

        // Destroy
        $this->actingAs($admin)
            ->delete("/admin/experiences/{$experience->id}")
            ->assertRedirect('/admin/experiences');

        $this->assertDatabaseMissing('experiences', [
            'id' => $experience->id,
        ]);
    }
}
