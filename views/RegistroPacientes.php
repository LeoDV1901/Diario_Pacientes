<?php
session_start();

$mensaje = '';
$mensajeTipo = ''; // 'exito' o 'error'

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $data = [
    'idPaciente' => $_POST['idPaciente'] ?? '',
    'iniciales' => $_POST['iniciales'] ?? '',
    'fechaNacimiento' => $_POST['fechaNacimiento'] ?? '',
    'sexo' => $_POST['sexo'] ?? '',
    'fechaReclutamiento' => $_POST['fechaReclutamiento'] ?? '',
  ];

  $json = json_encode($data);

  $options = [
    'http' => [
      'header' => "Content-Type: application/json\r\n",
      'method' => 'POST',
      'content' => $json,
    ]
  ];

  $context = stream_context_create($options);
  $response = @file_get_contents('http://localhost:5000/paciente/create', false, $context);

  if ($response) {
    $result = json_decode($response, true);
    $mensaje = '¡Paciente registrado exitosamente!';
    $mensajeTipo = 'exito';
  } else {
    $mensaje = 'No se pudo registrar el paciente. Verifique los datos o intente más tarde.';
    $mensajeTipo = 'error';
  }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Añadir Sujeto</title>
  <link rel="stylesheet" href="./views/css/Formulario_Pacientes.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
  <div class="container">
    <h2>AÑADIR SUJETO</h2>

    <form method="POST">
      <input type="text" name="idPaciente" placeholder="ID del Paciente" required />

      <input type="text" name="iniciales" placeholder="Iniciales" required />

      <div class="field-group">
        <label for="fechaNacimiento">Fecha de Nacimiento</label>
        <input type="date" name="fechaNacimiento" id="fechaNacimiento" required />
      </div>

      <div class="field-group">
        <label>Sexo</label>
        <div class="radio-group">
          <label class="radio-button">
            <input type="radio" name="sexo" value="Masculino" required />
            <span>Masculino</span>
          </label>
          <label class="radio-button">
            <input type="radio" name="sexo" value="Femenino" required />
            <span>Femenino</span>
          </label>
        </div>
      </div>

      <div class="field-group">
        <label for="fechaReclutamiento">Fecha de Reclutamiento</label>
        <input type="date" name="fechaReclutamiento" id="fechaReclutamiento" required />
      </div>

      <button type="submit">Enviar</button>
    </form>
  </div>

  <?php if ($mensaje): ?>
    <script>
      Swal.fire({
        icon: '<?= $mensajeTipo === "exito" ? "success" : "error" ?>',
        title: '<?= $mensajeTipo === "exito" ? "¡Paciente registrado!" : "¡Error!" ?>',
        text: '<?= $mensaje ?>',
        showConfirmButton: <?= $mensajeTipo === "exito" ? "false" : "true" ?>,
        timer: <?= $mensajeTipo === "exito" ? "2000" : "null" ?>
      });
    </script>
  <?php endif; ?>
</body>
</html>