CREATE DATABASE IF NOT EXISTS toy_story_shop;
USE toy_story_shop;

-- Tabela 1: Categorias (ex: Espaço, Velho Oeste, Dinossauros)
CREATE TABLE categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT
);

-- Tabela 2: Donos (Os antigos/atuais donos dos brinquedos)
CREATE TABLE donos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    idade INT
);

-- Tabela 3: Brinquedos (Tabela principal com chaves estrangeiras)
CREATE TABLE brinquedos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    categoria_id INT,
    dono_id INT,
    status VARCHAR(50) DEFAULT 'Ativo',
    FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE SET NULL,
    FOREIGN KEY (dono_id) REFERENCES donos(id) ON DELETE SET NULL
);

-- Inserindo dados padrão para teste
INSERT INTO categorias (nome, descricao) VALUES ('Velho Oeste', 'Brinquedos clássicos de caubói'), ('Comando Espacial', 'Tecnologia de ponta de Star Command');
INSERT INTO donos (nome, idade) VALUES ('Andy', 10), ('Bonnie', 6);
INSERT INTO brinquedos (nome, categoria_id, dono_id, status) VALUES ('Sheriff Woody', 1, 1, 'Vivo (escondido)'), ('Buzz Lightyear', 2, 1, 'Modo Demo');