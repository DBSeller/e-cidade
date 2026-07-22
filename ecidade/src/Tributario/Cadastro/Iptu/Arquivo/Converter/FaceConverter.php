<?php

namespace ECidade\Tributario\Cadastro\Iptu\Arquivo\Converter;

use ECidade\Tributario\Library\Entity;
use ECidade\Tributario\Cadastro\Iptu\Arquivo\Converter\Converter;
use ECidade\Tributario\Cadastro\Iptu\Arquivo\Entity\Face;

final class FaceConverter extends Converter
{
    /**
     * @todo implementar o tratamento dos dados
     */
    public function get(Entity $face)
    {
        $l = '';

        $size = $this->layout->getSize(Face::OUTRAS_INFORMACOES);
        $l   .= str_pad(substr($face->getOutrasInformacoes(),                 0, $size), $size, ' ', STR_PAD_LEFT);

        $size = $this->layout->getSize(Face::CODIGO_CGM);
        $l   .= str_pad(substr($face->getCodigoCGM(),                         0, $size), $size);

        $size = $this->layout->getSize(Face::FRACAO_LOTE);
        $l   .= str_pad(substr($face->getFracaoLote(),                        0, $size), $size);

        $size = $this->layout->getSize(Face::CEP_IMOVEL);
        $l   .= str_pad(substr($face->getCEPImovel(),                         0, $size), $size);

        $size = $this->layout->getSize(Face::MUNICIPIO_IMOVEL);
        $l   .= str_pad(substr($face->getMunicipioImovel(),                   0, $size), $size);

        $size = $this->layout->getSize(Face::UF_IMOVEL);
        $l   .= str_pad(substr($face->getUFImovel(),                          0, $size), $size);

        $size = $this->layout->getSize(Face::MENSAGEM_DEBITOS_ANOS_ANTERIORES);
        $l   .= str_pad(substr($face->getMensagemDebitosAnosAnteriores(),     0, $size), $size);

        $size = $this->layout->getSize(Face::NOME_BAIRRO);
        $l   .= str_pad(substr($face->getNomeBairro(),                        0, $size), $size);

        $size = $this->layout->getSize(Face::CODIGO_ISENCAO);
        $l   .= str_pad(substr($face->getCodigoIsencao(),                     0, $size), $size, '0', STR_PAD_LEFT);

        $size = $this->layout->getSize(Face::CODIGO_TIPO_ISENCAO);
        $l   .= str_pad(substr($face->getCodigoTipoIsencao(),                 0, $size), $size, '0', STR_PAD_LEFT);

        return $l;
    }
}
