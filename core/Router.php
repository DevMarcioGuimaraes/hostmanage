<?php
class Router {
    private $controller = 'HomeController';
    private $method = 'index';
    private $params = [];
    
    public function route() {
        $url = $this->parseUrl();
        
        // Verifica se o controlador existe
        if (isset($url[0]) && file_exists('controllers/' . ucfirst($url[0]) . 'Controller.php')) {
            $this->controller = ucfirst($url[0]) . 'Controller';
            unset($url[0]);
        }
        
        require_once 'controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller;
        
        // Verifica se o método existe
        if (isset($url[1]) && method_exists($this->controller, $url[1])) {
            $this->method = $url[1];
            unset($url[1]);
        }
        
        // Obtém os parâmetros
        $this->params = $url ? array_values($url) : [];
        
        // Chama o método do controlador com os parâmetros
        call_user_func_array([$this->controller, $this->method], $this->params);
    }
    
    private function parseUrl() {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return [];
    }
}

