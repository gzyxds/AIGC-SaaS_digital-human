/** @type {import('tailwindcss').Config} */
import TailwindcssTypography from '@tailwindcss/typography';

export default {
    content: ['./src/**/*.{js,vue,ts,html}'],
    darkMode: 'class',
    theme: {
        extend: {
            colors: {
                border: {
                    DEFAULT: 'rgb(var(--border))',
                    soft: 'rgb(var(--border-soft))',
                },
                background: {
                    DEFAULT: 'rgb(var(--background))',
                    soft: 'rgb(var(--background-soft))',
                },
                foreground: 'rgb(var(--foreground))',
                primary: {
                    DEFAULT: 'rgb(var(--primary))',
                    foreground: 'rgb(var(--primary-foreground))',
                },
                theme: {
                    DEFAULT: 'var(--DEFAULT)',
                    50: 'var(--50)',
                    100: 'var(--100)',
                    200: 'var(--200)',
                    300: 'var(--300)',
                    400: 'var(--400)',
                    500: 'var(--500)',
                    600: 'var(--600)',
                    700: 'var(--700)',
                    800: 'var(--800)',
                    900: 'var(--900)',
                    950: 'var(--950)',
                },
            },
        },
    },
    plugins: [
        TailwindcssTypography,
        function ({ addUtilities }) {
            addUtilities({
                '.start': {
                    display: 'flex',
                    'justify-content': 'start',
                    'align-items': 'center',
                },
                '.center': {
                    display: 'flex',
                    'justify-content': 'center',
                    'align-items': 'center',
                },
                '.end': {
                    display: 'flex',
                    'justify-content': 'end',
                    'align-items': 'center',
                },
            });
        },
    ],
};
