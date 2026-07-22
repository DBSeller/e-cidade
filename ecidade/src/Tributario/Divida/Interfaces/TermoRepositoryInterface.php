<?php

namespace ECidade\Tributario\Divida\Interfaces;

use ECidade\Tributario\Divida\Termo\Termo;

interface TermoRepositoryInterface
{
    /**
     * @param  array $numpres
     * @param  Termo $parcelamento
     * @return mixed
     */
    public function atualizarObservacaoOrigemPorNumpreAoAnular(array $numpres, Termo $parcelamento);
}
