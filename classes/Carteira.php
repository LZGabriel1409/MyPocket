<?php
require_once "conexao.php";

class Carteira{
    private $pdo;

    public function __construct(){
        global $pdo;
        $this->pdo = $pdo;
    }

    private function adicionarTransacao($transacao){
        $sql = "INSERT INTO transacoes
                (valor, descricao, data, tipo)
                VALUES (?, ?, ?, ?)";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            $transacao->getValor(),
            $transacao->getDescricao(),
            $transacao->getData(),
            $transacao->getTipo()
        ]);
    }

    public function adicionarReceita($transacao){
        $this->adicionarTransacao($transacao);
    }

    public function adicionarDespesa($transacao){
        if ($transacao->getValor() > $this->getSaldo()) {
            throw new Exception("Saldo insuficiente!");
        }

        $this->adicionarTransacao($transacao);
    }

    public function getSaldo(){
        $sql = "SELECT SUM(
                    CASE
                        WHEN tipo = 'entrada' THEN valor
                        ELSE -valor
                    END
                )
                FROM transacoes";

        $saldo = $this->pdo->query($sql)->fetchColumn();

        return $saldo ?? 0;
    }

    public function getHistorico(){
        $sql = "SELECT *
                FROM transacoes
                ORDER BY data DESC, id DESC";

        return $this->pdo
            ->query($sql)
            ->fetchAll(PDO::FETCH_ASSOC);
    }
}
