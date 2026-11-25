{{-- One fullscreen viewer per page; opened by any [data-gallery-item] --}}
<dialog id="lightbox" class="lightbox on-dark" aria-label="{{ __('site.lightbox.label') }}">
    <div class="flex items-center justify-between gap-4 px-4 py-4 md:px-8">
        <p class="text-sm text-white/75" data-lightbox-counter data-template="{{ __('site.lightbox.counter') }}" aria-live="polite"></p>
        <button type="button" class="lightbox-btn" data-lightbox-close autofocus>
            <x-ui.icon name="close" :size="22" />
            <span class="sr-only">{{ __('site.lightbox.close') }}</span>
        </button>
    </div>
    <div class="lightbox-stage">
        <img data-lightbox-image alt="" draggable="false">
        <button type="button" class="lightbox-btn lightbox-nav" data-dir="prev">
            <x-ui.icon name="chevron" :size="22" class="rotate-180 rtl:rotate-0" />
            <span class="sr-only">{{ __('site.lightbox.prev') }}</span>
        </button>
        <button type="button" class="lightbox-btn lightbox-nav" data-dir="next">
            <x-ui.icon name="chevron" :size="22" class="rtl:rotate-180" />
            <span class="sr-only">{{ __('site.lightbox.next') }}</span>
        </button>
    </div>
    <p class="px-4 py-4 text-center text-sm text-white/60 md:px-8" data-lightbox-caption></p>
</dialog>
