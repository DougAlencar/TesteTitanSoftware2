<?php
// Inclui o arquivo de configuração de banco de dados
include 'config.php';

function getConnection() {
    // Cria a conexão utilizando as configurações definidas
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    // Verifica se há algum erro na conexão
    if ($conn->connect_error) {
        // Loga o erro e exibe uma mensagem amigável
        error_log("Erro de conexão ao banco: " . $conn->connect_error);
        die("Erro na conexão com o banco de dados. Tente novamente mais tarde.");
    }
    
    return $conn;
}
?>
