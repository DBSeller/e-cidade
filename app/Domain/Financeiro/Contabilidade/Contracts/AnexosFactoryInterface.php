<?php

namespace App\Domain\Financeiro\Contabilidade\Contracts;

interface AnexosFactoryInterface
{
    /**
     * O retorno deve seguir o exemplo:
     * ['relatorio' => $relatorio, 'programa' => $programa, 'rota' => $rota];
     * onde:
     * $relatorio é o código do relatório
     * $programa é a view para selecionar os filtros
     * $rota é a rota de impressãoa
     * @param $exercicio
     * @return array
     */
    public static function getDadosView($exercicio);

    /**
     * @param $exercicio
     * @return integer
     */
    public static function getCodigoRelatorio($exercicio);
}
