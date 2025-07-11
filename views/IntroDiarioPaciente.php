<?php
// Si se envía el formulario, redirige a la siguiente vista
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  header('Location: index.php?route=AvisodePrivacidad');
  exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Diario del Paciente</title>
  <link rel="stylesheet" href="./views/css/login.css">
</head>
<body>
  <div class="container">
    <img src="/image001.png" alt="Logo Infinite" class="logo" />
    <h2>Diario del Paciente</h2>

    <p style="color: white; margin-bottom: 20px; text-align: justify;">
      Bienvenida al Proyecto de Experiencia Clínica en Pacientes Mexicanas con Infecciones Vaginales tratadas con <strong>FEMEDUAL®</strong>.<br /><br />
      Como participante necesitamos recopilar tu experiencia desde tu primera consulta y continuar a las 24, 48, 72 y 96 horas posteriores.<br /><br />
      Si tiene dudas, comunícate al teléfono: <strong>(55) 5080-3620</strong> o <strong>(55) 4071-8008</strong>.
    </p>

    <form method="POST">
      <button type="submit">Siguiente</button>
    </form>
  </div>
</body>
</html>