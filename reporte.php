<?php
session_start();
include 'db_connection.php';

// Consulta para obtener los reportes
$query = "SELECT r.ReporteAlimentoID, c.NombreColonia, c.UbicacionColonia, r.FechaEntrega, 
                 r.AlimentosDonados, r.CosteAlimentos, c.DescripcionColonia 
          FROM ReporteCompraAlimento r
          JOIN Colonia c ON r.ColoniaDonadaID = c.ColoniaID";
$result = mysqli_query($conn, $query);

$reportes = [];
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $reportes[] = $row;
    }
} else {
    echo "No se encontraron reportes.";
}
?>

<!doctype html>
<html lang="es" data-bs-theme="auto">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reporte Donaciones - CausaNoble</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/carousel.css" rel="stylesheet">
    <link href="css/stylereporte.css" rel="stylesheet">
    <link href="css/footer.css" rel="stylesheet">
    

</head>
<body>
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

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-3">
                <div class="report-section">
                    <h5 class="mb-3">Reportes Recientes</h5>
                    <ul class="list-group recent-reports">
                        <?php foreach ($reportes as $reporte): ?>
                            <li class="list-group-item">
                                <a href="#" onclick="loadReport(<?php echo $reporte['ReporteAlimentoID']; ?>)">
                                    <?php echo $reporte['NombreColonia']; ?> - Envío de alimentos
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <div class="col-md-6" id="report-detail">
                <div class="report-section">
                    <h5>Selecciona un reporte para ver detalles</h5>
                </div>
            </div>

            <div class="col-md-3" id="report-text">
                <div class="report-section">
                    <h5 class="text-end">Fecha</h5>
                    <p></p>
                </div>
            </div>
        </div>
    </div>

    <script>
        const reports = <?php echo json_encode($reportes); ?>;

        function loadReport(reportID) {
            const report = reports.find(r => r.ReporteAlimentoID == reportID);
            const index = reports.indexOf(report); // Índice del reporte

            const imgStart = 6 + (index * 3); // Primer número de imagen para cada reporte

            document.getElementById("report-detail").innerHTML = `
                <div class="report-section">
                    <h5>${report.NombreColonia} - Envío de alimentos</h5>
                    <h6>Fecha: ${new Date(report.FechaEntrega).toLocaleDateString()}</h6>
                    <p><strong>Alimentos donados:</strong> ${report.AlimentosDonados}</p>
                    <p><strong>Coste total:</strong> ${report.CosteAlimentos}</p>

                    <div id="carousel-${reportID}" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            ${generateCarouselImages(imgStart)}
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carousel-${reportID}" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Anterior</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carousel-${reportID}" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Siguiente</span>
                        </button>
                    </div>
                </div>
            `;

            document.getElementById("report-text").innerHTML = `
                <div class="report-section">
                    <h5 class="text-end">${new Date(report.FechaEntrega).toLocaleDateString()}</h5>
                </div>
            `;
        }

        function generateCarouselImages(start) {
            let imagesHtml = '';
            for (let i = 0; i < 3; i++) {
                const imgNumber = start + i;
                imagesHtml += `
                    <div class="carousel-item ${i === 0 ? 'active' : ''}">
                        <img src="images/6/${imgNumber}.jpg" class="d-block w-100" alt="Imagen ${imgNumber}">
                    </div>
                `;
            }
            return imagesHtml;
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




    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
