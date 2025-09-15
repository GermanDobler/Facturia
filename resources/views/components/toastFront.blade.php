@section('page-script')
    <script src="{{ asset('assets/js/ui-toasts.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('success'))
                showToast('success', 'Éxito', '{{ session('success') }}');
            @elseif (session('error'))
                showToast('error', 'Error', '{{ session('error') }}');
            @endif
        });

        function showToast(type, title, message) {
            const toastElement = document.getElementById('toastMessage');
            const toastIcon = document.getElementById('toastIcon');
            const toastTitle = document.getElementById('toastTitle');
            const toastBody = document.getElementById('toastBody');

            // Resetear clases previas
            toastElement.className = 'bs-toast toast fade show'; // Base class

            if (type === 'success') {
                toastElement.classList.add('bg-success'); // Clase verde
                toastIcon.className = 'bx bx-check-circle text-light me-2'; // Ícono verde
                toastTitle.textContent = title;
            } else if (type === 'error') {
                toastElement.classList.add('bg-danger'); // Clase roja
                toastIcon.className = 'bx bx-error-circle text-light me-2'; // Ícono rojo
                toastTitle.textContent = title;
            }

            // Actualizar el contenido del toast
            toastBody.textContent = message;

            // Mostrar el toast
            const bootstrapToast = new bootstrap.Toast(toastElement);
            bootstrapToast.show();
        }
    </script>
@endsection

<style>
    .bs-toast.toast {
        background-color: #71dd37 !important;
        /* Verde sólido */
        border: none;
        /* Sin bordes */
        opacity: 1 !important;
        /* Quita la transparencia */
    }

    .bs-toast.toast.bg-danger {
        background-color: #ff2600 !important;
        /* Rojo sólido */
    }

    .bs-toast .toast-header {
        background: transparent !important;
        /* Fondo de la cabecera */
        border-bottom: none;
        /* Sin bordes */
    }

    .bs-toast .text-white {
        color: #fff !important;
        /* Asegura el texto blanco */
    }

    .bs-toast .bx {
        color: #fff !important;
        /* Íconos blancos */
    }

    .btn-close {
        background-color: #ffffff !important;
    }

    .toast-body {
        color: #fff !important;
        /* Texto blanco */
    }
</style>

<div class="position-fixed top-0 end-0 p-3" style="z-index: 99999">
    <div id="toastMessage" class="bs-toast toast fade" role="alert" aria-live="assertive" aria-atomic="true"
        data-bs-delay="3000">

        <div class="toast-header">
            <i id="toastIcon" class="bx me-2"></i>
            <strong id="toastTitle" class="me-auto"></strong>
            <small class="text-white">Ahora</small>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Cerrar"></button>
        </div>
        <div id="toastBody" class="toast-body">
        </div>
    </div>
</div>
