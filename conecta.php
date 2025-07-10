<?php

$con = pg_connect("host=localhost dbname=chamados user=erikzaros password=");

if (!$con) {
    die("
    <div style='
        background-color: #f8d7da;
        color: #721c24;
        padding: 20px;
        border: 1px solid #f5c6cb;
        border-radius: 8px;
        font-family: Arial, sans-serif;
        max-width: 500px;
        margin: 40px auto;
        text-align: center;
    '>
        <h2 style='margin-top: 0;'>Erro de Conexão</h2>
        <p>Não foi possível conectar ao banco de dados.</p>
        <p style='font-size: 14px; color: #a94442;'>Verifique as credenciais, o host, ou se o servidor está ativo.</p>
        <p style='font-size: 12px; margin-top: 15px;'></p>
    </div>
    ");
}
