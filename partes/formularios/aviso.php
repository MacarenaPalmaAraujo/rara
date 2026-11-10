    <!-- Formulario del rol Emprendedor para publicar un mensaje y una foto -->
    <!-- Sección exclusiva del Emprendedor: publicar mensaje + foto -->
    <section id="sectionPagina6" class="hidden flex-grow max-w-2xl w-full mx-auto px-4 py-8">
        <div class="bg-gradient-to-br from-orange-600 via-orange-700 to-terracotta rounded-3xl p-6 sm:p-8 shadow-2xl border-2 border-orange-400/50 space-y-6">
            <div class="flex items-start justify-between gap-3 border-b border-white/20 pb-4">
                <div>
                    <span class="text-xs font-extrabold text-amber-300 uppercase tracking-widest">Emprendedores</span>
                    <h2 class="font-display text-2xl font-black text-white">Mi Perfil de Emprendedor</h2>
                    <p class="text-xs text-amber-200 font-semibold mt-1">Avisá en qué feria estás: lugar, hora, puesto, Instagram y una foto de tu emprendimiento.</p>
                </div>
                <button onclick="showSection('home')" class="text-xs bg-slate-900/80 hover:bg-slate-900 text-white font-bold px-3 py-1.5 rounded-xl shrink-0">
                    <i class="fa-solid fa-arrow-left mr-1"></i> Volver
                </button>
            </div>

            <form onsubmit="guardarAvisoBD(event)" class="space-y-4">
                <div>
                    <label class="block text-xs font-extrabold text-amber-200 uppercase mb-1">Feria en la que Participás</label>
                    <select id="avisoFeriaId" required class="w-full bg-slate-950/80 border border-white/20 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-amber-400 font-semibold">
                        <option value="">Seleccioná una feria</option>
                    </select>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-extrabold text-amber-200 uppercase mb-1">Lugar</label>
                        <input type="text" id="avisoLugar" placeholder="Ej: Plaza Italia" class="w-full bg-slate-950/80 border border-white/20 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-amber-400 font-semibold">
                    </div>
                    <div>
                        <label class="block text-xs font-extrabold text-amber-200 uppercase mb-1">Hora</label>
                        <input type="text" id="avisoHora" placeholder="Ej: 14:00 a 20:00" class="w-full bg-slate-950/80 border border-white/20 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-amber-400 font-semibold">
                    </div>
                    <div>
                        <label class="block text-xs font-extrabold text-amber-200 uppercase mb-1">Puesto</label>
                        <input type="text" id="avisoPuesto" placeholder="Ej: 12" class="w-full bg-slate-950/80 border border-white/20 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-amber-400 font-semibold">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-extrabold text-amber-200 uppercase mb-1">Instagram del Emprendimiento</label>
                    <input type="text" id="avisoInstagram" placeholder="@mi_emprendimiento" class="w-full bg-slate-950/80 border border-white/20 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-amber-400 font-semibold">
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
