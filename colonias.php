<?php
session_start();
include 'db_connection.php';

$query = "SELECT NombreColonia, UbicacionColonia, DescripcionColonia FROM Colonia";
$result = mysqli_query($conn, $query);

$colonias = [];
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $colonias[] = $row;
    }
} else {
    echo "No se encontraron colonias.";
}
?>

<!doctype html>
<html lang="es" data-bs-theme="auto">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <title>CausaNoble</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/carousel.css" rel="stylesheet">
    <link href="css/footer.css" rel="stylesheet">
    <link href="css/colonias.css" rel="stylesheet">
</head>
<body>

<header>
<nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">CausaNoble</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav me-auto mb-2 mb-md-0">
                    <li class="nav-item"><a class="nav-link" href="reporte.php">Reporte Colonias</a></li>
                    <li class="nav-item"><a class="nav-link" href="colonias.php">Colonias</a></li>
                </ul>
                <div class="d-flex align-items-center">
                    <?php if (isset($_SESSION['user_name'])): ?>
                        <span class="navbar-text me-3 text-white">
                            Bienvenid@, <?= htmlspecialchars($_SESSION['user_name']); ?>
                        </span>
                        <a href="logout.php">
                            <button class="btn btn-outline-danger me-2" type="button">Cerrar sesión</button>
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

<header class="bg-light py-5">
    <div class="container">
        <div class="text-center">
            <br/>
            <h1 class="fw-light">Bienvenido a las Colonias</h1>
            <p class="lead text-muted">Una colección de colonias a ayudar.</p>
        </div>
    </div>
</header>

<div class="album py-5 bg-light">
    <div class="container">
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
            <?php foreach ($colonias as $index => $colonia): ?>
            <div class="col">
                <div class="card shadow-sm">
                    <!-- Contenedor para el carrusel con ajuste de imagen -->
                    <div class="card-img-container">
                        <div id="carousel<?php echo $index; ?>" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                <?php for ($i = 1; $i <= 3; $i++): ?>
                                    <div class="carousel-item <?php echo $i == 1 ? 'active' : ''; ?>">
                                        <img src="images/<?php echo $index + 1; ?>/<?php echo $i; ?>.jpg" class="d-block w-100" alt="Imagen <?php echo $i; ?>">
                                    </div>
                                <?php endfor; ?>
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#carousel<?php echo $index; ?>" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Anterior</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carousel<?php echo $index; ?>" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Siguiente</span>
                            </button>
                        </div>
                    </div>
                    <!-- Fin del carrusel -->
                    
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $colonia['NombreColonia']; ?></h5>
                        <p class="card-text"><?php echo $colonia['UbicacionColonia']; ?></p>
                        <p class="card-text"><?php echo $colonia['DescripcionColonia']; ?></p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
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



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
