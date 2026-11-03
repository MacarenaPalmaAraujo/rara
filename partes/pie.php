    <!-- Pie de página -->
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

    <!-- Lógica principal de la aplicación (calendario, sesión, formularios y ventanas) -->
    <script src="js/programa.js"></script>
</body>
</html>
