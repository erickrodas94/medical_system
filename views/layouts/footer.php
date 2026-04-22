    <script src="https://unpkg.com/lucide@0.473.0/dist/umd/lucide.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Monitor de Conexión
        function updateOnlineStatus() {
            const statusDisplay = document.getElementById('connection-status-toast');
            const submitButtons = document.querySelectorAll('button[type="submit"]');

            if (navigator.onLine) {
                // Hay internet
                if (statusDisplay) statusDisplay.classList.add('hidden');
                submitButtons.forEach(btn => btn.disabled = false);
            } else {
                // NO hay internet
                showOfflineAlert();
                submitButtons.forEach(btn => btn.disabled = true);
            }
        }

        function showOfflineAlert() {
            // Creamos un aviso visual si no existe
            let alertDiv = document.getElementById('connection-status-toast');
            if (!alertDiv) {
                alertDiv = document.createElement('div');
                alertDiv.id = 'connection-status-toast';
                alertDiv.className = "fixed bottom-5 left-1/2 -translate-x-1/2 z-[100] bg-rose-600 text-white px-6 py-3 rounded-2xl shadow-2xl flex items-center gap-3 animate-bounce";
                alertDiv.innerHTML = `
                    <i data-lucide="wifi-off"></i>
                    <span class="font-bold">Sin conexión a Internet. Guardado deshabilitado.</span>
                `;
                document.body.appendChild(alertDiv);
                lucide.createIcons(); // Re-renderizar iconos
            }
            alertDiv.classList.remove('hidden');
        }

        window.addEventListener('online', updateOnlineStatus);
        window.addEventListener('offline', updateOnlineStatus);

        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function(e) {
                if (!navigator.onLine) {
                    e.preventDefault();
                    alert("No puedes guardar cambios sin conexión a internet. Verifica tu red.");
                } else {
                    // Opcional: Mostrar un loader para que el usuario sepa que se está procesando
                    const btn = this.querySelector('button[type="submit"]');
                    if(btn) {
                        btn.innerHTML = '<i class="animate-spin mr-2" data-lucide="loader-2"></i> Procesando...';
                        btn.classList.add('opacity-70', 'cursor-not-allowed');
                        lucide.createIcons();
                    }
                }
            });
        });
    </script>

    <script>
        // Creamos un diccionario global de traducciones para JavaScript
        const i18n = {
            logout_title: '<?= __('logout_title') ?>',
            logout_confirm: '<?= __('logout_confirm') ?>',
            confirm_logout_btn: '<?= __('confirm_logout_btn') ?>',
            cancel_btn: '<?= __('cancel_btn') ?>'
        };
    </script>

    <script src="<?= URL_BASE ?>public/js/alerts.js"></script>
    <script src="<?= URL_BASE ?>public/js/usefullFunctions.js"></script>

    <script>// Inicializar iconos de Lucide
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
        <?php if (isset($_SESSION['message'])): 
            $msg = $_SESSION['message'];
            $text = (string)($msg['message'] ?? '');
            
            // Separamos el código ERR si existe
            $parts = explode(': ', $text, 2);
            
            if (count($parts) > 1) {
                $title = $parts[0]; // Ej: "ERR-001"
                $body  = $parts[1]; // El mensaje
            } else {
                $title = '';        // ¡Sin título inventado!
                $body  = $text;     // Aquí va tu __('all_fields_required') directo
            }
        ?>
            showAlert(
                <?= json_encode($msg['type']) ?>, 
                <?= json_encode($title) ?>, 
                <?= json_encode($body) ?>, 
                5000
            );
            console.log(<?= json_encode($msg) ?>);
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- 1. LÓGICA DEL SIDEBAR MÓVIL ---
            const sidebar = document.getElementById('sidebar');
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');
            const closeSidebarBtn = document.getElementById('closeSidebarBtn');
            const backdrop = document.getElementById('sidebarBackdrop');

            function toggleSidebar() {
                sidebar.classList.toggle('-translate-x-full');
                backdrop.classList.toggle('hidden');
                setTimeout(() => backdrop.classList.toggle('opacity-0'), 10); // Animación suave
            }

            if(mobileMenuBtn) mobileMenuBtn.addEventListener('click', toggleSidebar);
            if(closeSidebarBtn) closeSidebarBtn.addEventListener('click', toggleSidebar);
            if(backdrop) backdrop.addEventListener('click', toggleSidebar); // Cierra al hacer clic afuera


            // --- 2. LÓGICA DEL MENÚ DESPLEGABLE (AVATAR) ---
            const profileBtn = document.getElementById('profileDropdownBtn');
            const profileMenu = document.getElementById('profileDropdownMenu');

            if (profileBtn && profileMenu) {
                profileBtn.addEventListener('click', function(e) {
                    // 1. Evitamos que el clic "suba" al document
                    e.preventDefault();
                    e.stopPropagation(); 

                    const isHidden = profileMenu.classList.contains('hidden');

                    if (isHidden) {
                        // Abrir
                        profileMenu.classList.remove('hidden');
                        // Pequeño delay para que la animación de Tailwind entre suave
                        setTimeout(() => {
                            profileMenu.classList.remove('opacity-0', 'scale-95');
                            profileMenu.classList.add('opacity-100', 'scale-100');
                        }, 10);
                    } else {
                        // Cerrar
                        closeDropdown();
                    }
                });

                // Función para cerrar con animación
                function closeDropdown() {
                    profileMenu.classList.add('opacity-0', 'scale-95');
                    profileMenu.classList.remove('opacity-100', 'scale-100');
                    // Esperamos a que termine la transición (200ms) para poner hidden
                    setTimeout(() => {
                        profileMenu.classList.add('hidden');
                    }, 200);
                }

                // 2. Cerrar al hacer clic en cualquier otro lado
                document.addEventListener('click', function(e) {
                    if (!profileMenu.contains(e.target) && !profileBtn.contains(e.target)) {
                        if (!profileMenu.classList.contains('hidden')) {
                            closeDropdown();
                        }
                    }
                });
            }
        });
    </script>

</body>
</html>