<?php

namespace ECidade\Patrimonial\Protocolo\Processo\AlvaraOnline\Parser\Factory;

use App\Domain\Tributario\ISSQN\Services\Redesim\InclusaoEmpresa\AtendimentoInclusaoInscricaoJsonService;
use BusinessException;
use ParameterException;
use ECidade\Tributario\Issqn\ParametrosProcessoEletronicoBag;
use ECidade\Patrimonial\Protocolo\Processo\AlvaraOnline\Parser\Entity\AlvaraMei as ParserAlvaraMei;
use ECidade\Patrimonial\Protocolo\Processo\AlvaraOnline\Parser\Entity\AlvaraEmpresa as ParserAlvaraEmpresa;
use ECidade\Patrimonial\Protocolo\Processo\AlvaraOnline\Parser\Entity\AlvaraAutonomo as ParserAlvaraAutonomo;

use ECidade\Patrimonial\Protocolo\Processo\ProcessoEletronico\Parser\Entity\AlvaraMei
as ParserAlvaraMeiProcessoEletronico;
use ECidade\Patrimonial\Protocolo\Processo\ProcessoEletronico\Parser\Entity\AlvaraEmpresa
as ParserAlvaraEmpresaProcessoEletronico;
use ECidade\Patrimonial\Protocolo\Processo\ProcessoEletronico\Parser\Entity\AlvaraAutonomo
as ParserAlvaraAutonomoProcessoEletronico;

class ParserAlvaraFactory
{
    public $collectionAtividades;

    public function __construct($collectionAtividades)
    {
        $this->collectionAtividades = $collectionAtividades;
    }

    public static function getInstance($collectionAtividades)
    {
        static $instance = null;

        if (null === $instance) {
            $instance = new static($collectionAtividades);
        }

        return $instance;
    }

    /**
     * @param $filtroProcessos
     * @return ParserAlvaraAutonomo|ParserAlvaraEmpresa|ParserAlvaraMei
     * @throws BusinessException
     * @throws ParameterException
     * @throws \Exception
     */
    public function create($filtroProcessos, ParametrosProcessoEletronicoBag $parameterBag)
    {
        switch ($filtroProcessos->getCodigoTipoProcesso()) {
            case $parameterBag->getAlvaraAutonomo():
                return new ParserAlvaraAutonomo($this->collectionAtividades);
                break;

            case $parameterBag->getAlvaraAutonomoProcessoEletronico():
                return new ParserAlvaraAutonomoProcessoEletronico($this->collectionAtividades);
                break;

            case $parameterBag->getAlvaraEmpresa():
                return new ParserAlvaraEmpresa($this->collectionAtividades);
                break;

            case $parameterBag->getAlvaraEmpresaProcessoEletronico():
            case AtendimentoInclusaoInscricaoJsonService::getTipoProcesso():
                return new ParserAlvaraEmpresaProcessoEletronico($this->collectionAtividades);
                break;

            case $parameterBag->getAlvaraMei():
                return new ParserAlvaraMei($this->collectionAtividades);
                break;

            case $parameterBag->getAlvaraMeiProcessoEletronico():
                return new ParserAlvaraMeiProcessoEletronico($this->collectionAtividades);
                break;

            default:
                throw new BusinessException("Não foi possí­vel identificar o tipo de parser a carregar.");
                break;
        }
    }
}
