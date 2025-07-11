<?php
$idPaciente = $_GET['idPaciente'] ?? '';
$api_base = 'http://localhost:5000/form/signos';
$registro = null;
$mensaje = '';
$editId = null;

// Función para hacer petición HTTP con cURL
function request_api($url, $method = 'GET', $data = null) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $headers = ['Content-Type: application/json'];

    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
    } elseif ($method === 'PUT' || $method === 'DELETE') {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    }

    if ($data) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $response = curl_exec($ch);
    curl_close($ch);
    return json_decode($response, true);
}

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'];
    $editId = $_POST['editId'] ?? null;

    $fecha = $_POST['anio'] . '-' . str_pad($_POST['mes'], 2, '0', STR_PAD_LEFT) . '-' . str_pad($_POST['dia'], 2, '0', STR_PAD_LEFT);

    $data = [
        'idPaciente' => $_POST['idPaciente'],
        'aleatorizacion' => $_POST['aleatorizacion'] ?? '',
        'fecha' => $fecha,
        'genero' => $_POST['genero'] ?? '',
        'presion_sistolica' => $_POST['presion_sistolica'],
        'presion_diastolica' => $_POST['presion_diastolica'],
        'temperatura' => $_POST['temperatura'],
        'frecuencia_cardiaca' => $_POST['frecuencia_cardiaca'],
        'frecuencia_respiratoria' => $_POST['frecuencia_respiratoria'],
        'peso' => $_POST['peso'],
        'talla' => $_POST['talla'],
        'imc' => $_POST['imc'],
        'embarazo' => $_POST['embarazo'] ?? '',
        'comentario' => $_POST['comentario'] ?? ''
    ];

    if ($accion === 'guardar') {
        if ($editId) {
            request_api("$api_base/$editId", 'PUT', $data);
            $mensaje = "Registro actualizado correctamente.";
        } else {
            request_api($api_base, 'POST', $data);
            $mensaje = "Datos guardados exitosamente.";
        }
    } elseif ($accion === 'eliminar' && $editId) {
        request_api("$api_base/$editId", 'DELETE');
        $mensaje = "Registro eliminado correctamente.";
    }
}

