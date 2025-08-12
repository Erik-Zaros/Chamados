<?php

include 'conecta.php';

if (isset($_POST['btn_acao']) && $_POST['btn_acao'] == 'gravar') {

    $aluno = trim($_POST['aluno']);
    $problema = trim($_POST['problema']);
    $descricao = trim($_POST['descricao']);
    $sala = trim($_POST['sala']);
    $chamado = $_GET['chamado'];

    $msg_erro = "";
    $msg_sucesso = "";

    if (strlen($aluno) == 0) {
        $msg_erro .= "Selecione o aluno <br>";
    }

    if (strlen($problema) == 0) {
        $msg_erro .= "Selecione o problema <br>";
    }

    if (strlen($descricao) == 0) {
        $msg_erro .= "Preencha a descrição<br>";
    }

    if (strlen($chamado)) {
        $cond_chamado = "AND chamado <> $chamado";
    }

    $sql_valida = "SELECT aluno, problema, sala
                        FROM chamado
                        WHERE aluno = $aluno
                        AND problema = $problema
                        AND sala = $sala
                        $cond_chamado
                    ";
    $res_valida = pg_query($con, $sql_valida);

    if (pg_num_rows($res_valida) > 0) {
        $msg_erro .= "Chamado já cadastrado para este aluno, problema e sala!";
    }

    if (strlen($msg_erro) === 0 && empty($chamado)) {
        pg_query($con, "BEGIN");

        $sql = "INSERT INTO chamado (aluno, problema, descricao_problema, sala) VALUES
                ($aluno, $problema, '$descricao', $sala)
            ";
        $res = pg_query($con, $sql);

        if ($res) {
            pg_query($con, "COMMIT");
            $msg_sucesso = "Chamado cadastrado com sucesso";
            unset($_GET);
            unset($_POST);
            echo "<meta http-equiv=refresh content=\"2;URL=formulario_chamado.php\">";
        } else {
            pg_query($con, "ROLLBACK");
            $msg_erro .= "Erro ao cadastrar Chamado";
        }
    } elseif (strlen($msg_erro) === 0 && !empty($chamado)) {

        pg_query($con, "BEGIN");

        $sql = "UPDATE chamado
                SET aluno = $aluno,
                problema = $problema,
                descricao_problema = '$descricao',
                sala = $sala
                WHERE chamado = $chamado
            ";
        $res = pg_query($con, $sql);

        if ($res) {
            pg_query($con, "COMMIT");
            $msg_sucesso = "Chamado atualizado com sucesso";
            unset($_GET);
            unset($_POST);
            echo "<meta http-equiv=refresh content=\"2;URL=formulario_chamado.php\">";
        } else {
            pg_query($con, "ROLLBACK");
            $msg_erro = "Erro ao atualizar chamado";
        }
    }
}

if (isset($_GET['chamado'])) {

    $chamado = $_GET['chamado'];

    $sql = "SELECT aluno,
                   problema,
                   descricao_problema,
                   sala
                FROM chamado
                WHERE chamado = $chamado
            ";
    $res = pg_query($con, $sql);

    if (pg_num_rows($res) > 0) {
        $dados_edicao = pg_fetch_assoc($res);
    }
}

if (isset($_POST['btn_acao']) && $_POST['btn_acao'] == 'excluir') {

    $chamado = $_GET['chamado'];

    if (!empty($chamado)) {

        pg_query($con, "BEGIN");

        $sql = "DELETE FROM chamado WHERE chamado = $chamado";
        $res = pg_query($con, $sql);

        if ($res) {
            pg_query($con, "COMMIT");
            $msg_sucesso = "Chamado excluído com sucesso";
            unset($_GET);
            unset($_POST);
            echo "<meta http-equiv=refresh content=\"2;URL=formulario_chamado.php\">";
        } else {
            pg_query($con, "ROLLBACK");
            $msg_erro = "Erro ao excluir o chamado";
        }
    }
}

if (isset($_GET['excluido']) && $_GET['excluido'] === 'ok') {
    $msg_sucesso = "Chamado excluido com sucesso";
}

include 'menu.php';
?>

