<?php

namespace ECidade\Financeiro\Contabilidade\Exportacao\PADRS\Layouts;

/**
 * Interface LayoutPad
 * @package ECidade\Financeiro\Contabilidade\Exportacao\PADRS\Layouts
 */
interface LayoutPad
{
    /**
     * @return array
     */
    public function toArray();

    /**
     * @param array $dados
     * @return array
     */
    public function parse(array $dados);
}
