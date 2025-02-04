<?php
session_start();

define('USERNAME', 'valdidev');
define('PASSWORD', 'valdidev');

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

if (isset($_POST['username']) && isset($_POST['password'])) {
    if ($_POST['username'] === USERNAME && $_POST['password'] === PASSWORD) {
        $_SESSION['logged_in'] = true;
    } else {
        $login_error = "Usuario o contraseña inválidos.";
    }
}

function getSubfolders($directory)
{
    $subfolders = [];
    if (is_dir($directory)) {
        $dirs = scandir($directory);
        foreach ($dirs as $dir) {
            if ($dir !== '.' && $dir !== '..') {
                $fullPath = $directory . DIRECTORY_SEPARATOR . $dir;
                if (is_dir($fullPath)) {
                    $subfolders[] = $dir;
                }
            }
        }
    }
    return $subfolders;
}

function getImages($directory)
{
    $images = [];
    if (is_dir($directory)) {
        $files = scandir($directory);
        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..') {
                $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                if (in_array($ext, ['png', 'jpg', 'jpeg', 'gif', 'webp'])) {
                    $images[] = $file;
                }
            }
        }
    }
    return $images;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>valdidev | seagreen</title>
    <!-- <link rel="icon" type="image/svg+xml" href="https://valdidev.com/static/logo/seagreen.svg" /> -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap');

        /* GENERALES */
        body {
            margin: 0;
            font-family: Ubuntu, Arial, sans-serif;
            background: #f5f5f5;
        }

        header {
            background-color: seagreen;
            color: white;
            padding: 1rem;
            text-align: center;
        }

        /* NAV */
        nav {
            background: #2e8b57;
            /* Seagreen variant */
            color: white;
            padding: 1rem;
            display: flex;
            flex-direction: row;
            flex-wrap: nowrap;
            justify-content: space-between;
            align-items: center;
            align-content: stretch;
        }

        nav a,
        nav span {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }

        /* LOGIN */
        .login-container {
            max-width: 350px;
            margin: 120px auto;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 30px;
            text-align: center;
        }

        .login-container h2 {
            margin-top: 0;
            margin-bottom: 1rem;
            color: seagreen;
            font-size: 1.8rem;
        }

        .login-container input[type="text"],
        .login-container input[type="password"] {
            display: block;
            width: calc(100% - 20px);
            padding: 10px;
            margin: 10px auto;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
        }

        .login-container input[type="submit"] {
            background: seagreen;
            color: #fff;
            border: none;
            padding: 10px;
            cursor: pointer;
            width: 100%;
            font-size: 1.1rem;
            border-radius: 5px;
            transition: background 0.3s ease;
        }

        .login-container input[type="submit"]:hover {
            background: #276749;
        }

        .login-error {
            color: red;
            margin-bottom: 1rem;
            font-weight: bold;
        }

        .main-container {
            display: flex;
            min-height: calc(100vh - 124px);
        }

        .left-pane {
            width: 20%;
            background: seagreen;
            color: white;
            border-right: 1px solid #ccc;
            padding: 1rem;
        }

        .left-pane h3 {
            margin-top: 0;
            color: seagreen;
        }

        .folder-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .folder-list li {
            margin: 0.5rem 0;
            padding: 10px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        .folder-list a {
            color: #333;
            text-decoration: none;
            font-weight: bold;

            width: 100%;
        }

        .folder-list a.selected {
            color: white;

        }

        .right-pane {
            width: 80%;
            padding: 1rem;
        }

        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .grid-container img {
            width: 100%;
            cursor: pointer;
            border: 2px solid #fff;
            transition: border 0.3s ease;
        }

        .grid-container img:hover {
            border: 2px solid seagreen;
        }

        /* VENTANA MODAL */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .modal-content {
            position: relative;
            max-width: 90%;
            max-height: 90%;
            background: #fff;
            border-radius: 4px;
            padding: 1rem;
        }

        .modal-content img {
            max-width: 100%;
            max-height: 80vh;
        }

        .close-btn {
            position: absolute;
            top: -10px;
            right: -10px;
            background: seagreen;
            color: white;
            border: none;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            cursor: pointer;
            font-weight: bold;
        }

        h1 {
            display: flex;
            flex-direction: row;
            flex-wrap: nowrap;
            justify-content: center;
            align-items: center;
            align-content: stretch;

        }

        h1 img {
            width: 60px;
        }
    </style>
    <script>
        function abrirModal(imgSrc) {
            var modalOverlay = document.getElementById('modalOverlay');
            var modalImage = document.getElementById('modalImage');
            modalImage.src = imgSrc;
            modalOverlay.style.display = 'flex';
        }

        function cerrarModal() {
            var modalOverlay = document.getElementById('modalOverlay');
            var modalImage = document.getElementById('modalImage');
            modalImage.src = '';
            modalOverlay.style.display = 'none';
        }
    </script>
</head>

<body>
    <?php if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true): ?>
        <div class="login-container">
            <h2>Login</h2>
            <?php if (isset($login_error)): ?>
                <div class="login-error"><?php echo $login_error; ?></div>
            <?php endif; ?>
            <form method="post" action="">
                <input type="text" name="username" placeholder="Usuario" required />
                <input type="password" name="password" placeholder="Contraseña" required />
                <input type="submit" value="Entrar" />
            </form>
        </div>
    <?php else: ?>

        <nav>
            <h1>
                <img src="https://valdidev.com/static/logo-seagreen.png" alt="Logo">
            </h1>
            <span>Usuario: <?php echo USERNAME; ?></span>
            <a href="?action=logout">Cerrar Sesión</a>
        </nav>

        <div class="main-container">
            <div class="left-pane">
                <h3>VirtualHosts</h3>
                <ul class="folder-list">
                    <?php
                    $subfolders = getSubfolders('graficas');
                    $currentSubfolder = isset($_GET['subfolder']) ? $_GET['subfolder'] : '';
                    foreach ($subfolders as $sf):
                        $selectedClass = ($sf === $currentSubfolder) ? 'selected' : '';
                        $url = $_SERVER['PHP_SELF'] . '?subfolder=' . urlencode($sf);
                        echo "<li><a class=\"$selectedClass\" href=\"$url\">$sf</a></li>";
                    endforeach;
                    ?>
                </ul>
            </div>

            <div class="right-pane">
                <?php if ($currentSubfolder): ?>
                    <?php
                    $images = getImages('graficas' . DIRECTORY_SEPARATOR . $currentSubfolder);
                    if (count($images) > 0):
                    ?>
                        <div class="grid-container">
                            <?php foreach ($images as $img): ?>
                                <?php $imgPath = 'graficas/' . $currentSubfolder . '/' . $img; ?>
                                <img
                                    src="<?php echo $imgPath; ?>"
                                    alt="<?php echo $img; ?>"
                                    onclick="abrirModal('<?php echo $imgPath; ?>')" />
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p>No se encontraron imágenes en la carpeta <strong><?php echo htmlspecialchars($currentSubfolder); ?></strong>.</p>
                    <?php endif; ?>
                <?php else: ?>
                    <p>Por favor, selecciona una carpeta de la lista</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="modal-overlay" id="modalOverlay" onclick="cerrarModal()">
            <div class="modal-content" onclick="event.stopPropagation()">
                <button class="close-btn" onclick="cerrarModal()">×</button>
                <img id="modalImage" src="" alt="Imagen grande" />
            </div>
        </div>

    <?php endif; ?>
</body>

</html>