<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Batik - Receipt</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'sans': ['Poppins', 'sans-serif'],
                        'dancing': ['Dancing Script', 'cursive'],
                    },
                    colors: {
                        'batik-cyan': '#06b6d4',
                        'batik-light-green': '#ecfdf5',
                        'batik-green': '#10b981',
                        'batik-light-blue': '#f0f9ff',
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
<body class="bg-gray-50 font-sans text-gray-800">

    <header class="bg-white shadow-sm sticky top-0 z-50">
        <div class="container mx-auto flex justify-between items-center p-4 md:p-6">
        
            <div class="flex items-center gap-x-12">
                <div class="font-dancing text-4xl font-bold">my Batik</div>
                <nav class="hidden md:flex space-x-8">
                    <a href="/" class="font-semibold text-gray-700 hover:text-black transition">Home</a>
                    <a href="/history" class="font-semibold text-gray-700 hover:text-black transition">Orders</a>
                </nav>
            </div>
            
            <div class="flex items-center space-x-3">
                <div x-data="{ dropdownOpen: false }" class="relative">
                    <button @click="dropdownOpen = !dropdownOpen" class="flex items-center space-x-3">
                        <span class="font-semibold text-gray-700 hover:text-black transition">{{ Auth::user()->name }}</span>
                        <div class="w-8 h-8">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </button>
                    <div x-show="dropdownOpen" @click.away="dropdownOpen = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-50">
                        <a href="/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                        <form method="POST" action="{{ route('logout') }}" id="logout-form">
                            @csrf
                            <a href="#" id="logout-link" class="block px-4 py-2 text-sm font-semibold text-red-600 hover:text-red-800 transition">Logout</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="container mx-auto py-8 md:py-12 px-4">
        <div class="bg-batik-light-green border border-batik-green/50 rounded-xl p-6 flex items-center gap-6 mb-8">
            <div class="flex-shrink-0">
                <svg class="w-16 h-16 text-batik-green" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Order Successfully Placed!</h2>
                <p class="text-gray-700 mt-1">Thank you for your order. Your order number is <span class="font-bold text-batik-cyan">#{{ $order->id_pesanan ?? '00001' }}</span></p>
                <p class="text-gray-600">Your order will be processed once payment is confirmed</p>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-8 items-start">
            
            <!-- left side -->
            <div class="w-full lg:w-2/3 bg-white p-8 rounded-xl shadow-md">
                <div class="flex justify-between items-center pb-4 border-b">
                    <div>
                        <h3 class="text-2xl font-bold">Order Receipt</h3>
                        <p class="text-gray-500">{{ ($order->created_at ?? now())->format('d F, Y') }}</p>
                    </div>
                    <p class="text-gray-600 font-semibold">Order #{{ $order->id_pesanan ?? '00001' }}</p>
                </div>

                <div class="py-6">
                    <h4 class="font-bold text-lg mb-4">Item Details</h4>
                    <div class="flex items-center">
                        <img src="{{ isset($order->desain) ? asset('storage/' . $order->desain) : 'https://placehold.co/80x80/06b6d4/ffffff?text=Batik' }}" alt="Batik Shirt" class="w-20 h-20 rounded-lg border p-1 mr-4 object-cover">
                        <div>
                            <h5 class="font-semibold text-lg">Custom Batik Shirt</h5>
                            <p class="text-gray-600 text-sm">Size: {{ $order->ukuran ?? 'L (Large)' }}</p>
                        </div>
                    </div>
                </div>

                <div class="space-y-3 py-4 border-t">
                    <div class="flex justify-between text-gray-700">
                        <span>Product Price</span>
                        <span class="font-medium">Rp. 250.000</span>
                    </div>
                    <div class="flex justify-between text-gray-700">
                        <span>Customization</span>
                        <span class="font-medium">Rp. 50.000</span>
                    </div>
                </div>

                <div class="flex justify-between font-bold text-xl py-4 border-t">
                    <span>Total</span>
                    <span>Rp. {{ number_format($order->total ?? 300000, 0, ',', '.') }}</span>
                </div>

                <div class="pt-6 border-t">
                     <h4 class="font-bold text-lg mb-2">Customer Information</h4>
                     <p class="font-medium">{{ $order->nama ?? 'John Doe' }}</p>
                     <p class="text-gray-600">{{ $order->email ?? 'john.doe@gmail.com' }}</p>
                     <p class="text-gray-600">{{ $order->no_telepon ?? '0123456789' }}</p>
                </div>

                <div class="pt-8 mt-8 border-t text-center text-gray-500">
                    <p>Thank you for using our Batik Designer service</p>
                </div>
            </div>

            <!-- right side -->
            <div class="w-full lg:w-1/3 space-y-8">
                <div class="bg-white p-6 rounded-xl shadow-md">
                    <h3 class="font-bold text-lg mb-4">Need Help?</h3>
                    <div class="space-y-3">
                        <a href="/home#faq" class="flex items-center gap-3 text-gray-700 hover:text-batik-cyan transition">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z" /></svg>
                            <span>FAQ</span>
                        </a>
                        <a href="/home#contact" class="flex items-center gap-3 text-gray-700 hover:text-batik-cyan transition">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" /></svg>
                            <span>Contact Us</span>
                        </a>
                    </div>
                </div>
                <div class="bg-batik-light-blue p-6 rounded-xl shadow-md">
                    <h3 class="font-bold text-lg mb-5">What's Next</h3>
                    <div class="space-y-5">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-8 h-8 bg-batik-cyan text-white font-bold text-sm rounded-full flex items-center justify-center">1</div>
                            <p class="text-gray-700">We'll verify your payment proof within 24 hours</p>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-8 h-8 bg-batik-cyan text-white font-bold text-sm rounded-full flex items-center justify-center">2</div>
                            <p class="text-gray-700">Your custom batik design will be crafted by our artisan</p>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-8 h-8 bg-batik-cyan text-white font-bold text-sm rounded-full flex items-center justify-center">3</div>
                            <p class="text-gray-700">We'll notify you when your batik is up for grab</p>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-8 h-8 bg-batik-cyan text-white font-bold text-sm rounded-full flex items-center justify-center">4</div>
                            <p class="text-gray-700">Enjoy your unique batik creation!</p>
                        </div>
                    </div>
                    <a href="{{ route('home') }}" class="block w-full text-center bg-batik-cyan text-white font-bold py-3 px-4 rounded-lg mt-8 hover:bg-cyan-600 transition-all duration-300">
                        Return to Home
                    </a>
                </div>
            </div>

        </div>
    </main>

    <div id="logout-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white p-8 rounded-lg shadow-xl text-center">
            <h3 class="text-xl font-bold mb-4">Confirm Logout</h3>
            <p class="mb-6">Are you sure you want to log out?</p>
            <div class="flex justify-center gap-4">
                <button id="confirm-logout-btn" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-6 rounded-lg">Logout</button>
                <button id="cancel-logout-btn" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-6 rounded-lg">Cancel</button>
            </div>
        </div>
    </div>
<script>
    const logoutLink = document.getElementById('logout-link');
    const logoutForm = document.getElementById('logout-form');
    const logoutModal = document.getElementById('logout-modal');
    const confirmLogoutBtn = document.getElementById('confirm-logout-btn');
    const cancelLogoutBtn = document.getElementById('cancel-logout-btn');

    if (logoutLink && logoutForm && logoutModal && confirmLogoutBtn && cancelLogoutBtn) {
        logoutLink.addEventListener('click', (e) => {
            e.preventDefault();
            logoutModal.classList.remove('hidden');
        });

        confirmLogoutBtn.addEventListener('click', () => {
            logoutForm.submit();
        });

        cancelLogoutBtn.addEventListener('click', () => {
            logoutModal.classList.add('hidden');
        });

        logoutModal.addEventListener('click', (e) => {
            if (e.target.id === 'logout-modal') {
                logoutModal.classList.add('hidden');
            }
        });
    }
</script>
</body>
</html>
