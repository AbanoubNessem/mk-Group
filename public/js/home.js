// Hero Slider Functionality
class HeroSlider {
    constructor() {
        this.currentSlide = 0;
        this.slides = document.querySelectorAll('.hero-slide');
        this.dots = document.querySelectorAll('.slider-dot');
        this.prevBtn = document.querySelector('.slider-arrow.prev');
        this.nextBtn = document.querySelector('.slider-arrow.next');
        this.autoPlayInterval = null;
        
        this.init();
    }
    
    init() {
        // Ensure first slide content is active on load
        if (this.slides.length > 0) {
            const firstContent = document.querySelector('.hero-slide-content[data-content-index="0"]');
            if (firstContent) {
                firstContent.classList.add('active');
            }
        }
        
        this.bindEvents();
        this.startAutoPlay();
    }
    
    bindEvents() {
        // Previous button
        if (this.prevBtn) {
            this.prevBtn.addEventListener('click', () => {
                this.prevSlide();
                this.restartAutoPlay();
            });
        }
        
        // Next button
        if (this.nextBtn) {
            this.nextBtn.addEventListener('click', () => {
                this.nextSlide();
                this.restartAutoPlay();
            });
        }
        
        // Dots navigation
        this.dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                this.goToSlide(index);
                this.restartAutoPlay();
            });
        });
        
        // Pause auto-play on hover
        const hero = document.querySelector('.hero');
        if (hero) {
            hero.addEventListener('mouseenter', () => {
                this.pauseAutoPlay();
            });
            
            hero.addEventListener('mouseleave', () => {
                this.startAutoPlay();
            });
        }
        
        // Ensure auto-play continues even if there are errors
        window.addEventListener('focus', () => {
            this.startAutoPlay();
        });
        
        // Restart auto-play when page becomes visible again
        document.addEventListener('visibilitychange', () => {
            if (!document.hidden) {
                this.startAutoPlay();
            } else {
                this.pauseAutoPlay();
            }
        });
    }
    
    goToSlide(index) {
        if (this.slides.length === 0) return;
        
        // Remove active class from current slide and dot
        this.slides[this.currentSlide].classList.remove('active');
        this.dots[this.currentSlide].classList.remove('active');
        
        // Remove active class from current content
        const currentContent = document.querySelector(`.hero-slide-content[data-content-index="${this.currentSlide}"]`);
        if (currentContent) {
            currentContent.classList.remove('active');
        }
        
        // Update current slide
        this.currentSlide = index;
        
        // Add active class to new slide and dot
        this.slides[this.currentSlide].classList.add('active');
        this.dots[this.currentSlide].classList.add('active');
        
        // Add active class to new content
        const newContent = document.querySelector(`.hero-slide-content[data-content-index="${this.currentSlide}"]`);
        if (newContent) {
            newContent.classList.add('active');
        }
    }
    
    nextSlide() {
        if (this.slides.length === 0) return;
        const nextIndex = (this.currentSlide + 1) % this.slides.length;
        this.goToSlide(nextIndex);
    }
    
    prevSlide() {
        if (this.slides.length === 0) return;
        const prevIndex = (this.currentSlide - 1 + this.slides.length) % this.slides.length;
        this.goToSlide(prevIndex);
    }
    
    startAutoPlay() {
        // Clear any existing interval first
        if (this.autoPlayInterval) {
            clearInterval(this.autoPlayInterval);
        }
        
        this.autoPlayInterval = setInterval(() => {
            this.nextSlide();
        }, 3000);
    }
    
    pauseAutoPlay() {
        if (this.autoPlayInterval) {
            clearInterval(this.autoPlayInterval);
            this.autoPlayInterval = null;
        }
    }
    
    restartAutoPlay() {
        this.pauseAutoPlay();
        setTimeout(() => {
            this.startAutoPlay();
        }, 1000);
    }
}

// Language Management
class LanguageManager {
    constructor() {
        this.currentLang = 'en';
        this.translations = null;
        this.init();
    }
    
