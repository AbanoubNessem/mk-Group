<?php

namespace App\Support;

/**
 * Bilingual field access that mirrors the existing view logic:
 *   Arabic  → `{attr}_ar`, falling back to `{attr}`
 *   English → `{attr}`,    falling back to `{attr}_ar`
 */
class Localized
{
    public static function isRtl(): bool
    {
        return app()->getLocale() === 'ar';
    }

    public static function get($model, string $attribute, ?string $default = null): ?string
    {
        if (! $model) {
            return $default;
        }

        $en = $model->{$attribute} ?? null;
        $ar = $model->{$attribute.'_ar'} ?? null;

        $value = self::isRtl() ? ($ar ?: $en) : ($en ?: $ar);

        return filled($value) ? $value : $default;
    }

    /** First non-empty localized value among several attributes. */
    public static function first($model, array $attributes, ?string $default = null): ?string
    {
        foreach ($attributes as $attribute) {
            if ($value = self::get($model, $attribute)) {
                return $value;
            }
        }

        return $default;
    }

    /** Localized array attribute (e.g. features / features_ar). */
    public static function list($model, string $attribute): array
    {
        if (! $model) {
            return [];
        }

        $en = $model->{$attribute} ?? null;
        $ar = $model->{$attribute.'_ar'} ?? null;
        $value = self::isRtl() ? ($ar ?: $en) : ($en ?: $ar);

        return array_values(array_filter((array) $value, 'filled'));
    }

    /** Split admin-entered multi-line text into paragraphs. */
    public static function paragraphs(?string $text): array
    {
        if (! filled($text)) {
            return [];
        }

        return array_values(array_filter(array_map('trim', preg_split('/\R+/u', $text))));
    }

    /** Public storage URL for an uploaded file path, or null. */
    public static function media(?string $path): ?string
    {
        return filled($path) ? asset('storage/'.ltrim($path, '/')) : null;
    }

    /**
     * Admin-entered links are sometimes saved with a local dev host
     * (e.g. http://127.0.0.1:8000/#services). Keep the path, drop the host.
     */
    public static function link(?string $url, string $fallback = '#'): string
    {
        if (! filled($url)) {
            return $fallback;
        }

        if (preg_match('#^https?://(127\.0\.0\.1|localhost)(:\d+)?(/.*)?$#i', $url, $m)) {
            $path = $m[3] ?? '/';

            return url($path === '' ? '/' : $path);
        }

        return $url;
    }

    /** tel: href from a display phone number such as "+20 01281271112". */
    public static function tel(string $phone): string
    {
        $digits = preg_replace('/[^\d+]/', '', $phone);

        // "+2001…" is a common Egyptian typo for "+201…"
        return 'tel:'.preg_replace('/^\+200/', '+20', $digits);
    }
}
