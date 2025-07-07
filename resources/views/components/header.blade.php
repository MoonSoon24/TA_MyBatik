@props(['title' => 'myBatik'])

<header class="bg-white shadow-sm sticky top-0 z-30">
        <div class="container mx-auto flex justify-between items-center p-4 md:p-6">
            <div class="flex items-center gap-x-12">
                <div class="font-dancing text-4xl font-bold">my Batik</div>
                <nav class="hidden md:flex space-x-8">
                    <a href="{{ route('home') }}" class="font-semibold transition text-gray-700 hover:text-black">Home</a>
                    <a href="{{ route('gallery.index') }}" class="font-semibold text-gray-700 hover:text-black transition">Gallery</a>
                    @auth
                    <a href="/history" class="font-semibold text-gray-700 hover:text-black transition">Orders</a>
                    @endauth
                    <a href="/#about" class="font-semibold text-gray-700 hover:text-black transition">About Us</a>
                    <a href="/#faq" class="font-semibold text-gray-700 hover:text-black transition">FAQ</a>
                    <a href="/#contact" class="font-semibold text-gray-700 hover:text-black transition">Contact</a>
                </nav>
            </div>
            <div class="flex items-center space-x-3">
                @auth
                    <div x-data="{ dropdownOpen: false }" class="relative">
                        <button @click="dropdownOpen = !dropdownOpen" class="flex items-center space-x-3">
                            <span class="font-semibold text-gray-700 hover:text-black transition">{{ Auth::user()->name }}</span>
                            <div class="w-8 h-8"><svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd" /></svg></div>
                        </button>
                        <div x-show="dropdownOpen" @click.away="dropdownOpen = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-50" x-cloak>
                            <a href="/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                            <form method="POST" action="{{ route('logout') }}" id="logout-form">
                                @csrf
                                <a href="#" id="logout-link" class="block px-4 py-2 text-sm font-semibold text-red-600 hover:text-red-800 transition">Logout</a>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="/login" class="font-semibold text-gray-700 hover:text-black transition">Sign In</a>
                    <div class="w-8 h-8"><svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd" /></svg></div>
                @endguest
            </div>
        </div>
    </header>