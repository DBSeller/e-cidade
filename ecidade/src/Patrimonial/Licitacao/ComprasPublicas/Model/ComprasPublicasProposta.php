<?php
namespace ECidade\Patrimonial\Licitacao\ComprasPublicas\Model;

class ComprasPublicasProposta
{
  
    private $idItem;
    private $liclicitem;
    private $data;
    private $hora;
    private $idFornecedor;
    private $modelo;
    private $marca;
    private $fabricante;
    private $detalhamento;
    private $validadeProposta;
    private $valorUnitario;
    private $valorDesconto;
    private $valorTotal;
    private $valido = false;

    public function __construct(
        $idItem,
        $liclicitem,
        $data,
        $hora,
        $idFornecedor,
        $modelo,
        $marca,
        $fabricante,
        $detalhamento,
        $validadeProposta,
        $valorUnitario,
        $valorDesconto,
        $valorTotal,
        $valido
    ) {
        $this->idItem           = $idItem;
        $this->liclicitem       = $liclicitem;
        $this->data             = $data;
        $this->hora             = $hora;
        $this->idFornecedor     = $idFornecedor;
        $this->modelo           = $modelo;
        $this->marca            = $marca;
        $this->fabricante       = $fabricante;
        $this->detalhamento     = $detalhamento;
        $this->validadeProposta = $validadeProposta;
        $this->valorUnitario    = $valorUnitario;
        $this->valorDesconto    = $valorDesconto;
        $this->valorTotal       = $valorTotal;
        $this->valido           = $valido;
    }

    public function setData($data)
    {
        $this->$data = $data;
    }
  
    public function setHora($hora)
    {
        $this->hora = $hora;
    }

    public function setValorTotal($valorTotal)
    {
        $this->valorTotal = $valorTotal;
    }

    public function setValorUnitario($valorUnitario)
    {
        $this->valorUnitario = $valorUnitario;
    }

    public function setDesconto($valorDesconto)
    {
        $this->valorDesconto = $valorDesconto;
    }

    public function getValorTotal()
    {
        return $this->valorTotal;
    }

    public function getValorUnitario()
    {
        return $this->valorUnitario;
    }

    public function getDesconto()
    {
        return $this->valorDesconto;
    }

    public function getMarca()
    {
        return $this->marca;
    }

    public function getFornecedor()
    {
        return $this->idFornecedor;
    }

    public function getData()
    {
        return $this->data;
    }
}
