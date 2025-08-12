<?php

include 'conecta.php';

$btn_acao = $_REQUEST['btn_acao'];

if (isset($btn_acao) && $btn_acao == 'gravar') {
    $numero = trim($_POST['numero']);
    $descricao = trim($_POST['descricao']);
    $sala = $_GET['sala'];

    $msg_erro = "";
    $msg_sucesso = "";

    if (empty($numero)) {
        $msg_erro .= "Preencha o campo Número <br>";
    }

    if (!empty($numero) && !is_numeric($numero)) {
        $msg_erro .= "Digitar somente números no campo Número Sala <br>";
    }

    if (empty($descricao)) {
        $msg_erro .= "Preencha o campo Descrição <br>";
    }

    if (!empty($sala)) {
        $cond_sql = " AND sala != $sala";
    }

    if (!empty($numero) && !empty($descricao) && is_numeric($numero)) {
        $sql_valida = "SELECT numero, descricao
                       FROM sala
                       WHERE numero = $numero
                       $cond_sql";
        $res_valida = pg_query($con, $sql_valida);

        if (pg_num_rows($res_valida) > 0) {
            $numero_existente = pg_fetch_result($res_valida, 0, 'numero');
            $msg_erro .= "Número da Sala: $numero_existente já existente!<br>";
        }
    }

    if (strlen($msg_erro) === 0 && strlen($sala) == 0) {

        pg_query($con, "BEGIN");

        $sql = "INSERT INTO sala (numero, descricao) VALUES
                ($numero, '$descricao')
            ";
        $res = pg_query($con, $sql);

        if ($res) {
            pg_query($con, "COMMIT");
            $msg_sucesso = "Sala cadastrada com sucesso";
            unset($_GET);
            unset($_POST);
            echo "<meta http-equiv=refresh content=\"2;URL=formulario_sala.php\">";
        } else {
            pg_query($con, "ROLLBACK");
            $msg_erro .= "Erro ao cadastrar Sala";
        }
    } elseif (strlen($msg_erro) === 0 && strlen($sala) > 0) {
        pg_query($con, "BEGIN");

        $sql = "UPDATE sala SET numero = $numero,
                       descricao = '$descricao'
                       WHERE sala = $sala
        ";
        $res = pg_query($con, $sql);

        if ($res) {
            pg_query($con, "COMMIT");
            $msg_sucesso = "Sala atualizada com sucesso!";
            unset($_GET);
            unset($_POST);
            echo "<meta http-equiv=refresh content=\"2;URL=formulario_sala.php\">";
        }
    }
}

if (isset($_GET['sala'])) {

    $sala = $_GET['sala'];

    $sql = "SELECT sala,
                   numero,
                   descricao
            FROM sala
            WHERE sala = $sala
        ";
    $res = pg_query($con, $sql);

    if (pg_num_rows($res) > 0) {
        $numero = pg_fetch_result($res, 0, 'numero');
        $descricao = pg_fetch_result($res, 0, 'descricao');
    }
}

include 'menu.php';

?>

<style>
    .nav-tabs .nav-link.active {
        background-color: green; /*Muda cor */
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
<h2 class="text-center mt-4">Cadastro de Sala</h2>

<?php if (!empty($msg_erro)) { ?>
    <div class="container mt-2 alert alert-danger" role="alert">
        <?= $msg_erro ?>
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
                    <label for="ra" class="form-label">Número Sala</label>
                    <input type="text" name="numero" class="form-control" value="<?=$numero?>" placeholder="Digite o Número da Sala">
                </div>

                <div class="mb-3">
                    <label for="nome" class="form-label">Descrição</label>
                    <input type="text" name="descricao" class="form-control" value="<?=$descricao?>" placeholder="Digite a Descrição da Sala">
                </div>

                <div class="d-flex justify-content-between">
                    <button type="submit" name="btn_acao" class="btn btn-primary" value="gravar">Gravar</button>
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
                    <th>ID</th>
                    <th>NÚMERO</th>
                    <th>DESCRIÇÃO</th>
                    <th>DATA CADASTRO</th>
                    <th>AÇÕES</th>
                </tr>
            </thead>
            <tbody>

                <?php

                $sql = "SELECT sala as id,
                               numero,
                               descricao,
                               to_char(data_input, 'DD/MM/YYYY') as data_cadastro
                            FROM sala
                            ";
                $res = pg_query($con, $sql);

                if (pg_num_rows($res) > 0) {

                    for ($i = 0; $i < pg_num_rows($res); $i++) {
                        $id = pg_fetch_result($res, $i, 'id');
                        $numero = pg_fetch_result($res, $i, 'numero');
                        $descricao = pg_fetch_result($res, $i, 'descricao');
                        $data_cadastro = pg_fetch_result($res, $i, 'data_cadastro');
                    ?>
                        <tr>
                            <td><?=$id?></td>
                            <td><?=$numero?></td>
                            <td><?=$descricao?></td>
                            <td><?=$data_cadastro?></td>
                            <td><a href="formulario_sala.php?sala=<?= $id?>"><button class='btn btn-warning'>Editar</button></a></td>
                        </tr>

              <?php } ?>

          <?php } else { ?>
            <tr>
                <td colspan="5" class="text-center">Nenhuma sala cadastrada</td>
            </tr>
          <?php } ?>

            </tbody>
        </table>
    </div>
</div>

<?php include 'rodape.php' ?>
