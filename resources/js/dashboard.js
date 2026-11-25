// Dashboard enhancements. Existing forms submit exactly as before;
// image-compress.js (public/js) still handles compression on submit.

const ready = (fn) => (document.readyState === 'loading' ? document.addEventListener('DOMContentLoaded', fn) : fn());

ready(() => {
    initSidebar();
    initFilePreviews();
});

function initSidebar() {
    const sidebar = document.querySelector('[data-dash-sidebar]');
    const toggle = document.querySelector('[data-dash-toggle]');
    const overlay = document.querySelector('[data-dash-overlay]');
    if (!sidebar || !toggle) return;

    const set = (open) => {
        sidebar.toggleAttribute('data-open', open);
        toggle.setAttribute('aria-expanded', String(open));
        if (overlay) overlay.hidden = !open;
        if (open) sidebar.querySelector('a')?.focus();
    };
    toggle.addEventListener('click', () => set(!sidebar.hasAttribute('data-open')));
    overlay?.addEventListener('click', () => set(false));
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && sidebar.hasAttribute('data-open')) { set(false); toggle.focus(); }
    });
}

// Show thumbnails of newly selected images before upload.
function initFilePreviews() {
    document.querySelectorAll('input[type="file"][accept*="image"]').forEach((input) => {
        const preview = document.createElement('div');
        preview.className = 'media-grid';
        preview.hidden = true;
        const label = document.createElement('p');
        label.className = 'media-label';
        label.hidden = true;
        input.after(label, preview);

        input.addEventListener('change', () => {
            preview.querySelectorAll('img').forEach((img) => URL.revokeObjectURL(img.src));
            preview.innerHTML = '';
            const files = [...input.files].filter((f) => f.type.startsWith('image/'));
            preview.hidden = label.hidden = files.length === 0;
            if (!files.length) return;
            label.textContent = files.length === 1 ? 'الصورة المختارة للرفع:' : `الصور المختارة للرفع (${files.length}):`;
            files.forEach((file) => {
                const img = document.createElement('img');
                img.alt = file.name;
                img.src = URL.createObjectURL(file);
                preview.appendChild(img);
            });
        });
    });
}
