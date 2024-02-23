import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import preset from './vendor/filament/support/tailwind.config.preset'

/** @type {import('tailwindcss').Config} */
export default {
    presets: [preset],
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './app/Filament/**/*.php',
        './resources/views/filament/**/*.blade.php',
        './resources/views/livewire/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
        './vendor/masmerise/livewire-toaster/resources/views/*.blade.php',
        './vendor/wire-elements/modal/resources/views/*.blade.php',
    ],

    safelist: [
        "sm:max-w-sm",
        "sm:max-w-md",
        "sm:max-w-lg",
        "sm:max-w-xl",
        "sm:max-w-2xl",
        "sm:max-w-3xl",
        "sm:max-w-4xl",
        "sm:max-w-5xl",
        "sm:max-w-6xl",
        "sm:max-w-7xl",
        "md:max-w-lg",
        "md:max-w-xl",
        "lg:max-w-2xl",
        "lg:max-w-3xl",
        "xl:max-w-4xl",
        "xl:max-w-5xl",
        "2xl:max-w-6xl",
        "2xl:max-w-7xl'",
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
                    'fill-hover': 'var(--color-fill-hover)',
                    'fill-hover-dark': 'var(--color-fill-hover-dark)',
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
                    muted: 'var(--color-border-muted-dark)',
                    'muted-dark': 'var(--color-border-muted-dark)',
                },
            },
        },
    },

    plugins: [forms],

    darkMode: 'class',
};
