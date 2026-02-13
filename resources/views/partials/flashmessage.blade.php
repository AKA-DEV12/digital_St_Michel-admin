@if (session()->has('success') || session()->has('error') || session()->has('warning') || session()->has('info') || $errors->any())
    <div id="flash-message-container" class="position-fixed bottom-0 end-0 p-4" style="z-index: 9999;">
        @php
            $type = 'info';
            $icon = 'info-circle';
            $message = '';
            
            if (session()->has('success')) {
                $type = 'success';
                $icon = 'check-circle';
                $message = session('success');
            } elseif (session()->has('error') || $errors->any()) {
                $type = 'danger';
                $icon = 'exclamation-circle';
                $message = session('error') ?? $errors->first();
            } elseif (session()->has('warning')) {
                $type = 'warning';
                $icon = 'exclamation-triangle';
                $message = session('warning');
            } elseif (session()->has('info')) {
                $type = 'primary';
                $icon = 'info-circle';
                $message = session('info');
            }
        @endphp

        <div class="toast show align-items-center text-white bg-{{ $type }} border-0 shadow-lg animate-slide-in" role="alert" aria-live="assertive" aria-atomic="true" id="liveToast">
            <div class="d-flex p-3">
                <div class="me-3 fs-4">
                    <i class="fa-solid fa-{{ $icon }}"></i>
                </div>
                <div class="toast-body grow">
                    <h6 class="mb-1 fw-bold">{{ ucfirst($type == 'danger' ? 'Erreur' : ($type == 'primary' ? 'Information' : $type)) }}</h6>
                    <div class="small opacity-90">{{ $message }}</div>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-progress-bar"></div>
        </div>
    </div>

    <style>
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes fadeOut {
            from { opacity: 1; transform: translateY(0); }
            to { opacity: 0; transform: translateY(10px); }
        }
        @keyframes progress {
            from { width: 100%; }
            to { width: 0%; }
        }
        .animate-slide-in {
            animation: slideIn 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
        }
        .animate-fade-out {
            animation: fadeOut 0.5s ease-in forwards;
        }
        .toast-progress-bar {
            height: 3px;
            background: rgba(255, 255, 255, 0.3);
            width: 100%;
            position: absolute;
            bottom: 0;
            left: 0;
            animation: progress 7s linear forwards;
        }
        .toast {
            min-width: 320px;
            border-radius: 12px;
            overflow: hidden;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toast = document.getElementById('liveToast');
            if (toast) {
                setTimeout(() => {
                    toast.classList.add('animate-fade-out');
                    setTimeout(() => {
                        const container = document.getElementById('flash-message-container');
                        if (container) container.remove();
                    }, 500);
                }, 7000);
            }
        });
    </script>
@endif
