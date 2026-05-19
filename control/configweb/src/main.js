function mostrarSeccion(seccion) {
  // Ocultar todas las secciones
  document.querySelectorAll('.seccion').forEach(
    sec => sec.style.display = 'none'
  );
  
  // Mostrar la seleccionada
  document.getElementById(seccion).style.display = 'flex';
}
