<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Batik - Checkout</title>
    
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
        .focus\:ring-cyan-400:focus { --tw-ring-color: #22d3ee; }
        .text-cyan-600 { color: #0891b2; }
        .modal-content { transition: all 0.3s ease-out; }
        .modal-container { transition: opacity 0.3s ease; }
        body.modal-active { overflow: hidden; }
    </style>
</head>
<body class="bg-gray-100 font-sans text-gray-800 pb-28">

    <x-header />
    
    <main class="container mx-auto py-8 md:py-12 px-4">
        <form id="checkout-form" method="POST" action="{{ route('checkout.store') }}">
            @csrf 
            <div class="flex flex-col lg:flex-row gap-8 items-start">
                
                <div class="w-full lg:w-2/3 bg-white p-6 md:p-8 rounded-xl shadow-md">
                    <h2 class="text-2xl font-semibold mb-6">Contact Information</h2>
                    <div class="space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label for="first-name" class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                                <input type="text" id="first-name" name="first_name" placeholder="Enter your first name" class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-cyan-400 transition" required>
                            </div>
                            <div>
                                <label for="last-name" class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                                <input type="text" id="last-name" name="last_name" placeholder="Enter your last name" class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-cyan-400 transition" required>
                            </div>
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                            <input type="email" id="email" name="email" value="{{ auth()->user()->email }}" class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-cyan-400 transition" required>
                        </div>
                        <div>
                            <label for="street-address" class="block text-sm font-medium text-gray-700 mb-1">Street Address</label>
                            <input type="text" id="street-address" name="street_address" placeholder="Enter your street address" class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-cyan-400 transition" required>
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                            <input type="tel" id="phone" name="phone" placeholder="Enter your phone number" class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-cyan-400 transition" required>
                        </div>
                        <input type="hidden" id="additional-note" name="additional_note">
                        <div>
                            <button type="button" id="add-note-btn" class="mt-2 bg-white border-2 border-cyan-500 text-cyan-500 font-semibold py-2 px-5 rounded-lg hover:bg-cyan-500 hover:text-white transition-all duration-300">Add Note</button>
                        </div>
                    </div>
                </div>

                <div class="w-full lg:w-1/3">
                    <div class="bg-white p-6 md:p-8 rounded-xl shadow-md">
                        <h2 class="text-2xl font-semibold mb-6">Order Summary</h2>
                        
                        <div class="flex items-center mb-6">
                            <img src="{{ $uploadedBatikUrl ?? 'https://placehold.co/80x80/e0e0e0/757575?text=No+Image' }}" alt="Custom Batik Design" class="w-20 h-20 rounded-lg border p-1 mr-4 object-cover">
                            <div>
                                <h3 class="font-semibold text-lg">Custom Batik</h3>
                                <p class="text-gray-600 text-sm">Size: {{ session('order_details.ukuran') ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <div class="space-y-3 text-gray-700">
                            <div class="flex justify-between text-gray-700">
                        <span>Product Price</span>
                        <span class="font-medium">Rp. 250.000</span>
                    </div>
                    <div class="flex justify-between text-gray-700">
                        <span>Customization</span>
                        <span class="font-medium">Rp. 50.000</span>
                    </div>
                            @if($discountAmount > 0)
                            <div class="flex justify-between text-green-600 font-semibold">
                                <span>Discount ({{ session('promo.code') }})</span>
                                <span>- Rp. {{ number_format($discountAmount, 0, ',', '.') }}</span>
                            </div>
                            @endif
                        </div>

                        <div class="border-t my-4"></div>

                        <div class="flex justify-between font-bold text-lg mb-6">
                            <span>Total</span>
                            <span>Rp. {{ number_format($finalPrice, 0, ',', '.') }}</span>
                        </div>

                        <div class="mb-4">
                            @if (session()->has('promo'))
                                <div class="flex justify-between items-center bg-green-100 text-green-800 p-3 rounded-lg">
                                    <p>Promo applied: <span class="font-bold">{{ session('promo.code') }}</span></p>
                                    <a href="{{ route('promo.remove') }}" class="font-semibold text-sm hover:underline">Remove</a>
                                </div>
                            @else
                                <div class="flex gap-2">
                                    <input type="text" id="promo-code-input" placeholder="Enter Promo Code" class="w-full border border-gray-300 rounded-lg p-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400 transition">
                                    <button type="button" id="apply-promo-btn" class="bg-gray-800 text-white font-semibold py-2 px-4 rounded-lg hover:bg-gray-700 transition-all duration-300">Apply</button>
                                </div>
                            @endif

                            @if ($errors->has('promo_code'))
                                <p class="mt-2 text-sm text-red-600">{{ $errors->first('promo_code') }}</p>
                            @elseif (session('success'))
                                <p class="mt-2 text-sm text-green-600">{{ session('success') }}</p>
                            @endif
                        </div>

                        <h3 class="text-lg font-semibold mb-4">Payment Methods</h3>
                        <div class="space-y-3">
                            <label for="bank-transfer" class="flex items-center border border-gray-300 rounded-lg p-3 cursor-pointer hover:border-cyan-500 transition">
                                <input id="bank-transfer" name="payment_method" type="radio" value="bank_transfer" class="focus:ring-cyan-500 h-4 w-4 text-cyan-600 border-gray-300" checked>
                                <span class="ml-3 block text-sm font-medium text-gray-700">Bank Transfer</span>
                            </label>
                            <label for="qris" class="flex items-center border border-gray-300 rounded-lg p-3 cursor-pointer hover:border-cyan-500 transition">
                                <input id="qris" name="payment_method" type="radio" value="qris" class="focus:ring-cyan-500 h-4 w-4 text-cyan-600 border-gray-300">
                                <span class="ml-3 block text-sm font-medium text-gray-700">Q-RIS</span>
                            </label>
                        </div>
                        
                        <button type="submit" class="w-full bg-cyan-500 text-white font-bold py-3 px-4 rounded-lg mt-6 hover:bg-cyan-600 transition-all duration-300 shadow-md hover:shadow-lg">
                            Place Order
                        </button>
                        <a href="{{ route('ukuran') }}" class="block text-center mt-4 text-sm text-cyan-600 hover:underline font-semibold">Back to measurement</a>
                    </div>
                </div>
            </div>
        </form>
    </main>

    <x-logout-modal />
    <x-alert />
    
    <div id="note-modal" class="modal-container fixed inset-0 z-50 flex items-center justify-center p-4 opacity-0 pointer-events-none" style="background-color: rgba(0,0,0,0.5);">
        <div class="modal-content bg-white w-full max-w-md p-6 rounded-xl shadow-lg transform -translate-y-10 opacity-0">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold">Additional Note</h3>
                <button id="close-note-modal-btn" class="text-gray-500 hover:text-gray-800 text-2xl">&times;</button>
            </div>
            <textarea id="note-textarea" class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-cyan-400 transition" rows="5" placeholder="Enter any additional information here..."></textarea>
            <div class="mt-5 text-right">
                <button id="save-note-btn" class="bg-cyan-500 text-white font-semibold py-2 px-6 rounded-lg hover:bg-cyan-600 transition-all duration-300">Save Note</button>
            </div>
        </div>
    </div>
    <div id="bank-transfer-modal" class="modal-container fixed inset-0 z-50 flex items-center justify-center p-4 opacity-0 pointer-events-none" style="background-color: rgba(0,0,0,0.6);">
        <div class="modal-content bg-white w-full max-w-lg p-8 rounded-2xl shadow-lg transform -translate-y-10 opacity-0">
            <div class="flex justify-between items-start mb-4">
                <h3 class="text-2xl font-bold">Bank Transfer Payment</h3>
                <button id="close-bank-modal-btn" class="text-gray-500 hover:text-gray-800 text-2xl font-bold leading-none disabled:opacity-50 disabled:cursor-not-allowed" disabled>&times;</button>
            </div>
            <p class="text-gray-600 mb-6">Please use the following details to make your bank transfer:</p>
            <div class="space-y-4 text-lg mb-6">
                <div class="flex justify-between"><span class="text-gray-500">Bank Name:</span><span class="font-semibold">BNI</span></div>
                <div class="flex justify-between"><span class="text-gray-500">Account Number:</span><span class="font-semibold">0123456789</span></div>
                <div class="flex justify-between"><span class="text-gray-500">Account Name:</span><span class="font-semibold">Designer Batik</span></div>
            </div>
            <div class="flex justify-between items-center border-t pt-6">
               <p class="text-red-500 font-semibold text-sm">For safety reason, this is the only time you will<br>see this information</p>
               <p class="text-xl font-bold">Total Payment: <span class="text-cyan-600">Rp. {{ number_format($finalPrice, 0, ',', '.') }}</span></p>
            </div>
        </div>
    </div>
    <div id="qris-modal" class="modal-container fixed inset-0 z-50 flex items-center justify-center p-4 opacity-0 pointer-events-none" style="background-color: rgba(0,0,0,0.6);">
        <div class="modal-content bg-white w-full max-w-lg p-8 rounded-2xl shadow-lg transform -translate-y-10 opacity-0">
            <div class="flex justify-between items-start mb-4">
                <h3 class="text-2xl font-bold">Q-RIS Payment</h3>
                <button id="close-qris-modal-btn" class="text-gray-500 hover:text-gray-800 text-2xl font-bold leading-none disabled:opacity-50 disabled:cursor-not-allowed" disabled>&times;</button>
            </div>
            <div class="text-center py-8">
                <p class="text-gray-600">Q-RIS payment placeholder. The QR code will be displayed here.</p>
                <div class="w-48 h-48 bg-gray-200 mx-auto mt-4 flex items-center justify-center">
                    <span class="text-gray-500">QRIS Code</span>
                </div>
            </div>
             <div class="flex justify-end items-center border-t pt-6">
                 <p class="text-xl font-bold">Total Payment: <span class="text-cyan-600">Rp. {{ number_format($finalPrice, 0, ',', '.') }}</span></p>
            </div>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const addNoteBtn = document.getElementById('add-note-btn');
            const noteModal = document.getElementById('note-modal');
            const bankTransferModal = document.getElementById('bank-transfer-modal');
            const qrisModal = document.getElementById('qris-modal');
            const closeBankModalBtn = document.getElementById('close-bank-modal-btn');
            const closeQrisModalBtn = document.getElementById('close-qris-modal-btn');
            const checkoutForm = document.getElementById('checkout-form');

            function openModal(modal) {
                const modalContent = modal.querySelector('.modal-content');
                modal.classList.remove('opacity-0', 'pointer-events-none');
                modalContent.classList.remove('opacity-0', '-translate-y-10');
                document.body.classList.add('modal-active');
            }

            function closeModal(modal) {
                const modalContent = modal.querySelector('.modal-content');
                modalContent.classList.add('opacity-0', '-translate-y-10');
                modal.classList.add('opacity-0', 'pointer-events-none');
                document.body.classList.remove('modal-active');
            }

            // Note modal logic
            addNoteBtn.addEventListener('click', () => openModal(noteModal));
            document.getElementById('close-note-modal-btn').addEventListener('click', () => closeModal(noteModal));
            document.getElementById('save-note-btn').addEventListener('click', () => {
                document.getElementById('additional-note').value = document.getElementById('note-textarea').value;
                closeModal(noteModal);
            });
            noteModal.addEventListener('click', (event) => {
                if (event.target === noteModal) closeModal(noteModal);
            });


            // --- **NEW SCRIPT TO FIX PROMO SUBMISSION** ---
            const applyPromoBtn = document.getElementById('apply-promo-btn');
            if (applyPromoBtn) {
                applyPromoBtn.addEventListener('click', () => {
                    const promoCodeInput = document.getElementById('promo-code-input');
                    if (!promoCodeInput.value) {
                        alert('Please enter a promo code.');
                        return;
                    }
                    
                    // Create a new form element in memory
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route("promo.apply") }}';
                    
                    // Add CSRF token
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    form.appendChild(csrfToken);
                    
                    // Add promo code input
                    const promoInput = document.createElement('input');
                    promoInput.type = 'hidden';
                    promoInput.name = 'promo_code';
                    promoInput.value = promoCodeInput.value;
                    form.appendChild(promoInput);
                    
                    // Append the form to the body, submit it, and then remove it
                    document.body.appendChild(form);
                    form.submit();
                    document.body.removeChild(form);
                });
            }

            // Main checkout form submission logic
            checkoutForm.addEventListener('submit', (e) => {
                e.preventDefault();
                const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
                if (paymentMethod === 'bank_transfer') {
                    openModal(bankTransferModal);
                    startPaymentTimer(bankTransferModal);
                } else if (paymentMethod === 'qris') {
                    openModal(qrisModal);
                    startPaymentTimer(qrisModal);
                }
            });

            function startPaymentTimer(modal) {
                let countdown = 5;
                const closeBtn = modal.querySelector('button[id^="close-"]');
                closeBtn.disabled = true;
                closeBtn.innerHTML = countdown;
                const timerInterval = setInterval(() => {
                    countdown--;
                    if (countdown > 0) {
                        closeBtn.innerHTML = countdown;
                    } else {
                        clearInterval(timerInterval);
                        closeBtn.innerHTML = '&times;';
                        closeBtn.disabled = false;
                    }
                }, 1000);
            }

            function finalizeOrder() {
                // This is the only place where the main form is submitted
                checkoutForm.submit();
            }

            closeBankModalBtn.addEventListener('click', () => {
                if (!closeBankModalBtn.disabled) {
                    closeModal(bankTransferModal);
                    finalizeOrder();
                }
            });
            
            closeQrisModalBtn.addEventListener('click', () => {
                if (!closeQrisModalBtn.disabled) {
                    closeModal(qrisModal);
                    finalizeOrder();
                }
            });
        });
    </script>

</body>
</html>