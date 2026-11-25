<?php

namespace Tests\Feature;

use App\Models\AboutSection;
use App\Models\Contact;
use App\Models\ContactInfo;
use App\Models\Project;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PublicSiteTest extends TestCase
{
    use RefreshDatabase;

    private function seedContent(): Project
    {
        ContactInfo::create(['contact_title' => 'Talk to us', 'contact_title_ar' => 'تواصل', 'phone_numbers' => ['+20 01000000000'], 'email' => 'hello@example.com']);
        Service::create(['name' => 'Villa finishing', 'name_ar' => 'تشطيب فيلات', 'description' => 'Full finishing', 'is_active' => true, 'sort_order' => 1, 'slug' => 'villa-finishing']);
        AboutSection::create(['section_key' => 'about', 'title' => 'About us', 'title_ar' => 'من نحن', 'content' => "Line one\nLine two", 'content_ar' => "سطر أول\nسطر ثانٍ", 'is_active' => true, 'sort_order' => 1]);

        Project::create(['name' => 'Second', 'name_ar' => 'الثاني', 'category' => 'Commercial', 'category_ar' => 'تجاري', 'is_active' => true, 'sort_order' => 2, 'images' => ['projects/b.jpg']]);
        Project::create(['name' => 'Hidden', 'name_ar' => 'مخفي', 'category' => 'Residential', 'is_active' => false, 'sort_order' => 3]);

        return Project::create([
            'name' => 'Villa One', 'name_ar' => 'فيلا واحد', 'description' => 'Para one', 'area' => '350 m²', 'rooms_count' => 5,
            'category' => 'Residential', 'category_ar' => 'سكني', 'location' => 'New Cairo', 'location_ar' => 'القاهرة الجديدة',
            'thumbnail' => 'projects/a.jpg', 'images' => ['projects/a1.jpg', 'projects/a2.jpg'], 'is_active' => true, 'sort_order' => 1,
        ]);
    }

    public static function pages(): array
    {
        return [['/'], ['/projects'], ['/services'], ['/about'], ['/contact'], ['/project']];
    }

    #[DataProvider('pages')]
    public function test_public_pages_render_in_english_and_arabic(string $uri): void
    {
        $this->seedContent();

        $this->get($uri)->assertOk()->assertSee('<html lang="en" dir="ltr">', false);

        $this->withSession(['locale' => 'ar'])->get($uri)
            ->assertOk()
            ->assertSee('<html lang="ar" dir="rtl">', false);
    }

    public function test_pages_render_with_an_empty_database(): void
    {
        foreach (['/', '/projects', '/services', '/about', '/contact'] as $uri) {
            $this->get($uri)->assertOk();
        }
    }

    public function test_home_shows_database_content_and_no_placeholder_stats(): void
    {
        $this->seedContent();

        $this->get('/')
            ->assertOk()
            ->assertSee('Villa One')
            ->assertSee('Villa finishing')
            ->assertDontSee('Hidden')
            ->assertDontSee('500+')
            ->assertDontSee('Awards Won')
            ->assertDontSee('Studio Elite');
    }

    public function test_arabic_uses_arabic_fields_with_english_fallback(): void
    {
        $this->seedContent();
        Project::create(['name' => 'English only', 'is_active' => true, 'sort_order' => 9, 'name_ar' => '']);

        $this->withSession(['locale' => 'ar'])->get('/projects')
            ->assertSee('فيلا واحد')
            ->assertSee('English only');
    }

    public function test_project_page_shows_facts_gallery_and_related(): void
    {
        $project = $this->seedContent();

        $this->get('/project/'.$project->id)
            ->assertOk()
            ->assertSee('Villa One')
            ->assertSee('350 m²')
            ->assertSee('New Cairo')
            ->assertSee('storage/projects/a1.jpg')
            ->assertSee('id="lightbox"', false)
            ->assertSee('Second')          // related, active
            ->assertDontSee('>Hidden<', false);
    }

    public function test_missing_project_returns_404(): void
    {
        $this->get('/project/99999')->assertNotFound();
    }

    public function test_projects_can_be_filtered_by_category(): void
    {
        $this->seedContent();

        $this->get('/projects?category=commercial')
            ->assertOk()
            ->assertSee('Second')
            ->assertDontSee('Villa One');
    }

    public function test_language_switch_sets_session_and_redirects_back(): void
    {
        $this->from('/projects')->get('/locale/ar')
            ->assertRedirect('/projects')
            ->assertSessionHas('locale', 'ar');
    }

    public function test_contact_form_stores_a_message(): void
    {
        $this->postJson('/contact', [
            'name' => 'Visitor', 'email' => 'v@example.com', 'phone' => '0100', 'project' => 'Villa finishing', 'message' => 'Hello',
        ])->assertOk()->assertJson(['success' => true]);

        $this->assertDatabaseHas('contacts', ['email' => 'v@example.com', 'project_type' => 'Villa finishing']);
    }

    public function test_contact_form_validation_errors_are_returned_as_json(): void
    {
        $this->postJson('/contact', ['email' => 'not-an-email'])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'email', 'message']);

        $this->assertSame(0, Contact::count());
    }

    public function test_contact_page_form_posts_to_existing_endpoint(): void
    {
        $this->get('/contact')
            ->assertSee('action="'.route('contact.store').'"', false)
            ->assertSee('name="project"', false)
            ->assertSee('name="message"', false);
    }

    public function test_reading_pages_does_not_create_contact_info(): void
    {
        $this->get('/contact')->assertOk();
        $this->get('/about')->assertOk();

        $this->assertSame(0, ContactInfo::count());
    }
}
