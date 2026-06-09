# Guía de Escalabilidad: Popup Dinámico ("No te vayas sin ver más")

El popup de intención de salida (`ExitIntentModal.tsx`) está actualmente activo y funciona con textos e interacciones definidos de manera estática. Para que este popup sea **escalable, dinámico y administrable en tiempo real desde un dashboard** en el futuro, se deben seguir los siguientes pasos de implementación.

---

## 1. Diseño de la Base de Datos

Se debe crear una tabla dedicada a gestionar las campañas del popup para poder modificar el contenido, botones, enlaces e incluso programarlo por fechas.

### Estructura SQL Sugerida
```sql
CREATE TABLE `configuracion_popups` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `titulo` VARCHAR(255) NOT NULL,
  `descripcion` TEXT NOT NULL,
  `texto_boton` VARCHAR(100) NOT NULL DEFAULT 'Consultar Catálogo',
  `enlace_boton` VARCHAR(255) NOT NULL DEFAULT '#',
  `activo` TINYINT(1) NOT NULL DEFAULT 1,
  `tiempo_espera_movil` INT NOT NULL DEFAULT 30000, -- Tiempo en milisegundos para móvil
  `fecha_inicio` DATETIME NULL,                    -- Programación opcional
  `fecha_fin` DATETIME NULL,                       -- Programación opcional
  `actualizado_en` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
```

---

## 2. Implementación del Backend (API PHP)

Se debe crear un archivo `api/popup.php` que retorne la configuración activa del popup en formato JSON.

### Código Sugerido (`api/popup.php`)
```php
<?php
require_once __DIR__ . '/config.php';
setCORSHeaders();

$db = getDB();

// Consulta para obtener el primer popup activo (y opcionalmente dentro del rango de fecha)
$sql = "SELECT titulo, descripcion, texto_boton, enlace_boton, activo, tiempo_espera_movil 
        FROM configuracion_popups 
        WHERE activo = 1 
          AND (fecha_inicio IS NULL OR fecha_inicio <= NOW())
          AND (fecha_fin IS NULL OR fecha_fin >= NOW())
        ORDER BY actualizado_en DESC 
        LIMIT 1";

$result = $db->query($sql);
$popupData = $result->fetch_assoc();

if (!$popupData) {
    // Si no hay ninguno activo, devolvemos un estado inactivo
    echo json_encode(['activo' => false]);
    exit;
}

// Retornamos los datos estructurados en formato JSON
echo json_encode([
    'activo' => true,
    'titulo' => $popupData['titulo'],
    'descripcion' => $popupData['descripcion'],
    'textoBoton' => $popupData['texto_boton'],
    'enlaceBoton' => $popupData['enlace_boton'],
    'tiempoEsperaMovil' => (int)$popupData['tiempo_espera_movil']
]);
```

---

## 3. Integración en el Frontend (`ExitIntentModal.tsx`)

Modificaremos el componente para que consulte a la API al montarse, guarde los datos dinámicos en el estado y use los valores de la base de datos si el popup está activo.

