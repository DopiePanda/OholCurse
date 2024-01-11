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
        },
    },
}
