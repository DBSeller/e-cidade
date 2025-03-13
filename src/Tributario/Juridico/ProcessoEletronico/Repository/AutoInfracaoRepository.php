<?php

namespace ECidade\Tributario\Juridico\ProcessoEletronico\Repository;

use ECidade\Tributario\Juridico\ProcessoEletronico\Domain\AutoInfracao;

/**
 * Class AutoInfracaoRepository
 * @package ECidade\Tributario\Juridico\ProcessoEletronico\Repository
 */
class AutoInfracaoRepository
{

    /**
     * @param $codigo
     * @return AutoInfracao
     * @throws \BusinessException
     */
    public function getAuto($codigo)
    {
        $sql = "select y50_codauto, y50_data from auto  where y50_codauto = {$codigo}";

        $rsAuto = db_query($sql);

        if (!$rsAuto) {
            throw new \BusinessException("Não foi possível buscar os dados do auto de infracao");
        }

        $oDados = \db_utils::fieldsMemory($rsAuto, 0);

        $oAutoInfracao =  new AutoInfracao();
        $oAutoInfracao->setCodigoAuto($oDados->y50_codauto);
        $oAutoInfracao->setDataAuto($oDados->y50_data);

        return  $oAutoInfracao;
    }
}