<?php
class Transacao{
    private $valor;
    private $descricao;
    private $data;
    private $tipo;

    public function __construct($valor, $descricao, $data, $tipo){
        $this->valor = $valor;
        $this->descricao = $descricao;
        $this->data = $data;
        $this->tipo = $tipo;
    }

    public function getValor(){
        return $this->valor;
    }

    public function getDescricao(){
        return $this->descricao;
    }

    public function getData(){
        return $this->data;
    }

    public function getTipo(){
        return $this->tipo;
    }
}