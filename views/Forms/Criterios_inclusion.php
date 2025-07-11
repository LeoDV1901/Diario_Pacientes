<?php
session_start();
$idPaciente = $_GET['idPaciente'] ?? null;

$preguntas = [
  'p1' => '1. Firma de la carta de Consentimiento Informado ff910d',
  'p2' => '2. Dolor lumbar con evolución de &lt; 6 semanas',
  'p3' => '3. Diagnóstico de Lumbalgia Mecánica aguda o subaguda SIN radiculopatía',
  'p4' => '4. Paciente de cualquier género, mayor de 18 años y menor de 65 años',
  'p5' => '5. Mujeres en edad fértil con método seguro de anticoncepción y/o prueba rápida de embarazo NEGATIVA',
  'p6' => '6. Intensidad de dolor entre 6 a 8 en la escala visual análoga en V0',
  'p7' => '7. Capacidad de llenado de los formatos de recolección de datos',
  'p8' => '8. Pacientes que al momento del enrolamiento no hayan recibido tratamiento o que hayan recibido tratamientos diferentes a los medicamentos de estudio sin tener respuesta satisfactoria',
];

$respuestas = [];
$comentarios = [];
$criterios = null;
$editId = null;

// Cargar datos existentes
if ($idPaciente) {
  $json = @file_get_contents("http://127.0.0.1:5000/form/criterios_inclusion/$idPaciente");
  $data = json_decode($json, true);
  $lista = isset($data['result']) ? $data['result'] : $data;

  if (is_array($lista) && count($lista) > 0) {
    $ultimo = end($lista);
    $criterios = $ultimo;
    $editId = $ultimo['id'];

    foreach (array_keys($preguntas) as $pid) {
      $respuestas[$pid] = $ultimo["pregunta" . substr($pid, 2)] ?? '';
      $comentarios[$pid] = $ultimo["comentario" . substr($pid, 2)] ?? '';
    }
  }
}

// Procesar envío
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  foreach (array_keys($preguntas) as $pid) {
    $respuestas[$pid] = $_POST[$pid] ?? '';
    $comentarios[$pid] = $_POST["c_$pid"] ?? '';
    if (!$respuestas[$pid]) {
      echo "<script>alert('Responda: {$preguntas[$pid]}');</script>";
      return;
    }
  }

  $payload = [
    'idPaciente' => $idPaciente,
    'respuestas' => []
  ];

  foreach ($preguntas as $pid => $texto) {
    $payload['respuestas'][] = [
      'id' => $pid,
      'respuesta' => $respuestas[$pid],
      'comentario' => $comentarios[$pid] ?? ''
    ];
  }

  $method = $editId ? 'PUT' : 'POST';
  $url = $editId 
    ? "http://127.0.0.1:5000/form/criterios_inclusion/$editId" 
    : "http://127.0.0.1:5000/form/criterios_inclusion";

  $options = [
    'http' => [
      'header' => "Content-Type: application/json\r\n",
      'method' => $method,
      'content' => json_encode($payload),
    ]
  ];

  $context = stream_context_create($options);
  $result = @file_get_contents($url, false, $context);

  echo $result 
    ? "<script>alert('Criterios de inclusión " . ($editId ? "actualizados" : "guardados") . " correctamente');location.reload();</script>"
    : "<script>alert('Error en el servidor');</script>";
}

// Eliminar
if (isset($_POST['eliminar']) && $editId) {
  $ctx = stream_context_create(['http' => ['method' => 'DELETE']]);
  $del = @file_get_contents("http://127.0.0.1:5000/form/criterios_inclusion/$editId", false, $ctx);
  echo $del 
    ? "<script>alert('Registro eliminado');location.reload();</script>" 
    : "<script>alert('Error al eliminar');</script>";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Criterios de Inclusión</title>
  <link rel="stylesheet" href="./views/css/Formulario_Pacientes.css">
</head>
<body>
  <?php if (!$idPaciente): ?>
    <div class="fondo">
      <div class="container glass">
        <h2 style="color: white;">No se proporcionó un ID de paciente.</h2>
      </div>
    </div>
  <?php else: ?>
    <div class="fondo">
      <div class="container glass">
        <img src="./views/css/image001.png" alt="Logo Infinite" class="logo" />
        <h2 style="color: #ff5733;">Criterios de Inclusión</h2>

        <form method="POST">
          <?php foreach ($preguntas as $pid => $texto): 
            $r = $respuestas[$pid] ?? '';
            $coment = $comentarios[$pid] ?? '';
          ?>
            <div class="pregunta">
              <span><?= $texto ?></span>
              <div class="radio-group">
                <?php foreach (['Sí', 'No'] as $option): ?>
                  <label class="radio-option">
                    <input 
                      type="radio" 
                      name="<?= $pid ?>" 
                      value="<?= $option ?>" 
                      <?= $r === $option ? 'checked' : '' ?>
                      required 
                    />
                    <span class="custom-radio"><?= $option ?></span>
                  </label>
                <?php endforeach; ?>
              </div>
              <?php if ($r === 'No'): ?>
                <input 
                  type="text" 
                  name="c_<?= $pid ?>" 
                  class="comentario-input" 
                  placeholder="Ingrese una observación..." 
                  value="<?= htmlspecialchars($coment) ?>"
                />
              <?php endif; ?>
            </div>
          <?php endforeach; ?>

          <button type="submit"><?= $editId ? 'Actualizar' : 'Enviar' ?></button>
          <?php if ($editId): ?>
            <button type="submit" name="eliminar">Eliminar registro</button>
          <?php endif; ?>
          <a href="index.php?route=CriteriosI&idPaciente=<?= $idPaciente ?>">
            <button type="button">Siguiente</button>
          </a>
        </form>
      </div>
    </div>
  <?php endif; ?>
</body>
</html>