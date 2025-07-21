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
        <div class="flex items-center space-x-4">
            @auth
                <div x-data="notifications()" x-init="fetchNotifications()" class="relative">
                    
                    <button @click="open = !open" class="relative text-gray-600 hover:text-black transition">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                        <span x-show="unreadCount > 0" x-text="unreadCount" class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center" x-cloak></span>
                    </button>

                    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-80 bg-white rounded-md shadow-lg z-50 overflow-hidden" x-cloak>
                        <div class="p-3 font-bold border-b text-gray-800">Notifications</div>
                        <div class="max-h-96 overflow-y-auto">
                            <template x-if="!notifications.length"><p class="text-center text-gray-500 py-4">You have no notifications.</p></template>
                            <template x-for="notification in notifications" :key="notification.id">
                                <div class="p-3 border-b border-gray-100 flex items-start justify-between gap-3" :class="{'bg-gray-50': notification.read}">
                                    <div @click="openNotificationModal(notification)" class="flex-grow cursor-pointer">
                                        <p class="font-semibold" :class="notification.read ? 'text-gray-600' : 'text-gray-800'" x-text="notification.title"></p>
                                        <p class="text-sm" :class="notification.read ? 'text-gray-500' : 'text-gray-600'" x-text="notification.message.length > 30 ? notification.message.substring(0, 30) + '...' : notification.message"></p>
                                    </div>
                                    <div class="flex-shrink-0 pt-0.5">
                                        <template x-if="!notification.read">
                                            <button @click.stop="markAsRead(notification)" title="Mark as read">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-cyan-500 hover:text-cyan-700" viewBox="0 0 20 20" fill="currentColor"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z" /><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.022 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" /></svg>
                                            </button>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                
                    <div x-show="modalOpen" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4" x-cloak>
                        <div @click.away="modalOpen = false" @click.stop class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md">
                            <template x-if="selectedNotification">
                                <div>
                                    <div class="flex justify-between items-center border-b pb-3">
                                        <h3 class="text-xl font-bold text-gray-800" x-text="selectedNotification.title"></h3>
                                        <button @click="modalOpen = false" class="text-gray-500 hover:text-gray-800 text-2xl font-bold">&times;</button>
                                    </div>
                                    <div class="mt-4">
                                        <p class="text-gray-700" x-text="selectedNotification.message"></p>
                                        <p class="text-xs text-gray-500 mt-4" x-text="'Received on: ' + new Date(selectedNotification.created_at).toLocaleString('en-GB')"></p>
                                        <template x-if="selectedNotification.order_id">
                                            <a :href="`/receipt/${selectedNotification.order_id}`" class="block w-full text-center bg-cyan-500 text-white font-bold py-2 px-4 rounded-lg mt-6 hover:bg-cyan-600 transition">View Order Details</a>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

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

<x-logout-modal />
<x-alert />

<script>
    function notifications() {
        return {
            open: false,
            modalOpen: false,
            notifications: [],
            selectedNotification: null,
            get unreadCount() {
                return this.notifications.filter(n => !n.read).length;
            },
            fetchNotifications() {
                fetch('{{ route("notifications.fetch") }}')
                    .then(response => response.json())
                    .then(data => {
                        this.notifications = [...data.unread, ...data.read];
                    }).catch(error => console.error('Error fetching notifications:', error));

                setInterval(() => {
                    fetch('{{ route("notifications.fetch") }}')
                        .then(response => response.json())
                        .then(data => {
                            this.notifications = [...data.unread, ...data.read];
                        }).catch(error => console.error('Error fetching notifications:', error));
                }, 30000);
            },
            markAsRead(notification) {
                if (notification.read) return;

                fetch(`/notifications/${notification.id}/read`, {
                    method: 'POST',
                    headers: { 
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                }).then(response => {
                    if (response.ok) {
                        const index = this.notifications.findIndex(n => n.id === notification.id);
                        if(index > -1) {
                            this.notifications[index].read = true;
                        }
                    }
                }).catch(error => console.error('Error in fetch call:', error));
            },
            openNotificationModal(notification) {
                this.selectedNotification = notification;
                this.modalOpen = true;
            }
        }
    }
</script>