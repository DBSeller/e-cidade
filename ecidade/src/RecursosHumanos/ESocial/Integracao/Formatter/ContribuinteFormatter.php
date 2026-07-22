<?php

namespace ECidade\RecursosHumanos\ESocial\Integracao\Formatter;

/**
 * Formata os dados da Rubrica
 *
 * @package ECidade\RecursosHumanos\ESocial\Integracao\Formatter
 */
class ContribuinteFormatter extends Formatter
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
            if (empty($dados->idePeriodo->fimvalid)) {
                $dados->idePeriodo->fimvalid = null;
            }
        }

        return $dadosFormatado;
    }
}
