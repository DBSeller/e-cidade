<?php

namespace ECidade\Financeiro\Contabilidade\Exportacao\PADRS\Campos\ExisteContratoTermo;

use Exception;

class ExisteContratoTermo2018Estrategia extends ExisteContratoTermoEstrategia
{
    public function getValor()
    {
        // Se empenho for gerado através da folha, enviar X
        if ($this->isFolhaPagamento()) {
            return 'X';
        }

        // Se empenho estiver salvo no plugin, buscar valor no mesmo
        $sql = "
            SELECT *
            FROM plugins.contratospadrs
            WHERE lancamento = '{$this->lancamento}'
            LIMIT 1
        ";

        if (!$resultado = db_query($sql)) {
            $this->tratarErroGetValor();
        }

        if (pg_num_rows($resultado)) {
            return pg_fetch_result($resultado, 0, 'cabecalho');
        }

        // Se empenho não for da folha e não estiver no plugin deve retornar N
        return 'N';
    }

    private function isFolhaPagamento()
    {
        $sql = "
            SELECT 1
            FROM conlancam
                INNER JOIN conlancamemp ON conlancamemp.c75_codlan = conlancam.c70_codlan
                INNER JOIN rhempenhofolhaempenho ON rhempenhofolhaempenho.rh76_numemp = conlancamemp.C75_numemp
            WHERE c70_codlan = '{$this->lancamento}'
        ";

        if (!$resultado = db_query($sql)) {
            $this->tratarErroGetValor();
        }

        return pg_num_rows($resultado);
    }

    private function tratarErroGetValor()
    {
        throw new Exception("Não foi possível verificar se o empenho possui contrato/termo. Por favor entre em contato com o administrador do sistema.");
    }
}
