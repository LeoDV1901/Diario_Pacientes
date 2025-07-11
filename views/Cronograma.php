<?php
$idPaciente = $_GET['idPaciente'] ?? null;

// Arrays de datos estáticos
$procedimientos = [
    "Firma de consentimiento Informado",
    "Criterios de Inclusión/ Exclusión",
    "Historia Clínica",
    "Signos Vitales",
    "Medicamentos Concomitantes",
    "Eventos Adversos",
];

$visitas = ["Visita 0", "Visita 1", "Visita 2", "Visita 3"];

$links = [
    "Firma de consentimiento Informado" => ["Visita 0" => "ConcentimientoInformado"],
    "Criterios de Inclusión/ Exclusión" => ["Visita 0" => "CriteriosE"],
    "Historia Clínica" => ["Visita 0" => "HistoriaClinica"],
    "Signos Vitales" => [
        "Visita 0" => "Signos_Vitales",
        "Visita 1" => "signos-vitales/v1",
        "Visita 2" => "signos-vitales/v2"
    ],
    "Medicamentos Concomitantes" => ["Visita 0" => "MedicamentosConcomitantes"],
    "Eventos Adversos" => ["Visita 0" => "EventosAdversos"],
];

// Inicializa los colores de botones
$botonColors = [
    "FirmaConsentimiento" => 'green',
    "SignosVitales" => 'green',
    "CriteriosInclusion" => 'green',
];

// Lógica asincrónica simulada en PHP usando llamadas fetch
try {
    // Verifica consentimiento informado
    $consentimiento = @file_get_contents("http://127.0.0.1:5000/form/verifyconsentimiento/$idPaciente");
    if ($consentimiento) {
        $data = json_decode($consentimiento, true);
        $botonColors['FirmaConsentimiento'] = $data['respuesta_correcta'] ? 'green' : 'red';
    }

    // Verifica signos vitales
    $signos = @file_get_contents("http://localhost:5000/form/verifysignos_vitales/$idPaciente");
    if ($signos) {
        $data = json_decode($signos, true);
        $fueraDeRango = false;
        foreach ($data as $signo) {
            foreach ($signo as $key => $value) {
                if (str_contains($key, '_fuera_de_rango') && $value) {
                    $fueraDeRango = true;
                    break;
                }
            }
        }
        $botonColors['SignosVitales'] = $fueraDeRango ? 'red' : 'green';
    }

    // Verifica criterios de inclusión
    $criterios = @file_get_contents("http://127.0.0.1:5000/form/verifycriterios_inclusion/$idPaciente");
    if ($criterios) {
        $data = json_decode($criterios, true);
        $botonColors['CriteriosInclusion'] = $data['respuesta_correcta'] ? 'green' : 'red';
    }
} catch (Exception $e) {
    $botonColors = [
        "FirmaConsentimiento" => 'red',
        "SignosVitales" => 'red',
        "CriteriosInclusion" => 'red',
    ];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Tabla de Procedimientos</title>
  <link rel="stylesheet" href="./views/css/Cronograma2.css">
  <style>
    table { border-collapse: collapse; width: 100%; }
    th, td { border: 1px solid #ccc; padding: 8px; text-align: center; }
    .boton-celda { padding: 5px 10px; border-radius: 4px; cursor: pointer; }
  </style>
</head>
<body>
  <div class="container" style="overflow-x: auto;">
    <h2>Tabla de Procedimientos</h2>
    <table>
      <thead>
        <tr>
          <th>Procedimiento</th>
          <?php foreach ($visitas as $v): ?>
            <th><?= htmlspecialchars($v) ?></th>
          <?php endforeach; ?>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($procedimientos as $proc): ?>
          <tr>
            <td><?= htmlspecialchars($proc) ?></td>
            <?php foreach ($visitas as $visita): 
              $link = $links[$proc][$visita] ?? "#";
              $bgColor = 'initial';

              if ($proc === "Firma de consentimiento Informado") $bgColor = $botonColors['FirmaConsentimiento'];
              if ($proc === "Signos Vitales") $bgColor = $botonColors['SignosVitales'];
              if ($proc === "Criterios de Inclusión/ Exclusión") $bgColor = $botonColors['CriteriosInclusion'];
            ?>
              <td>
                <?php if ($link !== "#"): ?>
                  <form method="GET" action="index.php">
                    <input type="hidden" name="route" value="<?= htmlspecialchars($link) ?>">
                    <input type="hidden" name="idPaciente" value="<?= htmlspecialchars($idPaciente) ?>">
                    <button class="boton-celda" style="background-color: <?= $bgColor ?>;">
                      📝
                    </button>
                  </form>
                <?php else: ?>
                  <button class="boton-celda" style="width:30px; height:30px;" disabled></button>
                <?php endif; ?>
              </td>
            <?php endforeach; ?>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</body>
</html>