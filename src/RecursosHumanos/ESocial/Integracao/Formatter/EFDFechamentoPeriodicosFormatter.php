<?php

namespace ECidade\RecursosHumanos\ESocial\Integracao\Formatter;

use cl_avaliacaogruporespostafechamentoefd;
use DBException;
use db_utils;

/**
 * Formata os dados da Rubrica
 *
 * @package ECidade\RecursosHumanos\ESocial\Integracao\Formatter
 */
class EFDFechamentoPeriodicosFormatter extends Formatter
{
    /**
     * Realiza a formatação dos dados para envio da API
     *
     * @param array $dados
     * @return array
     */
    public function formatar($dados)
    {
        $dadosFormatado = parent::formatar($dados);
        return $this->posProcessamento($dadosFormatado);
    }

    /**
     * Realiza uma consistencia nos dados enviados
     *
     * @param array  $dadosFormatado
     * @return array
     */
    private function posProcessamento($dadosFormatado)
    {
        foreach ($dadosFormatado as $dados) {
            $dados->infoFech->evtServPr = $dados->infoFech->evtServPr == "1" ? "S" : "N";
            $dados->infoFech->evtServTm = $dados->infoFech->evtServTm == "1" ? "S" : "N";
            $dados->infoFech->evtAssDespRec = "N";
            $dados->infoFech->evtAssDespRep = "N";
            $dados->infoFech->evtComProd = "N";
            $dados->infoFech->evtCPRB = "N";
            $dados->infoFech->evtAquis = "N";
            $dados->ideRespInf->perApur = $dados->referencia;
            unset($dados->infoFech->compSemMovto);
        }
        $this->unsetEmpty($dadosFormatado);

        return $dadosFormatado;
    }
}
