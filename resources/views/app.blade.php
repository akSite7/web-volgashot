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
        @if (($page['component'] ?? null) === 'Home')
            <!-- Yandex.Metrika counter -->
            <script type="text/javascript">
                (function(m,e,t,r,i,k,a){
                    m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
                    m[i].l=1*new Date();
                    for (var j = 0; j < document.scripts.length; j++) {
                        if (document.scripts[j].src === r) { return; }
                    }
                    k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)
                })(window, document, 'script', 'https://mc.yandex.ru/metrika/tag.js?id=112016986', 'ym');

                ym(112016986, 'init', {ssr:true, clickmap:true, ecommerce:"dataLayer", referrer:document.referrer, url:location.href, accurateTrackBounce:true, trackLinks:true});
            </script>
            <!-- /Yandex.Metrika counter -->
        @endif
        @vite(['resources/css/app.css', 'resources/js/app.ts', "resources/js/pages/{$page['component']}.vue"])
        <x-inertia::head>
            <title>{{ config('app.name', 'inoilshot') }}</title>
        </x-inertia::head>
    </head>
    <body>
        @if (($page['component'] ?? null) === 'Home')
            <noscript><div><img src="https://mc.yandex.ru/watch/112016986" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
        @endif
        <x-inertia::app /> 
    </body>
</html>
