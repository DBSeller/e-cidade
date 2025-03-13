<?php


namespace App\Domain\Financeiro\Planejamento\Mappers;

use ECidade\Financeiro\Contabilidade\PlanoDeContas\EstruturalReceita;

/**
 * Class ProjecaoReceitaMapper
 * @package App\Domain\Financeiro\Planejamento\Mappers
 */
class ProjecaoReceitaMapper
{
    /**
     * código da receita - codrec
     * @todo vou tentar sem ele
     * @var integer
     */
    public $reduzido;

    /**
     * @var EstruturalReceita
     */
    public $estrutural;
    /**
     * @var integer
     */
    public $codigoFonte;
    /**
     * @var string
     */
    public $fonte;
    /**
     * @var string
     */
    public $descricao;
    /**
     * @var integer
     */
    public $ano;
    /**
     * @var integer
     */
    public $orgao;
    /**
     * @var integer
     */
    public $unidade;
    /**
     * @var $instituicao
     */
    public $instituicao;
    /**
     * @var string
     */
    public $caracteristicaPeculiar;

    /**
     * @var float
     */
    public $valorBase;
    /**
     * @var array
     */
    public $valoresProjetados = [];

    /**
     * @var string
     */
    public $esferaOrcamentaria;

    /**
     * @var integer
     */
    public $recurso;

    /**
     * @var boolean
     */
    public $manual;
    /**
     * @var \App\Domain\Financeiro\Planejamento\Models\Planejamento
     */
    public $planejamento;

    /**
     * @return object
     */
    public function toArray()
    {
        return (object) [
            'codigoFonte' => $this->codigoFonte,
            'reduzido' => $this->reduzido,
            'fonte' => $this->fonte,
            'descricao' => $this->descricao,
            'ano' => $this->ano,
            'orgao' => $this->orgao,
            'unidade' => $this->unidade,
            'instituicao' => $this->instituicao,
            'caracteristicaPeculiar' => $this->caracteristicaPeculiar,
            'valorBase' => $this->valorBase,
            'esferaOrcamentaria' => $this->esferaOrcamentaria,
            'manual' => $this->manual,
        ];
    }
}
