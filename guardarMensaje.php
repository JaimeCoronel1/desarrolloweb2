

<?php 
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli('database-1.cnke6mmiomx5.us-east-2.rds.amazonaws.com', 'admin', '-Joz1lr:ebRFZRP2GFqU:}c2?7', 'desarrolloweb2');

if ($conn->connect_erro) {
    die("Conexión fallida: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $celular = $_POST['celular'];
    $correo = $_POST['correo'];
    $mensaje = $_POST['mensaje'];

    $stmt = $conn->prepare("INSERT INTO mensajes (nombre, apellido, celular, correo, mensaje) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $nombre, $apellido, $celular, $correo, $mensaje);
    
    if ($stmt->execute()) {
        $id_generado = $stmt->insert_id;
        // Generar el código QR
        require 'phpqrcode/qrlib.php';
        $data = "ID: $id_generado, Nombre: $nombre $apellido";
        $filename = "qr_$id_generado.png";
        QRcode::png($data, $filename);

        echo "<h3>Mensaje guardado con éxito!</h3>";
        echo "<img src='$filename' alt='Código QR'>";
    } else {
        echo "Error al guardar el mensaje.";
    }
}

$conn->close();
?>
