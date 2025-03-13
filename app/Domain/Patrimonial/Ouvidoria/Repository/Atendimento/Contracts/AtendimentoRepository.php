<?php
namespace App\Domain\Patrimonial\Ouvidoria\Repository\Atendimento\Contracts;

use ECidade\Patrimonial\Protocolo\Processo\ProcessoEletronico\Filter\ListagemProcessos as FiltroListagemProcessos;

/**
* Interface da classe AtendimentoRepository
*
* @var string
*/
interface AtendimentoRepository
{
    /**
     * Funчуo que busca os processos da ouvidoria que nуo tem um atendimento vinculado
     *
     * @param FiltroListagemProcessos $filtroProcesso
     * @return EloquentCollection|Paginator
     */
    public function buscarProcessosOuvidoria(FiltroListagemProcessos $filtroProcesso);

    /**
     * Funчуo que busca os dados de uma solicitaчуo da ouvidoria
     *
     * @param FiltroListagemProcessos $filtroProcesso
     * @return EloquentCollection|Paginator
     */
    public function buscarSolicitacaoOuvidoria(FiltroListagemProcessos $filtroProcesso);
}
