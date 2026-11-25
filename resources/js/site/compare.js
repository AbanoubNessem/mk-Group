// Before/after comparison. A transparent range input drives the split so it is
// keyboard-accessible (arrow keys) and works with touch and mouse.
export function initCompare() {
    document.querySelectorAll('[data-compare]').forEach((el) => {
        const range = el.querySelector('.compare-range');
        if (!range) return;
        const update = () => el.style.setProperty('--pos', `${range.value}%`);
        range.addEventListener('input', update);
        update();
    });
}
