<?php
session_start();

require_once "classes/Transacao.php";
require_once "classes/Carteira.php";

try {
    $acao = $_POST["acao"] ?? "criar";

    $carteira = new Carteira();

    if ($acao === "criar" || $acao === "editar") {
        $valor = filter_input(INPUT_POST, "valor", FILTER_VALIDATE_FLOAT);
        $descricao = trim($_POST["descricao"] ?? "");
        $data = $_POST["data"] ?? "";
        $tipo = $_POST["tipo"] ?? "";

        if ($valor === false || $valor <= 0) {
            throw new Exception("Informe um valor válido maior que zero.");
        }

        if ($descricao === "") {
            throw new Exception("Informe uma descrição.");
        }

        if ($data === "") {
            throw new Exception("Informe uma data.");
        }

        if ($tipo !== "entrada" && $tipo !== "saida") {
            throw new Exception("Tipo de transação inválido.");
        }

        $transacao = new Transacao($valor, $descricao, $data, $tipo);

        if ($acao === "criar") {
            if ($tipo === "entrada") {
                $carteira->adicionarReceita($transacao);
            } else {
                $carteira->adicionarDespesa($transacao);
            }

            $_SESSION["mensagem"] = "Transação cadastrada com sucesso!";
        } else {
            $id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);

            if (!$id) {
                throw new Exception("ID da transação inválido.");
            }

            $carteira->atualizarTransacao($id, $transacao);
            $_SESSION["mensagem"] = "Transação atualizada com sucesso!";
        }
    } elseif ($acao === "excluir") {
        $id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);

        if (!$id) {
            throw new Exception("ID da transação inválido.");
        }

        $carteira->excluirTransacao($id);
        $_SESSION["mensagem"] = "Transação excluída com sucesso!";
    } else {
        throw new Exception("Ação inválida.");
    }
} catch (Exception $erro) {
    $_SESSION["erro"] = $erro->getMessage();
}

header("Location: index.php");
exit();
