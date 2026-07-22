<?php

namespace ECidade\Financeiro\Contabilidade\Exportacao\PADRS\Builders;

use ECidade\Financeiro\Contabilidade\Exportacao\PADRS\Layouts\LayoutPad;

abstract class PadBuilder
{
    /**
     * @var LayoutPad
     */
    protected $layout;

    /**
     * @var array
     */
    protected $dados;

    /**
     * Cria a instancia do layout do pad.
     */
    abstract protected function create();

    /**
     * @return self
     */
    public function addDados(array $dados)
    {
        $this->dados = $dados;
        return $this;
    }

    /**
     * @return LayoutPad
     */
    public function build()
    {
        $this->create();
        $this->processar();

        return $this->layout;
    }

    /**
     * @return LayoutPad
     */
    abstract protected function processar();

    protected function formatar($input, $length, $padString, $type)
    {
        return str_pad($input, $length, $padString, $type);
    }

    /**
     * @param $string
     * @param $length
     * @return string
     */
    protected function formataCaractere($string, $length)
    {
        return $this->formatar($string, $length, ' ', STR_PAD_RIGHT);
    }

    protected function formataNumerico($valor, $length)
    {
        $numero = preg_replace("/[^\d]/s", '', $valor);
        return $this->formatar($numero, $length, '0', STR_PAD_LEFT);
    }

    protected function formatEstrutural($valor, $length)
    {
        return $this->formatar($valor, $length, '0', STR_PAD_RIGHT);
    }

    protected function formatEstruturalReceita($valor, $length)
    {
        return $this->formatar($valor, $length, '0', STR_PAD_LEFT);
    }

    public function formataValor($valor, $length)
    {
        if ($valor < 0) {
            $valor *= -1;
            $numero = number_format($valor, 2, '', '');
            $valor = '-' . $this->formatar($numero, $length - 1, '0', STR_PAD_LEFT);
            return $valor;
        } else {
            $numero = number_format($valor, 2, '', '');
            return $this->formatar($numero, $length, '0', STR_PAD_LEFT);
        }
    }

    public function formataData($data)
    {
        $data = implode(array_reverse(explode('-', $data)));
        return $data;
    }
}
