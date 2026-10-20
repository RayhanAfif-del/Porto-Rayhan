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

/* ---------- Scroll Spy (Navigasi aktif saat scroll) ---------- */
const navLinks = [...document.querySelectorAll('[data-nav-link]')];
const trackedIds = [...new Set(navLinks.map((a) => a.dataset.navLink))];
const sections = trackedIds.map((id) => document.getElementById(id)).filter(Boolean);

const setActive = (id) => {
    navLinks.forEach((a) => {
        const active = a.dataset.navLink === id;
        a.classList.toggle('active', active);
        a.classList.toggle('is-active', active);
        if (active) a.setAttribute('aria-current', 'true');
        else a.removeAttribute('aria-current');
    });
};

if ('IntersectionObserver' in window && sections.length) {
    const spy = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    setActive(entry.target.id);
                }
            });
        },
        { threshold: 0.4 },
    );
    sections.forEach((s) => spy.observe(s));

    // Inisialisasi awal saat posisi di paling atas
    if (window.scrollY < 120 && sections.length > 0) {
        setActive(sections[0].id);
    }
}

/* ---------- Scroll Progress Bar (0% - 100%) ---------- */
const progressBar = document.getElementById('scroll-progress');
const updateScrollProgress = () => {
    if (!progressBar) return;
    const scrollTotal = document.documentElement.scrollHeight - window.innerHeight;
    const progress = scrollTotal > 0 ? Math.min(1, Math.max(0, window.scrollY / scrollTotal)) : 0;
    progressBar.style.transform = `scaleX(${progress})`;
};
window.addEventListener('scroll', updateScrollProgress, { passive: true });
updateScrollProgress();

/* ---------- Reveal on Scroll & Staggering ---------- */
const revealEls = document.querySelectorAll('[data-reveal]');
if (!('IntersectionObserver' in window)) {
    revealEls.forEach((el) => el.classList.add('is-visible'));
} else {
    // 1. Terapkan data-delay eksplisit jika ada (misal: data-delay="100" -> 100ms)
    revealEls.forEach((el) => {
        const customDelay = el.getAttribute('data-delay');
        if (customDelay) {
            el.style.setProperty('--reveal-delay', `${customDelay}ms`);
        }
    });

    // 2. Otomatis stagger untuk elemen bertingkat dalam grid/list yang belum ada delay manual
    const parentGroups = new Map();
    revealEls.forEach((el) => {
        if (!el.getAttribute('data-delay') && !el.style.getPropertyValue('--reveal-delay')) {
            const parent = el.parentElement;
            if (parent) {
                if (!parentGroups.has(parent)) {
                    parentGroups.set(parent, []);
                }
                parentGroups.get(parent).push(el);
            }
        }
    });

    parentGroups.forEach((children) => {
        if (children.length > 1) {
            children.forEach((child, idx) => {
                const delay = Math.min((idx + 1) * 80, 400);
                child.style.setProperty('--reveal-delay', `${delay}ms`);
            });
        }
    });

    // 3. Observer pemicu animasi reveal saat elemen masuk ke viewport di tiap halaman/section
    const io = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                } else {
                    // Reset kelas saat elemen keluar dari layar agar animasi aktif kembali di tiap masuk halaman
                    entry.target.classList.remove('is-visible');
                }
            });
        },
        { threshold: 0.1, rootMargin: '0px 0px -20px 0px' },
    );
    revealEls.forEach((el) => io.observe(el));
}

/* ---------- Animated Counters on Scroll (Durasi: 1.5 detik, Easing: Ease-Out) ---------- */
const counterEls = document.querySelectorAll('[data-counter]');
if (counterEls.length && 'IntersectionObserver' in window) {
    const runCounter = (el) => {
        if (el.dataset.animating === 'true') return;
        el.dataset.animating = 'true';

        const rawVal = el.dataset.rawVal || (el.getAttribute('data-counter') === 'true'
            ? el.textContent.trim()
            : (el.getAttribute('data-counter') || el.textContent.trim()));

        el.dataset.rawVal = rawVal;

        const numMatch = rawVal.match(/\d+/);
        if (!numMatch) {
            el.dataset.animating = 'false';
            return;
        }

        const targetNum = parseInt(numMatch[0], 10);
        const prefix = rawVal.slice(0, numMatch.index);
        const suffix = rawVal.slice(numMatch.index + numMatch[0].length);
        const padLength = numMatch[0].startsWith('0') && numMatch[0].length > 1 ? numMatch[0].length : 0;

        const duration = 1500;
        const startTime = performance.now();

        const animate = (now) => {
            const elapsed = now - startTime;
            const progress = Math.min(elapsed / duration, 1);
            const ease = 1 - Math.pow(1 - progress, 3);
            const currentNum = Math.floor(ease * targetNum);

            const formattedNum = padLength > 0
                ? String(currentNum).padStart(padLength, '0')
                : String(currentNum);

            el.textContent = `${prefix}${formattedNum}${suffix}`;

            if (progress < 1) {
                requestAnimationFrame(animate);
            } else {
                const finalFormatted = padLength > 0
                    ? String(targetNum).padStart(padLength, '0')
                    : String(targetNum);
                el.textContent = `${prefix}${finalFormatted}${suffix}`;
                el.dataset.animating = 'false';
            }
        };
        requestAnimationFrame(animate);
    };

    const counterObserver = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    runCounter(entry.target);
                } else if (entry.boundingClientRect.top > window.innerHeight) {
                    entry.dataset.animating = 'false';
                }
            });
        },
        { threshold: 0.25 },
    );
    counterEls.forEach((el) => counterObserver.observe(el));
}

/* ---------- Parallax Subtle Effect (Maksimal Offset 30px) ---------- */
const parallaxEls = document.querySelectorAll('[data-parallax]');
if (parallaxEls.length) {
    let ticking = false;
    const updateParallax = () => {
        const scrollY = window.scrollY;
        if (scrollY < window.innerHeight * 1.5) {
            parallaxEls.forEach((el) => {
                const speed = parseFloat(el.dataset.parallax) || 0.15;
                // Offset dibatasi maksimal 30px sesuai spesifikasi
                const offset = Math.min(Math.max(scrollY * speed, -30), 30);
                el.style.transform = `translate3d(0, ${offset.toFixed(2)}px, 0)`;
            });
        }
        ticking = false;
    };

    window.addEventListener('scroll', () => {
        if (!ticking) {
            window.requestAnimationFrame(updateParallax);
            ticking = true;
        }
    }, { passive: true });
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
    window.scrollTo({ top: 0, behavior: 'smooth' });
});

/* ---------- Cegah kirim ganda formulir kontak ---------- */
document.querySelector('[data-contact-form]')?.addEventListener('submit', (e) => {
    const btn = e.currentTarget.querySelector('button[type="submit"]');
    if (btn) {
        btn.disabled = true;
        btn.querySelector('[data-label]')?.replaceChildren('Mengirim…');
    }
});
