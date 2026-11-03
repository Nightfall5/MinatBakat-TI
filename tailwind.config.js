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
                    navy: '#091721',
                    surface: '#FFFFFF',
                    card: '#FFFFFF',
                    cardhover: '#F3F8FA',
                    border: '#DCE8EC',
                    purple: '#3D6C97',
                    purpleLight: '#5FA2BE',
                    cyan: '#9BE2F3',
                    cyanLight: '#9BE2F3',
                    muted: '#647985',
                    light: '#172D3A',
                }
            },
            fontFamily: {
                sans: ['Manrope', 'DM Sans', 'system-ui', 'sans-serif'],
            },
            boxShadow: {
                'glow-cyan': '0 10px 28px rgba(9, 23, 33, 0.08)',
                'glow-purple': '0 10px 28px rgba(9, 23, 33, 0.08)',
                'glow-combined': '0 10px 28px rgba(9, 23, 33, 0.08)',
            },
            animation: {
                'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
            }
        },
    },
    plugins: [],
}
