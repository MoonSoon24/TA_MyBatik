<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Batik - Login</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'sans': ['Poppins', 'sans-serif'],
                        'dancing': ['Dancing Script', 'cursive'],
                    }
                }
            }
        }
    </script>
    <style>
        .font-dancing {
            font-family: 'Dancing Script', cursive;
        }
    </style>
</head>
<body class="bg-gray-100 font-sans text-gray-800">

    <header class="bg-white shadow-sm">
        <div class="container mx-auto flex justify-between items-center p-4 md:p-6">
            <a href="/" class="font-dancing text-4xl font-bold">my Batik</a>
        </div>
    </header>

    <main class="flex flex-col items-center justify-center min-h-screen bg-gray-50 py-12 px-4 -mt-20">
        <div class="bg-white rounded-2xl shadow-lg p-8 md:p-12 w-full max-w-md">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-900">Welcome!</h2>
                <p class="text-gray-500 mt-2">Log in to continue to your account.</p>
            </div>

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Oops!</strong>
                    <span class="block sm:inline">There were some problems with your input.</span>
                    <ul class="mt-3 list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="/login" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label for="Email" class="block text-sm font-medium text-gray-700">Email</label>
                    <div class="mt-1">
                        <input id="email" name="email" type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <div class="mt-1">
                        <input id="password" name="password" type="password" autocomplete="current-password" required class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                    </div>
                </div>

                <div>
                    <button type="submit" class="w-full bg-black text-white font-semibold py-3 px-4 rounded-lg shadow-md hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black transition-all duration-300 text-lg">
                        Log In
                    </button>
                </div>
            </form>

            <div class="text-center mt-6 pt-6 border-t border-gray-200">
                <p class="text-sm text-gray-600">
                    Don't have an account? 
                    <a href="/register" class="font-semibold text-blue-600 hover:text-blue-800 transition">Sign Up</a>
                </p>
            </div>
        </div>
    </main>

    <x-alert />
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // This handles success messages
            @if (session('success'))
                window.dispatchEvent(new CustomEvent('alert', {
                    detail: { type: 'success', message: "{{ session('success') }}" }
                }));
            @endif

            // This handles validation errors, just in case
            @if ($errors->any())
                window.dispatchEvent(new CustomEvent('alert', {
                    detail: { type: 'error', message: "{{ $errors->first() }}" }
                }));
            @endif
        });
    </script>
</body>
</html>
