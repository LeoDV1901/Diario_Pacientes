<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Historia Clínica</title>
  <link rel="stylesheet" href="./views/css/HistoriaClinica.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
  <form id="formPatologias" method="POST">
    <h3>Antecedentes Heredo Familiares</h3>
    <table style="width: 100%; margin-bottom: 20px;" border="1">
      <thead>
        <tr>
          <th>No</th>
          <th>Sí</th>
          <th>Familiar</th>
          <th>Año de Inicio</th>
          <th>Fallecido Sí</th>
          <th>Fallecido No</th>
        </tr>
      </thead>
      <tbody>
        <!-- Heredo Familiares -->
        <script>
          const heredoItems = ["Diabetes Mellitus", "Hipertensión Arterial", "Cardiopatías", "Cáncer", "Otra"];
          document.write(heredoItems.map((item, index) => `
            <tr>
              <td><input type="checkbox" class="exclusivo-no" name="heredo_no_${index}" data-pair="heredo_si_${index}"></td>
              <td><input type="checkbox" class="exclusivo-si" name="heredo_si_${index}" data-pair="heredo_no_${index}"></td>
              <td><input type="text" name="heredo_familiar_${index}"></td>
              <td><input type="text" name="heredo_inicio_${index}"></td>
              <td><input type="checkbox" class="exclusivo-si" name="heredo_fallecido_si_${index}" data-pair="heredo_fallecido_no_${index}"></td>
              <td><input type="checkbox" class="exclusivo-no" name="heredo_fallecido_no_${index}" data-pair="heredo_fallecido_si_${index}"></td>
            </tr>`).join(''));
        </script>
      </tbody>
    </table>

    <h3>Antecedentes Personales No Patológicos</h3>
    <table style="width: 100%;" border="1">
      <thead>
        <tr>
          <th>No</th>
          <th>Sí</th>
          <th>AÑO INICIO</th>
          <th>CONTINUA</th>
          <th>AÑO FIN</th>
          <th>COPAS/SEMANA</th>
          <th>CIGARRILLOS/SEMANA</th>
          <th>CONSUMO/SEMANA</th>
        </tr>
      </thead>
      <tbody>
        <script>
          const noPatItems = ["ALCOHOL", "TABACO", "MARIHUANA", "COCAÍNA", "HEROINA", "CRISTAL/PIEDRA"];
          document.write(noPatItems.map((item, index) => `
            <tr>
              <td><input type="checkbox" class="exclusivo-no" name="nopat_no_${index}" data-pair="nopat_si_${index}"></td>
              <td><input type="checkbox" class="exclusivo-si" name="nopat_si_${index}" data-pair="nopat_no_${index}"></td>
              <td><input type="text" name="nopat_inicio_${index}"></td>
              <td><input type="checkbox" name="nopat_continua_${index}"></td>
              <td><input type="text" name="nopat_fin_${index}"></td>
              <td><input type="text" name="nopat_copas_${index}"></td>
              <td><input type="text" name="nopat_cigarros_${index}"></td>
              <td><input type="text" name="nopat_consumo_${index}"></td>
            </tr>`).join(''));
        </script>
      </tbody>
    </table>

    <h3>Antecedentes Personales Patológicos</h3>
    <p>Seleccione las patologías:</p>
    <script>
      const patologias = ["Diabetes Mellitus", "Hipertensión Arterial", "Dislipidemias", "Sobrepeso", "Obesidad", "Alérgicos", "Quirúrgicos", "Otro"];
      document.write(patologias.map((item, index) => `
        <label>
          <input type="checkbox" class="patologia-check" name="patologia_${index}" value="${item}" data-patologia="${item}"> ${item}
        </label><br>`).join(''));
    </script>

    <div id="tabla-patologias-detalle" style="margin-top: 20px;"></div>

    <h3>Padecimiento Actual</h3>
    <table style="width: 100%;" border="1">
      <thead>
        <tr>
          <th>Padecimiento</th>
          <th>Sí</th>
          <th>No</th>
          <th>Día</th>
          <th>Mes</th>
          <th>Año</th>
          <th>Continua Sí</th>
        </tr>
      </thead>
      <tbody>
        <script>
          const padecimientos = [
            "Dolor de espalda baja", "Dolor sordo", "Dolor sin irradiación",
            "Dolor que se irradia al glúteo", "Dolor que se irradia a la pierna",
            "Dolor que limita los movimientos", "Dolor que limita las actividades diarias/laborales"
          ];
          document.write(padecimientos.map((item, index) => `
            <tr>
              <td>${item}</td>
              <td><input type="checkbox" class="exclusivo-si" name="padecimiento_si_${index}" data-pair="padecimiento_no_${index}"></td>
              <td><input type="checkbox" class="exclusivo-no" name="padecimiento_no_${index}" data-pair="padecimiento_si_${index}"></td>
              <td><input type="text" name="padecimiento_dia_${index}"></td>
              <td><input type="text" name="padecimiento_mes_${index}"></td>
              <td><input type="text" name="padecimiento_anio_${index}"></td>
              <td><input type="checkbox" name="padecimiento_continua_${index}"></td>
            </tr>`).join(''));
        </script>
      </tbody>
    </table>

    <br>
    <button type="submit">Guardar</button>
  </form>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const form = document.getElementById('formPatologias');
      const contenedor = document.getElementById('tabla-patologias-detalle');

      // Exclusividad entre Sí y No
      document.querySelectorAll('input[type="checkbox"][data-pair]').forEach(chk => {
        chk.addEventListener('change', () => {
          if (chk.checked) {
            const pair = document.getElementsByName(chk.getAttribute('data-pair'))[0];
            if (pair) pair.checked = false;
          }
        });
      });

      // Patologías dinámicas
      document.querySelectorAll('.patologia-check').forEach(cb => {
        cb.addEventListener('change', actualizarTabla);
      });

      function actualizarTabla() {
        const seleccionadas = Array.from(document.querySelectorAll('.patologia-check:checked'))
          .map(cb => cb.dataset.patologia);

        if (seleccionadas.length === 0) {
          contenedor.innerHTML = '';
          return;
        }

        let html = `
          <h4>Detalles de las Patologías Seleccionadas</h4>
          <table border="1">
            <thead>
              <tr>
                <th>Patología</th>
                <th>Fecha Diagnóstico</th>
                <th>Tratamiento</th>
                <th>Observaciones</th>
              </tr>
            </thead>
            <tbody>`;

        seleccionadas.forEach((patologia, i) => {
          html += `
            <tr>
              <td><input type="text" name="detalle_patologia_${i}_nombre" value="${patologia}" readonly></td>
              <td><input type="text" name="detalle_patologia_${i}_fecha" placeholder="DD/MM/AAAA"></td>
              <td><input type="text" name="detalle_patologia_${i}_tratamiento"></td>
              <td><input type="text" name="detalle_patologia_${i}_observaciones"></td>
            </tr>`;
        });

        html += '</tbody></table>';
        contenedor.innerHTML = html;
      }

      form.addEventListener('submit', function (e) {
        e.preventDefault();

        const algunaSi = document.querySelector('.exclusivo-si:checked, .patologia-check:checked');
        if (!algunaSi) {
          Swal.fire({ icon: 'warning', title: 'Completa el formulario', text: 'Debes seleccionar al menos una opción con "Sí".' });
          return;
        }

        const formData = new FormData(form);
        const patologias = [];

        document.querySelectorAll('.patologia-check:checked').forEach((cb, i) => {
          patologias.push({
            nombre: cb.value,
            fecha: formData.get(`detalle_patologia_${i}_fecha`) || '',
            tratamiento: formData.get(`detalle_patologia_${i}_tratamiento`) || '',
            observaciones: formData.get(`detalle_patologia_${i}_observaciones`) || ''
          });
        });
console.log("Enviando datos a la API:", patologias);

        fetch('http://localhost:5000/form/historia_clinica', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ patologias })
        })
        .then(res => res.ok ? res.json() : Promise.reject(res))
        .then(data => {
          Swal.fire({ icon: 'success', title: 'Guardado', text: data.mensaje || 'Datos guardados con éxito', timer: 1500, showConfirmButton: false });
          form.reset();
          contenedor.innerHTML = '';
        })
        .catch(err => {
          Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudo guardar. Intenta más tarde.' });
        });
      });
    });
  </script>
</body>
</html>
