<?php


namespace ECidade\Educacao\Escola\Factory;

use ECidade\Educacao\Escola\Relatorios\AreaGradeAproveitamentoRelatorio;
use Matricula;
use RelatorioGradeAproveitamento;

/**
 * Class GradeAproveitamentoFactory
 * @package ECidade\Educacao\Escola\Factory
 */
class GradeAproveitamentoFactory
{
    /**
     * @param Matricula $matricula
     * @param $pdf
     * @param $tamanhoLinha
     * @return AreaGradeAproveitamentoRelatorio|RelatorioGradeAproveitamento
     * @throws \Exception
     */
    public static function get(Matricula $matricula, $pdf, $tamanhoLinha)
    {
        db_inicio_transacao();
        $areaProcedimento = $matricula->getDiarioDeClasse()->getAreaProcedimento();
        db_fim_transacao();
        if (is_null($areaProcedimento)) {
            return new RelatorioGradeAproveitamento($pdf, $matricula, $tamanhoLinha);
        }

        return new AreaGradeAproveitamentoRelatorio($pdf, $matricula, $tamanhoLinha);
    }
}
