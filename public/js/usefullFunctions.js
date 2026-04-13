function togglePasswordVisibility() {
    const passInput = document.getElementById('password');
    const iconContainer = document.getElementById('eyeIconContainer');

    // Validamos de forma rápida para evitar errores
    if (!passInput || !iconContainer) return;

    const isPassword = passInput.type === 'password';

    // Cambiamos el tipo de input
    passInput.type = isPassword ? 'text' : 'password';

    // Cambiamos el ícono
    iconContainer.innerHTML = isPassword
        ? '<i data-lucide="eye-off" class="w-5 h-5"></i>'
        : '<i data-lucide="eye" class="w-5 h-5"></i>';

    // Le decimos a Lucide que dibuje el nuevo ícono
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
}

// --- Lógica del Modal Generál ---
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (!modal) return;

    modal.classList.remove('hidden');
    // Pequeño retraso para que Tailwind procese la animación de opacidad y escala
    setTimeout(() => {
        modal.classList.remove('opacity-0');
        modal.children[1].classList.remove('scale-95'); // El contenedor blanco
    }, 10);
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (!modal) return;

    modal.classList.add('opacity-0');
    modal.children[1].classList.add('scale-95');

    // Esperamos a que termine la animación visual para ocultarlo del DOM
    setTimeout(() => {
        modal.classList.add('hidden');

        // Limpiar el formulario si lo cerramos (opcional, pero buena práctica)
        const form = modal.querySelector('form');
        if (form) form.reset();

        // Esconder el bloque del tutor si estaba abierto
        const tutorFields = document.getElementById('tutorFields');
        if (tutorFields) tutorFields.classList.add('hidden');

    }, 300);
}

// --- Lógica del "Interruptor Mágico" del Tutor ---
document.addEventListener('DOMContentLoaded', function () {
    const tutorToggle = document.getElementById('tutorToggle');
    const tutorFields = document.getElementById('tutorFields');

    // Inputs obligatorios del tutor
    const tutorFirstName = document.getElementById('tutorFirstName');
    const tutorLastName = document.getElementById('tutorLastName');

    if (tutorToggle && tutorFields) {
        tutorToggle.addEventListener('change', function () {
            if (this.checked) {
                // Mostrar campos
                tutorFields.classList.remove('hidden');
                // Hacer los campos obligatorios para que HTML5 valide
                tutorFirstName.required = true;
                tutorLastName.required = true;
            } else {
                // Ocultar campos
                tutorFields.classList.add('hidden');
                // Quitar obligatoriedad para no bloquear el guardado de un paciente normal
                tutorFirstName.required = false;
                tutorLastName.required = false;
            }
        });
    }
});