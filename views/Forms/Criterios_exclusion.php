<?php
session_start();
$idPaciente = $_GET['idPaciente'] ?? null;

// Preguntas definidas
$preguntas = [
  'p1' => '1. Diagnóstico de Lumbalgia Mecánica aguda o subaguda CON radiculopatía',
  'p2' => '2. Intensidad de dolor igual o mayor a 8 en la escala visual análoga en V0',
  'p3' => '3. Presencia de síntomas de cauda equina',
  'p4' => '4. Enfermedad sistémica (cáncer, infecciones graves, VIH-SIDA, fiebre persistente, epilepsia)',
  'p5' => '5. Patología neurológica sistémica',
  'p6' => '6. Hernia discal o coxartrosis',
  'p7' => '7. Historia de trauma de columna lumbar',
  'p8' => '8. Pérdida de peso inexplicada',
  'p9' => '9. No estar en tratamiento con AINE',
  'p10' => '10. Estar bajo tratamiento derivado de la presencia de trastornos psiquiátricos o de personalidad',
  'p11' => '11. Pacientes con HAS o DM NO CONTROLADA',
  'p12' => '12. Paciente con antecedente de infarto agudo al miocardio',
  'p13' => '13. En caso de presentar sobrepeso u obesidad, estar tomando medicamentos para reducción de peso',
  'p14' => '14. Alteraciones renales o hepáticas',
  'p15' => '15. Mujeres embarazadas o lactando',
  'p16' => '16. ERGE, úlcera gástrica o duodenal y/o sangrados de tubo digestivo',
  'p17' => '17. Alergia conocida a cualquier AINE o sulfonamidas',
  'p18' => '18. Sacralización',
  'p19' => '19. Alteraciones reumáticas',
  'p20' => '20. Pacientes con antecedentes de abuso de alcohol o drogas',
];

// Inicializa estados
$respuestas = [];
$comentarios = [];
$criteriosExistentes = null;
$editId = null;

// Carga datos existentes
if ($idPaciente) {
  $json = @file_get_contents("http://127.0.0.1:5000/form/criterios_exclusion/$idPaciente");
  if ($json) {
    $data = json_decode($json, true);
    $lista = isset($data['result']) ? $data['result'] : $data;
    if (is_array($lista) && count($lista) > 0) {
      $ultimo = end($lista);
      $criteriosExistentes = $ultimo;
      $editId = $ultimo['id'];

      foreach ($preguntas as $pid => $_) {
        $respuestas[$pid] = $ultimo["pregunta" . substr($pid, 2)] ?? '';
        $comentarios[$pid] = $ultimo["comentario" . substr($pid, 2)] ?? '';
      }
    }
  }
}

// Guardar o actualizar criterios
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  foreach ($preguntas as $pid => $_) {
    $respuestas[$pid] = $_POST[$pid] ?? '';
    $comentarios[$pid] = $_POST["c_$pid"] ?? '';
  }

  $faltan = array_filter($respuestas, fn($r) => !$r);
  if (count($faltan) > 0) {
    echo "<script>alert('Por favor responde todas las preguntas.');</script>";
  } elseif (array_filter($respuestas, fn($r, $k) => $r === 'Sí' && !trim($comentarios[$k]), ARRAY_FILTER_USE_BOTH)) {
    echo "<script>alert('Por favor ingresa una observación en todas las respuestas \"Sí\".');</script>";
  } else {
    $respuestaArray = [];
    foreach ($preguntas as $pid => $_) {
      $respuestaArray[] = [
        'id' => $pid,
        'respuesta' => $respuestas[$pid]
      ];
    }

    $payload = json_encode([
      'idPaciente' => $idPaciente,
      'respuestas' => $respuestaArray,
      'comentarios' => $comentarios
    ]);

    $method = $criteriosExistentes ? 'PUT' : 'POST';
    $url = $criteriosExistentes ? "http://127.0.0.1:5000/form/criterios_exclusion/$editId" : "http://127.0.0.1:5000/form/criterios_exclusion";

    $context = stream_context_create([
      'http' => [
        'header' => "Content-Type: application/json\r\n",
        'method' => $method,
        'content' => $payload,
      ]
    ]);

    $response = @file_get_contents($url, false, $context);
    echo $response ? "<script>alert('Criterios guardados con éxito');location.href='index.php?route=CriteriosI&idPaciente=$idPaciente';</script>"
                   : "<script>alert('Error al guardar los criterios');</script>";
  }
}

// Eliminar criterios
if (isset($_POST['eliminar']) && $criteriosExistentes && $editId) {
  $ctx = stream_context_create(['http' => ['method' => 'DELETE']]);
  $response = @file_get_contents("http://127.0.0.1:5000/form/criterios_exclusion/$editId", false, $ctx);
  echo $response ? "<script>alert('Criterios eliminados con éxito');location.href='index.php?route=CriteriosI&idPaciente=$idPaciente';</script>"
                 : "<script>alert('Error al eliminar criterios');</script>";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Evolución de Criterios de Exclusión</title>
  <link rel="stylesheet" href="./views/css/Formulario_Pacientes.css">
</head>
<body>
  <div class="fondo">
    <div class="container glass">
      <img src="./views/css/image001.png" alt="Logo Infinite" class="logo" />
      <h2 style="color: #ff5733;">Evolución de Criterios de Exclusión</h2>

      <form method="POST">
        <?php foreach ($preguntas as $pid => $texto): 
          $r = $respuestas[$pid] ?? '';
          $esSi = $r === 'Sí';
        ?>
        <div class="pregunta" style="border: <?= $esSi ? '2px solid red' : '1px solid transparent' ?>; border-radius:8px; padding:10px; margin-bottom:15px;">
          <span><?= $texto ?></span>
          <div class="radio-group">
            <label class="radio-option">
              <input type="radio" name="<?= $pid ?>" value="Sí" <?= $r === 'Sí' ? 'checked' : '' ?> required /> Sí
            </label>
            <label class="radio-option">
              <input type="radio" name="<?= $pid ?>" value="No" <?= $r === 'No' ? 'checked' : '' ?> required /> No
            </label>
          </div>
          <?php if ($esSi): ?>
          <div class="comentario">
            <textarea name="c_<?= $pid ?>" placeholder="Comentario sobre esta respuesta..." style="color:black"><?= htmlspecialchars($comentarios[$pid] ?? '') ?></textarea>
          </div>
          <?php endif; ?>
        </div>
        <?php endforeach; ?>

        <div class="actions">
          <button type="submit" class="btn btn-primary">Guardar</button>
          <?php if ($criteriosExistentes): ?>
            <button type="submit" name="eliminar" class="btn btn-danger">Eliminar</button>
          <?php endif; ?>
        </div>
      </form>
    </div>
  </div>
</body>
</html>