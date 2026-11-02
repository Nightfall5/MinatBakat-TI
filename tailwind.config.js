export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    darkMode: 'class',
    theme: {
        extend: {
            colors: {
                brand: {
                    navy: '#0B1020',
                    surface: '#121A2B',
                    card: '#182238',
                    cardhover: '#1F2C49',
                    border: '#243252',
                    purple: '#8B5CF6',
                    purpleLight: '#A78BFA',
                    cyan: '#22D3EE',
                    cyanLight: '#67E8F9',
                    muted: '#94A3B8',
                    light: '#F8FAFC',
                }
            },
            fontFamily: {
                sans: ['Inter', 'system-ui', '-apple-system', 'sans-serif'],
            },
            boxShadow: {
                'glow-cyan': '0 0 25px -5px rgba(34, 211, 238, 0.3)',
                'glow-purple': '0 0 25px -5px rgba(139, 92, 246, 0.35)',
                'glow-combined': '0 0 35px -5px rgba(139, 92, 246, 0.25), 0 0 20px -5px rgba(34, 211, 238, 0.2)',
            },
            animation: {
                'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
            }
        },
    },
    plugins: [],
}
