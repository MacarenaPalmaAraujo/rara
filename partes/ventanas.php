    <!-- Modales y notificaciones: autenticación, reseñas, asistentes, emprendedor, conocenos y toast -->
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
