<?php
    include_once "conexion.php";
    include_once "funciones.php";

    if (!isset($_SESSION['logged']) || $_SESSION['logged'] !== true){
        ob_end_clean();
        header("location: index.php");
        exit;
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de seguimiento de usuarios</title>
    <link rel="shortcut icon" href="../src/imgs/favicon-32x32.png" type="image/png">
    <link rel="stylesheet" href="style/style.css">
    <!-- Panel Style -->
    <!-- <link rel="stylesheet" href="style/panel.css"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />


</head>
<body>
    <?php include "includes/nav.php" ?>
    <header>
        <h1>Ingreso de categorias y subcategorias</h1>
    </header>

    <div class="container">
        <!-- SECCIÓN: AGREGAR CATEGORÍAS -->
        <div class="seccion-categorias">
            <h2>Gestionar Categorías</h2>
            
            <div class="form-section">
                <h3>Agregar Nueva Categoría</h3>
                <form method="POST" id="form-categoria">
                    <div class="form-group">
                        <label for="nombre-categoria">Nombre de Categoría:</label>
                        <input type="text" id="nombre-categoria" name="nombre_categoria" placeholder="Ej: Fajas, Brassieres, etc." required>
                    </div>
                    
                    <div class="form-group">
                        <label for="descripcion-categoria">Descripción (Opcional):</label>
                        <textarea id="descripcion-categoria" name="descripcion_categoria" placeholder="Describe la categoría..." rows="3"></textarea>
                    </div>
                    
                    <button type="submit" name="agregar_categoria" class="btn-submit">
                        <i class="fa-solid fa-plus"></i> Agregar Categoría
                    </button>
                </form>
            </div>

            <div class="lista-section">
                <h3>Categorías Existentes</h3>
                <table class="tabla-categorias">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tbody-categorias">
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 20px;">Cargando categorías...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- SECCIÓN: AGREGAR SUBCATEGORÍAS -->
        <div class="seccion-subcategorias">
            <h2>Gestionar Subcategorías</h2>
            
            <div class="form-section">
                <h3>Agregar Nueva Subcategoría</h3>
                <form method="POST" id="form-subcategoria">
                    <div class="form-group">
                        <label for="categoria-padre">Categoría Padre:</label>
                        <select id="categoria-padre" name="categoria_padre" required>
                            <option value="">Selecciona una categoría...</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="nombre-subcategoria">Nombre de Subcategoría:</label>
                        <input type="text" id="nombre-subcategoria" name="nombre_subcategoria" placeholder="Ej: Fajas Reductoras, Push-up, etc." required>
                    </div>
                    
                    <div class="form-group">
                        <label for="descripcion-subcategoria">Descripción (Opcional):</label>
                        <textarea id="descripcion-subcategoria" name="descripcion_subcategoria" placeholder="Describe la subcategoría..." rows="3"></textarea>
                    </div>
                    
                    <button type="submit" name="agregar_subcategoria" class="btn-submit">
                        <i class="fa-solid fa-plus"></i> Agregar Subcategoría
                    </button>
                </form>
            </div>

            <div class="lista-section">
                <h3>Subcategorías Existentes</h3>
                <table class="tabla-subcategorias">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Categoría Padre</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tbody-subcategorias">
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 20px;">Cargando subcategorías...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <style>
        .container {
            display: flex;
            flex-direction: column;
            align-items: stretch;
            width: 90%;
            max-width: 1200px;
            margin: 20px auto;
            height: auto;
            gap: 40px;
        }

        .seccion-categorias,
        .seccion-subcategorias {
            background-color: white;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .seccion-categorias h2,
        .seccion-subcategorias h2 {
            color: var(--color-principal);
            margin-bottom: 20px;
            font-size: 24px;
            border-bottom: 3px solid var(--color-principal);
            padding-bottom: 10px;
        }

        .form-section {
            margin-bottom: 40px;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 6px;
            border-left: 4px solid var(--color-principal);
        }

        .form-section h3 {
            color: #333;
            margin-bottom: 15px;
            font-size: 18px;
        }

        .form-group {
            margin-bottom: 15px;
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            font-weight: 600;
            margin-bottom: 8px;
            color: #555;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-family: 'Rajdhani', sans-serif;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--color-principal);
            box-shadow: 0 0 5px rgba(150, 81, 84, 0.3);
        }

        .btn-submit {
            background-color: var(--color-principal);
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-submit:hover {
            background-color: #6b4145;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .lista-section {
            margin-top: 30px;
        }

        .lista-section h3 {
            color: #333;
            margin-bottom: 15px;
            font-size: 18px;
        }

        .tabla-categorias,
        .tabla-subcategorias {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .tabla-categorias thead,
        .tabla-subcategorias thead {
            background-color: var(--color-principal);
            color: white;
        }

        .tabla-categorias th,
        .tabla-subcategorias th {
            padding: 12px;
            text-align: left;
            font-weight: 600;
        }

        .tabla-categorias td,
        .tabla-subcategorias td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }

        .tabla-categorias tbody tr:hover,
        .tabla-subcategorias tbody tr:hover {
            background-color: #f5f5f5;
        }

        .btn-accion {
            padding: 8px 12px;
            margin-right: 5px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            transition: all 0.3s ease;
        }

        .btn-editar {
            background-color: #4CAF50;
            color: white;
        }

        .btn-editar:hover {
            background-color: #45a049;
        }

        .btn-eliminar {
            background-color: var(--color-alerta);
            color: white;
        }

        .btn-eliminar:hover {
            background-color: #e64545;
        }

        @media (max-width: 768px) {
            .container {
                width: 95%;
                padding: 10px;
            }

            .seccion-categorias,
            .seccion-subcategorias {
                padding: 15px;
            }

            .tabla-categorias,
            .tabla-subcategorias {
                font-size: 12px;
            }

            .tabla-categorias th,
            .tabla-subcategorias th,
            .tabla-categorias td,
            .tabla-subcategorias td {
                padding: 8px;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            cargarCategorias();
            cargarSubcategorias();

            // Cargar categorías en el select de subcategorías
            document.getElementById('categoria-padre').addEventListener('focus', cargarCategorias);
        });

        function cargarCategorias() {
            const select = document.getElementById('categoria-padre');
            const tbody = document.getElementById('tbody-categorias');

            // Hacer petición AJAX para obtener categorías
            fetch('api/obtener_categorias.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Llenar select
                        const currentValue = select.value;
                        select.innerHTML = '<option value="">Selecciona una categoría...</option>';
                        
                        data.categorias.forEach(cat => {
                            const option = document.createElement('option');
                            option.value = cat.nombre;
                            option.textContent = cat.nombre;
                            select.appendChild(option);
                        });
                        
                        select.value = currentValue;

                        // Llenar tabla
                        tbody.innerHTML = '';
                        data.categorias.forEach((cat, idx) => {
                            const row = `
                                <tr>
                                    <td>${idx + 1}</td>
                                    <td>${cat.nombre}</td>
                                    <td>${cat.descripcion || '-'}</td>
                                    <td>
                                        <button class="btn-accion btn-editar" onclick="editarCategoria('${cat.nombre}')">Editar</button>
                                        <button class="btn-accion btn-eliminar" onclick="eliminarCategoria('${cat.nombre}')">Eliminar</button>
                                    </td>
                                </tr>
                            `;
                            tbody.innerHTML += row;
                        });
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        function cargarSubcategorias() {
            const tbody = document.getElementById('tbody-subcategorias');

            // Hacer petición AJAX para obtener subcategorías
            fetch('api/obtener_subcategorias.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        tbody.innerHTML = '';
                        data.subcategorias.forEach((sub, idx) => {
                            const row = `
                                <tr>
                                    <td>${idx + 1}</td>
                                    <td>${sub.categoria || '-'}</td>
                                    <td>${sub.nombre}</td>
                                    <td>${sub.descripcion || '-'}</td>
                                    <td>
                                        <button class="btn-accion btn-editar" onclick="editarSubcategoria('${sub.nombre}')">Editar</button>
                                        <button class="btn-accion btn-eliminar" onclick="eliminarSubcategoria('${sub.nombre}')">Eliminar</button>
                                    </td>
                                </tr>
                            `;
                            tbody.innerHTML += row;
                        });
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        // Manejar formulario de categoría
        document.getElementById('form-categoria').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('api/agregar_categoria.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Categoría agregada exitosamente');
                    this.reset();
                    cargarCategorias();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        });

        // Manejar formulario de subcategoría
        document.getElementById('form-subcategoria').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('api/agregar_subcategoria.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Subcategoría agregada exitosamente');
                    this.reset();
                    cargarSubcategorias();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        });

        function editarCategoria(nombre) {
            alert('Funcionalidad de edición en desarrollo - Categoría: ' + nombre);
        }

        function eliminarCategoria(nombre) {
            if (confirm('¿Deseas eliminar la categoría: ' + nombre + '?')) {
                fetch('api/eliminar_categoria.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ nombre: nombre })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Categoría eliminada');
                        cargarCategorias();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        }

        function editarSubcategoria(nombre) {
            alert('Funcionalidad de edición en desarrollo - Subcategoría: ' + nombre);
        }

        function eliminarSubcategoria(nombre) {
            if (confirm('¿Deseas eliminar la subcategoría: ' + nombre + '?')) {
                fetch('api/eliminar_subcategoria.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ nombre: nombre })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Subcategoría eliminada');
                        cargarSubcategorias();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        }
    </script>

</body>
</html>
