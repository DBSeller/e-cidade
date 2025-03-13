<?php

namespace ECidade\RecursosHumanos\ESocial\Integracao\Formatter;

class TrabalhoIntermitenteFormatter extends Formatter
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
     * @param array $dadosFormatado
     * @return array
     */
    private function posProcessamento($dadosFormatado)
    {
        foreach ($dadosFormatado as $dado) {
            if (empty($dado->infoConvInterm->jornada->codHorContrat) && !empty($dado->infoConvInterm->jornada->dscJornada)) {
                unset($dado->infoConvInterm->jornada->codHorContrat);
            }
            if (empty($dado->infoConvInterm->jornada->dscJornada) && !empty($dado->infoConvInterm->jornada->codHorContrat)) {
                unset($dado->infoConvInterm->jornada->dscJornada);
            }
            if ($dado->infoConvInterm->localTrab->indLocal != 1) {
                unset($dado->infoConvInterm->localTrab->localTrabInterm);
            }
        }

        return $dadosFormatado;
    }
}
