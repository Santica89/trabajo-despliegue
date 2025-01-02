<?php
// Configuración de la base de datos
$database = "mydb";
$user = "myuser";
$password = "password";
$host = "mysql";

try {
    // Conexión a la base de datos
    $connection = new PDO("mysql:host={$host};dbname={$database};charset=utf8", $user, $password);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Si se hace clic en el botón Insertar
    if (isset($_POST['insert'])) {
        $nombre = $_POST['nombre'];
        $email = $_POST['email'];
        $query = "INSERT INTO usuarios (nombre, email) VALUES ('{$nombre}', '{$email}')";
        $connection->exec($query);

        // Redirige después de insertar
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }

    // Si se hace clic en el botón Modificar
    if (isset($_POST['update'])) {
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $email = $_POST['email'];
        $query = "UPDATE usuarios SET nombre = '{$nombre}', email = '{$email}' WHERE id = {$id}";
        $connection->exec($query);

        // Redirige después de actualizar
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }

    // Si se hace clic en el botón Borrar
    if (isset($_POST['delete_user'])) {
        $id = $_POST['id'];
        $query = "DELETE FROM usuarios WHERE id = {$id}";
        $connection->exec($query);

        // Redirige después de borrar
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }

    // Mostrar los usuarios
    $query = $connection->query("SELECT * FROM usuarios");
    $usuarios = $query->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "<div class='alert alert-danger mt-4'>Error al conectar con la base de datos: " . htmlspecialchars($e->getMessage()) . "</div>";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>APP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1 class="text-center">¡Hola mundo desde Docker!</h1>
        <p class="text-center"><?php echo 'Esta corriendo PHP, versión: ' . phpversion(); ?></p>

        <!-- Formulario para insertar datos -->
        <div class="card mt-4">
            <div class="card-header">
                <h2>Agregar Nuevo Usuario</h2>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre:</label>
                        <input type="text" id="nombre" name="nombre" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>
                    <button type="submit" name="insert" class="btn btn-primary">Insertar</button>
                </form>
            </div>
        </div>

        <?php if (!empty($usuarios)) { ?>
            <table class="table table-striped mt-4">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $usuario): ?>
                        <tr>
                            <td><?= $usuario['id'] ?></td>
                            <td><?= htmlspecialchars($usuario['nombre']) ?></td>
                            <td><?= htmlspecialchars($usuario['email']) ?></td>
                            <td>
                                <form method="POST" style="display:inline">
                                    <input type="hidden" name="id" value="<?= $usuario['id'] ?>">
                                    <input type="text" name="nombre" value="<?= htmlspecialchars($usuario['nombre']) ?>" class="form-control mb-2" required>
                                    <input type="email" name="email" value="<?= htmlspecialchars($usuario['email']) ?>" class="form-control mb-2" required>
                                    <button type="submit" name="update" class="btn btn-info btn-sm">Modificar</button>
                                </form>
                                <form method="POST" style="display:inline">
                                    <input type="hidden" name="id" value="<?= $usuario['id'] ?>">
                                    <button type="submit" name="delete_user" class="btn btn-danger btn-sm">Borrar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php } else { ?>
            <div class="alert alert-warning mt-4">No hay usuarios registrados.</div>
        <?php } ?>
    </div>
</body>
</html>