### Modificaciones Sugeridas en el Componente
```tsx
import { motion, AnimatePresence } from 'motion/react';
import { X } from 'lucide-react';
import { useEffect, useState } from 'react';

interface PopupConfig {
  activo: boolean;
  titulo: string;
  descripcion: string;
  textoBoton: string;
  enlaceBoton: string;
  tiempoEsperaMovil: number;
}

export function ExitIntentModal() {
  const [isOpen, setIsOpen] = useState(false);
  const [hasShown, setHasShown] = useState(false);
  const [config, setConfig] = useState<PopupConfig | null>(null);

  // 1. Obtener la configuración dinámica de la BD
  useEffect(() => {
    fetch('/api/popup.php')
      .then(res => res.json())
      .then((data: PopupConfig) => {
        if (data.activo) {
          setConfig(data);
        }
      })
      .catch(err => {
        console.error("Error cargando configuración del popup:", err);
        // Fallback local opcional en caso de que falle la API
      });
  }, []);

  // 2. Controladores de eventos basados en la configuración de la BD
  useEffect(() => {
    if (!config) return; // Si la API dice que no está activo, no agregamos los event listeners

    const handleMouseLeave = (e: MouseEvent) => {
      if (e.clientY <= 0 && !hasShown && window.innerWidth >= 768) {
        setIsOpen(true);
        setHasShown(true);
      }
    };

    const handleTouchEnd = () => {
      if (!hasShown && window.innerWidth < 768) {
        // Usa el tiempo de espera dinámico configurado en la BD
        setTimeout(() => {
          if (!hasShown) {
            setIsOpen(true);
            setHasShown(true);
          }
        }, config.tiempoEsperaMovil || 30000);
      }
    };

    document.addEventListener('mouseleave', handleMouseLeave);
    window.addEventListener('touchend', handleTouchEnd, { once: true });

    return () => {
      document.removeEventListener('mouseleave', handleMouseLeave);
      window.removeEventListener('touchend', handleTouchEnd);
    };
  }, [hasShown, config]);

  // Si no se ha cargado la config o el popup está inactivo, no renderizamos nada
  if (!config) return null;

  return (
    <AnimatePresence>
      {isOpen && (
        <>
          {/* Fondo traslúcido */}
          <motion.div
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            exit={{ opacity: 0 }}
            className="fixed inset-0 bg-black/60 z-50 backdrop-blur-sm"
            onClick={() => setIsOpen(false)}
          />

          {/* Modal del Popup */}
          <motion.div
            initial={{ scale: 0.8, opacity: 0 }}
            animate={{ scale: 1, opacity: 1 }}
            exit={{ scale: 0.8, opacity: 0 }}
            className="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-50 w-[90%] max-w-md"
          >
            <div className="bg-background rounded-3xl shadow-2xl p-8 relative">
              <button
                onClick={() => setIsOpen(false)}
                className="absolute top-4 right-4 p-2 hover:bg-muted rounded-full transition-all"
                aria-label="Cerrar"
              >
                <X className="w-5 h-5" />
              </button>

              <div className="text-center">
                {/* Título dinámico */}
                <h2 className="text-3xl mb-4">{config.titulo}</h2>
                {/* Descripción dinámica */}
                <p className="text-muted-foreground mb-8">{config.descripcion}</p>
                
                {/* Botón dinámico con enlace personalizado */}
                <a href={config.enlaceBoton} className="block w-full">
                  <motion.button
                    whileHover={{ scale: 1.05 }}
                    whileTap={{ scale: 0.95 }}
                    onClick={() => setIsOpen(false)}
                    className="bg-primary text-primary-foreground px-8 py-3 rounded-full w-full transition-all hover:shadow-lg"
                  >
                    {config.textoBoton}
                  </motion.button>
                </a>
              </div>
            </div>
          </motion.div>
        </>
      )}
    </AnimatePresence>
  );
}
```

---

## 4. Gestión mediante Dashboard

Una vez integrada la API y el componente de React, cualquier cambio realizado en la tabla `configuracion_popups` se reflejará inmediatamente en el sitio web sin necesidad de tocar el código ni volver a subir el frontend.

### Opciones de Administración:
1. **Administración Básica (Existente):** Puedes utilizar **phpMyAdmin** (accediendo en `http://localhost:8082` en tu entorno local) para editar directamente la fila de `configuracion_popups`.
2. **Dashboard a medida:** En una fase posterior, se puede añadir una vista sencilla en tu panel de administración (desarrollada en PHP o React) con un formulario que haga un `POST`/`PUT` a un endpoint del backend para actualizar las columnas `titulo`, `descripcion`, `texto_boton`, `enlace_boton` y `activo` de forma amigable e intuitiva para cualquier usuario.
