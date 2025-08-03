<?php

include 'conecta.php';

$aluno = $_GET['aluno'];

if (isset($_POST['btn_acao']) && $_POST['btn_acao'] == 'gravar') {

    $ra = trim($_POST['ra']);
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $aluno = $_GET['aluno'];

    $msg_erro = "";
    $msg_sucesso = "";

    if (empty($ra)) {
        $msg_erro .= "Preencha o campo RA <br>";
    }

    if (!empty($ra) && !is_numeric($ra)) {
        $msg_erro .= "Digitar somente números no RA <br>";
    }

    if (empty($nome)) {
        $msg_erro .= "Preencha o campo Nome <br>";
    }

    if (empty($email)) {
        $msg_erro .= "Preencha o campo Email <br>";
    }

    if (!empty($ra) && !empty($nome) && !empty($email) && is_numeric($ra)) {

        $aluno_existe = "";
        if (!empty($aluno)) {
            $aluno_existe = "AND aluno <> $aluno";
        }

        $sql_valida = "SELECT ra, nome
                            FROM aluno
                            WHERE ra = $ra
                            {$aluno_existe}
                        ";
        $res_valida = pg_query($con, $sql_valida);

        if (pg_num_rows($res_valida) > 0) {
            $ra_existente = pg_fetch_result($res_valida, 0, 'ra');
            $nome_existente = pg_fetch_result($res_valida, 0, 'nome');
            $msg_erro .= "RA: $ra_existente já existe para o aluno $nome_existente!";
        }
    }

    if (strlen($msg_erro) === 0 && empty($aluno)) {
        pg_query($con, "BEGIN");

        $sql = "INSERT INTO aluno (ra, nome, email) VALUES
                ($ra, '$nome', '$email')
            ";
        $res = pg_query($con, $sql);

        if ($res) {
            pg_query($con, "COMMIT");
            $msg_sucesso = "Aluno cadastrado com sucesso.";
            unset($_GET);
            unset($_POST);
            echo "<meta http-equiv=refresh content=\"2;URL=formulario_aluno.php\">";
        } else {
            pg_query($con, "ROLLBACK");
            $msg_erro .= "Erro ao cadastrar Aluno";
        }
    } elseif (strlen($msg_erro) === 0 && !empty($aluno)) {
        pg_query($con, "BEGIN");

        $sql = "UPDATE aluno SET ra = $ra,
                                 nome = '$nome',
                                 email = '$email'
                                 WHERE aluno = $aluno
                            ";
        $res = pg_query($con, $sql);

        if ($res) {
            pg_query($con, "COMMIT");
            $msg_sucesso = "Aluno atualizado com sucesso.";
            unset($_GET);
            unset($_POST);
            echo "<meta http-equiv=refresh content=\"2;URL=formulario_aluno.php\">";
        } else {
            pg_query($con, "ROLLBACK");
            $msg_erro = "Erro ao atualizar dados aluno";
        }
    }
}

if (isset($_GET['aluno'])) {

    $aluno = $_GET['aluno'];

    $sql = "select ra, nome, email from aluno where aluno = $aluno";
    $res = pg_query($con, $sql);

    if (pg_num_rows($res) > 0) {
        $ra = pg_fetch_result($res, 0, 'ra');
        $nome = pg_fetch_result($res, 0, 'nome');
        $email = pg_fetch_result($res, 0, 'email');
    }
}

include 'menu.php';
?>

<style>
    .nav-tabs .nav-link.active {
        background-color: red; /*Muda cor */
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

<h2 class="text-center mt-4">Cadastro de aluno</h2>

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
                    <label for="ra" class="form-label">RA</label>
                    <input type="text" name="ra" class="form-control" value="<?=$ra?>" placeholder="Digite RA">
                </div>

                <div class="mb-3">
                    <label for="nome" class="form-label">Nome</label>
                    <input type="text" name="nome" class="form-control" value="<?=$nome?>" placeholder="Digite Nome">
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="text" name="email" class="form-control" value="<?=$email?>" placeholder="Digite Email">
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
                    <th>RA</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Data Cadastro</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>

                <?php

                $sql = "SELECT aluno as id,
                               ra,
                               nome,
                               email,
                               to_char(data_input, 'DD/MM/YYYY') as data_cadastro
                            FROM aluno
                            ";
                $res = pg_query($con, $sql);

                $alunos = pg_fetch_all($res);

                if ($alunos) {

                    foreach ($alunos as $aluno) {
                        $id = $aluno['id'];
                        $ra = $aluno['ra'];
                        $nome = $aluno['nome'];
                        $email = $aluno['email'];
                        $data_cadastro = $aluno['data_cadastro'];
                    ?>

                        <tr>
                            <td><?=$id?></td>
                            <td><?=$ra?></td>
                            <td><?=$nome?></td>
                            <td><?=$email?></td>
                            <td><?=$data_cadastro?></td>
                            <td><a href="formulario_aluno.php?aluno=<?= $id?>"><button class="btn btn-warning">Editar</button></a></td>
                        </tr>

              <?php } ?>

          <?php } else { ?>
            <tr>
                <td colspan="5" class="text-center">Nenhum aluno cadastrado</td>
            </tr>
          <?php } ?>

            </tbody>
        </table>
    </div>
</div>

<?php include 'rodape.php' ?>

