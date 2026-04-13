<div id="sidebarBackdrop" class="fixed inset-0 bg-slate-900/50 z-30 hidden md:hidden transition-opacity opacity-0"></div>

<aside id="sidebar" class="fixed inset-y-0 left-0 transform -translate-x-full md:relative md:translate-x-0 w-64 bg-slate-900 text-white flex flex-col h-full shadow-2xl md:shadow-xl z-40 transition-transform duration-300 ease-in-out shrink-0">
    
    <div class="h-32 relative flex items-center justify-center bg-slate-950 border-b border-slate-800">
        
        <a href="<?= URL_BASE ?>dashboard" class="flex items-center justify-center w-full h-full p-4">
            <img src="<?= URL_BASE ?>public/img/logo-cc.png" alt="clinic.cloud" class="h-full w-auto object-contain drop-shadow-md">
        </a>
        
        <button id="closeSidebarBtn" class="md:hidden absolute top-4 right-4 text-slate-400 hover:text-white p-2 rounded-lg hover:bg-slate-800 transition-colors">
            <i data-lucide="x" class="w-6 h-6"></i>
        </button>
    </div>
    
    <?php
    // 1. Obtenemos en qué módulo estamos leyendo la URL
    $url_parts = explode('/', isset($_GET['url']) ? trim($_GET['url'], '/') : '');
    $current_module = !empty($url_parts[0]) ? $url_parts[0] : 'dashboard';

    // 2. Función para el fondo del botón
    function navClass($module, $current_module) {
        if ($module === $current_module) {
            return 'flex items-center px-4 py-3 bg-blue-600 text-white rounded-lg font-semibold shadow-sm';
        }
        return 'flex items-center px-4 py-2.5 text-slate-300 hover:bg-slate-800 hover:text-white rounded-lg transition-all group';
    }

    // 3. Función para el color del ícono
    function iconClass($module, $current_module) {
        if ($module === $current_module) {
            return 'w-5 h-5 mr-3 text-white';
        }
        return 'w-5 h-5 mr-3 text-slate-400 group-hover:text-blue-400';
    }
    ?>

    <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
        
        <a href="<?= URL_BASE ?>dashboard" class="<?= navClass('dashboard', $current_module) ?>">
            <i data-lucide="layout-dashboard" class="<?= iconClass('dashboard', $current_module) ?>"></i> Dashboard
        </a>

        <?php if(hasPermission('view_calendar') || hasPermission('view_patients') || hasPermission('manage_consultations')): ?>
        <div class="pt-2 pb-1">
            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest px-4">Área Médica</p>
        </div>
        <?php endif; ?>

        <?php if(hasPermission('view_calendar')): ?>
        <a href="<?= URL_BASE ?>agenda" class="<?= navClass('agenda', $current_module) ?>">
            <i data-lucide="calendar" class="<?= iconClass('agenda', $current_module) ?>"></i> Agenda
        </a>
        <?php endif; ?>

        <?php if(hasPermission('view_patients')): ?>
        <a href="<?= URL_BASE ?>pacientes" class="<?= navClass('pacientes', $current_module) ?>">
            <i data-lucide="users" class="<?= iconClass('pacientes', $current_module) ?>"></i> Pacientes
        </a>
        <?php endif; ?>

        <?php if(hasPermission('manage_consultations')): ?>
        <a href="<?= URL_BASE ?>consultas" class="<?= navClass('consultas', $current_module) ?>">
            <i data-lucide="stethoscope" class="<?= iconClass('consultas', $current_module) ?>"></i> Consultas
        </a>
        <?php endif; ?>

        <?php if(hasPermission('manage_quotes') || hasPermission('manage_inventory') || hasPermission('view_finances')): ?>
        <div class="pt-4 pb-1">
            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest px-4">Administración</p>
        </div>
        <?php endif; ?>

        <?php if(hasPermission('manage_quotes')): ?>
        <a href="<?= URL_BASE ?>cotizaciones" class="<?= navClass('cotizaciones', $current_module) ?>">
            <i data-lucide="file-text" class="<?= iconClass('cotizaciones', $current_module) ?>"></i> Cotizaciones
        </a>
        <?php endif; ?>

        <?php if(hasPermission('manage_inventory')): ?>
        <a href="<?= URL_BASE ?>inventario" class="<?= navClass('inventario', $current_module) ?>">
            <i data-lucide="package" class="<?= iconClass('inventario', $current_module) ?>"></i> Inventario
        </a>
        <?php endif; ?>

        <?php if(hasPermission('view_finances')): ?>
        <a href="<?= URL_BASE ?>finanzas" class="<?= navClass('finanzas', $current_module) ?>">
            <i data-lucide="wallet" class="<?= iconClass('finanzas', $current_module) ?>"></i> Finanzas
        </a>
        <?php endif; ?>

        <?php if(hasPermission('manage_services') || hasPermission('manage_users')): ?>
        <div class="pt-4 pb-1">
            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest px-4">Configuración</p>
        </div>
        <?php endif; ?>

        <?php if(hasPermission('manage_clinic')): ?>
        <a href="<?= URL_BASE ?>configuracion-clinica" class="<?= navClass('configuracion-clinica', $current_module) ?>">
            <i data-lucide="building-2" class="<?= iconClass('configuracion-clinica', $current_module) ?>"></i> 
            Configuración Clínica
        </a>
        <?php endif; ?>

        <?php if(hasPermission('manage_services')): ?>
        <a href="<?= URL_BASE ?>servicios" class="<?= navClass('servicios', $current_module) ?>">
            <i data-lucide="cog" class="<?= iconClass('servicios', $current_module) ?>"></i> Servicios
        </a>
        <?php endif; ?>

        <?php if(hasPermission('manage_users')): ?>
        <a href="<?= URL_BASE ?>equipo" class="<?= navClass('equipo', $current_module) ?>">
            <i data-lucide="user-cog" class="<?= iconClass('equipo', $current_module) ?>"></i> Equipo y Roles
        </a>
        <?php endif; ?>
        
    </nav>

    <div class="p-4 border-t border-slate-800 bg-slate-950">
        <button type="button" onclick="confirmation_page_change('<?= URL_BASE ?>', 'logout', '<?= __('logout_title') ?>', '<?= __('logout_confirm') ?>')" class="flex items-center px-4 py-3 w-full text-left text-slate-400 hover:text-red-400 hover:bg-slate-900 rounded-lg transition-colors cursor-pointer">
            <i data-lucide="log-out" class="w-5 h-5 mr-3"></i> <?= __('logout_title') ?>
        </button>
    </div>
</aside>