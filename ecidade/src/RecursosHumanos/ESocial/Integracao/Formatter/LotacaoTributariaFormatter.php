<?php

namespace ECidade\RecursosHumanos\ESocial\Integracao\Formatter;

use ECidade\RecursosHumanos\ESocial\Integracao\Formatter\Formatter;

/**
 * Formata os dados da Lotação Tributária
 *
 * @package ECidade\RecursosHumanos\ESocial\Integracao\Formatter
 * @author Andrio Costa <andrio.costa@dbseller.com.br>
 */

class LotacaoTributariaFormatter extends Formatter
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


    public function posProcessamento($dadosFormatado)
    {

        foreach ($dadosFormatado as $dadoLotacao) {
            $dadoLotacao->referencia = $dadoLotacao->referencia . '-' . $dadoLotacao->ideLotacao->codLotacao;
            if ($dadoLotacao->ideLotacao->fimValid === "") {
                $dadoLotacao->ideLotacao->fimValid = null;
            }

            if (!empty($dadoLotacao->dadosLotacao->tpLotacao)) {
                if (in_array($dadoLotacao->dadosLotacao->tpLotacao, array('01', '10', '21', '24', '90', '91'))) {
                    unset($dadoLotacao->dadosLotacao->tpInsc);
                    unset($dadoLotacao->dadosLotacao->nrInsc);
                }
            }

            if ($dadoLotacao->dadosLotacao->fpasLotacao->codTercsSusp === "") {
                unset($dadoLotacao->dadosLotacao->fpasLotacao->codTercsSusp);
            }

            // grupo procJudTerceiro é opcional
            if (isset($dadoLotacao->dadosLotacao->fpasLotacao->procJudTerceiro)) {
                if (is_array($dadoLotacao->dadosLotacao->fpasLotacao->procJudTerceiro)
                    && sizeof($dadoLotacao->dadosLotacao->fpasLotacao->procJudTerceiro) > 0) {
                    if (!$this->validaSeGrupoFoiPreenchido(
                        get_object_vars($dadoLotacao->dadosLotacao->fpasLotacao->procJudTerceiro[0])
                    )) {
                        unset($dadoLotacao->dadosLotacao->fpasLotacao->procJudTerceiro);
                    }
                } else {
                    unset($dadoLotacao->dadosLotacao->fpasLotacao->procJudTerceiro);
                }
            }
            // grupo infoEmprParcial é opcional
            if (isset($dadoLotacao->dadosLotacao->infoEmprParcial)) {
                if (!$this->validaSeGrupoFoiPreenchido(get_object_vars($dadoLotacao->dadosLotacao->infoEmprParcial))) {
                    unset($dadoLotacao->dadosLotacao->infoEmprParcial);
                }
            }
            // grupo dadosOpPort é opcional
            if (isset($dadoLotacao->dadosLotacao->dadosOpPort)) {
                if (!$this->validaSeGrupoFoiPreenchido(get_object_vars($dadoLotacao->dadosLotacao->dadosOpPort))) {
                    unset($dadoLotacao->dadosLotacao->dadosOpPort);
                } else {
                    if (!empty($dadoLotacao->dadosLotacao->dadosOpPort->fap)) {
                        $dadoLotacao
                            ->dadosLotacao
                            ->dadosOpPort
                            ->fap=(float)$dadoLotacao
                                            ->dadosLotacao
                                            ->dadosOpPort
                                            ->fap;
                    }
                }
            }
        }
        return $dadosFormatado;
    }
}
