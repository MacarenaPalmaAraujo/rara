    <!-- Navegación principal: marquesina, header, menú de cuenta y menú lateral -->
    <!-- Marquesina superior del puesto de feria -->
    <div class="h-3 market-canopy w-full"></div>

    <!-- Header / Barra de Navegación -->
    <header class="sticky top-0 z-40 bg-feria-night/95 backdrop-blur border-b border-white/10 px-4 sm:px-8 py-3 flex items-center justify-between shadow-xl">
        <div class="flex items-center gap-6">
            <a href="#" onclick="showSection('home')" class="flex items-center gap-2.5 group">
                <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-amber-400 to-orange-600 flex items-center justify-center text-slate-950 font-extrabold text-xl shadow-lg shadow-orange-500/30 group-hover:rotate-6 transition-transform">
                    <i class="fa-solid fa-store"></i>
                </div>
                <div>
                    <span class="font-display font-black text-2xl tracking-tight text-white group-hover:text-amber-400 transition-colors">Feriando</span>
                    <span class="block text-[10px] text-amber-300 font-semibold uppercase tracking-widest -mt-1">Paseo de Ferias</span>
                </div>
            </a>

            <nav id="mainNav" class="hidden md:flex items-center gap-2 text-sm font-semibold">
                <a href="#calendario" onclick="showSection('home')" class="px-3.5 py-2 rounded-xl text-slate-200 hover:text-white hover:bg-white/10 transition-all flex items-center gap-1.5">
                    <i class="fa-regular fa-calendar-check text-amber-400"></i> Calendario
                </a>
                <a href="#meses" onclick="showSection('home')" class="px-3.5 py-2 rounded-xl text-slate-200 hover:text-white hover:bg-white/10 transition-all flex items-center gap-1.5">
                    <i class="fa-solid fa-layer-group text-orange-400"></i> Meses
                </a>
                <a href="#conocenos" onclick="openModal('modalConocenos')" class="px-3.5 py-2 rounded-xl text-slate-200 hover:text-white hover:bg-white/10 transition-all flex items-center gap-1.5">
                    <i class="fa-solid fa-users text-yellow-400"></i> Conocenos
                </a>
            </nav>
        </div>

        <div class="flex items-center gap-3">
            <!-- Botón Dinámico de Autenticación -->
            <div class="relative">
                <button id="mainAuthBtn" onclick="handleAuthButtonClick()" class="bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-400 hover:to-orange-400 text-slate-950 font-extrabold px-4 py-2 rounded-xl text-xs sm:text-sm transition-all shadow-lg flex items-center gap-2">
                    <i id="mainAuthIcon" class="fa-solid fa-user-plus text-sm"></i>
                    <span id="mainAuthText">Registrarse / Iniciar Sesión</span>
                </button>

                <!-- Menú de cuenta: solo se muestra cuando hay sesión iniciada -->
                <div id="authMenu" class="absolute right-0 top-full mt-2 w-60 bg-slate-900 border border-white/10 rounded-2xl shadow-2xl p-3 hidden z-50">
                    <p class="text-[10px] text-slate-400 uppercase tracking-wider font-bold px-2">Sesión iniciada como</p>
                    <p id="authMenuUserName" class="text-sm font-bold text-white px-2 truncate">Invitado</p>
                    <button onclick="closeSession()" class="w-full mt-3 px-3 py-2 rounded-xl bg-white/5 hover:bg-orange-600 text-white text-xs font-bold flex items-center gap-2 transition-colors">
                        <i class="fa-solid fa-right-from-bracket text-amber-400"></i> Cerrar Sesión
                    </button>
                </div>
            </div>

            <!-- Botón de Menú Ajustes Lateral -->
            <button onclick="toggleDrawer()" class="w-10 h-10 rounded-xl bg-white/10 hover:bg-white/20 border border-white/15 flex items-center justify-center text-amber-400 transition-all" title="Menú Lateral">
                <i class="fa-solid fa-gear text-lg"></i>
            </button>
        </div>
    </header>

    <!-- Menú Deslizable (Side Drawer) -->
    <div id="drawerBackdrop" onclick="toggleDrawer()" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-50 opacity-0 pointer-events-none transition-opacity duration-300"></div>
    <aside id="sideDrawer" class="fixed top-0 right-0 bottom-0 w-80 bg-slate-900 border-l border-white/10 z-50 transform translate-x-full drawer-slide p-6 flex flex-col justify-between overflow-y-auto">
        <div>
            <div class="flex items-center justify-between pb-4 border-b border-white/10 mb-6">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-bars-staggered text-amber-400 text-xl"></i>
                    <h3 class="font-display font-bold text-lg text-white">Menú Feriando</h3>
                </div>
                <button onclick="toggleDrawer()" class="w-8 h-8 rounded-full bg-white/10 text-slate-300 hover:text-white flex items-center justify-center">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="space-y-4">
                <div id="drawerProfileBox" onclick="handleDrawerProfileClick()" role="button" tabindex="0" onkeydown="if(event.key==='Enter'||event.key===' '){event.preventDefault();handleDrawerProfileClick();}" class="p-4 rounded-2xl bg-white/5 border border-white/10 flex items-center gap-3 cursor-pointer hover:bg-white/10 transition-colors" title="Iniciar sesión">
                    <div id="drawerAvatar" class="w-12 h-12 rounded-full bg-slate-800 font-extrabold text-amber-400 flex items-center justify-center text-lg border-2 border-amber-400/40 shrink-0">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <div class="overflow-hidden">
                        <p id="drawerUserName" class="font-bold text-sm text-white truncate">Invitado</p>
                        <span id="drawerUserRole" class="inline-block px-2 py-0.5 rounded text-[10px] bg-amber-500/20 text-amber-300 font-bold border border-amber-500/40 underline">Sin sesión iniciada · Iniciar sesión</span>
                    </div>
                </div>

                <div id="drawerQuickAccess" class="pt-2 space-y-2">
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider px-2">Acceso Rápido</p>
                    <button onclick="showSection('home'); toggleDrawer();" class="w-full text-left px-4 py-3 rounded-xl bg-white/5 hover:bg-orange-600 text-white font-semibold text-sm flex items-center gap-3 transition-colors">
                        <i class="fa-solid fa-house text-amber-400"></i> Inicio / Calendario
                    </button>
                    <button id="drawerFavoritosBtn" onclick="openFavoritosModal(); toggleDrawer();" class="hidden w-full text-left px-4 py-3 rounded-xl bg-white/5 hover:bg-orange-600 text-white font-semibold text-sm flex items-center gap-3 transition-colors">
                        <i class="fa-solid fa-star text-amber-400"></i> Mis Favoritos
                    </button>
                    <button onclick="showAvisoForm(); toggleDrawer();" class="w-full text-left px-4 py-3 rounded-xl bg-white/5 hover:bg-orange-600 text-white font-semibold text-sm flex items-center gap-3 transition-colors">
                        <i class="fa-solid fa-bullhorn text-amber-400"></i> Avisar en qué feria estoy
                    </button>
                    <button onclick="openModal('authModal'); toggleDrawer();" class="w-full text-left px-4 py-3 rounded-xl bg-white/5 hover:bg-orange-600 text-white font-semibold text-sm flex items-center gap-3 transition-colors">
                        <i class="fa-solid fa-user-plus text-amber-400"></i> Autenticación / Registro
                    </button>
                    <button onclick="showEmprendedorRegistration(); toggleDrawer();" class="w-full text-left px-4 py-3 rounded-xl bg-white/5 hover:bg-orange-600 text-white font-semibold text-sm flex items-center gap-3 transition-colors">
                        <i class="fa-solid fa-store text-amber-400"></i> Formulario Emprendedores
                    </button>
                    <button onclick="showFairRegistration(); toggleDrawer();" class="w-full text-left px-4 py-3 rounded-xl bg-white/5 hover:bg-orange-600 text-white font-semibold text-sm flex items-center gap-3 transition-colors">
                        <i class="fa-solid fa-flag text-amber-400"></i> Registrar Nueva Feria
                    </button>
                </div>
            </div>
        </div>

        <div class="pt-6 border-t border-white/10 text-xs text-slate-400 text-center">
            <p class="font-bold text-slate-300">Feriando App PHP Edition</p>
            <p class="text-[10px] mt-1 text-amber-400 font-medium"><?php echo $institutionCopyright; ?></p>
        </div>
    </aside>
