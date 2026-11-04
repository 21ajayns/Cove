/** @type {import('tailwindcss').Config} */

// Colours come from CSS variables (see resources/css/app.css) so the same classes work in dark and light mode.
const rgb = (name) => `rgb(var(--${name}) / <alpha-value>)`;

export default {
    content: [
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Sora', 'ui-sans-serif', 'system-ui', 'sans-serif'],
            },
            colors: {
                ink: {
                    950: rgb('ink-950'),
                    900: rgb('ink-900'),
                    850: rgb('ink-850'),
                    800: rgb('ink-800'),
                    700: rgb('ink-700'),
                    600: rgb('ink-600'),
                    500: rgb('ink-500'),
                    400: rgb('ink-400'),
                    300: rgb('ink-300'),
                    100: rgb('ink-100'),
                },
                // overlay colour for hairlines and hover fills: white on dark, deep ink on light, scaled by --fg-boost
                fg: 'rgb(var(--fg) / calc(<alpha-value> * var(--fg-boost)))',
                surface: {
                    DEFAULT: rgb('surface'),
                    2: rgb('surface-2'),
                },
                accent: {
                    DEFAULT: rgb('accent'),
                    deep: rgb('accent-deep'),
                    hover: rgb('accent-hover'),
                    'deep-hover': rgb('accent-deep-hover'),
                    soft: rgb('accent-soft'),
                },
                'tab-on': rgb('tab-on'),
                'tab-icon': rgb('tab-icon'),
                cmd: rgb('cmd'),
                // shades the app uses, remapped per theme
                amber: {
                    400: rgb('amber-400'),
                    500: rgb('amber-500'),
                    600: rgb('amber-600'),
                    700: rgb('amber-700'),
                },
                red: {
                    300: rgb('red-300'),
                    400: rgb('red-400'),
                    500: rgb('red-500'),
                },
                emerald: {
                    400: rgb('emerald-400'),
                    500: rgb('emerald-500'),
                },
                blue: {
                    400: rgb('blue-400'),
                    500: rgb('blue-500'),
                },
            },
            boxShadow: {
                card: 'var(--shadow-card)',
                popover: 'var(--shadow-popover)',
            },
        },
    },
    plugins: [],
};
