<?php

/*
 * Public website settings that are not stored in the database.
 * Every value is optional; the UI hides features whose value is empty.
 */
return [

    'name' => 'MK Group',

    // Other names people search for (Google, ChatGPT, Claude, Gemini…). Used in schema.org and llms.txt.
    'alternate_names' => [
        'MK Build & Renovation',
        'MK Group Egypt',
        'ام كي جروب',
        'إم كي جروب',
        'ام كيه جروب',
        'MK جروب',
        'شركة MK Group للتشطيبات والمقاولات',
    ],

    // Social / listing pages (Facebook, Instagram, Google Business Profile, LinkedIn…).
    // Add full URLs here once they exist; they help Google and AI assistants link the site to the brand.
    'same_as' => array_values(array_filter(explode(',', (string) env('MK_SOCIAL_LINKS', '')))),

    // International format, digits only (e.g. 201281271112). Enables WhatsApp buttons.
    'whatsapp' => env('MK_WHATSAPP'),

    // Optional public PDF of the company profile (path under /public or full URL).
    'company_profile_pdf' => env('MK_COMPANY_PROFILE_PDF'),

    // Areas the company serves, shown in the footer and contact page.
    'areas' => [
        'en' => ['Cairo', 'Giza', 'New Cairo'],
        'ar' => ['القاهرة', 'الجيزة', 'القاهرة الجديدة'],
    ],

    // Search engine ownership codes (Google Search Console, Bing Webmaster Tools) — set in .env
    'google_verification' => env('GOOGLE_SITE_VERIFICATION'),
    'bing_verification' => env('BING_SITE_VERIFICATION'),

    // Firebase Analytics block inherited from the original codebase.
    // Kept enabled (unchanged behaviour) until its ownership is confirmed.
    'firebase_analytics' => env('MK_FIREBASE_ANALYTICS', true),

];
