CREATE DATABASE IF NOT EXISTS biblioteca;
USE biblioteca;

-- Usuários
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL
);

-- Categorias
CREATE TABLE categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL
);

-- Itens (livros/revistas)
CREATE TABLE itens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    autor VARCHAR(255) NOT NULL,
    descricao TEXT,
    tipo ENUM('livro', 'revista') NOT NULL,
    categoria_id INT,
    capa VARCHAR(255),
    id_usuario INT,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE SET NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Favoritos
CREATE TABLE favoritos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT,
    id_item INT,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (id_item) REFERENCES itens(id) ON DELETE CASCADE
);

-- Carrinho
CREATE TABLE carrinho (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT,
    id_item INT,
    valor DECIMAL(10, 2) DEFAULT NULL,
    dias INT DEFAULT NULL,
    tipo ENUM('emprestimo', 'venda') NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (id_item) REFERENCES itens(id) ON DELETE CASCADE
);

-- Operações
CREATE TABLE operacoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    destinatario VARCHAR(255) NOT NULL,
    tipo ENUM('emprestimo', 'venda') NOT NULL,
    data_operacao DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Itens da operação
CREATE TABLE operacao_itens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_operacao INT,
    id_item INT,
    valor DECIMAL(10, 2) DEFAULT NULL,
    dias INT DEFAULT NULL,
    FOREIGN KEY (id_operacao) REFERENCES operacoes(id) ON DELETE CASCADE,
    FOREIGN KEY (id_item) REFERENCES itens(id) ON DELETE CASCADE
);
