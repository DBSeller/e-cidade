<?php

namespace ECidade\Tributario\Arrecadacao\EmissaoRecibo\RegrasParcelamento;

use DBException;
use Instituicao;
use stdClass;

/**
 * Class TipoParcelamentoMapper
 * @package ECidade\Tributario\Arrecadacao\EmissaoRecibo\RegrasParcelamento
 */
class TipoParcelamentoMapper
{
    /**
     * @param $row
     * @return TipoParcelamento
     * @throws DBException
     */
    public static function mapRow(stdClass $row)
    {
        $tipoParcelamento = new TipoParcelamento(new Instituicao(db_getsession('DB_instit')));
        $tipoParcelamento->setTipoparc($row->tipoparc);
        $tipoParcelamento->setDescr($row->descr);
        $tipoParcelamento->setDtini($row->dtini);
        $tipoParcelamento->setDtfim($row->dtfim);
        $tipoParcelamento->setMaxparc($row->maxparc);
        $tipoParcelamento->setVlrmin($row->vlrmin);
        $tipoParcelamento->setDtvlr($row->dtvlr);
        $tipoParcelamento->setInflat($row->inflat);
        $tipoParcelamento->setDescmul($row->descmul);
        $tipoParcelamento->setDescjur($row->descjur);
        $tipoParcelamento->setDescvlr($row->descvlr);
        $tipoParcelamento->setCadtipoparc($row->cadtipoparc);
        $tipoParcelamento->setK42Minentrada($row->k42_minentrada);
        $tipoParcelamento->setTipovlr($row->tipovlr);
        $tipoParcelamento->setMinparc($row->minparc);

        return $tipoParcelamento;
    }
}
