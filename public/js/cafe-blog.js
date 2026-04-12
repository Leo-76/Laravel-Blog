/**
 * CaféHistoire — Laravel Blog Package JS
 */
(function () {
    'use strict';

    /* ── Mobile Nav Toggle ─────────────────────────────────────────────────── */
    const navToggle  = document.getElementById('navToggle');
    const mobileMenu = document.getElementById('mobileMenu');

    if (navToggle && mobileMenu) {
        navToggle.addEventListener('click', () => {
            const isOpen = mobileMenu.classList.toggle('is-open');
            navToggle.setAttribute('aria-expanded', String(isOpen));
            mobileMenu.setAttribute('aria-hidden', String(!isOpen));
        });

        // Close on outside click
        document.addEventListener('click', (e) => {
            if (!navToggle.contains(e.target) && !mobileMenu.contains(e.target)) {
                mobileMenu.classList.remove('is-open');
                navToggle.setAttribute('aria-expanded', 'false');
                mobileMenu.setAttribute('aria-hidden', 'true');
            }
        });

        // Close on Escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && mobileMenu.classList.contains('is-open')) {
                mobileMenu.classList.remove('is-open');
                navToggle.setAttribute('aria-expanded', 'false');
                mobileMenu.setAttribute('aria-hidden', 'true');
                navToggle.focus();
            }
        });
    }

    /* ── Dropdown keyboard nav ─────────────────────────────────────────────── */
    document.querySelectorAll('.cafe-navbar__dropdown-toggle').forEach((btn) => {
        btn.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                const expanded = btn.getAttribute('aria-expanded') === 'true';
                btn.setAttribute('aria-expanded', String(!expanded));
            }
        });
    });

    /* ── Auto-dismiss alerts ────────────────────────────────────────────────── */
    document.querySelectorAll('.cafe-alert').forEach((alert) => {
        // Close button
        const closeBtn = alert.querySelector('.cafe-alert__close');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => {
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-4px)';
                alert.style.transition = 'all 0.3s ease';
                setTimeout(() => alert.remove(), 300);
            });
        }
        // Auto-dismiss after 6 seconds
        setTimeout(() => {
            if (document.body.contains(alert)) {
                alert.style.opacity = '0';
                alert.style.transition = 'opacity 0.4s ease';
                setTimeout(() => alert.remove(), 400);
            }
        }, 6000);
    });

    /* ── Smooth scroll for anchor links ────────────────────────────────────── */
    document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
        anchor.addEventListener('click', (e) => {
            const targetId = anchor.getAttribute('href').slice(1);
            const target   = document.getElementById(targetId);
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                target.setAttribute('tabindex', '-1');
                target.focus({ preventScroll: true });
            }
        });
    });

    /* ── Lazy image fade-in ─────────────────────────────────────────────────── */
    if ('IntersectionObserver' in window) {
        const imgObserver = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    imgObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('img[loading="lazy"]').forEach((img) => {
            img.style.opacity = '0';
            img.style.transition = 'opacity 0.5s ease';
            if (img.complete) {
                img.style.opacity = '1';
            } else {
                img.addEventListener('load', () => { img.style.opacity = '1'; });
                imgObserver.observe(img);
            }
        });
    }

    /* ── Filter pills animation ─────────────────────────────────────────────── */
    document.querySelectorAll('.cafe-filter-pill').forEach((pill) => {
        pill.addEventListener('click', function () {
            document.querySelectorAll('.cafe-filter-pill').forEach((p) => p.classList.remove('cafe-filter-pill--active'));
            this.classList.add('cafe-filter-pill--active');
        });
    });

    /* ── Reading progress bar ────────────────────────────────────────────────
       (also defined inline in show.blade.php for reliability)               */
    const progress = document.getElementById('readingProgress');
    const article  = document.getElementById('article-content');
    if (progress && article && !progress.dataset.bound) {
        progress.dataset.bound = '1';
        const updateProgress = () => {
            const rect  = article.getBoundingClientRect();
            const total = article.offsetHeight - window.innerHeight;
            const done  = Math.max(0, -rect.top);
            progress.style.width = Math.min(100, (done / total) * 100) + '%';
        };
        window.addEventListener('scroll', updateProgress, { passive: true });
    }

    /* ── Search: clear button ────────────────────────────────────────────────  */
    document.querySelectorAll('input[type="search"]').forEach((input) => {
        input.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                input.value = '';
                input.dispatchEvent(new Event('input'));
            }
        });
    });

    /* ── Copy link button (if present) ─────────────────────────────────────── */
    const copyLinkBtn = document.getElementById('copyLink');
    if (copyLinkBtn) {
        copyLinkBtn.addEventListener('click', async () => {
            try {
                await navigator.clipboard.writeText(window.location.href);
                const original = copyLinkBtn.textContent;
                copyLinkBtn.textContent = '✓ Copié !';
                setTimeout(() => { copyLinkBtn.textContent = original; }, 2000);
            } catch (_) {
                // Clipboard API not available — fail silently
            }
        });
    }

})();
