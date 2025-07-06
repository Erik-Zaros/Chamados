<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Análise de Chamado</title>
    
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
    </style>
</head>

<body>
<center>

  <?php
  
    require("conecta.php");
    require("funcoes_tecnico.php");
    
    if ($resultado_abertos->num_rows > 0) {
        mostrarTabela($resultado_abertos);
    } else {
        echo "<strong><h1>Nenhum chamado aberto.</h1></strong>" . "<br>";
    }
  ?>
        <td><a href="menu.html"><input type="button" name="botao_voltar" value="Voltar"></a></td>
</center>
</body>
</html>