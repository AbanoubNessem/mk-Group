<?php

namespace App\Http\Controllers;

use App\Models\AboutSection;
use App\Models\ContactInfo;
use App\Models\Country;
use App\Models\HomeSection;
use App\Models\Project;
use App\Models\Service;
use App\Models\Stat;
use App\Support\Localized;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Public pages added in the redesign: projects index, services, about, contact.
 * Read-only; uses the same models and scopes as HomeController.
 */
class SiteController extends Controller
{
    private function applyLocale(): void
    {
        // Same rule as HomeController
        if (session()->has('locale')) {
            app()->setLocale(session('locale'));
        }
    }

    public function projects(Request $request)
    {
        $this->applyLocale();

        $query = Project::active()->ordered();
        if ($request->filled('country')) {
            $query->byCountry($request->country);
        }
        $all = $query->get();

        // Categories come from the projects themselves (category, else project_type)
        $categories = $all
            ->map(fn ($p) => self::categoryOf($p))
            ->filter()
            ->unique('key')
            ->values();

        $active = $request->query('category');
        $projects = $active
            ? $all->filter(fn ($p) => (self::categoryOf($p)['key'] ?? null) === $active)->values()
            : $all;

        $countries = Country::active()->ordered()->get();

        return view('pages.projects', compact('projects', 'categories', 'active', 'countries', 'all'));
    }

    public function services()
    {
        $this->applyLocale();

        $services = Service::active()->ordered()->get();
        $intro = HomeSection::active()->where('section_key', 'services')->first();

        return view('pages.services', compact('services', 'intro'));
    }

    public function about()
    {
        $this->applyLocale();

        $sections = AboutSection::active()->ordered()->get()->keyBy('section_key');
        $services = Service::active()->ordered()->get();
        $stats = Stat::active()->ordered()->get();

        return view('pages.about', compact('sections', 'services', 'stats'));
    }

    public function contact()
    {
        $this->applyLocale();

        $contactInfo = ContactInfo::first();
        $services = Service::active()->ordered()->get(['id', 'name', 'name_ar']);

        return view('pages.contact', compact('contactInfo', 'services'));
    }

    /**
     * Filter key + localized label for a project. Key is a slug of the English
     * value so URLs stay the same in both languages.
     */
    public static function categoryOf($project): ?array
    {
        $source = $project->category ?: $project->category_ar ?: $project->project_type ?: $project->project_type_ar;
        if (! filled($source)) {
            return null;
        }

        $key = Str::slug($source) ?: substr(md5($source), 0, 8);
        $label = Localized::get($project, $project->category || $project->category_ar ? 'category' : 'project_type');

        return ['key' => $key, 'label' => $label];
    }
}
