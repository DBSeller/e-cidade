<?php

namespace ECidade\Tributario\Configuracao\Entity\Repository;

use ECidade\Tributario\Configuracao\Entity\Instituicao;
use ECidade\Tributario\Library\DataBaseRepository;
use Instituicao as InstituicaoLegacy;

class InstituicaoRepository extends DataBaseRepository
{
    public function find($codigo)
    {
        $instituicaoLegacy = new InstituicaoLegacy($codigo);

        $instituicao = new Instituicao();

        $instituicao->setNome( $instituicaoLegacy->getDescricao() );
        $instituicao->setCodigo( $instituicaoLegacy->getSequencial() );
        
        return $instituicao;
    }
}

