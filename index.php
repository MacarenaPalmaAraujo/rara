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
      <script src="ola.js"></script>
</head>
<body class="min-h-screen flex flex-col justify-between selection:bg-amber-400 selection:text-slate-900">
<script src="archivo.js"></script>
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

      
</body>
</html>