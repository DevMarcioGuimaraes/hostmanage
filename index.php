<?php
// Ponto de entrada da aplicação
session_start();
require_once 'config/config.php';
require_once 'core/Router.php';

// Inicializa o roteador
$router = new Router();
$router->route();

