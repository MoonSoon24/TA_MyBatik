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
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            <span x-show="unreadCount > 0" x-text="unreadCount" class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center" x-cloak></span>
                        </button>

                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-80 bg-white rounded-md shadow-lg z-50 overflow-hidden" x-cloak>
                            <div class="p-3 font-bold border-b text-gray-800">Notifications</div>
                            <div class="max-h-96 overflow-y-auto">
                                <template x-if="unread.length === 0 && read.length === 0">
                                    <p class="text-center text-gray-500 py-4">You have no notifications.</p>
                                </template>
                                <template x-for="notification in unread" :key="notification.id">
                                    <div @click="markAsRead(notification)" class="p-3 border-b border-gray-100 hover:bg-gray-50 cursor-pointer">
                                        <p class="text-m text-gray-800" x-text="notification.title"></p>
                                        <p class="text-sm text-gray-600" x-text="notification.message"></p>
                                        <p class="text-xs text-gray-500 mt-1" x-text="new Date(notification.created_at).toLocaleString('en-GB')"></p>
                                    </div>
                                </template>
                                <template x-for="notification in read" :key="notification.id">
                                     <div class="p-3 border-b border-gray-100 bg-gray-50">
                                        <p class="text-m text-gray-800" x-text="notification.title"></p>
                                        <p class="text-sm text-gray-500" x-text="notification.message"></p>
                                        <p class="text-xs text-gray-400 mt-1" x-text="new Date(notification.created_at).toLocaleString('en-GB')"></p>
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

<script>
    function notifications() {
        return {
            open: false,
            unread: [],
            read: [],
            unreadCount: 0,
            fetchNotifications() {
                setInterval(() => {
                    fetch('{{ route("notifications.fetch") }}')
                        .then(response => response.json())
                        .then(data => {
                            this.unread = data.unread;
                            this.read = data.read;
                            this.unreadCount = data.unread.length;
                        }).catch(error => console.error('Error fetching notifications:', error));
                }, 30000);

                fetch('{{ route("notifications.fetch") }}')
                    .then(response => response.json())
                    .then(data => {
                        this.unread = data.unread;
                        this.read = data.read;
                        this.unreadCount = data.unread.length;
                    }).catch(error => console.error('Error fetching notifications:', error));
            },
            markAsRead(notification) {
                fetch(`/notifications/${notification.id}/read`, {
                    method: 'POST',
                    headers: { 
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                     },
                }).then(response => {
                    if(response.ok) {
                        const index = this.unread.findIndex(n => n.id === notification.id);
                        if (index > -1) {
                            const [readNotification] = this.unread.splice(index, 1);
                            this.read.unshift(readNotification);
                            this.unreadCount = this.unread.length;
                        }
                    }
                }).catch(error => console.error('Error marking notification as read:', error));
            }
        }
    }
</script>
