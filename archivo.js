// ESTADO DE LA APLICACIÓN
let currentUser = null; 
let selectedRole = 'Consumidor';
let activeMonthKey = 'MAYO'; 
let activeFairKey = 'Feria del Libro';
let selectedReviewRating = 5;

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
    'Feria del Libro': {
        id: 1,
        title: 'Feria del Libro 2026',
        month: 'MAYO',
        dayNumber: 16,
        dateTime: '16 de Mayo de 2026 | 14:00 - 20:00 hs',
        handle: '@feria.del.libro.2026',
        address: 'La Rural, Palermo',
        mapUrl: 'https://maps.google.com/maps?q=La+Rural+Palermo+Buenos+Aires&t=&z=15&ie=UTF8&iwloc=&output=embed',
        isFavorite: false,
        reviews: [
            { id: 101, author: 'María del Pilar', rating: 5, timeAgo: 'Hace poco', text: 'Gran variedad de stands y excelente atención.', likes: 24, userLiked: false }
        ]
    }
};

// Mapeo para traducir el número de mes (01-12) a las claves de tu objeto monthsData
const numeroToMonthKey = {
    '01': 'ENERO', '02': 'FEBRERO', '03': 'MARZO', '04': 'ABRIL',
    '05': 'MAYO', '06': 'JUNIO', '07': 'JULIO', '08': 'AGOSTO',
    '09': 'SEPTIEMBRE', '10': 'OCTUBRE', '11': 'NOVIEMBRE', '12': 'DICIEMBRE'
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
                // Separamos YYYY-MM-DD sin problemas de zona horaria
                const [anio, mesStr, diaStr] = f.fecha_inicio.split('-');
                const monthKey = numeroToMonthKey[mesStr] || 'MAYO';
                const diaNumero = parseInt(diaStr, 10);

                // Usamos el ID de la feria como clave en lugar del nombre
                fairsDatabase[f.id_feria] = {
                    id: f.id_feria,
                    title: f.nombre_feria,
                    month: monthKey,
                    dayNumber: diaNumero,
                    dateTime: `${diaStr}/${mesStr}/${anio} | ${f.hora_apertura} - ${f.hora_cierre} hs`,
                    handle: f.instagram || '@feria_oficial',
                    address: f.direccion_texto,
                    mapUrl: `https://maps.google.com/maps?q=${encodeURIComponent(f.direccion_texto)}&output=embed`,
                    isFavorite: false,
                    reviews: []
                };
            });

            // Seleccionamos la primera feria cargada por defecto si existe
            const allKeys = Object.keys(fairsDatabase);
            if (allKeys.length > 0 && !fairsDatabase[activeFairKey]) {
                activeFairKey = allKeys[0];
                activeMonthKey = fairsDatabase[activeFairKey].month;
            }

            renderAccordionMonths();
            renderCalendar(activeMonthKey);
            if (fairsDatabase[activeFairKey]) {
                loadFairDetails(activeFairKey);
            }
        }
    } catch (error) {
        console.error("Error al conectar con la base de datos feriando:", error);
    }
}

// MANEJO DE SESIÓN Y AUTENTICACIÓN (REGISTRO Y LOGIN)
async function processUserLogin(e, mode) {
    e.preventDefault();

    if (mode === 'signup') {
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
                updateAuthUI();
                closeModal('authModal');
                showToast(`¡Usuario ${data.nombre} creado con éxito!`);
            } else {
                showToast(data.message);
            }
        } catch (err) {
            showToast("Error al conectar con la base de datos.");
        }

    } else {
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
                updateAuthUI();
                closeModal('authModal');
                showToast(`¡Bienvenido/a de nuevo, ${data.usuario.nombre}!`);
            } else {
                showToast(data.message);
            }
        } catch (err) {
            showToast("Error de conexión al verificar la cuenta.");
        }
    }
}

