<?php
require('C:\codigos\Titan\fpdf186\fpdf.php');

// Conexão com o banco de dados
$conn = new mysqli('localhost', 'root', 'Doug@le5728', 'php_bd');
if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}

// Consulta para listar os funcionários, agora incluindo o nome da empresa
$sql = "SELECT f.nome, f.cpf, f.rg, f.email, e.nome AS nome_empresa, 
        DATE_FORMAT(f.data_cadastro, '%d/%m/%Y') AS data_cadastro, 
        TIMESTAMPDIFF(YEAR, f.data_cadastro, CURDATE()) AS anos_de_casa,
        DATE_FORMAT(f.data_cadastro, '%m/%d/%Y') AS data_formatada,
        FORMAT(f.salario, 2, 'pt_BR') AS salario, 
        FORMAT(f.bonificacao, 2, 'pt_BR') AS bonificacao 
        FROM tbl_funcionario f
        JOIN tbl_empresa e ON f.id_empresa = e.id_empresa";
$result = $conn->query($sql);

// Inicializa o PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 12);

// Título
$pdf->Cell(0, 10, 'Lista de Funcionarios', 1, 1, 'C');

// Cabeçalho da Tabela 
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(100, 100, 255); // Azul claro para o fundo do cabeçalho
$pdf->SetTextColor(255, 255, 255); // Cor do texto (branco)
$pdf->Cell(40, 10, 'Nome', 1, 0, 'C', true);
$pdf->Cell(25, 10, 'CPF', 1, 0, 'C', true);
$pdf->Cell(20, 10, 'RG', 1, 0, 'C', true);
$pdf->Cell(50, 10, 'Email', 1, 0, 'C', true);
$pdf->Cell(35, 10, 'Empresa', 1, 0, 'C', true); // Ajuste no tamanho da coluna
$pdf->Cell(30, 10, 'Data', 1, 1, 'C', true);

// Resetando as cores do texto para as células
$pdf->SetTextColor(0, 0, 0); // Texto preto para os dados
$pdf->SetFont('Arial', '', 10);

// Dados
while ($row = $result->fetch_assoc()) {
    // Lógica para definir a cor de fundo com base no tempo de empresa
    if ($row['anos_de_casa'] >= 5) {
        // Bonificação de 20% e cor vermelha para 5 anos ou mais
        $pdf->SetFillColor(255, 0, 0); // Vermelho
    } elseif ($row['anos_de_casa'] >= 1) {
        // Bonificação de 10% e cor azul para mais de 1 ano, mas menos de 5
        $pdf->SetFillColor(0, 0, 255); // Azul
    } else {
        // Cor padrão (sem bonificação)
        $pdf->SetFillColor(255, 255, 255); // Branco
    }

    // Preenche as células com as informações do funcionário
    $pdf->Cell(40, 10, $row['nome'], 1, 0, 'C', true);
    $pdf->Cell(25, 10, $row['cpf'], 1, 0, 'C', true);
    $pdf->Cell(20, 10, $row['rg'], 1, 0, 'C', true);
    $pdf->Cell(50, 10, $row['email'], 1, 0, 'C', true);
    $pdf->Cell(35, 10, $row['nome_empresa'], 1, 0, 'C', true); // Agora exibe o nome da empresa
    $pdf->Cell(30, 10, $row['data_cadastro'], 1, 1, 'C', true);
}

// Gera o PDF
$pdf->Output('D', 'Lista_Funcionarios.pdf');
?>
