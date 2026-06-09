# Regale Lencería - E-commerce Project

Este proyecto está diseñado para funcionar como un E-commerce de lencería y ropa interior. Cuenta con un backend robusto en PHP Vanilla para control total sin dependencias innecesarias de frameworks, y un frontend moderno reconstruido bajo principios de **Modular Clean Architecture** utilizando React, TypeScript y Vite.

---

## Arquitectura del Frontend (Modular Clean Architecture)

El código del frontend (`src/`) ha sido estructurado siguiendo buenas prácticas para garantizar alta cohesión, bajo acoplamiento y escalabilidad del proyecto en producción.

El proyecto está dividido en **Módulos funcionales**, y cada módulo se organiza en capas según la Clean Architecture:

```
src/
├── core/                       # Núcleo compartido y utilidades transversales
│   ├── components/ui/          # Componentes base de presentación (Shadcn UI)
│   ├── styles/                 # Estilos globales y tokens de diseño
│   └── utils/                  # Formateadores (moneda, etc.) y helpers genéricos
├── modules/
│   ├── catalog/                # Módulo del Catálogo (Productos, Categorías, Filtros, Búsqueda)
│   │   ├── domain/             # Lógica de negocio (Entidades, Interfaces de Repositorios, Use Cases)
│   │   ├── infrastructure/     # Adaptadores externos (Modelos de API, Mappers, Repositorios HTTP)
│   │   └── presentation/       # UI (Páginas, Componentes de catálogo y Hooks React)
│   ├── cart/                   # Módulo del Carrito de Compras
│   │   ├── domain/             # Entidad CartItem
│   │   └── presentation/       # Contexto de estado (CartContext con localStorage) y CartPage
│   └── shared/                 # Elementos y layout globales del sitio
│       └── presentation/       # Header, Footer, Hero, WhatsAppFAB, HomePage, etc.
└── presentation/
    └── App.tsx                 # Enrutador principal de vistas y layouts
```

###  Responsabilidades de las Capas

1. **Domain Layer (Dominio)**: Contiene las reglas de negocio más puras e independientes de frameworks. Define las entidades de datos (`Product`, `Category`, `CartItem`), las firmas de interfaces de repositorio (`ProductRepository`) y los casos de uso (`GetProducts`, `GetProductDetail`, `GetFeaturedProducts`).
2. **Infrastructure Layer (Infraestructura)**: Se encarga de las comunicaciones externas. Contiene el mapeo de objetos de la base de datos (`ProductMapper` para adaptar `ApiProduct` al dominio de la app) y la implementación concreta de comunicación HTTP (`HttpProductRepository`).
3. **Presentation Layer (Presentación)**: React Hooks (`useProducts`, `useProductDetail`), Contextos de estado global (como `CartContext` con persistencia en `localStorage`) y los componentes visuales o páginas (`CategoryPage`, `ProductDetailPage`, `CartPage`).

---

### Configuración en Desarrollo (Vite Proxy)
Para local, Vite redirige las peticiones a la API local (`http://localhost:8083`) automáticamente a través de la propiedad `proxy` en `vite.config.ts`:
```typescript
server: {
  proxy: {
    '/api': {
      target: 'http://localhost:8083',
      changeOrigin: true,
      secure: false,
    }
  }
}
```

---

##  Requisitos de Software

* Servidor Apache o Nginx (con soporte de proxy inverso en producción)
* PHP 8.x
* MySQL + phpmyadmin
* Node.js (v18 o superior para compilar el frontend)

---

##  Estructura de la Base de Datos

```
regalele_base
    |
    |- banners 
    |    |- foto
    |    |- id
    |    |- url
    |
    |- categorias
    |    |- descripcion
    |    |- id
    |    |- nombre
    |    |- parent_id
    |    
    |- colores
    |    |- codigo_color
    |    |- color_hex
    |    |- descripcion
    |    |- estado
    |    |- id
    |    |- id_prenda
    |    |- img_estampado
    |    |- tipo_color
    | 
    |- control
    |    |- id
    |    |- password
    |    |- privilegios
    |    |- usuario
    |
    |- fotos
    |    |- color
    |    |- estado
    |    |- id
    |    |- id_prenda 
    |    |- ruta
    |    
    |- inventario
         |- beneficio
         |- categoria
         |- codigo
         |- compresion
         |- descripcion_corta
         |- descripcion_larga
         |- estado
         |- genero
         |- id
         |- marca_prenda
         |- nombre_prenda
         |- precio_base
         |- sistema
         |- subcategoria 
         |- tipoFaja
 
     |- stock
         |- codigo_color
         |- estado
         |- id
         |- id_prenda 
         |- stock
         |- talla
 
     |- sys_registro
         |- actividad
         |- fecha
         |- id
         |- nivel
         |- user
 
     |- tallas
         |- estado 
         |- id
         |- id_prenda 
         |- talla
```

###  Seguridad de Acceso
Las contraseñas de la tabla `control` están encriptadas con **Bcrypt Hash** para mitigar cualquier riesgo de filtrado de credenciales.

---

##  Despliegue y Comandos de Consola

### Desarrollo Local
Para iniciar el servidor de desarrollo de Vite con recarga rápida en el puerto `5173`:
```bash
npm run dev
```

### Compilación para Producción
Para generar el bundle optimizado y minificado de producción dentro de la carpeta `/dist`:
```bash
npm run build
```
Los archivos estáticos de `/dist` resultantes son los que deben copiarse al directorio de publicación del servidor Nginx/Apache.
