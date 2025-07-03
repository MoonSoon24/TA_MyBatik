<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Gallery - myBatik</title>
    
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
        .font-dancing { font-family: 'Dancing Script', cursive; }
        html { scroll-behavior: smooth; }
        .modal-body { max-height: 90vh; }
        .comments-container::-webkit-scrollbar { width: 8px; }
        .comments-container::-webkit-scrollbar-track { background: #f1f1f1; }
        .comments-container::-webkit-scrollbar-thumb { background: #888; border-radius: 4px; }
        .comments-container::-webkit-scrollbar-thumb:hover { background: #555; }
    </style>
</head>
<body class="bg-gray-100 font-sans text-gray-800">

    <!-- Header -->
    <header class="bg-white shadow-sm sticky top-0 z-30">
        <div class="container mx-auto flex justify-between items-center p-4 md:p-6">
            <div class="flex items-center gap-x-12">
                <div class="font-dancing text-4xl font-bold">my Batik</div>
                <nav class="hidden md:flex space-x-8">
                    <a href="/" class="font-semibold transition text-gray-700 hover:text-black">Home</a>
                    <a href="{{ route('gallery.index') }}" class="font-semibold transition text-black border-b-2 border-black">Gallery</a>
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
                        <div x-show="dropdownOpen" @click.away="dropdownOpen = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-50">
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

    <!-- Main Content: Community Gallery -->
    <main class="container mx-auto px-4 py-8" x-data="galleryPage()">
        @if($designs->isEmpty())
             <div class="text-center py-16"><p class="text-xl text-gray-500">No designs have been shared yet. Be the first!</p></div>
        @else
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-1 md:gap-4">
                @foreach($designs as $design)
                <!-- Design Card -->
                <div @click="openDetailModal({{ json_encode($design) }})" class="group relative aspect-square cursor-pointer">
                    <img src="{{ asset('storage/' . $design->image_path) }}" alt="{{ $design->title }}" class="w-full h-full object-cover rounded-md">
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition-all duration-300 flex items-center justify-center">
                        <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center space-x-6 text-white">
                            <div class="flex items-center space-x-2">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                                <span class="font-bold">{{ $design->likes->count() }}</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M21.99 4c0-1.1-.89-2-1.99-2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h14l4 4-.01-18z"/></svg>
                                <span class="font-bold">{{ $design->comments->count() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="mt-12">{{ $designs->links() }}</div>
        @endif

        <!-- Details Modal -->
        <div x-show="showDetailModal" @keydown.escape.window="showDetailModal = false" x-cloak class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-50 p-4">
            <div @click.away="showDetailModal = false" class="bg-white w-full max-w-5xl h-full max-h-[90vh] flex rounded-lg overflow-hidden">
                <!-- Image Column -->
                <div class="w-1/2 bg-black flex items-center justify-center">
                    <img :src="'/storage/' + currentDesign.image_path" :alt="currentDesign.title" class="max-w-full max-h-full object-contain">
                </div>
                <!-- Details Column -->
                <div class="w-1/2 flex flex-col">
                    <div class="p-4 border-b">
                        <h3 class="font-bold text-xl" x-text="currentDesign.title"></h3>
                        <p class="text-sm text-gray-600">by <span x-text="currentDesign.user ? currentDesign.user.name : 'Unknown'"></span></p>
                    </div>
                    <!-- Comments -->
                    <div class="flex-grow p-4 overflow-y-auto comments-container" id="modal-comments-list">
                        <!-- Comments will be populated by JS -->
                    </div>
                    <!-- Actions & Comment Form -->
                    <div class="p-4 border-t bg-gray-50">
                        <div class="flex items-center space-x-4 mb-3">
                            <form @submit.prevent="submitLike" class="like-form-modal">
                                <button type="submit" class="flex items-center space-x-1.5 text-gray-500 hover:text-red-500">
                                    <svg id="modal-like-icon" class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.636l1.318-1.318a4.5 4.5 0 116.364 6.364L12 20.364l-7.682-7.682a4.5 4.5 0 010-6.364z"></path></svg>
                                    <span class="font-semibold text-lg" id="modal-like-count" x-text="currentDesign.likes ? currentDesign.likes.length : 0"></span>
                                </button>
                            </form>
                        </div>
                        @auth
                        <form @submit.prevent="submitComment($event)" class="comment-form-modal">
                            <div class="flex items-center space-x-2">
                                <textarea name="body" rows="1" class="w-full border rounded-md px-3 py-2 text-sm resize-none" placeholder="Add a comment..."></textarea>
                                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 font-semibold">Post</button>
                            </div>
                        </form>
                        @else
                        <p class="text-sm text-center text-gray-500"><a href="{{ route('login') }}" class="text-blue-500 hover:underline">Log in</a> to comment.</p>
                        @endauth
                    </div>
                </div>
            </div>
            <button @click="showDetailModal = false" class="absolute top-4 right-4 text-white hover:text-gray-300 text-4xl">&times;</button>
        </div>
    </main>

    <!-- Floating Upload Button -->
    @auth
    <div x-data="{}" class="fixed bottom-6 left-1/2 -translate-x-1/2 z-40">
        <button @click="$dispatch('open-modal', 'upload-design')" class="bg-black text-white font-semibold py-4 px-8 rounded-full shadow-lg hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-black focus:ring-opacity-50 transition-all duration-300 text-lg flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
            Upload Design
        </button>
    </div>
    @endauth

    <!-- Upload Modal -->
    <div x-data="{ show: false }" @open-modal.window="if ($event.detail === 'upload-design') show = true" x-show="show" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-40 p-4">
        <div @click.away="show = false" class="bg-white p-8 rounded-lg shadow-xl w-full max-w-md modal-body">
            <h3 class="text-2xl font-bold mb-6 text-center">Share Your Design</h3>
            <form action="{{ route('designs.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="space-y-6">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">Design Title</label>
                        <input type="text" name="title" id="title" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="e.g., 'Ocean Waves Shirt'" required>
                    </div>
                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-700">Upload Image</label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true"><path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="image-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                        <span>Upload a file</span>
                                        <input id="image-upload" name="image" type="file" class="sr-only" required>
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG up to 2MB</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-8 flex justify-end gap-4">
                    <button type="button" @click="show = false" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-6 rounded-lg">Cancel</button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg">Share</button>
                </div>
            </form>
        </div>
    </div>

    <!-- FIXED: Logout Modal HTML is now directly in this file -->
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
    
    <footer id="contact" class="bg-gray-800 text-white py-12 mt-20"></footer>
    
    <script>
    function galleryPage() {
        return {
            showDetailModal: false,
            currentDesign: {},
            likeActionUrl: '',
            commentActionUrl: '',
            csrfToken: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            authUserId: {{ auth()->id() ?? 'null' }},

            openDetailModal(design) {
                this.currentDesign = design;
                this.likeActionUrl = `/designs/${design.id}/like`;
                this.commentActionUrl = `/designs/${design.id}/comments`;
                this.showDetailModal = true;
                
                this.$nextTick(() => {
                    this.updateLikeButton();
                    const commentsList = document.getElementById('modal-comments-list');
                    commentsList.innerHTML = ''; 
                    if (this.currentDesign.comments.length > 0) {
                        this.currentDesign.comments.forEach(comment => {
                            commentsList.innerHTML += this.createCommentHtml(comment);
                        });
                    } else {
                        commentsList.innerHTML = '<p class="text-sm text-gray-500 no-comments-placeholder">No comments yet.</p>';
                    }
                });
            },

            isLikedByUser() {
                if (!this.currentDesign.likes || !this.authUserId) return false;
                return this.currentDesign.likes.some(like => like.user_id === this.authUserId);
            },

            updateLikeButton() {
                const icon = document.getElementById('modal-like-icon');
                if (this.isLikedByUser()) {
                    icon.style.fill = 'currentColor';
                    icon.classList.add('text-red-500');
                } else {
                    icon.style.fill = 'none';
                    icon.classList.remove('text-red-500');
                }
            },

            submitLike() {
                fetch(this.likeActionUrl, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': this.csrfToken, 'Accept': 'application/json' },
                })
                .then(res => {
                    if (!res.ok) {
                        if (res.status === 401) window.location.href = '{{ route("login") }}';
                        return Promise.reject(res);
                    }
                    return res.json();
                })
                .then(data => {
                    if (data.liked) {
                        this.currentDesign.likes.push({ user_id: this.authUserId });
                    } else {
                        this.currentDesign.likes = this.currentDesign.likes.filter(like => like.user_id !== this.authUserId);
                    }
                    this.updateLikeButton();
                })
                .catch(error => console.error('Error:', error));
            },

            submitComment(event) {
                const form = event.target;
                const formData = new FormData(form);
                const textarea = form.querySelector('textarea');

                fetch(this.commentActionUrl, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': this.csrfToken, 'Accept': 'application/json' },
                    body: formData
                })
                .then(res => {
                    if (!res.ok) {
                        if (res.status === 401) window.location.href = '{{ route("login") }}';
                        return Promise.reject(res);
                    }
                    return res.json();
                })
                .then(data => {
                    // Add new comment to the local state
                    this.currentDesign.comments.unshift(data.comment);
                    
                    // Re-render comments list
                    const commentsList = document.getElementById('modal-comments-list');
                    const noCommentsPlaceholder = commentsList.querySelector('.no-comments-placeholder');
                    if (noCommentsPlaceholder) noCommentsPlaceholder.remove();
                    
                    commentsList.insertAdjacentHTML('afterbegin', this.createCommentHtml(data.comment));
                    textarea.value = '';
                })
                .catch(error => console.error('Error:', error));
            },

            createCommentHtml(comment) {
                const date = new Date(comment.created_at);
                const formattedDate = date.toLocaleString('en-US', { month: 'short', day: 'numeric' });
                const userName = comment.user ? comment.user.name : 'A User';
                return `
                    <div class="text-sm flex items-start space-x-3 mb-3">
                        <div class="flex-shrink-0 w-8 h-8 bg-gray-300 rounded-full"></div>
                        <div>
                            <p><strong class="font-semibold">${userName}:</strong> ${comment.body}</p>
                            <p class="text-xs text-gray-400 mt-1">${formattedDate}</p>
                        </div>
                    </div>
                `;
            }
        }
    }

    document.addEventListener('alpine:init', () => {
        Alpine.data('galleryPage', galleryPage);
    });
    
    // Logout Modal Script
    document.addEventListener('DOMContentLoaded', function() {
        const logoutLink = document.getElementById('logout-link');
        if(logoutLink) {
            const logoutModal = document.getElementById('logout-modal');
            const confirmLogoutBtn = document.getElementById('confirm-logout-btn');
            const cancelLogoutBtn = document.getElementById('cancel-logout-btn');
            const logoutForm = document.getElementById('logout-form');

            logoutLink.addEventListener('click', (e) => {
                e.preventDefault();
                logoutModal.classList.remove('hidden');
            });
            cancelLogoutBtn.addEventListener('click', () => logoutModal.classList.add('hidden'));
            confirmLogoutBtn.addEventListener('click', () => logoutForm.submit());
        }
    });
    </script>
</body>
</html>
