        document.addEventListener('DOMContentLoaded', () => {
            const carrusel = document.querySelector('.carrusel');
            const productos = document.querySelectorAll('.producto');
            const btnIzquierda = document.querySelector('.btn-izquierda');
            const btnDerecha = document.querySelector('.btn-derecha');
            const indicadores = document.querySelectorAll('.indicador');
            
            let indiceActual = 0;
            let intervalo;
            const tiempoTransicion = 9000; // 9 segundos
            
            // Función para mover el carrusel
            function moverCarrusel(indice) {
                // Aseguramos que el índice esté dentro de los límites
                if (indice < 0) {
                    indice = productos.length - 1;
                } else if (indice >= productos.length) {
                    indice = 0;
                }
                
                // Movemos el carrusel
                carrusel.style.transform = `translateX(-${indice * 100}%)`;
                
                // Actualizamos indicadores
                indicadores.forEach((indicador, i) => {
                    if (i === indice) {
                        indicador.classList.add('activo');
                    } else {
                        indicador.classList.remove('activo');
                    }
                });
                
                indiceActual = indice;
            }
            
            // Función para iniciar el movimiento automático
            function iniciarAutoMovimiento() {
                intervalo = setInterval(() => {
                    moverCarrusel(indiceActual + 1);
                }, tiempoTransicion);
            }
            
            // Función para detener el movimiento automático
            function detenerAutoMovimiento() {
                clearInterval(intervalo);
            }
            
            // Event listeners para los botones
            btnDerecha.addEventListener('click', () => {
                detenerAutoMovimiento();
                moverCarrusel(indiceActual + 1);
                iniciarAutoMovimiento();
            });
            
            btnIzquierda.addEventListener('click', () => {
                detenerAutoMovimiento();
                moverCarrusel(indiceActual - 1);
                iniciarAutoMovimiento();
            });
            
            // Event listeners para los indicadores
            indicadores.forEach((indicador, indice) => {
                indicador.addEventListener('click', () => {
                    detenerAutoMovimiento();
                    moverCarrusel(indice);
                    iniciarAutoMovimiento();
                });
            });
            
            // Pausar el carrusel cuando el ratón está sobre él
            const carruselContainer = document.querySelector('.carrusel-container');
            carruselContainer.addEventListener('mouseenter', detenerAutoMovimiento);
            carruselContainer.addEventListener('mouseleave', iniciarAutoMovimiento);
            
            // Iniciar el movimiento automático al cargar la página
            iniciarAutoMovimiento();
        });