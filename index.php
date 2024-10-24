<?php
session_start();
include 'db_connection.php';

$query = "SELECT NombreColonia, UbicacionColonia FROM Colonia LIMIT 3";
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
    <meta name="author" content="CausaNoble">
    <title>CausaNoble · Carrusel de Colonias</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/carousel.css" rel="stylesheet">
    <link href="css/desingindex.css" rel="stylesheet">  

</head>
<body>
<header>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">CausaNoble</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
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

<main>
    <!-- Carrusel de colonias -->
    <div id="myCarousel" class="carousel slide" data-bs-ride="carousel" style="z-index: 1;">
        <div class="carousel-indicators">
            <?php foreach ($colonias as $index => $colonia): ?>
                <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="<?= $index; ?>"
                        class="<?= $index === 0 ? 'active' : ''; ?>"
                        aria-current="<?= $index === 0 ? 'true' : 'false'; ?>"
                        aria-label="Slide <?= $index + 1; ?>"></button>
            <?php endforeach; ?>
        </div>

        <div class="carousel-inner">
            <?php foreach ($colonias as $index => $colonia): ?>
                <div class="carousel-item <?= $index === 0 ? 'active' : ''; ?>">
                    <img src="images/<?= $index + 1; ?>/2.jpg" class="d-block w-100" 
                         alt="Imagen Colonia <?= $index + 1; ?>">

                    <div class="carousel-caption d-flex flex-column align-items-center justify-content-center" 
                         style="min-height: 60vh; gap: 1rem;">
                         
                        <!-- Recuadro superior: Descripción general -->
                        <div class="bg-dark bg-opacity-75 text-white p-4 rounded shadow-sm text-center w-75">
                            <?php if ($index === 0): ?>
                                <h2 class="display-5 mb-2">¿Qué es CausaNoble?</h2>
                                <p class="lead">
                                    CausaNoble es una plataforma dedicada a recolectar donaciones para apoyar colonias 
                                    afectadas por la hambruna. A través de esta plataforma, puedes mejorar la calidad de vida 
                                    de comunidades vulnerables proporcionando alimentos y recursos esenciales.
                                </p>
                            <?php elseif ($index === 1): ?>
                                <h2 class="display-5 mb-2">Descubre las Colonias</h2>
                                <p class="lead">
                                    Conoce las colonias que forman parte de nuestra causa y descubre cómo puedes mejorar 
                                    la vida de estas comunidades.
                                </p>
                            <?php elseif ($index === 2): ?>
                                <h2 class="display-5 mb-2">Reportes de las Colonias</h2>
                                <p class="lead">
                                    Mantente informado sobre los avances y resultados de tu apoyo consultando nuestros reportes.
                                </p>
                            <?php endif; ?>
                        </div>

                        <!-- Recuadro inferior: Datos específicos de la colonia -->
                        <div class="bg-dark bg-opacity-75 text-white p-3 rounded shadow-sm text-center w-50">
                            <h5 class="text-uppercase"><?= htmlspecialchars($colonia['NombreColonia']); ?></h5>
                            <p class="lead"><?= htmlspecialchars($colonia['UbicacionColonia']); ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#myCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#myCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</main>



<footer class="footer">
    <div class="footer-content">
        <div class="footer-section mission">
            <h4>Misión</h4>
            <p>
                Nuestra misión es conectar a los restaurantes y organizaciones con comunidades vulnerables, facilitando la donación de alimentos.
            </p>
        </div>
        <div class="footer-section vision">
            <h4>Visión</h4>
            <p>
                Ser el puente entre la solidaridad y la necesidad, promoviendo un impacto social duradero.
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
