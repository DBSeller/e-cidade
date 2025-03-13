<?php


namespace ECidade\RecursosHumanos\ESocial\Integracao\Formatter;

/**
 * Class AvisoPrevioFormatter
 * @package ECidade\RecursosHumanos\ESocial\Integracao\Formatter
 */
class AvisoPrevioFormatter extends Formatter
{
    /**
     * Realiza a formatação dos dados para envio da API
     *
     * @param  array $dados
     * @return \stdClass[]
     */
    public function formatar($dados)
    {
        $dados = parent::formatar($dados);
        return $this->posProcessamento($dados);
    }

    public function posProcessamento($dados)
    {
        foreach ($dados as $dado) {
            if (!empty($dado->infoAvPrevio->cancAvPrevio->dtCancAvPrv)) {
                unset($dado->infoAvPrevio->detAvPrevio);
            } else {
                unset($dado->infoAvPrevio->cancAvPrevio);
            }
        }
        return $dados;
    }
}
