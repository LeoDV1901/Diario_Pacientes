<?php
session_start();
$error = '';
$success = '';

// Manejo del envío
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $data = [
    'iniciales' => $_POST['iniciales'] ?? '',
    'num_aleatorizacion' => $_POST['num_aleatorizacion'] ?? '',
    'presento_ea' => $_POST['presento_ea'] === 'si',
    'evento' => $_POST['evento'] ?? '',
    'clasificado_como_ea' => $_POST['clasificado_como_ea'] ?? '',
    'fecha_inicio' => $_POST['fecha_inicio'] ?? '',
    'fecha_termino' => $_POST['fecha_termino'] ?? '',
    'hora_inicio' => $_POST['hora_inicio'] ?? '',
    'hora_termino' => $_POST['hora_termino'] ?? '',
    'intensidad' => $_POST['intensidad'] ?? '',
    'causalidad' => $_POST['causalidad'] ?? '',
    'relacion_medicamento' => $_POST['relacion_medicamento'] ?? '',
    'acciones_tomadas' => $_POST['acciones_tomadas'] ?? '',
    'desenlace' => $_POST['desenlace'] ?? '',
    'nota_evolucion' => $_POST['nota_evolucion'] ?? ''
  ];

  $json = json_encode($data);
  $opts = [
    'http' => [
      'header' => "Content-Type: application/json\r\n",
      'method' => 'POST',
      'content' => $json
    ]
  ];
  $context = stream_context_create($opts);
  $response = @file_get_contents('http://localhost:5000/form/eventos_adversos', false, $context);

  if ($response !== false) {
    $success = 'Evento adverso registrado con éxito';
  } else {
    $error = 'Hubo un error al registrar el evento adverso';
  }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Eventos Adversos</title>
  <link rel="stylesheet" href="./views/css/SignosV.css">
</head>
<body>
  <div class="container">
    <h2>EVENTOS ADVERSOS</h2>
    <?php if ($success): ?><p style="color: green;"><?= $success ?></p><?php endif; ?>
    <?php if ($error): ?><p style="color: red;"><?= $error ?></p><?php endif; ?>

    <form method="POST">
      <table>
        <tr>
          <td><label>Iniciales:</label></td>
          <td><input type="text" name="iniciales" required /></td>
        </tr>
        <tr>
          <td><label>No. De Aleatorización:</label></td>
          <td><input type="text" name="num_aleatorizacion" required /></td>
        </tr>
        <tr>
          <td colspan="2">
            <label>¿El paciente presentó algún Evento Adverso durante la visita?</label><br>
            <label><input type="radio" name="presento_ea" value="si" required /> Sí</label>
            <label><input type="radio" name="presento_ea" value="no" required /> No</label>
          </td>
        </tr>
      </table>

      <div id="detalleEA">
        <table>
          <tr><td><label>Evento Adverso:</label></td><td><input type="text" name="evento" /></td></tr>
          <tr>
            <td><label>¿Es clasificado como EA's?</label></td>
            <td>
              <label><input type="radio" name="clasificado_como_ea" value="si" /> Sí</label>
              <label><input type="radio" name="clasificado_como_ea" value="no" /> No</label>
            </td>
          </tr>
          <tr><td><label>Fecha de inicio:</label></td><td><input type="date" name="fecha_inicio" /></td></tr>
          <tr><td><label>Fecha de término:</label></td><td><input type="date" name="fecha_termino" /></td></tr>
          <tr><td><label>Hora de inicio:</label></td><td><input type="time" name="hora_inicio" /></td></tr>
          <tr><td><label>Hora de término:</label></td><td><input type="time" name="hora_termino" /></td></tr>
          <tr>
            <td><label>Intensidad:</label></td>
            <td>
              <select name="intensidad">
                <option value="">Seleccione</option>
                <option value="1">1. Leve</option>
                <option value="2">2. Moderado</option>
                <option value="3">3. Severo</option>
              </select>
            </td>
          </tr>
          <tr>
            <td><label>Causalidad:</label></td>
            <td>
              <select name="causalidad">
                <option value="">Seleccione</option>
                <option value="1">1. Cierta</option>
                <option value="2">2. Probable</option>
                <option value="3">3. Posible</option>
                <option value="4">4. Improbable</option>
                <option value="5">5. Condicional/No clasificada</option>
                <option value="6">6. No evaluable / Inclasificable</option>
              </select>
            </td>
          </tr>
          <tr>
            <td><label>Relación con el medicamento de estudio:</label></td>
            <td>
              <select name="relacion_medicamento">
                <option value="">Seleccione</option>
                <option value="1">1. Ninguna</option>
                <option value="2">2. Dudosa</option>
                <option value="3">3. Posible</option>
                <option value="4">4. Probable</option>
                <option value="5">5. Muy probable</option>
              </select>
            </td>
          </tr>
          <tr>
            <td><label>Acciones tomadas:</label></td>
            <td>
              <select name="acciones_tomadas">
                <option value="">Seleccione</option>
                <option value="1">1. Ninguna</option>
                <option value="2">2. Descontinuación del medicamento de estudio</option>
                <option value="3">3. Medicamento concomitante</option>
                <option value="4">4. Hospitalización requerida o prolongada</option>
                <option value="5">5. Otro</option>
              </select>
            </td>
          </tr>
          <tr>
            <td><label>Desenlace:</label></td>
            <td>
              <select name="desenlace">
                <option value="">Seleccione</option>
                <option value="1">1. Resuelto</option>
                <option value="2">2. Mejoría</option>
                <option value="3">3. Sin cambio</option>
                <option value="4">4. Empeoró</option>
                <option value="5">5. Muerte</option>
                <option value="6">6. Pérdida de seguimiento</option>
              </select>
            </td>
          </tr>
          <tr><td><label>Nota de evolución:</label></td><td><textarea name="nota_evolucion" rows="5"></textarea></td></tr>
        </table>
      </div>

      <button type="submit">Guardar</button>
    </form>
  </div>
</body>
</html>