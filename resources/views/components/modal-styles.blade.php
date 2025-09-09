@push('styles')
<style>
/* Modal Base Styles */
.modal-open {
    overflow: hidden;
    padding-right: 0 !important;
}

.modal {
    position: fixed;
    top: 0;
    left: 0;
    z-index: 1055;
    display: none;
    width: 100%;
    height: 100%;
    overflow-x: hidden;
    overflow-y: auto;
    outline: 0;
    -webkit-overflow-scrolling: touch;
}

.modal-dialog {
    position: relative;
    width: auto;
    margin: 0.5rem;
    pointer-events: none;
}

.modal.fade .modal-dialog {
    transform: none !important;
}

.modal.show .modal-dialog {
    transform: none !important;
}

.modal-dialog-centered {
    display: flex;
    align-items: center;
    min-height: calc(100% - 1rem);
}

/* Backdrop Styles */
.modal-backdrop {
    position: fixed;
    top: 0;
    left: 0;
    z-index: 1050;
    width: 100vw;
    height: 100vh;
    background-color: rgba(0, 0, 0, 0.5);
}

.modal-backdrop.fade {
    opacity: 1 !important;
}

.modal-backdrop.show {
    opacity: 1 !important;
}

/* Content Styles */
.modal-content {
    position: relative;
    display: flex;
    flex-direction: column;
    width: 100%;
    pointer-events: auto;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid rgba(0, 0, 0, 0.2);
    border-radius: 0.5rem;
    outline: 0;
}

/* Performance Optimizations */
.modal,
.modal-dialog,
.modal-content {
    transform: translateZ(0);
    backface-visibility: hidden;
    perspective: 1000px;
    will-change: transform;
}

/* Z-index Management */
.modal {
    z-index: 1055;
}
.modal-backdrop {
    z-index: 1050;
}

/* Prevent Multiple Backdrops */
.modal-backdrop + .modal-backdrop {
    display: none !important;
}

/* Clean Modal Look */
.modal-blur .modal-dialog {
    transform: translate3d(0, 0, 0);
}

.modal-blur .modal-content {
    border: none;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
}

/* Smooth Scrolling */
.modal-body {
    -webkit-overflow-scrolling: touch;
    overscroll-behavior: contain;
}

/* Form Element Optimization */
.modal input,
.modal select,
.modal textarea {
    transform: translateZ(0);
    backface-visibility: hidden;
}
</style>
@endpush
