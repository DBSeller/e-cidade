<?php


namespace ECidade\RecursosHumanos\RH\Assentamento\Repository;

use ECidade\RecursosHumanos\RH\Assentamento\Model\HoraExtraManual as Model;

class HoraExtraManual
{
    public static function incluir(Model $horaExtraManual)
    {
        $dao = new \cl_assentamentohoraextra();
        $dao->h17_assenta = $horaExtraManual->getCodigoAssentamento();
        $dao->h17_hora = $horaExtraManual->getHora();
        $dao->h17_tipo = $horaExtraManual->getTipo();

        if (!$dao->incluir()) {
            throw new \Exception($dao->erro_msg);
        }
        $horaExtraManual->setSequencial($dao->h17_sequencial);
    }

    public static function delete($codigoAssentamento)
    {
        $dao = new \cl_assentamentohoraextra();
        $dao->excluir(null, "h17_assenta = {$codigoAssentamento}");
        if ($dao->erro_status === '0') {
            throw new \Exception("Não foi possível excluir.");
        }
    }
}
