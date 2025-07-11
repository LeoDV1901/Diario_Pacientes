<?php
session_start();

$id = '';
$error = '';

// Maneja el envío del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id = $_POST['idInput'] ?? '';

  if (empty($id)) {
    $error = 'Por favor, ingresa tu ID';
  } else {
    // Podrías validar el formato del ID aquí si deseas
    $_SESSION['idPaciente'] = $id;
    header('Location: index.php?route=PreguntaInicio');
    exit;
  }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Instrucciones Diario</title>
  <link rel="stylesheet" href="./views/css/login.css">
  <style>
    ul { color: white; text-align: justify; margin: 10px 0 20px 20px; }
    label, p { color: white; text-align: justify; }
    input[type="text"] {
      padding: 10px;
      margin: 10px 0;
      width: 100%;
    }
  </style>
</head>
<body>
  <div class="container">
    <img src="/image001.png" alt="Logo Infinite" class="logo" />
    <h2>Instrucciones para ingresar a tu diario</h2>

    <p>
      Por favor ingresa tu número de identificación (ID). Este se compone de:
    </p>
    <ul>
      <li>2 Números de referencia de tu médico</li>
      <li>3 Números correspondientes a tu número de paciente</li>
      <li>Las iniciales de tus apellidos y primer nombre en el siguiente orden: Apellido paterno + Apellido materno + Primer Nombre.</li>
    </ul>

    <p>
      <strong>Ejemplo:</strong><br />
      Paciente: María Guadalupe Sánchez López <br />
      Número de Referencia del Médico: 30 <br />
      Número de Paciente: 120 <br />
      ID: 30120SLM
    </p>

    <p>
      <strong>NOTA IMPORTANTE:</strong> El nombre proporcionado se utilizará exclusivamente con el propósito de ejemplificar el proceso de generación de ID en un contexto educativo o demostrativo. El objetivo principal es ilustrar cómo se pueden crear identificadores únicos y no está destinado a representar a ninguna persona, entidad o producto real.
    </p>

    <?php if ($error): ?>
      <p style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST">
      <label for="idInput">Escribe tu ID:</label>
      <input 
        type="text"
        id="idInput"
        name="idInput"
        value="<?= htmlspecialchars($id) ?>"
        required
        placeholder="Ejemplo: 30120SLM"
      />
      <button type="submit">Siguiente</button>
    </form>
  </div>
</body>
</html>