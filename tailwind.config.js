import defaultTheme from 'tailwindcss/defaultTheme'
import forms from '@tailwindcss/forms'
import typography from '@tailwindcss/typography'
import rtl from 'tailwindcss-rtl'

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',

    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],

    theme: {
        extend: {
            // ── الهوية البصرية للمنصة القرآنية ──────────
            colors: {
                emerald: {
                    950: '#022c22',
                    900: '#064e3b',  // اللون الرئيسي
                    800: '#065f46',
                    700: '#047857',
                    600: '#059669',
                    500: '#10b981',
                    100: '#d1fae5',
                    50:  '#ecfdf5',
                },
                amber: {
                    600: '#d97706',  // لون الإبراز الذهبي
                    500: '#f59e0b',
                    400: '#fbbf24',
                    100: '#fef3c7',
                    50:  '#fffbeb',
                },
            },

            // ── الخطوط العربية ────────────────────────────
            fontFamily: {
                arabic: ['Tajawal', 'Cairo', ...defaultTheme.fontFamily.sans],
                quran:  ['Amiri', 'serif'],
                sans:   ['Tajawal', ...defaultTheme.fontFamily.sans],
            },

            // ── حجم الخطوط للنصوص القرآنية ───────────────
            fontSize: {
                'quran-sm': ['1.4rem', { lineHeight: '2.2rem' }],
                'quran-md': ['1.8rem', { lineHeight: '2.8rem' }],
                'quran-lg': ['2.2rem', { lineHeight: '3.4rem' }],
            },

            // ── ظلال مستوحاة من الزخارف الإسلامية ────────
            boxShadow: {
                'islamic': '0 4px 24px rgba(6, 78, 59, 0.12)',
                'islamic-lg': '0 8px 40px rgba(6, 78, 59, 0.18)',
                'gold': '0 4px 20px rgba(217, 119, 6, 0.15)',
            },

            // ── نمط الزخرفة الهندسية الإسلامية ───────────
            backgroundImage: {
                'geometric-pattern': "url('/images/islamic-pattern.svg')",
                'arabesque': "url('/images/arabesque.svg')",
            },
        },
    },

    plugins: [forms, typography, rtl],
}