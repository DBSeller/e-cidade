<?php

namespace ECidade\Tributario\Cadastro\Iptu\Arquivo\Converter;

use ECidade\Tributario\Library\Entity;
use ECidade\Tributario\Cadastro\Iptu\Arquivo\Entity\Banco;

final class BancoConverter extends Converter
{
    /**
     * @param Banco $banco
     * @return string
     *
     * Retorna os dados no layout para montagem do TXT
     */
    public function get(Entity $banco)
    {
        $l = '';

        $size = $this->layout->getSize(Banco::TOTAL_BOM_PAGADOR);
        $l   .= str_pad(substr($banco->getTotalBomPagador(), 0, $size), $size);

        $size = $this->layout->getSize(Banco::AGENCIA);
        $l   .= str_pad(substr($banco->getAgencia(), 0, $size), $size);

        $size = $this->layout->getSize(Banco::DIGITO_AGENCIA);
        $l   .= str_pad(substr($banco->getDigitoAgencia(), 0, $size), $size);

        $size = $this->layout->getSize(Banco::OPERACAO);
        $l   .= str_pad(substr($banco->getOperacao(), 0, $size), $size);

        $size = $this->layout->getSize(Banco::CEDENTE);
        $l   .= str_pad(substr($banco->getCedente(), 0, $size), $size);

        $size = $this->layout->getSize(Banco::DIGITO_CEDENTE);
        $l   .= str_pad(substr($banco->getDigitoCedente(), 0, $size), $size);

        $size = $this->layout->getSize(Banco::CARTEIRA);
        $l   .= str_pad(substr($banco->getCarteira(), 0, $size), $size);

        $size = $this->layout->getSize(Banco::CONVENIO);
        $l   .= str_pad(substr($banco->getConvenio(), 0, $size), $size);

        $size = $this->layout->getSize(Banco::DATA_PROCESSAMENTO);
        $l   .= str_pad(substr($this->format->date($banco->getDataProcessamento()->format('Y-m-d')), 0, $size), $size);

        $size = $this->layout->getSize(Banco::DESCRICAO_CONVENIO);
        $l   .= str_pad(substr($banco->getDescricaoConvenio(), 0, $size), $size);

        return $l;
    }
}