    async init() {
        // Get current language from HTML lang attribute (set by server) or localStorage
        const htmlLang = document.documentElement.getAttribute('lang');
        if (htmlLang && (htmlLang === 'en' || htmlLang === 'ar')) {
            this.currentLang = htmlLang;
        } else {
            // Fallback to saved language preference or default to English
            const savedLang = localStorage.getItem('preferredLanguage');
            if (savedLang && (savedLang === 'en' || savedLang === 'ar')) {
                this.currentLang = savedLang;
            } else {
                this.currentLang = 'en';
            }
        }
        
        // Fallback to inline translations
        this.translations = {
            // en: {
            //     home: "Home", services: "Services", about: "About", portfolio: "Portfolio", contact: "Contact",
            //     getStarted: "Get Started", heroTitle: "Crafting Excellence in Design",
            //     heroSubtitle: "Where timeless elegance meets contemporary innovation. We create spaces and experiences that inspire.",
            //     discoverButton: "Discover Our Work", servicesTitle: "Our Services",
            //     servicesSubtitle: "Comprehensive solutions tailored to your vision",
            //     portfolioTitle: "Our Portfolio", portfolioSubtitle: "A showcase of our finest work and creative achievements",
            //     viewProject: "View Project"
            // },
            // ar: {
            //     home: "الرئيسية", services: "خدماتنا", about: "من نحن", portfolio: "أعمالنا", contact: "اتصل بنا",
            //     getStarted: "ابدأ الآن", heroTitle: "نصنع التميز في التصميم",
            //     heroSubtitle: "حيث يلتقي الأناقة الخالدة بالابتكار المعاصر. نخلق مساحات وتجارب تلهم",
            //     discoverButton: "اكتشف أعمالنا", servicesTitle: "خدماتنا",
            //     servicesSubtitle: "حلول شاملة مصممة خصيصاً لرؤيتك",
            //     portfolioTitle: "معرض أعمالنا", portfolioSubtitle: "عرض لأفضل أعمالنا وإنجازاتنا الإبداعية",
            //     viewProject: "عرض المشروع"
            // }
        };
        this.loadLanguage();
        this.bindLanguageEvents();
    }
    
    loadLanguage() {
        // Set document direction
        document.documentElement.dir = this.currentLang === 'ar' ? 'rtl' : 'ltr';
        
        // Update all translatable elements (but skip hero slide content which comes from database)
        // Note: Translations are now handled server-side, so we only update direction and button states
        if (this.translations && this.translations[this.currentLang]) {
            document.querySelectorAll('[data-translate]').forEach(element => {
                // Skip hero slide content - it comes from database, not translations
                if (element.closest('.hero-slide-content')) {
                    return;
                }
                
                const key = element.getAttribute('data-translate');
                if (this.translations[this.currentLang][key]) {
                    if (element.tagName === 'INPUT' || element.tagName === 'TEXTAREA') {
                        element.placeholder = this.translations[this.currentLang][key];
                    } else {
                        element.textContent = this.translations[this.currentLang][key];
                    }
                }
            });
        }
        
        // Update language buttons
        document.querySelectorAll('.lang-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        const activeBtn = document.querySelector(`[data-lang="${this.currentLang}"]`);
        if (activeBtn) {
            activeBtn.classList.add('active');
        }
        
        // Save language preference
        localStorage.setItem('preferredLanguage', this.currentLang);
    }
    
    switchLanguage(lang) {
        // Validate language
        if (!['en', 'ar'].includes(lang)) {
            console.error('Invalid language:', lang);
            return;
        }
        
        // Reload page with new locale
        window.location.href = `/locale/${lang}`;
    }
    
    bindLanguageEvents() {
        document.querySelectorAll('.lang-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const lang = btn.getAttribute('data-lang');
                this.switchLanguage(lang);
            });
        });
    }
}

// Portfolio Management
class PortfolioManager {
    constructor() {
        this.currentLanguage = 'en';
        this.portfolioGrid = null;
        this.projects = [];
        this.hasServerContent = false; // Flag to track if content is from server
        this.init();
    }
    
    async init() {
        this.portfolioGrid = document.getElementById('portfolio-grid');
        console.log('PortfolioManager init - Grid element:', this.portfolioGrid);
        
        if (!this.portfolioGrid) {
            console.error('Portfolio grid element not found! Make sure id="portfolio-grid" exists in HTML.');
            return;
        }
        
        // Check if there's already content from server-side rendering
        const existingItems = this.portfolioGrid.querySelectorAll('.portfolio-item');
        this.hasServerContent = existingItems.length > 0;
        
        if (this.hasServerContent) {
            console.log('Found', existingItems.length, 'existing portfolio items from server. Keeping server-rendered content.');
            // Don't interfere with server-rendered content - it's already correct
            return;
        }
        
        // Only load from API if there's no server-rendered content
        await this.loadProjects();
        
        // Only render if we have projects from API and no server content
        if (this.projects.length > 0) {
            this.renderPortfolio();
        } else {
            // Only show "no projects" message if there's no server-rendered content either
            this.renderPortfolio();
        }
    }
    
