<?php

include 'conecta.php';

if ($_POST['resolver_chamado'] == 'resolver') {
    $chamado = $_POST['chamado'];

    $sql_update = "UPDATE chamado SET resolvido = 't', data_resolvido = CURRENT_TIMESTAMP WHERE chamado = $chamado";
    $res_update = pg_query($con, $sql_update);

    if (!$res_update) {
        $msg_erro = "Erro ao resolver chamado.";
    } else {
        $msg_sucesso = "Chamado resolvido com sucesso";
    }
}

if (isset($_POST['btn_acao']) == 'consultar') {

    $raBuscar = $_POST["raBuscar"];

    $msg_erro = "";

    $sql = "WITH dados_chamado AS (
                SELECT chamado.chamado,
                       aluno.ra,
                    concat(aluno.ra, ' - ', aluno.nome) as nome_aluno,
                    problema.descricao as problema_descricao,
                    chamado.descricao_problema as chamado_descricao_problema,
                    concat(sala.numero, ' - ', sala.descricao) as sala,
                    to_char(data_abertura, 'DD/MM/YYYY') as data_abertura,
                    chamado.resolvido,
                    to_char(chamado.data_resolvido, 'DD/MM/YYYY') as data_resolvido
                    FROM chamado
                    INNER JOIN aluno USING(aluno)
                    INNER JOIN problema USING(problema)
                    INNER JOIN sala USING(sala)
                )
                SELECT * FROM dados_chamado WHERE ra::text ILIKE '%$raBuscar%' ORDER BY chamado ASC
            ";
    $res = pg_query($con, $sql);

    if (!$res) {
        $msg_erro = "Erro na consulta";
    }

}

include 'menu.php';
?>

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

        .nav-tabs .nav-link.active {
            background-color: darkblue;
            color: #fff;
        }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const page = window.location.pathname.split("/").pop();
        const navLinks = document.querySelectorAll(".nav-link");
        navLinks.forEach(link => {
            if(link.getAttribute("href") === page) {
                link.classList.add("active");
            }
        });
    });
</script>

<h2 class="text-center mt-4">Consulta de Chamados</h2>

<?php if (!empty($msg_erro)) { ?>
    <div class="container mt-2 alert alert-danger" role="alert">
        <?= nl2br($msg_erro) ?>
    </div>
<?php } ?>

<?php if (!empty($msg_sucesso)) { ?>
    <div class="container mt-2 alert alert-success" role="alert">
        <?= $msg_sucesso ?>
    </div>
<?php } ?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <form action="consulta_chamado.php" method="POST">

                <div class="mb-3">
                    <label for="raBuscar">RA:</label>
                    <input type="text" id="raBuscar" name="raBuscar" class="form-control">
                </div>

                <button type="submit" name="btn_acao" value="consultar" class="btn btn-primary">Consultar</button>

            </form>
        </div>
    </div>

    <table>
        <br>
        <?php
        if (pg_num_rows($res) > 0) {
            echo "<table border='1'>
                    <thead>
                        <tr>
                        <th>Chamado</th>
                        <th>Aluno</th>
                        <th>Problema</th>
                        <th>Descrição do problema</th>
                        <th>Sala</th>
                        <th>Data Cadastro</th>
                        <th>Resolvido</th>
                        <th>Data Resolvido</th>
                        <th>Ações</th>
                        </tr>
                    </thead>";

            while ($linha = pg_fetch_assoc($res)) {

                $resolvido = $linha['resolvido'] === 't' ? "Sim" : "Não";

                if (empty($linha['data_resolvido'])) {
                    $botao_resolvido = "
                            <form method='POST'>
                            <input type='hidden' name='chamado' value='" . $linha["chamado"] . "'>
                            <button type='submit' name='resolver_chamado' value='resolver' class='btn btn-primary'>Resolver</button>
                            </form>";
                } else {
                    $botao_resolvido = '<span style="background-color: #007bff; color: white; padding: 4px 8px; border-radius: 4px;">Resolvido</span>';
                }

                echo "<tr>
                        <td>{$linha['chamado']}</td>
                        <td>{$linha['nome_aluno']}</td>
                        <td>{$linha['problema_descricao']}</td>
                        <td>{$linha['chamado_descricao_problema']}</td>
                        <td>{$linha['sala']}</td>
                        <td>{$linha['data_abertura']}</td>
                        <td>$resolvido</td>
                        <td>{$linha['data_resolvido']}</td>
                        <td>$botao_resolvido</td>
                    </tr>";
            }
            echo "</table>";
        } else {
            echo "Nenhum resultado encontrado." . "<br>";
        }
        ?>
    </table>

</div>
<?php include 'rodape.php'; ?>

