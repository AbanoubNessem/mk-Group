<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\HomeSection;
use App\Models\Service;
use App\Models\AboutSection;
use App\Models\HeroSlide;
use App\Models\Country;
use App\Models\Contact;
use App\Models\ContactInfo;
use App\Models\Stat;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Set locale from session or default
        if (session()->has('locale')) {
            app()->setLocale(session('locale'));
        }
        
        // Get all active sections from database
        $homeSections = HomeSection::active()->ordered()->get()->keyBy('section_key');
        $services = Service::active()->ordered()->get();
        $aboutSections = AboutSection::active()->ordered()->get()->keyBy('section_key');
        $countries = Country::active()->ordered()->get();
        
        // Get projects - filter by country if provided
        $projectsQuery = Project::active()->ordered();
        if ($request->has('country') && $request->country) {
            $projectsQuery->byCountry($request->country);
        }
        $projects = $projectsQuery->get();
        
        $heroSlides = HeroSlide::active()->ordered()->get();
        $contactInfo = ContactInfo::getInfo();
        $stats = Stat::active()->ordered()->get();
        
        return view('home', compact('homeSections', 'services', 'aboutSections', 'projects', 'heroSlides', 'countries', 'contactInfo', 'stats'));
    }
    
    public function switchLocale($lang)
    {
        // Validate language
        if (!in_array($lang, ['en', 'ar'])) {
            $lang = 'en';
        }
        
        // Set locale in session
        session(['locale' => $lang]);
        app()->setLocale($lang);
        
        // Redirect back to previous page or home
        return redirect()->back();
    }
    
    public function project($id = null)
    {
        // Set locale from session or default
        if (session()->has('locale')) {
            app()->setLocale(session('locale'));
        }
        
        if ($id) {
            $project = Project::findOrFail($id);
            return view('project', compact('project'));
        }
        return view('project');
    }

    /**
     * Get all projects as JSON (for API)
     */
    public function getProjects(Request $request)
    {
        try {
            $projectsQuery = Project::active()->ordered();
            
            // Filter by country if provided
            if ($request->has('country_id') && $request->country_id) {
                $projectsQuery->byCountry($request->country_id);
            }
            
            $projects = $projectsQuery->get();
            
            // Transform projects to include full image URLs
            $projects = $projects->map(function ($project) {
                $projectData = $project->toArray();
                
                if ($project->thumbnail) {
                    $projectData['thumbnail'] = asset('storage/' . $project->thumbnail);
                }
                
                if ($project->images && is_array($project->images)) {
                    $projectData['images'] = array_map(function ($image) {
                        return asset('storage/' . $image);
                    }, $project->images);
                }
                
                // Include country data
                if ($project->country) {
                    $projectData['country'] = [
                        'id' => $project->country->id,
                        'name' => $project->country->name,
                        'name_ar' => $project->country->name_ar,
                        'flag_emoji' => $project->country->flag_emoji,
                    ];
                }
                
                return $projectData;
            });
            
            return response()->json($projects->values());
        } catch (\Exception $e) {
            \Log::error('Error in getProjects: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load projects'], 500);
        }
    }

    /**
     * Get single project as JSON
     */
    public function getProject($id)
    {
        $project = Project::findOrFail($id);
        
        return response()->json($project);
    }

    /**
     * Store contact form submission
     */
    public function storeContact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:255',
            'project' => 'nullable|string|max:255',
            'message' => 'required|string',
        ]);

        Contact::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'project_type' => $request->project,
            'message' => $request->message,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم إرسال رسالتك بنجاح. سنتواصل معك قريباً.'
        ]);
    }
}

