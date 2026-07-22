<?php

namespace ECidade\Tributario\Cadastro\Iptu\Arquivo\Converter;

use ECidade\Tributario\Cadastro\Iptu\Arquivo\Converter\Converter;
use ECidade\Tributario\Cadastro\Iptu\Arquivo\Entity\Localizacao;
use ECidade\Tributario\Library\Entity;

final class LocalizacaoConverter extends Converter
{
    public function get(Entity $localizacao)
    {
        $l = '';

        $size = $this->layout->getSize(Localizacao::SEQUENCIAL_SETOR_LOCALIZACAO);
        $l   .= str_pad(substr($localizacao->getSequencialSetorLocalizacao(),          0, $size), $size);

        $size = $this->layout->getSize(Localizacao::CODIGO_PROPRIO_SETOR_LOCALIZACAO);
        $l   .= str_pad(substr($localizacao->getCodigoProprioSetorLocalizacao(),       0, $size), $size);

        $size = $this->layout->getSize(Localizacao::DESCRICAO_SETOR_LOCALIZACAO);
        $l   .= str_pad(substr($localizacao->getDescricaoSetorLocalizacao(),           0, $size), $size);

        $size = $this->layout->getSize(Localizacao::QUADRA_LOCALIZACAO);
        $l   .= str_pad(substr($localizacao->getQuadraLocalizacao(),                   0, $size), $size);

        $size = $this->layout->getSize(Localizacao::LOTE_LOCALIZACAO);
        $l   .= str_pad(substr($localizacao->getLoteLocalizacao(),                     0, $size), $size);

        return $l;
    }
}
