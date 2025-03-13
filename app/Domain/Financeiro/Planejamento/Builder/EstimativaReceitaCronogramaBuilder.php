<?php

namespace App\Domain\Financeiro\Planejamento\Builder;

use App\Domain\Financeiro\Planejamento\Mappers\EstimativaReceitaCronogramaMapper;
use ECidade\Financeiro\Contabilidade\PlanoDeContas\EstruturalReceita;

class EstimativaReceitaCronogramaBuilder extends EstimativaReceitaBuilder
{
    protected $mapper;

    public function __construct()
    {
        $this->mapper = new EstimativaReceitaCronogramaMapper();
    }

    public function buildAnalitico($dados, EstruturalReceita $estrutural, $temDesdobramento = false)
    {
        $this->defaultData($estrutural, $dados, $temDesdobramento);
        $this->mapper->setIdCronograma($dados->id_cronograma);
        $this->mapper->setExercicioMeta($dados->exercicio);
        $this->mapper->setValorBase((float)$dados->novo_valor_base);
        $this->mapper->setJaneiro((float)$dados->janeiro);
        $this->mapper->setFevereiro((float)$dados->fevereiro);
        $this->mapper->setMarco((float)$dados->marco);
        $this->mapper->setAbril((float)$dados->abril);
        $this->mapper->setMaio((float)$dados->maio);
        $this->mapper->setJunho((float)$dados->junho);
        $this->mapper->setJulho((float)$dados->julho);
        $this->mapper->setAgosto((float)$dados->agosto);
        $this->mapper->setSetembro((float)$dados->setembro);
        $this->mapper->setOutubro((float)$dados->outubro);
        $this->mapper->setNovembro((float)$dados->novembro);
        $this->mapper->setDezembro((float)$dados->dezembro);

        return $this->mapper->toArray();
    }
}
