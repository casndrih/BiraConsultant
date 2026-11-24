<script>
(() => {
    const instances = new WeakMap();

    const modalIsOpen = (modal) => modal && !modal.classList.contains('hidden') && getComputedStyle(modal).display !== 'none';

    const clearBackdropIfIdle = () => {
        const anotherModalIsOpen = Array.from(document.querySelectorAll('[aria-modal="true"]')).some(modalIsOpen);
        if (anotherModalIsOpen) return;

        document.querySelectorAll('[modal-backdrop]').forEach((backdrop) => backdrop.remove());
        document.body.classList.remove('overflow-hidden');
    };

    const hideModal = (modal) => {
        if (!modal) return;
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        modal.setAttribute('aria-hidden', 'true');
        modal.removeAttribute('aria-modal');
        clearBackdropIfIdle();
    };

    const showModal = (modal) => {
        if (!modal) return;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        modal.setAttribute('aria-hidden', 'false');
        modal.setAttribute('aria-modal', 'true');
        modal.setAttribute('role', 'dialog');
        document.body.classList.add('overflow-hidden');

        if (!document.querySelector('[modal-backdrop]')) {
            const backdrop = document.createElement('div');
            backdrop.setAttribute('modal-backdrop', '');
            backdrop.className = 'fixed inset-0 z-40 bg-gray-900/50 dark:bg-gray-900/80';
            document.body.appendChild(backdrop);
        }
    };

    class BiraConsultantModalFallback {
        constructor(element, options = {}) {
            this.element = typeof element === 'string' ? document.getElementById(element) : element;
            this.options = options;
            if (this.element) instances.set(this.element, this);
        }

        show() { showModal(this.element); }
        hide() { hideModal(this.element); }

        static getInstance(element) {
            const modal = typeof element === 'string' ? document.getElementById(element) : element;
            return modal ? instances.get(modal) || null : null;
        }
    }

    if (typeof window.Modal === 'undefined') window.Modal = BiraConsultantModalFallback;

    document.addEventListener('click', (event) => {
        if (!(event.target instanceof Element)) return;
        const control = event.target.closest('[data-modal-show], [data-modal-hide]');
        if (!control) return;

        const showId = control.getAttribute('data-modal-show') || control.getAttribute('data-modal-target');
        const hideId = control.getAttribute('data-modal-hide');

        // Flowbite handles these attributes when its bundle is available. This
        // deferred pass also covers pages where its handler was not initialized.
        window.setTimeout(() => {
            if (showId) showModal(document.getElementById(showId));
            if (hideId) hideModal(document.getElementById(hideId));
        }, 0);
    });
})();
</script>
