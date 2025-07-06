<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultar Chamado</title>
</head>

<body>
    <center>
        <table>
            <h2>Consulta por RA</h2>
            <form action="visualizacao_chamado.php" method="POST">
                    RA: <label for="raBuscar"></label>
                    <input type="text" id="raBuscar" name="raBuscar">
                    <input type="submit" value="Consultar">
                    <a href="menu.html"><input type="button" name="voltar" value="Voltar"></a>
            </form>
        </table>
    </center>
</body>
</html>