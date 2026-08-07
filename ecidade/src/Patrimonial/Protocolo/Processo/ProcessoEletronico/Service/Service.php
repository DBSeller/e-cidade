<?php

namespace ECidade\Patrimonial\Protocolo\Processo\ProcessoEletronico\Service;

use ECidade\Tributario\Library\Service as BaseService;
use ECidade\Patrimonial\Protocolo\Processo\ProcessoEletronico\Repository\ConsultaProcesso as RepositoryConsultaProcesso;
use ECidade\Patrimonial\Protocolo\Processo\ProcessoEletronico\Filter\ListagemProcessos as FiltroListagemProcessos;

final class Service extends BaseService
{
    private $consultaProcessosRepository;

    public function __construct(RepositoryConsultaProcesso $consultaProcessosRepository)
    {
        $this->consultaProcessosRepository = $consultaProcessosRepository;
    }

    public function listarProcessos(FiltroListagemProcessos $filtro)
    {
        return $this->consultaProcessosRepository->listarProcessos($filtro);
    }
}
