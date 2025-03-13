<?php

namespace ECidade\RecursosHumanos\ESocial\Integracao\Formatter;

use ECidade\RecursosHumanos\ESocial\Integracao\Formatter\Formatter;

/**
 * Formata os dados do Cargo
 *
 * @package ECidade\RecursosHumanos\ESocial\Integracao\Formatter
 * @author Augusto Oliveira <augusto.oliveira@dbseller.com.br>
 */
class CargoFormatter extends Formatter
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

            if(!isset($dados->dadosCargo->cargoPublico->acumCargo)) {
                unset($dados->dadosCargo->cargoPublico);
            }

            if (isset($dados->dadosCargo->cargoPublico->leiCargo->dtLei)) {
                $leiCargoDt =  $dados->dadosCargo->cargoPublico->leiCargo->dtLei;
                if (!empty($leiCargoDt)) {
                    $dados->dadosCargo->cargoPublico->leiCargo->dtLei = date('Y-m-d', strtotime($leiCargoDt));
                } else {
                    unset($dados->dadosCargo->cargoPublico->leiCargo->dtLei);
                }
            }

            if (empty($dados->ideCargo->fimValid)) {
                $dados->ideCargo->fimValid = null;
            }

        }

        return $dadosFormatado;
    }
}
