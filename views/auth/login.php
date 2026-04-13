<?php include '../views/layouts/header.php'; ?>

<div class="min-h-screen w-full flex items-center justify-center p-6 bg-cover bg-center bg-no-repeat relative" 
     style="background-image: url('<?= URL_BASE ?>public/img/bg-login.jpg');">
    
    <div class="absolute inset-0 bg-blue-900/60 backdrop-blur-sm"></div>

    <div class="max-w-md w-full bg-white/95 rounded-3xl shadow-2xl border border-white/20 p-10 relative z-10 backdrop-blur-sm">
        
        <div class="text-center mb-10">
            <img src="<?= URL_BASE ?>public/img/logo-cc.png" alt="Icono clinic.cloud" class="h-128 mx-auto mb-6">
            
            <!-- <h1 class="text-3xl font-bold text-slate-950">clinic.cloud</h1> -->
            <p class="text-slate-600 mt-2"><?= __('login_subtitle') ?></p>
        </div>

        <form action="<?= URL_BASE ?>login" method="POST" class="space-y-6">
            <div>
                <label class="block text-sm font-semibold text-slate-800 mb-2"><?= __('login_clinic_code_label') ?></label>
                <div class="relative">
                    <i data-lucide="building-2" class="w-5 h-5 absolute left-4 top-3.5 text-slate-400"></i>
                    <input type="text" name="clinic_code" required placeholder="<?= __('login_clinic_code_placeholder') ?>"  value="<?= isset($_SESSION['last_clinic_code']) ? htmlspecialchars($_SESSION['last_clinic_code']) : '' ?>"
                        class="w-full pl-12 pr-4 py-3.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-800 mb-2"><?= __('login_email_label') ?></label>
                <div class="relative">
                    <i data-lucide="mail" class="w-5 h-5 absolute left-4 top-3.5 text-slate-400"></i>
                    <input type="email" name="email" required placeholder="<?= __('login_email_placeholder') ?>" value="<?= isset($_SESSION['last_email']) ? htmlspecialchars($_SESSION['last_email']) : '' ?>"
                           class="w-full pl-12 pr-4 py-3.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all text-slate-900 bg-white">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-800 mb-2"><?= __('login_password_label') ?></label>
                <div class="relative">
                    <input id="password" type="password" name="password" required placeholder="••••••••"
                           class="w-full pl-12 pr-4 py-3.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all text-slate-900 bg-white">
                    <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                        <i data-lucide="lock" class="w-5 h-5"></i>
                    </div>

                    <button type="button" onclick="togglePasswordVisibility()" id="togglePassword" 
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none">
                        <span id="eyeIconContainer">
                            <i data-lucide="eye" class="w-5 h-5"></i>
                        </span>
                    </button>
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" 
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-xl transition-colors shadow-lg shadow-blue-200 active:scale-[0.98] transform">
                    <?= __('login_btn') ?>
                </button>
            </div>

            <!-- <div class="pt-2">
                <label class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-xl transition-colors shadow-lg shadow-blue-200 active:scale-[0.98] transform"><?= __('login_forgot_password') ?></label>
            </div> -->
        </form>
        
        <p class="text-center text-xs text-slate-400 mt-12">
            © <?= date('Y') ?> Clinic.Cloud. Powered by KeyTech.
        </p>
    </div>
</div>

<?php include '../views/layouts/footer.php'; ?>
<?php 
// Una vez que el input ya se renderizó con el valor, limpiamos la sesión
if (isset($_SESSION['last_email'])) {
    unset($_SESSION['last_email']); 
}
if (isset($_SESSION['last_clinic_code'])) {
    unset($_SESSION['last_clinic_code']); 
}
?>