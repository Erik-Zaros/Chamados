<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualização de Chamado</title>
    
<style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
    </style>
</head>

<body>
<?php
  require("conecta.php");
  require("consulta_chamado.php");
  
  $raBuscar = $_POST["raBuscar"];
  
  $consulta = "SELECT aluno.ra,
                      aluno.nome,
                      aluno.sala,
                      aluno.descricao,
                      aluno.hora_cadastro,
                      aluno.hora_resolvido,
                      aluno.status
                FROM aluno
                WHERE aluno.ra = '$raBuscar'";
  
  $resultado = $conn->query($consulta);
  
  if ($resultado->num_rows > 0) {
      echo "<table border='1'>
              <thead>
                  <tr>
                      <th>RA</th>
                      <th>Nome</th>
                      <th>Sala</th>
                      <th>Descrição</th>
                      <th>Hora de Cadastro</th>
                      <th>Hora Resolvido</th>
                      <th>Status</th>
                  </tr>
              </thead>";
  
      while ($linha = $resultado->fetch_assoc()) {

          echo "<tr>
                  <td>{$linha['ra']}</td>
                  <td>{$linha['nome']}</td>
                  <td>{$linha['sala']}</td>
                  <td>{$linha['descricao']}</td>
                  <td>{$linha['hora_cadastro']}</td>
                  <td>{$linha['hora_resolvido']}</td>
                  <td>{$linha['status']}</td>
              </tr>";
      }
      echo "</table>";
  } else {
      echo "Nenhum resultado encontrado." . "<br>";
  }

?>
<td><a href="menu.html"><input type="button" name="voltar" value="Voltar"></a></td>
</body>
</html>