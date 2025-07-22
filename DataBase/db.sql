-- Criação do banco de dados
CREATE DATABASE IF NOT EXISTS biblioteca;
USE biblioteca;

-- Criação da tabela de usuários
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    criado_em DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de categorias
CREATE TABLE categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    id_usuario INT NOT NULL,
    criado_em DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Tabela de itens (livros, revistas)
CREATE TABLE itens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    autor VARCHAR(255) NOT NULL,
    descricao TEXT,
    tipo ENUM('livro','revista') NOT NULL,
    categoria_id INT NOT NULL,
    capa VARCHAR(255) DEFAULT NULL,
    valor DECIMAL(10,2) DEFAULT 0.00,
    id_usuario INT NOT NULL,
    criado_em DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE SET NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Tabela de favoritos
CREATE TABLE favoritos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    item_id INT NOT NULL,
    criado_em DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY (usuario_id, item_id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (item_id) REFERENCES itens(id) ON DELETE CASCADE
);

-- Tabela do carrinho
CREATE TABLE carrinho (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    item_id INT NOT NULL,
    criado_em DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY (usuario_id, item_id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (item_id) REFERENCES itens(id) ON DELETE CASCADE
);

-- Tabela de operações (vendas ou empréstimos)
CREATE TABLE operacoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    tipo ENUM('venda', 'emprestimo') NOT NULL,
    destinatario VARCHAR(255) NOT NULL,
    valor_total DECIMAL(10,2) DEFAULT 0.00,
    data_operacao DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Tabela que relaciona itens com operações
CREATE TABLE itens_operacao (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_operacao INT NOT NULL,
    id_item INT NOT NULL,
    valor DECIMAL(10,2) DEFAULT 0.00,
    FOREIGN KEY (id_operacao) REFERENCES operacoes(id) ON DELETE CASCADE,
    FOREIGN KEY (id_item) REFERENCES itens(id) ON DELETE CASCADE
);
