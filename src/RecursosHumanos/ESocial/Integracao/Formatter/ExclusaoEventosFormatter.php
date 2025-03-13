<?php

namespace ECidade\RecursosHumanos\ESocial\Integracao\Formatter;

/**
 * Formata os dados da Exclusão de Eventos
 *
 * @package ECidade\RecursosHumanos\ESocial\Integracao\Formatter
 * @author Andre Mello <andre.mello@dbseller.com.br>
 */
class ExclusaoEventosFormatter extends Formatter
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
     * Realiza uma consistência nos dados enviados
     *
     * @param array  $dadosFormatado
     * @return array
     */
    private function posProcessamento($dados)
    {
        foreach ($dados as $dadoExclusao) {
            $this->unsetEmpty($dadoExclusao);
            if (empty($dadoExclusao->infoExclusao->ideTrabalhador->cpfTrab)) {
                unset($dadoExclusao->infoExclusao->ideTrabalhador);
            }
            if (empty($dadoExclusao->infoExclusao->ideFolhaPagto->indApuracao) &&
                empty($dadoExclusao->infoExclusao->ideFolhaPagto->perApur)) {
                unset($dadoExclusao->infoExclusao->ideFolhaPagto);
            } else {
                if ($dadoExclusao->infoExclusao->ideFolhaPagto->indApuracao == 2) {
                    $anoMes = explode("-", $dadoExclusao->infoExclusao->ideFolhaPagto->perApur);
                    $dadoExclusao->infoExclusao->ideFolhaPagto->perApur = $anoMes[0];
                }
            }
            /**
             * Adicionada validacao abaixo pois acima podem ocorrer impactos
             * codigo implementado com base em debug em tela, identificando divergencia com validacoes acima
             */
            if (isset($dadoExclusao->infoExclusao['ideFolhaPagto'])
                && !empty($dadoExclusao->infoExclusao['ideFolhaPagto'])
            ) {
                if (isset($dadoExclusao->infoExclusao['ideFolhaPagto']['indApuracao'])
                    && !empty($dadoExclusao->infoExclusao['ideFolhaPagto']['indApuracao'])
                ) {
                    // Validamos se o evento a ser excluido deve possuir essa informacao
                    if (isset($dadoExclusao->infoExclusao['tpEvento'])
                        && !empty($dadoExclusao->infoExclusao['tpEvento'])
                    ) {
                        $layout  = $dadoExclusao->infoExclusao['tpEvento'];
                        $eventos = ['S-1200', 'S-1202', 'S-1207', 'S-1218', 'S-1300'];
                        if (!in_array($layout, $eventos)) {
                            unset($dadoExclusao->infoExclusao['ideFolhaPagto']['indApuracao']);
                        }
                    }
                }
            }
        }
        return $dados;
    }
}
