import preset from '../../../../vendor/filament/filament/tailwind.config.preset'

export default {
    presets: [preset],
    content: [
        './app/Filament/**/*.php',
        './resources/views/filament/**/*.blade.php',
        './resources/views/livewire/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Lato'],
            },
            textColor: {
                skin: {
                    base: 'var(--color-text-base)',
                    'base-dark': 'var(--color-text-base-dark)',
                    muted: 'var(--color-text-muted)',
                    'muted-dark': 'var(--color-text-muted-dark)',
                    inverted: 'var(--color-text-inverted)',
                    'inverted-dark': 'var(--color-text-inverted-dark)',
                },
            },
            backgroundColor: {
                skin: {
                    fill: 'var(--color-fill)',
                    'fill-dark': 'var(--color-fill-dark)',
                    'fill-wrapper': 'var(--color-fill-wrapper)',
                    'fill-wrapper-dark': 'var(--color-fill-wrapper-dark)',
                    'fill-muted': 'var(--color-fill-muted)',
                    'fill-muted-dark': 'var(--color-fill-muted-dark)',
                    'button-accent': 'var(--color-button-accent)',
                    'button-accent-dark': 'var(--color-button-accent-dark)',
                    'button-accent-hover': 'var(--color-button-accent-hover)',
                    'button-accent-hover-dark': 'var(--color-button-accent-hover-dark)',
                    'button-muted': 'var(--color-button-muted)',
                    'button-muted-dark': 'var(--color-button-muted-dark)',
                },
            },
            borderColor: {
                skin: {
                    base: 'var(--color-border-base)',
                    'base-dark': 'var(--color-border-base-dark)',
                },
            },
            colors: {
                custom: {
                    50: 'rgba(var(--c-50), <alpha-value>)',
                    100: 'rgba(var(--c-100), <alpha-value>)',
                    200: 'rgba(var(--c-200), <alpha-value>)',
                    300: 'rgba(var(--c-300), <alpha-value>)',
                    400: 'rgba(var(--c-400), <alpha-value>)',
                    500: 'rgba(var(--c-500), <alpha-value>)',
                    600: 'rgba(var(--c-600), <alpha-value>)',
                    700: 'rgba(var(--c-700), <alpha-value>)',
                    800: 'rgba(var(--c-800), <alpha-value>)',
                    900: 'rgba(var(--c-900), <alpha-value>)',
                    950: 'rgba(var(--c-950), <alpha-value>)',
                },
            },
        },
    },
}
