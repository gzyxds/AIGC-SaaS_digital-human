export default defineAppConfig({
    ui: {
        primary: 'theme',
        gray: 'zinc',
        modal: {
            overlay: {
                background: 'backdrop-blur-lg',
            },
        },
        badge: {
            variant: {
                solid: 'text-white dark:text-white',
            },
        },
        button: {
            default: {
                loadingIcon: 'tabler:loader-2',
            },
            variant: {
                // soft: 'text-{color}-500 dark:text-{color}-500 bg-{color}-50 hover:bg-{color}-200 disabled:bg-{color}-50',
                solid: 'text-white dark:text-white',
            },
            icon: {
                base: 'flex-shrink-0',
                loading: 'animate-spin',
                size: {
                    '2xs': 'h-3 w-3',
                    xs: 'h-3.5 w-3.5',
                    sm: 'h-4 w-4',
                    md: 'h-4 w-4',
                    lg: 'h-5 w-5',
                    xl: 'h-6 w-6',
                },
            },
            size: {
                '2xs': 'text-xs',
                xs: 'text-xs',
                sm: 'text-sm',
                md: 'text-sm',
                lg: 'text-sm',
                xl: 'text-base',
            },
        },
    },
});
