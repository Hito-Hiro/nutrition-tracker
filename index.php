<?php
session_start();

// Asegurar sesión
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}

$usuario = $_SESSION['usuario'];
$archivo_json = "/tmp/data/{$usuario}.json";
$datos = file_exists($archivo_json) ? json_decode(file_get_contents($archivo_json), true) : [];

// Eliminar
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    $datos = array_filter($datos, fn($item) => $item['id'] !== $id);
    file_put_contents($archivo_json, json_encode(array_values($datos), JSON_PRETTY_PRINT));
    header("Location: index.php");
    exit;
}

// Editar (prellenar formulario)
$editar = null;
if (isset($_GET['editar'])) {
    $id = $_GET['editar'];
    foreach ($datos as $item) {
        if ($item['id'] === $id) {
            $editar = $item;
            break;
        }
    }
}

// Crear o actualizar
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $id = $_POST['id'] ?? null;

    if ($id) {
        // Actualizar
        foreach ($datos as &$item) {
            if ($item['id'] === $id) {
                $item['titulo'] = $titulo;
                $item['descripcion'] = $descripcion;
                break;
            }
        }
    } else {
        // Crear
        $datos[] = [
            'id' => uniqid(),
            'titulo' => $titulo,
            'descripcion' => $descripcion,
        ];
    }

    file_put_contents($archivo_json, json_encode($datos, JSON_PRETTY_PRINT));
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nutrition Tracker</title>
    <link rel="stylesheet" type="text/css" href="css/tracker.css">
</head>
<body id="body">

<div id="banner">
    <img src="assets/ensalada.png" alt="ensalada con pasta" id="banner-img">
    <div class="banner-gradient"></div>
</div>

<h1>Tracker de <?php echo htmlspecialchars($usuario); ?> &#x1F32E;</h1>

<form method="post" class="registros">
    <input type="hidden" name="id" value="<?php echo $editar['id'] ?? ''; ?>">
    
    <label for="titulo">Platillo: </label><br>
    <input type="text" name="titulo" required value="<?php echo $editar['titulo'] ?? ''; ?>"><br><br>

    <label for="descripcion">Descripción:</label><br>
    <textarea name="descripcion" rows="1" required><?php echo $editar['descripcion'] ?? ''; ?></textarea><br><br>

    <button type="submit" id="guardar"><?php echo $editar ? 'Actualizar' : 'Guardar'; ?></button>
    <?php if ($editar): ?>
        <br><button type="button" onclick="window.location='index.php';" id="cancelar">Cancelar</button>
    <?php endif; ?>
</form>

<h2>Registros</h2>
<table class="tabla">
    <thead>
        <tr>
            <th>Platillo</th>
            <th>Descripción</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($datos as $item): ?>
        <tr>
            <td><?php echo htmlspecialchars($item['titulo']); ?></td>
            <td><?php echo htmlspecialchars($item['descripcion']); ?></td>
            <td>
                <a href="?editar=<?php echo $item['id']; ?>" id="editar">Editar</a> 
                <a href="?eliminar=<?php echo $item['id']; ?>" onclick="return confirm('¿Eliminar este registro?');" id="eliminar">Eliminar</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<br><h2>Sugerencias del día</h2>

    <seccion id="seccion">

        <div class="tarjeta">
            <img src="assets/pollo-a-la-plancha.jpg" alt="Pollo a la plancha" class="tarjeta-img">
            <div class="tarjeta-texto">
                <h3>Pollo a la plancha</h3>
            </div>
        </div>

        <br>
        <div class="tarjeta">
            <img src="assets/omelette-de-verduras.png" alt="Omelette de verduras" class="tarjeta-img">
            <div class="tarjeta-texto">
                <h3>Omelette de Verduras</h3>
            </div>
        </div>

        <br>
        <div class="tarjeta">
            <img src="assets/sopa-de-tortilla.jpg" alt="Sopa de tortilla" class="tarjeta-img">
            <div class="tarjeta-texto">
                <h3>Sopa de tortilla</h3>
            </div>
        </div>
    </seccion>

    <div id="youtube">
        <a href="https://youtu.be/7irhXk5sTko?si=F_oRWBeWvhiy2iHN" target="_blank">&#x1F517; Video sugerencia: 7 almuerzos saludables para toda la semana</a>
    </div>
    <footer id="cerrar-sesion">
        <form action="cerrar_sesion.php" method="post">
            <button type="sumbit" id="boton">Cerrar Sesión<button>
        </form>
    </footer>
</body>
</html>

