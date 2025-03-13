<?php

namespace ECidade\Financeiro\Contabilidade\Exportacao\PADRS\Campos\BaseLegalContratacao;

use Exception;

/**
 * Class BaseLegalContratacaoEstrategia2018
 * @package ECidade\Financeiro\Contabilidade\Exportacao\PADRS\Campos\BaseLegalContratacao
 */
class BaseLegalContratacao2018Estrategia extends BaseLegalContratacaoEstrategia
{
    /**
     * @return string
     * @throws Exception
     */
    public function getValor()
    {
        if ($this->empenho) {
            $sql = "
                SELECT sigla
                FROM plugins.padrsacordovinculo
                JOIN plugins.padrsbaselegalcontratacaoacordo
                        ON padrsbaselegalcontratacaoacordo.sequencial = padrsacordovinculo.baselegalcontratacao
                WHERE acordo = (
                    SELECT coalesce(e100_acordo, ac54_acordo)
                      FROM empempenho
                      LEFT JOIN empempenhocontrato ON empempenhocontrato.e100_numemp = empempenho.e60_numemp
                      LEFT JOIN acordoempempenho ON acordoempempenho.ac54_empempenho = empempenho.e60_numemp
                      WHERE e60_numemp = {$this->empenho}
                      limit 1
                    )
                LIMIT 1

            ";

            if (!$resultado = db_query($sql)) {
                throw new Exception(
                    "Não foi possível buscar a base legal de contratação do empenho {$this->empenho}.
                    Por favor entre em contato com o administrador do sistema."
                );
            }

            if (pg_num_rows($resultado)) {
                return pg_fetch_result($resultado, 0, 'sigla');
            }
        }

        return '00';
    }
}
