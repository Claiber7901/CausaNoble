<?php
session_start();
require 'C:\xampp\htdocs\CausaNoble\vendor\autoload.php'; // Autoload de FPDF
include 'db_connection.php'; // Conexión a la base de datos

// 1. Obtener el último ID de donación
$query_last_donacion = "SELECT DonacionID FROM donacion ORDER BY FechaDonacion DESC LIMIT 1";
$result_last_donacion = $conn->query($query_last_donacion);

if ($result_last_donacion->num_rows > 0) {
    $row = $result_last_donacion->fetch_assoc();
    $donacion_id = $row['DonacionID'];
} else {
    die("Error: No se encontró ninguna donación.");
}

// 2. Obtener los detalles de la donación y usuario
$query = "
    SELECT d.CantidadDonacion, d.FechaDonacion, u.Nombre, u.Apellido, u.Email, u.DUI 
    FROM donacion d
    JOIN usuario u ON d.UsuarioID = u.UsuarioID
    WHERE d.DonacionID = ?
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $donacion_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Error: No se encontraron datos para la donación proporcionada.");
}

$datos = $result->fetch_assoc();
$cantidad = $datos['CantidadDonacion'];
$fecha = $datos['FechaDonacion'];
$nombre = $datos['Nombre'];
$apellido = $datos['Apellido'];
$email = $datos['Email'];
$dui = $datos['DUI'];

// 3. Cálculo del total
$total = $cantidad;

// 4. Crear el PDF con FPDF
require 'C:\xampp\htdocs\CausaNoble\vendor\setasign\fpdf\fpdf.php'; // Asegúrate de usar el path correcto

class PDF extends FPDF {
    function Header() {
        // Agregar logo y título
        $this->Image('C:\xampp\htdocs\CausaNoble\images\logo.png', 10, 8, 30); // Ajusta la ruta si es necesario
        $this->SetFont('Arial', 'B', 20);
        $this->Cell(0, 15, utf8_decode('Factura de Donación'), 0, 1, 'C');
        $this->Ln(10);
    }

    function Footer() {
        // Pie de página con mensaje y contacto
        $this->SetY(-30);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, utf8_decode('Gracias por su generosidad y apoyo'), 0, 1, 'C');
        $this->Cell(0, 10, utf8_decode('Causa Noble - Todos los derechos reservados'), 0, 1, 'C');
        $this->Cell(0, 10, 'contacto@causanoble.org | +503 1234 5678 | www.causanoble.org', 0, 1, 'C');
    }

    function InvoiceDetails($nombre, $apellido, $email, $dui, $fecha) {
        // Mostrar detalles del donante
        $this->SetFont('Arial', '', 12);
        $this->Cell(0, 10, utf8_decode("Nombre: $nombre $apellido"), 0, 1);
        $this->Cell(0, 10, "Email: $email", 0, 1);
        $this->Cell(0, 10, "DUI: $dui", 0, 1);
        $this->Cell(0, 10, "Fecha: $fecha", 0, 1);
        $this->Ln(10);
    }

    function DonationTable($total) {
        // Encabezados de la tabla
        $this->SetFont('Arial', 'B', 12);
        $this->SetFillColor(230, 230, 230);
        $this->Cell(95, 10, utf8_decode('Descripción'), 1, 0, 'C', true);
        $this->Cell(95, 10, 'Monto', 1, 1, 'C', true);

        // Datos de la donación
        $this->SetFont('Arial', '', 12);
        $this->Cell(95, 10, utf8_decode('Donación'), 1);
        $this->Cell(95, 10, '$' . number_format($total, 2), 1, 1, 'R');

        // Total final
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(95, 10, 'Total', 1);
        $this->Cell(95, 10, '$' . number_format($total, 2), 1, 1, 'R');
    }
}

// 5. Generar el PDF
$pdf = new PDF();
$pdf->AddPage();

// Información del Donante
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, utf8_decode('Información del Donante'), 0, 1, 'L');
$pdf->InvoiceDetails($nombre, $apellido, $email, $dui, $fecha);

// Detalles de la Donación
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, utf8_decode('Detalles de la Donación'), 0, 1, 'L');
$pdf->Ln(5);

// Tabla de donación
$pdf->DonationTable($total);

// Descargar el PDF
$pdf->Output('D', "Factura_Donacion_$donacion_id.pdf");

$stmt->close();
$conn->close();
?>
