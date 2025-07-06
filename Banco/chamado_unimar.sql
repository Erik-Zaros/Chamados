CREATE TABLE aluno (
    id INT(4) AUTO_INCREMENT PRIMARY KEY,
    ra INT(7) NOT NULL,
    nome VARCHAR(60) NOT NULL,
    email VARCHAR(60) NOT NULL, 
    id_problema INT(4),
    FOREIGN KEY (id_problema) REFERENCES problema(id),
    descricao TEXT NOT NULL,
    sala INT(4) NOT NULL
);

CREATE TABLE problema (
    id INT(4) AUTO_INCREMENT PRIMARY KEY,
    tipo VARCHAR(30)
);

INSERT INTO problema (tipo) VALUES
("Monitor"),
("Mouse"),
("Teclado"),
("Cabo"),
("Rede");

  /*
    18/11/2023
        Comandos abaixos adicionados ao Banco.
  */

ALTER TABLE aluno
ADD COLUMN hora_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP;

ALTER TABLE aluno ADD COLUMN status VARCHAR(20);

ALTER TABLE aluno
ADD COLUMN hora_resolvido TIMESTAMP;

  /*

      25/11/2023
          Escrevendo novamente o banco, acresentando os comandos que coloquei separado.
  
  CREATE TABLE aluno (
      id INT(4) AUTO_INCREMENT PRIMARY KEY,
      ra INT(7) NOT NULL,
      nome VARCHAR(60) NOT NULL,
      email VARCHAR(60) NOT NULL, 
      id_problema INT(4),
      FOREIGN KEY (id_problema) REFERENCES problema(id),
      descricao TEXT NOT NULL,
      sala INT(4) NOT NULL,
      hora_cadastro TIMESTAP DEFAULT CURRENT_TIMESTAMP,
      status VARCHAR(20),
      hora_resolvido TIMESTAMP
  );
  
  CREATE TABLE problema (
      id INT(4) AUTO_INCREMENT PRIMARY KEY,
      tipo VARCHAR(30)
  );
  
  INSERT INTO problema (tipo) VALUES
  ("Monitor"),
  ("Mouse"),
  ("Teclado"),
  ("Cabo"),
  ("Rede");
  
  */