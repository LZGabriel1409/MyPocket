<?php
session_start();

require_once "classes/Carteira.php";

$carteira = new Carteira();

$mensagem = $_SESSION["mensagem"] ?? "";
$erro = $_SESSION["erro"] ?? "";

unset($_SESSION["mensagem"]);
unset($_SESSION["erro"]);

$editar = null;
if (isset($_GET["editar"])) {
    $idEditar = filter_input(INPUT_GET, "editar", FILTER_VALIDATE_INT);

    if ($idEditar) {
        $editar = $carteira->getTransacao($idEditar);

        if (!$editar) {
            $erro = "Transação não encontrada.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gerenciador Financeiro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
<div class="container mt-4 mb-5">
    <h1 class="text-center mb-4">Gerenciador Financeiro</h1>

    <?php if ($mensagem !== "") { ?>
        <div class="alert alert-success">
            <?php echo htmlspecialchars($mensagem); ?>
        </div>
    <?php } ?>

    <?php if ($erro !== "") { ?>
        <div class="alert alert-danger">
            <?php echo htmlspecialchars($erro); ?>
        </div>
    <?php } ?>

    <div class="card mb-4">
        <div class="card-body text-center">
            <h3>Saldo Atual</h3>
            <h1>R$ <?php echo number_format($carteira->getSaldo(), 2, ",", "."); ?></h1>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <?php echo $editar ? "Editar Transação" : "Nova Transação"; ?>
        </div>

        <div class="card-body">
            <form action="processar.php" method="POST">

                <input type="hidden" name="acao" value="<?php echo $editar ? "editar" : "criar"; ?>">

                <?php if ($editar) { ?>
                    <input type="hidden" name="id" value="<?php echo (int) $editar["id"]; ?>">
                <?php } ?>

                <div class="mb-3">
                    <label class="form-label">Valor</label>
                    <input
                        type="number"
                        step="0.01"
                        min="0.01"
                        name="valor"
                        class="form-control"
                        value="<?php echo $editar ? htmlspecialchars($editar["valor"]) : ""; ?>"
                        required
                    >
                </div>

                <div class="mb-3">
                    <label class="form-label">Descrição</label>
                    <input
                        type="text"
                        name="descricao"
                        class="form-control"
                        value="<?php echo $editar ? htmlspecialchars($editar["descricao"]) : ""; ?>"
                        required
                    >
                </div>

                <div class="mb-3">
                    <label class="form-label">Data</label>
                    <input
                        type="date"
                        name="data"
                        class="form-control"
                        value="<?php echo $editar ? htmlspecialchars($editar["data"]) : date("Y-m-d"); ?>"
                        required
                    >
                </div>

                <div class="mb-3">
                    <label class="form-label">Tipo</label>
                    <select name="tipo" class="form-select" required>
                        <option value="entrada" <?php echo ($editar && $editar["tipo"] === "entrada") ? "selected" : ""; ?>>Entrada</option>
                        <option value="saida" <?php echo ($editar && $editar["tipo"] === "saida") ? "selected" : ""; ?>>Saída</option>
                    </select>
                </div>

                <button class="btn btn-primary">
                    <?php echo $editar ? "Salvar alterações" : "Cadastrar"; ?>
                </button>

                <?php if ($editar) { ?>
                    <a href="index.php" class="btn btn-secondary">Cancelar</a>
                <?php } ?>

            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">Histórico</div>

        <div class="card-body">
            <?php $historico = $carteira->getHistorico(); ?>

            <?php if (count($historico) === 0) { ?>
                <p class="text-center mb-0">Nenhuma transação cadastrada.</p>
            <?php } else { ?>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Data</th>
                                <th>Descrição</th>
                                <th>Tipo</th>
                                <th>Valor</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($historico as $item) { ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($item["data"]); ?></td>
                                    <td><?php echo htmlspecialchars($item["descricao"]); ?></td>
                                    <td>
                                        <?php if ($item["tipo"] === "entrada") { ?>
                                            <span class="badge bg-success">Entrada</span>
                                        <?php } else { ?>
                                            <span class="badge bg-danger">Saída</span>
                                        <?php } ?>
                                    </td>
                                    <td>R$ <?php echo number_format($item["valor"], 2, ",", "."); ?></td>
                                    <td>
                                        <a href="index.php?editar=<?php echo (int) $item["id"]; ?>" class="btn btn-sm btn-warning">
                                            Editar
                                        </a>

                                        <form action="processar.php" method="POST" class="d-inline" onsubmit="return confirm('Deseja realmente excluir esta transação?');">
                                            <input type="hidden" name="acao" value="excluir">
                                            <input type="hidden" name="id" value="<?php echo (int) $item["id"]; ?>">
                                            <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
</body>
</html>