    async loadProjects() {
        try {
            console.log('Fetching projects from /api/projects...');
            const response = await fetch('/api/projects', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                }
            });
            
            console.log('API Response status:', response.status);
            console.log('API Response headers:', response.headers);
            
            if (response.ok) {
                const data = await response.json();
                this.projects = Array.isArray(data) ? data : [];
                console.log('Projects loaded successfully:', this.projects.length);
                if (this.projects.length > 0) {
                    console.log('First project:', this.projects[0]);
                } else {
                    console.warn('No projects returned from API. Make sure projects are active in dashboard.');
                }
            } else {
                console.error('Failed to load projects. Status:', response.status);
                const errorText = await response.text();
                console.error('Error response:', errorText);
                this.projects = [];
            }
        } catch (error) {
            console.error('Error loading projects:', error);
            console.error('Error details:', error.message, error.stack);
            this.projects = [];
        }
    }
    
    renderPortfolio() {
        console.log('Rendering portfolio. Projects count:', this.projects.length);
        console.log('Portfolio grid element:', this.portfolioGrid);
        
        if (!this.portfolioGrid) {
            console.error('Portfolio grid element not found!');
            return;
        }
        
        if (this.projects.length === 0) {
            this.portfolioGrid.innerHTML = `
                <div style="text-align: center; padding: 60px 20px; color: #666;">
                    <p style="font-size: 18px; margin-bottom: 10px;">No projects available</p>
                    <p style="font-size: 14px; color: #999;">Please add active projects from the dashboard.</p>
                    <a href="/dashboard/portfolio" style="display: inline-block; margin-top: 20px; padding: 10px 20px; background: #2e394b; color: white; text-decoration: none; border-radius: 5px;">Go to Dashboard</a>
                </div>
            `;
            console.warn('No projects to render. Make sure projects are active in dashboard.');
            return;
        }
        
        // Clear existing content
        this.portfolioGrid.innerHTML = '';
        
        // Render each project
        this.projects.forEach((project, index) => {
            try {
                const projectItem = this.createProjectItem(project);
                // Add animation delay for staggered effect
                projectItem.style.animationDelay = `${index * 0.1}s`;
                projectItem.classList.add('fade-in-up');
                this.portfolioGrid.appendChild(projectItem);
                console.log(`Rendered project ${index + 1}: ${project.name || project.name_ar}`);
            } catch (error) {
                console.error(`Error rendering project ${index}:`, error);
            }
        });
        
        console.log(`Successfully rendered ${this.projects.length} projects`);
    }
    
    createProjectItem(project) {
        const item = document.createElement('div');
        item.className = 'portfolio-item';
        
        // Use thumbnail from database or fallback to images array first image
        let thumbnailUrl = project.thumbnail || (project.images && project.images.length > 0 ? project.images[0] : '');
        if (thumbnailUrl) {
            item.style.backgroundImage = `url(${thumbnailUrl})`;
            item.style.backgroundSize = 'cover';
            item.style.backgroundPosition = 'center';
        } else {
            // Fallback background color if no image
            item.style.backgroundColor = '#f0f0f0';
        }
        
        // Create overlay
        const overlay = document.createElement('div');
        overlay.className = 'portfolio-overlay';
        
        // Create content
        const content = document.createElement('div');
        content.className = 'portfolio-content';
        
        const title = document.createElement('h4');
        title.textContent = this.currentLanguage === 'ar' ? (project.name_ar || project.name) : (project.name || project.name_ar);
        
        const category = document.createElement('p');
        const categoryText = this.currentLanguage === 'ar' 
            ? (project.category_ar || project.category || project.engineering_function_ar || project.engineering_function)
            : (project.category || project.category_ar || project.engineering_function || project.engineering_function_ar);
        category.textContent = categoryText || '';
        
        const viewBtn = document.createElement('a');
        viewBtn.href = `/project/${project.id}`;
        viewBtn.className = 'view-project-btn';
        viewBtn.setAttribute('data-translate', 'viewProject');
        // Update button text based on current language
        if (typeof window.languageManager !== 'undefined' && window.languageManager.translations) {
            viewBtn.textContent = window.languageManager.translations[this.currentLanguage].viewProject;
        } else {
            viewBtn.textContent = this.currentLanguage === 'ar' ? 'عرض المشروع' : 'View Project';
        }
        
        content.appendChild(title);
        content.appendChild(category);
        content.appendChild(viewBtn);
        overlay.appendChild(content);
        item.appendChild(overlay);
        
        // Add click event
        item.addEventListener('click', () => {
            window.location.href = `/project/${project.id}`;
        });
        
        return item;
    }
    
    async updateLanguage(lang) {
        this.currentLanguage = lang;
        // Only re-render if we're managing the content (not server-rendered)
        if (this.hasServerContent) {
            console.log('Content is server-rendered, skipping language update re-render');
            return;
        }
        // Reload projects if needed (they should already be loaded, but just in case)
        if (this.projects.length === 0) {
            await this.loadProjects();
        }
        this.renderPortfolio();
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', async () => {
    console.log('DOM Content Loaded - Initializing components...');
    
    const slider = new HeroSlider();
    const languageManager = new LanguageManager();
    const portfolioManager = new PortfolioManager();
    
    // Make managers globally accessible
    window.portfolioManager = portfolioManager;
    window.languageManager = languageManager;
    
    // Sync language with portfolio manager (only if not server-rendered)
    setTimeout(async () => {
        console.log('Syncing portfolio with language manager...');
        portfolioManager.currentLanguage = languageManager.currentLang;
        
        // Only re-render if content is NOT from server
        if (!portfolioManager.hasServerContent) {
            // Re-render portfolio to ensure it's displayed
            if (portfolioManager.projects.length > 0) {
                console.log('Re-rendering portfolio with', portfolioManager.projects.length, 'projects');
                portfolioManager.renderPortfolio();
            } else {
                console.warn('No projects loaded yet, waiting...');
                // Try to reload projects if none were loaded
                await portfolioManager.loadProjects();
                portfolioManager.renderPortfolio();
            }
        } else {
            console.log('Portfolio content is server-rendered, skipping re-render');
        }
    }, 500);
});

