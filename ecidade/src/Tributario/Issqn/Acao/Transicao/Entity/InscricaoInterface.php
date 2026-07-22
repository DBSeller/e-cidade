<?php

namespace ECidade\Tributario\Issqn\Acao\Transicao\Entity;

interface InscricaoInterface
{
    public function verificaCamposAdicionais($sSecao, $sCampo, $sDefault = null);
}
