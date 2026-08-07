<?php

namespace ECidade\RecursosHumanos\Pessoal\Factory;

// use ECidade\RecursosHumanos\Pessoal\Model\PadArquivoEscritorXML2021;
use Exception;

/**
 * Class PadEscritorXMLFactory
 * @package ECidade\Integracao\Sped\Common\Evento
 */
class PadEscritorXMLFactory
{
    /**
     * @param $tipo
     * @return EventoAbstract
     * @throws Exception
     */
    public static function getInstance($ano)
    {
        switch ($ano) {
            case 2021:
                require_once(modification('model/PadArquivoEscritorXML2021.model.php'));
                return new \PadArquivoEscritorXML2021();
            case 2020:
                // return new padArquivoEscritorXML();
            default:
                throw new Exception("Escritor XML não encontrado.");
        }
    }
}
