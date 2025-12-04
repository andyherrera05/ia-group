# Documentación del Proyecto - IA GROUPS

## Información General

**Nombre del Proyecto:** IA GROUPS - Sistema de Cotización Logística  
**Framework:** Laravel 12.40.2  
**UI Framework:** Livewire 3.7  
**CSS Framework:** TailwindCSS 4.0  
**Fecha de Última Actualización:** Diciembre 2025

---

## Descripción del Proyecto

IA GROUPS es una plataforma web integral para cotización de servicios logísticos internacionales. El sistema permite a los usuarios calcular costos de transporte marítimo, aéreo, terrestre, así como gestionar impuestos aduaneros. Incluye una landing page informativa con calculadoras interactivas y secciones de servicios adicionales.

---

## Estructura del Proyecto

### Arquitectura Principal

```
landingpage/
├── app/
│   ├── Http/Controllers/
│   ├── Livewire/
│   │   ├── CalculadoraMaritima.php
│   │   ├── CalculadoraAerea.php
│   │   ├── CalculadoraTerrestre.php
│   │   └── CalculadoraImpuestos.php
│   └── Models/
├── resources/
│   ├── views/
│   │   ├── welcome-new.blade.php (Landing Principal)
│   │   ├── livewire/ (Vistas de Calculadoras)
│   │   ├── components/ (Componentes Reutilizables)
│   │   └── layouts/ (Plantillas Base)
│   ├── css/
│   └── js/
├── routes/
│   └── web.php
└── public/
    └── images/
        └── logo.png
```

---

## Componentes Principales

### 1. Landing Page (welcome-new.blade.php)

**Ubicación:** `resources/views/welcome-new.blade.php`

**Secciones:**

