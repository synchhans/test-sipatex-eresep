<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'E-Prescription Sipatex')</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        'brand': {
                            'light': '#eff6ff', 
                            'DEFAULT': '#3b82f6', 
                            'dark': '#1e40af'   
                        },
                    }
                }
            }
        }
    </script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

    <style>
        body {
            background-color: #f8fafc;
        }
        
        .choices__inner {
            background-color: #fff;
            border: 1px solid #d1d5db; 
            border-radius: 0.5rem; 
            font-size: 1rem;
            min-height: 48px;
            padding-left: 0.75rem;
        }
        .choices__input {
            background-color: #fff !important;
            font-size: 1rem;
        }
        .choices[data-type*="select-one"].is-focused .choices__inner {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        }
        .choices__list--dropdown .choices__item--selectable.is-highlighted {
            background-color: #3b82f6;
        }
    </style>
</head>
<body class="font-sans antialiased">

    <header class="bg-white/80 backdrop-blur-md shadow-sm sticky top-0 z-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0 bg-brand-light p-2 rounded-lg">
                        <svg class="h-6 w-6 text-brand-dark" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                           <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                        </svg>
                    </div>
                    <h1 class="text-xl font-extrabold text-gray-800">
                        Aplikasi Resep Digital
                    </h1>
                </div>
                <div class="hidden md:block">
                     <span class="text-xs font-semibold bg-gray-100 text-gray-600 px-3 py-1 rounded-full">Sipatex Developer Test</span>
                </div>
            </div>
        </div>
    </header>

    <main class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-800 p-4 mb-8 rounded-lg shadow-md" role="alert">
                <div class="flex">
                    <div class="py-1"><svg class="h-6 w-6 text-green-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" /></svg></div>
                    <div>
                        <p class="font-bold">Sukses</p>
                        <p>{{ session('success') }}</p>
                    </div>
                </div>
            </div>
            @endif

            @if (session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-800 p-4 mb-8 rounded-lg shadow-md" role="alert">
                <div class="flex">
                    <div class="py-1"><svg class="h-6 w-6 text-red-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" /></svg></div>
                    <div>
                        <p class="font-bold">Terjadi Kesalahan</p>
                        <p>{{ session('error') }}</p>
                    </div>
                </div>
            </div>
            @endif
            
            @yield('content')
        </div>
    </main>
</body>
</html>