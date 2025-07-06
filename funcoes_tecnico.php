<?php

  require("conecta.php");

  if ($_SERVER['REQUEST_METHOD'] == 'POST' and isset($_POST['resolver_chamado'])) {
      $chamado_id = $_POST['chamado_id'];
    
      $atualizacao_sql = "UPDATE aluno SET status = 'Resolvido', hora_resolvido = CURRENT_TIMESTAMP WHERE id = $chamado_id";
      $conn->query($atualizacao_sql);
  }
  
  $sql_abertos = "SELECT aluno.id,
                         aluno.ra,
                         problema.tipo,
                         aluno.descricao,
                         aluno.hora_cadastro,
                         aluno.hora_resolvido,
                         aluno.status
                  FROM aluno
                  JOIN problema ON aluno.id_problema = problema.id
                  WHERE aluno.status = 'Aberto'";
  $resultado_abertos = $conn->query($sql_abertos);

  function mostrarTabela($resultado) {
      echo "<table>";
      echo "
      <tr>
          <th>RA</th>
          <th>Problema</th>
          <th>Descrição</th>
          <th>Hora de Cadastro</th>
          <th>Status</th>
          <th>Ação</th>
      </tr>";
  
      while ($linha = $resultado->fetch_assoc()) {
          echo "<tr>";
          echo "<td>" . $linha["ra"] . "</td>";
          echo "<td>" . $linha["tipo"] . "</td>";
          echo "<td>" . $linha["descricao"] . "</td>";
          echo "<td>" . $linha["hora_cadastro"] . "</td>";
          echo "<td>" . $linha["status"] . "</td>";
          echo "<td>";
          echo "<form method='POST'>";
          echo "<input type='hidden' name='chamado_id' value='" . $linha["id"] . "'>";
          echo "<input type='submit' name='resolver_chamado' value='Resolver'>";
          echo "</form>";
          echo "</td>";
          echo "</tr>";
      }
      echo "</table>";
  }
?>