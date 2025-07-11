<?php
session_start();
$idPaciente = $_GET['idPaciente'] ?? null;

$preguntas = [
  'p1' => '¿El paciente aceptó su participación en el estudio?',
  'p2' => 'Hora de obtención del consentimiento informado (Formato 24hrs):',
  'p3' => '¿Tiene el paciente alguna condición preexistente relevante?',
  'p4' => '¿El paciente recibió toda la información necesaria?',
];

$respuestas = [];
$comentarios = [];
$horaError = '';
$editId = null;

// Cargar datos existentes
if ($idPaciente) {
  $json = @file_get_contents("http://127.0.0.1:5000/form/consentimiento/$idPaciente");
  $data = json_decode($json, true);
  $lista = $data['result'] ?? $data;
  if (is_array($lista) && count($lista)) {
    $ultimo = end($lista);
    $editId = $ultimo['id'];
    $respuestas = [
      'p1' => $ultimo['pregunta1'] ?? '',
      'p3' => $ultimo['pregunta3'] ?? '',
      'p4' => $ultimo['pregunta4'] ?? ''
    ];
    $comentarios = [
      'p1' => $ultimo['comentario1'] ?? '',
      'p2' => $ultimo['pregunta2'] ?? '',
      'p3' => $ultimo['comentario3'] ?? '',
      'p4' => $ultimo['comentario4'] ?? ''
    ];
  }
}

// Enviar datos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  foreach ($preguntas as $pid => $texto) {
    if ($pid !== 'p2' && empty($_POST[$pid])) {
      echo "<script>alert('Responda: $texto');</script>";
      return;
    }
  }

  $hora = $_POST['p2'] ?? '';
  if (!preg_match('/^([01]?[0-9]|2[0-3]):([0-5][0-9])$/', $hora)) {
    $horaError = 'Ingrese una hora válida (ej: 13:45)';
  } else {
    $payload = [
      'idPaciente' => $idPaciente,
      'respuestas' => [
        ['id' => 'p1', 'respuesta' => $_POST['p1'], 'comentario' => $_POST['c_p1'] ?? ''],
        ['id' => 'p2', 'comentario' => $_POST['p2']],
        ['id' => 'p3', 'respuesta' => $_POST['p3'], 'comentario' => $_POST['c_p3'] ?? ''],
        ['id' => 'p4', 'respuesta' => $_POST['p4'], 'comentario' => $_POST['c_p4'] ?? ''],
      ]
    ];
    $method = $editId ? 'PUT' : 'POST';
    $url = $editId 
      ? "http://127.0.0.1:5000/form/consentimiento/$editId" 
      : "http://127.0.0.1:5000/form/consentimiento";

    $options = [
      'http' => [
        'header' => "Content-Type: application/json\r\n",
        'method' => $method,
        'content' => json_encode($payload),
      ]
    ];
    $context = stream_context_create($options);
    $res = @file_get_contents($url, false, $context);

    echo $res
      ? "<script>alert('Consentimiento " . ($editId ? "actualizado" : "guardado") . "');location.reload();</script>"
      : "<script>alert('Error al guardar datos');</script>";
  }
}

// Eliminar registro
if (isset($_POST['eliminar']) && $editId) {
  $ctx = stream_context_create(['http' => ['method' => 'DELETE']]);
  $res = @file_get_contents("http://127.0.0.1:5000/form/consentimiento/$editId", false, $ctx);
  echo $res
    ? "<script>alert('Registro eliminado');location.reload();</script>"
    : "<script>alert('Error al eliminar');</script>";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Consentimiento Informado</title>
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
        <h2 style="color: #ff5733;">Consentimiento Informado</h2>

        <form method="POST">
          <?php foreach ($preguntas as $pid => $texto): 
            $resp = $respuestas[$pid] ?? '';
            $coment = $comentarios[$pid] ?? '';
            $esNo = $resp === 'No';
          ?>
            <div class="pregunta">
              <span><?= $texto ?></span>
              <?php if (in_array($pid, ['p1', 'p3', 'p4'])): ?>
                <div class="radio-group">
                  <?php foreach (['Sí', 'No'] as $op): ?>
                    <label class="radio-option">
                      <input 
                        type="radio" 
                        name="<?= $pid ?>" 
                        value="<?= $op ?>" 
                        <?= $resp === $op ? 'checked' : '' ?> 
                        required 
                      />
                      <span class="custom-radio"><?= $op ?></span>
                    </label>
                  <?php endforeach; ?>
                </div>
              <?php elseif ($pid === 'p2'): ?>
                <input 
                  type="time" 
                  name="<?= $pid ?>" 
                  value="<?= htmlspecialchars($coment) ?>" 
                  class="comentario-input <?= $horaError ? 'error' : '' ?>" 
                  required 
                />
                <?php if ($horaError): ?>
                  <span style="color: red; font-size: 12px;"><?= $horaError ?></span>
                <?php endif; ?>
              <?php endif; ?>

              <?php if ($esNo): ?>
                <input 
                  type="text" 
                  name="c_<?= $pid ?>" 
                  class="comentario-input" 
                  placeholder="Ingrese una observación..." 
                  value="<?= htmlspecialchars($comentarios[$pid] ?? '') ?>" 
                />
              <?php endif; ?>
            </div>
          <?php endforeach; ?>

          <button type="submit"><?= $editId ? 'Actualizar' : 'Enviar' ?></button>
          <?php if ($editId): ?>
            <button type="submit" name="eliminar">Eliminar registro</button>
          <?php endif; ?>
        </form>
      </div>
    </div>
  <?php endif; ?>
</body>
</html>