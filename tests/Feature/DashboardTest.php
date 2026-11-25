<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_requires_authentication(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
        $this->get('/dashboard/portfolio')->assertRedirect('/login');
    }

    public function test_login_page_renders(): void
    {
        $this->get('/login')->assertOk()->assertSee('name="username"', false)->assertSee('name="password"', false);
    }

    public static function screens(): array
    {
        return [
            ['dashboard.index'], ['dashboard.home'], ['dashboard.home.form'], ['dashboard.services'], ['dashboard.services.form'],
            ['dashboard.about'], ['dashboard.about.form'], ['dashboard.portfolio'], ['dashboard.portfolio.form'],
            ['dashboard.hero'], ['dashboard.hero.form'], ['dashboard.countries'], ['dashboard.countries.form'],
            ['dashboard.contacts'], ['dashboard.contact-info'], ['dashboard.stats'], ['dashboard.stats.form'],
        ];
    }

    #[DataProvider('screens')]
    public function test_every_dashboard_screen_renders(string $route): void
    {
        $this->actingAs(User::factory()->create())->get(route($route))->assertOk();
    }

    public function test_project_edit_form_keeps_all_field_names(): void
    {
        $project = Project::create(['name' => 'P', 'name_ar' => 'ب', 'is_active' => true, 'sort_order' => 0, 'images' => ['projects/x.jpg']]);

        $response = $this->actingAs(User::factory()->create())->get(route('dashboard.portfolio.form', $project->id))->assertOk();

        foreach (['name', 'name_ar', 'description', 'description_ar', 'thumbnail', 'images[]', 'area', 'rooms_count', 'project_type', 'project_type_ar',
            'engineering_function', 'engineering_function_ar', 'location', 'location_ar', 'category', 'category_ar', 'country_id', 'is_active', 'sort_order'] as $field) {
            $response->assertSee('name="'.$field.'"', false);
        }
        $response->assertSee('name="_method" value="PUT"', false);
    }

    public function test_contact_messages_are_not_rendered_as_html(): void
    {
        Contact::create(['name' => 'X', 'email' => 'x@example.com', 'message' => '<img src=x onerror=alert(1)>']);

        $html = $this->actingAs(User::factory()->create())->get(route('dashboard.contacts'))->assertOk()->getContent();

        $this->assertStringNotContainsString('<img src=x onerror', $html);
        $this->assertStringNotContainsString('innerHTML', $html);
    }

    public function test_sidebar_links_every_section(): void
    {
        $response = $this->actingAs(User::factory()->create())->get(route('dashboard.index'));

        foreach (['dashboard.portfolio', 'dashboard.services', 'dashboard.home', 'dashboard.hero', 'dashboard.about', 'dashboard.stats', 'dashboard.countries', 'dashboard.contacts', 'dashboard.contact-info'] as $r) {
            $response->assertSee('href="'.route($r).'"', false);
        }
    }
}
