<?php
// Configurações do banco de dados
define('DB_HOST', 'hostmanage.mysql.dbaas.com.br');
define('DB_USER', 'hotmanage');
define('DB_PASS', 'Dj627246#');
define('DB_NAME', 'hostmanage');

// Configurações da aplicação
define('BASE_URL', 'http://localhost/hostmanage');
define('APP_NAME', 'Sistema de Gestão de Hospedagem');
define('APP_VERSION', '1.0.0');

// Configurações de email
define('MAIL_HOST', 'smtp.example.com');
define('MAIL_PORT', 587);
define('MAIL_USERNAME', 'noreply@example.com');
define('MAIL_PASSWORD', 'your-password');
define('MAIL_FROM_ADDRESS', 'noreply@example.com');
define('MAIL_FROM_NAME', 'Sistema de Gestão de Hospedagem');

// Timezone
date_default_timezone_set('America/Sao_Paulo');

// Função para carregar classes automaticamente
spl_autoload_register(function ($class_name) {
    // Converte namespace para caminho de arquivo
    $class_file = str_replace('\\', '/', $class_name) . '.php';
    
    // Verifica se o arquivo existe em diferentes diretórios
    $directories = ['core/', 'models/', 'controllers/', 'helpers/'];
    
    foreach ($directories as $dir) {
        if (file_exists($dir . $class_file)) {
            require_once $dir . $class_file;
            return;
        }
    }
});

