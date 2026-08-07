<?php

namespace ECidade\Integracao\Sped\Common\Evento;

use ECidade\Integracao\Sped\EFDReinf\Evento\R1000;
use ECidade\Integracao\Sped\EFDReinf\Evento\R1070;
use ECidade\Integracao\Sped\EFDReinf\Evento\R2010;
use ECidade\Integracao\Sped\EFDReinf\Evento\R2020;
use ECidade\Integracao\Sped\EFDReinf\Evento\R2099;
use ECidade\Integracao\Sped\EFDReinf\Evento\R9000;
use ECidade\Integracao\Sped\ESocial\Evento\S1295;
use ECidade\Integracao\Sped\ESocial\Evento\S1299;
use ECidade\RecursosHumanos\ESocial\Model\Formulario\Tipo;
use Exception;

/**
 * Class EventoFactory
 * @package ECidade\Integracao\Sped\Common\Evento
 */
class EventoFactory
{
    /**
     * @param $tipo
     * @return EventoAbstract
     * @throws Exception
     */
    public static function getInstance($tipo)
    {
        switch ($tipo) {
            case Tipo::CONTRIBUINTE:
                return new R1000();
            case Tipo::EFD_PROCESSOS:
                return new R1070();
            case Tipo::TOTALIZACAO_PAGAMENTOS_CONTINGENCIA:
                return new S1295();
            case Tipo::FECHAMENTO_EVENTOS_PERIODICOS:
                return new S1299();
            case Tipo::EFD_RETENCOES_SERVICOS_TOMADOS:
                return new R2010();
            case Tipo::EFD_SERVICOS_PRESTADOS:
                return new R2020();
            case Tipo::EFD_FECHAMENTO_PERIODICOS:
                return new R2099();
            case Tipo::EFD_EXCLUSAO_EVENTOS:
                return new R9000();
            default:
                throw new Exception("Evento não encontrado.");
        }
    }
}
