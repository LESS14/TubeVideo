<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        .video-container {
            position: relative;
            padding-bottom: 56.25%;
            height: 0;
            overflow: hidden;
        }

        .video-container video {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .video-thumbnail {
            display: block;
            width: 100%;
            max-width: 300px;
            height: auto;
            margin-bottom: 10px;
        }
    </style>
    <title>Vídeos por Categoria</title>
</head>
<body>
    <div class="container mt-5">
        <h1>Vídeos por Categoria</h1>

        <div class="row">
            <div class="col-lg-8 offset-lg-2 col-md-10 offset-md-1">
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

                // Obtém o valor da categoria da URL
                $categoria = $_GET['categoria'];

                // Modifique a consulta SQL para buscar apenas os vídeos com a categoria fornecida
                $sql = "SELECT * FROM videos WHERE category = '$categoria'";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $videoId = $row['id'];
                        $videoTitle = $row['title'];
                        $thumbnailPath = $row['thumbnail_path'];

                        // Exibe as informações do vídeo
                        echo '<div class="video-container">';
                        echo '<a href="watch.php?video_id=' . $videoId . '">';
                        echo '<img src="' . $thumbnailPath . '" alt="' . $videoTitle . '" class="video-thumbnail">';
                        echo '<h3>' . $videoTitle . '</h3>';
                        echo '</a>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>Nenhum vídeo encontrado para essa categoria.</p>';
                }

                // Fecha a conexão com o banco de dados
                $conn->close();
                ?>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    </div>
</body>
</html>
