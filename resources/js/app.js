// ── Theme ──────────────────────────────────────────────────
const getTheme = () => localStorage.getItem('theme') || 'dark';

const applyTheme = (theme) => {
    document.documentElement.classList.remove('dark', 'light');
    document.documentElement.classList.add(theme);
    localStorage.setItem('theme', theme);
    const btn = document.getElementById('theme-toggle');
    if (!btn) return;
    const icon = document.getElementById('theme-icon');
    if (icon) {
        icon.className = theme === 'dark'
            ? 'fa-solid fa-sun text-sm'
            : 'fa-solid fa-moon text-sm';
    }
};

const initDeleteConfirmation = () => {
    const modal = document.getElementById('delete-confirm-modal');
    const message = document.getElementById('delete-confirm-message');
    const confirmButton = document.getElementById('delete-confirm-submit');
    const cancelButton = document.getElementById('delete-confirm-cancel');

    if (!modal || !message || !confirmButton || !cancelButton) {
        return;
    }

    const closeTargets = modal.querySelectorAll('[data-confirm-close]');
    let pendingForm = null;

    const closeModal = () => {
        modal.classList.remove('is-open');
        modal.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('modal-open');
        pendingForm = null;
    };

    const openModal = (form) => {
        pendingForm = form;
        const itemName = form.dataset.itemName || 'film ini';
        message.textContent = `Kamu yakin ingin menghapus "${itemName}" dari favorit?`;
        modal.classList.add('is-open');
        modal.setAttribute('aria-hidden', 'false');
        document.body.classList.add('modal-open');
        confirmButton.focus();
    };

    closeTargets.forEach((target) => {
        target.addEventListener('click', closeModal);
    });

    cancelButton.addEventListener('click', closeModal);

    confirmButton.addEventListener('click', () => {
        if (!pendingForm) {
            return;
        }

        pendingForm.dataset.confirmed = 'true';
        pendingForm.submit();
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && modal.classList.contains('is-open')) {
            closeModal();
        }
    });

    document.querySelectorAll('form[data-confirm-delete]').forEach((form) => {
        form.addEventListener('click', (event) => {
            event.stopPropagation();
        });

        form.addEventListener('submit', (event) => {
            if (form.dataset.confirmed === 'true') {
                form.dataset.confirmed = '';
                return;
            }

            event.preventDefault();
            openModal(form);
        });
    });
};

applyTheme(getTheme());

document.addEventListener('DOMContentLoaded', () => {
    applyTheme(getTheme());

    document.getElementById('theme-toggle')?.addEventListener('click', () => {
        applyTheme(getTheme() === 'dark' ? 'light' : 'dark');
    });

    const flash = document.getElementById('flash-msg');
    if (flash) {
        setTimeout(() => {
            flash.style.transition = 'opacity 0.45s ease, transform 0.45s ease';
            flash.style.opacity = '0';
            flash.style.transform = 'translateX(16px)';
            setTimeout(() => flash.remove(), 450);
        }, 3000);
    }

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.08 });

    document.querySelectorAll('.fade-section').forEach(el => observer.observe(el));

    initDeleteConfirmation();
});
