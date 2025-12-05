<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IA GROUPS - Logística Internacional</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        @keyframes slideZoom {
            0% {
                transform: scale(1);
            }

            100% {
                transform: scale(1.08);
            }
        }

        .carousel-image {
            transform: scale(1);
        }

        .carousel-slide {
            opacity: 0;
            transition: opacity 0.5s ease-out;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .carousel-slide.active {
            opacity: 1;
            z-index: 1;
        }

        .carousel-slide.active .carousel-image {
            animation: slideZoom 3s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        }

        /* Mobile menu transitions */
        #mobileMenu {
            transition: opacity 0.3s ease;
            opacity: 0;
        }

        #mobileMenu.show {
            opacity: 1;
        }

        /* Theme transitions */
        body {
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        * {
            transition: background-color 0.3s ease, border-color 0.3s ease, color 0.3s ease;
        }

        /* Responsive utilities */
        @media (max-width: 640px) {
            .carousel-slide {
                min-height: 500px;
            }
        }
    </style>
    <style>
        .boat-container {
            width: 100%;
            height: 80px;
            position: relative;
            overflow: hidden;
        }

        .boat-svg {
            position: absolute;
            width: 110px;
            height: 110px;
            bottom: 20px;
            animation: navigate 8s infinite ease-in-out;
        }

        .wave-motion {
            animation: wave 1.5s infinite alternate ease-in-out;
        }

        @keyframes navigate {
            0% {
                left: -60px;
            }

            50% {
                left: calc(100% - 60px);
            }

            100% {
                left: -60px;
            }
        }

        @keyframes wave {
            0% {
                transform: translateY(0px);
            }

            100% {
                transform: translateY(-5px);
            }
        }
    </style>
</head>

<body class="bg-black min-h-screen text-white overflow-x-hidden" data-theme="dark">
    <!-- Header -->
    <header class="bg-white/5 backdrop-blur-xl border-b border-yellow-500/20 sticky top-0 z-50 shadow-2xl">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 lg:py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">


                    <!-- Espacio para logo de la empresa -->
                    <div
                        class="w-12 h-12 sm:w-14 sm:h-14 bg-yellow-500/10 border-2 border-yellow-500/30 rounded-lg flex items-center justify-center">
                        <img src="images/logo.png" alt="IA Groups Logo" class="w-full h-full object-contain">
                        <svg class="w-7 h-7 text-yellow-500" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5z" />
                        </svg>
                    </div>
                    <h1 class="text-xl sm:text-2xl font-bold tracking-widest text-yellow-500">IA GROUPS</h1>
                </div>
                <nav class="hidden lg:flex space-x-8">
                    <a href="#inicio"
                        class="text-yellow-400 hover:text-yellow-300 font-medium transition-colors">Inicio</a>
                    <a href="{{ route('nosotros') }}"
                        class="text-yellow-400 hover:text-yellow-300 font-medium transition-colors">Nosotros</a>
                    <a href="#servicios"
                        class="text-yellow-400 hover:text-yellow-300 font-medium transition-colors">Servicios</a>
                </nav>

                <div class="flex items-center gap-3 sm:gap-4">
                    <!-- Mobile Menu Button -->
                    <button id="mobileMenuBtn"
                        class="lg:hidden text-yellow-500 hover:text-yellow-400 transition-colors">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Mobile Menu Overlay -->
    <div id="mobileMenu" class="fixed inset-0 bg-black/95 backdrop-blur-lg z-50 lg:hidden hidden">
        <div class="flex flex-col h-full">
            <div class="flex items-center justify-between p-4 border-b border-yellow-500/20">
                <h2 class="text-xl font-bold text-yellow-500">MENÚ</h2>
                <button id="closeMobileMenu" class="text-yellow-500 hover:text-yellow-400">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <nav class="flex flex-col p-6 space-y-6">
                <a href="#inicio"
                    class="text-2xl text-yellow-400 hover:text-yellow-300 font-bold transition-colors mobile-menu-link">Inicio</a>
                <a href="{{ route('nosotros') }}"
                    class="text-2xl text-yellow-400 hover:text-yellow-300 font-bold transition-colors mobile-menu-link">Nosotros</a>
                <a href="#servicios"
                    class="text-2xl text-yellow-400 hover:text-yellow-300 font-bold transition-colors mobile-menu-link">Servicios</a>
            </nav>
        </div>
    </div>

    <!-- Hero Section with Carousel -->
    <section id="inicio" class="relative h-screen overflow-hidden">
        <!-- Carousel Container -->
        <div id="carousel" class="relative w-full h-full">
            <!-- Slide 1 -->
            <div class="carousel-slide active">
                <div class="absolute inset-0 bg-gradient-to-r from-black via-black/70 to-transparent z-10"></div>
                <img src="/images/camion.jpg" alt="Logística Global" class="carousel-image w-full h-full object-cover"
                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                <div class="hidden w-full h-full bg-gradient-to-br from-gray-900 to-black items-center justify-center">
                    <span class="text-yellow-500 text-xl">Slide 1</span>
                </div>
            </div>

            <!-- Slide 2 -->
            <div class="carousel-slide">
                <div class="absolute inset-0 bg-gradient-to-r from-black via-black/70 to-transparent z-10"></div>
                <img src="/images/barco2.jpg" alt="Transporte Marítimo"
                    class="carousel-image w-full h-full object-cover"
                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                <div class="hidden w-full h-full bg-gradient-to-br from-gray-900 to-black items-center justify-center">
                    <span class="text-yellow-500 text-xl">Slide 2</span>
                </div>
            </div>

            <!-- Slide 3 -->
            <div class="carousel-slide">
                <div class="absolute inset-0 bg-gradient-to-r from-black via-black/70 to-transparent z-10"></div>
                <img src="/images/aviondef.jpg" alt="Logística Terrestre"
                    class="carousel-image w-full h-full object-cover"
                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                <div class="hidden w-full h-full bg-gradient-to-br from-gray-900 to-black items-center justify-center">
                    <span class="text-yellow-500 text-xl">Slide 3</span>
                </div>
            </div>
        </div>

        <!-- Content Overlay -->
        <div class="absolute inset-0 z-20 flex items-center">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                <div class="max-w-3xl px-4 sm:px-6">
                    <h2
                        class="text-3xl xs:text-4xl sm:text-5xl md:text-6xl lg:text-7xl xl:text-8xl font-black mb-4 sm:mb-6 lg:mb-8 leading-none tracking-tight">
                        <span class="text-yellow-500">LOGÍSTICA</span><br>
                        <span class="text-white">SIN LÍMITES</span>
                    </h2>
                    <p
                        class="text-sm sm:text-base md:text-lg lg:text-xl text-gray-300 mb-6 sm:mb-8 lg:mb-10 max-w-2xl leading-relaxed">
                        Conectamos el mundo con soluciones de transporte inteligentes.
                        Tu carga, nuestro compromiso.
                    </p>
                    <a href="#servicios"
                        class="inline-block bg-yellow-500 hover:bg-yellow-400 text-black px-8 sm:px-12 py-3 sm:py-4 text-base sm:text-lg font-black transition-all transform hover:scale-105 uppercase tracking-wider rounded-lg">
                        EXPLORAR SERVICIOS
                    </a>
                </div>
            </div>
        </div>

        <!-- Navigation Arrows -->
        <button id="prevBtn"
            class="absolute left-4 sm:left-8 top-1/2 -translate-y-1/2 z-30 w-12 h-12 sm:w-14 sm:h-14 bg-yellow-500/20 hover:bg-yellow-500 border-2 border-yellow-500 transition-all flex items-center justify-center group rounded-lg">
            <svg class="w-6 h-6 sm:w-7 sm:h-7 text-yellow-500 group-hover:text-black transition-colors" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7" />
            </svg>
        </button>
        <button id="nextBtn"
            class="absolute right-4 sm:right-8 top-1/2 -translate-y-1/2 z-30 w-12 h-12 sm:w-14 sm:h-14 bg-yellow-500/20 hover:bg-yellow-500 border-2 border-yellow-500 transition-all flex items-center justify-center group rounded-lg">
            <svg class="w-6 h-6 sm:w-7 sm:h-7 text-yellow-500 group-hover:text-black transition-colors" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7" />
            </svg>
        </button>
    </section>

    <!-- Cotizador de Importación -->
    <section
        class="relative bg-gradient-to-b from-gray-900 via-black to-gray-900 py-12 sm:py-16 lg:py-20 overflow-hidden border-y border-yellow-500/20">
        <div class="absolute inset-0 opacity-5">
            <div
                class="absolute top-1/4 right-1/4 w-96 h-96 bg-gradient-to-br from-yellow-500 to-amber-600 rounded-full blur-3xl">
            </div>
            <div
                class="absolute bottom-1/4 left-1/4 w-96 h-96 bg-gradient-to-tl from-orange-500 to-yellow-600 rounded-full blur-3xl">
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <!-- Header -->
            <div class="text-center mb-8 sm:mb-12">
                <h2 class="text-3xl sm:text-4xl md:text-5xl font-black text-white mb-3 sm:mb-4">
                    COTIZADOR <span
                        class="bg-gradient-to-r from-yellow-500 via-amber-500 to-yellow-600 bg-clip-text text-transparent">INTELIGENTE</span>
                </h2>
                <p class="text-sm sm:text-base text-gray-400 max-w-2xl mx-auto">
                    Obtén precios cotizados al instante para tus importaciones desde cualquier parte del mundo
                </p>
            </div>

            <!-- Search Bar -->
            <form id="search-form" class="mt-4">
                <div class="max-w-4xl mx-auto mb-8">
                    <div
                        class="bg-gradient-to-br from-gray-800/80 via-black/60 to-gray-800/80 backdrop-blur-xl border border-yellow-500/30 p-4 sm:p-6 rounded-2xl shadow-2xl">
                        <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">

                            <div class="flex-1">
                                <input id="productUrl" type="text"
                                    placeholder="https://www.alibaba.com/product-detail/Teddy-Bear-I-Love-You-Valentines.html"
                                    class="w-full bg-black/50 border border-yellow-500/30 text-white px-4 py-3 sm:py-4 rounded-lg focus:outline-none focus:border-yellow-500 transition-all text-sm sm:text-base placeholder-gray-500">
                            </div>
                            {{-- <button id="searchBtn"
                                class="bg-gradient-to-r from-yellow-500 via-amber-500 to-yellow-500 hover:from-yellow-400 hover:via-amber-400 hover:to-yellow-400 text-black px-6 sm:px-10 py-3 sm:py-4 font-black rounded-lg transition-all transform hover:scale-105 shadow-lg shadow-yellow-500/30 text-sm sm:text-base uppercase tracking-wide whitespace-nowrap">
                                <span class="hidden sm:inline">Buscar Producto</span>
                                <span class="sm:hidden">Buscar</span>
                            </button> --}}
                            <button type="submit" id="search-button-normal"
                                class="bg-gradient-to-r from-yellow-500 via-amber-500 to-yellow-500 hover:from-yellow-400 hover:via-amber-400 hover:to-yellow-400 text-black px-6 sm:px-10 py-3 sm:py-4 font-black rounded-lg transition-all transform hover:scale-105 shadow-lg shadow-yellow-500/30 text-sm sm:text-base uppercase tracking-wide whitespace-nowrap">
                                Buscar Producto
                            </button>
                            <button type="button" id="search-button-loading"
                                class="btn btn-light button-custombg-gradient-to-r from-yellow-500 via-amber-500 to-yellow-500 hover:from-yellow-400 hover:via-amber-400 hover:to-yellow-400 text-black px-6 sm:px-10 py-3 sm:py-4 font-black rounded-lg transition-all transform hover:scale-105 shadow-lg shadow-yellow-500/30 text-sm sm:text-base uppercase tracking-wide whitespace-nowrap"
                                disabled style="display: none;">
                                <span class="spinner-border spinner-border-sm me-2"></span>
                                Buscando...
                            </button>

                        </div>
                    </div>
                </div>
            </form>
            <!-- Results Dashboard (Hidden by default) -->
            <div id="quoteDashboard" class="hidden max-w-7xl mx-auto">
                <div
                    class="bg-gradient-to-br from-gray-800/60 via-black/40 to-gray-800/60 backdrop-blur-xl border border-yellow-500/40 rounded-2xl overflow-hidden shadow-2xl shadow-yellow-500/10">

                    <!-- Dashboard Header -->
                    <div
                        class="bg-gradient-to-r from-yellow-500/10 via-amber-500/10 to-yellow-500/10 border-b border-yellow-500/30 p-4 sm:p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-xl sm:text-2xl font-black text-yellow-500">Dashboard de Cotización</h3>
                                <p class="text-sm text-gray-400 mt-1">Analiza y cotiza tu importación en tiempo real
                                </p>
                            </div>
                            <button id="closeDashboard" class="text-gray-400 hover:text-yellow-500 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Dashboard Content -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 p-4 sm:p-6 lg:p-8">

                        <!-- Left Column: Product Analysis -->
                        <div class="space-y-6">
                            <div
                                class="bg-gradient-to-br from-gray-900/80 via-black/60 to-gray-900/80 border border-yellow-500/30 rounded-xl p-6">
                                <h4 class="text-lg font-bold text-yellow-500 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                                        <path fill-rule="evenodd"
                                            d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Información del Producto
                                </h4>

                                <!-- TODO: Backend - Conectar con scraper de productos -->
                                <div class="space-y-4">

                                    <!-- Product Image -->
                                    <div
                                        class="relative overflow-hidden rounded-lg border-2 border-yellow-500/30 hover:border-yellow-500 transition-all group">
                                        <div
                                            class="w-full h-48 bg-gradient-to-br from-gray-800 to-gray-900 flex items-center justify-center">
                                            <img id="scraped-image" class="w-full h-full object-contain hidden">
                                            <div id="placeholder-image" class="text-center">
                                                <svg class="w-16 h-16 mx-auto text-yellow-500/30 mb-2" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                <p class="text-xs text-gray-500">Imagen del producto</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Product Details -->
                                    <div class="grid grid-cols-2 gap-3">

                                        <div class="bg-black/40 border border-yellow-500/20 rounded-lg p-3">
                                            <p class="text-xs text-gray-500 mb-1">Precio Unitario</p>
                                            <p id="scraped-price" class="text-lg font-bold text-cyan-400">--</p>
                                        </div>
                                        <div class="bg-black/40 border border-yellow-500/20 rounded-lg p-3">
                                            <label for="moqInput" class="text-xs text-gray-500 mb-1 block">Cantidad (MOQ)</label>
                                            <input 
                                                type="number" 
                                                id="scraped-moq"
                                                min="1" 
                                                value="1" 
                                                class="w-full bg-black/60 border border-purple-500/40 rounded px-2 py-1 text-lg font-bold text-purple-400 focus:border-purple-500 focus:outline-none focus:ring-1 focus:ring-purple-500"
                                                placeholder="1"
                                            >
                                        </div>

                                        <div class="bg-black/40 border border-yellow-500/20 rounded-lg p-3">
                                            <p class="text-xs text-gray-500 mb-1">CBM(m³)</p>
                                            <p id="scraped-package-size" class="text-sm font-bold text-yellow-400">--
                                            </p>
                                        </div>

                                        <div class="bg-black/40 border border-yellow-500/20 rounded-lg p-3">
                                            <p class="text-xs text-gray-500 mb-1">CBM(Kg)</p>
                                            <p id="scraped-package-weight" class="text-sm font-bold text-green-400">--
                                            </p>
                                        </div>

                                    </div>
                                </div>

                            </div>

                            <!-- Price Summary Card -->
                            <div
                                class="bg-gradient-to-br from-gray-900/80 via-black/60 to-gray-900/80 border border-yellow-500/30 rounded-xl p-6">
                                <h4 class="text-lg font-bold text-yellow-500 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z" />
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Resumen de Costos
                                </h4>

                                <!-- TODO: Backend - Conectar con cálculos dinámicos -->
                                <div class="space-y-3">
                                    <div class="flex justify-between items-center pb-3 border-b border-yellow-500/20">
                                        <span class="text-sm text-gray-400">Costo de envio de Paquete</span>
                                        <span class="text-lg font-bold text-white" id="costPackage">$--</span>
                                    </div>
                                    <div class="flex justify-between items-center pt-2">
                                        <span class="text-lg font-black text-green-400">Total Estimado</span>
                                        <span
                                            class="text-3xl font-black bg-gradient-to-r from-green-400 to-emerald-500 bg-clip-text text-transparent"
                                            id="estimatedTotal">$--</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column: Shipping Options -->
                        <div class="space-y-6">
                            <div
                                class="bg-gradient-to-br from-gray-900/80 via-black/60 to-gray-900/80 border border-yellow-500/30 rounded-xl p-6">
                                <h4 class="text-lg font-bold text-yellow-500 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 0l-2 2a1 1 0 101.414 1.414L8 10.414l1.293 1.293a1 1 0 001.414 0l4-4z" />
                                    </svg>
                                    Selecciona Tu Cotización
                                </h4>

                                <!-- TODO: Backend - Conectar con calculadoras -->
                                <p class="text-sm text-gray-400 mb-6">Elige el tipo de transporte que mejor se adapte a
                                    tus necesidades</p>

                                <div class="space-y-4">
                                    <!-- Cotización Marítima -->
                                    <a href="/maritimo"
                                        class="block bg-gradient-to-br from-blue-900/30 to-blue-800/20 border-2 border-blue-500/40 hover:border-blue-500 rounded-xl p-5 transition-all group hover:shadow-lg hover:shadow-blue-500/20">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-4">
                                                <div
                                                    class="w-12 h-12 bg-blue-500/20 rounded-lg flex items-center justify-center group-hover:bg-blue-500/30 transition-all">
                                                    <svg class="w-7 h-7 text-blue-400" fill="currentColor"
                                                        viewBox="0 0 20 20">
                                                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                                                        <path fill-rule="evenodd"
                                                            d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm9.707 5.707a1 1 0 00-1.414-1.414L9 12.586l-1.293-1.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </div>
                                                <div>
                                                    <h5
                                                        class="font-bold text-white text-lg group-hover:text-blue-400 transition-colors">
                                                        Cotización Marítima</h5>
                                                    <p class="text-xs text-gray-400">15-30 días · Grandes volúmenes</p>
                                                </div>
                                            </div>
                                            <svg class="w-6 h-6 text-blue-400 transform group-hover:translate-x-1 transition-transform"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5l7 7-7 7" />
                                            </svg>
                                        </div>
                                    </a>

                                    <!-- Cotización Aérea -->
                                    <a href="/aereo"
                                        class="block bg-gradient-to-br from-amber-900/30 to-orange-800/20 border-2 border-amber-500/40 hover:border-amber-500 rounded-xl p-5 transition-all group hover:shadow-lg hover:shadow-amber-500/20">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-4">
                                                <div
                                                    class="w-12 h-12 bg-amber-500/20 rounded-lg flex items-center justify-center group-hover:bg-amber-500/30 transition-all">
                                                    <svg class="w-7 h-7 text-amber-400" fill="currentColor"
                                                        viewBox="0 0 20 20">
                                                        <path
                                                            d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                                                    </svg>
                                                </div>
                                                <div>
                                                    <h5
                                                        class="font-bold text-white text-lg group-hover:text-amber-400 transition-colors">
                                                        Cotización Aérea</h5>
                                                    <p class="text-xs text-gray-400">4-8 días · Express Internacional
                                                    </p>
                                                </div>
                                            </div>
                                            <svg class="w-6 h-6 text-amber-400 transform group-hover:translate-x-1 transition-transform"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5l7 7-7 7" />
                                            </svg>
                                        </div>
                                    </a>

                                    <!-- Cotización Terrestre -->
                                    <a href="/terrestre"
                                        class="block bg-gradient-to-br from-green-900/30 to-emerald-800/20 border-2 border-green-500/40 hover:border-green-500 rounded-xl p-5 transition-all group hover:shadow-lg hover:shadow-green-500/20">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-4">
                                                <div
                                                    class="w-12 h-12 bg-green-500/20 rounded-lg flex items-center justify-center group-hover:bg-green-500/30 transition-all">
                                                    <svg class="w-7 h-7 text-green-400" fill="currentColor"
                                                        viewBox="0 0 20 20">
                                                        <path
                                                            d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" />
                                                        <path
                                                            d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z" />
                                                    </svg>
                                                </div>
                                                <div>
                                                    <h5
                                                        class="font-bold text-white text-lg group-hover:text-green-400 transition-colors">
                                                        Cotización Terrestre</h5>
                                                    <p class="text-xs text-gray-400">5-10 días · Transporte Regional
                                                    </p>
                                                </div>
                                            </div>
                                            <svg class="w-6 h-6 text-green-400 transform group-hover:translate-x-1 transition-transform"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5l7 7-7 7" />
                                            </svg>
                                        </div>
                                    </a>
                                </div>

                                <!-- Info Note -->
                                <div class="mt-6 bg-yellow-500/10 border border-yellow-500/30 rounded-lg p-4">
                                    <div class="flex items-start space-x-3">
                                        <svg class="w-5 h-5 text-yellow-500 flex-shrink-0 mt-0.5" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <div>
                                            <p class="text-sm font-semibold text-yellow-500 mb-1">Cotización
                                                Instantánea</p>
                                            <p class="text-xs text-gray-400">Accede a nuestra calculadora interactiva y
                                                obtén precios en tiempo real según el tipo de envío seleccionado.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Close Button -->
                            <button id="closeDashboardBtn"
                                class="w-full bg-gray-800 hover:bg-gray-700 border border-gray-600 text-gray-300 py-3 rounded-lg font-semibold text-sm transition-all">
                                Cerrar Dashboard
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Grid Section -->
    <section
        class="relative bg-gradient-to-b from-black via-gray-900 to-black py-16 sm:py-20 lg:py-24 overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div
                class="absolute top-0 right-1/4 w-96 h-96 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-full blur-3xl">
            </div>
            <div
                class="absolute bottom-0 left-1/4 w-96 h-96 bg-gradient-to-tl from-amber-500 to-yellow-600 rounded-full blur-3xl">
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <!-- Section Header -->
            <div class="text-center mb-12 lg:mb-16">
                <span class="text-amber-500 font-bold text-sm sm:text-base tracking-widest uppercase">Servicios
                    Adicionales</span>
                <h2 class="text-3xl sm:text-4xl md:text-5xl font-black text-white mt-4 mb-6">
                    Más que <span
                        class="bg-gradient-to-r from-yellow-500 via-amber-500 to-yellow-600 bg-clip-text text-transparent">Logística</span>
                </h2>
            </div>

            <!-- Services Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                <!-- Importaciones y Exportaciones -->
                <a href="/importaciones-exportaciones"
                    class="group relative bg-gradient-to-br from-yellow-500/10 to-yellow-600/5 border border-yellow-500/30 hover:border-yellow-500 rounded-2xl p-8 transition-all duration-500 hover:shadow-xl hover:shadow-yellow-500/20 overflow-hidden">
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-yellow-500/0 to-yellow-500/0 group-hover:from-yellow-500/10 group-hover:to-yellow-500/5 transition-all duration-500">
                    </div>

                    <div class="relative z-10">
                        <div
                            class="w-16 h-16 bg-yellow-500/20 group-hover:bg-yellow-500/30 rounded-2xl flex items-center justify-center mb-6 transition-all duration-500 group-hover:scale-110">
                            <svg class="w-8 h-8 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" />
                                <path
                                    d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z" />
                            </svg>
                        </div>

                        <h3
                            class="text-xl font-bold text-white mb-3 group-hover:text-yellow-400 transition-colors duration-300">
                            Importaciones y Exportaciones
                        </h3>

                        <p class="text-sm text-gray-400 leading-relaxed mb-6">
                            Guías completas para importar desde China, Japón, EE.UU. y más. Procesos aduaneros,
                            documentación y mejores prácticas.
                        </p>

                        <div class="flex items-center text-yellow-400 font-semibold text-sm">
                            <span>Más información</span>
                            <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-2 transition-transform duration-300"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </div>
                    </div>
                </a>

                <!-- Capacitaciones -->
                <a href="/capacitaciones"
                    class="group relative bg-gradient-to-br from-yellow-500/10 to-amber-600/5 border border-yellow-500/30 hover:border-yellow-500 rounded-2xl p-8 transition-all duration-500 hover:shadow-xl hover:shadow-yellow-500/20 overflow-hidden">
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-yellow-500/0 to-amber-500/0 group-hover:from-yellow-500/10 group-hover:to-amber-500/5 transition-all duration-500">
                    </div>

                    <div class="relative z-10">
                        <div
                            class="w-16 h-16 bg-yellow-500/20 group-hover:bg-amber-500/30 rounded-2xl flex items-center justify-center mb-6 transition-all duration-500 group-hover:scale-110">
                            <svg class="w-8 h-8 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" />
                            </svg>
                        </div>

                        <h3
                            class="text-xl font-bold text-white mb-3 group-hover:text-yellow-400 transition-colors duration-300">
                            Capacitaciones
                        </h3>

                        <p class="text-sm text-gray-400 leading-relaxed mb-6">
                            Asesoramiento personalizado para transformar tu idea en un negocio rentable y escalable a
                            nivel internacional.
                        </p>

                        <div class="flex items-center text-yellow-400 font-semibold text-sm">
                            <span>Más información</span>
                            <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-2 transition-transform duration-300"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </div>
                    </div>
                </a>

                <!-- Logística y Transporte -->
                <a href="/logistica-transporte"
                    class="group relative bg-gradient-to-br from-amber-500/10 to-orange-600/5 border border-amber-500/30 hover:border-amber-500 rounded-2xl p-8 transition-all duration-500 hover:shadow-xl hover:shadow-amber-500/20 overflow-hidden">
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-amber-500/0 to-orange-500/0 group-hover:from-amber-500/10 group-hover:to-orange-500/5 transition-all duration-500">
                    </div>

                    <div class="relative z-10">
                        <div
                            class="w-16 h-16 bg-amber-500/20 group-hover:bg-orange-500/30 rounded-2xl flex items-center justify-center mb-6 transition-all duration-500 group-hover:scale-110">
                            <svg class="w-8 h-8 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                            </svg>
                        </div>

                        <h3
                            class="text-xl font-bold text-white mb-3 group-hover:text-amber-400 transition-colors duration-300">
                            Logística y Transporte
                        </h3>

                        <p class="text-sm text-gray-400 leading-relaxed mb-6">
                            Optimización de cadenas de suministro, transporte internacional y gestión eficiente de
                            operaciones.
                        </p>

                        <div class="flex items-center text-amber-400 font-semibold text-sm">
                            <span>Más información</span>
                            <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-2 transition-transform duration-300"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </div>
                    </div>
                </a>

                <!-- Criptomonedas -->
                <a href="/criptomonedas"
                    class="group relative bg-gradient-to-br from-orange-500/10 to-yellow-600/5 border border-orange-500/30 hover:border-orange-500 rounded-2xl p-8 transition-all duration-500 hover:shadow-xl hover:shadow-orange-500/20 overflow-hidden">
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-orange-500/0 to-yellow-500/0 group-hover:from-orange-500/10 group-hover:to-yellow-500/5 transition-all duration-500">
                    </div>

                    <div class="relative z-10">
                        <div
                            class="w-16 h-16 bg-orange-500/20 group-hover:bg-yellow-500/30 rounded-2xl flex items-center justify-center mb-6 transition-all duration-500 group-hover:scale-110">
                            <svg class="w-8 h-8 text-orange-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 5a1 1 0 000 2h1a2 2 0 011.732 1H7a1 1 0 000 2h2.732A2 2 0 018 11H7a1 1 0 100 2h1a2 2 0 011.732 1H7a1 1 0 100 2h2.732A2 2 0 0111 15h2a1 1 0 100-2h-2a2 2 0 01-1.732-1H13a1 1 0 100-2H9.268A2 2 0 0111 9h2a1 1 0 100-2h-2a2 2 0 01-1.732-1H13a1 1 0 100-2H7z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>

                        <h3
                            class="text-xl font-bold text-white mb-3 group-hover:text-orange-400 transition-colors duration-300">
                            Criptomonedas
                        </h3>

                        <p class="text-sm text-gray-400 leading-relaxed mb-6">
                            Pagos digitales seguros, billeteras virtuales y métodos de pago modernos para tu negocio
                            global.
                        </p>

                        <div class="flex items-center text-orange-400 font-semibold text-sm">
                            <span>Más información</span>
                            <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-2 transition-transform duration-300"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </div>
                    </div>
                </a>

                <!-- E-Commerce -->
                <a href="/ecommerce"
                    class="group relative bg-gradient-to-br from-yellow-500/10 to-amber-600/5 border border-yellow-500/30 hover:border-yellow-500 rounded-2xl p-8 transition-all duration-500 hover:shadow-xl hover:shadow-yellow-500/20 overflow-hidden">
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-yellow-500/0 to-amber-500/0 group-hover:from-yellow-500/10 group-hover:to-amber-500/5 transition-all duration-500">
                    </div>

                    <div class="relative z-10">
                        <div
                            class="w-16 h-16 bg-yellow-500/20 group-hover:bg-amber-500/30 rounded-2xl flex items-center justify-center mb-6 transition-all duration-500 group-hover:scale-110">
                            <svg class="w-8 h-8 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                            </svg>
                        </div>

                        <h3
                            class="text-xl font-bold text-white mb-3 group-hover:text-yellow-400 transition-colors duration-300">
                            E-Commerce
                        </h3>

                        <p class="text-sm text-gray-400 leading-relaxed mb-6">
                            Estrategias de venta online, marketing digital y posicionamiento en plataformas globales
                            como Amazon.
                        </p>

                        <div class="flex items-center text-yellow-400 font-semibold text-sm">
                            <span>Más información</span>
                            <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-2 transition-transform duration-300"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </div>
                    </div>
                </a>

                <!-- Subastas -->
                <a href="/subastas"
                    class="group relative bg-gradient-to-br from-amber-500/10 to-orange-600/5 border border-amber-500/30 hover:border-amber-500 rounded-2xl p-8 transition-all duration-500 hover:shadow-xl hover:shadow-amber-500/20 overflow-hidden">
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-amber-500/0 to-orange-500/0 group-hover:from-amber-500/10 group-hover:to-orange-500/5 transition-all duration-500">
                    </div>

                    <div class="relative z-10">
                        <div
                            class="w-16 h-16 bg-amber-500/20 group-hover:bg-orange-500/30 rounded-2xl flex items-center justify-center mb-6 transition-all duration-500 group-hover:scale-110">
                            <svg class="w-8 h-8 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 2a1 1 0 011 1v1.323l3.954 1.582 1.599-.8a1 1 0 01.894 1.79l-1.233.616 1.738 5.42a1 1 0 01-.285 1.05A3.989 3.989 0 0115 15a3.989 3.989 0 01-2.667-1.019 1 1 0 01-.285-1.05l1.715-5.349L11 6.477V16h2a1 1 0 110 2H7a1 1 0 110-2h2V6.477L6.237 7.582l1.715 5.349a1 1 0 01-.285 1.05A3.989 3.989 0 015 15a3.989 3.989 0 01-2.667-1.019 1 1 0 01-.285-1.05l1.738-5.42-1.233-.617a1 1 0 01.894-1.788l1.599.799L9 4.323V3a1 1 0 011-1zm-5 8.274l-.818 2.552c.25.112.526.174.818.174.292 0 .569-.062.818-.174L5 10.274zm10 0l-.818 2.552c.25.112.526.174.818.174.292 0 .569-.062.818-.174L15 10.274z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>

                        <h3
                            class="text-xl font-bold text-white mb-3 group-hover:text-amber-400 transition-colors duration-300">
                            Subastas
                        </h3>

                        <p class="text-sm text-gray-400 leading-relaxed mb-6">
                            Acceso a subastas internacionales de vehículos, maquinaria pesada y productos
                            especializados.
                        </p>

                        <div class="flex items-center text-amber-400 font-semibold text-sm">
                            <span>Más información</span>
                            <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-2 transition-transform duration-300"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </div>
                    </div>
                </a>

            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="servicios"
        class="relative bg-gradient-to-b from-black via-gray-900 to-black py-12 sm:py-16 overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div
                class="absolute top-0 right-1/4 w-96 h-96 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-full blur-3xl">
            </div>
            <div
                class="absolute bottom-0 left-1/4 w-96 h-96 bg-gradient-to-tl from-amber-500 to-yellow-600 rounded-full blur-3xl">
            </div>
        </div>

        <div class="relative z-10 text-center mb-8 sm:mb-12 px-4">
            <span class="text-amber-500 font-bold text-sm sm:text-base tracking-widest uppercase">Nuestros
                Servicios</span>
            <h2 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-black text-white mt-4 mb-6">
                Soluciones <span
                    class="bg-gradient-to-r from-yellow-500 via-amber-500 to-yellow-600 bg-clip-text text-transparent">Integrales</span>
            </h2>
            <p class="text-base sm:text-lg text-gray-300 max-w-3xl mx-auto">
                Selecciona el servicio que necesitas y obtén tu cotización instantánea
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4">

            <!-- Marítimo -->
            <a href="/maritimo"
                class="group relative overflow-hidden h-[350px] xs:h-[400px] sm:h-[450px] md:h-[500px] lg:h-[600px] border-r border-b border-yellow-500/10">
                <!-- Background Image (Usuario reemplazará la ruta) -->
                <div class="absolute inset-0">
                    <img src="/images/calcbarco.jpg" alt="Servicio Marítimo"
                        class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                    <div
                        class="absolute inset-0 bg-gradient-to-t from-black via-black/80 to-black/40 group-hover:from-black/90 group-hover:via-black/70 transition-all duration-500">
                    </div>
                </div>

                <div class="absolute inset-0 flex flex-col justify-end p-8 sm:p-10 lg:p-12 z-10">
                    <div class="transform transition-all duration-500 group-hover:-translate-y-4">
                        <div
                            class="w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-br from-yellow-500 to-amber-600 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform shadow-2xl shadow-amber-500/50">
                            <svg class="w-8 h-8 sm:w-10 sm:h-10 text-black" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z" />
                            </svg>
                        </div>

                        <h3
                            class="text-3xl sm:text-4xl lg:text-5xl font-black text-white mb-4 uppercase tracking-tight">
                            Marítimo</h3>
                        <p
                            class="text-base sm:text-lg text-gray-300 mb-6 leading-relaxed opacity-0 group-hover:opacity-100 transition-opacity duration-500 delay-100">
                            LCL, FCL y ULD. Conectamos puertos globales con tarifas competitivas.
                        </p>

                        <div class="flex items-center text-yellow-500 font-bold text-lg sm:text-xl">
                            <span class="group-hover:text-yellow-400 transition-colors">COTIZAR</span>
                            <svg class="w-6 h-6 ml-3 transform group-hover:translate-x-2 transition-transform"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </div>
                    </div>
                </div>
            </a>

            <!-- Aéreo -->
            <a href="/aereo"
                class="group relative overflow-hidden h-[350px] xs:h-[400px] sm:h-[450px] md:h-[500px] lg:h-[600px] border-r border-b border-yellow-500/10">
                <!-- Background Image (Usuario reemplazará la ruta) -->
                <div class="absolute inset-0">
                    <img src="/images/avioncalcu.jpg" alt="Servicio Aéreo"
                        class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                    <div
                        class="absolute inset-0 bg-gradient-to-t from-black via-black/80 to-black/40 group-hover:from-black/90 group-hover:via-black/70 transition-all duration-500">
                    </div>
                </div>

                <div class="absolute inset-0 flex flex-col justify-end p-8 sm:p-10 lg:p-12 z-10">
                    <div class="transform transition-all duration-500 group-hover:-translate-y-4">
                        <div
                            class="w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-br from-amber-500 to-orange-500 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform shadow-2xl shadow-orange-500/50">
                            <svg class="w-8 h-8 sm:w-10 sm:h-10 text-black" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                        </div>

                        <h3
                            class="text-3xl sm:text-4xl lg:text-5xl font-black text-white mb-4 uppercase tracking-tight">
                            Aéreo</h3>
                        <p
                            class="text-base sm:text-lg text-gray-300 mb-6 leading-relaxed opacity-0 group-hover:opacity-100 transition-opacity duration-500 delay-100">
                            Velocidad máxima. Peso volumétrico y servicio express disponible.
                        </p>

                        <div class="flex items-center text-yellow-500 font-bold text-lg sm:text-xl">
                            <span class="group-hover:text-yellow-400 transition-colors">COTIZAR</span>
                            <svg class="w-6 h-6 ml-3 transform group-hover:translate-x-2 transition-transform"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </div>
                    </div>
                </div>
            </a>

            <!-- Terrestre -->
            <a href="/terrestre"
                class="group relative overflow-hidden h-[350px] xs:h-[400px] sm:h-[450px] md:h-[500px] lg:h-[600px] border-r border-b border-yellow-500/10">
                <!-- Background Image (Usuario reemplazará la ruta) -->
                <div class="absolute inset-0">
                    <img src="/images/camioncal.jpg" alt="Servicio Terrestre"
                        class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                    <div
                        class="absolute inset-0 bg-gradient-to-t from-black via-black/80 to-black/40 group-hover:from-black/90 group-hover:via-black/70 transition-all duration-500">
                    </div>
                </div>

                <div class="absolute inset-0 flex flex-col justify-end p-8 sm:p-10 lg:p-12 z-10">
                    <div class="transform transition-all duration-500 group-hover:-translate-y-4">
                        <div
                            class="w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-br from-yellow-600 to-amber-500 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform shadow-2xl shadow-yellow-600/50">
                            <svg class="w-8 h-8 sm:w-10 sm:h-10 text-black" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>

                        <h3
                            class="text-3xl sm:text-4xl lg:text-5xl font-black text-white mb-4 uppercase tracking-tight">
                            Terrestre</h3>
                        <p
                            class="text-base sm:text-lg text-gray-300 mb-6 leading-relaxed opacity-0 group-hover:opacity-100 transition-opacity duration-500 delay-100">
                            Rutas terrestres optimizadas. Cálculo por kilómetro y peajes.
                        </p>

                        <div class="flex items-center text-yellow-500 font-bold text-lg sm:text-xl">
                            <span class="group-hover:text-yellow-400 transition-colors">COTIZAR</span>
                            <svg class="w-6 h-6 ml-3 transform group-hover:translate-x-2 transition-transform"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </div>
                    </div>
                </div>
            </a>

            <!-- Impuestos -->
            <a href="/impuestos"
                class="group relative overflow-hidden h-[350px] xs:h-[400px] sm:h-[450px] md:h-[500px] lg:h-[600px] border-r border-b border-yellow-500/10">
                <!-- Background Image (Usuario reemplazará la ruta) -->
                <div class="absolute inset-0">
                    <img src="/images/impuestoscalc.jpg" alt="Servicio Impuestos"
                        class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                    <div
                        class="absolute inset-0 bg-gradient-to-t from-black via-black/80 to-black/40 group-hover:from-black/90 group-hover:via-black/70 transition-all duration-500">
                    </div>
                </div>

                <div class="absolute inset-0 flex flex-col justify-end p-8 sm:p-10 lg:p-12 z-10">
                    <div class="transform transition-all duration-500 group-hover:-translate-y-4">
                        <div
                            class="w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-br from-orange-500 to-yellow-500 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform shadow-2xl shadow-orange-500/50">
                            <svg class="w-8 h-8 sm:w-10 sm:h-10 text-black" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                        </div>

                        <h3
                            class="text-3xl sm:text-4xl lg:text-5xl font-black text-white mb-4 uppercase tracking-tight">
                            Impuestos</h3>
                        <p
                            class="text-base sm:text-lg text-gray-300 mb-6 leading-relaxed opacity-0 group-hover:opacity-100 transition-opacity duration-500 delay-100">
                            Aranceles, IVA y DUA. Gestión aduanera completa y transparente.
                        </p>

                        <div class="flex items-center text-yellow-500 font-bold text-lg sm:text-xl">
                            <span class="group-hover:text-yellow-400 transition-colors">COTIZAR</span>
                            <svg class="w-6 h-6 ml-3 transform group-hover:translate-x-2 transition-transform"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </div>
                    </div>
                </div>
            </a>

        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-black border-t border-yellow-500/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 sm:gap-12">
                <!-- Company Info -->
                <div>
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-12 h-12 bg-yellow-500/10 rounded-lg flex items-center justify-center">
                            <img src="images/logo.png" alt="IA Groups Logo" class="w-full h-full object-contain">
                        </div>
                        <h3 class="text-xl font-bold text-yellow-500">IA GROUPS</h3>
                    </div>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        Conectando el mundo a través de soluciones logísticas inteligentes. Tu socio en comercio
                        internacional.
                    </p>
                </div>

                <!-- Quick Links -->
                <div>
                    <h4 class="text-yellow-500 font-bold mb-4 uppercase tracking-wide text-sm">Enlaces Rápidos</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('home') }}"
                                class="text-gray-400 hover:text-yellow-500 transition-colors text-sm">Inicio</a></li>
                        <li><a href="{{ route('nosotros') }}"
                                class="text-gray-400 hover:text-yellow-500 transition-colors text-sm">Nosotros</a></li>
                        <li><a href="#servicios"
                                class="text-gray-400 hover:text-yellow-500 transition-colors text-sm">Servicios</a>
                        </li>
                    </ul>
                </div>

                <!-- Services -->
                <div>
                    <h4 class="text-yellow-500 font-bold mb-4 uppercase tracking-wide text-sm">Servicios</h4>
                    <ul class="space-y-2">
                        <li><a href="/maritimo"
                                class="text-gray-400 hover:text-yellow-500 transition-colors text-sm">Transporte
                                Marítimo</a></li>
                        <li><a href="/aereo"
                                class="text-gray-400 hover:text-yellow-500 transition-colors text-sm">Transporte
                                Aéreo</a></li>
                        <li><a href="/terrestre"
                                class="text-gray-400 hover:text-yellow-500 transition-colors text-sm">Transporte
                                Terrestre</a></li>
                        <li><a href="/impuestos"
                                class="text-gray-400 hover:text-yellow-500 transition-colors text-sm">Gestión de
                                Impuestos</a></li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div>
                    <h4 class="text-yellow-500 font-bold mb-4 uppercase tracking-wide text-sm">Contacto</h4>
                    <ul class="space-y-3">
                        <li class="flex items-start space-x-3">
                            <svg class="w-5 h-5 text-yellow-500 mt-0.5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span class="text-gray-400 text-sm">info@iagroups.com</span>
                        </li>
                        <li class="flex items-start space-x-3">
                            <svg class="w-5 h-5 text-yellow-500 mt-0.5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            <span class="text-gray-400 text-sm">+591 64700457</span>
                        </li>
                        <li class="flex items-start space-x-3">
                            <svg class="w-5 h-5 text-yellow-500 mt-0.5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span class="text-gray-400 text-sm">Tarija, Bolivia</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-yellow-500/20 mt-12 pt-8">
                <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                    <p class="text-gray-500 text-sm">
                        © 2025 IA GROUPS. Todos los derechos reservados.
                    </p>
                    <div class="flex space-x-6">
                        <a href="#" class="text-gray-400 hover:text-yellow-500 transition-colors">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-yellow-500 transition-colors">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-yellow-500 transition-colors">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    @livewireScripts

    <script>
        // Mobile Menu System
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const closeMobileMenu = document.getElementById('closeMobileMenu');
        const mobileMenu = document.getElementById('mobileMenu');
        const mobileMenuLinks = document.querySelectorAll('.mobile-menu-link');

        mobileMenuBtn.addEventListener('click', () => {
            mobileMenu.classList.remove('hidden');
            setTimeout(() => {
                mobileMenu.style.opacity = '1';
            }, 10);
        });

        closeMobileMenu.addEventListener('click', () => {
            mobileMenu.style.opacity = '0';
            setTimeout(() => {
                mobileMenu.classList.add('hidden');
            }, 300);
        });

        mobileMenuLinks.forEach(link => {
            link.addEventListener('click', () => {
                mobileMenu.style.opacity = '0';
                setTimeout(() => {
                    mobileMenu.classList.add('hidden');
                }, 300);
            });
        });

        // Carousel System
        let currentSlide = 0;
        const slides = document.querySelectorAll('.carousel-slide');
        const totalSlides = slides.length;

        function showSlide(index) {
            slides.forEach((slide, i) => {
                slide.classList.remove('active');
                if (i === index) {
                    slide.classList.add('active');
                }
            });
        }

        function nextSlide() {
            currentSlide = (currentSlide + 1) % totalSlides;
            showSlide(currentSlide);
        }

        function prevSlide() {
            currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
            showSlide(currentSlide);
        }

        // Auto-advance cada 2 segundos (rápido y agresivo)
        setInterval(nextSlide, 2000);

        document.getElementById('nextBtn').addEventListener('click', nextSlide);
        document.getElementById('prevBtn').addEventListener('click', prevSlide);

        // Quote Dashboard System

        const quoteDashboard = document.getElementById('quoteDashboard');
        const closeDashboard = document.getElementById('closeDashboard');
        const closeDashboardBtn = document.getElementById('closeDashboardBtn');
        const productUrl = document.getElementById('productUrl');



        const closeDashboardFunction = () => {
            quoteDashboard.style.opacity = '0';
            quoteDashboard.style.transform = 'translateY(20px)';
            setTimeout(() => {
                quoteDashboard.classList.add('hidden');
            }, 300);
        };

        closeDashboard.addEventListener('click', closeDashboardFunction);
        closeDashboardBtn.addEventListener('click', closeDashboardFunction);
    </script>

    <!-- Social Media Floating Icons - Fixed Right Side with Animations -->
    <div class="fixed right-6 top-1/2 -translate-y-1/2 z-50 flex flex-col gap-5">

        <!-- WhatsApp - Verdadero color con animación hover -->
        <a href="https://wa.me/59164700293" target="_blank"
            class="group w-16 h-16 bg-black/50 backdrop-blur-sm rounded-full flex items-center justify-center shadow-xl overflow-hidden transition-all duration-300 hover:scale-110 hover:rotate-6">
            <!-- Color real de WhatsApp solo visible en hover -->
            <div
                class="absolute inset-0 bg-gradient-to-br from-[#25D366] to-[#128C7E] opacity-0 group-hover:opacity-100 transition-opacity duration-300">
            </div>
            <svg class="relative w-8 h-8 fill-white transition-all duration-300 group-hover:scale-110"
                viewBox="0 0 24 24">
                <path
                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.372-.025-.521-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
            </svg>
        </a>

        <!-- Facebook - Azul oficial con animación -->
        <a href="https://facebook.com" target="_blank"
            class="group w-16 h-16 bg-black/50 backdrop-blur-sm rounded-full flex items-center justify-center shadow-xl overflow-hidden transition-all duration-300 hover:scale-110 hover:-rotate-6">
            <div
                class="absolute inset-0 bg-gradient-to-br from-[#1877F2] to-[#0E5A9E] opacity-0 group-hover:opacity-100 transition-opacity duration-300">
            </div>
            <svg class="relative w-8 h-8 fill-white transition-all duration-300 group-hover:scale-110"
                viewBox="0 0 24 24">
                <path
                    d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
            </svg>
        </a>

        <!-- TikTok - Logo negro con gradiente característico -->
        <a href="https://tiktok.com/@iagroups" target="_blank"
            class="group w-16 h-16 bg-black/50 backdrop-blur-sm rounded-full flex items-center justify-center shadow-xl overflow-hidden transition-all duration-300 hover:scale-110 hover:rotate-3">
            <!-- Gradiente característico de TikTok -->
            <div
                class="absolute inset-0 bg-gradient-to-br from-[#000000] via-[#FF0050] to-[#00F2EA] opacity-0 group-hover:opacity-100 transition-opacity duration-300">
            </div>
            <svg class="relative w-8 h-8 fill-white transition-all duration-300 group-hover:scale-110"
                viewBox="0 0 24 24">
                <path
                    d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.52.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z" />
            </svg>
        </a>

        <!-- Gmail/Email - Rojo oficial -->
        <a href="mailto:info@iagroups.com"
            class="group w-16 h-16 bg-black/50 backdrop-blur-sm rounded-full flex items-center justify-center shadow-xl overflow-hidden transition-all duration-300 hover:scale-110 hover:-rotate-3">
            <div
                class="absolute inset-0 bg-gradient-to-br from-[#EA4335] to-[#D33B2C] opacity-0 group-hover:opacity-100 transition-opacity duration-300">
            </div>
            <svg class="relative w-8 h-8 fill-white transition-all duration-300 group-hover:scale-110"
                viewBox="0 0 24 24">
                <path
                    d="M24 5.457v13.909c0 .904-.732 1.636-1.636 1.636h-3.819V11.73L12 16.64l-6.545-4.91v9.273H1.636A1.636 1.636 0 0 1 0 19.366V5.457c0-.512.241-.994.645-1.3L11.64.153a1.636 1.636 0 0 1 1.719 0l10.364 3.964a1.636 1.636 0 0 1 .277 2.91l-10.661 4.073z" />
            </svg>
        </a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark/dark.css">
    <script>
        document.getElementById('search-form').addEventListener('submit', function(e) {
            e.preventDefault();
            scrapeProduct();
        });
        document.getElementById('productUrl').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                scrapeProduct();
            }
        });
        let eventSource = null;

        function calcularCostoMaritimoLCL(pesoKg, cbmReal) {
            if (typeof pesoKg !== 'number' || typeof cbmReal !== 'number') {
                console.error("Error: pesoKg o cbmReal deben ser números.");
                return {
                    costo: "0.00",
                    tipo: "Error",
                    valorUsado: "0.000",
                    unidad: ""
                };
            }

            // TARIFA POR PESO (cuando CBM < 0.01)
            const TARIFA_POR_KG = {
                1: 10,
                2: 9.5,
                3: 9,
                4: 8.5,
                5: 8,
                6: 7.5,
                7: 7,
                8: 6.5,
                9: 6,
                10: 5.5,
                11: 5,
                12: 4.5,
                13: 4,
                14: 3.5,
                15: 3,
                16: 2.5
            };

            // TARIFA POR M³ (cuando CBM ≥ 0.01)
            const TARIFA_POR_CBM = {
                20: 129,
                15: 138,
                11: 149,
                8: 159,
                5: 168,
                3: 179,
                1: 188,
                0.5: 116,
                0.25: 60
            };

            let costoFinal = 0;
            let tipoCobro = '';
            let valorUsado = 0;

            // REGLA DE ORO: Si CBM < 0.01 → se cobra por peso (W/M)
            if (cbmReal < 0.01) {
                tipoCobro = 'Peso (W/M)';
                valorUsado = Math.ceil(pesoKg); // Redondea el peso hacia arriba (siempre un número)

                // SOLUCIÓN 2: Lógica de la Tarifa de Peso (más clara y segura)
                let tarifaPorUnidad;
                if (valorUsado > 16) {
                    // Después de 16kg, la tarifa es fija ($2.5/kg)
                    tarifaPorUnidad = 2.5;
                } else {
                    // Busca la tarifa en la tabla, si no existe (ej: 0kg), usa 2.5 como fallback
                    tarifaPorUnidad = TARIFA_POR_KG[valorUsado] || 2.5;
                }

                costoFinal = tarifaPorUnidad * valorUsado;

            } else {
                // COBRO POR CBM REAL
                tipoCobro = 'CBM Real';
                valorUsado = cbmReal;

                // Buscar en la tabla de CBM (de mayor a menor)
                const claves = Object.keys(TARIFA_POR_CBM).map(Number).sort((a, b) => b - a);
                for (let limite of claves) {
                    if (cbmReal >= limite) {
                        costoFinal = TARIFA_POR_CBM[limite];
                        break;
                    }
                }
                // Si es menor a 0.25 → se cobra como 0.25 ($60)
                if (costoFinal === 0) costoFinal = 60;
            }

            // Aseguramos que las variables sean números antes de aplicar .toFixed()
            costoFinal = Number(costoFinal);
            valorUsado = Number(valorUsado);

            return {
                costo: costoFinal.toFixed(2),
                tipo: tipoCobro,
                valorUsado: valorUsado.toFixed(3), // ¡Ahora valorUsado es garantizado como Number!
                unidad: tipoCobro.includes('Peso') ? 'kg' : 'm³'
            };
        }

        function mostrarResultado(data) {

            // Mostrar imagen
            const img = document.getElementById('scraped-image');
            const placeholder = document.getElementById('placeholder-image');

            if (data.image) {
                img.src = data.image;
                img.classList.remove('hidden');
                placeholder.classList.add('hidden');
            } else {
                img.classList.add('hidden');
                placeholder.classList.remove('hidden');
            }

            // Insertar datos
            const priceTextData = data.price
            let price = priceTextData.split('-').pop().replace(/[^0-9]/g, '').trim();
            document.getElementById('scraped-price').textContent = `$ ${price}` || '--';

            document.getElementById('scraped-moq').textContent =
                data.moq || '--';

            const packageSizeTexto = data.packageSize
            let [L, A, H] = packageSizeTexto.match(/\d+/g).map(Number);
            let packageSize = ((L * A * H) / 1000000).toFixed(2);
            document.getElementById('scraped-package-size').textContent = `${packageSize} m³` || '--';

            const packageWeightTexto = data.packageWeight
            let packageWeight = (packageWeightTexto.replace(/[^0-9]/g, '') / 5000).toFixed(2);
            document.getElementById('scraped-package-weight').textContent = `${packageWeight} Kg` || '--';

            const costPackage = calcularCostoMaritimoLCL(Number(packageWeight), Number(packageSize) ).costo
            const moq = parseInt(data.moq) || 1;
            const totalProducto = costPackage * moq;
            document.getElementById('costPackage').textContent = `$ ${totalProducto.toFixed(2)}`;

            const priceProductElement = document.getElementById('scraped-price');
            const costPackageElement = document.getElementById('costPackage');

            const priceText = priceProductElement.textContent;
            const costText = costPackageElement.textContent;

            const cleanPriceText = priceText.replace(/[^0-9.]/g, '');
            const cleanCostText = costText.replace(/[^0-9.]/g, '');

            const priceProduct = parseFloat(cleanPriceText);
            const costPackageProduct = parseFloat(cleanCostText);

            const estimatedTotal = priceProduct + costPackageProduct;

            if (!isNaN(estimatedTotal)) {
                document.getElementById('estimatedTotal').textContent = `$ ${estimatedTotal.toFixed(2)}`;
            } else {
                document.getElementById('estimatedTotal').textContent = '--';
            }
            // Mostrar dashboard con animación
            const quoteDashboard = document.getElementById('quoteDashboard');
            quoteDashboard.classList.remove('hidden');
            quoteDashboard.style.opacity = '0';
            quoteDashboard.style.transform = 'translateY(20px)';

            setTimeout(() => {
                quoteDashboard.style.transition = 'all 0.5s ease';
                quoteDashboard.style.opacity = '1';
                quoteDashboard.style.transform = 'translateY(0)';
            }, 50);

            // Scroll automático
            setTimeout(() => {
                quoteDashboard.scrollIntoView({
                    behavior: 'smooth',
                    block: 'nearest'
                });
            }, 100);
        }


        function mostrarLoadingNavegacion() {
            const imageUrl = '/images/ship.svg';
            Swal.fire({
                title: 'Buscando datos...',
                html: `
            <div class="boat-container">
                <img src="${imageUrl}" class="boat-svg" style="animation: navigate 8s infinite ease-in-out;" alt="Barco navegando">
            </div>
            <p id="sse-timer" style="margin-top: 10px; font-weight: bold;">Iniciara dentro de poco</p>
        `,
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {}
            });
        }


        async function scrapeProduct() {
            const url = document.getElementById('productUrl').value.trim();
            if (!url) return Swal.fire('Error', 'Pega un link', 'warning');

            document.getElementById('search-button-normal').style.display = 'none';
            document.getElementById('search-button-loading').style.display = 'inline-flex';

            mostrarLoadingNavegacion();

            try {
                const res = await fetch('/scrape-product', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        url
                    })
                });

                if (!res.ok) {
                    console.error('HTTP ERROR:', res.status, await res.text());
                    throw new Error('Bad HTTP response');
                }

                const json = await res.json();

                if (json.status !== 'processing' || !json.runId) {
                    throw new Error('No se pudo iniciar');
                }

                // ABRIR SSE
                eventSource = new EventSource(`/scrape-stream/${json.runId}`);

                eventSource.addEventListener('heartbeat', (e) => {
                    const data = JSON.parse(e.data);
                    document.getElementById('sse-timer').textContent = `Ya pasaron ${data.waiting} segundos`;
                });

                eventSource.addEventListener('ready', (e) => {
                    const data = JSON.parse(e.data);
                    eventSource.close();
                    Swal.close();
                    mostrarResultado(data);
                    resetForm();
                });

                eventSource.addEventListener('timeout', () => {
                    eventSource.close();
                    Swal.fire('Tiempo agotado', 'No se encontraron datos en 90 segundos', 'info');
                    resetForm();
                });

                eventSource.onerror = () => {
                    eventSource.close();
                    Swal.fire('Error', 'Conexión perdida', 'error');
                    resetForm();
                };

            } catch (err) {
                console.error('FETCH ERROR:', err);
                Swal.fire('Error', 'No se pudo iniciarx', 'error');
                resetForm();
            }
        }

        function resetForm() {
            document.getElementById('search-button-normal').style.display = 'inline-flex';
            document.getElementById('search-button-loading').style.display = 'none';
            document.getElementById('productUrl').value = '';
        }
    </script>
</body>

</html>
