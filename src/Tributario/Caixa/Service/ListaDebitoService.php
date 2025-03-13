<?php
namespace ECidade\Tributario\Caixa\Service;

use ECidade\Tributario\Library\Service;
use ECidade\Tributario\Caixa\Entity\Repository\ListaRepository;
use ECidade\Tributario\Caixa\Entity\Repository\ListaDebitoRepository;
use ECidade\Tributario\Caixa\Entity\Lista;
use ECidade\Tributario\Caixa\Entity\Collection\DebitoCollection;

final class ListaDebitoService extends Service
{
    private $listaRepository;

    private $listaDebitoRepository;

    public function __construct(ListaRepository $listaRepository, ListaDebitoRepository $listaDebitoRepository)
    {
        $this->listaRepository = $listaRepository;
        $this->listaDebitoRepository = $listaDebitoRepository;
    }

    public function find($codigo)
    {
        $lista = $this->listaRepository->find($codigo);

        $debitos = $this->listaDebitoRepository->find($lista);

        $lista->setDebitos($debitos);

        return $lista;
    }

    public function create()
    {
        $lista = new Lista();

        $debitos = new DebitoCollection();

        $lista->setDebitos($debitos);

        return $lista;
    }
}