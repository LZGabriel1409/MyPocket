<?php
session_start();

require_once "classes/Carteira.php";

$carteira = new Carteira();

$mensagem = isset($_SESSION["mensagem"])
    ? $_SESSION["mensagem"] : "";

$erro = isset($_SESSION["erro"])
    ? $_SESSION["erro"] : "";

unset($_SESSION["mensagem"]);
unset($_SESSION["erro"]);
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
<div class="container mt-4">
    <h1 class="text-center mb-4">Gerenciador Financeiro</h1>

    <?php if($mensagem != "") { ?>
    <div class="alert alert-success">
        <?php echo $mensagem; ?>
    </div>
    <?php } ?>

    <?php if($erro != "") { ?>
    <div class="alert alert-danger">
        <?php echo $erro; ?>
    </div>
    <?php } ?>

    <div class="card mb-4">
        <div class="card-body text-center">
            <h3>Saldo Atual</h3>
            <h1>R$<?php echo number_format($carteira->getSaldo(),2,",","."); ?></h1>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">Nova Transação</div>

        <div class="card-body">
            <form action="processar.php" method="POST">

            <div class="mb-3">
                <label>Valor</label>
                <input type="number" step="0.01" name="valor" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Descrição</label>
                <input type="text" name="descricao" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Data</label>
                <input type="date" name="data" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Tipo</label>
                <select name="tipo" class="form-select">
                    <option value="entrada">Entrada</option>
                    <option value="saida">Saída</option>
                </select>
            </div>

            <button class="btn btn-primary w-100">Cadastrar</button>

            </form>

        </div>
    </div>

    <div class="card">
        <div class="card-header">Histórico</div>

        <div class="card-body">
            <table class="table">
                <tr>
                    <th>Data</th>
                    <th>Descrição</th>
                    <th>Tipo</th>
                    <th>Valor</th>
                </tr>

                <?php foreach(array_reverse($carteira->getHistorico()) as $item){ ?>

                <tr>
                    <td><?php echo $item["data"]; ?></td>
                    <td><?php echo $item["descricao"]; ?></td>
                    <td>
                        <?php if($item["tipo"] == "entrada"){ ?>
                        <span class="badge bg-success">Entrada</span>
                        <?php } else{ ?>
                        <span class="badge bg-danger">Saída</span>
                        <?php } ?>
                    </td>
                    <td>R$ <?php echo number_format($item["valor"],2,",","."); ?></td>
                </tr>
                <?php } ?>
            </table>
        </div>
    </div>
</div>

</body>
</html>