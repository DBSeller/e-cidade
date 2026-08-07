<?php

namespace ECidade\RecursosHumanos\ESocial\Integracao\Formatter;

use ECidade\RecursosHumanos\ESocial\Integracao\Formatter\Formatter;

/**
 * Formata os dados de Comunicação de Acidente de Trabalho
 *
 * @package ECidade\RecursosHumanos\ESocial\Integracao\Formatter
 * @author Fabio Egidio <fabio.egidio@dbseller.com.br>
 */
class MonitoriamentoSaudeFormatter extends Formatter
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
        foreach ($dadosFormatado as &$dadoMonitoriamentoSaude) {
            if (isset($dadoMonitoriamentoSaude->ideVinculo)) {
                if (empty($dadoMonitoriamentoSaude->ideVinculo->matricula)) {
                    unset($dadoMonitoriamentoSaude->ideVinculo->matricula);
                }

                if (empty($dadoMonitoriamentoSaude->ideVinculo->codCateg)) {
                    unset($dadoMonitoriamentoSaude->ideVinculo->codCateg);
                }
            }
            if (isset($dadoMonitoriamentoSaude->exMedOcup->aso)) {
                if (empty($dadoMonitoriamentoSaude->exMedOcup->aso->resAso)) {
                    unset($dadoMonitoriamentoSaude->exMedOcup->aso->resAso);
                }
            }
            if (isset($dadoMonitoriamentoSaude->exMedOcup->aso->exame)) {
                if (empty($dadoMonitoriamentoSaude->exMedOcup->aso->exame->obsProc)) {
                    unset($dadoMonitoriamentoSaude->exMedOcup->aso->exame->obsProc);
                }
                if (empty($dadoMonitoriamentoSaude->exMedOcup->aso->exame->ordExame)) {
                    unset($dadoMonitoriamentoSaude->exMedOcup->aso->exame->ordExame);
                }
                if (empty($dadoMonitoriamentoSaude->exMedOcup->aso->exame->indResult)) {
                    unset($dadoMonitoriamentoSaude->exMedOcup->aso->exame->indResult);
                }
            }

            if (isset($dadoMonitoriamentoSaude->exMedOcup->respMonit)) {
                if (empty($dadoMonitoriamentoSaude->exMedOcup->respMonit->cpfResp)) {
                    unset($dadoMonitoriamentoSaude->exMedOcup->aso->cpfResp);
                }
            }
        }

        return $dadosFormatado;
    }
}
