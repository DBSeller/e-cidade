<?php

namespace ECidade\Financeiro\Contabilidade\ContaCorrente\Consulta\Formatter;

use ECidade\Financeiro\Contabilidade\ContaCorrente\Model\Visao;

interface ConsultaInterface
{


    public function setDados($dados);

    public function setColunas(array $colunas);

    public function setVisao(Visao $visao);

    public function setAgruparPorDocumento($agruparPorDocumento);
    public function formatar();
}
