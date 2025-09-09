<!-- Modern Toast Notification -->
<style>
.toast-notification {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 2000;
    visibility: hidden;
    min-width: 300px;
}

.toast-notification.show {
    visibility: visible;
    animation: slideIn 0.5s ease forwards, slideOut 0.5s ease forwards 2.5s;
}

.toast-content {
    background: rgba(255, 255, 255, 0.98);
    backdrop-filter: blur(10px);
    border-radius: 12px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
    padding: 16px;
    display: flex;
    align-items: center;
    gap: 12px;
    transform-origin: right;
}

.toast-icon {
    flex-shrink: 0;
    width: 48px;
    height: 48px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    animation: iconPop 0.5s ease forwards 0.3s;
    transform: scale(0);
}

.toast-icon i {
    font-size: 24px;
    color: white;
}

.toast-message {
    flex-grow: 1;
}

.toast-title {
    font-weight: 600;
    font-size: 1rem;
    margin: 0 0 4px;
}

.toast-text {
    color: #666;
    font-size: 0.9rem;
    margin: 0;
}

.toast-success .toast-icon { background: linear-gradient(45deg, #28a745, #34ce57); }
.toast-warning .toast-icon { background: linear-gradient(45deg, #ffc107, #ffdb4a); }
.toast-error .toast-icon { background: linear-gradient(45deg, #dc3545, #ff4d5f); }

@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes slideOut {
    from {
        transform: translateX(0);
        opacity: 1;
    }
    to {
        transform: translateX(100%);
        opacity: 0;
    }
}

@keyframes iconPop {
    from {
        transform: scale(0) rotate(-180deg);
    }
    to {
        transform: scale(1) rotate(0);
    }
}

.toast-progress {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 3px;
    background: rgba(0, 0, 0, 0.1);
    border-radius: 0 0 12px 12px;
    overflow: hidden;
}

.toast-progress::after {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    height: 100%;
    width: 100%;
    background: rgba(255, 255, 255, 0.3);
    animation: progress 3s linear forwards;
}

@keyframes progress {
    from { width: 100%; }
    to { width: 0%; }
}
</style>

<div class="toast-notification" id="notificationToast">
    <div class="toast-content">
        <div class="toast-icon">
            <i class="fas"></i>
        </div>
        <div class="toast-message">
            <h6 class="toast-title"></h6>
            <p class="toast-text"></p>
        </div>
        <div class="toast-progress"></div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentToast = null;
    let timeoutId = null;

    function showNotification(type, title, message) {
        // If there's a current toast, remove it first
        if (currentToast) {
            currentToast.classList.remove('show');
            clearTimeout(timeoutId);
        }

        const toast = document.getElementById('notificationToast');
        const toastIcon = toast.querySelector('.toast-icon i');
        const toastTitle = toast.querySelector('.toast-title');
        const toastText = toast.querySelector('.toast-text');
        
        // Reset classes
        toast.className = 'toast-notification';
        
        // Set content based on type
        switch(type) {
            case 'success':
                toast.classList.add('toast-success');
                toastIcon.className = 'fas fa-check';
                break;
            case 'warning':
                toast.classList.add('toast-warning');
                toastIcon.className = 'fas fa-exclamation';
                break;
            case 'error':
                toast.classList.add('toast-error');
                toastIcon.className = 'fas fa-times';
                break;
        }
        
        toastTitle.textContent = title;
        toastText.textContent = message;
        
        // Show toast with animation
        requestAnimationFrame(() => {
            toast.classList.add('show');
        });
        
        currentToast = toast;
        
        // Auto hide after animation completes
        timeoutId = setTimeout(() => {
            if (currentToast) {
                currentToast.classList.remove('show');
                currentToast = null;
            }
        }, 3000); // matches the animation duration
    }

    // Listen for session messages
    @if(session('success'))
        showNotification('success', 'Berhasil!', '{{ session('success') }}');
    @endif
    @if(session('warning'))
        showNotification('warning', 'Perhatian!', '{{ session('warning') }}');
    @endif
    @if(session('error'))
        showNotification('error', 'Gagal!', '{{ session('error') }}');
    @endif
});
</script>
@endpush
