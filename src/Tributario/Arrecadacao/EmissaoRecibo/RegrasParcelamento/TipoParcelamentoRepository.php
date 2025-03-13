<?php

namespace ECidade\Tributario\Arrecadacao\EmissaoRecibo\RegrasParcelamento;

use Exception;
use stdClass;

/**
 * Class TipoParcelamentoRepository
 * @package ECidade\Tributario\Arrecadacao\EmissaoRecibo\RegrasParcelamento
 */
class TipoParcelamentoRepository
{
    /**
     * @param $data
     * @param $tipo
     * @param $vencimento
     * @param $filtro
     * @return TipoParcelamento
     * @throws Exception
     */
    public function getByDateIntervalAndType($data, $tipo, $vencimento, $filtro)
    {
        $query = "
            SELECT tipoparc.*
            FROM tipoparc
              INNER JOIN cadtipoparc ON cadtipoparc = k40_codigo
              INNER JOIN cadtipoparcdeb ON k41_cadtipoparc = cadtipoparc
            WHERE maxparc = 1
                  AND '{$data}' BETWEEN dtini AND dtfim
                  AND k41_arretipo = {$tipo}
                  AND '{$vencimento}' BETWEEN k41_vencini AND k41_vencfim
                  {$filtro}
            LIMIT 1
        ";

        $result = db_query($query);

        if (!$result) {
            throw new Exception('Não foi possível buscar o tipo de parcelamento.');
        }

        $row = pg_fetch_object($result, 0) ?: new stdClass();

        return TipoParcelamentoMapper::mapRow($row);
    }
}
