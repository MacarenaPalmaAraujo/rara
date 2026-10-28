<?php
// Configuración básica de la aplicación PHP Feriando
$pageTitle = "Feriando - Agenda y Descubrimiento de Ferias";
$institutionCopyright = "E.T. N°26 \"Confederación Suiza\"";
?>
<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome Icons CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@600;700;800&display=swap" rel="stylesheet">
    
    <!-- Hoja de Estilos CSS Separada -->
    <link rel="stylesheet" href="style.css">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        display: ['Space Grotesk', 'sans-serif'],
                    },
                    colors: {
                        feria: {
                            night: '#1a2a48',
                            darkBlue: '#0f172a',
                            orange: '#ea580c',
                            brightOrange: '#f97316',
                            terracotta: '#c2410c',
                            amber: '#fbbf24',
                            lightBg: '#ffedd5',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="min-h-screen flex flex-col justify-between selection:bg-amber-400 selection:text-slate-900">

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
                <button onclick="showFairRegistration()" class="px-3.5 py-2 rounded-xl bg-orange-600 hover:bg-orange-500 text-white font-bold transition-all shadow flex items-center gap-1.5 text-xs">
                    <i class="fa-solid fa-bullhorn text-amber-300"></i> Avisar de Feria
                </button>
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
                <div id="drawerProfileBox" class="p-4 rounded-2xl bg-white/5 border border-white/10 flex items-center gap-3">
                    <div id="drawerAvatar" class="w-12 h-12 rounded-full bg-slate-800 font-extrabold text-amber-400 flex items-center justify-center text-lg border-2 border-amber-400/40 shrink-0">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <div class="overflow-hidden">
                        <p id="drawerUserName" class="font-bold text-sm text-white truncate">Invitado</p>
                        <span id="drawerUserRole" class="inline-block px-2 py-0.5 rounded text-[10px] bg-white/10 text-slate-300 font-bold border border-white/15">Sin sesión iniciada</span>
                    </div>
                </div>

                <div id="drawerQuickAccess" class="pt-2 space-y-2">
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider px-2">Acceso Rápido</p>
                    <button onclick="showSection('home'); toggleDrawer();" class="w-full text-left px-4 py-3 rounded-xl bg-white/5 hover:bg-orange-600 text-white font-semibold text-sm flex items-center gap-3 transition-colors">
                        <i class="fa-solid fa-house text-amber-400"></i> Inicio / Calendario
                    </button>
                    <button onclick="openModal('authModal'); toggleDrawer();" class="w-full text-left px-4 py-3 rounded-xl bg-white/5 hover:bg-orange-600 text-white font-semibold text-sm flex items-center gap-3 transition-colors">
                        <i class="fa-solid fa-user-plus text-amber-400"></i> Autenticación / Registro
                    </button>
                    <button onclick="showSection('pagina4'); toggleDrawer();" class="w-full text-left px-4 py-3 rounded-xl bg-white/5 hover:bg-orange-600 text-white font-semibold text-sm flex items-center gap-3 transition-colors">
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

    <!-- Contenido Principal -->
    <main id="sectionHome" class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-10">

        <!-- Banner Promocional Principal -->
        <section class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">
            <div class="lg:col-span-7 bg-gradient-to-br from-orange-600 via-orange-700 to-terracotta rounded-3xl p-6 sm:p-8 relative overflow-hidden shadow-2xl border-2 border-orange-400/40 flex flex-col justify-between">
                <div class="flex justify-between -mt-6 -mx-6 mb-4 opacity-90">
                    <span class="w-0 h-0 border-l-[15px] border-l-transparent border-r-[15px] border-r-transparent border-t-[25px] border-t-amber-300"></span>
                    <span class="w-0 h-0 border-l-[15px] border-l-transparent border-r-[15px] border-r-transparent border-t-[25px] border-t-yellow-400"></span>
                    <span class="w-0 h-0 border-l-[15px] border-l-transparent border-r-[15px] border-r-transparent border-t-[25px] border-t-white"></span>
                    <span class="w-0 h-0 border-l-[15px] border-l-transparent border-r-[15px] border-r-transparent border-t-[25px] border-t-amber-300"></span>
                    <span class="w-0 h-0 border-l-[15px] border-l-transparent border-r-[15px] border-r-transparent border-t-[25px] border-t-yellow-400"></span>
                </div>

                <div class="space-y-4 relative z-10">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-950/40 text-amber-300 text-xs font-bold border border-amber-300/30">
                        <i class="fa-solid fa-circle-check text-emerald-400"></i> Ferias Urbanas & Artesanales
                    </span>

                    <h1 class="font-display text-3xl sm:text-4xl font-black text-white leading-tight">
                        Descubrí ferias cerca de vos, organizá tu tiempo, conectá con emprendedores.
                    </h1>
                </div>

                <div class="pt-6 flex flex-wrap gap-3 relative z-10">
                    <button onclick="showSection('pagina4')" class="bg-slate-900 hover:bg-slate-800 text-white font-bold px-5 py-2.5 rounded-2xl text-xs sm:text-sm transition-all shadow-lg flex items-center gap-2">
                        <i class="fa-solid fa-store text-amber-400"></i> ¿Sos Emprendedor? Sumate
                    </button>
                    <button onclick="showFairRegistration()" class="bg-amber-400 hover:bg-amber-300 text-slate-950 font-extrabold px-5 py-2.5 rounded-2xl text-xs sm:text-sm transition-all shadow-lg flex items-center gap-2">
                        <i class="fa-solid fa-calendar-plus"></i> Registrar Nueva Feria
                    </button>
                </div>
            </div>

            <div class="lg:col-span-5 bg-gradient-to-br from-terracotta to-orange-800 rounded-3xl p-6 sm:p-8 relative overflow-hidden shadow-2xl border-2 border-orange-500/30 flex flex-col justify-between text-center">
                <div class="absolute -top-6 -right-6 w-24 h-24 bg-feria-night text-amber-400 font-black text-xs flex items-center justify-center p-2 starburst shadow-xl rotate-12">
                    <span class="rotate-[-12deg]">¡NUEVO!</span>
                </div>

                <div>
                    <h2 class="font-display text-3xl font-black tracking-tight text-white mb-2">Feriando</h2>
                    <p class="text-amber-200 text-sm font-semibold italic">"Feria que ves, feria que vas"</p>
                </div>

                <div class="my-4 bg-slate-900/60 rounded-2xl p-4 border border-white/10 backdrop-blur flex items-center justify-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-amber-400 text-slate-950 font-black text-2xl flex items-center justify-center shadow-lg shrink-0">
                        <i class="fa-solid fa-tent"></i>
                    </div>
                    <div class="text-left">
                        <p class="font-bold text-white text-sm">Agenda de Ferias 2026</p>
                        <p class="text-xs text-amber-300">No te enteres tarde, enterate acá.</p>
                    </div>
                </div>

                <div class="text-xs text-white/80 font-medium">
                    <i class="fa-solid fa-location-dot text-amber-400 mr-1"></i> Buenos Aires & Alrededores
                </div>
            </div>
        </section>

        <!-- Calendario Principal -->
        <section id="calendario" class="bg-slate-900/90 rounded-3xl border border-white/10 p-5 sm:p-8 shadow-2xl space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <span class="text-xs font-bold text-amber-400 uppercase tracking-widest">Calendario de Eventos</span>
                    <h2 id="calendarMonthTitle" class="font-display text-2xl sm:text-3xl font-black text-white">Mayo 2026</h2>
                </div>

                <div class="flex items-center gap-3">
                    <button type="button" onclick="navigateCalendarMonth(-1)" aria-label="Mes anterior" title="Mes anterior" class="w-11 h-11 rounded-2xl bg-white/10 hover:bg-orange-600 border border-white/15 hover:border-orange-400/50 text-amber-400 hover:text-white flex items-center justify-center text-lg shadow-lg transition-all">
                        <i class="fa-solid fa-chevron-left"></i>
                    </button>

                    <div id="calendarBadge" class="inline-flex items-center justify-center bg-orange-600 text-white font-display font-extrabold text-xl sm:text-2xl px-8 py-2 rounded-2xl shadow-lg border border-orange-400/50 uppercase">
                        MAYO
                    </div>

                    <button type="button" onclick="navigateCalendarMonth(1)" aria-label="Mes siguiente" title="Mes siguiente" class="w-11 h-11 rounded-2xl bg-white/10 hover:bg-orange-600 border border-white/15 hover:border-orange-400/50 text-amber-400 hover:text-white flex items-center justify-center text-lg shadow-lg transition-all">
                        <i class="fa-solid fa-chevron-right"></i>
                    </button>
                </div>
            </div>

            <div id="categoryFilter" class="flex flex-wrap items-center gap-2">
                <!-- Categorías de ferias para filtrar (vista consumidor) -->
            </div>

            <div class="bg-slate-950/70 rounded-2xl p-3 sm:p-5 border border-white/10">
                <div class="grid grid-cols-7 gap-1.5 sm:gap-2 text-center text-xs font-bold text-amber-400 uppercase tracking-wider mb-3">
                    <div>Dom</div><div>Lun</div><div>Mar</div><div>Mié</div><div>Jue</div><div>Vie</div><div>Sáb</div>
                </div>

                <div id="calendarDaysGrid" class="grid grid-cols-7 gap-1.5 sm:gap-2.5 text-center font-bold text-sm">
                    <!-- Dinámico vía JavaScript -->
                </div>
            </div>
        </section>

        <!-- Sección Acordeón y Detalle -->
        <section id="meses" class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <div class="lg:col-span-5 bg-slate-900/80 rounded-3xl border border-white/10 p-5 shadow-2xl space-y-3">
                <h3 class="font-display text-xl font-bold text-white px-2 mb-4 flex items-center justify-between">
                    <span><i class="fa-solid fa-calendar-days text-amber-400 mr-2"></i> Explora por Mes</span>
                    <span class="text-[10px] text-amber-300 font-semibold">Toca para abrir/cerrar</span>
                </h3>

                <div id="accordionMonthsContainer" class="space-y-2 text-sm font-bold">
                    <!-- Dinámico vía JS -->
                </div>
            </div>

            <div class="lg:col-span-7 bg-gradient-to-br from-orange-600 to-terracotta rounded-3xl p-6 sm:p-8 shadow-2xl border-2 border-orange-400/40 text-white space-y-6">
                <div class="flex items-start justify-between gap-4 border-b border-white/20 pb-4">
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 id="fairTitle" class="font-display text-2xl sm:text-3xl font-black">Feria del Libro</h3>
                            <span id="fairFavoriteBadge" class="hidden px-2 py-0.5 rounded-full bg-amber-400 text-slate-950 text-[10px] font-black uppercase tracking-wider shadow">
                                <i class="fa-solid fa-star text-amber-900"></i> Favorita
                            </span>
                        </div>
                        <p id="fairDateTime" class="text-amber-200 text-xs sm:text-sm font-semibold mt-1">
                            <i class="fa-regular fa-clock mr-1"></i> 16 de Mayo de 2026 | 14:00 - 20:00 hs
                        </p>
                    </div>

                    <button id="fairFavoriteBtn" onclick="toggleFairFavorite()" class="w-12 h-12 rounded-2xl bg-slate-900 hover:bg-slate-950 text-slate-400 hover:text-amber-400 font-extrabold flex items-center justify-center text-xl shadow-lg transition-all shrink-0 border border-white/10">
                        <i class="fa-solid fa-star"></i>
                    </button>
                </div>

                <div>
                    <a id="fairInsta" href="https://instagram.com" target="_blank" class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white px-4 py-2 rounded-xl text-xs font-bold hover:scale-105 transition-transform shadow-lg">
                        <i class="fa-brands fa-instagram text-base"></i>
                        <span id="fairHandle">@feria.del.libro.2026</span>
                    </a>
                </div>

                <div class="bg-slate-950/60 rounded-2xl p-4 border border-white/15 space-y-3">
                    <div class="flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-users text-amber-400"></i>
                            <h4 class="font-bold text-sm text-white">Emprendedores que asisten</h4>
                        </div>
                        <button type="button" onclick="openAsistentesModal()" class="bg-amber-400 hover:bg-amber-300 text-slate-950 font-black px-3 py-1.5 rounded-xl text-[10px] uppercase tracking-wide shadow flex items-center gap-1.5 transition-all">
                            <i class="fa-solid fa-users"></i> Quiénes asisten
                        </button>
                    </div>
                    <div id="asistentesPreview" class="flex items-center gap-2 overflow-x-auto pb-1">
                        <!-- Dinámico vía JS: fotos de los emprendedores -->
                    </div>
                </div>

                <div id="fairImagenContainer" class="hidden relative h-52 rounded-2xl overflow-hidden border-2 border-white/20 shadow-inner bg-slate-950">
                    <img id="fairImagen" alt="Foto del emprendimiento" class="w-full h-full object-cover">
                </div>

                <div id="fairDescripcion" class="text-sm text-white/90 italic leading-relaxed"></div>

                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <label class="text-xs font-bold text-amber-200 uppercase tracking-wider block">Ubicación y Mapa</label>
                        <span id="fairAddressLabel" class="text-xs font-semibold text-white">Palermo, CABA</span>
                    </div>
                    <div class="relative h-52 rounded-2xl bg-slate-950 overflow-hidden border-2 border-white/20 shadow-inner">
                        <iframe id="fairGoogleMap" class="w-full h-full border-0" loading="lazy" allowfullscreen src="https://maps.google.com/maps?q=La+Rural+Palermo+Buenos+Aires&t=&z=15&ie=UTF8&iwloc=&output=embed"></iframe>
                    </div>
                </div>

                <div class="bg-slate-950/70 rounded-2xl p-4 sm:p-5 border border-white/15 space-y-4">
                    <div class="flex items-center justify-between border-b border-white/10 pb-3">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-comments text-amber-400"></i>
                            <h4 class="font-bold text-sm text-white">Comentarios de la Comunidad</h4>
                        </div>
                        <span id="reviewsCountBadge" class="px-2 py-0.5 rounded-full bg-orange-600/80 text-white font-bold text-xs">
                            1 comentario
                        </span>
                    </div>

                    <div id="fairReviewsList" class="space-y-3 max-h-72 overflow-y-auto pr-1">
                        <!-- Dinámico vía JS -->
                    </div>

                    <div class="pt-2 border-t border-white/10 flex justify-end">
                        <button onclick="openReviewModal()" class="bg-gradient-to-r from-amber-400 to-orange-500 hover:brightness-110 text-slate-950 font-black px-4 py-2 rounded-xl text-xs shadow-lg transition-all flex items-center gap-1.5">
                            <i class="fa-solid fa-pen-to-square"></i> Añadir Reseña
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Mensajes publicados por los emprendedores (visibles para el consumidor) -->
        <section id="mensajesEmprendedores" class="bg-slate-900/90 rounded-3xl border border-white/10 p-5 sm:p-8 shadow-2xl space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div>
                    <span class="text-xs font-bold text-amber-400 uppercase tracking-widest">Comunidad</span>
                    <h2 class="font-display text-2xl sm:text-3xl font-black text-white">Mensajes de Emprendedores</h2>
                </div>
                <span class="text-[11px] text-slate-400 font-semibold">Novedades y fotos de los emprendimientos</span>
            </div>

            <div id="avisosList" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- Dinámico vía JS -->
            </div>
        </section>

    </main>

    <!-- Sección Emprendedores -->
    <section id="sectionPagina4" class="hidden flex-grow max-w-2xl w-full mx-auto px-4 py-8">
        <div class="bg-gradient-to-br from-orange-600 via-orange-700 to-terracotta rounded-3xl p-6 sm:p-8 shadow-2xl border-2 border-orange-400/50 space-y-6">
            <div class="flex items-center justify-between border-b border-white/20 pb-4">
                <div>
                    <span class="text-xs font-extrabold text-amber-300 uppercase tracking-widest">Emprendedores</span>
                    <h2 class="font-display text-2xl font-black text-white">Postulación de Emprendedor</h2>
                </div>
                <button onclick="showSection('home')" class="text-xs bg-slate-900/80 hover:bg-slate-900 text-white font-bold px-3 py-1.5 rounded-xl">
                    <i class="fa-solid fa-arrow-left mr-1"></i> Volver
                </button>
            </div>

            <form onsubmit="handleFormSubmit(event, 'Formulario de Emprendedor enviado con éxito!')" class="space-y-4">
                <div>
                    <label class="block text-xs font-extrabold text-amber-200 uppercase mb-1">Ubicación del Stand</label>
                    <input type="text" required value="Av. Rivadavia 3868" class="w-full bg-slate-950/80 border border-white/20 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-amber-400 font-semibold">
                </div>
                <div>
                    <label class="block text-xs font-extrabold text-amber-200 uppercase mb-1">Día Solicitado</label>
                    <input type="text" required value="25 de Septiembre del 2026" class="w-full bg-slate-950/80 border border-white/20 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-amber-400 font-semibold">
                </div>
                <div>
                    <label class="block text-xs font-extrabold text-amber-200 uppercase mb-1">Retiro de Merch</label>
                    <input type="text" required value="O'Higgins 1283" class="w-full bg-slate-950/80 border border-white/20 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-amber-400 font-semibold">
                </div>
                <div class="pt-4">
                    <button type="submit" class="w-full bg-slate-950 hover:bg-slate-900 text-amber-400 font-display font-black py-4 rounded-2xl shadow-xl transition-all border border-amber-400/40 text-base tracking-wide uppercase">
                        Finalizar Registro
                    </button>
                </div>
            </form>
        </div>
    </section>

    <!-- Sección Registrar Nueva Feria -->
    <section id="sectionPagina5" class="hidden flex-grow max-w-2xl w-full mx-auto px-4 py-8">
        <div class="bg-gradient-to-br from-orange-600 via-orange-700 to-terracotta rounded-3xl p-6 sm:p-8 shadow-2xl border-2 border-orange-400/50 space-y-6">
            <div class="flex items-center justify-between border-b border-white/20 pb-4">
                <div>
                    <span class="text-xs font-extrabold text-amber-300 uppercase tracking-widest">Feria</span>
                    <h2 class="font-display text-2xl font-black text-white">Avisar / Registrar Nueva Feria</h2>
                </div>
            </div>

            <form onsubmit="registrarNuevaFeriaBD(event)" class="space-y-4">
                <div>
                    <label class="block text-xs font-extrabold text-amber-200 uppercase mb-1">Nombre o Lugar de la Feria</label>
                    <input type="text" required value="Feria de Caballito" class="w-full bg-slate-950/80 border border-white/20 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-amber-400 font-semibold">
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-extrabold text-amber-200 uppercase mb-1">Día</label>
                        <input type="date" id="feriaDia" required value="2026-11-14" class="w-full bg-slate-950/80 border border-white/20 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-amber-400 font-semibold">
                    </div>
                    <div>
                        <label class="block text-xs font-extrabold text-amber-200 uppercase mb-1">Hora de Apertura</label>
                        <input type="time" id="feriaHoraApertura" required value="12:00" class="w-full bg-slate-950/80 border border-white/20 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-amber-400 font-semibold">
                    </div>
                    <div>
                        <label class="block text-xs font-extrabold text-amber-200 uppercase mb-1">Hora de Cierre</label>
                        <input type="time" id="feriaHoraCierre" required value="19:00" class="w-full bg-slate-950/80 border border-white/20 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-amber-400 font-semibold">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-extrabold text-amber-200 uppercase mb-1">Permisos Municipales</label>
                    <input type="text" required value="Permiso_municipal_aprobado_2026.pdf" class="w-full bg-slate-950/80 border border-white/20 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-amber-400 font-semibold">
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-extrabold text-amber-200 uppercase mb-1">Feriantes Esperados</label>
                        <input type="number" min="0" required value="45" class="w-full bg-slate-950/80 border border-white/20 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-amber-400 font-semibold">
                    </div>
                    <div>
                        <label class="block text-xs font-extrabold text-amber-200 uppercase mb-1">Responsable</label>
                        <input type="text" required value="Carlos Peralta" class="w-full bg-slate-950/80 border border-white/20 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-amber-400 font-semibold">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-extrabold text-amber-200 uppercase mb-1">Categoría de la Feria</label>
                    <select id="feriaCategoria" class="w-full bg-slate-950/80 border border-white/20 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-amber-400 font-semibold">
                        <option value="">Seleccioná una categoría</option>
                        <option value="Artesanías">Artesanías</option>
                        <option value="Comida y Bebidas">Comida y Bebidas</option>
                        <option value="Libros y Papelería">Libros y Papelería</option>
                        <option value="Modas y Textiles">Modas y Textiles</option>
                        <option value="Juguetes y Manualidades">Juguetes y Manualidades</option>
                        <option value="Música y Espontáneas">Música y Espontáneas</option>
                    </select>
                </div>
                <div class="pt-4">
                    <button type="submit" class="w-full bg-slate-950 hover:bg-slate-900 text-amber-400 font-display font-black py-4 rounded-2xl shadow-xl transition-all border border-amber-400/40 text-base tracking-wide uppercase">
                        Finalizar Registro
                    </button>
                </div>
            </form>
        </div>
    </section>

    <!-- Sección exclusiva del Emprendedor: publicar mensaje + foto -->
    <section id="sectionPagina6" class="hidden flex-grow max-w-2xl w-full mx-auto px-4 py-8">
        <div class="bg-gradient-to-br from-orange-600 via-orange-700 to-terracotta rounded-3xl p-6 sm:p-8 shadow-2xl border-2 border-orange-400/50 space-y-6">
            <div class="border-b border-white/20 pb-4">
                <span class="text-xs font-extrabold text-amber-300 uppercase tracking-widest">Emprendedores</span>
                <h2 class="font-display text-2xl font-black text-white">Publicá tu Mensaje</h2>
                <p class="text-xs text-amber-200 font-semibold mt-1">Escribí tu mensaje y subí una foto de tu emprendimiento.</p>
            </div>

            <form onsubmit="guardarAvisoBD(event)" class="space-y-4">
                <div>
                    <label class="block text-xs font-extrabold text-amber-200 uppercase mb-1">Feria en la que Participás</label>
                    <select id="avisoFeriaId" required class="w-full bg-slate-950/80 border border-white/20 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-amber-400 font-semibold">
                        <option value="">Seleccioná una feria</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-extrabold text-amber-200 uppercase mb-1">Mensaje del Emprendedor</label>
                    <textarea id="avisoMensaje" required rows="5" placeholder="Escribí tu mensaje o aviso..." class="w-full bg-slate-950/80 border border-white/20 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-amber-400 font-semibold"></textarea>
                </div>
                <div>
                    <label class="block text-xs font-extrabold text-amber-200 uppercase mb-1">Foto del Emprendimiento</label>
                    <input type="file" id="avisoFoto" accept="image/*" onchange="previewAvisoFoto(event)" class="w-full bg-slate-950/80 border border-white/20 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-amber-400 font-semibold file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-amber-400 file:text-slate-950 hover:file:bg-amber-300">
                    <img id="avisoFotoPreview" alt="Foto del emprendimiento" class="hidden mt-3 w-full h-52 object-cover rounded-2xl border-2 border-white/20 shadow-inner">
                </div>
                <div class="pt-4">
                    <button type="submit" class="w-full bg-slate-950 hover:bg-slate-900 text-amber-400 font-display font-black py-4 rounded-2xl shadow-xl transition-all border border-amber-400/40 text-base tracking-wide uppercase">
                        Publicar Mensaje
                    </button>
                </div>
            </form>
        </div>
    </section>

    <!-- Modal: Autenticación -->
    <div id="authModal" class="fixed inset-0 bg-slate-950/80 backdrop-blur-md z-50 flex items-center justify-center p-4 opacity-0 pointer-events-none transition-all duration-300">
        <div class="bg-slate-900 border-2 border-orange-500/50 rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl space-y-6 relative overflow-hidden">
            <button onclick="closeModal('authModal')" class="absolute top-4 right-4 w-8 h-8 rounded-full bg-white/10 text-slate-300 hover:text-white flex items-center justify-center">
                <i class="fa-solid fa-xmark"></i>
            </button>

            <div>
                <span class="text-xs font-extrabold text-amber-400 uppercase tracking-widest">Autenticación</span>
                <h3 class="font-display text-2xl font-black text-white mt-1">Acceso a Feriando</h3>
            </div>

            <div class="grid grid-cols-2 gap-2 bg-slate-950 p-1.5 rounded-2xl border border-white/10">
                <button onclick="switchAuthTab('signup')" id="tabSignupBtn" class="py-2.5 rounded-xl font-bold text-xs bg-orange-600 text-white shadow-md">
                    Crear Cuenta
                </button>
                <button onclick="switchAuthTab('login')" id="tabLoginBtn" class="py-2.5 rounded-xl font-bold text-xs text-slate-400 hover:text-white">
                    Iniciar Sesión
                </button>
            </div>

            <div id="roleSelector" class="mb-4">
    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Seleccioná tu perfil</p>
    <div class="grid grid-cols-3 gap-2">
        <button type="button" id="btn-role-consumidor" onclick="selectRole('consumidor')" class="py-2 px-4 rounded-xl text-xs font-semibold bg-amber-500/20 text-amber-400 border border-amber-500/50">Consumidor</button>
        <button type="button" id="btn-role-emprendedor" onclick="selectRole('emprendedor')" class="py-2 px-4 rounded-xl text-xs font-semibold bg-slate-800/40 text-slate-400 border border-slate-700/50">Emprendedor</button>
        <button type="button" id="btn-role-feria" onclick="selectRole('feria')" class="py-2 px-4 rounded-xl text-xs font-semibold bg-slate-800/40 text-slate-400 border border-slate-700/50">Feria</button>
    </div>
</div>

            <form id="formSignup" onsubmit="processUserLogin(event, 'signup')" class="space-y-3">
                <div>
                    <input id="signupName" type="text" required placeholder="Nombre completo (ej: Juan Pérez)" class="w-full bg-slate-950 border border-white/15 rounded-xl px-4 py-3 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-orange-500">
                </div>
                <div>
                    <input id="signupEmail" type="email" required placeholder="Correo electrónico" class="w-full bg-slate-950 border border-white/15 rounded-xl px-4 py-3 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-orange-500">
                </div>
                <div>
                    <input type="password" required placeholder="Contraseña" class="w-full bg-slate-950 border border-white/15 rounded-xl px-4 py-3 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-orange-500">
                </div>
                <button type="submit" class="w-full bg-gradient-to-r from-amber-400 to-orange-500 text-slate-950 font-black py-3.5 rounded-xl shadow-lg hover:brightness-110 transition-all text-xs uppercase tracking-wider">
                    Crear Cuenta e Ingresar
                </button>
            </form>

            <form id="formLogin" onsubmit="processUserLogin(event, 'login')" class="space-y-3 hidden">
                <div>
                    <input id="loginEmail" type="email" required placeholder="Correo electrónico" class="w-full bg-slate-950 border border-white/15 rounded-xl px-4 py-3 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-orange-500">
                </div>
                <div>
                    <input type="password" required placeholder="Contraseña" class="w-full bg-slate-950 border border-white/15 rounded-xl px-4 py-3 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-orange-500">
                </div>
                <button type="submit" class="w-full bg-orange-600 text-white font-black py-3.5 rounded-xl shadow-lg hover:bg-orange-500 transition-all text-xs uppercase tracking-wider">
                    Ingresar
                </button>
            </form>
        </div>
    </div>

    <!-- Modal: Publicar Reseña -->
    <div id="reviewModal" class="fixed inset-0 bg-slate-950/80 backdrop-blur-md z-50 flex items-center justify-center p-4 opacity-0 pointer-events-none transition-all duration-300">
        <div class="bg-slate-900 border border-white/20 rounded-3xl max-w-md w-full p-6 shadow-2xl space-y-4">
            <div class="flex items-center justify-between border-b border-white/10 pb-3">
                <h4 class="font-display font-bold text-white text-lg">Añadir Reseña</h4>
                <button onclick="closeModal('reviewModal')" class="text-slate-400 hover:text-white"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form onsubmit="submitNewReview(event)" class="space-y-3">
                <div>
                    <label class="text-xs text-amber-300 font-bold block mb-1">Calificación</label>
                    <div class="flex gap-2 text-amber-400 text-xl cursor-pointer">
                        <i onclick="setReviewRating(1)" class="star-rating-icon fa-solid fa-star"></i>
                        <i onclick="setReviewRating(2)" class="star-rating-icon fa-solid fa-star"></i>
                        <i onclick="setReviewRating(3)" class="star-rating-icon fa-solid fa-star"></i>
                        <i onclick="setReviewRating(4)" class="star-rating-icon fa-solid fa-star"></i>
                        <i onclick="setReviewRating(5)" class="star-rating-icon fa-solid fa-star"></i>
                    </div>
                </div>
                <div>
                    <textarea id="reviewCommentText" required rows="3" placeholder="Escribí tu comentario sobre esta feria..." class="w-full bg-slate-950 border border-white/15 rounded-xl p-3 text-xs text-white focus:outline-none focus:border-amber-400"></textarea>
                </div>
                <button type="submit" class="w-full bg-amber-400 text-slate-950 font-extrabold py-2.5 rounded-xl text-xs">Publicar Reseña</button>
            </form>
        </div>
    </div>

    <!-- Modal: Quiénes asisten a la feria -->
    <div id="asistentesModal" class="fixed inset-0 bg-slate-950/80 backdrop-blur-md z-50 flex items-center justify-center p-4 opacity-0 pointer-events-none transition-all duration-300">
        <div class="bg-slate-900 border border-white/20 rounded-3xl max-w-lg w-full p-6 shadow-2xl space-y-4 max-h-[85vh] overflow-y-auto">
            <div class="flex items-start justify-between border-b border-white/10 pb-3 gap-3">
                <div>
                    <span class="text-[10px] font-bold text-amber-400 uppercase tracking-widest">Emprendedores</span>
                    <h4 class="font-display font-bold text-white text-lg">Quiénes asisten</h4>
                    <p id="asistentesFairName" class="text-[11px] text-slate-400 font-semibold"></p>
                </div>
                <button onclick="closeModal('asistentesModal')" class="text-slate-400 hover:text-white shrink-0"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div id="asistentesList" class="space-y-3">
                <!-- Dinámico vía JS -->
            </div>
        </div>
    </div>

    <!-- Modal: Mensajes de un emprendedor -->
    <div id="emprendedorModal" class="fixed inset-0 bg-slate-950/80 backdrop-blur-md z-50 flex items-center justify-center p-4 opacity-0 pointer-events-none transition-all duration-300">
        <div class="bg-slate-900 border border-white/20 rounded-3xl max-w-lg w-full p-6 shadow-2xl space-y-4 max-h-[85vh] overflow-y-auto">
            <div class="flex items-start justify-between border-b border-white/10 pb-3 gap-3">
                <div>
                    <span class="text-[10px] font-bold text-amber-400 uppercase tracking-widest">Emprendedor</span>
                    <h4 id="emprendedorNombre" class="font-display font-bold text-white text-lg">Emprendedor</h4>
                    <p id="emprendedorFeria" class="text-[11px] text-slate-400 font-semibold"></p>
                </div>
                <button onclick="closeModal('emprendedorModal')" class="text-slate-400 hover:text-white shrink-0"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div id="emprendedorMensajes" class="space-y-4">
                <!-- Dinámico vía JS -->
            </div>
            <button onclick="closeModal('emprendedorModal'); openModal('asistentesModal');" class="w-full bg-white/10 hover:bg-white/20 text-white font-bold py-2.5 rounded-xl text-xs">
                <i class="fa-solid fa-arrow-left mr-1"></i> Volver a los asistentes
            </button>
        </div>
    </div>

    <!-- Modal: Conocenos -->
    <div id="modalConocenos" class="fixed inset-0 bg-slate-950/80 backdrop-blur-md z-50 flex items-center justify-center p-4 opacity-0 pointer-events-none transition-all duration-300">
        <div class="bg-slate-900 border border-white/20 rounded-3xl max-w-md w-full p-6 shadow-2xl space-y-4 text-center">
            <div class="w-16 h-16 rounded-2xl bg-orange-600 text-amber-300 font-black text-2xl flex items-center justify-center mx-auto shadow-lg">
                <i class="fa-solid fa-store"></i>
            </div>
            <h3 class="font-display text-2xl font-bold text-white">Sobre Feriando</h3>
            <p class="text-xs text-slate-300 leading-relaxed">
                Feriando es la red de conexión urbana entre feriantes, artesanos, eventos culturales y visitantes de Buenos Aires. Promovemos el trabajo autogestivo y la cultura popular.
            </p>
            <p class="text-[11px] text-amber-400 font-bold">Desarrollado para <?php echo $institutionCopyright; ?></p>
            <button onclick="closeModal('modalConocenos')" class="w-full bg-white/10 hover:bg-white/20 text-white font-bold py-2.5 rounded-xl text-xs">Cerrar</button>
        </div>
    </div>

    <!-- Toast de Notificaciones -->
    <div id="toastBox" class="fixed bottom-6 right-6 z-50 bg-amber-400 text-slate-950 px-5 py-3 rounded-2xl font-bold text-xs shadow-2xl border-2 border-slate-900 transform translate-y-20 opacity-0 transition-all duration-300 flex items-center gap-2">
        <i class="fa-solid fa-circle-check text-base text-slate-900"></i>
        <span id="toastMsg">Acción completada con éxito</span>
    </div>

    <!-- Footer -->
    <footer class="mt-auto bg-slate-950 border-t border-white/10 py-6 px-4 text-center text-xs text-slate-400 font-medium">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-tent text-orange-500"></i>
                <span class="font-display font-bold text-white">Feriando</span>
            </div>
            <p>Derechos Reservados a <?php echo $institutionCopyright; ?></p>
            <div id="footerLinks" class="flex items-center gap-4 text-slate-500 text-[11px]">
                <a href="#calendario" onclick="showSection('home')" class="hover:text-amber-400">Inicio</a>
                <a href="#meses" onclick="showSection('home')" class="hover:text-amber-400">Ferias</a>
            </div>
        </div>
    </footer>

    <!-- Lógica Interactiva JavaScript -->
    <script>
        // ESTADO DE LA APLICACIÓN
        let currentUser = null; 
        let selectedRole = 'consumidor';
        const SESSION_STORAGE_KEY = 'feriando_current_user';
        let activeMonthKey = 'MAYO'; 
        let activeFairKey = 1;
        let selectedReviewRating = 5;
        let selectedCategory = 'Todas';
        let currentFairAvisos = [];

        // CATEGORÍAS DE FERIAS DISPONIBLES PARA FILTRAR (vista del consumidor)
        const fairCategories = ['Artesanías', 'Comida y Bebidas', 'Libros y Papelería', 'Modas y Textiles', 'Juguetes y Manualidades', 'Música y Espontáneas'];

        // SESIÓN: guardar / cargar usuario en localStorage para persistir el login
        function saveCurrentUserSession(user) {
            try {
                if (user) localStorage.setItem(SESSION_STORAGE_KEY, JSON.stringify(user));
                else localStorage.removeItem(SESSION_STORAGE_KEY);
            } catch (e) { /* almacenamiento no disponible */ }
        }

        function loadCurrentUserSession() {
            try {
                const saved = localStorage.getItem(SESSION_STORAGE_KEY);
                return saved ? JSON.parse(saved) : null;
            } catch (e) { return null; }
        }

        // Restaurar sesión persistida al iniciar la aplicación
        currentUser = loadCurrentUserSession();

        // BASE DE DATOS DE MESES
        const monthsData = {
            'ENERO': { name: 'Enero', year: 2026, totalDays: 31, startOffset: 4 },
            'FEBRERO': { name: 'Febrero', year: 2026, totalDays: 28, startOffset: 0 },
            'MARZO': { name: 'Marzo', year: 2026, totalDays: 31, startOffset: 0 },
            'ABRIL': { name: 'Abril', year: 2026, totalDays: 30, startOffset: 3 },
            'MAYO': { name: 'Mayo', year: 2026, totalDays: 31, startOffset: 5 },
            'JUNIO': { name: 'Junio', year: 2026, totalDays: 30, startOffset: 1 },
            'JULIO': { name: 'Julio', year: 2026, totalDays: 31, startOffset: 3 },
            'AGOSTO': { name: 'Agosto', year: 2026, totalDays: 31, startOffset: 0 },
            'SEPTIEMBRE': { name: 'Septiembre', year: 2026, totalDays: 30, startOffset: 2 },
            'OCTUBRE': { name: 'Octubre', year: 2026, totalDays: 31, startOffset: 4 },
            'NOVIEMBRE': { name: 'Noviembre', year: 2026, totalDays: 30, startOffset: 0 },
            'DICIEMBRE': { name: 'Diciembre', year: 2026, totalDays: 31, startOffset: 2 }
        };

        // BASE DE DATOS LOCAL
        const fairsDatabase = {
            1: {
                id: 1,
                title: 'Feria del Libro 2026',
                month: 'MAYO',
                dayNumber: 16,
                dateTime: '16 de Mayo de 2026 | 14:00 - 20:00 hs',
                handle: '@feria.del.libro.2026',
                address: 'La Rural, Palermo',
                mapUrl: 'https://maps.google.com/maps?q=La+Rural+Palermo+Buenos+Aires&t=&z=15&ie=UTF8&iwloc=&output=embed',
                imagen: null,
                descripcion: null,
                categoria: 'Libros y Papelería',
                isFavorite: false,
                reviews: [
                    { id: 101, author: 'María del Pilar', rating: 5, timeAgo: 'Hace poco', text: 'Gran variedad de stands y excelente atención.', likes: 24, userLiked: false }
                ]
            }
        };

        // CARGAR DESDE BD MYSQL
        async function cargarFeriasDesdeBD() {
            try {
                const response = await fetch('api/get_ferias.php');
                const resultado = await response.json();

                if (resultado.status === 'success') {
                    const feriasBD = resultado.data;

                    for (let key in fairsDatabase) delete fairsDatabase[key];

                    feriasBD.forEach(f => {
                        // Parsear la fecha como local para evitar que se corra un día por zona horaria
                        const partesFecha = (f.fecha_inicio || '').split('-');
                        const anio = parseInt(partesFecha[0], 10);
                        const mesNum = parseInt(partesFecha[1], 10);
                        const diaNum = parseInt(partesFecha[2], 10);
                        const fechaObj = new Date(anio, mesNum - 1, diaNum);

                        const mesNombre = fechaObj.toLocaleString('es-ES', { month: 'long' });
                        const mesKey = mesNombre.charAt(0).toUpperCase() + mesNombre.slice(1).toUpperCase();

                        fairsDatabase[f.id_feria] = {
                            id: f.id_feria,
                            title: f.nombre_feria,
                            month: mesKey,
                            dayNumber: diaNum,
                            dateTime: `${f.fecha_inicio} | ${f.hora_apertura} - ${f.hora_cierre} hs`,
                            handle: f.instagram || '@feria_oficial',
                            address: f.direccion_texto,
                            mapUrl: `https://maps.google.com/maps?q=${encodeURIComponent(f.direccion_texto)}&output=embed`,
                            imagen: f.imagen_url || null,
                            descripcion: f.descripcion || null,
                            categoria: f.categoria || 'Artesanías',
                            isFavorite: false,
                            reviews: []
                        };
                    });

                    // Si hay ferias cargadas desde la BD, seleccionar la primera y renderizar
                    const primerFeria = Object.values(fairsDatabase)[0];
                    if (primerFeria) {
                        activeFairKey = primerFeria.id;
                        loadFairDetails(primerFeria.id);
                    }

                    // Sincronizar el selector de feria del formulario del emprendedor
                    const avisoFeriaSelect = document.getElementById('avisoFeriaId');
                    if (avisoFeriaSelect) {
                        const valorActual = avisoFeriaSelect.value;
                        avisoFeriaSelect.innerHTML = '<option value="">Seleccioná una feria</option>';
                        Object.values(fairsDatabase).forEach(fair => {
                            const opt = document.createElement('option');
                            opt.value = fair.id;
                            opt.textContent = fair.title;
                            avisoFeriaSelect.appendChild(opt);
                        });
                        if (valorActual) avisoFeriaSelect.value = valorActual;
                    }

                    renderAccordionMonths();
                    renderCalendar(activeMonthKey);
                    renderCategoryFilter();
                }
            } catch (error) {
                console.error("Error al conectar con la base de datos feriando:", error);
            }
        }

        window.addEventListener('DOMContentLoaded', () => {
            renderAccordionMonths();
            renderCalendar(activeMonthKey);
            loadFairDetails(activeFairKey);
            updateAuthUI();
            cargarFeriasDesdeBD();
            cargarAvisos();
        });

        // 1. REGISTRAR USUARIO EN MYSQL
       // MANEJO DE SESIÓN Y AUTENTICACIÓN (REGISTRO Y LOGIN)
async function processUserLogin(e, mode) {
    e.preventDefault();

    if (mode === 'signup') {
        // REGISTRO DE NUEVA CUENTA
        const nombre = document.getElementById('signupName').value.trim();
        const email = document.getElementById('signupEmail').value.trim();
        const contrasena = document.querySelector('#formSignup input[type="password"]').value;

        try {
            const res = await fetch('api/guardar_usuario.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ nombre, email, contrasena, rol: selectedRole.toLowerCase() })
            });
            const data = await res.json();

            if (data.status === 'success') {
                currentUser = { id_usuario: data.id_usuario, name: data.nombre, role: selectedRole };
                saveCurrentUserSession(currentUser);
                updateAuthUI();
                closeModal('authModal');
                showToast(`¡Usuario ${data.nombre} creado con éxito!`);
                cargarFavoritos();
            } else {
                showToast(data.message);
            }
        } catch (err) {
            showToast("Error al conectar con la base de datos.");
        }

    } else {
        // INICIO DE SESIÓN CON VALIDACIÓN EN BD
        const email = document.getElementById('loginEmail').value.trim();
        const contrasena = document.querySelector('#formLogin input[type="password"]').value;

        try {
            const res = await fetch('api/login_usuario.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ email, contrasena })
            });
            const data = await res.json();

            if (data.status === 'success') {
                currentUser = { 
                    id_usuario: data.usuario.id_usuario, 
                    name: data.usuario.nombre, 
                    role: data.usuario.rol 
                };
                saveCurrentUserSession(currentUser);
                updateAuthUI();
                closeModal('authModal');
                showToast(`¡Bienvenido/a de nuevo, ${data.usuario.nombre}!`);
                cargarFavoritos();
            } else {
                // Muestra el mensaje de error de cuenta no encontrada o clave errónea
                showToast(data.message);
            }
        } catch (err) {
            showToast("Error de conexión al verificar la cuenta.");
        }
    }
}

        // 2. REGISTRAR NUEVA FERIA EN MYSQL
        async function registrarNuevaFeriaBD(e) {
            e.preventDefault();
            const form = e.target;
            const inputs = form.querySelectorAll('input');

            // JERARQUÍA: si no hay sesión iniciada, no se puede registrar una feria
            if (!currentUser) {
                openModal('authModal');
                showToast('Necesitás iniciar sesión para registrar una feria.');
                return;
            }

            // JERARQUÍA: solo los roles autorizados (feria) pueden registrar ferias
            const rolesPermitidosRegistrarFeria = ['feria'];
            if (!rolesPermitidosRegistrarFeria.includes((currentUser.role || '').toLowerCase())) {
                showToast('Tu rol no tiene permisos para registrar una feria.');
                return;
            }

            const feriaData = {
                id_organizador: currentUser.id_usuario,
                nombre_feria: inputs[0].value,
                direccion_texto: inputs[0].value,
                categoria: document.getElementById('feriaCategoria')?.value || null,
                fecha_inicio: document.getElementById('feriaDia')?.value || "2026-11-14",
                hora_apertura: ((document.getElementById('feriaHoraApertura')?.value || '12:00') + ':00'),
                hora_cierre: ((document.getElementById('feriaHoraCierre')?.value || '19:00') + ':00')
            };

            // VALIDACIÓN: los feriantes esperados no pueden ser negativos
            const feriantesInput = form.querySelector('input[type="number"]');
            const feriantesEsperados = feriantesInput ? parseInt(feriantesInput.value, 10) : 45;
            if (isNaN(feriantesEsperados) || feriantesEsperados < 0) {
                showToast('Los feriantes esperados no pueden ser números negativos.');
                return;
            }

            try {
                const res = await fetch('api/guardar_feria.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(feriaData)
                });
                const data = await res.json();

                if (data.status === 'success') {
                    showToast('¡Feria registrada en MySQL!');
                    showSection('home');
                    cargarFeriasDesdeBD();
                } else {
                    showToast('Error: ' + data.message);
                }
            } catch (err) {
                showToast('Error al registrar la feria.');
            }
        }

        // 3. REGISTRAR RESEÑA EN MYSQL
        async function submitNewReview(e) {
            e.preventDefault();
            const comment = document.getElementById('reviewCommentText').value.trim();
            if (!comment) return;

            // JERARQUÍA: si no hay sesión iniciada, no se puede publicar una reseña
            if (!currentUser) {
                openModal('authModal');
                showToast('Necesitás iniciar sesión para publicar una reseña.');
                return;
            }

            // JERARQUÍA: solo los consumidores pueden publicar reseñas
            if ((currentUser.role || '').toLowerCase() !== 'consumidor') {
                showToast('Solo los usuarios con rol de consumidor pueden publicar reseñas.');
                return;
            }

            const fair = fairsDatabase[activeFairKey];

            try {
                const res = await fetch('api/guardar_resena.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        id_consumidor: currentUser.id_usuario,
                        id_feria: fair ? fair.id : 1,
                        calificacion: selectedReviewRating,
                        comentario: comment
                    })
                });
                const data = await res.json();

                if (data.status === 'success') {
                    closeModal('reviewModal');
                    document.getElementById('reviewCommentText').value = '';
                    showToast('¡Reseña guardada en MySQL!');
                    cargarFeriasDesdeBD();
                }
            } catch (err) {
                showToast('Error al publicar la reseña.');
            }
        }

        // NAVEGACIÓN ENTRE SECCIONES
        function showSection(sectionId) {
            // El emprendedor solo accede a su página de mensaje + foto
            // La feria solo accede a su formulario para publicar una feria
            const rolActual = (currentUser && currentUser.role ? currentUser.role : '').toLowerCase();
            if (rolActual === 'emprendedor') sectionId = 'pagina6';
            if (rolActual === 'feria') sectionId = 'pagina5';

            document.getElementById('sectionHome').classList.add('hidden');
            document.getElementById('sectionPagina4').classList.add('hidden');
            document.getElementById('sectionPagina5').classList.add('hidden');
            document.getElementById('sectionPagina6').classList.add('hidden');

            if (sectionId === 'home') document.getElementById('sectionHome').classList.remove('hidden');
            if (sectionId === 'pagina4') document.getElementById('sectionPagina4').classList.remove('hidden');
            if (sectionId === 'pagina5') document.getElementById('sectionPagina5').classList.remove('hidden');
            if (sectionId === 'pagina6') document.getElementById('sectionPagina6').classList.remove('hidden');

            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // NAVEGACIÓN PROTEGIDA: Avisar / Registrar Feria
        // - Rol 'feria': acceso al formulario completo de registro de feria.
        // - Rol 'emprendedor': acceso al formulario simple de envío de aviso.
        // - Rol 'consumidor': sin permisos para avisar de ferias.
        function showFairRegistration() {
            if (!currentUser) {
                openModal('authModal');
                showToast('Necesitás iniciar sesión para avisar de una feria.');
                return;
            }

            const rolActual = (currentUser.role || '').toLowerCase();

            if (rolActual === 'feria') {
                showSection('pagina5');
                return;
            }

            if (rolActual === 'emprendedor') {
                showSection('pagina6');
                return;
            }

            showToast('Solo los emprendedores y ferias pueden avisar de ferias.');
        }

        // RENDER DEL ACORDEÓN DE MESES
        function renderAccordionMonths() {
            const container = document.getElementById('accordionMonthsContainer');
            container.innerHTML = '';

            Object.keys(monthsData).forEach(mKey => {
                const mInfo = monthsData[mKey];
                const isOpen = (mKey === activeMonthKey);
                const monthFairs = Object.values(fairsDatabase).filter(f => f.month === mKey && (selectedCategory === 'Todas' || f.categoria === selectedCategory));

                const itemHtml = `
                    <div class="rounded-2xl overflow-hidden border ${isOpen ? 'bg-orange-600/20 border-orange-500/50' : 'bg-white/5 border-white/10'} transition-all">
                        <button onclick="toggleMonthAccordion('${mKey}')" class="w-full px-4 py-3 text-left font-extrabold text-sm flex items-center justify-between ${isOpen ? 'bg-orange-600 text-white shadow-md' : 'text-slate-300 hover:bg-white/10'} transition-colors">
                            <span class="flex items-center gap-2">
                                <i class="fa-solid fa-calendar-day ${isOpen ? 'text-amber-300' : 'text-slate-500'}"></i>
                                ${mInfo.name} ${mInfo.year}
                            </span>
                            <i class="fa-solid ${isOpen ? 'fa-chevron-down text-white' : 'fa-chevron-right text-xs opacity-50'}"></i>
                        </button>

                        ${isOpen ? `
                            <div class="p-2 space-y-1 bg-slate-950/70">
                                ${monthFairs.length > 0 ? monthFairs.map(fair => {
                                    const isSelected = (fair.id === activeFairKey);
                                    return `
                                        <button onclick="selectFairFromList('${fair.id}')" class="w-full text-left px-3.5 py-2.5 rounded-xl text-xs font-bold flex items-center justify-between transition-all ${isSelected ? 'bg-amber-400 text-slate-950 shadow-md' : 'text-slate-300 hover:bg-white/10'}">
                                            <span class="flex items-center gap-1.5 truncate pr-2">
                                                ${fair.isFavorite ? '<i class="fa-solid fa-star text-amber-500 shrink-0"></i>' : '<i class="fa-solid fa-circle text-[6px] text-orange-400 shrink-0"></i>'}
                                                <span class="truncate">${fair.title}</span>
                                            </span>
                                            <span class="text-[10px] font-extrabold shrink-0 opacity-80">Día ${fair.dayNumber}</span>
                                        </button>
                                    `;
                                }).join('') : `
                                    <div class="p-3 text-center text-xs text-slate-400 italic">
                                        No hay ferias programadas para este mes aún.
                                    </div>
                                `}
                            </div>
                        ` : ''}
                    </div>
                `;
                container.insertAdjacentHTML('beforeend', itemHtml);
            });
        }

        function toggleMonthAccordion(mKey) {
            activeMonthKey = mKey;
            const firstFairInMonth = Object.values(fairsDatabase).find(f => f.month === mKey);
            if (firstFairInMonth) activeFairKey = firstFairInMonth.id;

            renderAccordionMonths();
            renderCalendar(activeMonthKey);
            if (firstFairInMonth) loadFairDetails(activeFairKey);
        }

        function selectFairFromList(fairId) {
            activeFairKey = fairId;
            renderAccordionMonths();
            loadFairDetails(fairId);
            renderCalendar(activeMonthKey);
        }

        // NAVEGACIÓN ENTRE MESES DEL CALENDARIO
        function navigateCalendarMonth(offset) {
            const keys = Object.keys(monthsData);
            const currentIndex = keys.indexOf(activeMonthKey);
            const newIndex = (currentIndex + offset + keys.length) % keys.length;
            const newMonthKey = keys[newIndex];

            activeMonthKey = newMonthKey;
            renderCalendar(newMonthKey);

            // Sincronizar la feria seleccionada con el mes visualizado
            const firstFairInMonth = Object.values(fairsDatabase).find(f => f.month === newMonthKey);
            if (firstFairInMonth) {
                activeFairKey = firstFairInMonth.id;
                loadFairDetails(activeFairKey);
            }
        }

        // RENDER DEL CALENDARIO
        function renderCalendar(mKey) {
            const mInfo = monthsData[mKey];
            document.getElementById('calendarMonthTitle').textContent = `${mInfo.name} ${mInfo.year}`;
            document.getElementById('calendarBadge').textContent = mInfo.name;

            const grid = document.getElementById('calendarDaysGrid');
            grid.innerHTML = '';

           // 1. Obtener las claves de los meses en un array para buscar el mes anterior
const keys = Object.keys(monthsData);
const currentIndex = keys.indexOf(mKey);
// Si es Enero (index 0), el anterior es Diciembre (index 11), sino tomamos el index anterior
const prevIndex = currentIndex === 0 ? 11 : currentIndex - 1;
const prevMonthKey = keys[prevIndex];
const prevMonthTotalDays = monthsData[prevMonthKey].totalDays;

// 2. Renderizar los días de relleno del mes anterior de forma dinámica y correcta
for (let i = 0; i < mInfo.startOffset; i++) {
    // Calcula el número de día exacto restando el desfase inverso
    const dayNumber = prevMonthTotalDays - mInfo.startOffset + 1 + i;
    
    grid.insertAdjacentHTML('beforeend', `
        <div class="calendar-day-cell bg-white/5 text-slate-600 border-white/5 opacity-40">
            <span class="text-xs sm:text-sm font-semibold">${dayNumber}</span>
        </div>
    `);
}


            const monthFairs = Object.values(fairsDatabase).filter(f => f.month === mKey && (selectedCategory === 'Todas' || f.categoria === selectedCategory));

            for (let d = 1; d <= mInfo.totalDays; d++) {
                const fairOnDay = monthFairs.find(f => f.dayNumber === d);

                if (fairOnDay) {
                    const isFav = fairOnDay.isFavorite;
                    const isSelected = (fairOnDay.id === activeFairKey);

                    grid.insertAdjacentHTML('beforeend', `
                        <button onclick="selectFairFromList('${fairOnDay.id}')" class="calendar-day-cell ${
                            isFav ? 'bg-gradient-to-br from-amber-500 to-orange-600 text-slate-950 favorite-glow border-amber-300' :
                            isSelected ? 'bg-gradient-to-br from-orange-500 to-terracotta border-amber-400 text-white shadow-lg' :
                            'bg-gradient-to-br from-amber-600/70 to-orange-700/70 border-orange-400/40 text-white hover:border-amber-300'
                        }">
                            <div class="w-full flex items-center justify-between text-xs sm:text-sm font-black px-0.5">
                                <span>${d}</span>
                                ${isFav ? '<i class="fa-solid fa-star text-amber-900 text-[10px]"></i>' : '<i class="fa-solid fa-calendar-check text-[10px] text-amber-300/80"></i>'}
                            </div>

                            <div class="w-full my-auto text-center px-0.5">
                                <span class="block text-[9px] sm:text-[10px] font-extrabold leading-tight truncate max-w-full tracking-tight ${isFav ? 'text-slate-950' : 'text-white'}">
                                    ${fairOnDay.title}
                                </span>
                            </div>
                        </button>
                    `);
                } else {
                    grid.insertAdjacentHTML('beforeend', `
                        <div class="calendar-day-cell bg-white/10 hover:bg-white/15 text-slate-200 border-white/10">
                            <span class="text-xs sm:text-sm font-bold">${d}</span>
                        </div>
                    `);
                }
            }
        }

        // CARGA DE DETALLES
        function loadFairDetails(fairId) {
            const fair = fairsDatabase[fairId];
            if (!fair) return;

            document.getElementById('fairTitle').textContent = fair.title;
            document.getElementById('fairDateTime').innerHTML = `<i class="fa-regular fa-clock mr-1"></i> ${fair.dateTime}`;
            document.getElementById('fairHandle').textContent = fair.handle;
            document.getElementById('fairAddressLabel').textContent = fair.address;
            document.getElementById('fairGoogleMap').src = fair.mapUrl;

            // Mostrar / ocultar la foto del emprendimiento
            const imagenContainer = document.getElementById('fairImagenContainer');
            const fairImagen = document.getElementById('fairImagen');
            const fairDescripcion = document.getElementById('fairDescripcion');

            if (imagenContainer && fairImagen) {
                if (fair.imagen) {
                    fairImagen.src = fair.imagen;
                    imagenContainer.classList.remove('hidden');
                } else {
                    imagenContainer.classList.add('hidden');
                }
            }

            if (fairDescripcion) {
                fairDescripcion.textContent = fair.descripcion || '';
            }

            const favBtn = document.getElementById('fairFavoriteBtn');
            const favBadge = document.getElementById('fairFavoriteBadge');

            if (fair.isFavorite) {
                favBtn.className = "w-12 h-12 rounded-2xl bg-amber-400 text-slate-950 font-extrabold flex items-center justify-center text-xl shadow-xl transition-all shrink-0 border-2 border-white animate-bounce";
                favBadge.classList.remove('hidden');
            } else {
                favBtn.className = "w-12 h-12 rounded-2xl bg-slate-900 hover:bg-slate-950 text-slate-400 hover:text-amber-400 font-extrabold flex items-center justify-center text-xl shadow-lg transition-all shrink-0 border border-white/10";
                favBadge.classList.add('hidden');
            }

            renderReviewsList(fair);
            cargarAsistentesFeria(fair.id);
        }

        async function toggleFairFavorite() {
            const fair = fairsDatabase[activeFairKey];
            if (!fair) return;

            // JERARQUÍA: solo los consumidores pueden guardar favoritos
            if (!currentUser) {
                openModal('authModal');
                showToast('Necesitás iniciar sesión para guardar favoritas.');
                return;
            }

            const rolActual = (currentUser.role || '').toLowerCase();
            if (rolActual !== 'consumidor') {
                showToast('Solo los consumidores pueden guardar ferias en favoritos.');
                return;
            }

            try {
                const res = await fetch('api/guardar_favorito.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id_feria: fair.id })
                });
                const data = await res.json();

                if (data.status === 'success') {
                    fair.isFavorite = data.is_favorito;
                    showToast(fair.isFavorite ? `¡${fair.title} en FAVORITAS! ⭐` : `Se quitó ${fair.title} de favoritas.`);

                    loadFairDetails(activeFairKey);
                    renderAccordionMonths();
                    renderCalendar(activeMonthKey);
                } else {
                    showToast('Error: ' + data.message);
                }
            } catch (err) {
                showToast('Error al guardar la feria en favoritas.');
            }
        }

        // CARGAR FAVORITOS DE UN CONSUMIDOR DESDE LA BD
        async function cargarFavoritos() {
            if (!currentUser) return;
            if ((currentUser.role || '').toLowerCase() !== 'consumidor') return;

            try {
                const res = await fetch('api/get_favoritos.php');
                const data = await res.json();

                if (data.status === 'success') {
                    const idsFavoritos = data.data.map(f => f.id_feria);
                    Object.values(fairsDatabase).forEach(f => { f.isFavorite = idsFavoritos.includes(f.id); });
                    renderAccordionMonths();
                    renderCalendar(activeMonthKey);
                    loadFairDetails(activeFairKey);
                }
            } catch (err) { /* Sin conexión a BD: se mantiene el estado local */ }
        }

        function escapeHtml(texto) {
            return String(texto ?? '').replace(/[&<>"']/g, c => ({
                '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
            }[c]));
        }

        // CARGAR MENSAJES DE EMPRENDEDORES (VISIBLES PARA EL CONSUMIDOR)
        async function cargarAvisos() {
            const container = document.getElementById('avisosList');
            if (!container) return;

            try {
                const res = await fetch('api/get_avisos.php');
                const data = await res.json();

                if (data.status !== 'success') return;

                const avisos = data.data || [];

                if (avisos.length === 0) {
                    container.innerHTML = `<div class="col-span-full text-center py-8 text-slate-400 text-xs italic">Todavía no hay mensajes publicados por los emprendedores.</div>`;
                    return;
                }

                container.innerHTML = avisos.map(a => {
                    const fecha = a.fecha_creacion ? new Date(String(a.fecha_creacion).replace(' ', 'T')) : null;
                    const fechaTexto = fecha ? fecha.toLocaleDateString('es-ES', { day: '2-digit', month: 'long', year: 'numeric' }) : '';
                    const inicial = escapeHtml((a.emprendedor || 'E').charAt(0).toUpperCase());
                    const imagen = a.imagen_url
                        ? `<img src="${escapeHtml(a.imagen_url)}" alt="Foto de ${escapeHtml(a.emprendedor)}" class="w-full h-44 object-cover border-b border-white/10">`
                        : '';

                    return `
                        <article class="bg-slate-950/70 rounded-2xl border border-white/10 overflow-hidden flex flex-col">
                            ${imagen}
                            <div class="p-4 space-y-3 flex-1 flex flex-col">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-amber-400 to-orange-500 text-slate-950 font-black text-xs flex items-center justify-center shrink-0">${inicial}</div>
                                    <div class="min-w-0">
                                        <p class="text-xs font-bold text-white truncate">${escapeHtml(a.emprendedor)}</p>
                                        <p class="text-[10px] text-slate-400">${escapeHtml(fechaTexto)}${a.nombre_feria ? ' · ' + escapeHtml(a.nombre_feria) : ''}</p>
                                    </div>
                                </div>
                                <p class="text-xs text-slate-200 italic leading-relaxed">"${escapeHtml(a.mensaje)}"</p>
                            </div>
                        </article>
                    `;
                }).join('');
            } catch (err) {
                container.innerHTML = `<div class="col-span-full text-center py-8 text-slate-400 text-xs italic">No se pudieron cargar los mensajes.</div>`;
            }
        }

        // ASISTENTES / EMPRENDEDORES DE LA FERIA
        async function cargarAsistentesFeria(fairId) {
            const preview = document.getElementById('asistentesPreview');
            if (!preview) return;

            if (!fairId) {
                currentFairAvisos = [];
                preview.innerHTML = '';
                return;
            }

            try {
                const res = await fetch(`api/get_avisos.php?id_feria=${encodeURIComponent(fairId)}`);
                const data = await res.json();

                // Evitar que una respuesta vieja pise la feria que se está viendo
                const feriaActual = fairsDatabase[activeFairKey];
                if (!feriaActual || Number(feriaActual.id) !== Number(fairId)) return;

                currentFairAvisos = (data.status === 'success' && Array.isArray(data.data)) ? data.data : [];
                renderAsistentesPreview();
            } catch (err) {
                currentFairAvisos = [];
                renderAsistentesPreview();
            }
        }

        function agruparAsistentes() {
            const grupos = new Map();
            currentFairAvisos.forEach(a => {
                const id = Number(a.id_emprendedor);
                if (!grupos.has(id)) {
                    grupos.set(id, {
                        id_emprendedor: id,
                        emprendedor: a.emprendedor,
                        imagen_url: a.imagen_url,
                        nombre_feria: a.nombre_feria,
                        mensajes: []
                    });
                }
                grupos.get(id).mensajes.push(a);
            });
            return [...grupos.values()];
        }

        function renderAsistentesPreview() {
            const preview = document.getElementById('asistentesPreview');
            if (!preview) return;

            const asistentes = agruparAsistentes();

            if (asistentes.length === 0) {
                preview.innerHTML = `<p class="text-[11px] text-slate-400 italic">Todavía no hay emprendedores que avisaron para esta feria.</p>`;
                return;
            }

            preview.innerHTML = asistentes.slice(0, 10).map(a => `
                <button type="button" onclick="verMensajesEmprendedor(${a.id_emprendedor})" title="${escapeHtml(a.emprendedor)}" class="shrink-0 group">
                    ${a.imagen_url
                        ? `<img src="${escapeHtml(a.imagen_url)}" alt="${escapeHtml(a.emprendedor)}" class="w-11 h-11 rounded-full object-cover border-2 border-amber-400/60 group-hover:border-amber-300 transition-all">`
                        : `<span class="w-11 h-11 rounded-full bg-gradient-to-br from-amber-400 to-orange-500 text-slate-950 font-black flex items-center justify-center border-2 border-amber-400/60">${escapeHtml((a.emprendedor || 'E').charAt(0).toUpperCase())}</span>`}
                </button>
            `).join('') + (asistentes.length > 10 ? `<span class="text-[10px] text-slate-400 font-bold shrink-0">+${asistentes.length - 10}</span>` : '');
        }

        function openAsistentesModal() {
            const fair = fairsDatabase[activeFairKey];
            if (!fair) return;

            const nombre = document.getElementById('asistentesFairName');
            if (nombre) nombre.textContent = fair.title;

            renderAsistentesLista();
            openModal('asistentesModal');
        }

        function renderAsistentesLista() {
            const container = document.getElementById('asistentesList');
            if (!container) return;

            const asistentes = agruparAsistentes();

            if (asistentes.length === 0) {
                container.innerHTML = `<div class="text-center py-8 text-slate-400 text-xs italic">Todavía no hay emprendedores que avisaron para esta feria.</div>`;
                return;
            }

            container.innerHTML = asistentes.map(a => {
                const cantidad = a.mensajes.length;
                return `
                    <button type="button" onclick="verMensajesEmprendedor(${a.id_emprendedor})" class="w-full text-left bg-slate-950/70 hover:bg-slate-800 border border-white/10 hover:border-amber-400/50 rounded-2xl p-3 flex items-center gap-3 transition-all">
                        ${a.imagen_url
                            ? `<img src="${escapeHtml(a.imagen_url)}" alt="${escapeHtml(a.emprendedor)}" class="w-11 h-11 rounded-full object-cover border-2 border-amber-400/60 shrink-0">`
                            : `<span class="w-11 h-11 rounded-full bg-gradient-to-br from-amber-400 to-orange-500 text-slate-950 font-black flex items-center justify-center border-2 border-amber-400/60 shrink-0">${escapeHtml((a.emprendedor || 'E').charAt(0).toUpperCase())}</span>`}
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-bold text-white truncate">${escapeHtml(a.emprendedor)}</p>
                            <p class="text-[11px] text-slate-400 truncate">${cantidad} ${cantidad === 1 ? 'mensaje publicado' : 'mensajes publicados'}</p>
                        </div>
                        <i class="fa-solid fa-chevron-right text-amber-400"></i>
                    </button>
                `;
            }).join('');
        }

        function verMensajesEmprendedor(idEmprendedor) {
            const mensajes = currentFairAvisos.filter(a => Number(a.id_emprendedor) === Number(idEmprendedor));
            if (mensajes.length === 0) return;

            const primero = mensajes[0];
            document.getElementById('emprendedorNombre').textContent = primero.emprendedor;
            const feriaLabel = document.getElementById('emprendedorFeria');
            if (feriaLabel) feriaLabel.textContent = primero.nombre_feria || '';

            document.getElementById('emprendedorMensajes').innerHTML = mensajes.map(m => {
                const fecha = m.fecha_creacion ? new Date(String(m.fecha_creacion).replace(' ', 'T')) : null;
                const fechaTexto = fecha ? fecha.toLocaleDateString('es-ES', { day: '2-digit', month: 'long', year: 'numeric' }) : '';
                return `
                    <article class="bg-slate-950/70 rounded-2xl border border-white/10 overflow-hidden">
                        ${m.imagen_url ? `<img src="${escapeHtml(m.imagen_url)}" alt="Foto de ${escapeHtml(m.emprendedor)}" class="w-full h-56 object-cover border-b border-white/10">` : ''}
                        <div class="p-4 space-y-2">
                            <p class="text-xs text-slate-400 font-semibold">${escapeHtml(fechaTexto)}</p>
                            <p class="text-sm text-slate-200 italic leading-relaxed">"${escapeHtml(m.mensaje)}"</p>
                        </div>
                    </article>
                `;
            }).join('');

            closeModal('asistentesModal');
            openModal('emprendedorModal');
        }

        // FILTRO POR CATEGORÍAS (CONSUMIDOR)
        function renderCategoryFilter() {
            const container = document.getElementById('categoryFilter');
            if (!container) return;

            const categories = ['Todas', ...fairCategories];
            container.innerHTML = categories.map(cat => `
                <button type="button" onclick="selectCategory('${cat}')" class="px-3 py-1.5 rounded-xl text-[10px] font-bold uppercase tracking-wide border transition-all ${selectedCategory === cat ? 'bg-orange-600 text-white border-orange-400 shadow-lg' : 'bg-white/5 text-slate-300 border-white/15 hover:bg-white/10 hover:text-white'}">
                    ${cat}
                </button>
            `).join('');
        }

        function selectCategory(cat) {
            selectedCategory = cat;
            renderCategoryFilter();
            renderCalendar(activeMonthKey);
            renderAccordionMonths();
        }

        // VISTA PREVIA DE LA FOTO DEL EMPRENDEDOR
        function previewAvisoFoto(event) {
            const archivo = event.target.files && event.target.files[0];
            const preview = document.getElementById('avisoFotoPreview');
            if (!archivo || !preview) return;

            preview.src = URL.createObjectURL(archivo);
            preview.classList.remove('hidden');
        }

        // PUBLICAR MENSAJE + FOTO DESDE EL ROL EMPRENDEDOR
        async function guardarAvisoBD(e) {
            e.preventDefault();

            const mensaje = document.getElementById('avisoMensaje')?.value.trim();
            const idFeria = document.getElementById('avisoFeriaId')?.value;
            const fotoInput = document.getElementById('avisoFoto');
            const fotoPreview = document.getElementById('avisoFotoPreview');

            if (!currentUser) {
                openModal('authModal');
                showToast('Necesitás iniciar sesión para publicar tu mensaje.');
                return;
            }

            const rolActual = (currentUser.role || '').toLowerCase();
            if (rolActual !== 'emprendedor') {
                showToast('Solo los emprendedores pueden publicar este mensaje.');
                return;
            }

            if (!mensaje) {
                showToast('Escribí el mensaje del emprendedor.');
                return;
            }

            if (!idFeria) {
                showToast('Seleccioná la feria en la que participás.');
                return;
            }

            try {
                const formData = new FormData();
                formData.append('mensaje', mensaje);
                formData.append('id_feria', idFeria);
                if (fotoInput && fotoInput.files && fotoInput.files[0]) {
                    formData.append('foto', fotoInput.files[0]);
                }

                const res = await fetch('api/guardar_aviso.php', {
                    method: 'POST',
                    body: formData
                });
                const data = await res.json();

                if (data.status === 'success') {
                    showToast('¡Mensaje publicado con éxito!');
                    document.getElementById('avisoMensaje').value = '';
                    if (fotoInput) fotoInput.value = '';
                    if (fotoPreview) {
                        fotoPreview.classList.add('hidden');
                        fotoPreview.removeAttribute('src');
                    }
                    cargarAvisos();
                } else {
                    showToast('Error: ' + data.message);
                }
            } catch (err) {
                showToast('Error al publicar el mensaje.');
            }
        }

        function renderReviewsList(fair) {
            const reviewsContainer = document.getElementById('fairReviewsList');
            const reviewsBadge = document.getElementById('reviewsCountBadge');
            
            reviewsBadge.textContent = `${fair.reviews.length} ${fair.reviews.length === 1 ? 'reseña' : 'reseñas'}`;
            reviewsContainer.innerHTML = '';

            if (fair.reviews.length === 0) {
                reviewsContainer.innerHTML = `<div class="text-center py-6 text-slate-400 text-xs italic">Aún no hay comentarios sobre esta feria.</div>`;
                return;
            }

            fair.reviews.forEach((rev) => {
                const starsHtml = Array(5).fill(0).map((_, i) => `<i class="fa-solid fa-star ${i < rev.rating ? 'text-amber-400' : 'text-slate-600'}"></i>`).join('');

                const itemHtml = `
                    <div class="p-3.5 rounded-xl bg-slate-900/90 border border-white/10 space-y-2">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full bg-gradient-to-br from-amber-400 to-orange-500 text-slate-950 font-black text-xs flex items-center justify-center">
                                    ${rev.author.charAt(0).toUpperCase()}
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-white">${rev.author}</p>
                                    <div class="flex text-[9px] gap-0.5">${starsHtml}</div>
                                </div>
                            </div>
                            <span class="text-[10px] text-slate-400">${rev.timeAgo}</span>
                        </div>
                        <p class="text-xs text-slate-200 italic leading-relaxed">"${rev.text}"</p>
                    </div>
                `;
                reviewsContainer.insertAdjacentHTML('beforeend', itemHtml);
            });
        }

        function openReviewModal() { openModal('reviewModal'); }

        function setReviewRating(rating) {
            selectedReviewRating = rating;
            const stars = document.querySelectorAll('.star-rating-icon');
            stars.forEach((s, idx) => {
                s.className = `star-rating-icon fa-solid fa-star ${idx < rating ? 'text-amber-400' : 'text-slate-600'}`;
            });
        }

        function handleAuthButtonClick() {
            const authMenu = document.getElementById('authMenu');
            const authMenuUser = document.getElementById('authMenuUserName');

            if (currentUser) {
                // Con sesión iniciada: alternar el menú de cuenta (no se cierra la sesión al tocar el botón)
                if (authMenu) authMenu.classList.toggle('hidden');
                if (authMenuUser) authMenuUser.textContent = currentUser.name;
                return;
            }

            openModal('authModal');
        }

        async function closeSession() {
            // Cerrar la sesión en el servidor
            try {
                await fetch('api/logout_usuario.php', { method: 'POST' });
            } catch (err) { /* Si falla el logout del servidor, igual limpiamos la sesión local */ }

            currentUser = null;
            saveCurrentUserSession(null);
            updateAuthUI();
            showToast('Has cerrado sesión correctamente.');
            const authMenu = document.getElementById('authMenu');
            if (authMenu) authMenu.classList.add('hidden');
        }

        function updateAuthUI() {
            const mainAuthText = document.getElementById('mainAuthText');
            const mainAuthIcon = document.getElementById('mainAuthIcon');

            if (currentUser) {
                mainAuthText.textContent = `¡Hola, ${currentUser.name}!`;
                mainAuthIcon.className = 'fa-solid fa-circle-user text-sm';

                // Sincronizar el menú de cuenta (authMenu)
                const authMenu = document.getElementById('authMenu');
                const authMenuUser = document.getElementById('authMenuUserName');
                if (authMenu) authMenu.classList.remove('hidden');
                if (authMenuUser) authMenuUser.textContent = currentUser.name;
            } else {
                mainAuthText.textContent = 'Registrarse / Iniciar Sesión';
                mainAuthIcon.className = 'fa-solid fa-user-plus text-sm';

                const authMenu = document.getElementById('authMenu');
                const authMenuUser = document.getElementById('authMenuUserName');
                if (authMenu) authMenu.classList.add('hidden');
                if (authMenuUser) authMenuUser.textContent = 'Invitado';
            }

            // Sincronizar el perfil del menú lateral (drawer)
            const drawerUserName = document.getElementById('drawerUserName');
            const drawerUserRole = document.getElementById('drawerUserRole');

            if (currentUser) {
                if (drawerUserName) drawerUserName.textContent = currentUser.name;
                if (drawerUserRole) drawerUserRole.textContent = currentUser.role ? currentUser.role.charAt(0).toUpperCase() + currentUser.role.slice(1) : 'Consumidor';
            } else {
                if (drawerUserName) drawerUserName.textContent = 'Invitado';
                if (drawerUserRole) drawerUserRole.textContent = 'Sin sesión iniciada';
            }

            // Aplicar la vista exclusiva según el rol (el emprendedor solo ve su mensaje)
            applyRoleUI();
        }

        // VISTAS EXCLUSIVAS POR ROL:
        // - Emprendedor: solo su formulario de mensaje + foto.
        // - Feria: solo su formulario para publicar una feria.
        function applyRoleUI() {
            const rolActual = (currentUser && currentUser.role ? currentUser.role : '').toLowerCase();
            const esEmprendedor = rolActual === 'emprendedor';
            const esFeria = rolActual === 'feria';
            const vistaRestringida = esEmprendedor || esFeria;

            const mainNav = document.getElementById('mainNav');
            if (mainNav) mainNav.style.display = vistaRestringida ? 'none' : '';

            const drawerQuickAccess = document.getElementById('drawerQuickAccess');
            if (drawerQuickAccess) drawerQuickAccess.style.display = vistaRestringida ? 'none' : '';

            const footerLinks = document.getElementById('footerLinks');
            if (footerLinks) footerLinks.style.display = vistaRestringida ? 'none' : '';

            const home = document.getElementById('sectionHome');
            const pagina4 = document.getElementById('sectionPagina4');
            const pagina5 = document.getElementById('sectionPagina5');
            const pagina6 = document.getElementById('sectionPagina6');
            if (!home || !pagina5 || !pagina6) return;

            home.classList.add('hidden');
            if (pagina4) pagina4.classList.add('hidden');

            if (esEmprendedor) {
                pagina5.classList.add('hidden');
                pagina6.classList.remove('hidden');
            } else if (esFeria) {
                pagina6.classList.add('hidden');
                pagina5.classList.remove('hidden');
            } else {
                pagina5.classList.add('hidden');
                pagina6.classList.add('hidden');
                home.classList.remove('hidden');
            }
        }

        function switchAuthTab(tab) {
    const signupBtn = document.getElementById('tabSignupBtn');
    const loginBtn = document.getElementById('tabLoginBtn');
    const signupForm = document.getElementById('formSignup');
    const loginForm = document.getElementById('formLogin');
    const roleSelector = document.getElementById('roleSelector');

    if (tab === 'signup') {
        // Estilos para botón Crear Cuenta activo
        signupBtn.className = "py-2.5 rounded-xl font-bold text-xs bg-orange-600 text-white shadow-md";
        loginBtn.className = "py-2.5 rounded-xl font-bold text-xs text-slate-400 hover:text-white";
        
        // Visibilidad
        signupForm.classList.remove('hidden');
        loginForm.classList.add('hidden');
        roleSelector.classList.remove('hidden');
    } else {
        // Estilos para botón Iniciar Sesión activo
        loginBtn.className = "py-2.5 rounded-xl font-bold text-xs bg-orange-600 text-white shadow-md";
        signupBtn.className = "py-2.5 rounded-xl font-bold text-xs text-slate-400 hover:text-white";
        
        // Visibilidad
        loginForm.classList.remove('hidden');
        signupForm.classList.add('hidden');
        roleSelector.classList.add('hidden');
    }
}

        function selectRole(role) {
    selectedRole = role; // Guarda 'consumidor', 'emprendedor' o 'feria'

    // Definir estilos
    const activeClass = "py-2 px-4 rounded-xl text-xs font-semibold bg-amber-500/20 text-amber-400 border border-amber-500/50 shadow-sm transition-all";
    const inactiveClass = "py-2 px-4 rounded-xl text-xs font-semibold bg-slate-800/40 text-slate-400 border border-slate-700/50 hover:bg-slate-800 hover:text-slate-200 transition-all";

    // Obtener los botones por su ID o atributo onclick
    const roles = ['consumidor', 'emprendedor', 'feria'];

    roles.forEach(r => {
        // Asumiendo que tus botones tienen IDs como 'btn-role-consumidor', 'btn-role-emprendedor', etc.
        const btn = document.getElementById(`btn-role-${r}`) || document.querySelector(`button[onclick*="'${r}'"]`);
        
        if (btn) {
            if (r === role) {
                btn.className = activeClass;
            } else {
                btn.className = inactiveClass;
            }
        }
    });
}

        function toggleDrawer() {
            document.getElementById('sideDrawer').classList.toggle('translate-x-full');
            document.getElementById('drawerBackdrop').classList.toggle('opacity-0');
            document.getElementById('drawerBackdrop').classList.toggle('pointer-events-none');
        }

        function openModal(id) { document.getElementById(id)?.classList.remove('opacity-0', 'pointer-events-none'); }
        function closeModal(id) { document.getElementById(id)?.classList.add('opacity-0', 'pointer-events-none'); }

        function handleFormSubmit(e, message) {
            e.preventDefault();
            showToast(message);
        }

        function showToast(msg) {
            const toast = document.getElementById('toastBox');
            document.getElementById('toastMsg').textContent = msg;
            toast.classList.remove('translate-y-20', 'opacity-0');
            setTimeout(() => toast.classList.add('translate-y-20', 'opacity-0'), 3200);
        }
    </script>
</body>
</html>