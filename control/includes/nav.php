<nav>
        <div class="nav-title">
            <h3>Panel de Control</h2>
            <p><b>Usuario: </b><?php echo htmlspecialchars($_SESSION['username']); ?></p>
        </div>

        <div class="nav-logo">
            <img src="../src/imgs/logo_panel.png" alt="Logo Regale Lenceria">
        </div>

        <div class="nav-menu">
            <ul>
                <li><a href="logout.php" title="Cerrar sesion"><i class="fa-solid fa-right-from-bracket"></i></a></li>
                <li><a href="panel.php" title="Panel principal"><i class="fa-solid fa-house"></i></a></li>
            </ul>
        </div>
</nav>