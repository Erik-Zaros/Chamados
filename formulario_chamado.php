<?php

include 'conecta.php';

$btn_acao = $_REQUEST['btn_acao'];

if (isset($btn_acao) && $btn_acao == 'gravar') {

    $aluno = trim($_POST['aluno']);
    $problema = trim($_POST['problema']);
    $descricao = trim($_POST['descricao']);
    $sala = trim($_POST['sala']);

    $msg_erro = "";
    $msg_sucesso = "";

    if (empty($aluno)) {
        $msg_erro .= "Selecione o aluno <br>";
    }

    if (empty($problema)) {
        $msg_erro .= "Selecione o problema <br>";
    }

    if (empty($descricao)) {
        $msg_erro .= "Preencha a descrição<br>";
    }

        $sql_valida = "SELECT aluno, problema, sala
                            FROM chamado
                            WHERE aluno = $aluno
                            AND problema = $problema
                            AND sala = $sala
                        ";
        $res_valida = pg_query($con, $sql_valida);

        if (pg_num_rows($res_valida) > 0) {
            $msg_erro .= "Chamado já cadastrado para este aluno, problema e sala!";
        }

    if (strlen($msg_erro) === 0) {
        pg_query($con, "BEGIN");

        $sql = "INSERT INTO chamado (aluno, problema, descricao_problema, sala) VALUES
                ($aluno, $problema, '$descricao', $sala)
            ";
        $res = pg_query($con, $sql);

        if ($res) {
            pg_query($con, "COMMIT");
            header("Location: formulario_chamado.php?gravado=ok");
            exit;
        } else {
            pg_query($con, "ROLLBACK");
            $msg_erro .= "Erro ao cadastrar Chamado";
        }
    }
}

if (isset($_GET['gravado']) && $_GET['gravado'] === 'ok') {
    $msg_sucesso = "Chamado cadastrado com sucesso";
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

            <form action="formulario_chamado.php" method="POST">

                <div class="mb-3">
                    <label for="aluno" class="form-label">Aluno</label>
                    <select class="form-select" name="aluno" id="aluno">
                        <option value="">Selecione</option>
                        <?php
                            $sql_aluno = "SELECT aluno,
                                                 concat(ra, ' - ', nome) as nome_aluno
                                                FROM aluno
                                            ";
                              $res_aluno = pg_query($con, $sql_aluno);

                              if (pg_num_rows($res_aluno) > 0) {
                                while ($dados = pg_fetch_array($res_aluno)) {
                                    echo "<option value='{$dados['aluno']}'>{$dados['nome_aluno']}</option>";
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
                                    echo "<option value='{$dados['problema']}'>{$dados['descricao']}</option>";
                                }
                              }
                        ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="descricao" class="form-label">Descrição</label>
                    <textarea class="form-control" name="descricao" value="<?=$descricao?>"></textarea>
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
                                    echo "<option value='{$dados['sala']}'>{$dados['descricao']}</option>";
                                }
                              }
                        ?>
                    </select>
                </div>

                <div class="d-flex justify-content-between">
                    <button type="submit" name="btn_acao" class="btn btn-primary" value="gravar">Gravar</button>
                    <!-- <a href="menu.html" class="btn btn-secondary">Voltar</a> -->
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
                </tr>
            </thead>
            <tbody>

                <?php

                $sql = "SELECT chamado.chamado,
                               concat(aluno.ra, ' - ', aluno.nome) as nome_aluno,
                               problema.descricao as problema_descricao,
                               chamado.descricao_problema as chamado_descricao_problema,
                               concat(sala.numero, ' - ', sala.descricao) as sala,
                               to_char(data_abertura, 'DD/MM/YYYY') as data_abertura
                            FROM chamado
                            INNER JOIN aluno USING(aluno)
                            INNER JOIN problema USING(problema)
                            INNER JOIN sala USING(sala)
                            ORDER BY chamado.chamado ASC
                        ";
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
                        </tr>

              <?php } ?>

          <?php } else { ?>
            <tr>
                <td colspan="5" class="text-center">Nenhum chamado cadastrado</td>
            </tr>
          <?php } ?>

            </tbody>
        </table>
    </div>
</div>

<?php include 'rodape.php' ?>

