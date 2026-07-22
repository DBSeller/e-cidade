<?php

namespace ECidade\Tributario\Arrecadacao\Arquivo\Autoatendimento\RCB800\Hydrator;

use \StdClass;
use \DateTime;
use ECidade\Tributario\Library\Service;
use ECidade\Tributario\Caixa\Service\ListaDebitoService;
use ECidade\Tributario\Arrecadacao\Arquivo\Autoatendimento\RCB800\Entity\Filtro;

final class FiltroHydrator extends Service
{
    private $listaDebitosRepository;

    public function __construct(ListaDebitoService $listaDebitoService)
    {
        $this->listaDebitoService = $listaDebitoService;
    }

    public function hydrate(StdClass $parametros)
    {
        $lista = $this->listaDebitoService->find($parametros->codigoLista);

        $producao = !empty($parametros->producao) ? $parametros->producao : null;

        $filtro = new Filtro;
        $filtro->setLista($lista);
        $filtro->setDataVigenciaInicial(new DateTime($parametros->datainicial));
        $filtro->setDataVigenciaFinal(new DateTime($parametros->datafinal));
        $filtro->setProducao($producao);
        $filtro->setCodigoConvenio($parametros->codigoConvenio);

        return $filtro;
    }
}
