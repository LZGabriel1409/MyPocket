<?php
session_start();

require_once "classes/Transacao.php";
require_once "classes/Carteira.php";

try {
    $valor = $_POST["valor"];
    $descricao = $_POST["descricao"];
    $data = $_POST["data"];
    $tipo = $_POST["tipo"];

    $transacao = new Transacao(
        $valor,
        $descricao,
        $data,
        $tipo
    );

    $carteira = new Carteira();

    if ($tipo == "entrada"){
        $carteira->adicionarReceita(
            $transacao
        );
    } else{
        $carteira->adicionarDespesa(
            $transacao
        );
    }

    $_SESSION["mensagem"] =
        "Transação cadastrada com sucesso!";
}
catch (Exception $erro){
    $_SESSION["erro"] =
        $erro->getMessage();
}

header("Location: index.php");
exit();