<?php

namespace App\Domain\Financeiro\Orcamento\Mapper;

class AcompanhamentoReceitaMapper extends ReceitaMapper
{
    public $idCronograma;
    public $exercicio;
    public $baseCalculo;
    public $valorBase = 0;
    public $janeiro = 0;
    public $fevereiro = 0;
    public $marco = 0;
    public $abril = 0;
    public $maio = 0;
    public $junho = 0;
    public $julho = 0;
    public $agosto = 0;
    public $setembro = 0;
    public $outubro = 0;
    public $novembro = 0;
    public $dezembro = 0;

    /**
     * @return mixed
     */
    public function toArray()
    {
        $x = [
            "idCronograma" => $this->idCronograma,
            "exercicio" => $this->exercicio,
            "baseCalculo" => $this->baseCalculo,
            "valorBase" => $this->valorBase,
            "janeiro" => $this->janeiro,
            "fevereiro" => $this->fevereiro,
            "marco" => $this->marco,
            "abril" => $this->abril,
            "maio" => $this->maio,
            "junho" => $this->junho,
            "julho" => $this->julho,
            "agosto" => $this->agosto,
            "setembro" => $this->setembro,
            "outubro" => $this->outubro,
            "novembro" => $this->novembro,
            "dezembro" => $this->dezembro
        ];
        return array_merge(parent::toArray(), $x);
    }
}
