<!DOCTYPE html>
<html lang="pt-br">
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        .titulo {
            background-color: #596d9b;
            color: white;
            padding: 10px;
            margin-bottom: 0px;
            border-radius: 4px;
        }
        #clienteForm {
            background-color: #D9E2EF;
            padding: 20px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .nav-tabs {
            justify-content: center;
        }
        .nav-tabs .nav-item {
            margin-bottom: -1px;
        }
        .nav-tabs .nav-link {
            border: 1px solid #ddd;
            border-top-left-radius: 5px;
            border-top-right-radius: 5px;
            color: #000;
            margin-right: 2px;
        }
        .sub-nav {
            display: flex;
            justify-content: center;
            background-color: #f8f9fa;
            padding: 10px 0;
        }
        .sub-nav a {
            margin: 0 10px;
            color: #000;
            text-decoration: none;
        }
        .sub-nav a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

  <div class="container mt-3">
    <ul class="nav nav-tabs">
      <li class="nav-item">
        <a class="nav-link" href="formulario_chamado.php">Chamado</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="formulario_aluno.php">Aluno</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="formulario_sala.php">Sala</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="consulta_chamado.php">Consulta Chamado</a>
      </li>
    </ul>
  </div>
