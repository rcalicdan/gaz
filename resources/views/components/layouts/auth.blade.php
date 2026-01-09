@props([
    'title' => config('app.name'),
    'brandTitle' => 'System zarządzania wywozem odpadów',
    'brandSubtitle' => 'Zarządzaj odbiorami, klientami, trasami i fakturami z jednego miejsca.',
    'showBrandPanel' => true,
])

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>

    {{ $head ?? '' }}

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles()
</head>

<body class="font-sans antialiased text-gray-800">
    <div class="min-h-screen">
        <div class="grid {{ $showBrandPanel ? 'lg:grid-cols-2' : 'grid-cols-1' }} min-h-screen">
            @if ($showBrandPanel)
                <!-- Left Panel: Illustration and Branding -->
                <div class="hidden lg:flex flex-col items-center justify-center bg-primary-light p-12 text-center">
                    <div class="w-full max-w-md">
                        {{ $brandLogo ?? '' }}
                        @if (empty($brandLogo))
                            <svg class="w-48 mx-auto text-primary" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 2c-2.21 0-4 1.79-4 4 0 3.31 4 6 4 6s4-2.69 4-6c0-2.21-1.79-4-4-4zM6 14c-1.1 1.1-2 3-2 4 0 .55.45 1 1 1h12c.55 0 1-.45 1-1 0-1-1-2.9-2-4-2 1-5 1-10 0z" fill="currentColor"/>
                            </svg>
                        @endif
                        <h2 class="mt-8 text-4xl font-bold text-primary-dark">{{ $brandTitle }}</h2>
                        <p class="mt-4 text-lg text-gray-600">{{ $brandSubtitle }}</p>
                    </div>
                </div>
            @endif

            <!-- Right Panel: Main Content -->
            <div class="flex flex-col justify-center items-center p-6 sm:p-12">
                <div class="w-full max-w-sm">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>

    @livewireScripts
</body>

</html>
