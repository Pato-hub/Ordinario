<?php
session_start();
include('conexion.php');

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}

$id = $_SESSION['usuario_id']; 

// Consultar
$consulta = "SELECT * FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($consulta);
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();
$usuario = $resultado->fetch_assoc();

// Procesar  actualización
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $edad = $_POST['edad'];
    $sexo = $_POST['sexo'];
    $direccion = $_POST['direccion'];
    $ciudad = $_POST['ciudad'];
    $cp = $_POST['cp'];

    // Verificar si 
    if (isset($_FILES['fotografia']) && $_FILES['fotografia']['error'] === UPLOAD_ERR_OK) {
        $fotografiaAnterior = $usuario['fotografia'];
        $carpetaFotografias = __DIR__ . '/fotografias/';
        if (file_exists($carpetaFotografias . $fotografiaAnterior)) {
            unlink($carpetaFotografias . $fotografiaAnterior); 
        }

        $imagen = $_FILES['fotografia'];
        $nombreImagen = md5(uniqid(rand(), true)) . '.jpg';
        if (move_uploaded_file($imagen['tmp_name'], $carpetaFotografias . $nombreImagen)) {
           
            $fotografia = $nombreImagen;
        } else {
            echo "Error al subir la imagen.";
            exit();
        }
    } else {
        
        $fotografia = $usuario['fotografia'];
    }

    // actualizar los datos
    $update_query = "UPDATE usuarios SET nombre = ?, apellidos = ?, edad = ?, sexo = ?, direccion = ?, ciudad = ?, cp = ?, fotografia = ? WHERE id = ?";
    $stmt_update = $conn->prepare($update_query);
    $stmt_update->bind_param("ssisssssi", $nombre, $apellidos, $edad, $sexo, $direccion, $ciudad, $cp, $fotografia, $id);

    if ($stmt_update->execute()) {
        echo "Datos actualizados con éxito.";
        header('Location: consultar.php'); 
        exit();
    } else {
        echo "Hubo un error al actualizar los datos.";
    }

    $stmt_update->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Datos</title>
    <link rel="stylesheet" href="styles2.css">
</head>
<body>
    <div class="container">
        <header class="banner">
            <h1>Actualizar Mis Datos</h1>
        </header>

        <nav class="navbar">
            <ul>
                <li><a href="index.php">Inicio</a></li>
                <li><a href="login.php">Login</a></li>
                <li><a href="registrar.php">Registrar</a></li>
                <li><a href="consultar.php">Consultar</a></li>
                <li><a href="#">Nosotros</a></li>
            </ul>
        </nav>

        <main class="tab-registrar">
            <div class="registrar-form">
                <h2>Actualizar Información</h2>

                <form action="actualizar.php" method="POST" enctype="multipart/form-data">

                <div class="form-grid">
                <div class="form-item">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" required>
                    </div>

                    <div class="form-item">
                    <label for="apellidos">Apellidos:</label>
                    <input type="text" id="apellidos" name="apellidos" value="<?php echo htmlspecialchars($usuario['apellidos']); ?>" required>
                    </div>

                    <div class="form-item">
                    <label for="edad">Edad:</label>
                    <input type="number" id="edad" name="edad" value="<?php echo htmlspecialchars($usuario['edad']); ?>" required>
                    </div>

                    <div class="form-item">
                    <label for="sexo">Sexo:</label>
                    <select id="sexo" name="sexo" required>
                        <option value="Masculino" <?php echo ($usuario['sexo'] == 'Masculino') ? 'selected' : ''; ?>>Masculino</option>
                        <option value="Femenino" <?php echo ($usuario['sexo'] == 'Femenino') ? 'selected' : ''; ?>>Femenino</option>
                    </select>
                    </div>

                    <div class="form-item">
                    <label for="direccion">Dirección:</label>
                    <input type="text" id="direccion" name="direccion" value="<?php echo htmlspecialchars($usuario['direccion']); ?>" required>
                    </div>

                    <div class="form-item">
                    <label for="ciudad">Ciudad:</label>
                    <input type="text" id="ciudad" name="ciudad" value="<?php echo htmlspecialchars($usuario['ciudad']); ?>" required>
                    </div>

                    <div class="form-item">
                    <label for="cp">C.P.:</label>
                    <input type="text" id="cp" name="cp" value="<?php echo htmlspecialchars($usuario['cp']); ?>" required>
                    </div>

                    <div class="form-item">
                    <label for="fotografia">Fotografía:</label>
                    <input type="file" id="fotografia" name="fotografia" accept="image/*">
                    </div>
                    <button type="submit" id="actualizar">Actualizar</button>
                </form>
            </div>
        </main>

        <footer class="footer">
            <p>&copy; 2024 - Todos los derechos reservados</p>
        </footer>
    </div>
</body>
</html>
