<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="#0d0d13">
        <meta name="robots" content="{{ request()->is('admin*', 'login', 'register', 'password/*', 'user/*') ? 'noindex, nofollow' : 'index, follow, max-image-preview:large' }}">
        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">
        @vite(['resources/css/app.css', 'resources/js/app.ts', "resources/js/pages/{$page['component']}.vue"])
        <x-inertia::head>
            <title>{{ config('app.name', 'inoilshot') }}</title>
        </x-inertia::head>
    </head>
    <body>
        <x-inertia::app /> 
    </body>
</html>
