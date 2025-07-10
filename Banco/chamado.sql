CREATE DATABASE chamados;

CREATE TABLE chamado (
    chamado SERIAL PRIMARY KEY NOT NULL,
    aluno INTEGER REFERENCES aluno(aluno) NOT NULL,
    problema INTEGER REFERENCES problema(problema) NOT NULL,
    descricao_problema TEXT NOT NULL,
    sala INTEGER REFERENCES sala(sala) NOT NULL,
    data_abertura TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    resolvido BOOLEAN DEFAULT FALSE,
    data_resolvido TIMESTAMP
);

CREATE TABLE aluno (
    aluno SERIAL PRIMARY KEY,
    ra INTEGER NOT NULL,
    nome VARCHAR(60) NOT NULL,
    email VARCHAR(60) NOT NULL,
    data_input TIMESTAMP DEFAULT NOW()
);

CREATE TABLE problema (
    problema SERIAL PRIMARY KEY,
    descricao VARCHAR(255),
    data_input TIMESTAMP DEFAULT NOW()
);

CREATE TABLE sala(
    sala SERIAL PRIMARY KEY,
    numero INTEGER NOT NULL,
    descricao VARCHAR(255) NOT NULL,
    data_input TIMESTAMP DEFAULT NOW()
);

INSERT INTO problema (descricao) VALUES
('Monitor'),
('Mouse'),
('Teclado'),
('Cabo'),
('Rede');

INSERT INTO sala (numero, descricao) VALUES
(429, 'Sala Amarela'),
(428, 'Sala Azul');
