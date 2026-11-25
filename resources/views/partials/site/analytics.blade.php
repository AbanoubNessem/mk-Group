{{-- Google Analytics (gtag) — rendered only when GOOGLE_ANALYTICS_ID is set. Unchanged from the original pages. --}}
@if (config('services.google_analytics_id'))
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ config('services.google_analytics_id') }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', @json(config('services.google_analytics_id')), {
            page_title: @json($pageTitle),
            page_location: @json($pageUrl)
        });
    </script>
@endif
