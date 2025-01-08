<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];

    if (empty($nome)) {
        echo "O nome da empresa é obrigatório!";
    } else {
        $conn = new mysqli('localhost', 'root', 'Doug@le5728', 'php_bd');
        $sql = "INSERT INTO tbl_empresa (nome) VALUES ('$nome')";

        if ($conn->query($sql) === TRUE) {
            echo "Empresa cadastrada com sucesso!";
        } else {
            echo "Erro ao cadastrar empresa!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro Empresa</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            padding: 20px;
        }
        .container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        .input-field {
            margin-bottom: 15px;
        }
        .input-field label {
            font-size: 14px;
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }
        .input-field input {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        button {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            background-color: #00508d;
            color: white;
            border: none;
            border-radius: 5px;
        }
        button:hover {
            background-color: #003f6f;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Cadastro de Empresa</h2>
        <form method="POST" action="cadastro_empresa.php">
            <div class="input-field">
                <label for="nome">Nome da Empresa:</label>
                <input type="text" name="nome" id="nome" required>
            </div>
            <button type="submit">Cadastrar</button>
        </form>
    </div>
</body>
</html>
