<?php
session_start();

include 'db_connection.php';

if (isset($_GET['message']) && $_GET['message'] == 'not_logged_in') {
    echo "<script>alert('Debes iniciar sesión para hacer una donación');</script>";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM usuario WHERE Email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        if (password_verify($password, $user['Contraseña'])) {
            $_SESSION['user_id'] = $user['UsuarioID']; 
            $_SESSION['user_name'] = $user['Nombre']; 
            header('Location: index.php');
            exit;
        } else {
            $error = "Credenciales Incorrectas"; // si falla la contra
        }
    } else {
        $error = "Credenciales Incorrectas"; // si el correo no existe
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/carousel.css" rel="stylesheet">
    <link href="css/footer.css" rel="stylesheet">
    <link href="css/login.css" rel="stylesheet">
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
    <h2 class="text-center mb-4" style="margin-top: 80px;">Iniciar Sesión</h2>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= $error; ?></div>
    <?php endif; ?>
    <form method="POST" action="">
        <div class="mb-3">
            <label for="email" class="form-label">Correo Electrónico</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Ingresa tu correo" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Ingresa tu contraseña" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Iniciar Sesión</button>
    </form>
    <div class="text-center mt-3">
        <p>¿No tienes una cuenta? <a href="registro.php">Regístrate aquí</a></p>
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
