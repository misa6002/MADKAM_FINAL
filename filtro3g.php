<?php
require_once "session.php";
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conexion = mysqli_connect('localhost', 'daniel', 'madkam', 'madkam');
if (!$conexion) {
        die("Conexión fallida: " . mysqli_connect_error());
}

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
        header("Location: interfaz.php");
        exit;
}

$nombre = $_SESSION["nombre"];

$pagina = 6;
if (isset($_GET['pagina'])) {
    $pagina_actual = $_GET['pagina'];
} else {
    $pagina_actual = 1;
}
$offset = ($pagina_actual - 1) * $pagina;

$sql = "SELECT g.nombre, g.grabaciones, g.fecha FROM grabaciones g JOIN usuarios u ON u.id = g.usuario_id WHERE u.nombre = '$nombre' AND fecha >= NOW() - INTERVAL 1 MONTH ORDER BY grabaciones DESC LIMIT $pagina OFFSET $offset";
$result = mysqli_query($conexion, $sql);
if (!$result) {
        die("Error al ejecutar la consulta: " . mysqli_error($conexion));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="interfaz.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <title>Grabaciones</title>
     <link rel="icon" href="logo5.png" type="image/x-icon">
</head>
<body class="d-flex flex-column min-vh-100" style="background: #85858521;">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
	   <a class="navbar-brand"> <img src="logo5.png" width="40" height="40"></a>
          <a class="navbar-brand">MADKAM</a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="interfaz.php">Imagenes</a>
              </li>
              <li class="nav-item">
                 <button type="button" class="btn btn-secondary">
                <a class="nav-link active" aria-current="page" href="Grabaciones.php" style="padding-left: 1px;
                padding-right: 1px;
                padding-top: 1px;
                padding-bottom: 1px;">Grabaciones</a>
                </button>
              </li>
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="Streaming.php">Streaming</a>
                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-circle me-1" viewBox="0 0 16 16">
                          <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                          <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/>
                      </svg>
                      <h6><?php echo $nombre; ?></h6>
                  </a>
                  <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                      <li><a class="dropdown-item" href="reset-password.php">Restablecer Contraseña</a></li>
                      <li><a class="dropdown-item" href="logout.php">Cerrar Sesión</a></li>
                  </ul>
              </li>
              </li>
            </ul>
          </div>
        </div>
    </nav>
    <div class="col-fluid">
      <div class="row">
          <ul class="list-inline">
              <li class="list-inline-item">
                  <a class="nav-link active" aria-current="page" href="filtro1g.php">Filtro última hora</a>
              </li>
              <li class="list-inline-item">
                  <a class="nav-link active" aria-current="page" href="filtro2g.php">Filtro última semana</a>
              </li>
              <li class="list-inline-item">
                  <a class="nav-link active" aria-current="page" href="filtro3g.php">Filtro último mes</a>
              </li>
          </ul>
      </div>
  </div>
    <div class="container">
       <div class="row image-card pb-sm-5 pb-md-0">
<?php
        if (mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
            $grabacion = $row["grabaciones"];
            $fecha = $row["fecha"];
            $name = $row["nombre"];
?>
                <div class="col-sm-12 col-md-6 col-lg-6 image-card">
                <p>Fecha: <?php echo $fecha; ?></p>
                <video source src= <?php echo $grabacion; ?> controls width="480" height="360"></video>
                <p>Nombre: <?php echo $name; ?></p>
                </div>
                <?php
                        }
                        } else {
                        echo "No se encontraron grabaciones asociadas a este usuario.";
                        }
                ?>
        </div>
<?php
    $sql_total = "SELECT COUNT(*) as total FROM grabaciones g JOIN usuarios u ON u.id = g.usuario_id WHERE u.nombre = '$nombre' AND fecha >= NOW() - INTERVAL 1 MONTH ORDER BY grabaciones DESC LIMIT $pagina";
    $result_total = mysqli_query($conexion, $sql_total);
    $row_total = mysqli_fetch_assoc($result_total);
    $total_resultados = $row_total['total'];
    $total_paginas = ceil($total_resultados / $pagina);
?>
    <nav aria-label="Página de navegación">
      <ul class="pagination">
        <?php if ($pagina_actual > 1) : ?>
            <li class="page-item"><a class="page-link"
              href="?pagina=<?php echo ($pagina_actual - 1); ?>">Anterior</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $total_paginas; $i++) : ?>
            <li class="page-item"><a class="page-link"
              href="?pagina=<?php echo $i; ?>" <?php if ($pagina_actual == $i) echo 'class="active"'; ?>><?php echo $i; ?></a>
        <?php endfor; ?>

        <?php if ($pagina_actual < $total_paginas) : ?>
            <li class="page-item"><a class="page-link"
              href="?pagina=<?php echo ($pagina_actual + 1); ?>">Siguiente</a>
        <?php endif; ?>
      </ul>
    </nav>
    </div>
  </body>
  <footer class="text-center text-lg-start bg-body-tertiary text-muted mt-auto">
    <div class="row">
      <div class="text-center p-4" style="text-emphasis: none;">
        © 2024 MADKAM
    </div>
    </div>
</footer>
</html>
