<?php

session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redireciona para a página de login se não estiver logado
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $videoDirectory = "uploads/videos/";
    $thumbnailDirectory = "uploads/thumbnails/";

    // Verificar se o diretório de vídeos existe
    if (!is_dir($videoDirectory)) {
        mkdir($videoDirectory, 0755, true);
    }

    // Verificar se o diretório de thumbnails existe
    if (!is_dir($thumbnailDirectory)) {
        mkdir($thumbnailDirectory, 0755, true);
    }

    // Verificar se foram enviados os arquivos de vídeo e thumbnail
    if (isset($_FILES["video"]) && isset($_FILES["thumbnail"])) {
        $videoName = $_FILES["video"]["name"];
        $thumbnailName = $_FILES["thumbnail"]["name"];

        $videoPath = $videoDirectory . $videoName;
        $thumbnailPath = $thumbnailDirectory . $thumbnailName;

        // Mover os arquivos para os diretórios de destino
        move_uploaded_file($_FILES["video"]["tmp_name"], $videoPath);
        move_uploaded_file($_FILES["thumbnail"]["tmp_name"], $thumbnailPath);

        // Obter a categoria selecionada
        $categoria = $_POST["categoria"];

        // Redirecionar para a página watch.php com o video_id como parâmetro
        $videoId = saveVideoToDatabase($videoName, $thumbnailName, $categoria);
        header("Location: watch.php?video_id=$videoId");
        exit();
    }
}

function saveVideoToDatabase($videoName, $thumbnailName, $categoria)
{
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "tubevideos";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Falha na conexão com o banco de dados: " . $conn->connect_error);
    }

    $title = $_POST["title"];

    $sql = "INSERT INTO videos (title, video_path, thumbnail_path, categoria) VALUES ('$title', '$videoName', '$thumbnailName', '$categoria')";

    if ($conn->query($sql) === TRUE) {
        $videoId = $conn->insert_id;
        $conn->close();
        return $videoId;
    } else {
        echo "Erro ao adicionar o vídeo: " . $conn->error;
        $conn->close();
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./assets/css/main.css">
    <title>Envio de Vídeo</title>
</head>
<body class="body">
<header class="header">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#"><img src="./assets/images/XVideos-Logo.png"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>

    <nav class="navbar navbar-expand-lg navbar-light bg-dark text-light">
        <div class="container">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCategories"
                aria-controls="navbarCategories" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse text-light text-center" id="navbarCategories">
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-light" href="./index.php" title="Início" id="categoriesDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-tag"></i> Categorias
                        </a>
                        <ul class="dropdown-menu bg-dark" aria-labelledby="categoriesDropdown">
                            <li><a class="dropdown-item text-light" href="./categoria.php">South-park</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-light" href="./categoria.php"> <i class="fas fa-play"></i> Melhores Vídeos</a>
                    </li>
                </ul>
            </div>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-light" href="#" id="accountDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user"></i> Conta
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end bg-dark" aria-labelledby="accountDropdown">
                            <?php
                                echo '<li><a class="dropdown-item text-light" href="profile.php">Minha Conta</a></li>';
                                echo '<li><a class="dropdown-item text-light" href="envio.php">Upload de Conteúdo</a></li>';
                                echo '<li><hr class="dropdown-divider"></li>';
                                echo '<li><a class="dropdown-item text-light" href="logout.php">Sair</a></li>';
                            ?>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>


    <div class="container mt-5">
        <h1>Envio de Vídeo</h1>

        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="video">Selecione um vídeo:</label>
                <input type="file" name="video" id="video" class="form-control-file">
            </div>
            <div class="form-group">
                <label for="thumbnail">Selecione uma thumbnail:</label>
                <input type="file" name="thumbnail" id="thumbnail" class="form-control-file">
            </div>
            <div class="form-group">
                <label for="title">Título do Vídeo:</label>
                <input type="text" name="title" id="title" class="form-control">
            </div>
            <div class="form-group">
                <label for="categoria">Categoria:</label>
                <select name="categoria" id="categoria" class="form-control">
                    <option value="southpark">South Park</option>
                    <!-- Adicione outras opções de categoria aqui, se necessário -->
                </select>
            </div>
            <button type="submit" class="btn btn-outline-danger px-5 text-align-center">Enviar</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
            crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</body>
</html>
