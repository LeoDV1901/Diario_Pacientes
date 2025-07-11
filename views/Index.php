<?php
session_start();

// Obtener la lista de pacientes desde el API
$pacientes = [];
$checkboxStates = [];

try {
  $response = @file_get_contents('http://127.0.0.1:5000/paciente/read');
  $data = json_decode($response, true);
  $lista = is_array($data['pacientes'] ?? $data) ? ($data['pacientes'] ?? $data) : [];

  foreach ($lista as $p) {
    $checkboxStates[$p['idPaciente']] = ['v1' => false, 'v2' => false];
  }

  $pacientes = $lista;
} catch (Exception $e) {
  $pacientes = [];
}

// Función para formatear el ID
function formatID($id, $iniciales) {
  return str_pad($id, 3, '0', STR_PAD_LEFT) . $iniciales;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Matriz de Sujetos</title>
  <link rel="stylesheet" href="./views/css/Index.css">
</head>
<body>
  <div class="vista-pacientes">
    <!-- Barra Superior -->
    <div class="barra-matriz">
      <div class="titulo-barra">Matriz de Sujetos</div>
      <div class="barra-grupo">
        <div class="flechas">
          <button>&laquo;</button>
          <button>&lt;</button>
          <button>&gt;</button>
          <button>&raquo;</button>
        </div>
        <div class="opciones">
          <label class="label-select">
            <select>
              <option>15</option>
              <option>25</option>
              <option>50</option>
            </select>
          </label>

          <select>
            <option>Seleccionar un Evento</option>
            <option>Evento 1</option>
            <option>Evento 2</option>
          </select>

          <a href="index.php?route=Graficas">
            <button class="boton-nuevo">Generar Gráficas</button>
          </a>
          <a href="index.php?route=RegistroPacientes">
            <button class="boton-nuevo">Añadir Nuevo Sujeto</button>
          </a>
        </div>
      </div>
    </div>

    <!-- Tabla de Pacientes -->
    <div class="tabla-contenedor">
      <table class="tabla-pacientes">
        <thead>
          <tr>
            <th>ID Sujeto</th>
            <th>Visita 1</th>
            <th>Visita 2</th>
            <th>MC</th>
            <th>R. Adversa</th>
            <th>R. Seg.</th>
            <th>D. Paciente</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php if (count($pacientes) > 0): ?>
            <?php foreach ($pacientes as $paciente): ?>
              <tr>
                <td><?= formatID($paciente['idPaciente'], $paciente['iniciales']) ?></td>
                <td><input type="checkbox" class="checkbox-white" /></td>
                <td><input type="checkbox" class="checkbox-white" /></td>
                <td><button class="icono">📄</button></td>
                <td><button class="icono">📄</button></td>
                <td><button class="icono">📄</button></td>
                <td><span>x5</span></td>
                <td>
                  <a href="index.php?route=Cronograma&idPaciente=<?= $paciente['idPaciente'] ?>">
                    <button class="accion-boton">🔍</button>
                  </a>
                  <form method="POST" style="display:inline;" onsubmit="return confirm('¿Estás seguro de que quieres eliminar a este paciente?');">
                    <input type="hidden" name="delete_id" value="<?= $paciente['idPaciente'] ?>">
                    <button class="accion-boton eliminar-boton">🗑️</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="8" style="color: white; text-align: center;">No hay pacientes registrados.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</body>
</html>

<?php
// Manejo de eliminación (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
  $idPaciente = $_POST['delete_id'];
  $delete = @file_get_contents("http://127.0.0.1:5000/paciente/delete/$idPaciente", false, stream_context_create(['http' => ['method' => 'DELETE']]));
  // Después de eliminar, puedes redirigir para recargar la tabla
  header('Location: index.php?route=Index');
  exit;
}
?>