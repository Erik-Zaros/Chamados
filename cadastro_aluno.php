<?php
    require("conecta.php");

    $ra = $_POST['ra'];
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $id_problema = $_POST['id_problema'];
    $descricao = $_POST['descricao'];
    $sala = $_POST['sala'];
    $status = 'Aberto';

    /*
        18/11/2023
             Coluna status adicionado no banco, status sempre aberto.
    */
    
    $sql = "INSERT INTO aluno (ra, nome, email, id_problema, descricao, sala, status)
    VALUES ('$ra', '$nome', '$email', '$id_problema', '$descricao', '$sala', '$status')";

    if ($conn->query($sql) === TRUE) {
      echo "<center><h2>Chamado Cadastrado</h2>";
      echo "<a href='menu.html'><input type='button' value='Voltar'></a></center>";
    } else {
      echo "<h3>Ocorreu um erro. Faça o contato com o ADMIN: erikzaros942@gmail.com: </h3>: " . $sql . "<br>" . $conn->error;
    }
?> 
