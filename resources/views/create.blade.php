<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Batik - Studio</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:ital@1@400;700&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
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

        body {
            font-family: 'Inter', sans-serif;
        }
        .logo {
            font-family: 'Playfair Display', serif;
            font-style: italic;
        }
        .active-garment-btn {
            background-color: #333;
            color: white;
        }
        .inactive-garment-btn {
            background-color: #e5e7eb;
            color: #374151;
        }
        #customMotifInput {
            display: none;
        }
        #color-picker-container {
            position: relative;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            background: conic-gradient(from 180deg at 50% 50%, #ff0000, #ffc800, #00ff00, #00ffff, #0000ff, #ff00ff, #ff0000);
        }
        #clothColor {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }
        .motif-item {
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }
        .motif-item:hover {
            transform: scale(1.05);
        }
        
        #canvas-container {
            position: relative;
            width: 100%;
            max-width: 500px;
            margin: auto;
            aspect-ratio: 1 / 1;
            overflow: hidden;
        }
        .masked-container {
            width: 100%;
            height: 100%;
            position: relative;
            -webkit-mask-size: contain;
            mask-size: contain;
            -webkit-mask-repeat: no-repeat;
            mask-repeat: no-repeat;
            -webkit-mask-position: center;
            mask-position: center;
        }
        #shirt-container {
            -webkit-mask-image: url('images/custom_shirt_design.png');
            mask-image: url('images/custom_shirt_design.png');
        }
        #dress-container {
            -webkit-mask-image: url('images/custom_dress_design.png');
            mask-image: url('images/custom_dress_design.png');
        }
        .canvas-layer { 
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
        .motif-canvas {
            background-color: #ffffff;
        }
        .garment-outline {
            background-size: contain;
            background-position: center;
            background-repeat: no-repeat;
            pointer-events: none;
        }
        #garment-outline-shirt {
            background-image: url('images/shirt_outline.png');
        }
        #garment-outline-dress {
            background-image: url('images/dress_outline.png');
        }
        .motif-image {
            position: absolute;
            cursor: grab;
            user-select: none;
            -webkit-user-drag: none;
        }
        
        #control-box {
            position: absolute;
            border: 2px dashed #0ea5e9;
            pointer-events: none;
            display: none;
        }
        .handle {
            position: absolute;
            width: 12px;
            height: 12px;
            background-color: #0ea5e9;
            border: 2px solid white;
            border-radius: 50%;
            pointer-events: auto;
        }
        .handle.resize {
            cursor: nwse-resize;
        }
        .handle.br {
            bottom: -8px;
            right: -8px;
        }
        .handle.bl {
            bottom: -8px;
            left: -8px;
            cursor: nesw-resize;
        }
        .handle.tr {
            top: -8px;
            right: -8px;
            cursor: nesw-resize;
        }
        .handle.tl {
            top: -8px;
            left: -8px;
        }
        .handle.rotate {
            top: -25px;
            left: 50%;
            transform: translateX(-50%);
            cursor: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="%230ea5e9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 4v6h-6"/><path d="M1 20v-6h6"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/></svg>') 12 12, auto;
            width: 16px;
            height: 16px;
        }
        .handle.mirror {
            left: -25px;
            top: 50%;
            transform: translateY(-50%);
            cursor: e-resize;
            width: 16px;
            height: 16px;
        }
        #delete-btn {
            position: absolute;
            top: -12px;
            right: -12px;
            width: 24px;
            height: 24px;
            background-color: red;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            pointer-events: auto;
            font-weight: bold;
            font-size: 14px;
        }
    </style>
