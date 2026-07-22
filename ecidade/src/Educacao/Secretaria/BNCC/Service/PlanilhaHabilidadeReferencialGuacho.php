<?php

namespace ECidade\Educacao\Secretaria\BNCC\Service;

use ECidade\Educacao\Secretaria\BNCC\Interfaces\PlanilhaHabilidadeInterface as InterfaceAlias;

/**
 * Class PlanilhaHabilidadeEnsinoFundamentalService
 * @package ECidade\Educacao\Secretaria\BNCC\Service
 */
class PlanilhaHabilidadeReferencialGuacho extends PlanilhaHabilidadeService implements InterfaceAlias
{
    const COLUNA_ETAPA = 0;
    const COLUNA_HABILIDADE_BNCC = 1;
    const COLUNA_HABILIDADE_REFERENCIAL = 2;

    protected $tabela = 'escola.bnccreferencial';

    protected $sequence = "nextval('bnccreferencial_ed168_codigo_seq')";

    protected $colunas = [
        'ed168_codigo',
        'ed168_ensino',
        'ed168_etapa',
        'ed168_codigohabilidade',
        'ed168_codigoreferencial',
        'ed168_habilidade',
        'ed168_ano',
    ];

    /**
     * @var string
     */
    private $tipoEnsino;
    /**
     * @var integer
     */
    private $ano;

    /**
     * PlanilhaHabilidadeReferencialGuacho constructor.
     * @param string $tipoEnsino
     */
    public function __construct($tipoEnsino)
    {
        $this->tipoEnsino = $tipoEnsino;
        $this->ano = date('Y');
    }

    public function processarLinhas()
    {
        foreach ($this->linhas as $linha) {
            if (!is_array($linha)) {
                continue;
            }
            $habilidadeReferencial = $this->removeQuebraLinha($linha[self::COLUNA_HABILIDADE_REFERENCIAL]);
            $codigoReferencial = $this->extractCodigo($habilidadeReferencial);
            if (empty($codigoReferencial)) {
                continue;
            }

            $habilidadeBncc = $this->removeQuebraLinha($linha[self::COLUNA_HABILIDADE_BNCC]);
            $codigoBncc = $this->extractCodigo($habilidadeBncc);


            $etapa = $this->removeQuebraLinha($linha[self::COLUNA_ETAPA]);

            $habilidadeReferencial = str_replace("\n", ' ', $habilidadeReferencial);
            $habilidade = pg_escape_string($habilidadeReferencial);
            $this->dados[] = [
                $this->sequence,
                "'{$this->tipoEnsino}'",
                "'{$etapa}'",
                "'{$codigoBncc}'",
                "'{$codigoReferencial}'",
                "'{$habilidade}'",
                $this->ano
            ];
        }
    }
}
