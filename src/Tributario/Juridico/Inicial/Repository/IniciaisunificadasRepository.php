<?php

namespace ECidade\Tributario\Juridico\Inicial\Repository;

use ECidade\Tributario\Juridico\Inicial\Model\Iniciaisunificadas;

class IniciaisunificadasRepository
{
    public function persist(Iniciaisunificadas $entity)
    {
        $cl_iniciaisunificadas = new \cl_iniciaisunificadas();

        $cl_iniciaisunificadas->v45_sequencial = $entity->getSequencial();
        $cl_iniciaisunificadas->v45_inicialprimaria = $entity->getInicialprimaria();
        $cl_iniciaisunificadas->v45_inicialsecundaria = $entity->getInicialsecundaria();
        $cl_iniciaisunificadas->v45_certidao = $entity->getCertidao();
        $cl_iniciaisunificadas->v45_dataunificacao = $entity->getDataunificacao();
        $cl_iniciaisunificadas->v45_usuario = $entity->getUsuario();

        if (!empty($cl_iniciaisunificadas->v45_sequencial)) {
            $cl_iniciaisunificadas->alterar($cl_iniciaisunificadas->v45_sequencial);
        } else {
            $cl_iniciaisunificadas->incluir(null);
        }

        if ($cl_iniciaisunificadas->erro_status == "0") {
            throw new \Exception($cl_iniciaisunificadas->erro_msg);
        }
    }
}
