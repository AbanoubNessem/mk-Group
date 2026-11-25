// MK Group public site. Vanilla modules, each initialised only when its markup exists.
import { initHeader } from './site/header';
import { initContactForm } from './site/contact-form';
import { initLightbox } from './site/lightbox';
import { initCompare } from './site/compare';

const ready = (fn) => (document.readyState === 'loading' ? document.addEventListener('DOMContentLoaded', fn) : fn());

ready(() => {
    initHeader();
    initContactForm();
    initLightbox();
    initCompare();
});
