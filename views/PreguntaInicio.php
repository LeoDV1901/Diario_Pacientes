<?php
session_start();

$respuesta = '';
$error = '';

// Procesa el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $respuesta = $_POST['tratamiento'] ?? '';

  if ($respuesta === '') {
    $error = 'Por favor, selecciona una opción';
  } elseif ($respuesta === 'Si') {
    header('Location: index.php?route=FechaDiarioPaciente');
    exit;
  } else {
    header('Location: index.php?route=EvaluacionTratamiento');
    exit;
  }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Instrucciones</title>
  <link rel="stylesheet" href="./views/css/login.css">
  <style>
    .radio-group {
      display: flex;
      flex-direction: column;
      gap: 10px;
      margin: 10px 0 20px 0;
    }
    .radio-group div {
      display: flex;
      align-items: center;
      gap: 10px;
    }
    label, p {
      color: white;
    }
  </style>
</head>
<body>
  <div class="container">
    <img src="/image001.png" alt="Logo Infinite" class="logo" />
    <h2>Instrucciones</h2>

    <p style="margin-bottom: 20px; text-align: justify;">
      Ingrese el tiempo de avance al momento de contestar este formulario.
      <br><br>
      <strong>¿Usted ya ha iniciado el tratamiento con FEMEDUAL®?</strong>
    </p>

    <?php if ($error): ?>
      <p style="color: red"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST">
      <label for="respuesta">Seleccione una opción:</label>
      <div class="radio-group">
        <div>
          <label for="si" style="min-width: 30px;">Sí</label>
          <input type="radio" id="si" name="tratamiento" value="Si" required />
        </div>
        <div>
          <label for="no" style="min-width: 30px;">No</label>
          <input type="radio" id="no" name="tratamiento" value="No" required />
        </div>
      </div>
      <button type="submit">Siguiente</button>
    </form>
  </div>
</body>
</html>