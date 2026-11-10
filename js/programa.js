
        // ESTADO DE LA APLICACIÓN
        let currentUser = null; 
        let selectedRole = 'consumidor';
        const SESSION_STORAGE_KEY = 'feriando_current_user';
        let activeMonthKey = 'MAYO'; 
        let activeYear = 2026;
        let openAccordionMonth = 'MAYO';
        let activeFairKey = 1;
        let selectedReviewRating = 5;
        let selectedCategory = 'Todas';
        let currentFairAvisos = [];
        let currentFavoritos = [];
        let popoverHideTimer = null;
        let popoverFairKey = null;

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
            'ENERO': { name: 'Enero', totalDays: 31 },
            'FEBRERO': { name: 'Febrero', totalDays: 28 },
            'MARZO': { name: 'Marzo', totalDays: 31 },
            'ABRIL': { name: 'Abril', totalDays: 30 },
            'MAYO': { name: 'Mayo', totalDays: 31 },
            'JUNIO': { name: 'Junio', totalDays: 30 },
            'JULIO': { name: 'Julio', totalDays: 31 },
            'AGOSTO': { name: 'Agosto', totalDays: 31 },
            'SEPTIEMBRE': { name: 'Septiembre', totalDays: 30 },
            'OCTUBRE': { name: 'Octubre', totalDays: 31 },
            'NOVIEMBRE': { name: 'Noviembre', totalDays: 30 },
            'DICIEMBRE': { name: 'Diciembre', totalDays: 31 }
        };

        const MONTH_KEYS = Object.keys(monthsData);

        // Devuelve la cantidad de días reales del mes para el año elegido.
        function getDaysInMonth(monthKey, year) {
            if (monthKey === 'FEBRERO') {
                const bisiesto = (year % 4 === 0 && year % 100 !== 0) || (year % 400 === 0);
                return bisiesto ? 29 : 28;
            }
            return monthsData[monthKey] ? monthsData[monthKey].totalDays : 30;
        }

        // Convierte "2026-05-16" en datos listos para el calendario.
        function parseFechaLocal(fecha) {
            const partes = String(fecha || '').split('-').map(Number);
            if (!partes[0] || !partes[1]) return null;
            const anio = partes[0];
            const mes = partes[1];
            const dia = partes[2] || 1;
            return {
                anio,
                mes,
                dia,
                monthKey: MONTH_KEYS[mes - 1] || 'ENERO',
                fechaObj: new Date(anio, mes - 1, dia)
            };
        }

        function formatearFechaBonita(fecha) {
            const datos = parseFechaLocal(fecha);
            if (!datos) return '';
            const mes = monthsData[datos.monthKey]?.name || '';
            return `${datos.dia} de ${mes} de ${datos.anio}`;
        }

        function formatearHora(hora) {
            return String(hora || '').slice(0, 5);
        }

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
                const response = await fetch('api/get_ferias.php', { cache: 'no-store' });
                const resultado = await response.json();

                if (resultado.status === 'success') {
                    const feriasBD = resultado.data || [];
                    const feriaSeleccionadaPrevia = activeFairKey;

                    for (let key in fairsDatabase) delete fairsDatabase[key];

                    feriasBD.forEach(f => {
                        // Parsear la fecha como local para evitar que se corra un día por zona horaria.
                        const datosFecha = parseFechaLocal(f.fecha_inicio);
                        if (!datosFecha) return;

                        const apertura = formatearHora(f.hora_apertura);
                        const cierre = formatearHora(f.hora_cierre);

                        fairsDatabase[f.id_feria] = {
                            id: f.id_feria,
                            id_organizador: Number(f.id_organizador) || null,
                            title: f.nombre_feria,
                            month: datosFecha.monthKey,
                            year: datosFecha.anio,
                            dayNumber: datosFecha.dia,
                            fecha_inicio: f.fecha_inicio,
                            fecha_fin: f.fecha_fin || f.fecha_inicio,
                            hora_apertura: apertura,
                            hora_cierre: cierre,
                            dateTime: `${formatearFechaBonita(f.fecha_inicio)} | ${apertura} - ${cierre} hs`,
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

                    // Mantener la feria elegida si todavía existe; si no, elegir la primera.
                    if (!fairsDatabase[feriaSeleccionadaPrevia]) {
                        const primerFeria = Object.values(fairsDatabase)[0];
                        if (primerFeria) {
                            activeFairKey = primerFeria.id;
                            activeMonthKey = primerFeria.month;
                            activeYear = primerFeria.year;
                            openAccordionMonth = primerFeria.month;
                        } else {
                            activeFairKey = null;
                        }
                    }
                    if (fairsDatabase[activeFairKey]) {
                        loadFairDetails(activeFairKey);
                    }

                    // Sincronizar el selector de feria del formulario del emprendedor.
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

                    // Si hay un consumidor logueado, refrescar sus favoritos.
                    if (currentUser && (currentUser.role || '').toLowerCase() === 'consumidor') {
                        cargarFavoritos();
                    }
                }
            } catch (error) {
                console.error('Error al conectar con la base de datos feriando:', error);
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

            // JERARQUÍA: si no hay sesión iniciada, primero se registra como feria
            if (!currentUser) {
                openAuthForRole('feria');
                showToast('Registrate como Feria para publicar tus ferias.');
                return;
            }

            // JERARQUÍA: solo el rol "feria" puede registrar ferias
            const rolesPermitidosRegistrarFeria = ['feria'];
            if (!rolesPermitidosRegistrarFeria.includes((currentUser.role || '').toLowerCase())) {
                showToast('Solo el rol Feria puede registrar ferias.');
                return;
            }

            const nombreFeria = (document.getElementById('feriaNombre')?.value || inputs[0]?.value || '').trim();
            if (!nombreFeria) {
                showToast('Escribí el nombre o lugar de la feria.');
                return;
            }

            const direccionFeria = (document.getElementById('feriaDireccion')?.value || '').trim() || nombreFeria;
            const fechaInicio = document.getElementById('feriaDia')?.value || '2026-11-14';
            const fechaFin = document.getElementById('feriaFechaFin')?.value || fechaInicio;

            const feriaData = {
                id_organizador: currentUser.id_usuario,
                nombre_feria: nombreFeria,
                direccion_texto: direccionFeria,
                categoria: document.getElementById('feriaCategoria')?.value || null,
                descripcion: (document.getElementById('feriaDescripcion')?.value || '').trim(),
                instagram: (document.getElementById('feriaInstagram')?.value || '').trim(),
                fecha_inicio: fechaInicio,
                fecha_fin: fechaFin,
                hora_apertura: (document.getElementById('feriaHoraApertura')?.value || '12:00'),
                hora_cierre: (document.getElementById('feriaHoraCierre')?.value || '19:00')
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

                    // Abrir directamente el mes de la feria recién publicada
                    // para que aparezca en el calendario y en "Explorar por Mes".
                    const datosFecha = parseFechaLocal(feriaData.fecha_inicio);
                    if (datosFecha) {
                        activeMonthKey = datosFecha.monthKey;
                        activeYear = datosFecha.anio;
                        openAccordionMonth = datosFecha.monthKey;
                    }
                    selectedCategory = 'Todas';
                    activeFairKey = data.id_feria || activeFairKey;

                    showSection('home');
                    await cargarFeriasDesdeBD();

                    if (data.id_feria) {
                        activeFairKey = data.id_feria;
                        loadFairDetails(activeFairKey);
                    }
                    renderAccordionMonths();
                    renderCalendar(activeMonthKey);
                } else if (data.duplicada) {
                    // La API detectó una feria exactamente igual: avisamos y no duplicamos.
                    if (data.id_feria) {
                        activeFairKey = data.id_feria;
                    }
                    showToast(data.message || 'Ya existe una feria igual. No se puede registrar dos veces.');
                    showSection('home');
                    await cargarFeriasDesdeBD();
                    if (data.id_feria && fairsDatabase[data.id_feria]) {
                        const fair = fairsDatabase[data.id_feria];
                        activeMonthKey = fair.month;
                        activeYear = fair.year;
                        openAccordionMonth = fair.month;
                        loadFairDetails(data.id_feria);
                        renderAccordionMonths();
                        renderCalendar(activeMonthKey);
                    }
                } else {
                    showToast('Error: ' + data.message);
                }
            } catch (err) {
                showToast('Error al registrar la feria.');
            }
        }

        // Convierte una fecha "AAAA-MM-DD" en la clave de mes usada por el calendario.
        function obtenerMesKeyDesdeFecha(fecha) {
            const datos = parseFechaLocal(fecha);
            return datos ? datos.monthKey : null;
        }

        // 3. REGISTRAR RESEÑA EN MYSQL
        async function submitNewReview(e) {
            e.preventDefault();
            const comment = document.getElementById('reviewCommentText').value.trim();
            if (!comment) return;

            // JERARQUÍA: si no hay sesión iniciada, no se puede publicar una reseña
            if (!currentUser) {
                switchAuthTab('login');
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
            if (!fair) {
                showToast('Elegí una feria para dejar tu reseña.');
                return;
            }

            try {
                const res = await fetch('api/guardar_resena.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        id_consumidor: currentUser.id_usuario,
                        id_feria: fair.id,
                        calificacion: selectedReviewRating,
                        comentario: comment
                    })
                });
                const data = await res.json();

                if (data.status === 'success') {
                    closeModal('reviewModal');
                    document.getElementById('reviewCommentText').value = '';
                    selectedReviewRating = 5;
                    setReviewRating(5);
                    showToast('¡Reseña publicada!');
                    await cargarResenasFeria(fair.id);
                } else {
                    showToast(data.message || 'No se pudo publicar la reseña.');
                }
            } catch (err) {
                showToast('Error al publicar la reseña.');
            }
        }

        // NAVEGACIÓN ENTRE SECCIONES
        // La página principal está disponible para todos los roles.
        // Los formularios se muestran únicamente cuando se abre su sección.
        function showSection(sectionId) {
            const home = document.getElementById('sectionHome');
            const pagina4 = document.getElementById('sectionPagina4');
            const pagina5 = document.getElementById('sectionPagina5');
            const pagina6 = document.getElementById('sectionPagina6');

            if (!home) return;

            [home, pagina4, pagina5, pagina6].forEach(sec => {
                if (sec) sec.classList.add('hidden');
            });

            if (sectionId === 'home') home.classList.remove('hidden');
            if (sectionId === 'pagina4' && pagina4) pagina4.classList.remove('hidden');
            if (sectionId === 'pagina5' && pagina5) pagina5.classList.remove('hidden');
            if (sectionId === 'pagina6' && pagina6) pagina6.classList.remove('hidden');

            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // Abre la ventana de registro con el rol ya elegido.
        function openAuthForRole(rol) {
            const rolNormalizado = (rol || 'consumidor').toLowerCase();
            switchAuthTab('signup');
            selectRole(rolNormalizado);
            openModal('authModal');
        }

        // Botón "¿Sos Emprendedor? Sumate".
        // - Invitado: abre el registro como emprendedor.
        // - Emprendedor: muestra el formulario de postulación.
        // - Otro rol: explica que necesita una cuenta de emprendedor.
        function showEmprendedorRegistration() {
            if (!currentUser) {
                openAuthForRole('emprendedor');
                showToast('Creá tu cuenta de emprendedor para sumarte.');
                return;
            }

            const rolActual = (currentUser.role || '').toLowerCase();

            if (rolActual === 'emprendedor') {
                showSection('pagina4');
                return;
            }

            showToast('Para postularte como emprendedor necesitás una cuenta con rol Emprendedor.');
        }

        // Registrar feria: solo el rol "feria" puede hacerlo.
        // Un invitado primero crea su cuenta de feria.
        function showFairRegistration() {
            if (!currentUser) {
                openAuthForRole('feria');
                showToast('Registrate como Feria para publicar tus ferias.');
                return;
            }

            const rolActual = (currentUser.role || '').toLowerCase();

            if (rolActual === 'feria') {
                showSection('pagina5');
                return;
            }

            showToast('Solo el rol Feria puede registrar ferias.');
        }

        // Perfil / aviso del emprendedor: lugar, hora, puesto, Instagram y foto.
        function showAvisoForm() {
            if (!currentUser) {
                switchAuthTab('login');
                openModal('authModal');
                showToast('Iniciá sesión como emprendedor para publicar tu aviso.');
                return;
            }

            if ((currentUser.role || '').toLowerCase() !== 'emprendedor') {
                showToast('Solo el rol Emprendedor puede publicar este aviso.');
                return;
            }

            showSection('pagina6');
            prefillAvisoDesdeFeria();
        }

        // Precarga los datos de la feria elegida en el formulario del emprendedor.
        function prefillAvisoDesdeFeria() {
            const select = document.getElementById('avisoFeriaId');
            const fair = fairsDatabase[activeFairKey];
            if (select && fair && !select.value) {
                select.value = fair.id;
            }
            const lugar = document.getElementById('avisoLugar');
            if (lugar && !lugar.value && fair) {
                lugar.value = fair.address || '';
            }
        }

        // RENDER DEL ACORDEÓN DE MESES
        function renderAccordionMonths() {
            const container = document.getElementById('accordionMonthsContainer');
            if (!container) return;
            container.innerHTML = '';

            MONTH_KEYS.forEach(mKey => {
                const mInfo = monthsData[mKey];
                const isOpen = (mKey === openAccordionMonth);
                const monthFairs = Object.values(fairsDatabase)
                    .filter(f => f.month === mKey && f.year === activeYear && (selectedCategory === 'Todas' || f.categoria === selectedCategory))
                    .sort((a, b) => a.dayNumber - b.dayNumber);

                const itemHtml = `
                    <div class="rounded-2xl overflow-hidden border ${isOpen ? 'bg-orange-600/20 border-orange-500/50' : 'bg-white/5 border-white/10'} transition-all">
                        <button type="button" onclick="toggleMonthAccordion('${mKey}')" class="w-full px-4 py-3 text-left font-extrabold text-sm flex items-center justify-between ${isOpen ? 'bg-orange-600 text-white shadow-md' : 'text-slate-300 hover:bg-white/10'} transition-colors">
                            <span class="flex items-center gap-2">
                                <i class="fa-solid fa-calendar-day ${isOpen ? 'text-amber-300' : 'text-slate-500'}"></i>
                                ${mInfo.name} ${activeYear}
                            </span>
                            <i class="fa-solid ${isOpen ? 'fa-chevron-down text-white' : 'fa-chevron-right text-xs opacity-50'}"></i>
                        </button>

                        ${isOpen ? `
                            <div class="p-2 space-y-1 bg-slate-950/70">
                                ${monthFairs.length > 0 ? monthFairs.map(fair => {
                                    const isSelected = (String(fair.id) === String(activeFairKey));
                                    return `
                                        <button type="button" onclick="selectFairFromList('${fair.id}')" class="w-full text-left px-3.5 py-2.5 rounded-xl text-xs font-bold flex items-center justify-between transition-all ${isSelected ? 'bg-amber-400 text-slate-950 shadow-md' : 'text-slate-300 hover:bg-white/10'}">
                                            <span class="flex items-center gap-1.5 truncate pr-2">
                                                ${fair.isFavorite ? '<i class="fa-solid fa-star text-amber-500 shrink-0"></i>' : '<i class="fa-solid fa-circle text-[6px] text-orange-400 shrink-0"></i>'}
                                                <span class="truncate">${escapeHtml(fair.title)}</span>
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

        // Abre y CIERRA el acordeón al tocar el mismo mes.
        function toggleMonthAccordion(mKey) {
            if (openAccordionMonth === mKey) {
                openAccordionMonth = null;
                renderAccordionMonths();
                return;
            }

            openAccordionMonth = mKey;
            activeMonthKey = mKey;

            const firstFairInMonth = Object.values(fairsDatabase)
                .filter(f => f.month === mKey && f.year === activeYear)
                .sort((a, b) => a.dayNumber - b.dayNumber)[0];

            if (firstFairInMonth) {
                activeFairKey = firstFairInMonth.id;
            }

            renderAccordionMonths();
            renderCalendar(activeMonthKey);
            if (firstFairInMonth) loadFairDetails(activeFairKey);
        }

        function selectFairFromList(fairId) {
            const fair = fairsDatabase[fairId];
            activeFairKey = fairId;
            if (fair) {
                activeMonthKey = fair.month;
                activeYear = fair.year;
                openAccordionMonth = fair.month;
                const selectorAnio = document.getElementById('yearSelector');
                if (selectorAnio) selectorAnio.value = String(activeYear);
            }
            renderAccordionMonths();
            loadFairDetails(fairId);
            renderCalendar(activeMonthKey);
        }

        // NAVEGACIÓN ENTRE MESES DEL CALENDARIO (también cambia de año)
        function navigateCalendarMonth(offset) {
            const currentIndex = MONTH_KEYS.indexOf(activeMonthKey);
            let newIndex = currentIndex + offset;

            if (newIndex < 0) {
                newIndex = MONTH_KEYS.length - 1;
                activeYear -= 1;
            } else if (newIndex >= MONTH_KEYS.length) {
                newIndex = 0;
                activeYear += 1;
            }

            const newMonthKey = MONTH_KEYS[newIndex];
            activeMonthKey = newMonthKey;
            openAccordionMonth = newMonthKey;

            const selectorAnio = document.getElementById('yearSelector');
            if (selectorAnio) selectorAnio.value = String(activeYear);

            renderCalendar(newMonthKey);
            renderAccordionMonths();

            // Sincronizar la feria seleccionada con el mes visualizado.
            const firstFairInMonth = Object.values(fairsDatabase)
                .filter(f => f.month === newMonthKey && f.year === activeYear)
                .sort((a, b) => a.dayNumber - b.dayNumber)[0];
            if (firstFairInMonth) {
                activeFairKey = firstFairInMonth.id;
                loadFairDetails(activeFairKey);
            }
        }

        function changeCalendarYear(year) {
            const anio = parseInt(year, 10);
            if (!anio) return;
            activeYear = anio;
            renderCalendar(activeMonthKey);
            renderAccordionMonths();
        }

        // RENDER DEL CALENDARIO
        function renderCalendar(mKey) {
            const mInfo = monthsData[mKey];
            if (!mInfo) return;

            document.getElementById('calendarMonthTitle').textContent = `${mInfo.name} ${activeYear}`;
            document.getElementById('calendarBadge').textContent = mInfo.name;

            const grid = document.getElementById('calendarDaysGrid');
            grid.innerHTML = '';

            const monthIndex = MONTH_KEYS.indexOf(mKey);
            const startOffset = new Date(activeYear, monthIndex, 1).getDay();
            const prevMonthKey = MONTH_KEYS[(monthIndex + 11) % 12];
            const prevMonthYear = monthIndex === 0 ? activeYear - 1 : activeYear;
            const prevMonthTotalDays = getDaysInMonth(prevMonthKey, prevMonthYear);

            // Días de relleno del mes anterior.
            for (let i = 0; i < startOffset; i++) {
                const dayNumber = prevMonthTotalDays - startOffset + 1 + i;
                grid.insertAdjacentHTML('beforeend', `
                    <div class="calendar-day-cell bg-white/5 text-slate-600 border-white/5 opacity-40">
                        <span class="text-xs sm:text-sm font-semibold">${dayNumber}</span>
                    </div>
                `);
            }

            const monthFairs = Object.values(fairsDatabase)
                .filter(f => f.month === mKey && f.year === activeYear && (selectedCategory === 'Todas' || f.categoria === selectedCategory))
                .sort((a, b) => a.dayNumber - b.dayNumber);

            const totalDays = getDaysInMonth(mKey, activeYear);
            for (let d = 1; d <= totalDays; d++) {
                const fairsOnDay = monthFairs.filter(f => f.dayNumber === d);
                const fairOnDay = fairsOnDay[0];

                if (fairOnDay) {
                    const isFav = fairOnDay.isFavorite;
                    const isSelected = (String(fairOnDay.id) === String(activeFairKey));
                    const extra = fairsOnDay.length > 1 ? `<span class="block text-[8px] font-black opacity-90">+${fairsOnDay.length - 1} más</span>` : '';

                    grid.insertAdjacentHTML('beforeend', `
                        <button type="button"
                            onmouseenter="showFairPopover('${fairOnDay.id}', event)"
                            onmouseleave="scheduleHideFairPopover()"
                            onclick="handleCalendarFairClick('${fairOnDay.id}', event)"
                            onfocus="showFairPopover('${fairOnDay.id}', event)"
                            class="calendar-day-cell ${
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
                                    ${escapeHtml(fairOnDay.title)}
                                </span>
                                ${extra}
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

        // PANEL CHIQUITO AL PASAR EL MOUSE / TOCAR UNA FERIA DEL CALENDARIO
        function handleCalendarFairClick(fairId, event) {
            const rect = event && event.currentTarget ? event.currentTarget.getBoundingClientRect() : null;
            selectFairFromList(fairId);
            showFairPopoverAt(fairId, rect);
        }

        function showFairPopover(fairId, event) {
            const rect = event && event.currentTarget ? event.currentTarget.getBoundingClientRect() : null;
            showFairPopoverAt(fairId, rect);
        }

        function showFairPopoverAt(fairId, rect) {
            const fair = fairsDatabase[fairId];
            const popover = document.getElementById('calendarFairPopover');
            if (!fair || !popover) return;

            cancelHideFairPopover();
            popoverFairKey = fairId;

            popover.innerHTML = `
                <div class="flex items-start justify-between gap-2 border-b border-white/10 pb-2">
                    <div class="min-w-0">
                        <p class="text-[10px] font-bold text-amber-400 uppercase tracking-widest">Feria</p>
                        <h4 class="font-display font-black text-sm text-white leading-tight truncate">${escapeHtml(fair.title)}</h4>
                    </div>
                    <button type="button" onclick="hideFairPopover()" class="text-slate-400 hover:text-white shrink-0"><i class="fa-solid fa-xmark"></i></button>
                </div>
                <div class="space-y-1.5 text-[11px] text-slate-200">
                    <p><i class="fa-regular fa-clock text-amber-400 mr-1.5"></i>${escapeHtml(fair.hora_apertura)} - ${escapeHtml(fair.hora_cierre)} hs</p>
                    <p><i class="fa-solid fa-location-dot text-amber-400 mr-1.5"></i>${escapeHtml(fair.address)}</p>
                    <p><i class="fa-solid fa-calendar-day text-amber-400 mr-1.5"></i>${escapeHtml(formatearFechaBonita(fair.fecha_inicio))}</p>
                </div>
                <button type="button" onclick="openAsistentesFromPopover('${fair.id}')" class="mt-3 w-full bg-amber-400 hover:bg-amber-300 text-slate-950 font-black px-3 py-2 rounded-xl text-[10px] uppercase tracking-wide shadow flex items-center justify-center gap-1.5 transition-all">
                    <i class="fa-solid fa-users"></i> Quiénes asisten
                </button>
            `;

            popover.classList.remove('hidden');

            // Posicionar cerca de la celda y dentro de la pantalla.
            const ancho = 280;
            const altoAprox = 190;
            const margen = 12;
            let left = rect ? rect.left + rect.width / 2 - ancho / 2 : (window.innerWidth - ancho) / 2;
            let top = rect ? rect.bottom + 8 : 120;

            left = Math.max(margen, Math.min(left, window.innerWidth - ancho - margen));
            if (rect && rect.bottom + altoAprox > window.innerHeight) {
                top = Math.max(margen, rect.top - altoAprox - 8);
            }

            popover.style.width = `${ancho}px`;
            popover.style.left = `${left}px`;
            popover.style.top = `${top}px`;
        }

        function scheduleHideFairPopover() {
            cancelHideFairPopover();
            popoverHideTimer = setTimeout(() => {
                hideFairPopover();
            }, 280);
        }

        function cancelHideFairPopover() {
            if (popoverHideTimer) {
                clearTimeout(popoverHideTimer);
                popoverHideTimer = null;
            }
        }

        function hideFairPopover() {
            cancelHideFairPopover();
            const popover = document.getElementById('calendarFairPopover');
            if (popover) popover.classList.add('hidden');
            popoverFairKey = null;
        }

        function openAsistentesFromPopover(fairId) {
            hideFairPopover();
            activeFairKey = fairId;
            const fair = fairsDatabase[fairId];
            if (fair) {
                activeMonthKey = fair.month;
                activeYear = fair.year;
                openAccordionMonth = fair.month;
            }
            loadFairDetails(fairId);
            openAsistentesModal(fairId);
        }

        // CARGA DE DETALLES
        function loadFairDetails(fairId) {
            const fair = fairsDatabase[fairId];
            if (!fair) return;

            document.getElementById('fairTitle').textContent = fair.title;
            document.getElementById('fairDateTime').innerHTML = `<i class="fa-regular fa-clock mr-1"></i> ${escapeHtml(fair.dateTime)}`;
            document.getElementById('fairHandle').textContent = fair.handle;
            document.getElementById('fairAddressLabel').textContent = fair.address;
            document.getElementById('fairGoogleMap').src = fair.mapUrl;

            // Link de Instagram de la feria / organizador.
            const fairInsta = document.getElementById('fairInsta');
            if (fairInsta) {
                const usuarioInsta = String(fair.handle || '').replace(/^@/, '').trim();
                if (usuarioInsta) {
                    fairInsta.href = `https://instagram.com/${encodeURIComponent(usuarioInsta)}`;
                    fairInsta.classList.remove('hidden');
                } else {
                    fairInsta.classList.add('hidden');
                }
            }

            // Botón para que el organizador elimine su propia feria.
            const fairDeleteBtn = document.getElementById('fairDeleteBtn');
            if (fairDeleteBtn) {
                const esOrganizador = currentUser
                    && (currentUser.role || '').toLowerCase() === 'feria'
                    && Number(fair.id_organizador) === Number(currentUser.id_usuario);
                fairDeleteBtn.classList.toggle('hidden', !esOrganizador);
            }

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

            if (favBtn && favBadge) {
                if (fair.isFavorite) {
                    favBtn.className = "w-12 h-12 rounded-2xl bg-amber-400 text-slate-950 font-extrabold flex items-center justify-center text-xl shadow-xl transition-all shrink-0 border-2 border-white animate-bounce";
                    favBadge.classList.remove('hidden');
                } else {
                    favBtn.className = "w-12 h-12 rounded-2xl bg-slate-900 hover:bg-slate-950 text-slate-400 hover:text-amber-400 font-extrabold flex items-center justify-center text-xl shadow-lg transition-all shrink-0 border border-white/10";
                    favBadge.classList.add('hidden');
                }
            }

            renderReviewsList(fair);
            cargarResenasFeria(fair.id);
            cargarAsistentesFeria(fair.id);
        }

        // CARGA DE RESEÑAS DESDE LA BASE DE DATOS
        async function cargarResenasFeria(fairId) {
            const fair = fairsDatabase[fairId];
            if (!fair) return;

            try {
                const res = await fetch(`api/get_resenas.php?id_feria=${encodeURIComponent(fairId)}`);
                const data = await res.json();

                if (data.status !== 'success') return;

                fair.reviews = (data.data || []).map(r => ({
                    id: Number(r.id_resena),
                    id_consumidor: Number(r.id_consumidor),
                    author: r.autor || 'Consumidor',
                    rating: parseInt(r.calificacion, 10) || 0,
                    timeAgo: calcularTiempoTranscurrido(r.fecha_resena),
                    text: r.comentario || ''
                }));

                // Solo volvemos a dibujar si esa feria sigue siendo la elegida.
                if (String(activeFairKey) === String(fairId)) {
                    renderReviewsList(fair);
                }
            } catch (err) {
                console.error('No se pudieron cargar las reseñas:', err);
            }
        }

        // Convierte la fecha de la reseña en un texto como "hace 2 días".
        function calcularTiempoTranscurrido(fecha) {
            if (!fecha) return '';
            const fechaObj = new Date(String(fecha).replace(' ', 'T'));
            if (isNaN(fechaObj.getTime())) return '';

            const segundos = Math.max(0, Math.floor((Date.now() - fechaObj.getTime()) / 1000));
            if (segundos < 60) return 'hace un momento';

            const minutos = Math.floor(segundos / 60);
            if (minutos < 60) return `hace ${minutos} min`;

            const horas = Math.floor(minutos / 60);
            if (horas < 24) return `hace ${horas} h`;

            const dias = Math.floor(horas / 24);
            if (dias < 30) return `hace ${dias} ${dias === 1 ? 'día' : 'días'}`;

            return fechaObj.toLocaleDateString('es-ES', {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            });
        }

        async function toggleFairFavorite() {
            const fair = fairsDatabase[activeFairKey];
            if (!fair) return;

            // JERARQUÍA: solo los consumidores pueden guardar favoritos
            if (!currentUser) {
                switchAuthTab('login');
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
                    body: JSON.stringify({
                        id_feria: fair.id,
                        id_consumidor: currentUser.id_usuario
                    })
                });
                const data = await res.json();

                if (data.status === 'success') {
                    fair.isFavorite = data.is_favorito;
                    showToast(fair.isFavorite ? `¡${fair.title} en FAVORITAS! ⭐` : `Se quitó ${fair.title} de favoritas.`);

                    loadFairDetails(activeFairKey);
                    renderAccordionMonths();
                    renderCalendar(activeMonthKey);
                    await cargarFavoritos(false);
                } else {
                    showToast('Error: ' + data.message);
                }
            } catch (err) {
                showToast('Error al guardar la feria en favoritas.');
            }
        }

        // CARGAR FAVORITOS DE UN CONSUMIDOR DESDE LA BD
        async function cargarFavoritos(redibujar = true) {
            if (!currentUser) {
                currentFavoritos = [];
                return;
            }
            if ((currentUser.role || '').toLowerCase() !== 'consumidor') {
                currentFavoritos = [];
                return;
            }

            try {
                const res = await fetch(`api/get_favoritos.php?id_consumidor=${encodeURIComponent(currentUser.id_usuario)}`, { cache: 'no-store' });
                const data = await res.json();

                if (data.status === 'success') {
                    currentFavoritos = data.data || [];
                    const idsFavoritos = currentFavoritos.map(f => Number(f.id_feria));
                    Object.values(fairsDatabase).forEach(f => { f.isFavorite = idsFavoritos.includes(Number(f.id)); });

                    if (redibujar) {
                        renderAccordionMonths();
                        renderCalendar(activeMonthKey);
                        if (fairsDatabase[activeFairKey]) loadFairDetails(activeFairKey);
                    }
                    renderFavoritosList();
                }
            } catch (err) { /* Sin conexión a BD: se mantiene el estado local */ }
        }

        // MODAL "MIS FAVORITOS" DEL CONSUMIDOR
        async function openFavoritosModal() {
            if (!currentUser) {
                switchAuthTab('login');
                openModal('authModal');
                showToast('Iniciá sesión para ver tus favoritos.');
                return;
            }
            if ((currentUser.role || '').toLowerCase() !== 'consumidor') {
                showToast('Los favoritos son una función del perfil consumidor.');
                return;
            }

            openModal('favoritosModal');
            const container = document.getElementById('favoritosList');
            if (container) {
                container.innerHTML = '<div class="text-center py-8 text-slate-400 text-xs italic"><i class="fa-solid fa-spinner fa-spin mr-2"></i>Cargando favoritos...</div>';
            }
            await cargarFavoritos(false);
        }

        function renderFavoritosList() {
            const container = document.getElementById('favoritosList');
            if (!container) return;

            if (currentFavoritos.length === 0) {
                container.innerHTML = `<div class="text-center py-10 text-slate-400 text-xs italic">
                    <i class="fa-regular fa-star text-3xl text-slate-600 block mb-2"></i>
                    Todavía no guardaste ferias en favoritos.
                </div>`;
                return;
            }

            container.innerHTML = currentFavoritos.map(f => {
                const datos = parseFechaLocal(f.fecha_inicio);
                const fecha = datos ? `${datos.dia} de ${monthsData[datos.monthKey].name} de ${datos.anio}` : '';
                const hora = `${formatearHora(f.hora_apertura)} - ${formatearHora(f.hora_cierre)} hs`;

                return `
                    <article class="bg-slate-950/70 rounded-2xl border border-white/10 overflow-hidden">
                        ${f.imagen_url ? `<img src="${escapeHtml(f.imagen_url)}" alt="${escapeHtml(f.nombre_feria)}" class="w-full h-32 object-cover">` : ''}
                        <div class="p-3 space-y-2">
                            <div class="flex items-start justify-between gap-2">
                                <div class="min-w-0">
                                    <h4 class="text-sm font-black text-white truncate">${escapeHtml(f.nombre_feria)}</h4>
                                    <p class="text-[11px] text-amber-300 font-semibold">${escapeHtml(fecha)} · ${escapeHtml(hora)}</p>
                                </div>
                                <button type="button" onclick="quitarFavorito(${Number(f.id_feria)})" title="Quitar de favoritos" class="w-8 h-8 rounded-full bg-red-500/20 hover:bg-red-500 text-red-300 hover:text-white flex items-center justify-center shrink-0 transition-colors">
                                    <i class="fa-solid fa-trash text-xs"></i>
                                </button>
                            </div>
                            <p class="text-[11px] text-slate-400 truncate"><i class="fa-solid fa-location-dot mr-1 text-amber-400"></i>${escapeHtml(f.direccion_texto)}</p>
                            <button type="button" onclick="irAFeriaDesdeFavoritos(${Number(f.id_feria)})" class="w-full bg-white/10 hover:bg-orange-600 text-white font-bold py-2 rounded-xl text-[11px] transition-colors">
                                Ver feria en el calendario
                            </button>
                        </div>
                    </article>
                `;
            }).join('');
        }

        async function quitarFavorito(fairId) {
            if (!currentUser) return;
            try {
                const res = await fetch('api/guardar_favorito.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id_feria: fairId, id_consumidor: currentUser.id_usuario })
                });
                const data = await res.json();
                if (data.status === 'success') {
                    showToast('Se quitó de favoritos.');
                    await cargarFavoritos(false);
                    renderAccordionMonths();
                    renderCalendar(activeMonthKey);
                    if (fairsDatabase[activeFairKey]) loadFairDetails(activeFairKey);
                } else {
                    showToast(data.message || 'No se pudo quitar de favoritos.');
                }
            } catch (err) {
                showToast('Error al quitar de favoritos.');
            }
        }

        function irAFeriaDesdeFavoritos(fairId) {
            closeModal('favoritosModal');
            selectFairFromList(fairId);
            showSection('home');
            const seccion = document.getElementById('calendario');
            if (seccion) seccion.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }

        // ELIMINAR FERIA (SOLO EL ORGANIZADOR)
        async function eliminarFeriaActual() {
            const fair = fairsDatabase[activeFairKey];
            if (!fair) return;

            if (!currentUser) {
                switchAuthTab('login');
                openModal('authModal');
                showToast('Iniciá sesión como organizador para eliminar una feria.');
                return;
            }

            if (!confirm(`¿Seguro que querés eliminar "${fair.title}"? Esta acción no se puede deshacer.`)) {
                return;
            }

            try {
                const res = await fetch('api/eliminar_feria.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id_feria: fair.id, id_usuario: currentUser.id_usuario })
                });
                const data = await res.json();

                if (data.status === 'success') {
                    showToast('Feria eliminada correctamente.');
                    delete fairsDatabase[fair.id];
                    activeFairKey = null;
                    await cargarFeriasDesdeBD();
                    renderAccordionMonths();
                    renderCalendar(activeMonthKey);
                } else {
                    showToast(data.message || 'No se pudo eliminar la feria.');
                }
            } catch (err) {
                showToast('Error al eliminar la feria.');
            }
        }

        // ELIMINAR COMENTARIO (AUTOR O ORGANIZADOR)
        async function eliminarResena(idResena) {
            if (!currentUser) {
                switchAuthTab('login');
                openModal('authModal');
                showToast('Iniciá sesión para eliminar tu comentario.');
                return;
            }
            if (!confirm('¿Querés eliminar este comentario?')) return;

            try {
                const res = await fetch('api/eliminar_resena.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id_resena: idResena, id_usuario: currentUser.id_usuario })
                });
                const data = await res.json();

                if (data.status === 'success') {
                    showToast('Comentario eliminado.');
                    await cargarResenasFeria(activeFairKey);
                } else {
                    showToast(data.message || 'No se pudo eliminar el comentario.');
                }
            } catch (err) {
                showToast('Error al eliminar el comentario.');
            }
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
                    const insta = a.instagram
                        ? `<a href="https://instagram.com/${encodeURIComponent(String(a.instagram).replace(/^@/, ''))}" target="_blank" class="inline-flex items-center gap-1 text-[10px] font-bold text-pink-300 hover:text-pink-200"><i class="fa-brands fa-instagram"></i> ${escapeHtml(a.instagram)}</a>`
                        : '';
                    const ubicacion = (a.lugar || a.hora || a.puesto)
                        ? `<div class="flex flex-wrap gap-2 text-[10px] text-amber-200 font-semibold">
                                ${a.lugar ? `<span><i class="fa-solid fa-location-dot mr-1"></i>${escapeHtml(a.lugar)}</span>` : ''}
                                ${a.hora ? `<span><i class="fa-regular fa-clock mr-1"></i>${escapeHtml(a.hora)}</span>` : ''}
                                ${a.puesto ? `<span><i class="fa-solid fa-store mr-1"></i>Puesto ${escapeHtml(a.puesto)}</span>` : ''}
                           </div>`
                        : '';

                    return `
                        <article class="bg-slate-950/70 rounded-2xl border border-white/10 overflow-hidden flex flex-col">
                            ${imagen}
                            <div class="p-4 space-y-3 flex-1 flex flex-col">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-amber-400 to-orange-500 text-slate-950 font-black text-xs flex items-center justify-center shrink-0">${inicial}</div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs font-bold text-white truncate">${escapeHtml(a.emprendedor)}</p>
                                        <p class="text-[10px] text-slate-400">${escapeHtml(fechaTexto)}${a.nombre_feria ? ' · ' + escapeHtml(a.nombre_feria) : ''}</p>
                                    </div>
                                    ${insta}
                                </div>
                                <p class="text-xs text-slate-200 italic leading-relaxed">"${escapeHtml(a.mensaje)}"</p>
                                ${ubicacion}
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
                        lugar: a.lugar,
                        hora: a.hora,
                        puesto: a.puesto,
                        instagram: a.instagram,
                        mensajes: []
                    });
                }
                const grupo = grupos.get(id);
                // Mostramos los últimos datos cargados del emprendedor.
                grupo.imagen_url = a.imagen_url || grupo.imagen_url;
                grupo.lugar = a.lugar || grupo.lugar;
                grupo.hora = a.hora || grupo.hora;
                grupo.puesto = a.puesto || grupo.puesto;
                grupo.instagram = a.instagram || grupo.instagram;
                grupo.mensajes.push(a);
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

        async function openAsistentesModal(fairId) {
            const id = fairId || activeFairKey;
            const fair = fairsDatabase[id];
            if (!fair) {
                showToast('Elegí una feria para ver sus asistentes.');
                return;
            }

            activeFairKey = id;
            const nombre = document.getElementById('asistentesFairName');
            if (nombre) nombre.textContent = fair.title;

            const container = document.getElementById('asistentesList');
            if (container) {
                container.innerHTML = '<div class="text-center py-8 text-slate-400 text-xs italic"><i class="fa-solid fa-spinner fa-spin mr-2"></i>Cargando asistentes...</div>';
            }
            openModal('asistentesModal');
            await cargarAsistentesFeria(id);
            renderAsistentesLista();
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
                const detalles = [a.lugar, a.hora ? `${a.hora} hs` : '', a.puesto ? `Puesto ${a.puesto}` : '']
                    .filter(Boolean).map(escapeHtml).join(' · ');
                return `
                    <button type="button" onclick="verMensajesEmprendedor(${a.id_emprendedor})" class="w-full text-left bg-slate-950/70 hover:bg-slate-800 border border-white/10 hover:border-amber-400/50 rounded-2xl p-3 flex items-center gap-3 transition-all">
                        ${a.imagen_url
                            ? `<img src="${escapeHtml(a.imagen_url)}" alt="${escapeHtml(a.emprendedor)}" class="w-11 h-11 rounded-full object-cover border-2 border-amber-400/60 shrink-0">`
                            : `<span class="w-11 h-11 rounded-full bg-gradient-to-br from-amber-400 to-orange-500 text-slate-950 font-black flex items-center justify-center border-2 border-amber-400/60 shrink-0">${escapeHtml((a.emprendedor || 'E').charAt(0).toUpperCase())}</span>`}
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-bold text-white truncate">${escapeHtml(a.emprendedor)}</p>
                            ${detalles ? `<p class="text-[11px] text-amber-300 truncate">${detalles}</p>` : ''}
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
            if (feriaLabel) {
                const detalles = [primero.nombre_feria, primero.lugar, primero.hora ? `${primero.hora} hs` : '', primero.puesto ? `Puesto ${primero.puesto}` : '']
                    .filter(Boolean).join(' · ');
                feriaLabel.textContent = detalles;
            }

            document.getElementById('emprendedorMensajes').innerHTML = mensajes.map(m => {
                const fecha = m.fecha_creacion ? new Date(String(m.fecha_creacion).replace(' ', 'T')) : null;
                const fechaTexto = fecha ? fecha.toLocaleDateString('es-ES', { day: '2-digit', month: 'long', year: 'numeric' }) : '';
                const insta = m.instagram
                    ? `<a href="https://instagram.com/${encodeURIComponent(String(m.instagram).replace(/^@/, ''))}" target="_blank" class="inline-flex items-center gap-1 text-[11px] font-bold text-pink-300 hover:text-pink-200"><i class="fa-brands fa-instagram"></i> ${escapeHtml(m.instagram)}</a>`
                    : '';
                return `
                    <article class="bg-slate-950/70 rounded-2xl border border-white/10 overflow-hidden">
                        ${m.imagen_url ? `<img src="${escapeHtml(m.imagen_url)}" alt="Foto de ${escapeHtml(m.emprendedor)}" class="w-full h-56 object-cover border-b border-white/10">` : ''}
                        <div class="p-4 space-y-2">
                            <div class="flex items-center justify-between gap-2">
                                <p class="text-xs text-slate-400 font-semibold">${escapeHtml(fechaTexto)}</p>
                                ${insta}
                            </div>
                            <p class="text-sm text-slate-200 italic leading-relaxed">"${escapeHtml(m.mensaje)}"</p>
                            ${(m.lugar || m.hora || m.puesto) ? `<div class="flex flex-wrap gap-2 text-[10px] text-amber-200 font-semibold">
                                ${m.lugar ? `<span><i class="fa-solid fa-location-dot mr-1"></i>${escapeHtml(m.lugar)}</span>` : ''}
                                ${m.hora ? `<span><i class="fa-regular fa-clock mr-1"></i>${escapeHtml(m.hora)}</span>` : ''}
                                ${m.puesto ? `<span><i class="fa-solid fa-store mr-1"></i>Puesto ${escapeHtml(m.puesto)}</span>` : ''}
                            </div>` : ''}
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
                switchAuthTab('login');
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
                formData.append('id_emprendedor', currentUser.id_usuario);
                formData.append('lugar', document.getElementById('avisoLugar')?.value.trim() || '');
                formData.append('hora', document.getElementById('avisoHora')?.value.trim() || '');
                formData.append('puesto', document.getElementById('avisoPuesto')?.value.trim() || '');
                formData.append('instagram', document.getElementById('avisoInstagram')?.value.trim() || '');
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
                    // Refrescar la lista de asistentes de la feria elegida.
                    if (String(idFeria) === String(activeFairKey)) {
                        cargarAsistentesFeria(activeFairKey);
                    }
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
            if (!reviewsContainer || !reviewsBadge) return;
            
            reviewsBadge.textContent = `${fair.reviews.length} ${fair.reviews.length === 1 ? 'reseña' : 'reseñas'}`;
            reviewsContainer.innerHTML = '';

            if (fair.reviews.length === 0) {
                reviewsContainer.innerHTML = `<div class="text-center py-6 text-slate-400 text-xs italic">Aún no hay comentarios sobre esta feria.</div>`;
                return;
            }

            fair.reviews.forEach((rev) => {
                const starsHtml = Array(5).fill(0).map((_, i) => `<i class="fa-solid fa-star ${i < rev.rating ? 'text-amber-400' : 'text-slate-600'}"></i>`).join('');
                const esMiComentario = currentUser && Number(rev.id_consumidor) === Number(currentUser.id_usuario);
                const esMiFeria = currentUser
                    && (currentUser.role || '').toLowerCase() === 'feria'
                    && Number(fair.id_organizador) === Number(currentUser.id_usuario);
                const puedeBorrar = rev.id && (esMiComentario || esMiFeria);

                const itemHtml = `
                    <div class="p-3.5 rounded-xl bg-slate-900/90 border border-white/10 space-y-2">
                        <div class="flex items-center justify-between gap-2">
                            <div class="flex items-center gap-2 min-w-0">
                                <div class="w-7 h-7 rounded-full bg-gradient-to-br from-amber-400 to-orange-500 text-slate-950 font-black text-xs flex items-center justify-center shrink-0">
                                    ${escapeHtml((rev.author || 'C').charAt(0).toUpperCase())}
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-bold text-white truncate">${escapeHtml(rev.author)}</p>
                                    <div class="flex text-[9px] gap-0.5">${starsHtml}</div>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                <span class="text-[10px] text-slate-400">${escapeHtml(rev.timeAgo)}</span>
                                ${puedeBorrar ? `<button type="button" onclick="eliminarResena(${Number(rev.id)})" title="Eliminar comentario" class="w-6 h-6 rounded-full bg-red-500/20 hover:bg-red-500 text-red-300 hover:text-white flex items-center justify-center transition-colors"><i class="fa-solid fa-trash text-[9px]"></i></button>` : ''}
                            </div>
                        </div>
                        <p class="text-xs text-slate-200 italic leading-relaxed">"${escapeHtml(rev.text)}"</p>
                    </div>
                `;
                reviewsContainer.insertAdjacentHTML('beforeend', itemHtml);
            });
        }

        function openReviewModal() {
            if (!currentUser) {
                switchAuthTab('login');
                openModal('authModal');
                showToast('Iniciá sesión como consumidor para publicar una reseña.');
                return;
            }

            if ((currentUser.role || '').toLowerCase() !== 'consumidor') {
                showToast('Solo los usuarios con rol de consumidor pueden publicar reseñas.');
                return;
            }

            setReviewRating(selectedReviewRating);
            openModal('reviewModal');
        }

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

            // Invitado: abrir directamente la pestaña "Iniciar Sesión".
            switchAuthTab('login');
            openModal('authModal');
        }

        // El cartel "Sin sesión iniciada" del menú lateral lleva al login.
        function handleDrawerProfileClick() {
            if (!currentUser) {
                switchAuthTab('login');
                openModal('authModal');
                toggleDrawer();
                showToast('Iniciá sesión o creá tu cuenta para empezar.');
                return;
            }

            if ((currentUser.role || '').toLowerCase() === 'consumidor') {
                toggleDrawer();
                openFavoritosModal();
            }
        }

        async function closeSession() {
            // Cerrar la sesión en el servidor
            try {
                await fetch('api/logout_usuario.php', { method: 'POST' });
            } catch (err) { /* Si falla el logout del servidor, igual limpiamos la sesión local */ }

            currentUser = null;
            currentFavoritos = [];
            saveCurrentUserSession(null);
            updateAuthUI();
            renderFavoritosList();
            showToast('Has cerrado sesión correctamente.');
            const authMenu = document.getElementById('authMenu');
            if (authMenu) authMenu.classList.add('hidden');
        }

        function updateAuthUI() {
            const mainAuthText = document.getElementById('mainAuthText');
            const mainAuthIcon = document.getElementById('mainAuthIcon');

            if (currentUser) {
                if (mainAuthText) mainAuthText.textContent = `¡Hola, ${currentUser.name}!`;
                if (mainAuthIcon) mainAuthIcon.className = 'fa-solid fa-circle-user text-sm';

                // Sincronizar el menú de cuenta (authMenu)
                const authMenu = document.getElementById('authMenu');
                const authMenuUser = document.getElementById('authMenuUserName');
                if (authMenu) authMenu.classList.remove('hidden');
                if (authMenuUser) authMenuUser.textContent = currentUser.name;
            } else {
                if (mainAuthText) mainAuthText.textContent = 'Registrarse / Iniciar Sesión';
                if (mainAuthIcon) mainAuthIcon.className = 'fa-solid fa-user-plus text-sm';

                const authMenu = document.getElementById('authMenu');
                const authMenuUser = document.getElementById('authMenuUserName');
                if (authMenu) authMenu.classList.add('hidden');
                if (authMenuUser) authMenuUser.textContent = 'Invitado';
            }

            // Sincronizar el perfil del menú lateral (drawer)
            const drawerProfileBox = document.getElementById('drawerProfileBox');
            const drawerUserName = document.getElementById('drawerUserName');
            const drawerUserRole = document.getElementById('drawerUserRole');
            const drawerFavoritosBtn = document.getElementById('drawerFavoritosBtn');

            if (currentUser) {
                if (drawerProfileBox) {
                    drawerProfileBox.classList.add('cursor-pointer');
                    drawerProfileBox.title = 'Ver mi perfil';
                }
                if (drawerUserName) drawerUserName.textContent = currentUser.name;
                if (drawerUserRole) drawerUserRole.textContent = currentUser.role ? currentUser.role.charAt(0).toUpperCase() + currentUser.role.slice(1) : 'Consumidor';
                if (drawerFavoritosBtn) {
                    drawerFavoritosBtn.classList.toggle('hidden', (currentUser.role || '').toLowerCase() !== 'consumidor');
                }
            } else {
                if (drawerProfileBox) {
                    drawerProfileBox.classList.add('cursor-pointer');
                    drawerProfileBox.title = 'Iniciar sesión';
                }
                if (drawerUserName) drawerUserName.textContent = 'Invitado';
                if (drawerUserRole) {
                    drawerUserRole.textContent = 'Sin sesión iniciada · Iniciar sesión';
                    drawerUserRole.className = 'inline-block px-2 py-0.5 rounded text-[10px] bg-amber-500/20 text-amber-300 font-bold border border-amber-500/40 underline';
                }
                if (drawerFavoritosBtn) drawerFavoritosBtn.classList.add('hidden');
            }

            // Aplicar la vista según el rol (todos inician en la página principal)
            applyRoleUI();
        }

        // VISTAS POR ROL:
        // - Todos los roles inician en la página principal (inicio/calendario).
        // - Los formularios propios se abren solo al tocar un botón de registro.
        function applyRoleUI() {
            // La navegación, el menú lateral y el footer quedan disponibles
            // para que puedan volver al inicio cuando quieran.
            const mainNav = document.getElementById('mainNav');
            if (mainNav) mainNav.style.display = '';

            const drawerQuickAccess = document.getElementById('drawerQuickAccess');
            if (drawerQuickAccess) drawerQuickAccess.style.display = '';

            const footerLinks = document.getElementById('footerLinks');
            if (footerLinks) footerLinks.style.display = '';

            const home = document.getElementById('sectionHome');
            const pagina4 = document.getElementById('sectionPagina4');
            const pagina5 = document.getElementById('sectionPagina5');
            const pagina6 = document.getElementById('sectionPagina6');
            if (!home) return;

            // El inicio se muestra siempre; los formularios quedan ocultos
            // hasta que el usuario toque un botón de registro.
            home.classList.remove('hidden');
            if (pagina4) pagina4.classList.add('hidden');
            if (pagina5) pagina5.classList.add('hidden');
            if (pagina6) pagina6.classList.add('hidden');
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
