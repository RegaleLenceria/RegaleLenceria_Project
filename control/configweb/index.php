<?php
    include_once "../conexion.php";
    include "../funciones.php";

    if (!isset($_SESSION['logged']) || $_SESSION['logged'] != true){
        if ($_SESSION['privilegio'] !=0){
            ob_end_clean();
            header("location: ../panel.php");
            exit();
        }
    }

    if (isset($_GET['borrar'])){
        $stmt = $mysqli->prepare("DELETE FROM control WHERE id = ?");
        $stmt->bind_param("i", $_GET['borrar']);
        $stmt->execute();

        ob_end_clean();
        header("location: index.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Regale Lenceria - Configuracion Web</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=delete" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="src/style.css">
    <script src="src/main.js"></script>
</head>
<body>
    <div id="modalPreview">
        <div class="imagen">
            <img class="imgModalPreview" src="" alt="Imagen preview">
        </div>
    </div>


            <div class="sidebar">
                <div class="sidebar-logo">
                    <img src="../../src/imgs/logo_panel.png" alt="Logo regale">
                    <h3>Configuracion Web</h3>
                </div>
                <ul>
                    <li><a href="#" onclick="mostrarSeccion('usuarios')"><i class="fa fa-cogs" aria-hidden="true"></i>
Configuracion Pagina</a></li>
                    <li><a href="#" onclick="mostrarSeccion('admin')"><i class="fa fa-users" aria-hidden="true"></i>
 Usuarios</a></li>
                    <li><a href="#" onclick="mostrarSeccion('ceo')">CEO</a></li>
                </ul>

                <div class="btn-atras">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i>
                </div>

                <script>
                    const btn_atras = document.querySelector('.btn-atras');
                    btn_atras.addEventListener('click', function(){
                        window.location.href = "../panel.php";    
                    });
                </script>
            </div>

            <div class="content">
                <!-- Sección usuarios -->
                <section id="usuarios" class="seccion">
                    <div class="container-configpagina">
                        <h2>Subida de banners de sitio Web</h2>
                        
                        <div class="subida-banners">
                            <!-- Banner Preview -->
                            <div class="banner-preview">
                                <img src="" id="previewBanner">
                            </div>

                            <form action="" method="post" enctype="multipart/form-data">
                                <label for="fimagen"><b>Seleccion la imagen a subir</b></label>
                                <input type="file" accept="image/png, image/jpg, image/jpeg" id="fimagen" name="imagenPreview">
                                <script>
                                    const imgSrc = document.getElementById('previewBanner');
                                    const fileInput = document.getElementById('fimagen');

                                    fileInput.addEventListener('change', () => {
                                        const files = fileInput.files;
                                        Array.from(files).forEach(file => {
                                            const reader = new FileReader();
                                            reader.onload=(event) => {
                                                imgSrc.src = event.target.result;
                                            }

                                            reader.readAsDataURL(file);
                                        });
                                    });
                                    
                                </script>
                                
                                <label for="furl"><b>Ingresa la URL a donde redireccionar al usuario</b></label>
                                <input type="url" id="furl" class="urlImagen" name="url" placeholder="https://regalelenceria.com">

                                <button>SUBIR IMAGEN</button>
                            </form>

<?php
    if ($_SERVER['REQUEST_METHOD'] == "POST"){
        $url = $_POST['url'];

        $img = $_FILES['imagenPreview'];
        $imgTemp = $_FILES['imagenPreview']['tmp_name'];
        $imgName = basename($_FILES['imagenPreview']['name']);
        $imgSize = $_FILES['imagenPreview']['size'];
        

        $maxFile = 6 * 1024 * 1024; // 6MB
        $uploadDir = "/src/imgs/carusel/";

        $imgExt = strtolower(pathinfo($imgName, PATHINFO_EXTENSION));
        $nombreAleatorio = md5(uniqid('img_', true)).'.'.$imgExt;
        $fullDir = $uploadDir.$nombreAleatorio;

        if (!is_dir($uploadDir)){
            mkdir($uploadDir, 0777, true);
        }
        
        if ($imgSize > $maxFile){
            echo "<p><b>El fichero es muy pesado, puedes subir imagen</b></p>";
        }

        if (move_uploaded_file($imgTemp, "../..".$fullDir)){
            $stmt = $mysqli->prepare("INSERT INTO banners (foto, url) VALUES (?, ?)");
            $stmt->bind_param("ss", $fullDir, $url);
            $stmt->execute();
            $stmt->close();

            echo "<p>Imagen subida subida con exito</p>";
        }
    }
?>
                        </div>

                        <div class="tableImages">
                            <h2>Banners subidos</h2>
                            <table>
                                <thead>
                                    <tr>
                                        <th>Banner</th>
                                        <th>URL</th>
                                        <th>OPCIONES</th>
                                    </tr>
                                </thead>
                                <tbody>
<?php
    $stmt = $mysqli->query("SELECT * FROM banners");
    while ($row = $stmt->fetch_assoc()){
        echo "<tr>";
        echo '<td><img onclick="imgModal(this.src)" id="imgBanner" src="../..'.$row['foto'].'"></td>';
        echo '<td><p>'.$row['url'].'</p></td>';
        echo '<td><a style="color:red;" href="index.php?delImage='.$row['id'].'"><span class="material-symbols-outlined">
delete
</span></p></td>';
        
        echo "</tr>";
    }
?>
                                </tbody>
                            </table>
                        </div>

                    <script>
                        const cajaPreview = document.getElementById("modalPreview");
                        const imgPreviewModal = document.querySelector(".imgModalPreview");
                        
                        function imgModal(img){
                            cajaPreview.style.display = "flex";
                            imgPreviewModal.src = img;
                        }

                        imgPreviewModal.addEventListener('click', function(){
                            cajaPreview.style.display = "none"; 
                        });
                        
                    </script>
                    </div>
                </section>

                <!-- Sección admin -->
                <section id="admin" class="seccion" style="display:none;">
                    <div class="admin-container">
                        <!-- CREAR NUEVO USUARIO -->
                        <div class="container-nuevoUsuario">
                            <h2><i class="fa-solid fa-user-plus"></i> Crear nuevo usuario</h2>
                            
                            <div id="mensaje-estado" class="mensaje-estado" style="display:none;"></div>
                            
                            <form id="form-nuevo-usuario" method="post" action="registrar.php" class="form-usuario">
                                <div class="form-group">
                                    <label for="username">Usuario:</label>
                                    <input type="text" id="username" name="username" placeholder="Ingresa nombre de usuario" required>
                                    <small class="validacion-texto" id="validacion-usuario"></small>
                                </div>
                                
                                <div class="form-group">
                                    <label for="pass">Contraseña:</label>
                                    <div class="input-password">
                                        <input type="password" id="pass" name="pass" placeholder="Mínimo 6 caracteres" required>
                                        <span class="toggle-password" onclick="togglePassword('pass')">
                                            <i class="fa-solid fa-eye"></i>
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="pass2">Confirmar Contraseña:</label>
                                    <div class="input-password">
                                        <input type="password" id="pass2" name="pass2" placeholder="Repite la contraseña" required>
                                        <span class="toggle-password" onclick="togglePassword('pass2')">
                                            <i class="fa-solid fa-eye"></i>
                                        </span>
                                    </div>
                                    <small class="validacion-texto" id="validacion-pass"></small>
                                </div>
                                
                                <div class="form-group">
                                    <label for="permisos">Nivel de Permisos:</label>
                                    <select id="permisos" name="permisos" required>
                                        <option value="">Selecciona un nivel...</option>
                                        <option value="0">Administrador (Acceso Total)</option>
                                        <option value="1">Almacén (Gestión de Inventario)</option>
                                    </select>
                                </div>
                                
                                <button type="submit" class="btn-submit">
                                    <i class="fa-solid fa-plus"></i> Crear Usuario
                                </button>
                            </form>
                        </div>

                        <!-- LISTA DE USUARIOS -->
                        <div class="container-listaUsuarios">
                            <div class="lista-header">
                                <h2><i class="fa-solid fa-users"></i> Usuarios del Sistema</h2>
                                <div class="search-box">
                                    <input type="text" id="buscar-usuario" placeholder="Buscar usuario..." class="search-input">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </div>
                            </div>
                            
                            <table id="tabla-usuarios" class="tabla-usuarios">
                                <thead>
                                    <tr>
                                        <th>Usuario</th>
                                        <th>Permisos</th>
                                        <th>Descripción</th>
                                        <th>Opciones</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody-usuarios">
<?php
    $sql = "SELECT * FROM control";
    $resultado = $mysqli->query($sql);
    while($raw = $resultado->fetch_assoc()):
        $permiso_texto = ($raw['privilegios'] == 0) ? 'Administrador' : 'Almacén';
        $permiso_desc = ($raw['privilegios'] == 0) ? 'Acceso total al sistema' : 'Gestión de inventario';
?>
                                    <tr class="fila-usuario" data-usuario="<?= strtolower($raw['usuario']) ?>">
                                        <td class="usuario-cell">
                                            <i class="fa-solid fa-user-circle"></i>
                                            <span><?= htmlspecialchars($raw['usuario']) ?></span>
                                        </td>
                                        <td>
                                            <span class="badge badge-<?= ($raw['privilegios'] == 0) ? 'admin' : 'almacen' ?>">
                                                <?= $permiso_texto ?>
                                            </span>
                                        </td>
                                        <td><?= $permiso_desc ?></td>
                                        <td class="acciones-cell">
                                            <button class="btn-accion btn-editar" onclick="abrirEditarUsuario(<?= $raw['id'] ?>, '<?= htmlspecialchars($raw['usuario']) ?>', <?= $raw['privilegios'] ?>)" title="Editar">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </button>
                                            <button class="btn-accion btn-eliminar" onclick="confirmarEliminar(<?= $raw['id'] ?>, '<?= htmlspecialchars($raw['usuario']) ?>')" title="Eliminar">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
<?php endwhile; ?>
                                </tbody>
                            </table>
                            <div id="sin-resultados" class="sin-resultados" style="display:none;">
                                <p><i class="fa-solid fa-circle-info"></i> No se encontraron usuarios</p>
                            </div>
                        </div>
                    </div>

                    <!-- MODAL EDITAR USUARIO -->
                    <div id="modal-editar" class="modal-overlay" style="display:none;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3>Editar Usuario</h3>
                                <button class="btn-cerrar" onclick="cerrarEditarUsuario()">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </div>
                            
                            <form id="form-editar-usuario" class="form-usuario">
                                <input type="hidden" id="usuario-id" name="usuario_id">
                                
                                <div class="form-group">
                                    <label for="username-editar">Usuario:</label>
                                    <input type="text" id="username-editar" readonly class="input-readonly">
                                </div>
                                
                                <div class="form-group">
                                    <label for="permisos-editar">Nivel de Permisos:</label>
                                    <select id="permisos-editar" name="permisos" required>
                                        <option value="0">Administrador (Acceso Total)</option>
                                        <option value="1">Almacén (Gestión de Inventario)</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label>
                                        <input type="checkbox" id="cambiar-password"> Cambiar contraseña
                                    </label>
                                </div>
                                
                                <div id="password-fields" style="display:none;">
                                    <div class="form-group">
                                        <label for="pass-editar">Nueva Contraseña:</label>
                                        <div class="input-password">
                                            <input type="password" id="pass-editar" name="pass">
                                            <span class="toggle-password" onclick="togglePassword('pass-editar')">
                                                <i class="fa-solid fa-eye"></i>
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="pass2-editar">Confirmar Contraseña:</label>
                                        <div class="input-password">
                                            <input type="password" id="pass2-editar" name="pass2">
                                            <span class="toggle-password" onclick="togglePassword('pass2-editar')">
                                                <i class="fa-solid fa-eye"></i>
                                            </span>
                                        </div>
                                        <small class="validacion-texto" id="validacion-pass-editar"></small>
                                    </div>
                                </div>
                                
                                <div class="modal-actions">
                                    <button type="submit" class="btn-submit">Guardar Cambios</button>
                                    <button type="button" class="btn-cancelar" onclick="cerrarEditarUsuario()">Cancelar</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <style>
                        .admin-container {
                            display: grid;
                            grid-template-columns: 1fr 1.5fr;
                            gap: 30px;
                            padding: 20px;
                        }

                        .container-nuevoUsuario,
                        .container-listaUsuarios {
                            background: white;
                            padding: 25px;
                            border-radius: 10px;
                            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                        }

                        .container-nuevoUsuario h2,
                        .container-listaUsuarios h2 {
                            color: var(--color-principal);
                            margin-bottom: 20px;
                            display: flex;
                            align-items: center;
                            gap: 10px;
                        }

                        .mensaje-estado {
                            padding: 15px;
                            border-radius: 6px;
                            margin-bottom: 15px;
                            display: flex;
                            align-items: center;
                            gap: 10px;
                        }

                        .mensaje-estado.exito {
                            background-color: #d4edda;
                            color: #155724;
                            border: 1px solid #c3e6cb;
                        }

                        .mensaje-estado.error {
                            background-color: #f8d7da;
                            color: #721c24;
                            border: 1px solid #f5c6cb;
                        }

                        .form-usuario {
                            display: flex;
                            flex-direction: column;
                            gap: 15px;
                        }

                        .form-group {
                            display: flex;
                            flex-direction: column;
                        }

                        .form-group label {
                            font-weight: 600;
                            margin-bottom: 8px;
                            color: #333;
                        }

                        .form-group input,
                        .form-group select {
                            padding: 12px;
                            border: 1px solid #ddd;
                            border-radius: 6px;
                            font-size: 14px;
                            transition: all 0.3s ease;
                            font-family: inherit;
                        }

                        .form-group input:focus,
                        .form-group select:focus {
                            outline: none;
                            border-color: var(--color-principal);
                            box-shadow: 0 0 5px rgba(150, 81, 84, 0.3);
                        }

                        .input-password {
                            position: relative;
                            display: flex;
                            align-items: center;
                        }

                        .input-password input {
                            width: 100%;
                        }

                        .toggle-password {
                            position: absolute;
                            right: 12px;
                            cursor: pointer;
                            color: #666;
                            font-size: 18px;
                        }

                        .toggle-password:hover {
                            color: var(--color-principal);
                        }

                        .validacion-texto {
                            font-size: 12px;
                            color: #dc3545;
                            margin-top: 5px;
                            display: none;
                        }

                        .validacion-texto.visible {
                            display: block;
                        }

                        .btn-submit {
                            background-color: var(--color-principal);
                            color: white;
                            padding: 12px 20px;
                            border: none;
                            border-radius: 6px;
                            cursor: pointer;
                            font-weight: 600;
                            transition: all 0.3s ease;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            gap: 8px;
                        }

                        .btn-submit:hover {
                            background-color: #6b4145;
                            transform: translateY(-2px);
                            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
                        }

                        .lista-header {
                            display: flex;
                            justify-content: space-between;
                            align-items: center;
                            margin-bottom: 20px;
                            gap: 15px;
                        }

                        .search-box {
                            position: relative;
                            flex: 1;
                            max-width: 300px;
                        }

                        .search-input {
                            width: 100%;
                            padding: 10px 35px 10px 12px;
                            border: 1px solid #ddd;
                            border-radius: 6px;
                            font-size: 14px;
                        }

                        .search-input:focus {
                            outline: none;
                            border-color: var(--color-principal);
                            box-shadow: 0 0 5px rgba(150, 81, 84, 0.3);
                        }

                        .search-box i {
                            position: absolute;
                            right: 12px;
                            top: 50%;
                            transform: translateY(-50%);
                            color: #999;
                            pointer-events: none;
                        }

                        .tabla-usuarios {
                            width: 100%;
                            border-collapse: collapse;
                            margin-top: 10px;
                        }

                        .tabla-usuarios thead {
                            background-color: var(--color-principal);
                            color: white;
                        }

                        .tabla-usuarios th {
                            padding: 15px;
                            text-align: left;
                            font-weight: 600;
                        }

                        .tabla-usuarios td {
                            padding: 15px;
                            border-bottom: 1px solid #eee;
                        }

                        .tabla-usuarios tbody tr:hover {
                            background-color: #f9f9f9;
                        }

                        .usuario-cell {
                            display: flex;
                            align-items: center;
                            gap: 10px;
                        }

                        .usuario-cell i {
                            color: var(--color-principal);
                            font-size: 20px;
                        }

                        .badge {
                            padding: 6px 12px;
                            border-radius: 20px;
                            font-size: 12px;
                            font-weight: 600;
                            display: inline-block;
                        }

                        .badge-admin {
                            background-color: #e3f2fd;
                            color: #1976d2;
                        }

                        .badge-almacen {
                            background-color: #f3e5f5;
                            color: #7b1fa2;
                        }

                        .acciones-cell {
                            display: flex;
                            gap: 10px;
                        }

                        .btn-accion {
                            padding: 8px 12px;
                            border: none;
                            border-radius: 6px;
                            cursor: pointer;
                            font-size: 16px;
                            transition: all 0.3s ease;
                            display: flex;
                            align-items: center;
                        }

                        .btn-editar {
                            background-color: #4CAF50;
                            color: white;
                        }

                        .btn-editar:hover {
                            background-color: #45a049;
                            transform: scale(1.1);
                        }

                        .btn-eliminar {
                            background-color: var(--color-alerta);
                            color: white;
                        }

                        .btn-eliminar:hover {
                            background-color: #e64545;
                            transform: scale(1.1);
                        }

                        .sin-resultados {
                            text-align: center;
                            padding: 40px;
                            color: #999;
                        }

                        .sin-resultados i {
                            font-size: 48px;
                            margin-bottom: 15px;
                            display: block;
                        }

                        /* MODAL */
                        .modal-overlay {
                            position: fixed;
                            top: 0;
                            left: 0;
                            width: 100%;
                            height: 100%;
                            background-color: rgba(0, 0, 0, 0.5);
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            z-index: 1000;
                        }

                        .modal-content {
                            background: white;
                            border-radius: 10px;
                            padding: 30px;
                            width: 90%;
                            max-width: 500px;
                            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
                            animation: slideUp 0.3s ease;
                        }

                        @keyframes slideUp {
                            from {
                                opacity: 0;
                                transform: translateY(50px);
                            }
                            to {
                                opacity: 1;
                                transform: translateY(0);
                            }
                        }

                        .modal-header {
                            display: flex;
                            justify-content: space-between;
                            align-items: center;
                            margin-bottom: 25px;
                            padding-bottom: 15px;
                            border-bottom: 2px solid var(--color-principal);
                        }

                        .modal-header h3 {
                            color: var(--color-principal);
                            margin: 0;
                        }

                        .btn-cerrar {
                            background: none;
                            border: none;
                            font-size: 24px;
                            cursor: pointer;
                            color: #666;
                            padding: 0;
                            transition: all 0.3s ease;
                        }

                        .btn-cerrar:hover {
                            color: var(--color-principal);
                            transform: rotate(90deg);
                        }

                        .modal-actions {
                            display: flex;
                            gap: 10px;
                            margin-top: 25px;
                        }

                        .modal-actions button {
                            flex: 1;
                            padding: 12px;
                            border: none;
                            border-radius: 6px;
                            cursor: pointer;
                            font-weight: 600;
                            transition: all 0.3s ease;
                        }

                        .btn-cancelar {
                            background-color: #e0e0e0;
                            color: #333;
                        }

                        .btn-cancelar:hover {
                            background-color: #d0d0d0;
                        }

                        .input-readonly {
                            background-color: #f5f5f5 !important;
                            color: #666 !important;
                            cursor: not-allowed !important;
                        }

                        @media (max-width: 1024px) {
                            .admin-container {
                                grid-template-columns: 1fr;
                            }
                        }

                        @media (max-width: 768px) {
                            .lista-header {
                                flex-direction: column;
                                align-items: stretch;
                            }

                            .search-box {
                                max-width: 100%;
                            }

                            .tabla-usuarios th,
                            .tabla-usuarios td {
                                padding: 10px;
                                font-size: 13px;
                            }

                            .acciones-cell {
                                flex-direction: column;
                            }
                        }
                    </style>

                    <script>
                        // TOGGLE PASSWORD
                        function togglePassword(inputId) {
                            const input = document.getElementById(inputId);
                            const isPassword = input.type === 'password';
                            input.type = isPassword ? 'text' : 'password';
                        }

                        // VALIDAR CONTRASEÑAS
                        function validarContraseñas(pass1Id, pass2Id, msgId) {
                            const pass1 = document.getElementById(pass1Id);
                            const pass2 = document.getElementById(pass2Id);
                            const msg = document.getElementById(msgId);

                            if (pass2.value && pass1.value !== pass2.value) {
                                msg.textContent = 'Las contraseñas no coinciden';
                                msg.classList.add('visible');
                                return false;
                            } else {
                                msg.classList.remove('visible');
                                return true;
                            }
                        }

                        // CREAR USUARIO
                        document.getElementById('form-nuevo-usuario').addEventListener('submit', function(e) {
                            e.preventDefault();

                            if (!validarContraseñas('pass', 'pass2', 'validacion-pass')) {
                                return;
                            }

                            const formData = new FormData(this);
                            
                            fetch('registrar.php', {
                                method: 'POST',
                                body: formData
                            })
                            .then(response => response.text())
                            .then(data => {
                                mostrarMensaje('Usuario creado exitosamente', 'exito');
                                this.reset();
                                setTimeout(() => location.reload(), 2000);
                            })
                            .catch(error => {
                                mostrarMensaje('Error al crear usuario: ' + error.message, 'error');
                            });
                        });

                        // BUSCAR USUARIO
                        document.getElementById('buscar-usuario').addEventListener('keyup', function() {
                            const busqueda = this.value.toLowerCase();
                            const filas = document.querySelectorAll('.fila-usuario');
                            let encontrado = false;

                            filas.forEach(fila => {
                                if (fila.dataset.usuario.includes(busqueda)) {
                                    fila.style.display = '';
                                    encontrado = true;
                                } else {
                                    fila.style.display = 'none';
                                }
                            });

                            document.getElementById('sin-resultados').style.display = encontrado ? 'none' : 'block';
                        });

                        // EDITAR USUARIO
                        function abrirEditarUsuario(id, usuario, permisos) {
                            document.getElementById('usuario-id').value = id;
                            document.getElementById('username-editar').value = usuario;
                            document.getElementById('permisos-editar').value = permisos;
                            document.getElementById('modal-editar').style.display = 'flex';
                        }

                        function cerrarEditarUsuario() {
                            document.getElementById('modal-editar').style.display = 'none';
                            document.getElementById('form-editar-usuario').reset();
                            document.getElementById('password-fields').style.display = 'none';
                        }

                        document.getElementById('cambiar-password').addEventListener('change', function() {
                            document.getElementById('password-fields').style.display = this.checked ? 'block' : 'none';
                        });

                        document.getElementById('form-editar-usuario').addEventListener('submit', function(e) {
                            e.preventDefault();

                            const cambiarPass = document.getElementById('cambiar-password').checked;
                            if (cambiarPass && !validarContraseñas('pass-editar', 'pass2-editar', 'validacion-pass-editar')) {
                                return;
                            }

                            const formData = new FormData(this);
                            
                            fetch('editar_usuario.php', {
                                method: 'POST',
                                body: formData
                            })
                            .then(response => response.text())
                            .then(data => {
                                mostrarMensaje('Usuario actualizado exitosamente', 'exito');
                                cerrarEditarUsuario();
                                setTimeout(() => location.reload(), 2000);
                            })
                            .catch(error => {
                                mostrarMensaje('Error: ' + error.message, 'error');
                            });
                        });

                        // CONFIRMAR ELIMINAR
                        function confirmarEliminar(id, usuario) {
                            if (confirm(`¿Deseas eliminar el usuario "${usuario}"? Esta acción no se puede deshacer.`)) {
                                fetch('index.php?borrar=' + id)
                                    .then(() => {
                                        mostrarMensaje('Usuario eliminado exitosamente', 'exito');
                                        setTimeout(() => location.reload(), 2000);
                                    })
                                    .catch(error => {
                                        mostrarMensaje('Error: ' + error.message, 'error');
                                    });
                            }
                        }

                        // MOSTRAR MENSAJES
                        function mostrarMensaje(texto, tipo) {
                            const div = document.getElementById('mensaje-estado');
                            const icono = tipo === 'exito' ? '<i class="fa-solid fa-check-circle"></i>' : '<i class="fa-solid fa-circle-exclamation"></i>';
                            div.innerHTML = icono + ' ' + texto;
                            div.className = 'mensaje-estado ' + tipo;
                            div.style.display = 'flex';

                            setTimeout(() => {
                                div.style.display = 'none';
                            }, 5000);
                        }

                        // Validación en tiempo real
                        document.getElementById('pass').addEventListener('keyup', function() {
                            validarContraseñas('pass', 'pass2', 'validacion-pass');
                        });

                        document.getElementById('pass2').addEventListener('keyup', function() {
                            validarContraseñas('pass', 'pass2', 'validacion-pass');
                        });

                        document.getElementById('pass-editar').addEventListener('keyup', function() {
                            validarContraseñas('pass-editar', 'pass2-editar', 'validacion-pass-editar');
                        });

                        document.getElementById('pass2-editar').addEventListener('keyup', function() {
                            validarContraseñas('pass-editar', 'pass2-editar', 'validacion-pass-editar');
                        });

                        // Cerrar modal con tecla ESC
                        document.addEventListener('keydown', function(e) {
                            if (e.key === 'Escape') {
                                cerrarEditarUsuario();
                            }
                        });
                    </script>
                </section>
            </div>

            </div>
</body>
</html>