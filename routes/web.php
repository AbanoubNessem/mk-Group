<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

// Language switching route
Route::get('/locale/{lang}', [HomeController::class, 'switchLocale'])->name('locale.switch');

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/project/{id?}', [HomeController::class, 'project'])->name('project');

// API Routes for projects
Route::get('/api/projects', [HomeController::class, 'getProjects'])->name('api.projects');
Route::get('/api/project/{id}', [HomeController::class, 'getProject'])->name('api.project');

// Contact Form Route
Route::post('/contact', [HomeController::class, 'storeContact'])->name('contact.store');

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Dashboard Routes (Protected by authentication)
Route::prefix('dashboard')->name('dashboard.')->middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('index');
    
    // Home Sections
    Route::get('/home', [DashboardController::class, 'homeSections'])->name('home');
    Route::get('/home/form/{id?}', [DashboardController::class, 'homeSectionForm'])->name('home.form');
    Route::match(['post', 'put'], '/home/store/{id?}', [DashboardController::class, 'homeSectionStore'])->name('home.store');
    Route::delete('/home/delete/{id}', [DashboardController::class, 'homeSectionDelete'])->name('home.delete');
    
    // Services
    Route::get('/services', [DashboardController::class, 'services'])->name('services');
    Route::get('/services/form/{id?}', [DashboardController::class, 'serviceForm'])->name('services.form');
    Route::match(['post', 'put'], '/services/store/{id?}', [DashboardController::class, 'serviceStore'])->name('services.store');
    Route::delete('/services/delete/{id}', [DashboardController::class, 'serviceDelete'])->name('services.delete');
    
    // About Sections
    Route::get('/about', [DashboardController::class, 'aboutSections'])->name('about');
    Route::get('/about/form/{id?}', [DashboardController::class, 'aboutSectionForm'])->name('about.form');
    Route::match(['post', 'put'], '/about/store/{id?}', [DashboardController::class, 'aboutSectionStore'])->name('about.store');
    Route::delete('/about/delete/{id}', [DashboardController::class, 'aboutSectionDelete'])->name('about.delete');
    
    // Portfolio (Projects)
    Route::get('/portfolio', [DashboardController::class, 'projects'])->name('portfolio');
    Route::get('/portfolio/form/{id?}', [DashboardController::class, 'projectForm'])->name('portfolio.form');
    Route::match(['post', 'put'], '/portfolio/store/{id?}', [DashboardController::class, 'projectStore'])->name('portfolio.store');
    Route::delete('/portfolio/delete/{id}', [DashboardController::class, 'projectDelete'])->name('portfolio.delete');
    
    // Hero Slides
    Route::get('/hero', [DashboardController::class, 'heroSlides'])->name('hero');
    Route::get('/hero/form/{id?}', [DashboardController::class, 'heroSlideForm'])->name('hero.form');
    Route::match(['post', 'put'], '/hero/store/{id?}', [DashboardController::class, 'heroSlideStore'])->name('hero.store');
    Route::delete('/hero/delete/{id}', [DashboardController::class, 'heroSlideDelete'])->name('hero.delete');
    
    // Countries
    Route::get('/countries', [DashboardController::class, 'countries'])->name('countries');
    Route::get('/countries/form/{id?}', [DashboardController::class, 'countryForm'])->name('countries.form');
    Route::match(['post', 'put'], '/countries/store/{id?}', [DashboardController::class, 'countryStore'])->name('countries.store');
    Route::delete('/countries/delete/{id}', [DashboardController::class, 'countryDelete'])->name('countries.delete');
    
    // Contacts
    Route::get('/contacts', [DashboardController::class, 'contacts'])->name('contacts');
    Route::delete('/contacts/delete/{id}', [DashboardController::class, 'contactDelete'])->name('contacts.delete');
    
    // Contact Info
    Route::get('/contact-info', [DashboardController::class, 'contactInfo'])->name('contact-info');
    Route::post('/contact-info/store', [DashboardController::class, 'contactInfoStore'])->name('contact-info.store');
    
    // Stats
    Route::get('/stats', [DashboardController::class, 'stats'])->name('stats');
    Route::get('/stats/form/{id?}', [DashboardController::class, 'statForm'])->name('stats.form');
    Route::match(['post', 'put'], '/stats/store/{id?}', [DashboardController::class, 'statStore'])->name('stats.store');
    Route::delete('/stats/delete/{id}', [DashboardController::class, 'statDelete'])->name('stats.delete');
});
