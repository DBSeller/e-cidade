<?php

namespace ECidade\Tributario\Issqn;

use App\Domain\Tributario\ISSQN\Services\Redesim\InclusaoEmpresa\AtendimentoInclusaoInscricaoJsonService;
use ECidade\V3\Extension\ParameterBag;
use ECidade\Tributario\Issqn\Model\ParametroProcessoEletronico;
use ECidade\Tributario\Issqn\Repository\ParametroProcessoEletronicoRepository;
use ECidade\Patrimonial\Protocolo\Processo\ProcessoEletronico\Helper\ProcessoEletronicoHelper;

final class ParametrosProcessoEletronicoBag extends ParameterBag
{
    public function __construct(
        ParametroProcessoEletronicoRepository $repository,
        ParametroProcessoEletronico $entidade
    ) {
        $entidade = new ParametroProcessoEletronico();
        $entidade->fromState($repository->buscaConfiguracao());

        parent::__construct($entidade->toArray());
    }

    public function getAlvaraAutonomo()
    {
        return $this->get('alvaraAutonomo');
    }

    public function getAlvaraEmpresa()
    {
        return $this->get('alvaraEmpresa');
    }

    public function getAlvaraMei()
    {
        return $this->get('alvaraMei');
    }

    public function getAlvaraAutonomoProcessoEletronico()
    {
        return $this->get('alvaraAutonomoProcessoEletronico');
    }

    public function getAlvaraEmpresaProcessoEletronico()
    {
        return $this->get('alvaraEmpresaProcessoEletronico');
    }

    public function getAlvaraMeiProcessoEletronico()
    {
        return $this->get('alvaraMeiProcessoEletronico');
    }

    public function getAlvaraBaixoRisco()
    {
        return $this->get('alvaraBaixoRisco');
    }

    public function getAlvaraMedioRisco()
    {
        return $this->get('alvaraMedioRisco');
    }

    public function getAlvaraAltoRisco()
    {
        return $this->get('alvaraAltoRisco');
    }

    /**
     * @throws \Exception
     */
    public function getAcaoByTipoProcesso($tipoProcesso)
    {
        switch ($tipoProcesso) {
            case $this->getAlvaraAutonomo():
            case $this->getAlvaraAutonomoProcessoEletronico():
                $acao = ProcessoEletronicoHelper::ACAO_ALVARA_AUTONOMO;
                break;

            case $this->getAlvaraEmpresa():
            case $this->getAlvaraEmpresaProcessoEletronico():
            case AtendimentoInclusaoInscricaoJsonService::getTipoProcesso():
                $acao = ProcessoEletronicoHelper::ACAO_ALVARA_EMPRESA;
                break;

            case $this->getAlvaraMei():
            case $this->getAlvaraMeiProcessoEletronico():
                $acao = ProcessoEletronicoHelper::ACAO_ALVARA_MEI;
                break;

            default:
                throw new \Exception('Tipo de processo inválido');
                break;
        }

        return $acao;
    }
}
