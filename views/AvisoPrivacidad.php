<?php
// Redirecciona si se presiona el botón
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['aceptar'])) {
    header('Location: index.php?route=InstruccionesDiario');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Aviso de Privacidad</title>
  <link rel="stylesheet" href="./views/css/login.css">
  <style>
    p {
      color: white;
      margin-bottom: 20px;
      text-align: justify;
    }
    a {
      color: lightblue;
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="container">
    <img src="/image001.png" alt="Logo Infinite" class="logo" />
    <h2>Aviso de Privacidad</h2>
    <p>
      El presente <strong>Aviso de Privacidad</strong> tiene como objetivo informarle sobre el tratamiento que se les dará a sus datos personales cuando sean recabados, utilizados, almacenados y/o transferidos por <strong>INFINITE</strong>.
      <br /><br />
      Puede acceder al mismo a través del siguiente enlace:
      <br />
      <a 
        href="http://weareinfinite.mx/aviso-de-privacidad/" 
        target="_blank" 
        rel="noopener noreferrer"
      >
        http://weareinfinite.mx/aviso-de-privacidad/
      </a>
    </p>

    <form method="POST">
      <button type="submit" name="aceptar">Aceptar</button>
    </form>
  </div>
</body>
</html>