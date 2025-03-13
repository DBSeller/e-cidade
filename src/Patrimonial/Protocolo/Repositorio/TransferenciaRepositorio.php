<?php
/**
 * Created by PhpStorm.
 * User: dbseller
 * Date: 23/11/18
 * Time: 10:36
 */

namespace ECidade\Patrimonial\Protocolo\Repositorio;

use ECidade\Patrimonial\Protocolo\Modelo\Processo;
use ECidade\Patrimonial\Protocolo\Modelo\Transferencia;

class TransferenciaRepositorio
{
    public static function buscaPorCodigo($codigoTransferencia)
    {
        $dao = new \cl_proctransfer();
        $sql = $dao->sql_query_file($codigoTransferencia);
        $rs = db_query($sql);

        if (!$rs) {
            throw new \Exception(
                "Ocorreu um erro ao tentar buscar a transferencia de código {$codigoTransferencia}.\nContate o suporte."
            );
        }

        if (pg_num_rows($rs) == 0) {
            return null;
        }

        $data = pg_fetch_array($rs, 0);
        return Transferencia::fromState($data);
    }

    /**
     * @param $codigoTransferencia
     * @return \processoProtocolo[]
     * @throws \Exception
     */
    public static function buscaProcessosPorTransferencia($codigoTransferencia)
    {
        $dao = new \cl_proctransferproc();
        $sql = $dao->sql_query_processos_por_transferencia($codigoTransferencia, 'p63_codproc as codigo');
        $rs = db_query($sql);

        if (!$rs) {
            throw new \Exception(
                "Ocorreu um erro ao tentar buscar a transferencia de código {$codigoTransferencia}.\nContate o suporte."
            );
        }

        $processos = array();

        if (pg_num_rows($rs) > 0) {
            while ($processo = pg_fetch_object($rs)) {
                $processos[] = new \processoProtocolo($processo->codigo);
            }
        }

        return $processos;
    }
}
