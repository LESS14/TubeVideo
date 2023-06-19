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

// Processa o login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Verifica as credenciais do usuário
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        // Login bem-sucedido, obter o ID do usuário
        $user = $result->fetch_assoc();
        $user_id = $user['user_id'];

        // Define a sessão de usuário
        $_SESSION['username'] = $username;
        $_SESSION['user_id'] = $user['user_id'];
        header("Location: index.php"); // Redireciona para a página de upload
    } else {
        // Login inválido, exibe uma mensagem de erro
        echo "Usuário ou senha inválidos.";
    }
}

// Fecha a conexão com o banco de dados
$conn->close();
?>
