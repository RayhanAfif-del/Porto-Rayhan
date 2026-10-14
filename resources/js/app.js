const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

/* ---------- Navbar: background saat scroll ---------- */
const navbar = document.querySelector('[data-navbar]');
const onScroll = () => {
    if (!navbar) return;
    navbar.classList.toggle('is-scrolled', window.scrollY > 40);
    const toTop = document.querySelector('[data-scrolltop]');
    if (toTop) toTop.classList.toggle('opacity-0', window.scrollY < 600);
};
window.addEventListener('scroll', onScroll, { passive: true });
onScroll();

/* ---------- Menu mobile ---------- */
const menuButton = document.querySelector('[data-menu-button]');
const mobileMenu = document.querySelector('[data-mobile-menu]');

const setMenu = (open) => {
    if (!menuButton || !mobileMenu) return;
    menuButton.setAttribute('aria-expanded', String(open));
    menuButton.setAttribute('aria-label', open ? 'Tutup menu' : 'Buka menu');
    mobileMenu.classList.toggle('hidden', !open);
    document.body.classList.toggle('overflow-hidden', open);
    navbar?.classList.toggle('is-menu-open', open);
};

menuButton?.addEventListener('click', () => {
    setMenu(menuButton.getAttribute('aria-expanded') !== 'true');
});
mobileMenu?.querySelectorAll('a').forEach((a) => a.addEventListener('click', () => setMenu(false)));
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        setMenu(false);
        menuButton?.focus();
    }
});
window.matchMedia('(min-width: 1024px)').addEventListener('change', (e) => e.matches && setMenu(false));

/* ---------- Menu aktif mengikuti section ---------- */
const navLinks = [...document.querySelectorAll('[data-nav-link]')];
const trackedIds = [...new Set(navLinks.map((a) => a.dataset.navLink))];
const sections = trackedIds.map((id) => document.getElementById(id)).filter(Boolean);

const setActive = (id) => {
    navLinks.forEach((a) => {
        const active = a.dataset.navLink === id;
        a.classList.toggle('is-active', active);
        if (active) a.setAttribute('aria-current', 'true');
        else a.removeAttribute('aria-current');
    });
};

if ('IntersectionObserver' in window && sections.length) {
    const spy = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => entry.isIntersecting && setActive(entry.target.id));
        },
        { rootMargin: '-45% 0px -50% 0px' },
    );
    sections.forEach((s) => spy.observe(s));
}

/* ---------- Reveal on scroll ---------- */
const revealEls = document.querySelectorAll('[data-reveal]');
if (prefersReducedMotion || !('IntersectionObserver' in window)) {
    revealEls.forEach((el) => el.classList.add('is-visible'));
} else {
    const io = new IntersectionObserver(
        (entries, obs) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    obs.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.12, rootMargin: '0px 0px -40px 0px' },
    );
    revealEls.forEach((el) => io.observe(el));
}

/* ---------- Filter proyek ---------- */
const filterButtons = document.querySelectorAll('[data-filter]');
const projectItems = document.querySelectorAll('[data-category]');
const emptyState = document.querySelector('[data-filter-empty]');

filterButtons.forEach((btn) => {
    btn.addEventListener('click', () => {
        const value = btn.dataset.filter;
        filterButtons.forEach((b) => {
            const on = b === btn;
            b.setAttribute('aria-pressed', String(on));
            b.classList.toggle('bg-ink', on);
            b.classList.toggle('text-white', on);
            b.classList.toggle('border-ink', on);
            b.classList.toggle('text-muted', !on);
            b.classList.toggle('border-line', !on);
        });

        let visible = 0;
        projectItems.forEach((item) => {
            const show = value === 'all' || item.dataset.category === value;
            item.hidden = !show;
            if (show) {
                visible++;
                item.classList.remove('is-entering');
                void item.offsetWidth; // restart animasi
                item.classList.add('is-entering');
            }
        });
        emptyState?.classList.toggle('hidden', visible !== 0);
    });
});

/* ---------- Gambar gagal dimuat ---------- */
document.querySelectorAll('img[data-fallback]').forEach((img) => {
    img.addEventListener('error', () => {
        if (img.dataset.failed) return;
        img.dataset.failed = '1';
        img.src = img.dataset.fallback;
    });
});

/* ---------- Kembali ke atas ---------- */
document.querySelector('[data-scrolltop]')?.addEventListener('click', () => {
    window.scrollTo({ top: 0, behavior: prefersReducedMotion ? 'auto' : 'smooth' });
});

/* ---------- Cegah kirim ganda formulir kontak ---------- */
document.querySelector('[data-contact-form]')?.addEventListener('submit', (e) => {
    const btn = e.currentTarget.querySelector('button[type="submit"]');
    if (btn) {
        btn.disabled = true;
        btn.querySelector('[data-label]')?.replaceChildren('Mengirim…');
    }
});
