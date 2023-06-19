<?php
session_start();

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

// Processa o registro
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Verifica se o usuário já existe
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Usuário já existe, exibe uma mensagem de erro
        echo "Usuário já existe. Escolha um nome de usuário diferente.";
    } else {
        // Insere o novo usuário no banco de dados
        $insertSql = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
        if ($conn->query($insertSql) === TRUE) {
            // Registro bem-sucedido, obter o ID do usuário inserido
            $user_id = $conn->insert_id;

            // Define a sessão de usuário
            $_SESSION['username'] = $username;
            $_SESSION['user_id'] = $user['user_id'];
            header("Location: index.php"); // Redireciona para a página de upload
        } else {
            echo "Erro ao registrar o usuário: " . $conn->error;
        }
    }
}

// Fecha a conexão com o banco de dados
$conn->close();
?>
