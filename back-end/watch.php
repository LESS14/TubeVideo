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

        .video-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: rgba(0, 0, 0, 0.5);
            cursor: pointer;
            transition: opacity 0.3s ease;
        }

        .video-overlay:hover {
            opacity: 0.7;
        }

        .play-icon {
            font-size: 60px;
            color: #fff;
        }

        .video-title {
            text-align: center;
            margin-top: 10px;
        }
    </style>
    <title>Assistir Vídeo</title>
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center">Assistir Vídeo</h1>

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

                // Verifica se o parâmetro video_id foi especificado
                if (!isset($_GET['video_id'])) {
                    echo "<p>Vídeo não encontrado.</p>";
                    exit();
                }

                $videoId = $_GET['video_id'];

                // Recupera as informações do vídeo do banco de dados
                $sql = "SELECT * FROM videos WHERE id = $videoId";
                $result = $conn->query($sql);

                // Verifica se o vídeo existe
                if ($result->num_rows === 0) {
                    echo "<p>Vídeo não encontrado.</p>";
                    exit();
                }

                $row = $result->fetch_assoc();
                $title = $row['title'];
                $videoPath = "uploads/videos/" . $row['video_path'];
                $thumbnailPath = "uploads/thumbnails/" . $row['thumbnail_path'];

                // Fecha a conexão com o banco de dados
                $conn->close();
                ?>

                <div class="video-container">
                    <video id="videoPlayer" controls>
                        <source src="<?php echo $videoPath; ?>" type="video/mp4">
                        Seu navegador não suporta o elemento de vídeo.
                    </video>
                    <div id="playButton" class="video-overlay">
                        <i class="fas fa-play play-icon"></i>
                    </div>
                </div>
                <h2 class="video-title"><?php echo $title; ?></h2>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

        <script>
            // Reproduz o vídeo quando o ícone de reprodução é clicado
            var videoPlayer = document.getElementById('videoPlayer');
            var playButton = document.getElementById('playButton');

            playButton.addEventListener('click', function() {
                videoPlayer.play();
                playButton.style.display = 'none';
            });
        </script>
    </div>
</body>

</html>