</head>
<body class="bg-gray-100 font-sans text-gray-800 pb-28" style="padding-bottom: 0px;">

    <header class="bg-white shadow-sm sticky top-0 z-50">
        <div class="container mx-auto flex justify-between items-center p-4 md:p-6">
        
            <div class="flex items-center gap-x-12">
                <div class="font-dancing text-4xl font-bold">my Batik</div>
                <nav class="hidden md:flex space-x-8">
                    <a href="/home" class="font-semibold text-gray-700 hover:text-black transition">Home</a>
                    @auth
                    <a href="/history" class="font-semibold text-gray-700 hover:text-black transition">Orders</a>
                    @else
                    @endguest
                </nav>
            </div>
            
            <div class="flex items-center space-x-3">
                @auth
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
                @else
                    <a href="/login" class="font-semibold text-gray-700 hover:text-black transition">Sign In</a>
                    <div class="w-8 h-8">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd" />
                        </svg>
                    </div>
                @endguest
            </div>
        </div>
    </header>

    <main class="w-full max-w-screen-2xl mx-auto bg-white rounded-2xl shadow-lg p-4 sm:p-8 flex flex-col lg:flex-row gap-8 mt-8">

        <div class="flex-grow lg:w-2/3 bg-gray-50 rounded-xl p-6 flex flex-col">
            <div class="flex items-center bg-gray-200 rounded-full p-1 w-max mb-6">
                <button id="shirtBtn" class="px-6 py-2 rounded-full text-lg font-semibold active-garment-btn">Shirt</button>
                <button id="dressBtn" class="px-6 py-2 rounded-full text-lg font-semibold inactive-garment-btn">Dress</button>
            </div>

            <div id="canvas-container">
                 <div id="shirt-container" class="masked-container">
                     <div id="motif-canvas-shirt" class="canvas-layer motif-canvas"></div>
                     <div id="garment-outline-shirt" class="canvas-layer garment-outline"></div>
                 </div>
                 <div id="dress-container" class="masked-container" style="display:none;">
                     <div id="motif-canvas-dress" class="canvas-layer motif-canvas"></div>
                     <div id="garment-outline-dress" class="canvas-layer garment-outline"></div>
                 </div>
                 <div id="control-box">
                     <div class="handle resize tl"></div>
                     <div class="handle resize tr"></div>
                     <div class="handle resize bl"></div>
                     <div class="handle resize br"></div>
                     <div class="handle rotate"></div>
                     <div class="handle mirror"></div>
                     <div id="delete-btn">×</div>
                 </div>
            </div>

            <div class="flex flex-col sm:flex-row items-center justify-between mt-6 gap-4">
                <div class="flex items-center gap-4">
                    <span class="text-lg font-medium">Cloth Color</span>
                    <div id="color-picker-container">
                        <input type="color" id="clothColor" value="#ffffff">
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <button id="undoBtn" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-3 px-6 rounded-lg flex items-center gap-2 disabled:opacity-50" disabled>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9h13.5a3.5 3.5 0 0 1 0 7H11"/><path d="m7 13-4-4 4-4"/></svg>
                        Undo
                    </button>
                    <button id="redoBtn" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-3 px-6 rounded-lg flex items-center gap-2 disabled:opacity-50" disabled>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 9H7.5a3.5 3.5 0 0 0 0 7H13"/><path d="m17 13 4-4-4-4"/></svg>
                        Redo
                    </button>
                    <button id="resetBtn" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-3 px-6 rounded-lg flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.5 2v6h-6M2.5 22v-6h6M2 11.5a10 10 0 0 1 18.8-4.3M22 12.5a10 10 0 0 1-18.8 4.3"/></svg>
                        Reset
                    </button>
                    <button id="downloadBtn" class="bg-cyan-500 hover:bg-cyan-600 text-white font-bold py-3 px-6 rounded-lg flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                        Download Design
                    </button>
                </div>
            </div>
        </div>

        <div class="lg:w-1/3 bg-gray-50 rounded-xl p-8 flex flex-col justify-between">
            <div>
                <h2 class="text-2xl font-bold mb-4">Batik Motifs</h2>
                <input type="file" id="customMotifInput" accept="image/*">
                <button id="uploadBtn" class="w-full bg-cyan-500 hover:bg-cyan-600 text-white font-bold py-3 px-6 rounded-lg flex items-center justify-center gap-2 mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                    Upload Custom Motifs
                </button>

                <h3 class="text-lg font-semibold mb-3">Default Motifs</h3>
                <div class="grid grid-cols-3 gap-4" id="default-motifs-container">
                    <img src="images\batik1.png" class="motif-item rounded-lg cursor-pointer border-2 border-transparent" alt="Batik Motif 1">
                    <img src="images\batik2.png" class="motif-item rounded-lg cursor-pointer border-2 border-transparent" alt="Batik Motif 2">
                    <img src="images\batik3.png" class="motif-item rounded-lg cursor-pointer border-2 border-transparent" alt="Batik Motif 3">
                </div>
                 <div id="custom-motif-thumbnail-container" class="grid grid-cols-3 gap-4 mt-4"></div>
            </div>
            @auth
                <a href="/ukuran" class="w-full bg-cyan-500 hover:bg-cyan-600 text-white font-bold py-4 px-6 rounded-lg flex items-center justify-center gap-2 mt-8 text-lg">
                    Continue Order
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </a>
            @else
                <a href="/login" class="w-full bg-cyan-500 hover:bg-cyan-600 text-white font-bold py-4 px-6 rounded-lg flex items-center justify-center gap-2 mt-8 text-lg">
                    Continue Order
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </a>
            @endguest
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
    
    <div id="reset-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white p-8 rounded-lg shadow-xl text-center">
            <h3 class="text-xl font-bold mb-4">Confirm Reset</h3>
            <p class="mb-6">Are you sure you want to clear your design? This action cannot be undone.</p>
            <div class="flex justify-center gap-4">
                <button id="confirm-reset-btn" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-6 rounded-lg">Reset</button>
                <button id="cancel-reset-btn" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-6 rounded-lg">Cancel</button>
            </div>
        </div>
    </div>


    <script>
    document.addEventListener('DOMContentLoaded', () => {

        const logoutLink = document.getElementById('logout-link');
        const logoutForm = document.getElementById('logout-form');
        const logoutModal = document.getElementById('logout-modal');
        const confirmLogoutBtn = document.getElementById('confirm-logout-btn');
        const cancelLogoutBtn = document.getElementById('cancel-logout-btn');
        const shirtBtn = document.getElementById('shirtBtn');
        const dressBtn = document.getElementById('dressBtn');
        const shirtContainer = document.getElementById('shirt-container');
        const dressContainer = document.getElementById('dress-container');
        const clothColorInput = document.getElementById('clothColor');
        const resetBtn = document.getElementById('resetBtn');
        const downloadBtn = document.getElementById('downloadBtn');
        const uploadBtn = document.getElementById('uploadBtn');
        const customMotifInput = document.getElementById('customMotifInput');
        const defaultMotifsContainer = document.getElementById('default-motifs-container');
        const customMotifThumbnailContainer = document.getElementById('custom-motif-thumbnail-container');
        const controlBox = document.getElementById('control-box');
        const deleteBtn = document.getElementById('delete-btn');
        const resetModal = document.getElementById('reset-modal');
        const confirmResetBtn = document.getElementById('confirm-reset-btn');
        const cancelResetBtn = document.getElementById('cancel-reset-btn');
        const undoBtn = document.getElementById('undoBtn');
        const redoBtn = document.getElementById('redoBtn');

        let activeMotifCanvas = null;
        let selectedElement = null;
        let currentAction = null;
        let startX, startY, startLeft, startTop, startWidth, startHeight, startAngle;

        let undoStack = [];
        let redoStack = [];
        let lastSavedState = '';

        // logout
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

        // undo/redo
        function saveState() {
            if (!activeMotifCanvas) return;
            
            const currentState = {
                html: activeMotifCanvas.innerHTML,
                color: activeMotifCanvas.style.backgroundColor
            };

            const currentStateString = JSON.stringify(currentState);

 
            if (currentStateString === lastSavedState) {
                return;
            }
            lastSavedState = currentStateString;

            undoStack.push(currentState);
            redoStack = [];
            updateUndoRedoButtons();
        }

        function restoreState(state) {
            if (!activeMotifCanvas || !state) return;
            
            activeMotifCanvas.innerHTML = state.html;
            activeMotifCanvas.style.backgroundColor = state.color;
            clothColorInput.value = rgbToHex(state.color);
            
            activeMotifCanvas.querySelectorAll('.motif-image').forEach(image => {
                image.addEventListener('mousedown', (e) => selectElement(e, image));
            });

            deselectElement();
            lastSavedState = JSON.stringify(state);
        }

        function undo() {
            if (undoStack.length <= 1) return;

            const currentState = undoStack.pop();
            redoStack.push(currentState);

            const prevState = undoStack[undoStack.length - 1];
            restoreState(prevState);
            updateUndoRedoButtons();
        }

        function redo() {
            if (redoStack.length === 0) return;

            const nextState = redoStack.pop();
            undoStack.push(nextState);
            
            restoreState(nextState);
            updateUndoRedoButtons();
        }

        function updateUndoRedoButtons() {
            undoBtn.disabled = undoStack.length <= 1;
            redoBtn.disabled = redoStack.length === 0;
        }
        
        undoBtn.addEventListener('click', undo);
        redoBtn.addEventListener('click', redo);
        document.addEventListener('keydown', (e) => {
            if (e.ctrlKey || e.metaKey) {
                if (e.key === 'z') {
                    e.preventDefault();
                    undo();
                } else if (e.key === 'y') {
                    e.preventDefault();
                    redo();
                }
            }
        });

        // add motif
        function addMotif(imageUrl) {
            if (!activeMotifCanvas) return;

            const image = document.createElement('img');
            image.src = imageUrl;
            image.className = 'motif-image';
            image.style.width = '150px';
            image.style.height = 'auto';
            image.style.left = '175px';
            image.style.top = '175px';
            image.style.transform = 'rotate(0deg) scaleX(1)';

            activeMotifCanvas.appendChild(image);
            
            image.addEventListener('mousedown', (e) => selectElement(e, image));

            saveState();
        }

        defaultMotifsContainer.addEventListener('click', (e) => {
            if (e.target.classList.contains('motif-item')) {
                addMotif(e.target.src);
            }
        });
        
        // motif control
        function selectElement(e, element) {
            selectedElement = element;
            updateControlBox();
            controlBox.style.display = 'block';
            startDrag(e);
        }

        function deselectElement() {
            selectedElement = null;
            controlBox.style.display = 'none';
        }

        function updateControlBox() {
            if (!selectedElement) return;
            const rect = selectedElement.getBoundingClientRect();
            const containerRect = activeMotifCanvas.getBoundingClientRect();

            controlBox.style.left = `${rect.left - containerRect.left}px`;
            controlBox.style.top = `${rect.top - containerRect.top}px`;
            controlBox.style.width = `${rect.width}px`;
            controlBox.style.height = `${rect.height}px`;
            controlBox.style.transform = selectedElement.style.transform;
        }

        function startDrag(e) {
            e.preventDefault();
            currentAction = 'drag';
            startX = e.clientX;
            startY = e.clientY;
            startLeft = selectedElement.offsetLeft;
            startTop = selectedElement.offsetTop;
            document.addEventListener('mousemove', handleMouseMove);
            document.addEventListener('mouseup', handleMouseUp);
        }

        controlBox.querySelectorAll('.resize').forEach(handle => {
            handle.addEventListener('mousedown', (e) => {
                e.preventDefault();
                e.stopPropagation();
                currentAction = 'resize';
                startX = e.clientX;
                startY = e.clientY;
                startWidth = selectedElement.offsetWidth;
                startHeight = selectedElement.offsetHeight;
                startLeft = selectedElement.offsetLeft;
                startTop = selectedElement.offsetTop;
                document.addEventListener('mousemove', handleMouseMove);
                document.addEventListener('mouseup', handleMouseUp);
            });
        });
        
        controlBox.querySelector('.rotate').addEventListener('mousedown', (e) => {
            e.preventDefault();
            e.stopPropagation();
            currentAction = 'rotate';
            const rect = selectedElement.getBoundingClientRect();
            const centerX = rect.left + rect.width / 2;
            const centerY = rect.top + rect.height / 2;
            const startVector = Math.atan2(e.clientY - centerY, e.clientX - centerX);
            const currentTransform = selectedElement.style.transform;
            const rotateMatch = /rotate\(([^deg)]+)deg\)/.exec(currentTransform);
            const currentRotation = rotateMatch ? parseFloat(rotateMatch[1]) : 0;
            startAngle = startVector - (currentRotation * Math.PI / 180);
            document.addEventListener('mousemove', handleMouseMove);
            document.addEventListener('mouseup', handleMouseUp);
        });
        
        controlBox.querySelector('.mirror').addEventListener('mousedown', (e) => {
            e.preventDefault();
            e.stopPropagation();
            if(!selectedElement) return;

            const currentTransform = selectedElement.style.transform;
            const scaleMatch = /scaleX\(([^)]+)\)/.exec(currentTransform);
            const currentScale = scaleMatch ? parseFloat(scaleMatch[1]) : 1;
            const newScale = -currentScale;
            
            const rotateMatch = /rotate\(([^deg)]+)deg\)/.exec(currentTransform);
            const currentRotation = rotateMatch ? rotateMatch[0] : 'rotate(0deg)';

            selectedElement.style.transform = `${currentRotation} scaleX(${newScale})`;
            updateControlBox();
            saveState();
        });

        deleteBtn.addEventListener('click', () => {
            if (selectedElement) {
                selectedElement.remove();
                deselectElement();
                saveState();
            }
        });

        function handleMouseMove(e) {
            if (!currentAction || !selectedElement) return;
            const dx = e.clientX - startX;
            const dy = e.clientY - startY;

            if (currentAction === 'drag') {
                selectedElement.style.left = `${startLeft + dx}px`;
                selectedElement.style.top = `${startTop + dy}px`;
            } else if (currentAction === 'resize') {
                selectedElement.style.width = `${startWidth + dx}px`;
                selectedElement.style.height = 'auto'; // Maintain aspect ratio
            } else if (currentAction === 'rotate') {
                 const rect = selectedElement.getBoundingClientRect();
                 const centerX = rect.left + rect.width / 2;
                 const centerY = rect.top + rect.height / 2;
                 const newVector = Math.atan2(e.clientY - centerY, e.clientX - centerX);
                 const newAngle = (newVector - startAngle) * (180 / Math.PI);

                 const currentTransform = selectedElement.style.transform;
                 const scaleMatch = /scaleX\(([^)]+)\)/.exec(currentTransform);
                 const currentScale = scaleMatch ? scaleMatch[0] : 'scaleX(1)';
                 selectedElement.style.transform = `rotate(${newAngle}deg) ${currentScale}`;
            }
            updateControlBox();
        }

        function handleMouseUp() {
            if (currentAction) {
                saveState();
            }
            currentAction = null;
            document.removeEventListener('mousemove', handleMouseMove);
            document.removeEventListener('mouseup', handleMouseUp);
        }

        document.addEventListener('click', (e) => {
            if (!e.target.closest('.motif-image') && !e.target.closest('#control-box')) {
                deselectElement();
            }
        });

        // choose cloth template
        function setActiveGarment(garment) {
            if (garment === 'shirt') {
                shirtBtn.classList.replace('inactive-garment-btn', 'active-garment-btn');
                dressBtn.classList.replace('active-garment-btn', 'inactive-garment-btn');
                shirtContainer.style.display = 'block';
                dressContainer.style.display = 'none';
                activeMotifCanvas = document.getElementById('motif-canvas-shirt');
            } else {
                dressBtn.classList.replace('inactive-garment-btn', 'active-garment-btn');
                shirtBtn.classList.replace('active-garment-btn', 'inactive-garment-btn');
                dressContainer.style.display = 'block';
                shirtContainer.style.display = 'none';
                activeMotifCanvas = document.getElementById('motif-canvas-dress');
            }
            resetDesign(false);
            saveState();
        }

        shirtBtn.addEventListener('click', () => setActiveGarment('shirt'));
        dressBtn.addEventListener('click', () => setActiveGarment('dress'));

        // color picker
        clothColorInput.addEventListener('input', (e) => {
            if (activeMotifCanvas) {
                activeMotifCanvas.style.backgroundColor = e.target.value;
            }
        });
        clothColorInput.addEventListener('change', saveState);

        // reset
        function resetDesign(shouldSaveState = true) {
            if (activeMotifCanvas) {
                 activeMotifCanvas.style.backgroundColor = '#ffffff';
                 activeMotifCanvas.innerHTML = '';
            }
            clothColorInput.value = '#ffffff';
            deselectElement();
            if (shouldSaveState) {
                saveState();
            }
        }
        
        resetBtn.addEventListener('click', () => {
            resetModal.classList.remove('hidden');
        });
        
        confirmResetBtn.addEventListener('click', () => {
            resetDesign();
            resetModal.classList.add('hidden');
        });

        cancelResetBtn.addEventListener('click', () => {
            resetModal.classList.add('hidden');
        });


        // motif upload
        uploadBtn.addEventListener('click', () => customMotifInput.click());
        customMotifInput.addEventListener('change', (event) => {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const imageUrl = e.target.result;
                    const thumb = document.createElement('img');
                    thumb.src = imageUrl;
                    thumb.className = 'motif-item rounded-lg cursor-pointer border-2 border-transparent';
                    customMotifThumbnailContainer.appendChild(thumb);
                    thumb.addEventListener('click', () => addMotif(imageUrl));
                };
                reader.readAsDataURL(file);
            }
        });
        
        // downlaod design
        downloadBtn.addEventListener('click', () => {
            if(typeof html2canvas === 'undefined') {
                console.error('Download feature is not available. Could not load required library.');
                return;
            }
            deselectElement(); 
            
            setTimeout(() => {
                const activeContainer = shirtContainer.style.display === 'block' ? shirtContainer : dressContainer;
                const activeOutline = shirtContainer.style.display === 'block' ? document.getElementById('garment-outline-shirt') : document.getElementById('garment-outline-dress');
                
                const maskUrl = getComputedStyle(activeContainer).getPropertyValue('-webkit-mask-image').replace(/url\((['"])?(.*?)\1\)/gi, '$2').split(', ')[0];
                const outlineUrl = getComputedStyle(activeOutline).getPropertyValue('background-image').replace(/url\((['"])?(.*?)\1\)/gi, '$2').split(', ')[0];

                const loadImage = (src) => new Promise((resolve, reject) => {
                    const img = new Image();
                    img.crossOrigin = "anonymous";
                    img.onload = () => resolve(img);
                    img.onerror = (err) => reject(new Error(`Failed to load image: ${src}`));
                    img.src = src;
                });

                Promise.all([
                    html2canvas(activeContainer, { 
                        backgroundColor: null,
                        allowTaint: true,
                        useCORS: true 
                    }),
                    loadImage(maskUrl),
                    loadImage(outlineUrl)
                ]).then(([designCanvas, maskImage, outlineImage]) => {
                    const finalCanvas = document.createElement('canvas');
                    const ctx = finalCanvas.getContext('2d');
                    const canvasWidth = designCanvas.width;
                    const canvasHeight = designCanvas.height;
                    finalCanvas.width = canvasWidth;
                    finalCanvas.height = canvasHeight;

                    const maskNaturalWidth = maskImage.naturalWidth;
                    const maskNaturalHeight = maskImage.naturalHeight;
                    const canvasRatio = canvasWidth / canvasHeight;
                    const maskRatio = maskNaturalWidth / maskNaturalHeight;
                    let destWidth, destHeight, destX, destY;

                    if (maskRatio > canvasRatio) {
                        destWidth = canvasWidth;
                        destHeight = canvasWidth / maskRatio;
                        destX = 0;
                        destY = (canvasHeight - destHeight) / 2;
                    } else {
                        destHeight = canvasHeight;
                        destWidth = canvasHeight * maskRatio;
                        destY = 0;
                        destX = (canvasWidth - destWidth) / 2;
                    }

                    ctx.drawImage(designCanvas, 0, 0);
                    ctx.globalCompositeOperation = 'destination-in';
                    ctx.drawImage(maskImage, destX, destY, destWidth, destHeight);
                    ctx.globalCompositeOperation = 'source-over';
                    ctx.drawImage(outlineImage, destX, destY, destWidth, destHeight);

                    const link = document.createElement('a');
                    link.download = 'myBatik-design.png';
                    link.href = finalCanvas.toDataURL('image/png');
                    link.click();

                }).catch(error => {
                    console.error("Failed to download design:", error);
                    alert("Could not download design. Please try again.");
                });
            }, 100);
        });

        // color control
        function rgbToHex(rgb) {
            if (!rgb || !rgb.match(/^rgb/)) {
                return rgb;
            }
            let sep = rgb.indexOf(",") > -1 ? "," : " ";
            rgb = rgb.substr(4).split(")")[0].split(sep);

            let r = (+rgb[0]).toString(16),
                g = (+rgb[1]).toString(16),
                b = (+rgb[2]).toString(16);

            if (r.length == 1) r = "0" + r;
            if (g.length == 1) g = "0" + g;
            if (b.length == 1) b = "0" + b;

            return "#" + r + g + b;
        }

        setActiveGarment('shirt');
    });
    </script>
</body>
</html>