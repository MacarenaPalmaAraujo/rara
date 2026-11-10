    <!-- Formulario público de postulación para emprendedores -->
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

            <div class="pt-2 border-t border-white/20">
                <button type="button" onclick="showAvisoForm()" class="w-full bg-amber-400 hover:bg-amber-300 text-slate-950 font-display font-black py-3.5 rounded-2xl shadow-xl transition-all text-sm tracking-wide uppercase flex items-center justify-center gap-2">
                    <i class="fa-solid fa-bullhorn"></i> Avisar en qué feria estoy
                </button>
                <p class="text-[11px] text-amber-100 text-center mt-2 font-semibold">Ahí podés cargar lugar, hora, puesto, Instagram y foto de tu emprendimiento.</p>
            </div>
        </div>
    </section>