<style>
    .nav-tabs .nav-link.active {
        background-color: orange; /*Muda cor */
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

<h2 class="text-center mt-4">Cadastro de Chamado</h2>

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

            <form action="<?= $PHP_SELF ?>" method="POST">
                <div class="mb-3">
                    <label for="aluno" class="form-label">Aluno</label>
                    <select class="form-select" name="aluno" id="aluno">
                        <option value="">Selecione</option>
                        <?php
                            $sql_aluno = "SELECT aluno,
                                                 ra || ' - ' || nome as nome_aluno
                                                FROM aluno
                                            ";
                              $res_aluno = pg_query($con, $sql_aluno);

                              if (pg_num_rows($res_aluno) > 0) {
                                while ($dados = pg_fetch_array($res_aluno)) {
                                    $selected = ($dados['aluno'] == ($dados_edicao['aluno'] ?? '')) ? 'selected' : '';
                                    echo "<option value='{$dados['aluno']}' $selected>{$dados['nome_aluno']}</option>";
                                }
                              }
                        ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="problema" class="form-label">Problema</label>
                    <select class="form-select" name="problema" id="problema">
                        <option value="">Selecione</option>
                        <?php
                            $sql_problema = "SELECT problema,
                                                 descricao
                                                FROM problema
                                            ";
                              $res_problema = pg_query($con, $sql_problema);

                              if (pg_num_rows($res_problema) > 0) {
                                while ($dados = pg_fetch_array($res_problema)) {
                                    $selected = ($dados['problema'] == ($dados_edicao['problema'] ?? '')) ? 'selected' : '';
                                    echo "<option value='{$dados['problema']}' $selected>{$dados['descricao']}</option>";
                                }
                              }
                        ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="descricao" class="form-label">Descrição</label>
                    <textarea class="form-control" name="descricao"><?= $dados_edicao['descricao_problema']?></textarea>
                </div>

                <div class="mb-3">
                    <label for="sala" class="form-label">Sala</label>
                    <select class="form-select" name="sala" id="sala">
                        <option value="">Selecione</option>
                        <?php
                            $sql_sala = "SELECT sala,
                                                 concat(numero, ' - ', descricao) as descricao
                                                FROM sala
                                            ";
                              $res_sala = pg_query($con, $sql_sala);

                              if (pg_num_rows($res_sala) > 0) {
                                while ($dados = pg_fetch_array($res_sala)) {
                                    $selected = ($dados['sala'] == ($dados_edicao['sala'] ?? '')) ? 'selected' : '';
                                    echo "<option value='{$dados['sala']}' $selected>{$dados['descricao']}</option>";
                                }
                              }
                        ?>
                    </select>
                </div>

                <div class="d-flex justify-content-between">
                    <button type="submit" name="btn_acao" class="btn btn-primary" value="gravar">Gravar</button>

                    <?php if (isset($_GET['chamado'])) { ?>
                        <div class="d-flex justify-content-end">
                            <button type="submit" name="btn_acao" class="btn btn-danger" value="excluir" onclick=" return confirm('Deseja excluir esse registro?')">Excluir</button>
                        </div>
                    <?php } ?>

                </div>

            </form>
        </div>
    </div>

    <br>
    <br>

    <div class="container-fluid">
        <table class='table table-striped table-bordered table-hover table-fixed' >
            <thead>
                <tr class='table-primary' >
                    <th>Chamado</th>
                    <th>Aluno</th>
                    <th>Problema</th>
                    <th>Descrição do problema</th>
                    <th>Sala</th>
                    <th>Data Cadastro</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>

                <?php

                $sql = "WITH chamados as (
                        SELECT
                          chamado.chamado,
                          aluno.ra || ' - ' || aluno.nome AS nome_aluno,
                          problema.descricao AS problema_descricao,
                          chamado.descricao_problema AS chamado_descricao_problema,
                          sala.numero || ' - ' || sala.descricao AS sala,
                          to_char(chamado.data_abertura, 'DD/MM/YYYY') AS data_abertura
                        FROM chamado
                        INNER JOIN aluno USING(aluno)
                        INNER JOIN problema USING(problema)
                        INNER JOIN sala USING(sala)
                    )
                SELECT * FROM chamados
                ORDER BY chamado ASC";
                $res = pg_query($con, $sql);

                if (pg_num_rows($res) > 0) {

                    for ($i = 0; $i < pg_num_rows($res); $i++) {
                        $chamado = pg_fetch_result($res, $i, 'chamado');
                        $nome_aluno = pg_fetch_result($res, $i, 'nome_aluno');
                        $problema_descricao = pg_fetch_result($res, $i, 'problema_descricao');
                        $chamado_descricao_problema = pg_fetch_result($res, $i, 'chamado_descricao_problema');
                        $sala = pg_fetch_result($res, $i, 'sala');
                        $data_cadastro = pg_fetch_result($res, $i, 'data_abertura');
                    ?>

                        <tr>
                            <td><?=$chamado?></td>
                            <td><?=$nome_aluno?></td>
                            <td><?=$problema_descricao?></td>
                            <td><?=$chamado_descricao_problema?></td>
                            <td><?=$sala?></td>
                            <td><?=$data_cadastro?></td>
                            <td><a href="formulario_chamado.php?chamado=<?= $chamado?>"><button class="btn btn-warning">Editar</button></a>
                            </td>
                        </tr>

              <?php } ?>

          <?php } else { ?>
            <tr>
                <td colspan="8" class="text-center">Nenhum chamado cadastrado</td>
            </tr>
          <?php } ?>

            </tbody>
        </table>
    </div>
</div>

<?php include 'rodape.php' ?>
