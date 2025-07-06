<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Chamado</title>
</head>

<body>
    <form action="cadastro_aluno.php" method="POST">
        <center>
            <h2>Abertura de Chamado</h2>
            <table border="1">
                <tr>
                     <td>RA: <input type="number" name="ra" placeholder="Digite seu RA..." requerid></td>
                </tr>
                <tr>
                     <td>Nome: <input type="text" name="nome" placeholder="Digite seu Nome.." requerid></td>
                </tr>
                <tr>
                     <td>Email: <input type="text" name="email" placeholder="Digite seu Email..." requerid></td>
                </tr>
                <tr>
                    <td>
                         Problemas: <select name='id_problema' required>

                            <?php

                              require("conecta.php");
  
                              $dados_select = mysqli_query($conn, "SELECT id, tipo FROM problema");
  
                              while($dado = mysqli_fetch_array($dados_select)) {
                                  echo '<option value='.$dado[0].'>'.$dado[1].'</option>';
                              }
                            ?>
                    </td>
                        </select>
                </tr>
                <tr>
                    <td>Descricao: <textarea name="descricao" id="" cols="30" rows="-2" placeholder="Informe o que ocorreu..." requerid ></textarea></td>
                </tr>
                <tr>
                    <td>Sala: <input type="number" name="sala" placeholder="Digite o número da sala..."></td>
                </tr>
            </table>
            <input type="submit" name="gravar" value="Gravar">
            <td><a href="menu.html"><input type="button" name="voltar" value="Voltar"></a></td>
        </center>
    </form>
</body>
</html>