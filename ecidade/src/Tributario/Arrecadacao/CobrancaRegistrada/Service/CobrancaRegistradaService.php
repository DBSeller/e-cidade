<?php

namespace ECidade\Tributario\Arrecadacao\CobrancaRegistrada\Service;

use ECidade\Tributario\Arrecadacao\CobrancaRegistrada\CobrancaRegistrada;
use ECidade\Tributario\Caixa\Entity\Recibo;
use ECidade\Tributario\Caixa\Entity\RegraEmissao;
use ECidade\Tributario\Caixa\Entity\Strategy\ReciboValorTotal;
use ECidade\Tributario\Library\Service;
use \Recibo as ReciboLegacy;

/**
 * Class CobrancaRegistradaService
 * @package ECidade\Tributario\Arrecadacao\CobrancaRegistrada\Service
 */
final class CobrancaRegistradaService extends Service
{
    private $reciboValorTotalStrategy;

    /**
     * CobrancaRegistradaService constructor.
     * @param ReciboValorTotal $reciboValorTotal
     */
    public function __construct(ReciboValorTotal $reciboValorTotal)
    {
        $this->reciboValorTotalStrategy = $reciboValorTotal;
    }

    /**
     * @param Recibo $recibo
     * @param RegraEmissao $regraEmissao
     * @throws \BusinessException
     * @throws \DBException
     */
    public function execute(Recibo $recibo, RegraEmissao $regraEmissao)
    {
        $lConvenioCobrancaValido = CobrancaRegistrada::validaConvenioCobranca($regraEmissao->getConvenio());

        $reciboLegacy = new ReciboLegacy(2, $recibo->getOrigem(), $recibo->getTipo(), $recibo->getNumpre());

        if ($lConvenioCobrancaValido) {
            if (CobrancaRegistrada::utilizaIntegracaoWebService($regraEmissao->getConvenio())) {
                CobrancaRegistrada::registrarReciboWebservice(
                    $recibo->getNumpre(),
                    $regraEmissao->getConvenio(),
                    $this->reciboValorTotalStrategy->calculate($recibo)
                );
            } else {
                CobrancaRegistrada::adicionarRecibo($reciboLegacy, $regraEmissao->getConvenio());
            }
        }
    }
}
