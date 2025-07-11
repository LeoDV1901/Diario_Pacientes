<?php
// Inicia sesión si es necesario
session_start();

// Variables para capturar los valores
$email = '';
$password = '';
$error = '';

// Maneja el envío del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (!str_ends_with($email, '@infinite.com.mx')) {
        $error = 'El correo no tiene acceso';
    } elseif ($email === 'admin@infinite.com.mx' && $password === 'Admin123') {
        header('Location: index.php?route=Index');
        exit;
    } else {
        header('Location: index.php?route=PreguntaInicio');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Iniciar Sesión</title>
  <link rel="stylesheet" href="./views/css/login.css">
</head>
<body>
  <div class="container">
    <img src="/Diario_Pacientes/public/image001.png" alt="Logo Infinite" class="logo" />
    <h2>Iniciar Sesión</h2>

    <?php if ($error): ?>
      <p style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST">
      <input 
        type="email" 
        name="email" 
        placeholder="Correo electrónico" 
        value="<?= htmlspecialchars($email) ?>" 
        required 
      />
      <input 
        type="password" 
        name="password" 
        placeholder="Contraseña" 
        value="<?= htmlspecialchars($password) ?>" 
        required 
      />
      <button type="submit">Entrar</button>
    </form>
  </div>
</body>
</html>