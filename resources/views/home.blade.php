<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Batik</title>
    
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
        
        .star-rating-display .star {
            font-size: 1.5rem;
            color: #d1d5db;
        }
        .star-rating-display .star.filled {
            color: #f59e0b;
        }
    </style>
</head>
<body class="bg-gray-100 font-sans text-gray-800">

    <x-header />
    
    <main class="flex flex-col items-center justify-center py-12 md:py-24 px-4">
        <div class="bg-white rounded-2xl shadow-lg p-8 md:p-12 w-full max-w-5xl">
            <div class="flex flex-col md:flex-row justify-around items-center gap-8">
                <div class="text-center flex flex-col items-center">
                    <img src="{{ asset('images/home_1.png') }}" alt="A blue shirt representing design creation" class="h-64 w-auto mb-4" onerror="this.onerror=null;this.src='https://placehold.co/256x256';">
                    <p class="font-semibold text-lg">Create your design!</p>
                </div>
                <div class="text-4xl text-blue-500 font-light transform md:rotate-0 rotate-90">&rarr;</div>
                <div class="text-center flex flex-col items-center">
                    <img src="{{ asset('images/home_2.png') }}" alt="A hand choosing a size" class="h-64 w-auto mb-4" onerror="this.onerror=null;this.src='https://placehold.co/256x256';">
                    <p class="font-semibold text-lg">Choose your size</p>
                </div>
                <div class="text-4xl text-blue-500 font-light transform md:rotate-0 rotate-90">&rarr;</div>
                <div class="text-center flex flex-col items-center">
                    <img src="{{ asset('images/home_3.png') }}" alt="A person working on a sewing pattern" class="h-64 w-auto mb-4" onerror="this.onerror=null;this.src='https://placehold.co/256x256';">
                    <p class="font-semibold text-lg">Let us do the work!</p>
                </div>
            </div>
        </div>
    </main>

    <section id="reviews" class="py-16 md:py-24 bg-gray-100">
        <div class="container mx-auto px-6 md:px-12">
            <h2 class="text-3xl font-bold text-center mb-12">What Our Customers Say</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-6xl mx-auto">
                @forelse ($reviews as $review)
                    <div class="bg-white rounded-xl shadow-lg p-8 flex flex-col">
                        <div class="flex-grow">
                            <p class="text-gray-600 italic mb-4">"{{ $review->comment }}"</p>
                        </div>
                        <div>
                            <div class="star-rating-display flex items-center mb-2">
                                @for ($i = 1; $i <= 5; $i++)
                                    <span class="star {{ $i <= $review->rating ? 'filled' : '' }}">★</span>
                                @endfor
                            </div>
                            <p class="font-bold text-gray-900">
                                @php
                                    $name = $review->user->name;
                                    $length = strlen($name);
                                    $displayName = substr($name, 0, 2) . str_repeat('*', max(0, $length - 2));
                                    echo htmlspecialchars($displayName);
                                @endphp
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="col-span-1 md:col-span-2 lg:col-span-3 text-center text-gray-500">
                        <p>No reviews yet. Be the first to share your experience!</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <section id="about" class="py-16 md:py-24 bg-white">
        <div class="container mx-auto px-6 md:px-12 text-center">
            <h2 class="text-3xl font-bold mb-4">About Us</h2>
            <p class="max-w-3xl mx-auto text-gray-600">
                Welcome to myBatik, where ancient tradition meets modern expression. We are passionate about preserving the rich heritage of Indonesian Batik, a UNESCO Intangible Cultural Heritage of Humanity. Our mission is to bring this beautiful art form to the world, allowing you to create personalized apparel that tells a story.
            </p>
        </div>
    </section>

    <section id="faq" class="py-16 md:py-24 bg-white">
        <div class="container mx-auto px-6 md:px-12">
            <h2 class="text-3xl font-bold text-center mb-12">Frequently Asked Questions</h2>
            <div class="max-w-3xl mx-auto space-y-8">
                <div>
                    <h3 class="font-semibold text-lg mb-2">How do I create my own Batik design?</h3>
                    <p class="text-gray-600">Simply click the "Create" button! Our design tool allows you to choose from traditional patterns, upload your own motifs, and select colors.</p>
                </div>
                <div>
                    <h3 class="font-semibold text-lg mb-2">What materials do you use?</h3>
                    <p class="text-gray-600">We use high-quality, natural fabrics like primissima cotton and silk, which are ideal for the Batik process.</p>
                </div>
                <div>
                    <h3 class="font-semibold text-lg mb-2">How long does it take to receive my order?</h3>
                    <p class="text-gray-600">Because each piece is handmade, the process takes time. Please allow 4-6 weeks for creation and an additional 1-2 weeks for shipping.</p>
                </div>
            </div>
        </div>
    </section>

    <div class="fixed bottom-6 left-1/2 -translate-x-1/2 z-40">
        <a href="/create" class="bg-black text-white font-semibold py-4 px-16 rounded-full shadow-lg hover:bg-gray-800 transition-all duration-300 text-lg">Create</a>
    </div>

    <footer id="contact" class="bg-gray-800 text-white py-12">
        <div class="container mx-auto px-6 md:px-12 text-center">
            <h2 class="text-2xl font-bold mb-4">Contact Us</h2>
            <p class="mb-2">Have a question? We'd love to hear from you.</p>
            <p class="font-semibold">mybatik@mybatik.com</p>
            <p class="mt-1">+62 21 1234 5678</p>
            <p class="mt-1">Jalan Batik No. 10, Yogyakarta, Indonesia</p>
            <div class="flex justify-center space-x-6 mt-6">
                <a href="#" class="hover:text-gray-300">Facebook</a>
                <a href="#" class="hover:text-gray-300">Instagram</a>
            </div>
            <div class="mt-8 border-t border-gray-700 pt-6">
                <p class="text-sm text-gray-400">&copy; 2025 myBatik. All Rights Reserved.</p>
            </div>
        </div>
    </footer>

    <x-logout-modal />
    <x-alert />
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            
            @if (session('success'))
                window.dispatchEvent(new CustomEvent('alert', {
                    detail: { type: 'success', message: "{{ session('success') }}" }
                }));
            @endif

            @if ($errors->any())
                window.dispatchEvent(new CustomEvent('alert', {
                    detail: { type: 'error', message: "{{ $errors->first() }}" }
                }));
            @endif
        });
    </script>
    
</body>
</html>