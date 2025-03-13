<?php

namespace ECidade\Financeiro\Contabilidade\Exportacao\PADRS\Campos\TipoInstrumentoContratual;

use Exception;

class TipoInstrumentoContratual2018Estrategia extends TipoInstrumentoContratualEstrategia
{
    public function getValor()
    {
        if ($this->lancamento) {
            $sql = "
                SELECT plugins.padrsinstrumentocontratual.*
                FROM plugins.contratospadrs
                    INNER JOIN plugins.padrsinstrumentocontratual ON plugins.padrsinstrumentocontratual.sequencial = plugins.contratospadrs.instrumentocontratual
                WHERE lancamento = {$this->lancamento}
                LIMIT 1
            ";

            if (!$resultado = db_query($sql)) {
                throw new Exception("Não foi possível buscar o tipo de instrumento contratual do lançamento {$this->lancamento}. Por favor entre em contato com o administrador do sistema.");
            }

            if (pg_num_rows($resultado)) {
                return pg_fetch_result($resultado, 0, 'sigla');
            }
        }

        return 'X';
    }
}
