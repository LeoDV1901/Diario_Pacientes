<?php
session_start();
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $formData = [
    'iniciales' => $_POST['iniciales'] ?? '',
    'num_aleatorizacion' => $_POST['num_aleatorizacion'] ?? '',
    'consumio_medicamento' => $_POST['consumio_medicamento'] ?? '',
    'nombre_medicamento' => $_POST['nombre_medicamento'] ?? '',
    'dosis_diaria' => $_POST['dosis_diaria'] ?? '',
    'presentacion' => $_POST['presentacion'] ?? '',
    'indicacionTerapeutica' => $_POST['indicacionTerapeutica'] ?? '',
    'fecha_inicio' => $_POST['fecha_inicio'] ?? '',
    'fecha_termino' => $_POST['fecha_termino'] ?? '',
    'continua_consumo' => $_POST['continua_consumo'] ?? ''
  ];

  $json = json_encode($formData);
  $opts = [
    'http' => [
      'header' => "Content-Type: application/json\r\n",
      'method' => 'POST',
      'content' => $json
    ]
  ];
  $context = stream_context_create($opts);
  $response = @file_get_contents('http://localhost:5000/form/medicamentos_concomitantes', false, $context);

  if ($response !== false) {
    $success = 'Formulario enviado con éxito';
  } else {
    $error = 'Hubo un error al enviar el formulario';
  }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Medicamentos Concomitantes</title>
  <link rel="stylesheet" href="./views/css/SignosV.css">
</head>
<body>
  <div class="container">
    <h2>MEDICAMENTOS CONCOMITANTES</h2>
    <?php if ($success): ?><p style="color: green;"><?= $success ?></p><?php endif; ?>
    <?php if ($error): ?><p style="color: red;"><?= $error ?></p><?php endif; ?>

    <form method="POST">
      <table>
        <tr>
          <td><label>Iniciales:</label></td>
          <td><input type="text" name="iniciales" placeholder="Iniciales del paciente" required /></td>
        </tr>
        <tr>
          <td><label>No. De Aleatorización:</label></td>
          <td><input type="text" name="num_aleatorizacion" placeholder="Número de aleatorización" required /></td>
        </tr>
        <tr>
          <td colspan="2">
            <label>¿El paciente consumió algún medicamento concomitante?</label><br />
            <label><input type="radio" name="consumio_medicamento" value="SI" required /> Sí</label>
            <label><input type="radio" name="consumio_medicamento" value="NO" required /> No</label>
          </td>
        </tr>
      </table>

      <h3>Si respondió "Sí", complete lo siguiente:</h3>
      <table>
        <tr><td><label>Nombre genérico:</label></td><td><input type="text" name="nombre_medicamento" /></td></tr>
        <tr><td><label>Dosis diaria:</label></td><td><input type="text" name="dosis_diaria" /></td></tr>
        <tr><td><label>Presentación:</label></td><td><input type="text" name="presentacion" /></td></tr>
        <tr><td><label>Indicación terapéutica:</label></td><td><input type="text" name="indicacionTerapeutica" /></td></tr>
        <tr><td><label>Fecha de inicio:</label></td><td><input type="date" name="fecha_inicio" /></td></tr>
        <tr><td><label>Fecha de término:</label></td><td><input type="date" name="fecha_termino" /></td></tr>
        <tr>
          <td colspan="2">
            <label>¿Continúa consumiendo el medicamento?</label><br />
            <label><input type="radio" name="continua_consumo" value="SI" /> Sí</label>
            <label><input type="radio" name="continua_consumo" value="NO" /> No</label>
          </td>
        </tr>
      </table>

      <button type="submit">Guardar</button>
    </form>
  </div>
</body>
</html>