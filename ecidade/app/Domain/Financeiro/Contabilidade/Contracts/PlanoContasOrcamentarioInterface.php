<?php

namespace App\Domain\Financeiro\Contabilidade\Contracts;

interface PlanoContasOrcamentarioInterface
{
    /**
     * Retorna o index da coluna que possui a conta
     * @return integer
     */
    public function indexColunaConta();
    /**
     * Retorna o index da linha que deve começar a importar os dados
     * @return integer
     */
    public function linhaInicio();

    /**
     * Retorna os index das colunas que devem ser importadas
     * @return array
     */
    public function colunasImportar();

    /**
     * Mapa das colunas com as colunas do banco de dados
     * @return array
     */
    public function colunasMapper();
}
