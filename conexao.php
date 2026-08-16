<?php

$host = "127.0.0.1";
$usuario = "root";
$senha = "";
$banco = "mypocket";

$conexao = new mysqli($host, $usuario, $senha, $banco);

if ($conexao->connect_error) {
    die("Erro na conexão com o banco: " . $conexao->connect_error);
}

$conexao->set_charset("utf8mb4");

?>