- **Header/Navbar:**
  - Logo de IA GROUPS (sin bordes amarillos)
  - Navegación: Inicio, Nosotros, Servicios
  - Diseño sticky con backdrop blur
  - Paleta de colores: Yellow (#EAB308), Amber (#F59E0B), Orange (#F97316)

- **Hero Section:**
  - Carrusel de imágenes con zoom animado
  - Título principal y descripción
  - CTA prominente

- **Dashboard de Cotizaciones:**
  - Layout de 2 columnas
  - Columna izquierda: Información de productos (placeholders con TODOs para backend)
  - Columna derecha: Menú de calculadoras con 3 opciones:
    - Transporte Marítimo (azul)
    - Transporte Aéreo (amber)
    - Transporte Terrestre (verde)

- **Grid de Servicios Adicionales:**
  - 6 cards con animaciones hover
  - Servicios: Importaciones/Exportaciones, Capacitaciones, Logística/Transporte, Criptomonedas, E-Commerce, Subastas
  - Cada card usa gradientes yellow/amber/orange (NO teal/cyan)
  - Efectos de hover: escala, sombra, cambio de borde

- **Footer:**
  - Logo de IA GROUPS (mismo del navbar, sin bordes)
  - 4 columnas: Info de empresa, Enlaces rápidos, Servicios, Contacto
  - Información de contacto: email, teléfono (+591 64700457), ubicación (Tarija, Bolivia)
  - Redes sociales: Facebook, Twitter, LinkedIn

### 2. Calculadoras Livewire

**Componentes Backend:**

- `CalculadoraMaritima.php`
- `CalculadoraAerea.php`
- `CalculadoraTerrestre.php`
- `CalculadoraImpuestos.php`

**Propiedades Comunes:**

```php
// Propiedades de cálculo (específicas por calculadora)
public $peso, $volumen, $origen, $destino, etc.

// Propiedades de interacción
public $mostrarPregunta = false;
public $respuestaUsuario = null;
public $resultado = null;
```

**Métodos Principales:**

- `calcular()`: Realiza el cálculo y activa la pregunta interactiva
- `responder($respuesta)`: Captura la respuesta del usuario ('si' o 'no')
- `limpiar()`: Resetea todos los campos y estados

**Flujo de Interacción:**

1. Usuario completa el formulario de cotización
2. Usuario hace clic en "Calcular"
3. Sistema procesa y muestra el resultado
4. Aparece la pregunta: "¿Te gusta el precio?"
5. Usuario selecciona SÍ o NO
6. Si SÍ: Mensaje positivo + botón WhatsApp con precio incluido
7. Si NO: Mensaje de comprensión + invitación a contactar

**Vistas de Calculadoras:**

- Diseño oscuro (fondo negro con efectos de blur amarillo)
- Header con logo de IA GROUPS
- Formulario con validación en tiempo real
- Sección de resultados con desglose detallado
- Sección interactiva con animaciones (borde dorado pulsante)
- Botones con efectos hover y transiciones

### 3. Páginas de Servicios Adicionales

**Ubicación:** `resources/views/`

**Archivos:**
- `importaciones-exportaciones.blade.php`
- `capacitaciones.blade.php`
- `logistica-transporte.blade.php`
- `criptomonedas.blade.php`
- `ecommerce.blade.php`
- `subastas.blade.php`

**Características:**

- Todas muestran mensaje: "Estamos trabajando en ello"
- Diseño consistente con background blur animado
- Barra de progreso con porcentaje único por servicio
- Botón de regreso a home
- Íconos representativos de cada servicio

---

## Paleta de Colores

### Colores Principales (USAR ESTOS)

- **Yellow:** `#EAB308` (yellow-500)
- **Amber:** `#F59E0B` (amber-500)
- **Orange:** `#F97316` (orange-500)

### Colores de Estado

- **Positivo/Afirmativo:** Verde (`green-500`, `green-600`)
- **Negativo/Rechazo:** Rojo (`red-500`, `red-600`)
- **Neutro:** Gris (`gray-400`, `gray-500`, `gray-600`)

### Colores NO Usar

- **Teal:** `#14B8A6` (INCORRECTO - ya corregido)
- **Cyan:** `#06B6D4` (INCORRECTO - ya corregido)

---

## Rutas del Sistema

### Rutas Principales

```php
// Landing
Route::get('/', function () {
    return view('welcome-new');
})->name('home');

// Calculadoras
Route::get('/maritimo', CalculadoraMaritima::class)->name('calculadora.maritima');
Route::get('/aereo', CalculadoraAerea::class)->name('calculadora.aerea');
Route::get('/terrestre', CalculadoraTerrestre::class)->name('calculadora.terrestre');
Route::get('/impuestos', CalculadoraImpuestos::class)->name('calculadora.impuestos');

// Servicios Adicionales
Route::get('/importaciones-exportaciones', ...)->name('importaciones.exportaciones');
Route::get('/capacitaciones', ...)->name('capacitaciones');
Route::get('/logistica-transporte', ...)->name('logistica.transporte');
Route::get('/criptomonedas', ...)->name('criptomonedas');
Route::get('/ecommerce', ...)->name('ecommerce');
Route::get('/subastas', ...)->name('subastas');

// Página Nosotros
Route::get('/nosotros', ...)->name('nosotros');
```

---

## Layouts y Componentes

### Layout Principal (Livewire)

**Archivo:** `resources/views/layouts/app.blade.php`

- Incluye favicon de IA GROUPS
- Carga Vite assets (CSS/JS)
- Incluye estilos y scripts de Livewire

### Layout de Componentes

**Archivo:** `resources/views/components/layout.blade.php`

- Header con logo y navegación
- Enlaces a todas las calculadoras
- Título dinámico: "IA GROUPS"

### Layout de Calculadora

**Archivo:** `resources/views/components/calculadora-layout.blade.php`

- Contenedor de formularios
- Sección de mensajes de sesión
- Manejo de resultados y desglose

---

## Características Especiales

### 1. Sistema de Preguntas Interactivas

**Implementación:**

Todas las calculadoras incluyen una funcionalidad interactiva post-cálculo:

**Backend (Ejemplo en CalculadoraMaritima.php):**

```php
public function calcular()
{
    // Reset de estado interactivo
    $this->mostrarPregunta = false;
    $this->respuestaUsuario = null;
    
    // Lógica de cálculo...
    
    // Activar pregunta
    $this->mostrarPregunta = true;
}

public function responder($respuesta)
{
    $this->respuestaUsuario = $respuesta;
}
```

**Frontend (Blade):**

```blade
@if($mostrarPregunta && $resultado !== null)
    <div class="animate-pulse border-4 border-yellow-500/50">
        <!-- Pregunta con botones SÍ/NO -->
        <!-- Respuesta condicional con WhatsApp CTA -->
    </div>
@endif
```

### 2. Integración con WhatsApp

**URL Format:**

```
https://wa.me/59164700457?text=Hola,%20me%20interesa%20el%20precio%20de%20$[MONTO]%20para%20transporte%20[TIPO]
```

**Parámetros dinámicos:**
- Precio calculado
- Tipo de transporte
- Mensaje personalizado

### 3. Animaciones y Efectos

**Carrusel Hero:**
- Slideshow automático cada 3 segundos
- Efecto zoom en imágenes activas
- Transiciones de opacidad suaves

**Cards de Servicios:**
- Hover: scale(1.05)
- Sombra dinámica con color de marca
- Transición de borde
- Ícono con scale(1.1)

**Sección Interactiva:**
- Borde pulsante (animate-pulse)
- Botones con hover:scale-110
- Transiciones de color fluidas

---

## Pendientes y TODOs

### Backend Necesario

1. **Dashboard de Productos:**
   - Conectar sección izquierda con datos reales
   - Implementar lógica de precios dinámicos
   - Sistema de inventario o catálogo

2. **Base de Datos:**
   - Tabla de cotizaciones guardadas
   - Historial de usuarios
   - Gestión de servicios

3. **Autenticación:**
   - Sistema de login/registro (si es necesario)
   - Roles de usuario
   - Panel administrativo

4. **Páginas de Servicios:**
   - Desarrollar contenido completo para las 6 páginas de servicios
   - Implementar formularios de contacto específicos
   - Agregar información detallada de cada servicio

### Mejoras de UX/UI

1. **Responsive Design:**
   - Verificar comportamiento en tablets
   - Optimizar mobile menu
   - Ajustar grid de servicios en pantallas pequeñas

2. **Accesibilidad:**
   - Agregar atributos ARIA
   - Mejorar contraste de textos
   - Navegación por teclado

3. **Performance:**
   - Optimizar imágenes del carrusel
   - Lazy loading de secciones
   - Minimizar JS/CSS

---

## Guía de Desarrollo

### Convenciones de Código

**Nombres de Archivos:**
- Componentes Livewire: PascalCase (ej: `CalculadoraMaritima.php`)
- Vistas Blade: kebab-case (ej: `calculadora-maritima.blade.php`)
- Rutas: kebab-case (ej: `/importaciones-exportaciones`)

**Clases CSS:**
- Usar utilidades de Tailwind
- Evitar CSS personalizado cuando sea posible
- Mantener consistencia en spacing (px-4, py-6, etc.)

**Colores:**
- SIEMPRE usar yellow/amber/orange para marca
- Verde para confirmaciones
- Rojo para errores/cancelaciones
- Gris para texto secundario

### Comandos Útiles

**Desarrollo:**
```bash
# Iniciar servidor
php artisan serve

# Compilar assets
npm run dev

# Compilar para producción
npm run build
```

**Livewire:**
```bash
# Crear componente
php artisan make:livewire NombreComponente

# Limpiar cache
php artisan livewire:delete --all
```

**Base de Datos:**
```bash
# Ejecutar migraciones
php artisan migrate

# Rollback
php artisan migrate:rollback

# Seed
php artisan db:seed
```

---

## Estructura de Archivos Críticos

### welcome-new.blade.php

**Líneas importantes:**
- 1-60: Head y estilos personalizados
- 61-95: Header/Navbar
- 230-450: Dashboard de cotizaciones
- 464-750: Grid de servicios adicionales
- 794-890: Footer

### Componentes Livewire

**Métodos obligatorios:**
- `mount()`: Inicialización
- `calcular()`: Lógica principal
- `responder()`: Manejo de interacción
- `limpiar()`: Reset de formulario

**Propiedades obligatorias:**
- `$mostrarPregunta`
- `$respuestaUsuario`
- `$resultado`

---

## Testing

### Áreas a Testear

1. **Calculadoras:**
   - Validación de campos
   - Cálculos correctos
   - Flujo de interacción completo
   - Integración WhatsApp

2. **Navegación:**
   - Todos los enlaces funcionan
   - Rutas correctamente definidas
   - Redirects apropiados

3. **Responsive:**
   - Mobile (320px - 640px)
   - Tablet (641px - 1024px)
   - Desktop (1025px+)

4. **Cross-browser:**
   - Chrome
   - Firefox
   - Safari
   - Edge

---

## Notas de Implementación

### Cambios Recientes

1. **Corrección de colores:** Se reemplazaron todos los colores teal/cyan por yellow/amber/orange en las cards de servicios.

2. **Logo unificado:** Se implementó el logo de IA GROUPS en:
   - Navbar principal
   - Footer
   - Headers de calculadoras
   - Favicon (todas las páginas)

3. **Eliminación de líneas amarillas:** Se removieron los bordes del logo en footer para mantener diseño limpio.

4. **Corrección de separación visual:** Se eliminaron elementos `absolute` huérfanos que creaban líneas de separación entre secciones.

### Mejores Prácticas

1. **Componentes Livewire:**
   - Siempre resetear estado al inicio de `calcular()`
   - Usar wire:model.live para validación en tiempo real
   - Mantener lógica de negocio en el componente PHP

2. **Diseño:**
   - Mantener consistencia en padding/margin
   - Usar sistema de grid de Tailwind
   - Aplicar efectos hover de manera uniforme

3. **Performance:**
   - Minimizar re-renders de Livewire
   - Usar wire:key en listas
   - Optimizar consultas de base de datos

---

## Contacto y Soporte

**Empresa:** IA GROUPS  
**Email:** info@iagroups.com  
**Teléfono:** +591 64700457  
**Ubicación:** Tarija, Bolivia

---

## Control de Versiones

**Versión Actual:** 1.0.0  
**Última Actualización:** Diciembre 2025  
**Laravel:** 12.40.2  
**Livewire:** 3.7  
**TailwindCSS:** 4.0

---

## Conclusión

Este proyecto está construido con tecnologías modernas y siguiendo las mejores prácticas de desarrollo web. La arquitectura modular permite fácil escalabilidad y mantenimiento. Se recomienda seguir las guías de estilo establecidas y completar los TODOs pendientes para tener una aplicación completamente funcional.
