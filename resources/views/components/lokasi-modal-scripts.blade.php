@push('scripts')
<style>
/* Reset default modal animations */
.modal {
    animation: none !important;
    transition: none !important;
}

.modal-dialog {
    transform: none !important;
    transition: none !important;
}

.modal-backdrop {
    animation: none !important;
    transition: none !important;
}

/* Clean modal styling */
.modal {
    padding-right: 0 !important;
}

.modal-content {
    transform: translateZ(0);
    backface-visibility: hidden;
    will-change: transform;
}

/* Prevent multiple backdrops */
.modal-backdrop + .modal-backdrop {
    display: none !important;
}

/* Prevent body shift */
body.modal-open {
    padding-right: 0 !important;
    overflow-y: hidden !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Cache DOM elements
    const createModalEl = document.getElementById('createLokasiModal');
    const editModalEl = document.getElementById('editLokasiModal');
    const deleteModalEl = document.getElementById('deleteLokasiModal');
    
    // Initialize modals with specific options
    const modalOptions = {
        backdrop: 'static',
        keyboard: false
    };

    const createModal = new bootstrap.Modal(createModalEl, modalOptions);
    const editModal = new bootstrap.Modal(editModalEl, modalOptions);
    const deleteModal = new bootstrap.Modal(deleteModalEl, modalOptions);

    // Clean up function
    function cleanupModals() {
        // Hide all modals
        [createModal, editModal, deleteModal].forEach(modal => {
            try {
                modal.hide();
            } catch (e) {}
        });

        // Remove backdrops
        document.querySelectorAll('.modal-backdrop').forEach(backdrop => backdrop.remove());
        
        // Reset body styles
        document.body.classList.remove('modal-open');
        document.body.style.removeProperty('padding-right');
        document.body.style.removeProperty('overflow');
    }

    // Handle Modal Functions
    window.openCreateLokasi = function() {
        cleanupModals();
        requestAnimationFrame(() => {
            const form = createModalEl.querySelector('form');
            if (form) form.reset();
            createModal.show();
        });
    };
    
    window.openEditLokasi = function(id, nama) {
        cleanupModals();
        requestAnimationFrame(() => {
            const form = document.getElementById('editLokasiForm');
            if (form) {
                form.action = `{{ route('lokasi.index') }}/${id}`;
                const namaInput = form.querySelector('#edit_nama_lokasi');
                if (namaInput) namaInput.value = nama;
            }
            editModal.show();
        });
    };
    
    window.openDeleteLokasi = function(id, nama) {
        cleanupModals();
        requestAnimationFrame(() => {
            const form = document.getElementById('deleteLokasiForm');
            if (form) {
                form.action = `{{ route('lokasi.index') }}/${id}`;
            }
            document.getElementById('deleteLokasiName').textContent = `"${nama}"`;
            deleteModal.show();
        });
    };

    // Handle Form Submissions
    document.querySelectorAll('.modal form').forEach(form => {
        form.addEventListener('submit', function() {
            // Disable submit button to prevent double submission
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) submitBtn.disabled = true;
        });
    });

    // Modal Event Handlers
    [createModalEl, editModalEl, deleteModalEl].forEach(modal => {
        // Clear existing listeners
        const newModal = modal.cloneNode(true);
        modal.parentNode.replaceChild(newModal, modal);
        
        newModal.addEventListener('show.bs.modal', function(e) {
            cleanupModals();
        });

        newModal.addEventListener('shown.bs.modal', function() {
            // Ensure single backdrop
            const backdrops = document.querySelectorAll('.modal-backdrop');
            backdrops.forEach((backdrop, index) => {
                if (index > 0) backdrop.remove();
            });

            // Focus first input
            const firstInput = this.querySelector('input:not([type=hidden])');
            if (firstInput) firstInput.focus();
        });

        newModal.addEventListener('hide.bs.modal', function() {
            this.style.transition = 'none';
        });

        newModal.addEventListener('hidden.bs.modal', function() {
            // Clean up
            const form = this.querySelector('form');
            if (form) {
                form.reset();
                const submitBtn = form.querySelector('button[type="submit"]');
                if (submitBtn) submitBtn.disabled = false;
            }
            cleanupModals();
        });
    });

    // Add cleanup on page events
    window.addEventListener('pageshow', cleanupModals);
    document.addEventListener('visibilitychange', () => {
        if (document.visibilityState === 'visible') {
            cleanupModals();
        }
    });

    // Handle back button
    window.addEventListener('popstate', cleanupModals);
});
</script>
@endpush
