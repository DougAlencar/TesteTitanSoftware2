<?php
session_start();

// Verificação de método POST para login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login = $_POST['login'];
    $senha = md5($_POST['senha']); // Criptografa a senha com MD5

    // Validação dos campos
    if (empty($login) || empty($senha)) {
        echo "Todos os campos são obrigatórios!";
    } elseif (!filter_var($login, FILTER_VALIDATE_EMAIL)) {
        echo "Formato de email inválido!";
    } else {
        // Conexão com o banco
        $conn = new mysqli('localhost', 'root', 'Doug@le5728', 'php_bd');
        if ($conn->connect_error) {
            die("Falha na conexão com o banco de dados: " . $conn->connect_error);
        }

        // Consulta de login
        $stmt = $conn->prepare("SELECT * FROM tbl_usuario WHERE login = ? AND senha = ?");
        $stmt->bind_param("ss", $login, $senha);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $_SESSION['logged_in'] = true;
            header('Location: dashboard.php');
            exit();
        } else {
            echo "Login ou senha inválidos!";
        }

        $stmt->close();
        $conn->close();
    }
}
?>

<!-- Formulário de login -->
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .container { background-color: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); width: 300px; }
        h2 { text-align: center; margin-bottom: 20px; }
        .input-field { margin-bottom: 15px; }
        .input-field label { font-size: 14px; font-weight: bold; display: block; margin-bottom: 5px; }
        .input-field input { width: 100%; padding: 10px; font-size: 16px; border-radius: 5px; border: 1px solid #ccc; }
        button { width: 100%; padding: 10px; font-size: 16px; background-color: #00508d; color: white; border: none; border-radius: 5px; }
        button:hover { background-color: #003f6f; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <form method="POST" action="login.php">
            <div class="input-field">
                <label for="login">Email:</label>
                <input type="text" name="login" id="login" placeholder="Digite seu email" required>
            </div>
            <div class="input-field">
                <label for="senha">Senha:</label>
                <input type="password" name="senha" id="senha" placeholder="Digite sua senha" required>
            </div>
            <button type="submit">Entrar</button>
        </form>
    </div>
</body>
</html>
