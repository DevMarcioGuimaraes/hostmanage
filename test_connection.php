<?php
require_once 'config/config.php';
require_once 'core/Database.php';

try {
    echo "<h2>Testando conexão com o banco de dados</h2>";
    
    $db = new Database();
    
    // Tenta executar uma consulta simples
    $db->query("SELECT 1");
    $db->execute();
    
    echo "<p style='color: green;'>Conexão estabelecida com sucesso!</p>";
    
    // Tenta listar as tabelas do banco
    $db->query("SHOW TABLES");
    $tables = $db->resultSet();
    
    echo "<h3>Tabelas encontradas no banco de dados:</h3>";
    echo "<ul>";
    foreach ($tables as $table) {
        echo "<li>" . reset($table) . "</li>";
    }
    echo "</ul>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Erro ao conectar com o banco de dados: " . $e->getMessage() . "</p>";
    echo "<p>Verifique as configurações em config/config.php</p>";
}
?>

