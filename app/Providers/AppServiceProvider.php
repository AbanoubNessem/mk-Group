<?php

namespace App\Providers;

use App\Models\ContactInfo;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Contact details for the public header/footer. Read-only: uses first()
        // rather than ContactInfo::getInfo(), which creates a default row.
        View::composer(['components.layouts.site', 'components.site.footer', 'components.site.contact-band'], function ($view) {
            static $contact = false;
            if ($contact === false) {
                try {
                    $contact = ContactInfo::first();
                } catch (\Throwable) {
                    $contact = null;
                }
            }
            $view->with('siteContact', $contact);
        });
    }
}
