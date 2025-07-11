<?php
session_start();

$fecha = '';
$hora = '';
$error = '';

// Procesa el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $fecha = $_POST['fecha'] ?? '';
  $hora = $_POST['hora'] ?? '';

  if (empty($fecha) || empty($hora)) {
    $error = 'Por favor, completa todos los campos';
  } else {
    // Puedes guardar los valores en sesión si quieres usar en otra vista
    $_SESSION['fecha_aplicacion'] = $fecha;
    $_SESSION['hora_aplicacion'] = $hora;

    // Redirección a Confirmación
    header('Location: index.php?route=Confirmacion');
    exit;
  }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Diario de Paciente</title>
  <link rel="stylesheet" href="./views/css/login.css">
</head>
<body>
  <div class="container">
    <img src="/image001.png" alt="Logo Infinite" class="logo" />
    <h2>DIARIO DE PACIENTE</h2>
    <p style="color: white; margin-bottom: 20px; text-align: justify;">
      Por favor, ingresa la fecha y el horario de la última aplicación del medicamento en formato de 24 horas.
    </p>

    <?php if ($error): ?>
      <p style="color: red"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST">
      <label for="fecha" style="color: white;">Fecha de la última aplicación:</label>
      <input 
        type="date"
        id="fecha"
        name="fecha"
        value="<?= htmlspecialchars($fecha) ?>"
        required
        style="padding: 10px; margin: 10px 0; width: 100%;"
      />

      <label for="hora" style="color: white;">Horario de la última aplicación (24 horas):</label>
      <input 
        type="time"
        id="hora"
        name="hora"
        value="<?= htmlspecialchars($hora) ?>"
        required
        style="padding: 10px; margin: 10px 0; width: 100%;"
      />

      <button type="submit">Siguiente</button>
    </form>
  </div>
</body>
</html>