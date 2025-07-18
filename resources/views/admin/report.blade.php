<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - My Batik Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { 'sans': ['Poppins', 'sans-serif'], 'dancing': ['Dancing Script', 'cursive'] }
                }
            }
        }
    </script>
    <style>
        .font-dancing { font-family: 'Dancing Script', cursive; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-100 font-sans text-gray-800">

    <header class="bg-white shadow-sm sticky top-0 z-40">
        <div class="container mx-auto flex justify-between items-center p-4 md:p-6">
            <div class="flex items-center gap-x-12">
                <div class="font-dancing text-4xl font-bold">my Batik</div>
                <nav class="hidden md:flex space-x-8">
                    <a href="{{ route('admin.home') }}" class="font-semibold text-gray-700 hover:text-black transition">Home</a>
                    <a href="{{ route('admin.reports.index') }}" class="font-bold text-black transition">Reports</a>
                </nav>
            </div>
            <div class="flex items-center space-x-3">
                <div x-data="{ dropdownOpen: false }" class="relative">
                    <button @click="dropdownOpen = !dropdownOpen" class="flex items-center space-x-3">
                        <span class="font-semibold text-gray-700 hover:text-black transition">{{ Auth::user()->name }}</span>
                        <div class="w-8 h-8">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd" /></svg>
                        </div>
                    </button>
                    <div x-show="dropdownOpen" @click.away="dropdownOpen = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-50" x-cloak>
                        <a href="{{ route('admin.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                        <form method="POST" action="{{ route('logout') }}">@csrf<a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="block px-4 py-2 text-sm font-semibold text-red-600 hover:text-red-800 transition">Logout</a></form>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container mx-auto p-4 sm:p-6 lg:p-8">
        <main class="bg-white rounded-2xl shadow-sm p-6 md:p-8">
            <div class="flex flex-col md:flex-row gap-8" x-data="{ activeReport: 'sales' }">
                <aside class="w-full md:w-1/4">
                    <h2 class="text-xl font-bold mb-4">Reports Menu</h2>
                    <ul class="space-y-2">
                        <li><a href="#" @click.prevent="activeReport = 'sales'" class="block px-4 py-2 rounded-md" :class="{ 'bg-blue-100 text-blue-700 font-bold': activeReport === 'sales', 'hover:bg-gray-100 font-semibold text-gray-600': activeReport !== 'sales' }">Monthly Sales</a></li>
                        <li><a href="#" @click.prevent="activeReport = 'promo'" class="block px-4 py-2 rounded-md" :class="{ 'bg-blue-100 text-blue-700 font-bold': activeReport === 'promo', 'hover:bg-gray-100 font-semibold text-gray-600': activeReport !== 'promo' }">Promo Code Usage</a></li>
                        <li><a href="#" @click.prevent="activeReport = 'users'" class="block px-4 py-2 rounded-md" :class="{ 'bg-blue-100 text-blue-700 font-bold': activeReport === 'users', 'hover:bg-gray-100 font-semibold text-gray-600': activeReport !== 'users' }">User Registrations</a></li>
                        <li><a href="#" @click.prevent="activeReport = 'customers'" class="block px-4 py-2 rounded-md" :class="{ 'bg-blue-100 text-blue-700 font-bold': activeReport === 'customers', 'hover:bg-gray-100 font-semibold text-gray-600': activeReport !== 'customers' }">Top Customers</a></li>
                    </ul>
                </aside>

                <div class="w-full md:w-3/4">
                    
                    <section id="sales-report" x-show="activeReport === 'sales'" x-cloak>
                        <h1 class="text-3xl font-bold text-gray-800 mb-6">Monthly Sales Report</h1>
                        <div class="overflow-x-auto">
                            <div class="inline-block min-w-full shadow rounded-lg overflow-hidden">
                                <table class="min-w-full leading-normal">
                                    <thead>
                                        <tr>
                                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Month</th>
                                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Total Orders</th>
                                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Total Sales</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($salesData as $data)
                                        <tr>
                                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                                <p class="text-gray-900 whitespace-no-wrap">{{ date('F Y', mktime(0, 0, 0, $data->month, 1, $data->year)) }}</p>
                                            </td>
                                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                                <p class="text-gray-900 whitespace-no-wrap">{{ $data->total_orders }}</p>
                                            </td>
                                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                                <p class="text-gray-900 whitespace-no-wrap">Rp {{ number_format($data->total_sales, 2, ',', '.') }}</p>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="3" class="text-center px-5 py-5 border-b border-gray-200 bg-white text-sm">No sales data found.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </section>

                    <section id="promo-report" x-show="activeReport === 'promo'" x-cloak>
                        <h1 class="text-3xl font-bold text-gray-800 mb-6">Promo Code Usage Report</h1>
                        <div class="overflow-x-auto">
                            <div class="inline-block min-w-full shadow rounded-lg overflow-hidden">
                                 <table class="min-w-full leading-normal">
                                    <thead>
                                        <tr>
                                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Promo Code</th>
                                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Discount</th>
                                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Times Used</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($promoData as $promo)
                                        <tr>
                                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm"><p class="text-gray-900 whitespace-no-wrap">{{ $promo->code }}</p></td>
                                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm"><p class="text-gray-900 whitespace-no-wrap">{{ $promo->type == 'percentage' ? $promo->value.'%' : 'Rp '.number_format($promo->value, 2, ',', '.') }}</p></td>
                                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm"><p class="text-gray-900 whitespace-no-wrap">{{ $promo->usage_count }}</p></td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="3" class="text-center px-5 py-5 border-b border-gray-200 bg-white text-sm">No promo codes have been used yet.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </section>
                    
                    <section id="users-report" x-show="activeReport === 'users'" x-cloak>
                        <h1 class="text-3xl font-bold text-gray-800 mb-6">New User Registrations</h1>
                        <div class="overflow-x-auto">
                            <div class="inline-block min-w-full shadow rounded-lg overflow-hidden">
                               <table class="min-w-full leading-normal">
                                    <thead>
                                        <tr>
                                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Month</th>
                                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">New Users</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($userData as $data)
                                        <tr>
                                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                                <p class="text-gray-900 whitespace-no-wrap">{{ date('F Y', mktime(0, 0, 0, $data->month, 1, $data->year)) }}</p>
                                            </td>
                                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                                <p class="text-gray-900 whitespace-no-wrap">{{ $data->total_users }}</p>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="2" class="text-center px-5 py-5 border-b border-gray-200 bg-white text-sm">No user registration data found.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </section>

                    <section id="customers-report" x-show="activeReport === 'customers'" x-cloak>
                        <h1 class="text-3xl font-bold text-gray-800 mb-6">Top Customers Report</h1>
                        <div class="overflow-x-auto">
                            <div class="inline-block min-w-full shadow rounded-lg overflow-hidden">
                                <table class="min-w-full leading-normal">
                                    <thead>
                                        <tr>
                                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Customer Name</th>
                                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Email</th>
                                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Total Orders</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($topCustomers as $customer)
                                        <tr>
                                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm"><p class="text-gray-900 whitespace-no-wrap">{{ $customer->name }}</p></td>
                                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm"><p class="text-gray-900 whitespace-no-wrap">{{ $customer->email }}</p></td>
                                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm"><p class="text-gray-900 whitespace-no-wrap">{{ $customer->orders_count }}</p></td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="3" class="text-center px-5 py-5 border-b border-gray-200 bg-white text-sm">No customer data found.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </main>
    </div>
</body>
</html>