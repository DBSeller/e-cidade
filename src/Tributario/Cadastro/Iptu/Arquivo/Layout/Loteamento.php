<?php

namespace ECidade\Tributario\Cadastro\Iptu\Arquivo\Layout;

final class Loteamento extends Layout
{
    private $sequencial;
    
    private $posicao;

    public function get($sequencial, $posicao)
    {
        $sequencial++;
        $posicao++;
        $tamanho = 40;

        $layout = $this->padSequencial($sequencial).$this->pipe();
        $layout += $this->padNome("loteamento").$this->pipe();
        $layout += $this->padDescricao("descricao do loteamento").$this->pipe();
        $layout += $this->padTamanho($tamanho).$this->pipe();
        $layout += $this->padPosicaoInicio($posicao).$this->pipe();
        $layout += $this->padPosicaoFim(($posicao + $tamanho));

        $this->sequencial = $sequencial;
        $this->posicao = $posicao;

        return $layout;
    }

    public function getSequencial()
    {
        return $this->sequencial;
    }

    public function getPosicao()
    {
        return $this->posicao;
    }

    private function padSequencial($sequencial)
    {
        return $this->pad($sequencial, 6);
    }

    private function padNome($nome)
    {
        return $this->pad(strtoupper($nome), 31);
    }

    private function padDescricao($descricao)
    {
        return $this->pad(strtoupper($descricao), 81);
    }

    private function padTamanho($tamanho)
    {
        return $this->pad($tamanho, 4, '0', STR_PAD_LEFT);
    }

    private function padPosicaoInicio($posicaoInicio)
    {
        return $this->pad($posicaoInicio, 4, '0', STR_PAD_LEFT);
    }

    private function padPosicaoFim($posicaoFim)
    {
        return $this->pad($posicaoFim, 4, '0', STR_PAD_LEFT);
    }

    private function pipe()
    {
        return " | ";
    }

    private function pad($text, $size, $value = " ", $type = STR_PAD_BOTH)
    {
        return str_pad($text, $size, $value, $type);
    }
}
