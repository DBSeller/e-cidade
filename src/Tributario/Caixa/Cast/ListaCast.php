<?php

namespace ECidade\Tributario\Caixa\Cast;

use ECidade\Tributario\Caixa\Model\Lista as ListaModel;
use ECidade\Tributario\Caixa\Entity\Lista;

final class ListaCast
{
    public function toEntity(ListaModel $listaModel)
    {
        $lista = new Lista();

        $lista->setCodigo($listaModel->getCodigo());
        $lista->setDescricao($listaModel->getDescr());
        $lista->setTipo($listaModel->getTipo());
        $lista->setData($listaModel->getDatadeb());
        $lista->setUsuario($listaModel->getUsuario());
        $lista->setInstituicao($listaModel->getInstit());

        return $lista;
    }
}
