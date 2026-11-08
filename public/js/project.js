// Project Gallery Slider Manager
class ProjectGallerySlider {
    constructor() {
        this.currentSlide = 0;
        this.slides = [];
        this.init();
    }
    
    init() {
        const sliderTrack = document.getElementById('slider-track');
        if (!sliderTrack) return;
        
        this.slides = Array.from(sliderTrack.querySelectorAll('.slide'));
        if (this.slides.length === 0) return;
        
        this.bindEvents();
        this.updateCounter();
    }
    
    goToSlide(index) {
        if (index < 0 || index >= this.slides.length) return;
        
        // Remove active class from all slides, dots, and thumbnails
        const currentSlide = document.querySelector('.slide.active');
        const currentDot = document.querySelector('.dot.active');
        const currentThumbnail = document.querySelector('.thumbnail-item.active');
        
        if (currentSlide) currentSlide.classList.remove('active');
        if (currentDot) currentDot.classList.remove('active');
        if (currentThumbnail) currentThumbnail.classList.remove('active');
        
        // Add active class to current slide, dot, and thumbnail
        if (this.slides[index]) this.slides[index].classList.add('active');
        
        const dots = document.querySelectorAll('.dot');
        if (dots[index]) dots[index].classList.add('active');
        
        const thumbnails = document.querySelectorAll('.thumbnail-item');
        if (thumbnails[index]) thumbnails[index].classList.add('active');
        
        // Update counter
        const currentCounter = document.querySelector('.image-counter .current');
        if (currentCounter) {
            currentCounter.textContent = index + 1;
        }
        
        this.currentSlide = index;
    }
    
    nextSlide() {
        const nextIndex = (this.currentSlide + 1) % this.slides.length;
        this.goToSlide(nextIndex);
    }
    
    prevSlide() {
        const prevIndex = (this.currentSlide - 1 + this.slides.length) % this.slides.length;
        this.goToSlide(prevIndex);
    }
    
    updateCounter() {
        const currentCounter = document.querySelector('.image-counter .current');
        if (currentCounter) {
            currentCounter.textContent = this.currentSlide + 1;
        }
    }
    
    bindEvents() {
        // Next/Prev buttons
        const nextBtn = document.getElementById('next-slide');
        const prevBtn = document.getElementById('prev-slide');
        
        if (nextBtn) {
            nextBtn.addEventListener('click', () => this.nextSlide());
        }
        
        if (prevBtn) {
            prevBtn.addEventListener('click', () => this.prevSlide());
        }
        
        // Dots navigation
        const dots = document.querySelectorAll('.dot');
        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => this.goToSlide(index));
        });
        
        // Thumbnail navigation
        const thumbnails = document.querySelectorAll('.thumbnail-item');
        thumbnails.forEach((thumbnail, index) => {
            thumbnail.addEventListener('click', () => this.goToSlide(index));
        });
        
        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowLeft') this.prevSlide();
            if (e.key === 'ArrowRight') this.nextSlide();
        });
        
        // Touch/swipe navigation
        let startX = 0;
        let endX = 0;
        
        const sliderTrack = document.getElementById('slider-track');
        if (sliderTrack) {
            sliderTrack.addEventListener('touchstart', (e) => {
                startX = e.touches[0].clientX;
            });
            
            sliderTrack.addEventListener('touchend', (e) => {
                endX = e.changedTouches[0].clientX;
                const diff = startX - endX;
                if (Math.abs(diff) > 50) {
                    if (diff > 0) this.nextSlide();
                    else this.prevSlide();
                }
            });
        }
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    new ProjectGallerySlider();
});
