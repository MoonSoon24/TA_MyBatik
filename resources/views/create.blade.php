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
        body { font-family: 'Inter', sans-serif; }
        .active-garment-btn { background-color: #333; color: white; }
        .inactive-garment-btn { background-color: #e5e7eb; color: #374151; }
        #customMotifInput { display: none; }
        #color-picker-container {
            position: relative;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            background: conic-gradient(from 180deg at 50% 50%, #ff0000, #ffc800, #00ff00, #00ffff, #0000ff, #ff00ff, #ff0000);
        }
        #clothColor { position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer; }
        .motif-item:hover { transform: scale(1.05); }
        .canvas-wrapper {
            position: relative;
            width: 500px;
            height: 500px;
            margin: auto;
            overflow: hidden;
            flex-shrink: 0;
            border: 3px solid transparent;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: border-color 0.2s ease-in-out;
        }
        .canvas-wrapper.active-canvas { border-color: #0ea5e9; }
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
        .canvas-layer { position: absolute; top: 0; left: 0; width: 100%; height: 100%; }
        .motif-canvas { background-color: #ffffff; }
        .garment-outline { background-size: contain; background-position: center; background-repeat: no-repeat; pointer-events: none; }
        .motif-image { position: absolute; cursor: grab; user-select: none; -webkit-user-drag: none; }
        #control-box { position: absolute; border: 2px dashed #0ea5e9; pointer-events: none; display: none; }
        .handle { position: absolute; width: 12px; height: 12px; background-color: #0ea5e9; border: 2px solid white; border-radius: 50%; pointer-events: auto; }
        .handle.resize { cursor: nwse-resize; }
        .handle.br { bottom: -8px; right: -8px; }
        .handle.bl { bottom: -8px; left: -8px; cursor: nesw-resize; }
        .handle.tr { top: -8px; right: -8px; cursor: nesw-resize; }
        .handle.tl { top: -8px; left: -8px; }
        .handle.rotate { top: -25px; left: 50%; transform: translateX(-50%); cursor: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="%230ea5e9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 4v6h-6"/><path d="M1 20v-6h6"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/></svg>') 12 12, auto; width: 16px; height: 16px; }
        #delete-btn { position: absolute; top: -12px; right: -12px; width: 24px; height: 24px; background-color: red; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; pointer-events: auto; font-weight: bold; font-size: 14px; }
    </style>
</head>
<body class="bg-gray-100 font-sans text-gray-800">

    <x-header />
    <main class="w-full max-w-screen-2xl mx-auto bg-white rounded-2xl shadow-lg p-4 sm:p-8 flex flex-col lg:flex-row gap-8 mt-8">

        <div class="flex-grow bg-gray-50 rounded-xl p-6 flex flex-col">
            <div class="flex items-center gap-4 mb-6">
                <div class="flex items-center bg-gray-200 rounded-full p-1 w-max">
                    <button id="shirtBtn" class="px-6 py-2 rounded-full text-lg font-semibold active-garment-btn">Shirt</button>
                    <button id="dressBtn" class="px-6 py-2 rounded-full text-lg font-semibold inactive-garment-btn">Dress</button>
                </div>
                <div class="flex items-center bg-gray-200 rounded-full p-1 w-max">
                    <button id="longSleeveBtn" class="px-6 py-2 rounded-full text-lg font-semibold active-garment-btn">Long</button>
                    <button id="shortSleeveBtn" class="px-6 py-2 rounded-full text-lg font-semibold inactive-garment-btn">Short</button>
                </div>
            </div>

             <div class="flex flex-col md:flex-row gap-8 justify-center items-start">
                
                <div class="flex flex-col items-center">
                    <h3 class="text-center font-bold text-xl mb-2">Front</h3>
                    <div id="canvas-container-front" class="canvas-wrapper">
                        <div id="shirt-container-front" class="masked-container">
                            <div id="motif-canvas-shirt-front" class="canvas-layer motif-canvas"></div>
                            <div id="garment-outline-shirt-front" class="canvas-layer garment-outline"></div>
                        </div>
                        <div id="dress-container-front" class="masked-container" style="display:none;">
                            <div id="motif-canvas-dress-front" class="canvas-layer motif-canvas"></div>
                            <div id="garment-outline-dress-front" class="canvas-layer garment-outline"></div>
                        </div>
                        <div id="control-box">
                            <div class="handle resize tl"></div>
                            <div class="handle resize tr"></div>
                            <div class="handle resize bl"></div>
                            <div class="handle resize br"></div>
                            <div class="handle rotate"></div>
                            <div id="delete-btn">×</div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col items-center">
                    <h3 class="text-center font-bold text-xl mb-2">Back</h3>
                    <div id="canvas-container-back" class="canvas-wrapper">
                        <div id="shirt-container-back" class="masked-container">
                            <div id="motif-canvas-shirt-back" class="canvas-layer motif-canvas"></div>
                            <div id="garment-outline-shirt-back" class="canvas-layer garment-outline"></div>
                        </div>
                        <div id="dress-container-back" class="masked-container" style="display:none;">
                            <div id="motif-canvas-dress-back" class="canvas-layer motif-canvas"></div>
                            <div id="garment-outline-dress-back" class="canvas-layer garment-outline"></div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="flex flex-col sm:flex-row items-center justify-between mt-6 gap-4">
                <div class="flex items-center gap-4">
                    <span class="text-lg font-medium">Cloth Color</span>
                    <div id="color-picker-container">
                        <input type="color" id="clothColor" value="#ffffff">
                    </div>
                </div>
                <div class="flex items-center gap-4 flex-wrap justify-center">
                    <button id="undoBtn" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-3 px-6 rounded-lg flex items-center gap-2 disabled:opacity-50" disabled>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9h13.5a3.5 3.5 0 0 1 0 7H11"/><path d="m7 13-4-4 4-4"/></svg> Undo
                    </button>
                    <button id="redoBtn" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-3 px-6 rounded-lg flex items-center gap-2 disabled:opacity-50" disabled>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 9H7.5a3.5 3.5 0 0 0 0 7H13"/><path d="m17 13 4-4-4-4"/></svg> Redo
                    </button>
                    <button id="resetBtn" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-3 px-6 rounded-lg flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.5 2v6h-6M2.5 22v-6h6M2 11.5a10 10 0 0 1 18.8-4.3M22 12.5a10 10 0 0 1-18.8 4.3"/></svg> Reset
                    </button>
                    <button id="downloadBtn" class="bg-cyan-500 hover:bg-cyan-600 text-white font-bold py-3 px-6 rounded-lg flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg> Download Design
                    </button>
                </div>
            </div>
        </div>

        <div class="lg:w-1/3 bg-gray-50 rounded-xl p-8 flex flex-col justify-between">
            <div>
                <h2 class="text-2xl font-bold mb-4">Batik Motifs</h2>
                <input type="file" id="customMotifInput" accept="image/*">
                <button id="uploadBtn" class="w-full bg-cyan-500 hover:bg-cyan-600 text-white font-bold py-3 px-6 rounded-lg flex items-center justify-center gap-2 mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg> Upload Custom Motifs
                </button>

                <h3 class="text-lg font-semibold mb-3">Default Motifs</h3>
                <div class="grid grid-cols-3 gap-4" id="default-motifs-container">
                    <img src="images/batik1.png" class="motif-item rounded-lg cursor-pointer border-2 border-transparent" alt="Batik Motif 1">
                    <img src="images/batik2.png" class="motif-item rounded-lg cursor-pointer border-2 border-transparent" alt="Batik Motif 2">
                    <img src="images/batik3.png" class="motif-item rounded-lg cursor-pointer border-2 border-transparent" alt="Batik Motif 3">
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

        const shirtBtn = document.getElementById('shirtBtn');
        const dressBtn = document.getElementById('dressBtn');
        const longSleeveBtn = document.getElementById('longSleeveBtn');
        const shortSleeveBtn = document.getElementById('shortSleeveBtn');
        const canvasContainerFront = document.getElementById('canvas-container-front');
        const canvasContainerBack = document.getElementById('canvas-container-back');
        const shirtContainerFront = document.getElementById('shirt-container-front');
        const dressContainerFront = document.getElementById('dress-container-front');
        const shirtContainerBack = document.getElementById('shirt-container-back');
        const dressContainerBack = document.getElementById('dress-container-back');
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

        let activeGarment = 'shirt';
        let activeSleeve = 'long';
        let activeCanvasContainer = null;
        let selectedElement = null;
        let currentAction = null;
        let startX, startY, startLeft, startTop, startWidth, startHeight, startAngle;
        let undoStack = [];
        let redoStack = [];
        let lastSavedState = '';

        const imagePaths = {
            shirt: {
                long: { front: { mask: 'images/custom_shirt_design.png', outline: 'images/shirt_outline.png' }, back: { mask: 'images/backshirt_long.png', outline: 'images/backshirt_long_outline.png' } },
                short: { front: { mask: 'images/short_sleeve_shirt.png', outline: 'images/short_sleeve_shirt_outline.png' }, back: { mask: 'images/backshirt_short.png', outline: 'images/backshirt_short_outline.png' } }
            },
            dress: {
                long: { front: { mask: 'images/custom_dress_design.png', outline: 'images/dress_outline.png' }, back: { mask: 'images/backdress_long.png', outline: 'images/backdress_long_outline.png' } },
                short: { front: { mask: 'images/short_sleeve_dress.png', outline: 'images/short_sleeve_dress_outline.png' }, back: { mask: 'images/backdress_short.png', outline: 'images/backdress_short_outline.png' } }
            }
        };

        function setActiveCanvas(container) {
            if (activeCanvasContainer) activeCanvasContainer.classList.remove('active-canvas');
            activeCanvasContainer = container;
            activeCanvasContainer.classList.add('active-canvas');
        }

        function saveState() {
            const frontCanvas = document.getElementById(`motif-canvas-${activeGarment}-front`);
            const backCanvas = document.getElementById(`motif-canvas-${activeGarment}-back`);
            if (!frontCanvas || !backCanvas) return;
            const currentState = { frontHTML: frontCanvas.innerHTML, backHTML: backCanvas.innerHTML, color: frontCanvas.style.backgroundColor };
            const currentStateString = JSON.stringify(currentState);
            if (currentStateString === lastSavedState) return;
            lastSavedState = currentStateString;
            undoStack.push(currentState);
            redoStack = [];
            updateUndoRedoButtons();
        }

        function restoreState(state) {
            if (!state) return;
            const frontCanvas = document.getElementById(`motif-canvas-${activeGarment}-front`);
            const backCanvas = document.getElementById(`motif-canvas-${activeGarment}-back`);
            frontCanvas.innerHTML = state.frontHTML;
            backCanvas.innerHTML = state.backHTML;
            frontCanvas.style.backgroundColor = state.color;
            backCanvas.style.backgroundColor = state.color;
            document.querySelectorAll('.motif-image').forEach(image => image.addEventListener('mousedown', onMotifMouseDown));
            clothColorInput.value = rgbToHex(state.color);
            deselectElement();
            lastSavedState = JSON.stringify(state);
        }

        function undo() {
            if (undoStack.length <= 1) return;
            redoStack.push(undoStack.pop());
            restoreState(undoStack[undoStack.length - 1]);
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

        function addMotif(imageUrl) {
            if (!activeCanvasContainer) return;
            
            const view = activeCanvasContainer.id.includes('front') ? 'front' : 'back';
            const motifCanvasId = `motif-canvas-${activeGarment}-${view}`;
            const activeMotifCanvas = document.getElementById(motifCanvasId);

            if (!activeMotifCanvas) {
                console.error("Fatal Error: Could not find active motif canvas with ID:", motifCanvasId);
                return;
            }
            
            const image = document.createElement('img');
            image.src = imageUrl;
            image.crossOrigin = "anonymous";
            image.className = 'motif-image';
            image.style.cssText = 'width: 150px; height: auto; left: 100px; top: 100px; transform: rotate(0deg);';
            activeMotifCanvas.appendChild(image);
            image.addEventListener('mousedown', onMotifMouseDown);
            saveState();
        }

        function onMotifMouseDown(e) {
            const element = e.target;
            selectElement(element);
            startDrag(e);
        }
        
        function selectElement(element) {
            if (!element) return;
            const elementCanvasContainer = element.closest('.canvas-wrapper');
            if (elementCanvasContainer !== activeCanvasContainer) {
                setActiveCanvas(elementCanvasContainer);
            }
            activeCanvasContainer.appendChild(controlBox);
            selectedElement = element;
            updateControlBox();
            controlBox.style.display = 'block';
        }

        function deselectElement() {
            if (selectedElement) {
                selectedElement = null;
                controlBox.style.display = 'none';
            }
        }

        function updateControlBox() {
            if (!selectedElement || !activeCanvasContainer) return;

            const view = activeCanvasContainer.id.includes('front') ? 'front' : 'back';
            const motifCanvasId = `motif-canvas-${activeGarment}-${view}`;
            const activeMotifCanvas = document.getElementById(motifCanvasId);

            if (!activeMotifCanvas) {
                 console.error("Fatal Error: Could not find active motif canvas for control box with ID:", motifCanvasId);
                return;
            }

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
                e.preventDefault(); e.stopPropagation();
                currentAction = 'resize';
                startX = e.clientX;
                startWidth = selectedElement.offsetWidth;
                document.addEventListener('mousemove', handleMouseMove);
                document.addEventListener('mouseup', handleMouseUp);
            });
        });

        controlBox.querySelector('.rotate').addEventListener('mousedown', (e) => {
            e.preventDefault(); e.stopPropagation();
            currentAction = 'rotate';
            const rect = selectedElement.getBoundingClientRect();
            const centerX = rect.left + rect.width / 2;
            const centerY = rect.top + rect.height / 2;
            const startVector = Math.atan2(e.clientY - centerY, e.clientX - centerX);
            const rotateMatch = /rotate\(([^deg)]+)deg\)/.exec(selectedElement.style.transform);
            const currentRotation = rotateMatch ? parseFloat(rotateMatch[1]) : 0;
            startAngle = startVector - (currentRotation * Math.PI / 180);
            document.addEventListener('mousemove', handleMouseMove);
            document.addEventListener('mouseup', handleMouseUp);
        });

        deleteBtn.addEventListener('click', (e) => {
            e.stopPropagation();
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
                selectedElement.style.height = 'auto';
            } else if (currentAction === 'rotate') {
                const rect = selectedElement.getBoundingClientRect();
                const centerX = rect.left + rect.width / 2;
                const centerY = rect.top + rect.height / 2;
                const newVector = Math.atan2(e.clientY - centerY, e.clientX - centerX);
                const newAngle = (newVector - startAngle) * (180 / Math.PI);
                selectedElement.style.transform = `rotate(${newAngle}deg)`;
            }
            updateControlBox();
        }

        function handleMouseUp() {
            if (currentAction) saveState();
            currentAction = null;
            document.removeEventListener('mousemove', handleMouseMove);
            document.removeEventListener('mouseup', handleMouseUp);
        }

        function updateGarmentView() {
            const garmentPaths = imagePaths[activeGarment][activeSleeve];
            shirtContainerFront.style.display = (activeGarment === 'shirt') ? 'block' : 'none';
            dressContainerFront.style.display = (activeGarment === 'dress') ? 'block' : 'none';
            shirtContainerBack.style.display = (activeGarment === 'shirt') ? 'block' : 'none';
            dressContainerBack.style.display = (activeGarment === 'dress') ? 'block' : 'none';
            const currentFrontContainer = document.getElementById(`${activeGarment}-container-front`);
            const currentBackContainer = document.getElementById(`${activeGarment}-container-back`);
            const currentFrontOutline = document.getElementById(`garment-outline-${activeGarment}-front`);
            const currentBackOutline = document.getElementById(`garment-outline-${activeGarment}-back`);
            currentFrontContainer.style.webkitMaskImage = `url('${garmentPaths.front.mask}')`;
            currentFrontContainer.style.maskImage = `url('${garmentPaths.front.mask}')`;
            currentFrontOutline.style.backgroundImage = `url('${garmentPaths.front.outline}')`;
            currentBackContainer.style.webkitMaskImage = `url('${garmentPaths.back.mask}')`;
            currentBackContainer.style.maskImage = `url('${garmentPaths.back.mask}')`;
            currentBackOutline.style.backgroundImage = `url('${garmentPaths.back.outline}')`;
            resetDesign(false);
        }

        function setGarment(type) {
            activeGarment = type;
            shirtBtn.classList.toggle('active-garment-btn', type === 'shirt');
            shirtBtn.classList.toggle('inactive-garment-btn', type !== 'shirt');
            dressBtn.classList.toggle('active-garment-btn', type === 'dress');
            dressBtn.classList.toggle('inactive-garment-btn', type !== 'dress');
            updateGarmentView();
        }

        function setSleeve(type) {
            activeSleeve = type;
            longSleeveBtn.classList.toggle('active-garment-btn', type === 'long');
            longSleeveBtn.classList.toggle('inactive-garment-btn', type !== 'long');
            shortSleeveBtn.classList.toggle('active-garment-btn', type === 'short');
            shortSleeveBtn.classList.toggle('inactive-garment-btn', type !== 'short');
            updateGarmentView();
        }

        function resetDesign(shouldSaveState = true) {
            document.querySelectorAll('.motif-canvas').forEach(canvas => {
                canvas.innerHTML = '';
                canvas.style.backgroundColor = '#ffffff';
            });
            clothColorInput.value = '#ffffff';
            deselectElement();
            if (shouldSaveState) {
                undoStack = [];
                redoStack = [];
                saveState();
            }
        }

        function rgbToHex(rgb) {
            if (!rgb || !rgb.match(/^rgb/)) return rgb;
            let sep = rgb.indexOf(",") > -1 ? "," : " ";
            rgb = rgb.substr(4).split(")")[0].split(sep);
            let r = (+rgb[0]).toString(16), g = (+rgb[1]).toString(16), b = (+rgb[2]).toString(16);
            if (r.length == 1) r = "0" + r;
            if (g.length == 1) g = "0" + g;
            if (b.length == 1) b = "0" + b;
            return "#" + r + g + b;
        }

        const loadImage = (src) => new Promise((resolve, reject) => {
            const img = new Image();
            img.crossOrigin = "anonymous";
            img.onload = () => resolve(img);
            img.onerror = (err) => reject(new Error(`Failed to load image: ${src}`));
            img.src = src;
        });

        canvasContainerFront.addEventListener('click', () => setActiveCanvas(canvasContainerFront));
        canvasContainerBack.addEventListener('click', () => setActiveCanvas(canvasContainerBack));
        undoBtn.addEventListener('click', undo);
        redoBtn.addEventListener('click', redo);
        shirtBtn.addEventListener('click', () => setGarment('shirt'));
        dressBtn.addEventListener('click', () => setGarment('dress'));
        longSleeveBtn.addEventListener('click', () => setSleeve('long'));
        shortSleeveBtn.addEventListener('click', () => setSleeve('short'));
        clothColorInput.addEventListener('input', (e) => {
            document.querySelectorAll('.motif-canvas').forEach(canvas => canvas.style.backgroundColor = e.target.value);
        });
        clothColorInput.addEventListener('change', saveState);
        resetBtn.addEventListener('click', () => resetModal.classList.remove('hidden'));
        confirmResetBtn.addEventListener('click', () => { resetDesign(true); resetModal.classList.add('hidden'); });
        cancelResetBtn.addEventListener('click', () => resetModal.classList.add('hidden'));
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
        defaultMotifsContainer.addEventListener('click', (e) => {
            if (e.target.classList.contains('motif-item')) addMotif(e.target.src);
        });
        document.addEventListener('click', (e) => {
            if (!e.target.closest('.canvas-wrapper') && !e.target.closest('#control-box')) deselectElement();
        });

        downloadBtn.addEventListener('click', () => {
            if(typeof html2canvas === 'undefined') { console.error('html2canvas not loaded'); return; }
            deselectElement(); 
            
            setTimeout(() => {
                const garmentFrontContainer = document.getElementById(`${activeGarment}-container-front`);
                const garmentBackContainer = document.getElementById(`${activeGarment}-container-back`);
                const paths = imagePaths[activeGarment][activeSleeve];

                const processView = (container, viewPaths) => {
                    return Promise.all([
                        html2canvas(container, { backgroundColor: null, allowTaint: true, useCORS: true }),
                        loadImage(viewPaths.mask),
                        loadImage(viewPaths.outline)
                    ]).then(([canvas, mask, outline]) => {
                        const offscreenCanvas = document.createElement('canvas');
                        const ctx = offscreenCanvas.getContext('2d');
                        offscreenCanvas.width = canvas.width;
                        offscreenCanvas.height = canvas.height;
                        
                        ctx.drawImage(canvas, 0, 0);
                        ctx.globalCompositeOperation = 'destination-in';
                        ctx.drawImage(mask, 0, 0, canvas.width, canvas.height);
                        ctx.globalCompositeOperation = 'source-over';
                        ctx.drawImage(outline, 0, 0, canvas.width, canvas.height);
                        return offscreenCanvas;
                    });
                };
                
                Promise.all([
                    processView(garmentFrontContainer, paths.front),
                    processView(garmentBackContainer, paths.back)
                ]).then(([frontCanvas, backCanvas]) => {
                    const finalCanvas = document.createElement('canvas');
                    const ctx = finalCanvas.getContext('2d');
                    finalCanvas.width = frontCanvas.width * 2;
                    finalCanvas.height = frontCanvas.height;
                    ctx.drawImage(frontCanvas, 0, 0);
                    ctx.drawImage(backCanvas, frontCanvas.width, 0);
                    const link = document.createElement('a');
                    link.download = `myBatik-${activeGarment}-${activeSleeve}.png`;
                    link.href = finalCanvas.toDataURL('image/png');
                    link.click();
                }).catch(error => console.error("Failed to download design:", error));
            }, 100);
        });

        // INITIAL SETUP
        updateGarmentView();
        setActiveCanvas(canvasContainerFront);
        saveState();

    });
    </script>
</body>
</html>