<?php
class Carteira
{
    private $saldo;
    private $historico;

    public function __construct()
    {
        $this->saldo = isset($_SESSION["saldo"])
            ? $_SESSION["saldo"] : 0;
        $this->historico = isset($_SESSION["historico"])
            ? $_SESSION["historico"] : array();
    }

    public function adicionarReceita($transacao)
    {
        $this->saldo += $transacao->getValor();
        $this->historico[] = array(
            "valor" => $transacao->getValor(),
            "descricao" => $transacao->getDescricao(),
            "data" => $transacao->getData(),
            "tipo" => $transacao->getTipo()
        );
        $this->salvar();
    }

    public function adicionarDespesa($transacao)
    {
        if ($transacao->getValor() > $this->saldo)
        {
            throw new Exception(
                "Saldo insuficiente!"
            );
        }

        $this->saldo -= $transacao->getValor();
        $this->historico[] = array(
            "valor" => $transacao->getValor(),
            "descricao" => $transacao->getDescricao(),
            "data" => $transacao->getData(),
            "tipo" => $transacao->getTipo()
        );
        $this->salvar();
    }

    private function salvar()
    {
        $_SESSION["saldo"] = $this->saldo;
        $_SESSION["historico"] = $this->historico;
    }

    public function getSaldo()
    {
        return $this->saldo;
    }

    public function getHistorico()
    {
        return $this->historico;
    }
}