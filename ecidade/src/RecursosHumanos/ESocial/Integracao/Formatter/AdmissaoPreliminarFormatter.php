<?php

namespace ECidade\RecursosHumanos\ESocial\Integracao\Formatter;

use ECidade\RecursosHumanos\ESocial\Integracao\Formatter\Formatter;

/**
 * Formata os dados do Cargo
 *
 * @package ECidade\RecursosHumanos\ESocial\Integracao\Formatter
 * @author Augusto Oliveira <augusto.oliveira@dbseller.com.br>
 */
class AdmissaoPreliminarFormatter extends Formatter
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
            if (!isset($dados->infoRegPrelim->natAtividade) || empty($dados->infoRegPrelim->natAtividade)) {
                $dados->infoRegPrelim->natAtividade = 1;
            }
            $infoRegExiste = false;
            $grupoinfoRegCTPS = $dados->infoRegPrelim->infoRegCTPS[0];

            if (!empty($grupoinfoRegCTPS->CBOCargo)
                || !empty($grupoinfoRegCTPS->vrSalFx)
                || !empty($grupoinfoRegCTPS->undSalFixo)
                || !empty($grupoinfoRegCTPS->tpContr)) {
                $infoRegExiste = true;
            }

            if (!isset($grupoinfoRegCTPS->dtTerm) || empty($grupoinfoRegCTPS->dtTerm)) {
                $dados->infoRegPrelim->infoRegCTPS[0]->dtTerm = null;
            }

            if (!$infoRegExiste) {
                unset($dados->infoRegPrelim->infoRegCTPS);
            }
        }
        return $dadosFormatado;
    }
}
