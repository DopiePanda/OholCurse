@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-skin-base dark:border-skin-base-dark text-sm font-medium leading-5 text-gray-900 dark:text-gray-200 focus:outline-none focus:border-skin-base dark:focus:border-skin-base-dark transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-400 dark:text-gray-600 dark:hover:text-gray-400 hover:border-skin-base dark:hover:border-skin-base-dark focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
