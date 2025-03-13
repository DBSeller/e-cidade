<?php
namespace ECidade\Financeiro\Contabilidade\LancamentoContabil\Validacao;

/**
 * Interface PosProcessamento
 * @package ECidade\Financeiro\Contabilidade\LancamentoContabil\Validacao
 */
interface InterfacePosProcessamento
{
    /**
     * @param integer $codigoLancamento
     * @return bool
     */
    public function processar($codigoLancamento);
}
