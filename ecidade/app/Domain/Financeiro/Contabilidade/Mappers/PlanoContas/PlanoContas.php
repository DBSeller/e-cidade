<?php

namespace App\Domain\Financeiro\Contabilidade\Mappers\PlanoContas;

use App\Domain\Financeiro\Contabilidade\Contracts\PlanoContasOrcamentarioInterface;

abstract class PlanoContas implements PlanoContasOrcamentarioInterface
{
    const PLANO_UNIAO = 'uniao';
    const PLANO_ESTADUAL = 'UF';

    const ORIGEM_RECEITA = 'receita';
    const ORIGEM_DESPESA = 'despesa';

    /**
     * Retorna o index da coluna que possui a conta
     * @return integer
     */
    public function indexColunaConta()
    {
        return $this->colunaConta;
    }

    /**
     * Retorna o index da linha que deve começar a importar os dados
     * @return integer
     */
    public function linhaInicio()
    {
        return $this->linhaDados;
    }

    /**
     * Retorna os index das colunas que devem ser importadas
     * @return array
     */
    public function colunasImportar()
    {
        return $this->colunasImportar;
    }

    /**
     * Mapa das colunas com as colunas do banco de dados
     * @return array
     */
    public function colunasMapper()
    {
        return $this->colunasMapper;
    }
}
