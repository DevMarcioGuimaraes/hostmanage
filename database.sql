-- Criação do banco de dados
CREATE DATABASE IF NOT EXISTS hostmanage;
USE hostmanage;

-- Tabela de usuários
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    type ENUM('client', 'reseller') NOT NULL DEFAULT 'client',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de planos
CREATE TABLE IF NOT EXISTS plans (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    disk_space INT NOT NULL,
    bandwidth INT NOT NULL,
    email_accounts INT NOT NULL,
    `databases` INT NOT NULL,
    subdomains INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de contas de hospedagem
CREATE TABLE IF NOT EXISTS hostings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    plan_id INT NOT NULL,
    domain VARCHAR(255) NOT NULL,
    status ENUM('pending', 'active', 'suspended', 'cancelled') NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (plan_id) REFERENCES plans(id)
);

-- Tabela de faturas
CREATE TABLE IF NOT EXISTS invoices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    description TEXT NOT NULL,
    status ENUM('pending', 'paid', 'overdue') NOT NULL DEFAULT 'pending',
    due_date DATE NOT NULL,
    paid_at DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Tabela de tickets
CREATE TABLE IF NOT EXISTS tickets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    subject VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    status ENUM('open', 'closed') NOT NULL DEFAULT 'open',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Tabela de respostas de tickets
CREATE TABLE IF NOT EXISTS ticket_replies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ticket_id INT NOT NULL,
    user_id INT NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ticket_id) REFERENCES tickets(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Inserir um usuário revendedor padrão
INSERT INTO users (name, email, password, type) VALUES ('Admin', 'admin@example.com', '$2y$10$QlpXMqv.iOLv1QT/fh.Mz.6CmHgZFQnvhEpwveK0KKnQOC9lUy7WS', 'reseller');

-- Inserir alguns planos padrão
INSERT INTO plans (name, description, price, disk_space, bandwidth, email_accounts, `databases`, subdomains) VALUES 
('Básico', 'Plano ideal para sites pequenos', 9.99, 1000, 10000, 5, 2, 5),
('Intermediário', 'Plano ideal para sites médios', 19.99, 5000, 50000, 10, 5, 10),
('Avançado', 'Plano ideal para sites grandes', 29.99, 10000, 100000, 20, 10, 20);

