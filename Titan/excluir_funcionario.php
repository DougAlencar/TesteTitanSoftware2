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

// Exclui o funcionário
$sql = "DELETE FROM tbl_funcionario WHERE id_funcionario = $id";

if ($conn->query($sql) === TRUE) {
    echo "Funcionário excluído com sucesso!";
    header("Location: dashboard.php");
    exit();
} else {
    echo "Erro ao excluir funcionário: " . $conn->error;
}

$conn->close();
?>
