@if (session()->has('success') || session()->has('error') || session()->has('warning') || session()->has('info') || $errors->any())
    <div id="flash-message-container" class="position-fixed top-0 start-50 translate-middle-x p-4" style="z-index: 9999; margin-top: 1rem;">
        @php
            $type = 'info';
            $icon = 'circle-info';
            $message = '';
            $theme_color = 'var(--primary)';
            
            if (session()->has('success')) {
                $type = 'success';
                $icon = 'circle-check';
                $message = session('success');
                $theme_color = 'var(--success)';
            } elseif (session()->has('error') || $errors->any()) {
                $type = 'error';
                $icon = 'circle-exclamation';
                $message = session('error') ?? $errors->first();
                $theme_color = 'var(--danger)';
            } elseif (session()->has('warning')) {
                $type = 'warning';
                $icon = 'triangle-exclamation';
                $message = session('warning');
                $theme_color = 'var(--warning)';
            } elseif (session()->has('info')) {
                $type = 'info';
                $icon = 'circle-info';
                $message = session('info');
                $theme_color = 'var(--primary)';
            }
        @endphp

        <div class="toast-premium animate-bounce-in shadow-lg" role="alert">
            <div class="d-flex align-items-center p-3">
                <div class="toast-icon-box" style="background-color: {{ $theme_color }}20; color: {{ $theme_color }};">
                    <i class="fa-solid fa-{{ $icon }}"></i>
                </div>
                <div class="ms-3 me-4">
                    <div class="fw-bold text-dark" style="font-size: 0.95rem;">{{ ucfirst($type == 'error' ? 'Erreur' : $type) }}</div>
                    <div class="text-secondary small">{{ $message }}</div>
                </div>
                <button type="button" class="btn-close ms-auto" style="font-size: 0.7rem;" onclick="this.parentElement.parentElement.remove()"></button>
            </div>
            <div class="toast-progress" style="background-color: {{ $theme_color }};"></div>
        </div>
    </div>

    <style>
        .toast-premium {
            background: white;
            min-width: 350px;
            border-radius: 16px;
            overflow: hidden;
            position: relative;
            border: 1px solid var(--border-color);
        }
        .toast-icon-box {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }
        .toast-progress {
            height: 3px;
            width: 100%;
            position: absolute;
            bottom: 0;
            left: 0;
            animation: toast-progress 5s linear forwards;
        }
        @keyframes toast-progress {
            from { width: 100%; }
            to { width: 0%; }
        }
        @keyframes bounceIn {
            0% { opacity: 0; transform: translateY(-20px) scale(0.9); }
            70% { opacity: 1; transform: translateY(5px) scale(1.02); }
            100% { opacity: 1; transform: translateY(0) scale(1); }
        }
        .animate-bounce-in {
            animation: bounceIn 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
        }
        .animate-fade-out {
            opacity: 0;
            transform: translateY(-20px);
            transition: all 0.4s ease;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                const toast = document.querySelector('.toast-premium');
                if (toast) {
                    toast.classList.add('animate-fade-out');
                    setTimeout(() => {
                        const container = document.getElementById('flash-message-container');
                        if (container) container.remove();
                    }, 400);
                }
            }, 5000);
        });
    </script>
@endif