// Obtener datos del paciente
if ($idPaciente) {
    $result = request_api("$api_base/paciente/$idPaciente");
    if (!empty($result)) {
        $registro = end($result); // último registro
        $editId = $registro['id'] ?? null;
        $fechaParts = explode('-', $registro['fecha']);
        $_POST['anio'] = $fechaParts[0] ?? '';
        $_POST['mes'] = $fechaParts[1] ?? '';
        $_POST['dia'] = $fechaParts[2] ?? '';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="./views/css/SignosV.css">
    <title>Formulario Médico</title>
    <style>
        input, textarea { padding: 8px; width: 100%; margin: 4px 0; }
        label { display: block; margin-top: 10px; }
        .rojo { border: 2px solid red; }
    </style>
    <script>
        function calcularIMC() {
            const peso = parseFloat(document.getElementById('peso').value);
            const talla = parseFloat(document.getElementById('talla').value);
            const imc = peso && talla ? (peso / (talla * talla)).toFixed(2) : '';
            document.getElementById('imc').value = imc;
        }

        function toggleComentario() {
            const embarazada = document.querySelector('input[name="embarazo"]:checked');
            const comentario = document.getElementById('comentarioBox');
            comentario.style.display = embarazada && embarazada.value === 'Si' ? 'block' : 'none';
        }

        function validarRango(id, min, max) {
            const input = document.getElementById(id);
            const val = parseFloat(input.value);
            if (!isNaN(val) && (val < min || val > max)) {
                input.classList.add("rojo");
            } else {
                input.classList.remove("rojo");
            }
        }
    </script>
</head>
<body onload="toggleComentario(); calcularIMC();">

<div class="container">
  <h2>Formulario Médico</h2>
  <form method="post" oninput="calcularIMC();">

    <div>
      <label for="idPaciente">ID Paciente</label>
      <input type="text" name="idPaciente" value="<?= htmlspecialchars($idPaciente) ?>" readonly>
    </div>

    <div>
      <label for="aleatorizacion">Aleatorización</label>
      <input type="text" name="aleatorizacion" value="<?= $registro['aleatorizacion'] ?? '' ?>">
    </div>

    <h3>Fecha</h3>
    <div style="display: flex; gap: 10px;">
      <input type="text" name="dia" placeholder="Día" value="<?= $_POST['dia'] ?? '' ?>">
      <input type="text" name="mes" placeholder="Mes" value="<?= $_POST['mes'] ?? '' ?>">
      <input type="text" name="anio" placeholder="Año" value="<?= $_POST['anio'] ?? '' ?>">
    </div>

 <h3>Datos Clínicos</h3>

<!-- Tabla de Género y Embarazo -->
<table>
  <tr>
    <td>Género</td>
    <td>
      <label>
        <input
          type="radio"
          name="genero"
          value="Masculino"
          <?= ($registro['genero'] ?? '') === 'Masculino' ? 'checked' : '' ?>
        /> Masculino
      </label>
      <label>
        <input
          type="radio"
          name="genero"
          value="Femenino"
          <?= ($registro['genero'] ?? '') === 'Femenino' ? 'checked' : '' ?>
        /> Femenino
      </label>
    </td>
  </tr>
</table>

<!-- Tabla de Signos Vitales -->
<h3>Signos Vitales</h3>
<table>
  <tr>
    <td>Presión Sistólica</td>
    <td>
      <input
        type="text"
        name="presion_sistolica"
        value="<?= $registro['presion_sistolica'] ?? '' ?>"
      />
    </td>
    <td>Presión Diastólica</td>
    <td>
      <input
        type="text"
        name="presion_diastolica"
        value="<?= $registro['presion_diastolica'] ?? '' ?>"
      />
    </td>
  </tr>
  <tr>
    <td>Temperatura</td>
    <td>
      <input
        type="text"
        name="temperatura"
        value="<?= $registro['temperatura'] ?? '' ?>"
      />
    </td>
    <td>Frecuencia Cardiaca</td>
    <td>
      <input
        type="text"
        name="frecuencia_cardiaca"
        value="<?= $registro['frecuencia_cardiaca'] ?? '' ?>"
      />
    </td>
  </tr>
  <tr>
    <td>Frecuencia Respiratoria</td>
    <td>
      <input
        type="text"
        name="frecuencia_respiratoria"
        value="<?= $registro['frecuencia_respiratoria'] ?? '' ?>"
      />
    </td>
    <td>Peso (kg)</td>
    <td>
      <input
        type="text"
        id="peso"
        name="peso"
        value="<?= $registro['peso'] ?? '' ?>"
        oninput="calcularIMC()"
      />
    </td>
  </tr>
  <tr>
    <td>Talla (m)</td>
    <td>
      <input
        type="text"
        id="talla"
        name="talla"
        value="<?= $registro['talla'] ?? '' ?>"
        oninput="calcularIMC()"
      />
    </td>
    <td>IMC</td>
    <td>
      <input
        type="text"
        id="imc"
        name="imc"
        readonly
        value="<?= $registro['imc'] ?? '' ?>"
      />
    </td>
    <tr>
    <td>¿Está embarazada?</td>
    <td>
      <label>
        <input
          type="radio"
          name="embarazo"
          value="Si"
          <?= ($registro['embarazo'] ?? '') === 'Si' ? 'checked' : '' ?>
          onclick="document.getElementById('comentarioBox').style.display = 'table-row';"
        /> Sí
      </label>
      <label>
        <input
          type="radio"
          name="embarazo"
          value="No"
          <?= ($registro['embarazo'] ?? '') === 'No' ? 'checked' : '' ?>
          onclick="document.getElementById('comentarioBox').style.display = 'none';"
        /> No
      </label>
    </td>
  </tr>

  <!-- Campo Comentario si embarazo = Sí -->
  <tr id="comentarioBox" style="display: <?= ($registro['embarazo'] ?? '') === 'Si' ? 'table-row' : 'none' ?>;">
    <td>Comentario</td>
    <td colspan="3">
      <textarea name="comentario" rows="2"><?= $registro['comentario'] ?? '' ?></textarea>
    </td>
  </tr>
  </tr>
</table>
<script>
  function calcularIMC() {
    const peso = parseFloat(document.getElementById('peso').value);
    const talla = parseFloat(document.getElementById('talla').value);
    if (!isNaN(peso) && !isNaN(talla) && talla > 0) {
      const imc = peso / (talla * talla);
      document.getElementById('imc').value = imc.toFixed(2);
    }
  }
</script>


    <input type="hidden" name="editId" value="<?= $editId ?>">
    <button type="submit" name="accion" value="guardar"><?= $editId ? 'Actualizar' : 'Guardar' ?></button>
    <?php if ($editId): ?>
      <button type="submit" name="accion" value="eliminar" onclick="return confirm('¿Eliminar?')">Eliminar</button>
    <?php endif; ?>
  </form>
</div>


</body>
</html>
