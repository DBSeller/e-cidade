<?php

namespace ECidade\Tributario\Cadastro\Iptu\Arquivo\Converter;

use ECidade\Tributario\Cadastro\Iptu\Arquivo\Converter\Converter;
use ECidade\Tributario\Cadastro\Iptu\Arquivo\Entity\Imovel;
use ECidade\Tributario\Library\Entity;

final class ImovelConverter extends Converter
{
    public function get(Entity $imovel)
    {
        $l = '';

        $size = $this->layout->getSize(Imovel::TIPO_IMOVEL_CODIGO);
        $l   .= str_pad(substr($imovel->getTipoImovelCodigo(),     0, $size), $size);

        $size = $this->layout->getSize(Imovel::TIPO_IMOVEL_DESCRICAO);
        $l   .= str_pad(substr($imovel->getTipoImovelDescricao(),  0, $size), $size);

        $size = $this->layout->getSize(Imovel::MATRICULA);
        $l   .= str_pad(substr($imovel->getMatricula(),            0, $size), $size);

        $size = $this->layout->getSize(Imovel::EXERCICIO);
        $l   .= str_pad(substr($imovel->getExercicio(),            0, $size), $size);

        $size = $this->layout->getSize(Imovel::NOTIFICACAO);
        $l   .= str_pad(substr($imovel->getNotificacao(),          0, $size), $size);

        $size = $this->layout->getSize(Imovel::ZONA_ENTREGA);
        $l   .= str_pad(substr($imovel->getZonaEntrega(),          0, $size), $size);

        $size = $this->layout->getSize(Imovel::ZONA_FISCAL_LOTE);
        $l   .= str_pad(substr($imovel->getZonaFiscalLote(),       0, $size), $size);

        $size = $this->layout->getSize(Imovel::SETOR_FISCAL);
        $l   .= str_pad(substr($imovel->getSetorFiscal(),          0, $size), $size);

        $size = $this->layout->getSize(Imovel::SETOR_CARTOGRAFICA);
        $l   .= str_pad(substr($imovel->getSetorCartografica(),    0, $size), $size, '0', STR_PAD_LEFT);

        $size = $this->layout->getSize(Imovel::QUADRACARTOGRAFICA);
        $l   .= str_pad(substr($imovel->getQuadraCartografica(),   0, $size), $size, '0', STR_PAD_LEFT);

        $size = $this->layout->getSize(Imovel::LOTE_CARTOGRAFICA);
        $l   .= str_pad(substr($imovel->getLoteCartografica(),     0, $size), $size, '0', STR_PAD_LEFT);

        $size = $this->layout->getSize(Imovel::SUBLOTE);
        $l   .= str_pad(substr($imovel->getSublote(),              0, $size), $size);

        return $l;
    }
}
