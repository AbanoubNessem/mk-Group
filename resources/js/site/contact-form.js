// Progressive enhancement for the contact form.
// Posts to the existing POST /contact endpoint (HomeController@storeContact) and
// shows inline field errors / status instead of alert() dialogs.
export function initContactForm() {
    const form = document.querySelector('[data-contact-form]');
    if (!form) return;

    const status = form.querySelector('[data-form-status]');
    const submit = form.querySelector('[type="submit"]');
    const submitLabel = submit?.querySelector('span');
    const t = form.dataset;

    const clearErrors = () => {
        form.querySelectorAll('[aria-invalid="true"]').forEach((el) => el.removeAttribute('aria-invalid'));
        form.querySelectorAll('.field-error').forEach((el) => { el.hidden = true; el.textContent = ''; });
    };

    const showStatus = (type, title, message) => {
        if (!status) return;
        status.innerHTML = '';
        const tpl = document.getElementById(`notice-${type}`);
        if (tpl) {
            const node = tpl.content.cloneNode(true);
            const titleEl = node.querySelector('[data-notice-title]');
            if (titleEl) { titleEl.textContent = title || ''; titleEl.hidden = !title; }
            node.querySelector('[data-notice-body]').textContent = message;
            status.appendChild(node);
        }
        status.hidden = false;
        status.focus();
    };

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        clearErrors();
        if (status) status.hidden = true;

        if (!form.checkValidity()) {
            const invalid = [...form.elements].filter((el) => el.willValidate && !el.checkValidity());
            invalid.forEach((el) => markInvalid(form, el.name, el.validity.valueMissing ? t.required : el.validity.typeMismatch ? t.emailInvalid : el.validationMessage));
            invalid[0]?.focus();
            return;
        }

        submit.disabled = true;
        if (submitLabel) submitLabel.textContent = t.sending;

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                body: new FormData(form),
            });
            const data = await response.json().catch(() => ({}));

            if (response.ok && data.success) {
                form.reset();
                showStatus('success', t.sentTitle, t.sent);
            } else if (response.status === 422 && data.errors) {
                const names = Object.keys(data.errors);
                names.forEach((name) => markInvalid(form, name, data.errors[name][0]));
                showStatus('error', '', t.fix);
                form.querySelector(`[name="${names[0]}"]`)?.focus();
            } else {
                showStatus('error', '', t.error);
            }
        } catch {
            showStatus('error', '', t.error);
        } finally {
            submit.disabled = false;
            if (submitLabel) submitLabel.textContent = t.submit;
        }
    });

    // Clear a field's error as soon as it is edited
    form.addEventListener('input', (e) => {
        if (e.target.getAttribute('aria-invalid') === 'true') {
            e.target.removeAttribute('aria-invalid');
            const err = document.getElementById(`${e.target.id}-error`);
            if (err) err.hidden = true;
        }
    });
}

function markInvalid(form, name, message) {
    const input = form.querySelector(`[name="${name}"]`);
    if (!input) return;
    input.setAttribute('aria-invalid', 'true');
    const err = document.getElementById(`${input.id}-error`);
    if (err) {
        err.textContent = message;
        err.hidden = false;
    }
}
