<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name') }} — Maintenance</title>
        <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
        @vite(['resources/css/app.css'])
    </head>
    <body class="font-sans antialiased bg-customer-bg min-h-screen flex items-center justify-center p-6">
        <div class="max-w-md text-center space-y-4">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-16 h-16 rounded-full object-cover mx-auto border-2 border-beige">
            <h1 class="text-2xl font-bold text-choco-dark">Maintenance en cours</h1>
            <p class="text-choco-soft">
                {{ $message ?? 'AfricaMall est momentanément indisponible pour une opération de maintenance. Merci de revenir dans quelques instants.' }}
            </p>
        </div>
    </body>
</html>
