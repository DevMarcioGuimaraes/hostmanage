<?php
class Database {
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;
    
    private $dbh;
    private $stmt;
    private $error;
    
    public function __construct() {
        // Configurar DSN
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
        $options = [
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_EMULATE_PREPARES => false
        ];
        
        // Criar instância PDO
        try {
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            echo 'Erro de conexão: ' . $this->error;
        }
    }
    
    // Preparar declaração com query
    public function query($sql) {
        $this->stmt = $this->dbh->prepare($sql);
    }
    
    // Bind values
    public function bind($param, $value, $type = null) {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        
        $this->stmt->bindValue($param, $value, $type);
    }
    
    // Executar a declaração preparada
    public function execute() {
        return $this->stmt->execute();
    }
    
    // Obter resultados como array de objetos
    public function resultSet() {
        $this->execute();
        return $this->stmt->fetchAll();
    }
    
    // Obter registro único como objeto
    public function single() {
        $this->execute();
        return $this->stmt->fetch();
    }
    
    // Obter contagem de linhas
    public function rowCount() {
        return $this->stmt->rowCount();
    }
    
    // Obter último ID inserido
    public function lastInsertId() {
        return $this->dbh->lastInsertId();
    }
    
    // Iniciar transação
    public function beginTransaction() {
        return $this->dbh->beginTransaction();
    }
    
    // Commit transação
    public function commit() {
        return $this->dbh->commit();
    }
    
    // Rollback transação
    public function rollBack() {
        return $this->dbh->rollBack();
    }
}

