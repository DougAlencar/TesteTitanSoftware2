<?php
session_start();
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit();
}

// Conexão com o banco
$conn = new mysqli('localhost', 'root', 'Doug@le5728', 'php_bd');
if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}

// Consulta para listar os funcionários com as bonificações calculadas e nome da empresa
$sql = "SELECT f.*, e.nome AS nome_empresa, 
       CASE 
           WHEN DATEDIFF(CURDATE(), f.data_cadastro) > 365 * 5 THEN f.salario * 0.2
           WHEN DATEDIFF(CURDATE(), f.data_cadastro) > 365 THEN f.salario * 0.1
           ELSE 0
       END AS bonificacao_calculada,
       TIMESTAMPDIFF(YEAR, f.data_cadastro, CURDATE()) AS anos_empresa
       FROM tbl_funcionario f
       LEFT JOIN tbl_empresa e ON f.id_empresa = e.id_empresa";  
$result = $conn->query($sql);

echo "<h1>Dashboard</h1>";
echo "<a href='cadastro_empresa.php' class='button'>Cadastrar Empresa</a> | 
      <a href='cadastro_funcionario.php' class='button'>Cadastrar Funcionário</a> | 
      <a href='exportar_pdf.php' class='button'>Exportar para PDF</a>";

echo "<table class='table'>";
echo "<tr>
        <th>Nome</th><th>CPF</th><th>RG</th><th>Email</th><th>Empresa</th>
        <th>Data Cadastro</th><th>Salário</th><th>Bonificação</th><th>Ações</th>
      </tr>";

while ($row = $result->fetch_assoc()) {
    $data_cadastro = isset($row['data_cadastro']) ? $row['data_cadastro'] : null;
    $salario = isset($row['salario']) ? $row['salario'] : 0;
    $anos_empresa = isset($row['anos_empresa']) ? $row['anos_empresa'] : 0;
    $bonificacao_calculada = isset($row['bonificacao_calculada']) ? $row['bonificacao_calculada'] : 0;

    $cor = '';
    if ($anos_empresa >= 5) {
        $cor = 'style="background-color: #FF4B4B;"';  // Mais de 5 anos
    } elseif ($anos_empresa >= 1) {
        $cor = 'style="background-color: #4B6EFF;"';  // Mais de 1 ano
    }

    $data_formatada = $data_cadastro ? date('d/m/Y', strtotime($data_cadastro)) : 'Data inválida';
    $salario_formatado = $salario ? number_format($salario, 2, ',', '.') : 'R$ 0,00';
    $bonificacao_formatada = $bonificacao_calculada ? number_format($bonificacao_calculada, 2, ',', '.') : 'R$ 0,00';

    echo "<tr $cor>
            <td>{$row['nome']}</td>
            <td>{$row['cpf']}</td>
            <td>{$row['rg']}</td>
            <td>{$row['email']}</td>
            <td>{$row['nome_empresa']}</td>
            <td>$data_formatada</td>
            <td>R$ $salario_formatado</td>
            <td>R$ $bonificacao_formatada</td>
            <td>
                <a href='editar_funcionario.php?id={$row['id_funcionario']}' class='action-button'>Editar</a> | 
                <a href='excluir_funcionario.php?id={$row['id_funcionario']}' class='action-button'>Excluir</a>
            </td>
          </tr>";
}
echo "</table>";

$conn->close();
?>

<!-- Estilo CSS -->
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 20px;
    }
    h1 {
        text-align: center;
        color: #333;
    }
    .button {
        padding: 10px 20px;
        background-color: #00508d;
        color: white;
        border-radius: 5px;
        text-decoration: none;
        font-size: 14px;
    }
    .button:hover {
        background-color: #003f6f;
    }
    .table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    .table th, .table td {
        padding: 12px;
        text-align: center;
        border: 1px solid #ddd;
    }
    .table th {
        background-color: #333;
        color: white;
    }
    .table tr:nth-child(even) {
        background-color: #f9f9f9;
    }
    .table tr:hover {
        background-color: #f1f1f1;
    }
    .action-button {
        padding: 5px 10px;
        background-color: #333;
        color: white;
        border-radius: 5px;
        text-decoration: none;
    }
    .action-button:hover {
        background-color: #555;
    }
</style>