// REGISTRAR NUEVA FERIA EN MYSQL
async function registrarNuevaFeriaBD(e) {
    e.preventDefault();
    const form = e.target;
    const inputs = form.querySelectorAll('input');

    const feriaData = {
        id_organizador: currentUser ? currentUser.id_usuario : 1,
        nombre_feria: inputs[0].value,
        direccion_texto: inputs[0].value,
        fecha_inicio: "2026-11-14",
        hora_apertura: "12:00:00",
        hora_cierre: "19:00:00"
    };

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

function selectFairFromList(fairId) {
    activeFairKey = fairId;
    if (fairsDatabase[fairId]) {
        activeMonthKey = fairsDatabase[fairId].month;
    }
    renderAccordionMonths();
    loadFairDetails(fairId);
    renderCalendar(activeMonthKey);
}

// REGISTRAR RESEÑA EN MYSQL
async function submitNewReview(e) {
    e.preventDefault();
    const comment = document.getElementById('reviewCommentText').value.trim();
    if (!comment) return;

    const fair = fairsDatabase[activeFairKey];

    try {
        const res = await fetch('api/guardar_resena.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                id_consumidor: currentUser ? currentUser.id_usuario : 1,
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
    document.getElementById('sectionHome').classList.add('hidden');
    document.getElementById('sectionPagina4').classList.add('hidden');
    document.getElementById('sectionPagina5').classList.add('hidden');

    if (sectionId === 'home') document.getElementById('sectionHome').classList.remove('hidden');
    if (sectionId === 'pagina4') document.getElementById('sectionPagina4').classList.remove('hidden');
    if (sectionId === 'pagina5') document.getElementById('sectionPagina5').classList.remove('hidden');

    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// RENDER DEL ACORDEÓN DE MESES
function renderAccordionMonths() {
    const container = document.getElementById('accordionMonthsContainer');
    container.innerHTML = '';

    Object.keys(monthsData).forEach(mKey => {
        const mInfo = monthsData[mKey];
        const isOpen = (mKey === activeMonthKey);
        const monthFairs = Object.values(fairsDatabase).filter(f => f.month === mKey);

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

// RENDER DEL CALENDARIO
function renderCalendar(mKey) {
    const mInfo = monthsData[mKey];
    document.getElementById('calendarMonthTitle').textContent = `${mInfo.name} ${mInfo.year}`;
    document.getElementById('calendarBadge').textContent = mInfo.name;

    const grid = document.getElementById('calendarDaysGrid');
    grid.innerHTML = '';

    const keys = Object.keys(monthsData);
    const currentIndex = keys.indexOf(mKey);
    const prevIndex = currentIndex === 0 ? 11 : currentIndex - 1;
    const prevMonthKey = keys[prevIndex];
    const prevMonthTotalDays = monthsData[prevMonthKey].totalDays;

    for (let i = 0; i < mInfo.startOffset; i++) {
        const dayNumber = prevMonthTotalDays - mInfo.startOffset + 1 + i;
        
        grid.insertAdjacentHTML('beforeend', `
            <div class="calendar-day-cell bg-white/5 text-slate-600 border-white/5 opacity-40">
                <span class="text-xs sm:text-sm font-semibold">${dayNumber}</span>
            </div>
        `);
    }

    const monthFairs = Object.values(fairsDatabase).filter(f => f.month === mKey);

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
}

function toggleFairFavorite() {
    const fair = fairsDatabase[activeFairKey];
    if (!fair) return;

    fair.isFavorite = !fair.isFavorite;
    showToast(fair.isFavorite ? `¡${fair.title} en FAVORITAS! ⭐` : `Se quitó ${fair.title} de favoritas.`);

    loadFairDetails(activeFairKey);
    renderAccordionMonths();
    renderCalendar(activeMonthKey);
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
    if (currentUser) {
        currentUser = null;
        updateAuthUI();
        showToast('Has cerrado sesión correctamente.');
    } else {
        openModal('authModal');
    }
}

function updateAuthUI() {
    const mainAuthBtn = document.getElementById('mainAuthBtn');
    const mainAuthText = document.getElementById('mainAuthText');
    const mainAuthIcon = document.getElementById('mainAuthIcon');

    if (currentUser) {
        mainAuthText.textContent = `¡Hola, ${currentUser.name}!`;
        mainAuthIcon.className = 'fa-solid fa-circle-user text-sm';
    } else {
        mainAuthText.textContent = 'Registrarse / Iniciar Sesión';
        mainAuthIcon.className = 'fa-solid fa-user-plus text-sm';
    }
}

function switchAuthTab(tab) {
    const signupBtn = document.getElementById('tabSignupBtn');
    const loginBtn = document.getElementById('tabLoginBtn');
    const signupForm = document.getElementById('formSignup');
    const loginForm = document.getElementById('formLogin');
    const roleSelector = document.getElementById('roleSelector');

    if (tab === 'signup') {
        signupBtn.className = "py-2.5 rounded-xl font-bold text-xs bg-orange-600 text-white shadow-md";
        loginBtn.className = "py-2.5 rounded-xl font-bold text-xs text-slate-400 hover:text-white";
        
        signupForm.classList.remove('hidden');
        loginForm.classList.add('hidden');
        roleSelector.classList.remove('hidden');
    } else {
        loginBtn.className = "py-2.5 rounded-xl font-bold text-xs bg-orange-600 text-white shadow-md";
        signupBtn.className = "py-2.5 rounded-xl font-bold text-xs text-slate-400 hover:text-white";
        
        loginForm.classList.remove('hidden');
        signupForm.classList.add('hidden');
        roleSelector.classList.add('hidden');
    }
}

function selectRole(role) {
    selectedRole = role;

    const activeClass = "py-2 px-4 rounded-xl text-xs font-semibold bg-amber-500/20 text-amber-400 border border-amber-500/50 shadow-sm transition-all";
    const inactiveClass = "py-2 px-4 rounded-xl text-xs font-semibold bg-slate-800/40 text-slate-400 border border-slate-700/50 hover:bg-slate-800 hover:text-slate-200 transition-all";

    const roles = ['consumidor', 'emprendedor', 'feria'];

    roles.forEach(r => {
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