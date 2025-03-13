<?php

namespace ECidade\Tributario\Cadastro\Iptu\Arquivo\Converter;

use ECidade\Tributario\Library\Entity;
use ECidade\Tributario\Cadastro\Iptu\Arquivo\Converter\Converter;
use ECidade\Tributario\Cadastro\Iptu\Arquivo\Entity\Exercicio;

final class ExercicioConverter extends Converter
{
    public function get(Entity $exercicio)
    {
        $l = '';

        $size = $this->layout->getSize(Exercicio::BRANCOS_1);
        $l   .= str_repeat(' ', $size);

        $size = $this->layout->getSize(Exercicio::BRANCOS_2);
        $l   .= str_repeat(' ', $size);

        $size = $this->layout->getSize(Exercicio::DESCRICAO_ISENCAO);
        $l   .= str_pad(substr($this->format->decimal($exercicio->getIsencaoDescricao()), 0, $size), $size);

        $size = $this->layout->getSize(Exercicio::LANCAMENTO_ISENCAO);
        if ($exercicio->getIsencaoDataLancamento() instanceof \DateTime) {
            $l .= str_pad(substr($exercicio->getIsencaoDataLancamento()->format('d/m/Y'),   0, $size), $size);
        } else {
            $l .= str_pad(substr(' ', 0, $size), $size);
        }

        $size = $this->layout->getSize(Exercicio::TOTAL_LANCADO);
        $l   .= str_pad(substr($this->format->decimal($exercicio->getTotalIptuTaxa()),    0, $size), $size);

        $size = $this->layout->getSize(Exercicio::QUANTIDADE_LANCADO);
        $l   .= str_pad(substr($exercicio->getQuantidadeIptuTaxa(),                       0, $size), $size);

        $size = $this->layout->getSize(Exercicio::TOTAL_LANCADO_TAXAS);
        $l   .= str_pad(substr($this->format->decimal($exercicio->getTotalTaxa()),        0, $size), $size);

        $size = $this->layout->getSize(Exercicio::QUANTIDADE_LANCADO_TAXAS);
        $l   .= str_pad(substr($exercicio->getQuantidadeTaxa(),                           0, $size), $size);

        $size = $this->layout->getSize(Exercicio::VALOR_CORRIGIDO_IPTU);
        $l   .= str_pad(substr($this->format->decimal($exercicio->getValorCorrigidoIptu()), 0, $size), $size);

        $size = $this->layout->getSize(Exercicio::VALOR_JUROS_IPTU);
        $l   .= str_pad(substr($this->format->decimal($exercicio->getValorJurosIptu()),     0, $size), $size);

        $size = $this->layout->getSize(Exercicio::VALOR_MULTA_IPTU);
        $l   .= str_pad(substr($this->format->decimal($exercicio->getValorMultaIptu()),     0, $size), $size);

        $size = $this->layout->getSize(Exercicio::VALOR_DESCONTO_IPTU);
        $l   .= str_pad(substr($this->format->decimal($exercicio->getValorDescontoIptu()),  0, $size), $size);

        $size = $this->layout->getSize(Exercicio::VALOR_TOTAL_IPTU);
        $l   .= str_pad(substr($this->format->decimal($exercicio->getValorTotalIptu()),     0, $size), $size);

        $size = $this->layout->getSize(Exercicio::CODIGO_FACE);
        $l   .= str_pad(substr($exercicio->getCodigoFace(),                                     0, $size), $size);

        $size = $this->layout->getSize(Exercicio::VALOR_M2_TERRENO_FACE);
        $l   .= str_pad(substr($this->format->decimal($exercicio->getValorM2TerrenoFace()),     0, $size), $size);

        $size = $this->layout->getSize(Exercicio::VALOR_M2_CONSTRUCAO_FACE);
        $l   .= str_pad(substr($this->format->decimal($exercicio->getValorM2ConstrucaoFace()),  0, $size), $size);

        $size = $this->layout->getSize(Exercicio::VALOR_VENAL_TERRENO);
        $l   .= str_pad(substr($this->format->decimal($exercicio->getValorVenalTerreno()),      0, $size), $size);

        $size = $this->layout->getSize(Exercicio::VALOR_VENAL_EDIFICACAO);
        $l   .= str_pad(substr($this->format->decimal($exercicio->getValorVenalEdificacoes()),  0, $size), $size);

        $size = $this->layout->getSize(Exercicio::VALOR_VENAL_TOTAL);
        $l   .= str_pad(substr($this->format->decimal($exercicio->getValorVenalTotal()),        0, $size), $size);

        $size = $this->layout->getSize(Exercicio::ALIQUOTA);
        $l   .= str_pad(substr($exercicio->getAliquota(),                                       0, $size), $size);

        return $l;
    }

    /**
     * @todo esse método precisa ser depreciado
     */
    public function getLegacy($exercicio)
    {
        return $this->get($exercicio);
    }
}
