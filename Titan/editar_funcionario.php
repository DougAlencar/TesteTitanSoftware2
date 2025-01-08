<?php
$conn = new mysqli('localhost', 'root', 'Doug@le5728', 'php_bd');
if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}

// Verifica se o ID foi passado
if (!isset($_GET['id'])) {
    die("ID do funcionário não fornecido!");
}

$id = intval($_GET['id']);

// Atualiza os dados do funcionário após o envio do formulário
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $cpf = $_POST['cpf'];
    $email = $_POST['email'];
    $salario = $_POST['salario'];

    $sql = "UPDATE tbl_funcionario 
            SET nome = '$nome', cpf = '$cpf', email = '$email', salario = $salario 
            WHERE id_funcionario = $id";

    if ($conn->query($sql) === TRUE) {
        echo "Funcionário atualizado com sucesso!";
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Erro ao atualizar funcionário: " . $conn->error;
    }
}

// Obtém os dados do funcionário
$sql = "SELECT * FROM tbl_funcionario WHERE id_funcionario = $id";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    die("Funcionário não encontrado!");
}

$funcionario = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Funcionário</title>
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
        h2 {
            text-align: center;
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
        <h2>Editar Funcionário</h2>
        <form method="POST">
            <div class="input-field">
                <label for="nome">Nome:</label>
                <input type="text" name="nome" id="nome" value="<?php echo $funcionario['nome']; ?>" required>
            </div>
            <div class="input-field">
                <label for="cpf">CPF:</label>
                <input type="text" name="cpf" id="cpf" value="<?php echo $funcionario['cpf']; ?>" required>
            </div>
            <div class="input-field">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" value="<?php echo $funcionario['email']; ?>" required>
            </div>
            <div class="input-field">
                <label for="salario">Salário:</label>
                <input type="text" name="salario" id="salario" value="<?php echo $funcionario['salario']; ?>" required>
            </div>
            <button type="submit">Salvar Alterações</button>
        </form>
    </div>
</body>
</html>

<?php
$conn->close();
?>
