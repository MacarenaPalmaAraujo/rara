// Configuración visual de Tailwind para Feriando.
// Se carga después del CDN de Tailwind y define los colores y tipografías del sitio.
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
};
