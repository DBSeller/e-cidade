<?php

namespace ECidade\Tributario\Caixa\Service;

use ECidade\Tributario\Library\Service;
use ECidade\Tributario\Library\Format;
use ECidade\Tributario\Caixa\Entity\Recibo;
use ECidade\Tributario\Caixa\Entity\RegraEmissao;
use ECidade\Tributario\Caixa\Entity\Strategy\ReciboValorTotal;
use \convenio as ConvenioLegacy;

final class ConvenioService extends Service
{
    private $format;

    private $reciboValorTotal;

    public function __construct(Format $format, ReciboValorTotal $reciboValorTotal)
    {
        $this->format = $format;
        $this->reciboValorTotal = $reciboValorTotal;
    }

    public function execute(Recibo $recibo, RegraEmissao $regraEmissao)
    {
        $valorTotal = $this->reciboValorTotal->calculate($recibo);

        $valorTotalCodigoBarras = $this->format->decimal(str_replace('.', '', str_pad(number_format($valorTotal, 2, "", "."), 11, "0", STR_PAD_LEFT)), 's', '0', 11, 'e');

        $convenioLegacy = new ConvenioLegacy(
            $regraEmissao->getConvenio(),
            $recibo->getNumpre(),
            0,
            $valorTotal,
            $valorTotalCodigoBarras,
            $recibo->getVencimento()->format('Y-m-d'),
            $recibo->getTerceiroDigito()
        );

        $recibo->setNossoNumero($convenioLegacy->getNossoNumero());
        $recibo->setCodigoBarras($convenioLegacy->getCodigoBarra());
        $recibo->setLinhaDigitavel($convenioLegacy->getLinhaDigitavel());

        return $recibo;
    }
}
