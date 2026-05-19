<?php
  $suscripcion_exito = false;

  if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['email'])) {
      $email = trim($_POST['email']);

      if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
          $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
          $fecha = date('Y-m-d H:i:s');

          if ($stmt = $mysqli->prepare(
              "INSERT INTO email_suscripcion (emails, fecha, navegador) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE fecha = VALUES(fecha), navegador = VALUES(navegador)"
          )) {
              $stmt->bind_param('sss', $email, $fecha, $user_agent);
              $stmt->execute();
              $stmt->close();
              $suscripcion_exito = true;
          }
      }
  }
?>
<!-- Footer -->
<div class="footer">
                <div class="footer-logo">
                    <img src="src/imgs/logo.png" alt="Logo Regale">
                    <p>En un mundo de constante cambio y desarrollo nuestro reto es brindar la mejor experiencia posible en la compra de productos, que van desde casual y despreocupada, hasta sofisticada. Manteniendo un diseño con estilo de lujo y altos estándares de belleza.</p>
                </div>

                <div class="footer-enlaces">
                    <h3>Enlaces</h3>
                    <ul>
                        <li><a href="#">Inicio</a></li>
                        <li><a href="tienda.php">Tienda</a></li>
                        <li><a href="">Contacto</a></li>
                        <li><a href="terminos.php">Terminos y condiciones</a></li>
                    </ul>
                </div>

                <div class="footer-suscripcion">
                <h3>¡Suscribete para mas beneficios!</h3>
                <form action="" method="post">
                    <input type="email" name="email" id="email" placeholder="Correo Electronico" required>
                    <button type="submit">Enviar</button>
                </form>

                <?php if ($suscripcion_exito): ?>
                    <script>
                        alert('Registro completado');
                    </script>
                <?php endif; ?>

                <div class="redes">
                    <h3>Descubre nuestras redes</h3>
                    <ul>
                        <li class="facebook"><a href="https://www.facebook.com/regale.lenceria/"><i class="fa-brands fa-square-facebook"></i></a></li>
                        <li><a class="instagram" href="https://www.instagram.com/regalelenceria/"><i class="fa-brands fa-instagram"></i></a></li>
                        <li><a class="tiktok" href="https://www.tiktok.com/@regalelenceria"><i class="fa-brands fa-tiktok"></i></a></li>
                        <li><a class="youtube" href="https://www.youtube.com/@regalelenceria"><i class="fa-brands fa-youtube"></i></a></li>
                    </ul>
                </div>

                <small style="color:#212121;font-family:monospace;">Development by DigitalCruz</small>
            </div>
        </div>