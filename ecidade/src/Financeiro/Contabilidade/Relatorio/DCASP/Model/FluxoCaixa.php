<?php


namespace ECidade\Financeiro\Contabilidade\Relatorio\DCASP\Model;

/**
 * Interface FluxoCaixa
 * @package ECidade\Financeiro\Contabilidade\Relatorio\DCASP\Model
 */
interface FluxoCaixa
{
    public function getDados();
    public function getInstituicoes($lObjeto = false);
    public function getNomePeriodo();
    public function getAno();
    public function isExibirExercicioAnterior();
    public function getNota($oPdf);
    public function getAssinatura($oPdf);
}
