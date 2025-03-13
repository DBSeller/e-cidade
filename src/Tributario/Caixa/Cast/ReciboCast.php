<?php

namespace ECidade\Tributario\Caixa\Cast;

use ECidade\Tributario\Caixa\Entity\Recibo;
use ECidade\Tributario\Caixa\Service\RegraEmissaoService;
use \Recibo as ReciboLegacy;

/**
 * Class ReciboCast
 * @package ECidade\Tributario\Caixa\Cast
 */
final class ReciboCast
{
    private $regraEmissaoService;

    public function __construct(RegraEmissaoService $regraEmissaoService)
    {
        $this->regraEmissaoService = $regraEmissaoService;
    }

    /**
     * @param Recibo $recibo
     * @return ReciboLegacy
     * @throws \Exception
     */
    public function toModel(Recibo $recibo)
    {
        $regraEmissao = $this->regraEmissaoService->execute($recibo);

        /**
         * @todo
         * buscar dados do identificador (CGM, Inscricao, matrícula)
         */

        $model = new ReciboLegacy(ReciboLegacy::TIPOEMISSAO_RECIBO_CGF);
        $model->setCadTipoMod(ReciboLegacy::TIPOEMISSAO_RECIBO_CGF);
        $model->setNumnov($recibo->getNumpre());

        foreach ($recibo->getDebitos() as $debito) {
            foreach ($debito->getParcelas() as $parcela) {
                // TODO buscar valor de desconto
                $model->addNumpre($debito->getNumpre(), $parcela->getNumero());
                $model->setDescontoReciboWeb($debito->getNumpre(), $parcela->getNumero(), 0);
            }
        }

        $model->setDataVencimentoRecibo($recibo->getVencimento()->format('Y-m-d'));
        $model->setHistorico(implode("\n", $recibo->getItensHistorico()));
        $model->setIdentificacao($recibo->getIdentificacao());
        $model->setProcessosForo($recibo->getProcessosForo());

        return $model;
    }
}
