<?php
session_start();

// Verificar se o usuário está autenticado
if (!isset($_SESSION['username'])) {
    // Redirecionar para a página de login
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./assets/css/main.css">
    <title>Vídeos</title>
</head>

<body class="body">
<header class="header">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="./index.php"><img src="./assets/images/XVideos-Logo.png"></a>
            <!--<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>!-->
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

        <div class="row">
            <?php
            // Conexão com o banco de dados (substitua os valores pelos seus próprios)
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "tubevideos";

            $conn = new mysqli($servername, $username, $password, $dbname);

            // Verifica se ocorreu um erro na conexão
            if ($conn->connect_error) {
                die("Falha na conexão com o banco de dados: " . $conn->connect_error);
            }

            // Recupera os vídeos do banco de dados
            $sql = "SELECT * FROM videos";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $videoId = $row['id'];
                    $videoTitle = $row['title'];
                    $thumbnailPath = $row['thumbnail_path'];

                    echo '<div class="col-lg-4 col-md-6 mb-4">';
                    echo '<div class="card bg-transparent border-none">';
                    echo '<img src="uploads/thumbnails/' . $thumbnailPath . '" class="card-img-top" alt="Thumbnail">';
                    echo '<div class="card-body">';
                    echo '<h5 class="card-title text-light">' . $videoTitle . '</h5>';
                    echo '<a href="watch.php?video_id=' . $videoId . '" class="btn btn-primary mt-5 text-align-center">Assistir</a>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<p>Nenhum vídeo encontrado.</p>';
            }

            // Fecha a conexão com o banco de dados
            $conn->close();
            ?>
        </div>

        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
            crossorigin="anonymous"></script>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    </div>
</body>

</html>