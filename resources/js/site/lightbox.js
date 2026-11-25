// Fullscreen project gallery on a native <dialog>.
// Markup: [data-gallery] containing [data-gallery-item][data-src][data-alt]
//         and one dialog#lightbox rendered by <x-project.lightbox>.
export function initLightbox() {
    const dialog = document.getElementById('lightbox');
    const galleries = document.querySelectorAll('[data-gallery]');
    if (!dialog || !galleries.length || typeof dialog.showModal !== 'function') return;

    const img = dialog.querySelector('[data-lightbox-image]');
    const counter = dialog.querySelector('[data-lightbox-counter]');
    const caption = dialog.querySelector('[data-lightbox-caption]');
    const prevBtn = dialog.querySelector('[data-dir="prev"]');
    const nextBtn = dialog.querySelector('[data-dir="next"]');
    const rtl = document.documentElement.dir === 'rtl';
    const counterTpl = counter?.dataset.template || ':current / :total';

    let items = [];
    let index = 0;
    let opener = null;

    const render = () => {
        const item = items[index];
        img.src = item.src;
        img.alt = item.alt;
        if (caption) caption.textContent = item.alt;
        if (counter) counter.textContent = counterTpl.replace(':current', index + 1).replace(':total', items.length);
        const single = items.length < 2;
        prevBtn.hidden = single;
        nextBtn.hidden = single;
        // Preload neighbours
        [index - 1, index + 1].forEach((i) => {
            const n = items[(i + items.length) % items.length];
            if (n) new Image().src = n.src;
        });
    };

    const go = (step) => {
        index = (index + step + items.length) % items.length;
        render();
    };

    galleries.forEach((gallery) => {
        const nodes = [...gallery.querySelectorAll('[data-gallery-item]')];
        nodes.forEach((node, i) => {
            node.addEventListener('click', (e) => {
                e.preventDefault();
                items = nodes.map((n) => ({ src: n.dataset.src, alt: n.dataset.alt || '' }));
                index = Number(node.dataset.index ?? i);
                opener = node;
                render();
                dialog.showModal();
                document.documentElement.style.overflow = 'hidden';
            });
        });
    });

    prevBtn.addEventListener('click', () => go(-1));
    nextBtn.addEventListener('click', () => go(1));
    dialog.querySelector('[data-lightbox-close]').addEventListener('click', () => dialog.close());

    dialog.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowRight') go(rtl ? -1 : 1);
        if (e.key === 'ArrowLeft') go(rtl ? 1 : -1);
    });

    dialog.addEventListener('close', () => {
        document.documentElement.style.overflow = '';
        img.removeAttribute('src');
        opener?.focus();
    });

    // Close when clicking the empty stage around the photo
    dialog.querySelector('.lightbox-stage').addEventListener('click', (e) => {
        if (e.target === e.currentTarget) dialog.close();
    });

    // Swipe
    let startX = null;
    dialog.addEventListener('pointerdown', (e) => { if (e.pointerType !== 'mouse') startX = e.clientX; });
    dialog.addEventListener('pointerup', (e) => {
        if (startX === null) return;
        const dx = e.clientX - startX;
        startX = null;
        if (Math.abs(dx) < 50) return;
        const forward = dx < 0;
        go((forward !== rtl) ? 1 : -1);
    });
}
