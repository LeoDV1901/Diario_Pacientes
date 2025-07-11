<?php
session_start();

// Variables para respuestas
$respuestas = [
  'malOlor' => '',
  'flujoVaginal' => '',
  'comezon' => '',
  'ardorVaginal' => '',
  'dolorRelaciones' => ''
];

$error = '';

// Manejo del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  foreach ($respuestas as $key => $_) {
    $respuestas[$key] = $_POST[$key] ?? '';
  }

  $incompleto = in_array('', $respuestas);

  if ($incompleto) {
    $error = 'Por favor, completa todas las opciones';
  } else {
    // Aquí puedes guardar datos en sesión o base si lo deseas
    header('Location: index.php?route=Resumen');
    exit;
  }
}

// Componente para preguntas tipo radio (generado por función)
function renderPreguntaRadio($nombre, $texto, $opciones, $valor) {
  $html = "<div style='margin-bottom: 30px;'>
    <label for='$nombre' style='color: white; display: block; margin-bottom: 10px; font-weight: 500;'>$texto:</label>
    <div class='radio-group'>";
  
  foreach ($opciones as $op) {
    $selectedClass = $valor === $op ? 'selected' : '';
    $checked = $valor === $op ? 'checked' : '';
    $html .= "<label class='radio-option $selectedClass'>
        <input type='radio' name='$nombre' value='$op' $checked required />
        $op
      </label>";
  }

  $html .= "</div></div>";
  return $html;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Evaluación de Tratamiento</title>
  <link rel="stylesheet" href="./views/css/login.css">
</head>
<body>
  <div class="container">
    <img src="/image001.png" alt="Logo Infinite" class="logo" />
    <h2>Evaluación de Tratamiento</h2>
    <p style="color: white; margin-bottom: 20px; text-align: justify;">
      Su información es estrictamente confidencial, la siguiente información es solo para fines de investigación,
      toda la información no se compartirá externamente.
    </p>

    <?php if ($error): ?>
      <p style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST">
      <h3>Instrucciones: Selecciona la opción que mejor describa tus síntomas</h3>

      <?= renderPreguntaRadio('malOlor', 'Mal Olor', ['Sin mal olor', 'Leve', 'Moderado', 'Intenso', 'Muy intenso'], $respuestas['malOlor']) ?>
      <?= renderPreguntaRadio('flujoVaginal', 'Cantidad de flujo vaginal', ['Normal', 'Poco', 'Moderado', 'Abundante', 'Muy abundante'], $respuestas['flujoVaginal']) ?>
      <?= renderPreguntaRadio('comezon', 'Comezón', ['No tengo', 'Raramente', 'Algunas veces', 'Frecuentemente', 'Siempre'], $respuestas['comezon']) ?>
      <?= renderPreguntaRadio('ardorVaginal', 'Ardor vaginal', ['No tengo', 'Raramente', 'Algunas veces', 'Frecuentemente', 'Siempre'], $respuestas['ardorVaginal']) ?>
      <?= renderPreguntaRadio('dolorRelaciones', 'Dolor durante relaciones sexuales', ['No aplica', 'Sin dolor', 'Raramente', 'Algunas veces', 'Frecuentemente'], $respuestas['dolorRelaciones']) ?>

      <button type="submit">Siguiente</button>
    </form>
  </div>
</body>
</html>