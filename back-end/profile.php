<?php
session_start();

// Verificar se o usuário está autenticado
if (!isset($_SESSION['username'])) {
    // Redirecionar para a página de login
    header("Location: login.php");
    exit;
}

// Simulação dos dados do usuário
$username = $_SESSION['username'];
$description = "Sou um usuário do site";
$profilePicture = "profile/pictures/default.jpg";

// Verificar se o formulário de atualização de perfil foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    // Verificar se o arquivo de foto de perfil foi enviado com sucesso
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        // Diretório onde as fotos de perfil serão armazenadas
        $uploadDir = 'profile/pictures/';

        // Nome do arquivo de foto de perfil
        $profilePictureName = $_FILES['profile_picture']['name'];

        // Verificar o tipo de arquivo
        $allowedExtensions = ['jpeg', 'jpg', 'png', 'bmp'];
        $fileExtension = strtolower(pathinfo($profilePictureName, PATHINFO_EXTENSION));
        if (in_array($fileExtension, $allowedExtensions)) {
            // Gerar um nome único para o arquivo
            $profilePictureName = uniqid('profile_') . '.' . $fileExtension;

            // Caminho completo do arquivo de foto de perfil
            $profilePicturePath = $uploadDir . $profilePictureName;

            // Mover o arquivo para o diretório de fotos de perfil
            move_uploaded_file($_FILES['profile_picture']['tmp_name'], $profilePicturePath);

            // Atualizar o caminho da foto de perfil no banco de dados ou variável de sessão, se necessário
            $profilePicture = $profilePicturePath;

            header("Location: index.php");
        } else {
            // Exibir mensagem de erro se o tipo de arquivo não for permitido
            $error_message = "Tipo de arquivo não suportado. Apenas imagens JPEG, PNG e BMP são permitidas.";
        }
    } else {
        // Exibir mensagem de erro se o arquivo de foto de perfil não foi enviado
        $error_message = "O arquivo de foto de perfil não foi enviado ou ocorreu um erro durante o envio.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="./assets/css/main.css">
    <title>Perfil</title>
</head>

<body class="body">
    <div class="container mt-5">
        <h1 class="text-center">Perfil</h1>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <?php if (isset($error_message)) { ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error_message; ?>
                    </div>
                <?php } ?>

                <form action="profile.php" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="username">Nome de usuário:</label>
                        <input type="text" class="form-control" id="username" name="username" value="<?php echo $username; ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="description">Descrição:</label>
                        <textarea class="form-control" id="description" name="description" rows="3"><?php echo $description; ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="profile_picture">Foto de perfil:</label>
                        <input type="file" class="form-control-file" id="profile_picture" name="profile_picture">
                    </div>
                    <button type="submit" class="btn btn-outline-danger" name="update_profile">Atualizar Perfil</button>
                </form>

                <div class="mt-4">
                    <h4>Foto de perfil atual:</h4>
                    <img src="<?php echo $profilePicture; ?>" alt="Foto de Perfil" class="img-thumbnail">
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
