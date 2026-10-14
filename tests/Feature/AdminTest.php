<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
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
            ->assertSee('Console Admin')
            ->assertSee('Portfolio Hub')
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
}
