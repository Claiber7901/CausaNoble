<?php
session_start(); 

if (!isset($_SESSION['user_name'])) {
    header('Location: login.php?message=not_logged_in');
    exit();
}

include 'db_connection.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>CausaNoble</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/carousel.css" rel="stylesheet">
    <link href="css/footer.css" rel="stylesheet">

    <script src="https://www.paypal.com/sdk/js?client-id=ARw78kQktKyUoVYCVj9-vqYoDNWy289g7EJVMxBOZ5SPMOv2jbbglstaz4Dy6KWOYqtzNNAsoQyY286O&currency=USD"></script>
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

<div class="mt-5 pt-5"></div>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-6 text-center">
            <h2>Haz tu donación</h2>
            <div id="paypal-button-container" class="mt-4"></div>
        </div>
        <div class="col-md-6">
            <h2>Cantidad a donar</h2>
            <form id="donation-form">
                <div class="mb-3">
                    <label for="donationAmount" class="form-label">Ingresa el monto ($)</label>
                    <input type="number" class="form-control" id="donationAmount" placeholder="0.00" step="0.01" min="1" required>
                </div>
                <button type="submit" class="btn btn-primary">Confirmar cantidad</button>
            </form>
        </div>
    </div>
</div>

<script>
    let donationAmount = 1.00;

    function renderPayPalButtons() {
        const paypalButtonContainer = document.getElementById('paypal-button-container');
        paypalButtonContainer.innerHTML = '';

        paypal.Buttons({
            style: {
                color: 'blue',
                shape: 'pill',
                label: 'pay'
            },
            createOrder: function(data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: donationAmount  
                        }
                    }]
                });
            },
            onApprove: function(data, actions) {
                return actions.order.capture().then(function(detalles) {
                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', 'guardar_donacion.php', true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.onreadystatechange = function () {
                        if (xhr.readyState === 4 && xhr.status === 200) {
                            window.location.href = "completado.php?amount=" + donationAmount;
                        }
                    };
                    const params = "amount=" + donationAmount + "&user_id=<?= $_SESSION['user_id']; ?>"; 
                    xhr.send(params);
                });
            },
            onCancel: function(data) {
                alert("Pago cancelado");
                console.log(data);
            }
        }).render(paypalButtonContainer);
    }

    renderPayPalButtons();

    document.getElementById('donation-form').addEventListener('submit', function(e) {
        e.preventDefault();
        donationAmount = document.getElementById('donationAmount').value;
        alert("Cantidad de donación actualizada a $" + donationAmount);
        renderPayPalButtons();
    });
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



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
