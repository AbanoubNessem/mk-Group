<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HomeSection;
use App\Models\Service;
use App\Models\AboutSection;
use App\Models\Project;
use App\Models\HeroSlide;
use App\Models\Country;
use App\Models\Contact;
use App\Models\ContactInfo;
use App\Models\Stat;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Helpers\ImageHelper;

class DashboardController extends Controller
{
    /**
     * Display dashboard home page
     */
    public function index()
    {
        return view('dashboard.index');
    }

    // ==================== HOME SECTIONS ====================

    /**
     * Display all home sections
     */
    public function homeSections()
    {
        $sections = HomeSection::ordered()->get();
        return view('dashboard.home.index', compact('sections'));
    }

    /**
     * Show form to create/edit home section
     */
    public function homeSectionForm($id = null)
    {
        $section = $id ? HomeSection::findOrFail($id) : new HomeSection();
        return view('dashboard.home.form', compact('section'));
    }

    /**
     * Store or update home section
     */
    public function homeSectionStore(Request $request, $id = null)
    {
        // Increase execution time for large image uploads
        set_time_limit(300);
        
        $request->validate([
            'section_key' => 'required|string|unique:home_sections,section_key,' . ($id ?? 'NULL'),
            'title' => 'nullable|string',
            'title_ar' => 'nullable|string',
            'content' => 'nullable|string',
            'content_ar' => 'nullable|string',
            'subtitle' => 'nullable|string',
            'subtitle_ar' => 'nullable|string',
            'background_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:30720',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:30720',
            'button_text' => 'nullable|string',
            'button_text_ar' => 'nullable|string',
            'button_link' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        $data = $request->except(['background_image', 'images', '_token', '_method']);
        
        // Handle checkbox
        $data['is_active'] = $request->has('is_active') ? true : false;
        
        // Handle background image upload
        if ($request->hasFile('background_image')) {
            if ($id && $section = HomeSection::find($id)) {
                Storage::disk('public')->delete($section->background_image);
            }
            $processed = ImageHelper::processAndStore(
                $request->file('background_image'),
                'public',
                'home',
                1920,
                85
            );
            if ($processed) {
                $data['background_image'] = $processed;
            }
        }

        // Handle images array
        if ($request->hasFile('images')) {
            $processedImages = ImageHelper::processMultiple(
                $request->file('images'),
                'public',
                'home',
                1920,
                85
            );
            if (!empty($processedImages)) {
                $data['images'] = $processedImages;
            }
        }

        if ($id) {
            $section = HomeSection::findOrFail($id);
            $section->update($data);
            return redirect()->route('dashboard.home')->with('success', 'تم تحديث القسم بنجاح');
        } else {
            HomeSection::create($data);
            return redirect()->route('dashboard.home')->with('success', 'تم إضافة القسم بنجاح');
        }
    }

    /**
     * Delete home section
     */
    public function homeSectionDelete($id)
    {
        $section = HomeSection::findOrFail($id);
        if ($section->background_image) {
            Storage::disk('public')->delete($section->background_image);
        }
        if ($section->images) {
            foreach ($section->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }
        $section->delete();
        return redirect()->route('dashboard.home')->with('success', 'تم حذف القسم بنجاح');
    }

    // ==================== SERVICES ====================

    /**
     * Display all services
     */
    public function services()
    {
        $services = Service::ordered()->get();
        return view('dashboard.services.index', compact('services'));
    }

    /**
     * Show form to create/edit service
     */
    public function serviceForm($id = null)
    {
        $service = $id ? Service::findOrFail($id) : new Service();
        return view('dashboard.services.form', compact('service'));
    }

    /**
     * Store or update service
     */
    public function serviceStore(Request $request, $id = null)
    {
        // Increase execution time for large image uploads
        set_time_limit(300);
        
        $request->validate([
            'name' => 'required|string',
            'name_ar' => 'required|string',
            'description' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'icon' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:30720',
            'slug' => 'nullable|string|unique:services,slug,' . ($id ?? 'NULL'),
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        $data = $request->except(['image', 'features', 'features_ar', '_token', '_method']);
        
        // Handle checkbox
        $data['is_active'] = $request->has('is_active') ? true : false;
        
        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            if ($id && $service = Service::find($id)) {
                Storage::disk('public')->delete($service->image);
            }
            $processed = ImageHelper::processAndStore(
                $request->file('image'),
                'public',
                'services',
                1920,
                85
            );
            if ($processed) {
                $data['image'] = $processed;
            }
        }

        // Handle features arrays - convert from textarea (newline-separated) to array
        if ($request->has('features') && !empty($request->features)) {
            if (is_string($request->features)) {
                // Try to decode JSON first, if fails treat as newline-separated text
                $decoded = json_decode($request->features, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $data['features'] = $decoded;
                } else {
                    // Split by newlines and filter empty lines
                    $data['features'] = array_filter(array_map('trim', explode("\n", $request->features)));
                }
            } else {
                $data['features'] = $request->features;
            }
        }
        
        if ($request->has('features_ar') && !empty($request->features_ar)) {
            if (is_string($request->features_ar)) {
                $decoded = json_decode($request->features_ar, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $data['features_ar'] = $decoded;
                } else {
                    $data['features_ar'] = array_filter(array_map('trim', explode("\n", $request->features_ar)));
                }
            } else {
                $data['features_ar'] = $request->features_ar;
            }
        }

        if ($id) {
            $service = Service::findOrFail($id);
            $service->update($data);
            return redirect()->route('dashboard.services')->with('success', 'تم تحديث الخدمة بنجاح');
        } else {
            Service::create($data);
            return redirect()->route('dashboard.services')->with('success', 'تم إضافة الخدمة بنجاح');
        }
    }

    /**
     * Delete service
     */
    public function serviceDelete($id)
    {
        $service = Service::findOrFail($id);
        if ($service->image) {
            Storage::disk('public')->delete($service->image);
        }
        $service->delete();
        return redirect()->route('dashboard.services')->with('success', 'تم حذف الخدمة بنجاح');
    }

    // ==================== ABOUT SECTIONS ====================

    /**
     * Display all about sections
     */
    public function aboutSections()
    {
        $sections = AboutSection::ordered()->get();
        return view('dashboard.about.index', compact('sections'));
    }

    /**
     * Show form to create/edit about section
     */
    public function aboutSectionForm($id = null)
    {
        $section = $id ? AboutSection::findOrFail($id) : new AboutSection();
        return view('dashboard.about.form', compact('section'));
    }

    /**
     * Store or update about section
     */
    public function aboutSectionStore(Request $request, $id = null)
    {
        // Increase execution time for large image uploads
        set_time_limit(300);
        
        $request->validate([
            'section_key' => 'required|string|unique:about_sections,section_key,' . ($id ?? 'NULL'),
            'title' => 'nullable|string',
            'title_ar' => 'nullable|string',
            'content' => 'nullable|string',
            'content_ar' => 'nullable|string',
            'subtitle' => 'nullable|string',
            'subtitle_ar' => 'nullable|string',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:30720',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:30720',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        $data = $request->except(['main_image', 'images', '_token', '_method']);
        
        // Handle checkbox
        $data['is_active'] = $request->has('is_active') ? true : false;
        
        // Handle main image upload
        if ($request->hasFile('main_image')) {
            if ($id && $section = AboutSection::find($id)) {
                Storage::disk('public')->delete($section->main_image);
            }
            $processed = ImageHelper::processAndStore(
                $request->file('main_image'),
                'public',
                'about',
                1920,
                85
            );
            if ($processed) {
                $data['main_image'] = $processed;
            }
        }

        // Handle images array
        if ($request->hasFile('images')) {
            $processedImages = ImageHelper::processMultiple(
                $request->file('images'),
                'public',
                'about',
                1920,
                85
            );
            if (!empty($processedImages)) {
                $data['images'] = $processedImages;
            }
        }

        if ($id) {
            $section = AboutSection::findOrFail($id);
            $section->update($data);
            return redirect()->route('dashboard.about')->with('success', 'تم تحديث القسم بنجاح');
        } else {
            AboutSection::create($data);
            return redirect()->route('dashboard.about')->with('success', 'تم إضافة القسم بنجاح');
        }
    }

    /**
     * Delete about section
     */
    public function aboutSectionDelete($id)
    {
        $section = AboutSection::findOrFail($id);
        if ($section->main_image) {
            Storage::disk('public')->delete($section->main_image);
        }
        if ($section->images) {
            foreach ($section->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }
        $section->delete();
        return redirect()->route('dashboard.about')->with('success', 'تم حذف القسم بنجاح');
    }

    // ==================== PORTFOLIO (PROJECTS) ====================

    /**
     * Display all projects
     */
    public function projects()
    {
        $projects = Project::ordered()->get();
        return view('dashboard.portfolio.index', compact('projects'));
    }

    /**
     * Show form to create/edit project
     */
    public function projectForm($id = null)
    {
        $project = $id ? Project::findOrFail($id) : new Project();
        $countries = Country::active()->ordered()->get();
        return view('dashboard.portfolio.form', compact('project', 'countries'));
    }

    /**
     * Store or update project
     */
    public function projectStore(Request $request, $id = null)
    {
        // Increase execution time for large image uploads
        set_time_limit(300);
        
        $request->validate([
            'name' => 'required|string',
            'name_ar' => 'required|string',
            'description' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:30720',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:30720',
            'area' => 'nullable|string',
            'rooms_count' => 'nullable|integer',
            'project_type' => 'nullable|string',
            'project_type_ar' => 'nullable|string',
            'engineering_function' => 'nullable|string',
            'engineering_function_ar' => 'nullable|string',
            'location' => 'nullable|string',
            'location_ar' => 'nullable|string',
            'category' => 'nullable|string',
            'category_ar' => 'nullable|string',
            'country_id' => 'nullable|exists:countries,id',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        $data = $request->except(['thumbnail', 'images', '_token', '_method']);
        
        // Handle checkbox
        // HTML checkboxes only send value when checked
        // For new projects: default to true (active by default)
        // For existing projects: false if unchecked, true if checked
        if ($id) {
            // For existing projects, respect checkbox state
            $data['is_active'] = $request->has('is_active');
        } else {
            // For new projects, default to true (active by default)
            // This ensures new projects appear on the home page immediately
            $data['is_active'] = true;
        }
        
        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            if ($id && $project = Project::find($id)) {
                Storage::disk('public')->delete($project->thumbnail);
            }
            $processed = ImageHelper::processAndStore(
                $request->file('thumbnail'),
                'public',
                'projects',
                1920,
                85
            );
            if ($processed) {
                $data['thumbnail'] = $processed;
            }
        }

        // Handle images array
        if ($request->hasFile('images')) {
            $processedImages = ImageHelper::processMultiple(
                $request->file('images'),
                'public',
                'projects',
                1920,
                85
            );
            if (!empty($processedImages)) {
                $data['images'] = $processedImages;
            }
        }

        if ($id) {
            $project = Project::findOrFail($id);
            $project->update($data);
            return redirect()->route('dashboard.portfolio')->with('success', 'تم تحديث المشروع بنجاح');
        } else {
            Project::create($data);
            return redirect()->route('dashboard.portfolio')->with('success', 'تم إضافة المشروع بنجاح');
        }
    }

    /**
     * Delete project
     */
    public function projectDelete($id)
    {
        $project = Project::findOrFail($id);
        if ($project->thumbnail) {
            Storage::disk('public')->delete($project->thumbnail);
        }
        if ($project->images) {
            foreach ($project->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }
        $project->delete();
        return redirect()->route('dashboard.portfolio')->with('success', 'تم حذف المشروع بنجاح');
    }

    // ==================== HERO SLIDES ====================

    /**
     * Display all hero slides
     */
    public function heroSlides()
    {
        $slides = HeroSlide::ordered()->get();
        return view('dashboard.hero.index', compact('slides'));
    }

    /**
     * Show form to create/edit hero slide
     */
    public function heroSlideForm($id = null)
    {
        $slide = $id ? HeroSlide::findOrFail($id) : new HeroSlide();
        return view('dashboard.hero.form', compact('slide'));
    }

    /**
     * Store or update hero slide
     */
    public function heroSlideStore(Request $request, $id = null)
    {
        // Increase execution time for large image uploads
        set_time_limit(300);
        
        $request->validate([
            'title' => 'nullable|string',
            'title_ar' => 'nullable|string',
            'subtitle' => 'nullable|string',
            'subtitle_ar' => 'nullable|string',
            'button_text' => 'nullable|string',
            'button_text_ar' => 'nullable|string',
            'button_link' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:30720',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        $data = $request->except(['image', '_token', '_method']);
        
        // Handle checkbox
        $data['is_active'] = $request->has('is_active') ? true : false;
        
        // Handle image upload
        if ($request->hasFile('image')) {
            if ($id && $slide = HeroSlide::find($id)) {
                Storage::disk('public')->delete($slide->image);
            }
            $processed = ImageHelper::processAndStore(
                $request->file('image'),
                'public',
                'hero',
                1920,
                85
            );
            if ($processed) {
                $data['image'] = $processed;
            }
        }

        if ($id) {
            $slide = HeroSlide::findOrFail($id);
            $slide->update($data);
            return redirect()->route('dashboard.hero')->with('success', 'تم تحديث الشريحة بنجاح');
        } else {
            HeroSlide::create($data);
            return redirect()->route('dashboard.hero')->with('success', 'تم إضافة الشريحة بنجاح');
        }
    }

    /**
     * Delete hero slide
     */
    public function heroSlideDelete($id)
    {
        $slide = HeroSlide::findOrFail($id);
        if ($slide->image) {
            Storage::disk('public')->delete($slide->image);
        }
        $slide->delete();
        return redirect()->route('dashboard.hero')->with('success', 'تم حذف الشريحة بنجاح');
    }

    // ==================== COUNTRIES ====================

    /**
     * Display all countries
     */
    public function countries()
    {
        $countries = Country::ordered()->get();
        return view('dashboard.countries.index', compact('countries'));
    }

    /**
     * Show form to create/edit country
     */
    public function countryForm($id = null)
    {
        $country = $id ? Country::findOrFail($id) : new Country();
        return view('dashboard.countries.form', compact('country'));
    }

    /**
     * Store or update country
     */
    public function countryStore(Request $request, $id = null)
    {
        $request->validate([
            'name' => 'required|string',
            'name_ar' => 'required|string',
            'flag_code' => 'nullable|string|max:10',
            'flag_emoji' => 'nullable|string|max:10',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        $data = $request->except(['_token', '_method']);
        
        // Handle checkbox
        $data['is_active'] = $request->has('is_active') ? true : false;

        if ($id) {
            $country = Country::findOrFail($id);
            $country->update($data);
            return redirect()->route('dashboard.countries')->with('success', 'تم تحديث الدولة بنجاح');
        } else {
            Country::create($data);
            return redirect()->route('dashboard.countries')->with('success', 'تم إضافة الدولة بنجاح');
        }
    }

    /**
     * Delete country
     */
    public function countryDelete($id)
    {
        $country = Country::findOrFail($id);
        
        // Check if country has projects
        if ($country->projects()->count() > 0) {
            return redirect()->route('dashboard.countries')->with('error', 'لا يمكن حذف الدولة لأنها مرتبطة بمشاريع. يرجى إزالة المشاريع أولاً أو تغيير الدولة المرتبطة بها.');
        }
        
        $country->delete();
        return redirect()->route('dashboard.countries')->with('success', 'تم حذف الدولة بنجاح');
    }

    // ==================== CONTACTS ====================

    /**
     * Display all contact messages
     */
    public function contacts()
    {
        $contacts = Contact::ordered()->get();
        return view('dashboard.contacts.index', compact('contacts'));
    }

    /**
     * Delete contact message
     */
    public function contactDelete($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();
        return redirect()->route('dashboard.contacts')->with('success', 'تم حذف الرسالة بنجاح');
    }

    // ==================== CONTACT INFO ====================

    /**
     * Display contact info edit form
     */
    public function contactInfo()
    {
        $contactInfo = ContactInfo::getInfo();
        return view('dashboard.contact-info.form', compact('contactInfo'));
    }

    /**
     * Store or update contact info
     */
    public function contactInfoStore(Request $request)
    {
        $request->validate([
            'contact_title' => 'nullable|string|max:255',
            'contact_title_ar' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'hours_text' => 'nullable|string',
            'hours_text_ar' => 'nullable|string',
            'phone_numbers' => 'nullable|string', // Will be converted to array
        ]);

        $contactInfo = ContactInfo::getInfo();
        
        $data = $request->except(['phone_numbers', '_token', '_method']);
        
        // Handle phone numbers - convert from newline-separated string to array
        if ($request->has('phone_numbers') && !empty($request->phone_numbers)) {
            $phones = array_filter(array_map('trim', explode("\n", $request->phone_numbers)));
            $data['phone_numbers'] = $phones;
        }

        $contactInfo->update($data);
        
        return redirect()->route('dashboard.contact-info')->with('success', 'تم تحديث معلومات التواصل بنجاح');
    }

    // ==================== STATS ====================

    /**
     * Display all stats
     */
    public function stats()
    {
        $stats = Stat::ordered()->get();
        return view('dashboard.stats.index', compact('stats'));
    }

    /**
     * Show form to create/edit stat
     */
    public function statForm($id = null)
    {
        $stat = $id ? Stat::findOrFail($id) : new Stat();
        return view('dashboard.stats.form', compact('stat'));
    }

    /**
     * Store or update stat
     */
    public function statStore(Request $request, $id = null)
    {
        $request->validate([
            'value' => 'required|string|max:255',
            'label' => 'required|string|max:255',
            'label_ar' => 'required|string|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        $data = $request->except(['_token', '_method']);
        
        // Handle checkbox
        $data['is_active'] = $request->has('is_active') ? true : false;

        if ($id) {
            $stat = Stat::findOrFail($id);
            $stat->update($data);
            return redirect()->route('dashboard.stats')->with('success', 'تم تحديث الإحصائية بنجاح');
        } else {
            Stat::create($data);
            return redirect()->route('dashboard.stats')->with('success', 'تم إضافة الإحصائية بنجاح');
        }
    }

    /**
     * Delete stat
     */
    public function statDelete($id)
    {
        $stat = Stat::findOrFail($id);
        $stat->delete();
        return redirect()->route('dashboard.stats')->with('success', 'تم حذف الإحصائية بنجاح');
    }
}
