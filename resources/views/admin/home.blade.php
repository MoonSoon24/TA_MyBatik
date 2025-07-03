<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Batik - Admin</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    
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
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-100 font-sans text-gray-800" x-data="adminTableData()" x-init="init()">

    <header class="bg-white shadow-sm sticky top-0 z-40">
        <div class="container mx-auto flex justify-between items-center p-4 md:p-6">
            <div class="flex items-center gap-x-12">
                <div class="font-dancing text-4xl font-bold">my Batik</div>
                <nav class="hidden md:flex space-x-8">
                    <a href="/admin" class="font-semibold text-gray-700 hover:text-black transition">Home</a>
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
                    <div x-show="dropdownOpen" @click.away="dropdownOpen = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-50" x-cloak>
                        <a href="{{ route('admin.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                        <form method="POST" action="{{ route('logout') }}" id="logout-form">
                            @csrf
                            <a href="#" id="logout-link" class="block px-4 py-2 text-sm font-semibold text-red-600 hover:text-red-800 transition">Logout</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container mx-auto p-4 sm:p-6 lg:p-8">
        <main class="bg-white rounded-2xl shadow-sm p-6 md:p-8">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
                <div class="relative">
                    <button @click="tableDropdownOpen = !tableDropdownOpen" class="flex items-center space-x-3 bg-gray-100 px-6 py-3 rounded-xl mb-4 sm:mb-0">
                        <h1 class="text-3xl font-bold text-gray-800 capitalize" x-text="activeTable"></h1>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="tableDropdownOpen" @click.away="tableDropdownOpen = false" x-cloak class="absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg z-20">
                        <a href="#" @click.prevent="activeTable = 'users'; tableDropdownOpen = false" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Users</a>
                        <a href="#" @click.prevent="activeTable = 'orders'; tableDropdownOpen = false" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Orders</a>
                    </div>
                </div>
                <div class="relative w-full sm:w-auto">
                    <input type="text" x-model="searchTerm" placeholder="Search..." class="w-full sm:w-64 pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>

            <div id="users-table" x-show="activeTable === 'users'" class="overflow-x-auto" x-cloak>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-white">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">ID</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Name</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Email</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <template x-for="user in filteredUsers" :key="user.id">
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900" x-text="user.id"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600" x-text="user.name"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600" x-text="user.email"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span x-text="user.email_verified_at ? 'Verified' : 'Not Verified'" 
                                          :class="{ 'text-green-600 font-semibold': user.email_verified_at, 'text-yellow-600 font-semibold': !user.email_verified_at }">
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <form :action="`/admin/users/${user.id}/verify`" method="POST" class="inline-block">
                                            @csrf
                                            <button type="submit" 
                                                    class="px-3 py-1 text-white text-xs font-semibold rounded-md transition-colors"
                                                    :class="user.email_verified_at ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-green-500 hover:bg-green-600'">
                                                <span x-text="user.email_verified_at ? 'Unverify' : 'Verify'"></span>
                                            </button>
                                        </form>
                                        <form :id="`delete-form-${user.id}`" :action="`/admin/users/${user.id}`" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" @click="openDeleteModal(user.id)" class="px-3 py-1 bg-red-600 text-white text-xs font-semibold rounded-md hover:bg-red-700 transition-colors">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <div id="orders-table" x-show="activeTable === 'orders'" class="overflow-x-auto" x-cloak>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-white">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Order ID</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">User ID</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Name</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Total</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Order Date</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Updated At</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                         <template x-for="order in filteredOrders" :key="order.id_pesanan">
                            <tr class="hover:bg-gray-50 cursor-pointer" @click="showOrderModal(order)">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900" x-text="order.id_pesanan"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600" x-text="order.id_user"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600" x-text="order.nama"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600" x-text="'Rp. ' + new Intl.NumberFormat('id-ID').format(order.total)"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600" x-text="new Date(order.tanggal_pesan).toLocaleDateString('id-ID')"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600" x-text="new Date(order.updated_at).toLocaleString('id-ID')"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold" :class="{ 'text-yellow-600': order.status === 'Pending', 'text-blue-600': order.status === 'In Progress', 'text-green-600': order.status === 'Ready', 'text-red-600': order.status === 'Cancelled' }" x-text="order.status"></td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </main>

        <div x-show="open" @keydown.escape.window="open = false" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4" x-cloak>
            <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="open = false"></div>
            
            <div x-show="open" x-transition class="relative bg-white rounded-2xl text-left shadow-xl transform transition-all sm:my-8 sm:max-w-6xl sm:w-full overflow-y-auto" style="max-height: 90vh;">
                <template x-if="selectedOrder">
                    <div>
                        <div id="order-details-content">
                            <div class="px-6 py-4 border-b sticky top-0 bg-white z-10">
                                <h3 class="text-2xl leading-6 font-bold text-gray-900">Order Details</h3>
                            </div>

                            <div class="p-6">
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-x-8">
                                    
                                    <div class="flex flex-col">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4">
                                            <div class="space-y-3 text-sm">
                                                <p><strong class="font-semibold text-gray-900 block">Order ID:</strong> <span class="text-gray-600" x-text="selectedOrder.id_pesanan"></span></p>
                                                <p><strong class="font-semibold text-gray-900 block">Customer Name:</strong> <span class="text-gray-600" x-text="selectedOrder.nama"></span></p>
                                                <p><strong class="font-semibold text-gray-900 block">Email:</strong> <span class="text-gray-600" x-text="selectedOrder.email"></span></p>
                                                <p><strong class="font-semibold text-gray-900 block">Phone:</strong> <span class="text-gray-600" x-text="selectedOrder.no_telepon"></span></p>
                                                <p><strong class="font-semibold text-gray-900 block">Address:</strong> <span class="text-gray-600" x-text="selectedOrder.alamat"></span></p>
                                            </div>
                                            <div class="space-y-3 text-sm">
                                                <p class="md:text-right text-gray-500">last update: <span x-text="new Date(selectedOrder.updated_at).toLocaleString('id-ID', { dateStyle: 'medium', timeStyle: 'short' })"></span></p>
                                                <p><strong class="font-semibold text-gray-900 block">Item:</strong> <span class="text-gray-600">Custom Batik</span></p>
                                                <p><strong class="font-semibold text-gray-900 block">Size:</strong> <span class="text-gray-600" x-text="selectedOrder.ukuran"></span></p>
                                                <p><strong class="font-semibold text-gray-900 block">Total Price:</strong> <span class="text-gray-800 font-bold">Rp <span x-text="new Intl.NumberFormat('id-ID').format(selectedOrder.total)"></span></span></p>
                                                <p><strong class="font-semibold text-gray-900 block">Payment Method:</strong> <span class="text-gray-600" x-text="selectedOrder.metode_bayar"></span></p>
                                                <p><strong class="font-semibold text-gray-900 block">Order Date:</strong> <span class="text-gray-600" x-text="new Date(selectedOrder.tanggal_pesan).toLocaleString('id-ID', { dateStyle: 'long', timeStyle: 'short' })"></span></p>
                                            </div>
                                        </div>
        
                                        <div class="mt-6">
                                            <label for="note" class="block text-sm font-semibold text-gray-900">Note:</label>
                                            <textarea id="note" name="note" x-model="selectedOrder.nota" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm bg-gray-100 cursor-not-allowed" readonly></textarea>
                                        </div>
                                    </div>

                                    <div class="mt-6 lg:mt-0">
                                        <div x-show="selectedOrder.desain">
                                            <label class="block text-sm font-semibold text-gray-900">Design Picture:</label>
                                            <div class="mt-2 p-2 border border-gray-200 rounded-lg bg-gray-50 h-full flex items-center justify-center">
                                                <img :src="'/storage/' + selectedOrder.desain" alt="Batik Design" class="w-full h-auto rounded-md object-contain max-h-[450px]" crossorigin="anonymous">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="px-6 py-4 flex justify-between items-center border-t bg-gray-50 sticky bottom-0 z-10">
                            <div class="flex items-center gap-x-3">
                                <label for="status" class="block text-sm font-bold text-gray-900">Status:</label>
                                <select x-model="selectedOrder.status" id="status" name="status" class="block w-full pl-3 pr-10 py-1.5 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                    <option value="Pending">Pending</option>
                                    <option value="In Progress">In Progress</option>
                                    <option value="Ready">Ready</option>
                                    <option value="Cancelled">Cancelled</option>
                                </select>
                            </div>
                            <div class="flex gap-x-3">
                                <button type="button" @click="exportOrder()" :disabled="isExporting" class="px-6 py-2 bg-green-500 text-white rounded-lg font-semibold hover:bg-green-600 transition-colors disabled:bg-green-300 disabled:cursor-not-allowed">
                                    <span x-show="!isExporting">Export</span>
                                    <span x-show="isExporting">Exporting...</span>
                                </button>
                                <button type="button" @click="open = false" class="px-6 py-2 bg-gray-200 text-gray-800 rounded-lg font-semibold hover:bg-gray-300 transition-colors">Cancel</button>
                                <button type="button" @click="updateOrderStatus()" class="px-8 py-2 bg-cyan-500 text-white rounded-lg font-semibold hover:bg-cyan-600 transition-colors">Save</button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

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

    <div x-show="deleteUserModalOpen" @keydown.escape.window="closeDeleteModal()" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" x-cloak>
        <div @click.away="closeDeleteModal()" class="bg-white p-8 rounded-lg shadow-xl text-center">
            <h3 class="text-xl font-bold mb-4">Confirm User Deletion</h3>
            <p class="mb-6">Are you sure you want to delete this user? <br> This action cannot be undone.</p>
            <div class="flex justify-center gap-4">
                <button @click="confirmDelete()" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-6 rounded-lg">Delete</button>
                <button @click="closeDeleteModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-6 rounded-lg">Cancel</button>
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
            logoutLink.addEventListener('click', (e) => { e.preventDefault(); logoutModal.classList.remove('hidden'); });
            confirmLogoutBtn.addEventListener('click', () => { logoutForm.submit(); });
            cancelLogoutBtn.addEventListener('click', () => { logoutModal.classList.add('hidden'); });
            logoutModal.addEventListener('click', (e) => { if (e.target.id === 'logout-modal') { logoutModal.classList.add('hidden'); } });
        }

        function adminTableData() {
            return {
                activeTable: 'users',
                tableDropdownOpen: false,
                searchTerm: '',
                usersData: @json($users ?? []),
                ordersData: @json($orders ?? []),
                
                open: false,
                selectedOrder: null,
                isExporting: false,

                deleteUserModalOpen: false,
                userToDeleteId: null,

                init() {
                    if (window.location.hash === '#orders') {
                        this.activeTable = 'orders';
                    }
                    window.addEventListener('hashchange', () => {
                        if (window.location.hash === '#orders') {
                            this.activeTable = 'orders';
                        }
                    });
                },

                openDeleteModal(userId) {
                    this.userToDeleteId = userId;
                    this.deleteUserModalOpen = true;
                },
                closeDeleteModal() {
                    this.userToDeleteId = null;
                    this.deleteUserModalOpen = false;
                },
                confirmDelete() {
                    if (this.userToDeleteId) {
                        const form = document.getElementById(`delete-form-${this.userToDeleteId}`);
                        if (form) {
                            form.submit();
                        }
                    }
                    this.closeDeleteModal();
                },

                showOrderModal(order) {
                    this.selectedOrder = JSON.parse(JSON.stringify(order));
                    this.open = true;
                },

                exportOrder() {
                    if (!this.selectedOrder) return;
                    this.isExporting = true;

                    const content = document.getElementById('order-details-content');
                    content.parentElement.scrollTop = 0;

                    const waitForImagesToLoad = (element) => {
                        const images = element.querySelectorAll('img');
                        const promises = [];
                        images.forEach(img => {
                            if (!img.complete) {
                                promises.push(new Promise(resolve => {
                                    img.onload = resolve;
                                    img.onerror = resolve;
                                }));
                            }
                        });
                        return Promise.all(promises);
                    };

                    setTimeout(() => {
                        waitForImagesToLoad(content).then(() => {
                            html2canvas(content, {
                                scale: 2,
                                useCORS: true,
                            }).then(canvas => {
                                const imageData = canvas.toDataURL('image/png');
                                const pdf = new window.jspdf.jsPDF({
                                    orientation: 'l',
                                    unit: 'mm',
                                    format: 'a4'
                                });

                                const pdfWidth = pdf.internal.pageSize.getWidth();
                                const pdfHeight = pdf.internal.pageSize.getHeight();
                                const canvasAspectRatio = canvas.width / canvas.height;

                                let imgWidth = pdfWidth - 20;
                                let imgHeight = imgWidth / canvasAspectRatio;

                                if (imgHeight > pdfHeight - 20) {
                                    imgHeight = pdfHeight - 20;
                                    imgWidth = imgHeight * canvasAspectRatio;
                                }

                                const x = (pdfWidth - imgWidth) / 2;
                                const y = 10;

                                pdf.addImage(imageData, 'PNG', x, y, imgWidth, imgHeight);
                                pdf.save(`order_${this.selectedOrder.id_pesanan}_details.pdf`);

                                this.isExporting = false;
                            }).catch(error => {
                                console.error('Error during PDF export:', error);
                                alert('Could not export to PDF. Check the console for details.');
                                this.isExporting = false;
                            });
                        });
                    }, 250);
                },

                async updateOrderStatus() {
                    if (!this.selectedOrder) return;
                    try {
                        const response = await fetch(`/admin/orders/${this.selectedOrder.id_pesanan}`, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ 
                                status: this.selectedOrder.status,
                                nota: this.selectedOrder.nota
                            })
                        });

                        if (!response.ok) {
                            const errorData = await response.json();
                            throw new Error(errorData.message || 'Server responded with an error');
                        }
                        
                        const updatedOrder = await response.json();
                        const index = this.ordersData.findIndex(o => o.id_pesanan === updatedOrder.id_pesanan);
                        if (index !== -1) {
                            this.ordersData.splice(index, 1, updatedOrder);
                        }
                        this.open = false;

                    } catch (error) {
                        console.error('Error details:', error);
                        alert('Update Failed: ' + error.message);
                    }
                },

                get filteredUsers() {
                    if (!this.searchTerm) return this.usersData;
                    const term = this.searchTerm.toLowerCase();
                    return this.usersData.filter(user => 
                        (user.id && String(user.id).toLowerCase().includes(term)) ||
                        (user.name && user.name.toLowerCase().includes(term)) ||
                        (user.email && user.email.toLowerCase().includes(term))
                    );
                },

                get filteredOrders() {
                    if (!this.searchTerm) return this.ordersData;
                    const term = this.searchTerm.toLowerCase();
                    return this.ordersData.filter(order => 
                        (order.nama && order.nama.toLowerCase().includes(term)) ||
                        (order.id_pesanan && String(order.id_pesanan).toLowerCase().includes(term)) ||
                        (order.status && order.status.toLowerCase().includes(term))
                    );
                }
            }
        }
    </script>
</body>
</html>
