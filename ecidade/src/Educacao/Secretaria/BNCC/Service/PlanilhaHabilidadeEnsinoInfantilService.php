<?php

namespace ECidade\Educacao\Secretaria\BNCC\Service;

use ECidade\Educacao\Secretaria\BNCC\Interfaces\PlanilhaHabilidadeInterface;

/**
 * Class PlanilhaHabilidadeEnsinoInfantilService
 * @package ECidade\Educacao\Secretaria\BNCC\Service
 */
class PlanilhaHabilidadeEnsinoInfantilService extends PlanilhaHabilidadeService implements PlanilhaHabilidadeInterface
{
    const COLUNA_DISCIPLINA = 0;
    const COLUNA_FAIXA_ETARIA = 1;
    const COLUNA_HABILIDADE = 2;

    protected $tabela = 'escola.bncceducacaoinfantil';

    protected $sequence = "nextval('bncceducacaoinfantil_ed147_sequencial_seq')";

    protected $colunas = [
        'ed147_sequencial',
        'ed147_disciplina',
        'ed147_faixa_etaria',
        'ed147_codigo',
        'ed147_habilidade',
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
            $faixaEtaria = pg_escape_string($this->removeQuebraLinha($linha[self::COLUNA_FAIXA_ETARIA]));
            $habilidade = pg_escape_string($habilidade);
            $this->dados[] = [
                $this->sequence,
                "'{$disciplina}'",
                "'{$faixaEtaria}'",
                "'{$codigo}'",
                "'{$habilidade}'",
            ];
        }
    }
}
