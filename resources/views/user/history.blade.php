<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Batik - History</title>

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
        html {
            scroll-behavior: smooth;
        }
    </style>
</head>
<body class="bg-gray-100 font-sans text-gray-800">

    <header class="bg-white shadow-sm sticky top-0 z-50">
        <div class="container mx-auto flex justify-between items-center p-4 md:p-6">

            <div class="flex items-center gap-x-12">
                <div class="font-dancing text-4xl font-bold">my Batik</div>
                <nav class="hidden md:flex space-x-8">
                    <a href="/" class="font-semibold text-gray-700 hover:text-black transition">Home</a>
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

    <main class="container mx-auto py-12 md:py-16 px-4">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Your Order History</h1>

            <div class="flex flex-col md:flex-row gap-4 mb-8">
                <div class="relative flex-grow">
                    <input type="search" id="search-input" placeholder="Search by Order ID, Name, Status..." class="w-full pl-4 pr-10 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <svg class="absolute right-3 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <div class="flex-shrink-0">
                    <select id="sort-select" class="w-full md:w-auto h-full px-4 py-2 border rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="newest">Sort by: Newest First</option>
                        <option value="oldest">Sort by: Oldest First</option>
                    </select>
                </div>
            </div>

            <div class="space-y-8" id="orders-container">
                @forelse ($riwayatPesanan as $riwayat)
                    @if($riwayat->order)
                        <div class="order-card bg-white rounded-xl shadow-lg overflow-hidden" 
                             data-id="{{ $riwayat->order->id_pesanan }}"
                             data-date="{{ optional($riwayat->order->tanggal_pesan)->timestamp }}" 
                             data-total="{{ $riwayat->order->total }}"
                             data-status="{{ strtolower($riwayat->order->status) }}"
                             data-search-content="{{ strtolower($riwayat->order->id_pesanan . ' ' . $riwayat->order->nama . ' ' . $riwayat->order->email . ' ' . $riwayat->order->status) }}">
                            <div class="bg-gray-50 p-4 border-b border-gray-200 flex flex-wrap justify-between items-center gap-4">
                                <div>
                                    <p class="text-sm text-gray-600">Order Placed</p>
                                    <p class="font-semibold text-gray-800">{{ optional($riwayat->order->tanggal_pesan)->format('M d, Y') }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Total</p>
                                    <p class="font-semibold text-gray-800">Rp {{ number_format($riwayat->order->total, 0, ',', '.') }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Order ID</p>
                                    <p class="font-semibold text-gray-800">{{ $riwayat->order->id_pesanan }}</p>
                                </div>
                            </div>
                            <div class="p-6">
                                <h2 class="text-lg font-bold mb-4 {{
                                    $riwayat->order->status == 'Pending' ? 'text-yellow-500' : (
                                    $riwayat->order->status == 'In Progress' ? 'text-blue-500' : (
                                    $riwayat->order->status == 'Ready' ? 'text-green-600' : (
                                    $riwayat->order->status == 'Cancelled' ? 'text-red-600' : 'text-gray-800'
                                ))) }}">{{ $riwayat->order->status }}</h2>
                                <div class="flex items-start ">
                                    <div class="w-24 h-24 flex-shrink-0">
                                        <img src="{{ isset($riwayat->order->desain) ? asset('storage/' . $riwayat->order->desain) : 'https://placehold.co/80x80/e0e0e0/757575?text=No+Image' }}" alt="Batik Design" class="w-full h-full object-cover rounded-md">
                                    </div>
                                    <div class="w-full pl-4">
                                        <h3 class="font-semibold text-lg">Custom Batik</h3>
                                        <p class="text-gray-600">Size: {{ $riwayat->order->ukuran }}</p>
                                        <div class="flex mt-2">
                                            <div class="w-1/2 pr-6">
                                                <p class="text-gray-500 text-sm mt-1">
                                                    <span class="font-semibold text-gray-700">Customer Name:</span> {{ $riwayat->order->nama }}
                                                </p>
                                                <p class="text-gray-500 text-sm">
                                                    <span class="font-semibold text-gray-700">Email:</span> {{ $riwayat->order->email }}
                                                </p>
                                                <p class="text-gray-500 text-sm">
                                                    <span class="font-semibold text-gray-700">Address:</span> {{ $riwayat->order->alamat }}
                                                </p>
                                                <p class="text-gray-500 text-sm">
                                                    <span class="font-semibold text-gray-700">Phone Number:</span> {{ $riwayat->order->no_telepon }}
                                                </p>
                                                <p class="text-gray-500 text-sm">
                                                    <span class="font-semibold text-gray-700">Payment Method:</span> 
                                                    @if($riwayat->order->metode_bayar == 'bank_transfer')
                                                        Bank Transfer
                                                    @elseif($riwayat->order->metode_bayar == 'qris')
                                                        Q-RIS
                                                    @endif
                                                </p>
                                                <p class="text-gray-500 text-sm">
                                                    <span class="font-semibold text-gray-700">Last Updated:</span> {{ $riwayat->order->updated_at->format('M d, Y H:i') }}
                                                </p>
                                            </div>
                                            <div class="w-1/2 pl-6">
                                                <p class="text-gray-600 font-semibold">Nota:</p>
                                                <p class="text-gray-500 text-sm">{{ $riwayat->order->nota }}</p>
                                            </div>
                                        </div>
                                        <a href="{{ route('receipt', $riwayat->order->id_pesanan) }}" class="mt-4 inline-block bg-blue-500 text-white font-semibold py-2 px-4 rounded-lg text-sm hover:bg-blue-600 transition">View Receipt</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @empty
                    <div id="no-orders-message" class="bg-white rounded-xl shadow-lg overflow-hidden text-center p-12">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <h2 class="mt-4 text-xl font-semibold text-gray-800">No Orders Yet</h2>
                        <p class="mt-2 text-gray-600">You haven't placed any orders with us. When you do, they will appear here.</p>
                        <a href="/create" class="mt-6 inline-block bg-purple-600 text-white font-bold py-3 px-6 rounded-lg hover:bg-purple-700 transition-all">Start Creating!</a>
                    </div>
                @endforelse
                 <div id="no-results-message" class="hidden bg-white rounded-xl shadow-lg overflow-hidden text-center p-12">
                       <h2 class="mt-4 text-xl font-semibold text-gray-800">No Orders Found</h2>
                       <p class="mt-2 text-gray-600">No orders match your search query. Try searching for something else.</p>
                 </div>
            </div>
            
            <div class="mt-8">
                {{ $riwayatPesanan->links() }}
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

    document.addEventListener('DOMContentLoaded', () => {
        const searchInput = document.getElementById('search-input');
        const sortSelect = document.getElementById('sort-select');
        const ordersContainer = document.getElementById('orders-container');
        const orderCards = Array.from(document.querySelectorAll('.order-card'));
        const noResultsMessage = document.getElementById('no-results-message');

        function updateOrdersDisplay() {
            const searchTerm = searchInput.value.toLowerCase();
            let visibleCards = orderCards.filter(card => {
                const content = card.dataset.searchContent;
                const isVisible = content.includes(searchTerm);
                card.classList.toggle('hidden', !isVisible);
                return isVisible;
            });

            const hasVisibleCards = visibleCards.length > 0;
            const noOrdersMessage = document.getElementById('no-orders-message');
            
            if (searchTerm === '') {
                noResultsMessage.classList.add('hidden');
                 if(noOrdersMessage) noOrdersMessage.style.display = orderCards.length > 0 ? 'none' : 'block';
            } else {
                 noResultsMessage.classList.toggle('hidden', hasVisibleCards);
                 if(noOrdersMessage) noOrdersMessage.style.display = 'none';
            }

            if (!hasVisibleCards && searchTerm !== '') return;
             if (orderCards.length === 0) return;


            const sortValue = sortSelect.value;
            visibleCards.sort((a, b) => {
                const dateA = parseInt(a.dataset.date, 10);
                const dateB = parseInt(b.dataset.date, 10);
                const idA = parseInt(a.dataset.id, 10);
                const idB = parseInt(b.dataset.id, 10);

                switch (sortValue) {
                    case 'oldest':
                        return (dateA - dateB) || (idA - idB);
                    case 'newest':
                    default:
                        return (dateB - dateA) || (idB - idA);
                }
            });

            visibleCards.forEach(card => ordersContainer.appendChild(card));
        }

        searchInput.addEventListener('input', updateOrdersDisplay);
        sortSelect.addEventListener('change', updateOrdersDisplay);

        updateOrdersDisplay();
    });
</script>

</body>
</html>
