    <!-- Página principal: banner, calendario, detalle de feria y mensajes de emprendedores -->
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
                    <button onclick="showEmprendedorRegistration()" class="bg-slate-900 hover:bg-slate-800 text-white font-bold px-5 py-2.5 rounded-2xl text-xs sm:text-sm transition-all shadow-lg flex items-center gap-2">
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
                    <div class="flex items-center gap-3 mt-1">
                        <h2 id="calendarMonthTitle" class="font-display text-2xl sm:text-3xl font-black text-white">Mayo 2026</h2>
                        <select id="yearSelector" onchange="changeCalendarYear(this.value)" class="bg-slate-800 text-amber-300 text-xs font-extrabold px-3 py-1.5 rounded-xl border border-white/15 outline-none cursor-pointer hover:bg-slate-700 transition-colors">
                            <option value="2025">2025</option>
                            <option value="2026" selected>2026</option>
                            <option value="2027">2027</option>
                            <option value="2028">2028</option>
                            <option value="2029">2029</option>
                        </select>
                    </div>
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

                    <div class="flex items-center gap-2 shrink-0">
                        <button id="fairFavoriteBtn" onclick="toggleFairFavorite()" title="Guardar en favoritos" class="w-12 h-12 rounded-2xl bg-slate-900 hover:bg-slate-950 text-slate-400 hover:text-amber-400 font-extrabold flex items-center justify-center text-xl shadow-lg transition-all shrink-0 border border-white/10">
                            <i class="fa-solid fa-star"></i>
                        </button>
                        <button id="fairDeleteBtn" onclick="eliminarFeriaActual()" title="Eliminar esta feria" class="hidden w-12 h-12 rounded-2xl bg-red-500/20 hover:bg-red-500 text-red-300 hover:text-white font-extrabold flex items-center justify-center text-lg shadow-lg transition-all shrink-0 border border-red-400/40">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
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

        <!-- Panel chiquito de la feria: aparece al pasar el mouse o tocar un día del calendario -->
        <div id="calendarFairPopover"
             class="hidden fixed z-[60] bg-slate-900 border border-amber-400/40 rounded-2xl p-3 shadow-2xl space-y-2"
             onmouseenter="cancelHideFairPopover()"
             onmouseleave="scheduleHideFairPopover()">
            <!-- Contenido dinámico vía JS -->
        </div>

    </main>
