<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Exploración Física Inicial</title>
  <link rel="stylesheet" href="./views/css/SignosV.css">
</head>
<body>
  <div class="container">
    <h3>Exploración Física Inicial</h3>
    <p>N: Normal &nbsp;&nbsp; A: Anormal</p>

    <form method="POST">
      <table style="width: 100%; margin-bottom: 20px;" border="1">
        <thead>
          <tr>
            <th>Sistema</th>
            <th>N</th>
            <th>A</th>
            <th>Comentarios</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $sistemas = [
            "Cabeza y cuello",
            "Oídos, nariz y garganta",
            "Tórax (corazón y pulmones)",
            "Extremidades superiores",
            "Abdomen",
            "Genitales",
            "Zona lumbar",
            "Extremidades inferiores"
          ];
          foreach ($sistemas as $index => $sistema):
          ?>
          <tr>
            <td><?= $sistema ?></td>
            <td><input type="checkbox" name="n_<?= $index ?>" /></td>
            <td><input type="checkbox" name="a_<?= $index ?>" /></td>
            <td><input type="text" name="comentario_<?= $index ?>" placeholder="Comentarios" style="width: 100%;" /></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <table style="width: 100%; margin-bottom: 20px;" border="1">
        <thead>
          <tr>
            <th colspan="3">Laboratorio VO (Basal)</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td colspan="3">¿Se realizaron BH, QS y EGO?</td>
          </tr>
          <tr>
            <td><label><input type="radio" name="bh_qs_ego" value="si" /> Sí</label></td>
            <td><label><input type="radio" name="bh_qs_ego" value="no" /> No</label></td>
            <td><input type="text" name="bh_qs_ego_motivo" placeholder="¿Por qué?" style="width: 100%;" /></td>
          </tr>
        </tbody>
      </table>

      <h3>Resultados Exámenes de Laboratorio VO</h3>
      <table style="width: 100%;" border="1">
        <thead>
          <tr>
            <th>Clínicamente Significativos</th>
            <th>No</th>
            <th>Sí</th>
            <th>¿Cuál(es) analito(s) y valor(es)?</th>
            <th>Acción Tomada<br /><small>*En caso de manejo farmacológico, anotar en Hoja de Medicamentos Concomitantes.</small></th>
          </tr>
        </thead>
        <tbody>
          <?php
          $examenes = [
            "Biometría Hemática",
            "Química Sanguínea",
            "Examen General de Orina"
          ];
          foreach ($examenes as $index => $examen):
          ?>
          <tr>
            <td><?= $examen ?></td>
            <td><input type="checkbox" name="no_<?= $index ?>" /></td>
            <td><input type="checkbox" name="si_<?= $index ?>" /></td>
            <td><input type="text" name="analito_<?= $index ?>" placeholder="Analito(s) y valor(es)" style="width: 100%;" /></td>
            <td><input type="text" name="accion_<?= $index ?>" placeholder="Acción tomada" style="width: 100%;" /></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <button type="submit">Guardar</button>
    </form>
  </div>
</body>
</html>