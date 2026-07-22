<?php

namespace ECidade\Educacao\Secretaria\BNCC\Service;

use ECidade\Educacao\Secretaria\BNCC\Interfaces\PlanilhaHabilidadeInterface as InterfaceAlias;

/**
 * Class PlanilhaHabilidadeEnsinoFundamentalService
 * @package ECidade\Educacao\Secretaria\BNCC\Service
 */
class PlanilhaHabilidadeEnsinoFundamentalService extends PlanilhaHabilidadeService implements InterfaceAlias
{
    const COLUNA_DISCIPLINA = 0;
    const COLUNA_ETAPA = 1;
    const COLUNA_UNIDADE_TEMATICA = 2;
    const COLUNA_OBJETO_CONHECIMENTO = 3;
    const COLUNA_HABILIDADE = 4;

    protected $tabela = 'escola.bnccensinofundamental';

    protected $sequence = "nextval('bnccensinofundamental_ed148_sequencial_seq')";

    protected $colunas = [
        'ed148_sequencial',
        'ed148_disciplina',
        'ed148_etapa',
        'ed148_codigo',
        'ed148_unidade_tematica',
        'ed148_objeto_conhecimento',
        'ed148_habilidade',
    ];

    public function processarLinhas()
    {
        foreach ($this->linhas as $linha) {
            if (!is_array($linha)) {
                continue;
            }
            $habilidade = $this->removeQuebraLinha($linha[self::COLUNA_HABILIDADE]);
            $codigo = $this->extractCodigo($habilidade);

            $disciplina = pg_escape_string($linha[self::COLUNA_DISCIPLINA]);
            $etapa = pg_escape_string($linha[self::COLUNA_ETAPA]);
            $unidade = pg_escape_string($this->removeQuebraLinha($linha[self::COLUNA_UNIDADE_TEMATICA]));
            $objeto = pg_escape_string($this->removeQuebraLinha($linha[self::COLUNA_OBJETO_CONHECIMENTO]));
            $habilidade = pg_escape_string($habilidade);
            $this->dados[] = [
                $this->sequence,
                "'{$disciplina}'",
                "'{$etapa}'",
                "'{$codigo}'",
                "'{$unidade}'",
                "'{$objeto}'",
                "'{$habilidade}'",
            ];
        }
    }
}
