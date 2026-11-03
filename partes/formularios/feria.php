    <!-- Formulario del rol Feria para registrar una nueva feria -->
    <!-- Sección Registrar Nueva Feria -->
    <section id="sectionPagina5" class="hidden flex-grow max-w-2xl w-full mx-auto px-4 py-8">
        <div class="bg-gradient-to-br from-orange-600 via-orange-700 to-terracotta rounded-3xl p-6 sm:p-8 shadow-2xl border-2 border-orange-400/50 space-y-6">
            <div class="flex items-center justify-between border-b border-white/20 pb-4">
                <div>
                    <span class="text-xs font-extrabold text-amber-300 uppercase tracking-widest">Feria</span>
                    <h2 class="font-display text-2xl font-black text-white">Avisar / Registrar Nueva Feria</h2>
                </div>
                <button onclick="showSection('home')" class="text-xs bg-slate-900/80 hover:bg-slate-900 text-white font-bold px-3 py-1.5 rounded-xl">
                    <i class="fa-solid fa-arrow-left mr-1"></i> Volver
                </button>
            </div>

            <form onsubmit="registrarNuevaFeriaBD(event)" class="space-y-4">
                <div>
                    <label class="block text-xs font-extrabold text-amber-200 uppercase mb-1">Nombre o Lugar de la Feria</label>
                    <input type="text" id="feriaNombre" required value="Feria de Caballito" class="w-full bg-slate-950/80 border border-white/20 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-amber-400 font-semibold">
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
