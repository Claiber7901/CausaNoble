<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $dui = $_POST['dui']; // Obtener el DUI

    // Validar que el DUI tenga exactamente 9 dígitos
    if (!preg_match('/^[0-9]{9}$/', $dui)) {
        $error = "El DUI debe tener 9 dígitos.";
    } else {
        $checkQuery = "SELECT * FROM usuario WHERE Email = ? OR DUI = ?";
        $stmt = $conn->prepare($checkQuery);
        $stmt->bind_param("ss", $email, $dui); // Comprobar tanto el email como el DUI
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = "El correo electrónico o DUI ya están registrados.";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $insertQuery = "INSERT INTO usuario (Nombre, Apellido, Email, Contraseña, DUI) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($insertQuery);
            $stmt->bind_param("sssss", $nombre, $apellido, $email, $hashedPassword, $dui); // Agregar DUI a la consulta
            
            if ($stmt->execute()) {
                $success = "Registro exitoso. Puedes iniciar sesión ahora.";
            } else {
                $error = "Error al registrar. Intenta nuevamente.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Registro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/carousel.css" rel="stylesheet">
    <link href="css/footer.css" rel="stylesheet">
    <link href="css/registro.css" rel="stylesheet">

</head>
<body>

<nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">CausaNoble</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <ul class="navbar-nav me-auto mb-2 mb-md-0">
                <li class="nav-item">
                    <a class="nav-link" href="reporte.php">Reporte Colonias</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="colonias.php">Colonias</a>
                </li>
            </ul>
            <div class="d-flex align-items-center"> 
                <?php if (isset($_SESSION['user_name'])): ?>
                    <span class="navbar-text me-3 text-white">
                        Bienvenid@, <?= htmlspecialchars($_SESSION['user_name']); ?>
                    </span>
                    <a href="logout.php" class="me-2">
                        <button class="btn btn-outline-danger">Cerrar sesión</button>
                    </a>
                <?php else: ?>
                    <a href="registro.php" class="me-2">
                        <button class="btn btn-outline-primary">Registrarte</button>
                    </a>
                    <a href="login.php" class="me-2">
                        <button class="btn btn-outline-danger">Iniciar sesión</button>
                    </a>
                <?php endif; ?>
                <a href="checkout_.php">
                    <button class="btn btn-success" type="button">Donar</button>
                </a>
            </div>
        </div>
    </div>
</nav>

<div class="container">
    <h2 class="text-center mb-4" style="margin-top: 80px;">Registro</h2>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= $error; ?></div>
    <?php elseif (isset($success)): ?>
        <div class="alert alert-success"><?= $success; ?></div>
    <?php endif; ?>
    <form method="POST" action="">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ingresa tu nombre" required>
        </div>
        <div class="mb-3">
            <label for="apellido" class="form-label">Apellido</label>
            <input type="text" class="form-control" id="apellido" name="apellido" placeholder="Ingresa tu apellido" required>
        </div>
        <div class="mb-3">
            <label for="dui" class="form-label">DUI</label>
            <input type="text" class="form-control" id="dui" name="dui" placeholder="Ingresa tu DUI" maxlength="9" pattern="\d{9}" title="DUI debe tener 9 dígitos" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Correo Electrónico</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Ingresa tu correo" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Ingresa tu contraseña" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Registrar</button>
    </form>
    <div class="text-center mt-3">
        <p>¿Ya tienes una cuenta? <a href="login.php">Inicia sesión</a></p>
    </div>
</div>

<footer class="footer">
    <div class="footer-content">
        <div class="footer-section mission">
            <h4>Misión</h4>
            <p>
                Nuestra misión es conectar a los restaurantes y organizaciones con comunidades vulnerables, facilitando la donación de alimentos y mejorando la calidad de vida de quienes más lo necesitan.
            </p>
        </div>
        <div class="footer-section vision">
            <h4>Visión</h4>
            <p>
                Ser el puente entre la solidaridad y la necesidad, promoviendo un impacto social duradero a través de la colaboración y el compromiso de todos los sectores de la sociedad.
            </p>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; 2024 CausaNoble | Todos los derechos reservados</p>
    </div>
</footer>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