// Smooth scrolling for navigation links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Form submission
const contactForm = document.getElementById('contact-form');
if (contactForm) {
    contactForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const submitBtn = this.querySelector('.submit-btn');
        const originalText = submitBtn.textContent;
        submitBtn.disabled = true;
        submitBtn.textContent = 'جاري الإرسال...';
        
        try {
            const formData = new FormData(this);
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                             this.querySelector('input[name="_token"]')?.value;
            
            const response = await fetch('/contact', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: formData
            });
            
            const data = await response.json();
            
            if (response.ok && data.success) {
                alert(data.message || 'تم إرسال رسالتك بنجاح. سنتواصل معك قريباً.');
                this.reset();
            } else {
                alert(data.message || 'حدث خطأ أثناء إرسال الرسالة. يرجى المحاولة مرة أخرى.');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('حدث خطأ أثناء إرسال الرسالة. يرجى المحاولة مرة أخرى.');
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        }
    });
}

// Add animation on scroll
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -100px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('fade-in-up');
        }
    });
}, observerOptions);

// Observe service cards and other elements
document.querySelectorAll('.service-card, .stat-item, .portfolio-item').forEach(el => {
    observer.observe(el);
});

// Header scroll effect
window.addEventListener('scroll', function() {
    const header = document.querySelector('.header');
    if (header) {
        if (window.scrollY > 100) {
            header.style.background = 'rgba(255, 255, 255, 0.98)';
            header.style.boxShadow = '0 2px 20px rgba(0, 0, 0, 0.1)';
        } else {
            header.style.background = 'rgba(255, 255, 255, 0.95)';
            header.style.boxShadow = 'none';
        }
    }
});

// Country Filter Function
function filterByCountry(countryId) {
    // Update active button
    document.querySelectorAll('.country-flag-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    
    const activeBtn = countryId 
        ? document.querySelector(`[data-country-id="${countryId}"]`)
        : document.querySelector('[data-country-id=""]');
    
    if (activeBtn) {
        activeBtn.classList.add('active');
    }
    
    // Update URL and reload projects
    const url = new URL(window.location.href);
    if (countryId) {
        url.searchParams.set('country', countryId);
    } else {
        url.searchParams.delete('country');
    }
    
    // Scroll to portfolio section
    const portfolioSection = document.getElementById('portfolio');
    if (portfolioSection) {
        portfolioSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
    
    // Reload page with filter
    window.location.href = url.toString();
}

