<?php
session_start();
?>

<!doctype html>
<html lang="es" data-bs-theme="auto">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <title>CausaNoble · Pago Completado</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/footer.css" rel="stylesheet">
    <link href="css/completado.css" rel="stylesheet">
</head>
<body>
<header>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">CausaNoble</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                <span class="mx-2">|</span>
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
                            <button class="btn btn-outline-primary" type="button">Registrarte</button>
                        </a>
                        <a href="login.php" class="me-2">
                            <button class="btn btn-outline-danger" type="button">Iniciar sesión</button>
                        </a>
                    <?php endif; ?>
                    <a href="checkout_.php">
                        <button class="btn btn-success" type="button">Donar</button>
                    </a>
                </div>
            </div>
        </div>
    </nav>
</header>

<div class="container">
    <h1 class="mb-4">Pago Completado</h1>
    <p>¡Gracias por tu donación de <strong id="donation-amount"></strong>!</p>
    <a href="index.php" class="btn btn-primary btn-back">Volver a Inicio</a>
    <a href="factura_pdf.php?donacion_id=<?= isset($_GET['donacion_id']) ? htmlspecialchars($_GET['donacion_id']) : ''; ?>" class="btn btn-secondary btn-back mt-3">Descargar Factura (PDF)</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const urlParams = new URLSearchParams(window.location.search);
    const amount = urlParams.get('amount');

    if (amount) {
        document.getElementById('donation-amount').textContent = `$${parseFloat(amount).toFixed(2)}`;
    } else {
        document.getElementById('donation-amount').textContent = `$0.00`;
    }
</script>

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


</body>
</html>
