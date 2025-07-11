<?php
// Obtiene la ruta solicitada desde la URL
$route = $_GET['route'] ?? 'login';

// Enrutamiento
switch ($route) {
    case 'CriteriosI':
        include './views/Forms/Criterios_exclusion.php';
        break;
    case 'CriteriosE':
        include './views/Forms/Criterios_inclusion.php';
        break;
    case 'Signos_Vitales':
        include './views/Forms/Signos_Vitales.php';
        break;
    case 'Cronograma':
        include './views/Cronograma.php';
        break;
    case 'HistoriaClinica':
        include './views/Forms/HistoriaClinica.php';
        break;
    case 'ExploracionFisicaInicial':
        include './views/Forms/ExploracionFisicaInicial.php';
        break;
    case 'ConcentimientoInformado':
        include './views/Forms/FormatoConcentimiento.php';
        break;
    case 'IntroDiarioPaciente':
        include './views/IntroDiarioPaciente.php';
        break;
    case 'AvisodePrivacidad':
        include './views/AvisoPrivacidad.php';
        break;
    case 'InstruccionesDiario':
        include './views/InstruccionesDiario.php';
        break;
    case 'PreguntaInicio':
        include './views/PreguntaInicio.php';
        break;
    case 'FechaDiarioPaciente':
        include './views/HorarioCita.php';
        break;
    case 'EvaluacionTratamiento':
        include './views/EvaluacionTratamiento.php';
        break;
    case 'RegistroPacientes':
        include './views/RegistroPacientes.php';
        break;
    case 'Index':
        include './views/Index.php';
        break;
    case 'Graficas':
        include './views/Graficas.php';
        break;
    case 'MedicamentosConcomitantes':
        include './views/Forms/MedicamentosConcomitantes.php';
        break;
    case 'MedicamentosAdversos':
        include './views/Forms/MedicamentosAdversos.php';
        break;
    case 'login':
    default:
        include './views/Login.php';
        break;
}
?>