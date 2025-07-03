<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Batik - Measurements</title>
    
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
        .size-guide-table th, .size-guide-table td {
            text-align: center;
            padding: 0.75rem;
            border: 1px solid #e5e7eb;
            transition: background-color 0.3s ease;
        }
        .size-guide-table th {
            font-weight: 600;
        }
        .size-guide-table .size-col {
            font-weight: 600;
        }
        .size-guide-table tr.selected {
            background-color: #e0f2fe;
        }
        .focus\:ring-cyan-400:focus {
            --tw-ring-color: #22d3ee;
        }
        .text-cyan-600 {
            color: #0891b2;
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
                </nav>
            </div>
            
            <div class="flex items-center space-x-3">
                <div x-data="{ dropdownOpen: false }" class="relative">
                    <button @click="dropdownOpen = !dropdownOpen" class="flex items-center space-x-3">
                        <span class="font-semibold text-gray-700 hover:text-black transition">{{ Auth::user()->name }}</span>
                        <div class="w-8 h-8">
                            <!-- User Icon SVG -->
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

    <main class="w-full max-w-7xl mx-auto bg-white rounded-2xl shadow-lg p-6 sm:p-10 mt-8 mb-8">
        <div class="mb-8">
            <h2 class="text-3xl font-bold mb-2">Measurements</h2>
            <p class="text-gray-600">Enter your measurements to ensure your batik clothing fits perfectly. All measurements should be in centimeters (cm).</p>
        </div>

        <hr class="my-6">
        
        <h3 class="text-2xl font-bold mb-6">Standard Measurement</h3>

        <form action="{{ route('order.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12">
                <!-- left side -->
                <div class="flex flex-col justify-between">
                    <div>
                        <h4 class="text-xl font-bold mb-4">Choose Your Size</h4>
                        <div id="size-options" class="space-y-3">
                            <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50 transition">
                                <input type="radio" name="size" value="XS" class="h-5 w-5 text-cyan-600 focus:ring-cyan-500" checked>
                                <span class="ml-4 text-lg">XS (Extra Small)</span>
                            </label>
                            <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50 transition">
                                <input type="radio" name="size" value="S" class="h-5 w-5 text-cyan-600 focus:ring-cyan-500">
                                <span class="ml-4 text-lg">S (Small)</span>
                            </label>
                            <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50 transition">
                                <input type="radio" name="size" value="M" class="h-5 w-5 text-cyan-600 focus:ring-cyan-500">
                                <span class="ml-4 text-lg">M (Medium)</span>
                            </label>
                            <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50 transition">
                                <input type="radio" name="size" value="L" class="h-5 w-5 text-cyan-600 focus:ring-cyan-500">
                                <span class="ml-4 text-lg">L (Large)</span>
                            </label>
                            <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50 transition">
                                <input type="radio" name="size" value="XL" class="h-5 w-5 text-cyan-600 focus:ring-cyan-500">
                                <span class="ml-4 text-lg">XL (Extra Large)</span>
                            </label>
                        </div>
                    </div>
                    <div class="mt-8">
                        <a href="/create" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-3 px-8 rounded-lg text-lg transition">Back to designing</a>
                    </div>
                </div>

                <!-- right side -->
                <div>
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="text-xl font-bold">Size Guide</h4>
                        <button type="button" id="garment-toggle-btn" class="text-cyan-600 font-semibold flex items-center gap-2">
                            <span id="garment-type-text">Shirt</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"/></svg>
                        </button>
                    </div>
                    
                    <div id="shirt-size-table" class="overflow-x-auto">
                        <div class="mb-6">
                            <img 
                                id="shirt-guide-image" 
                                src="images/shirt_guide.png" 
                                alt="Shirt Measurement Guide" 
                                class="w-full max-w-sm mx-auto rounded-lg shadow-sm h-96 object-contain"
                            >
                        </div>
                        <table class="w-full border-collapse size-guide-table">
                            <thead>
                                <tr>
                                    <th class="bg-gray-50">Size</th>
                                    <th class="bg-red-100 text-red-800">Body Length</th>
                                    <th class="bg-orange-100 text-orange-800">Sleeve Length</th>
                                    <th class="bg-blue-100 text-blue-800">Shoulder Width</th>
                                    <th class="bg-green-100 text-green-800">Body Width</th>
                                    <th class="bg-black text-white">Neck Size</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr data-size="XS"><td class="size-col">XS</td><td>69</td><td>78.5</td><td>43.5</td><td>51</td><td>39</td></tr>
                                <tr data-size="S"><td class="size-col">S</td><td>71</td><td>80.5</td><td>45</td><td>54</td><td>39</td></tr>
                                <tr data-size="M"><td class="size-col">M</td><td>73</td><td>83</td><td>46.5</td><td>57</td><td>41</td></tr>
                                <tr data-size="L"><td class="size-col">L</td><td>75</td><td>85.5</td><td>48</td><td>60</td><td>43</td></tr>
                                <tr data-size="XL"><td class="size-col">XL</td><td>78</td><td>88</td><td>50</td><td>64</td><td>45</td></tr>
                            </tbody>
                        </table>
                    </div>

                    <div id="dress-size-table" class="overflow-x-auto" style="display: none;">
                        <div class="mb-6">
                            <img 
                                id="dress-guide-image" 
                                src="images/dress_guide.png" 
                                alt="Dress Measurement Guide" 
                                class="w-full max-w-sm mx-auto rounded-lg shadow-sm h-96 object-contain"
                            >
                        </div>
                        <table class="w-full border-collapse size-guide-table">
                            <thead>
                                <tr>
                                    <th class="bg-gray-50">Size</th>
                                    <th class="bg-red-100 text-red-800">Body Length</th>
                                    <th class="bg-orange-100 text-orange-800">Sleeve Length</th>
                                    <th class="bg-blue-100 text-blue-800">Shoulder Width</th>
                                    <th class="bg-green-100 text-green-800">Body Width</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr data-size="XS"><td class="size-col">XS</td><td>110</td><td>60</td><td>38</td><td>48</td></tr>
                                <tr data-size="S"><td class="size-col">S</td><td>112</td><td>61</td><td>39</td><td>51</td></tr>
                                <tr data-size="M"><td class="size-col">M</td><td>114</td><td>62</td><td>40</td><td>54</td></tr>
                                <tr data-size="L"><td class="size-col">L</td><td>116</td><td>63</td><td>41</td><td>57</td></tr>
                                <tr data-size="XL"><td class="size-col">XL</td><td>118</td><td>64</td><td>42</td><td>60</td></tr>
                            </tbody>
                        </table>
                    </div>

                    <div id="preview-container" class="mt-6 text-center" style="display: none;">
                        <h4 class="text-lg font-bold mb-4 text-gray-700">Your Design Preview</h4>
                        <img id="image-preview" src="#" alt="Your uploaded design" class="max-w-xs w-full mx-auto rounded-lg shadow-md border">
                    </div>

                    <div class="mt-8">
                        <label for="design_file" class="block text-lg font-medium text-gray-700 mb-2">Upload Your Design (PNG)</label>
                        <input type="file" name="design_file" id="design_file" accept=".png, image/png" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
                    </div>

                    <div class="mt-8 text-right">
                        <button type="submit" class="bg-cyan-500 hover:bg-cyan-600 text-white font-bold py-3 px-8 rounded-lg text-lg transition shadow-md hover:shadow-lg">Continue Checkout</button>
                    </div>
                </div>
            </div>
        </form>
    </main>

    <x-logout-modal />

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const sizeOptions = document.getElementById('size-options');
        const shirtTable = document.getElementById('shirt-size-table');
        const dressTable = document.getElementById('dress-size-table');
        const garmentToggleBtn = document.getElementById('garment-toggle-btn');
        const garmentTypeText = document.getElementById('garment-type-text');
        const guideImage = document.getElementById('guide-image');
        const designFileInput = document.getElementById('design_file');
        const imagePreview = document.getElementById('image-preview');
        const previewContainer = document.getElementById('preview-container');
        const shirtGuideImage = document.getElementById('shirt-guide-image');
        const dressGuideImage = document.getElementById('dress-guide-image');

        function highlightRow(selectedValue) {
            const activeTable = shirtTable.style.display !== 'none' ? shirtTable : dressTable;
            activeTable.querySelectorAll('tbody tr').forEach(row => {
                row.classList.toggle('selected', row.dataset.size === selectedValue);
            });
        }

        sizeOptions.addEventListener('change', (e) => {
            if (e.target.name === 'size') {
                highlightRow(e.target.value);
            }
        });
        
        garmentToggleBtn.addEventListener('click', () => {
            const isShirtVisible = shirtTable.style.display !== 'none';
            
            if (isShirtVisible) {
                shirtTable.style.display = 'none';
                dressTable.style.display = 'block';
                garmentTypeText.textContent = 'Dress';
                guideImage.src = dressGuideImage;
                guideImage.alt = 'Dress Measurement Guide';
            } else {
                shirtTable.style.display = 'block';
                dressTable.style.display = 'none';
                garmentTypeText.textContent = 'Shirt';
                guideImage.src = shirtGuideImage;
                guideImage.alt = 'Shirt Measurement Guide';
            }
            
            const xsRadio = document.querySelector('input[name="size"][value="XS"]');
            if(xsRadio) {
                xsRadio.checked = true;
            }
            highlightRow('XS');
        });

        designFileInput.addEventListener('change', (event) => {
            const file = event.target.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                
                reader.onload = (e) => {
                    imagePreview.src = e.target.result;
                    previewContainer.style.display = 'block';
                };
                
                reader.readAsDataURL(file);
            } else {
                previewContainer.style.display = 'none';
                imagePreview.src = '#';
            }
        });

        const initialSelected = document.querySelector('input[name="size"]:checked');
        if (initialSelected) {
            highlightRow(initialSelected.value);
        }
    });
    </script>
</body>
</html>
