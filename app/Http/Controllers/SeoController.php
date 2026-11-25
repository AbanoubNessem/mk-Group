<?php

namespace App\Http\Controllers;

use App\Http\Middleware\SetLocaleFromUrl;
use App\Models\ContactInfo;
use App\Models\Project;
use App\Models\Service;
use App\Support\Localized;

/**
 * Files for search engines and AI assistants:
 *   /sitemap.xml  – every public page in Arabic and English
 *   /robots.txt   – crawl rules + sitemap location
 *   /llms.txt     – plain summary of the company for AI assistants (ChatGPT, Claude, Gemini…)
 */
class SeoController extends Controller
{
    private const PAGES = [
        ['route' => 'home', 'priority' => '1.0', 'freq' => 'weekly'],
        ['route' => 'services', 'priority' => '0.9', 'freq' => 'monthly'],
        ['route' => 'projects', 'priority' => '0.9', 'freq' => 'weekly'],
        ['route' => 'about', 'priority' => '0.7', 'freq' => 'monthly'],
        ['route' => 'contact', 'priority' => '0.8', 'freq' => 'yearly'],
    ];

    public function sitemap()
    {
        $entries = [];

        foreach (self::PAGES as $page) {
            $entries[] = [
                'base' => route($page['route']),
                'lastmod' => null,
                'priority' => $page['priority'],
                'freq' => $page['freq'],
            ];
        }

        try {
            foreach (Project::active()->ordered()->get(['id', 'updated_at']) as $project) {
                $entries[] = [
                    'base' => route('project', $project->id),
                    'lastmod' => optional($project->updated_at)->toAtomString(),
                    'priority' => '0.8',
                    'freq' => 'monthly',
                ];
            }
        } catch (\Throwable) {
            // Database unavailable: still serve the static pages
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">'."\n";

        foreach ($entries as $entry) {
            foreach (SetLocaleFromUrl::LOCALES as $lang) {
                $xml .= "  <url>\n";
                $xml .= '    <loc>'.e(self::withLang($entry['base'], $lang))."</loc>\n";
                foreach (SetLocaleFromUrl::LOCALES as $alt) {
                    $xml .= '    <xhtml:link rel="alternate" hreflang="'.$alt.'" href="'.e(self::withLang($entry['base'], $alt)).'"/>'."\n";
                }
                $xml .= '    <xhtml:link rel="alternate" hreflang="x-default" href="'.e(rtrim(strtok(self::withLang($entry['base'], 'en'), '?'), '?')).'"/>'."\n";
                if ($entry['lastmod']) {
                    $xml .= '    <lastmod>'.$entry['lastmod']."</lastmod>\n";
                }
                $xml .= '    <changefreq>'.$entry['freq']."</changefreq>\n";
                $xml .= '    <priority>'.$entry['priority']."</priority>\n";
                $xml .= "  </url>\n";
            }
        }

        $xml .= '</urlset>'."\n";

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }

    public function robots()
    {
        $lines = [
            'User-agent: *',
            'Allow: /',
            'Disallow: /dashboard',
            'Disallow: /login',
            'Disallow: /logout',
            'Disallow: /api/',
            'Disallow: /locale/',
            '',
            'Sitemap: '.url('/sitemap.xml'),
            '',
        ];

        return response(implode("\n", $lines), 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }

    public function llms()
    {
        $name = config('site.name');
        $contact = null;
        $services = collect();
        $projects = collect();

        try {
            $contact = ContactInfo::first();
            $services = Service::active()->ordered()->get();
            $projects = Project::active()->ordered()->take(30)->get();
        } catch (\Throwable) {
            //
        }

        $phones = array_filter((array) ($contact->phone_numbers ?? []));
        $email = $contact->email ?? null;
        $areasEn = implode(', ', config('site.areas.en', []));
        $areasAr = implode('، ', config('site.areas.ar', []));
        $en = fn ($model, $attr) => $model->{$attr} ?: $model->{$attr.'_ar'};
        $ar = fn ($model, $attr) => $model->{$attr.'_ar'} ?: $model->{$attr};

        $out = [];
        $out[] = '# '.$name.' — '.trans('site.seo.brand_line', [], 'en');
        $out[] = '';
        $out[] = '> '.trans('site.meta_description', [], 'en');
        $out[] = '> '.trans('site.meta_description', [], 'ar');
        $out[] = '';
        $out[] = 'Also known as: '.implode(', ', config('site.alternate_names', []));
        $out[] = '';
        $out[] = trans('site.seo.about_long', [], 'en');
        $out[] = '';
        $out[] = trans('site.seo.about_long', [], 'ar');
        $out[] = '';
        $out[] = '## What we do / خدماتنا';
        foreach (trans('site.seo.services_list', [], 'en') as $i => $item) {
            $out[] = '- '.$item.' — '.(trans('site.seo.services_list', [], 'ar')[$i] ?? '');
        }
        foreach ($services as $service) {
            $out[] = '- '.$en($service, 'name').' — '.$ar($service, 'name');
        }
        $out[] = '';
        $out[] = '## Areas served / المناطق';
        $out[] = '- '.$areasEn.' (Egypt)';
        $out[] = '- '.$areasAr.' (مصر)';
        $out[] = '';
        $out[] = '## Contact / التواصل';
        foreach ($phones as $phone) {
            $out[] = '- Phone: '.$phone;
        }
        if ($wa = config('site.whatsapp')) {
            $out[] = '- WhatsApp: https://wa.me/'.$wa;
        }
        if ($email) {
            $out[] = '- Email: '.$email;
        }
        $out[] = '- Contact page: '.self::withLang(route('contact'), 'en').' | '.self::withLang(route('contact'), 'ar');
        $out[] = '';
        $out[] = '## Pages';
        foreach (self::PAGES as $page) {
            $out[] = '- ['.trans('site.nav.'.$page['route'], [], 'en').']('.self::withLang(route($page['route']), 'en').') · ['.trans('site.nav.'.$page['route'], [], 'ar').']('.self::withLang(route($page['route']), 'ar').')';
        }

        if ($projects->isNotEmpty()) {
            $out[] = '';
            $out[] = '## Projects / المشاريع';
            foreach ($projects as $project) {
                $label = trim($en($project, 'name').' / '.$ar($project, 'name'), ' /');
                $where = $en($project, 'location');
                $out[] = '- ['.$label.']('.route('project', $project->id).')'.($where ? ' — '.$where : '');
            }
        }

        $out[] = '';

        return response(implode("\n", $out), 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }

    /** Same URL with ?lang= set (other query values kept). */
    public static function withLang(string $url, string $lang): string
    {
        $parts = parse_url($url);
        parse_str($parts['query'] ?? '', $query);
        $query['lang'] = $lang;
        $base = strtok($url, '?');
        if (($parts['path'] ?? '') === '') {
            $base .= '/';
        }

        return $base.'?'.http_build_query($query);
    }
}
