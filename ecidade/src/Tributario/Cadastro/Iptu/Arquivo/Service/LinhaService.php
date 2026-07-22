<?php

namespace ECidade\Tributario\Cadastro\Iptu\Arquivo\Service;

use ECidade\Tributario\Library\Service;
use ECidade\Tributario\Cadastro\Iptu\Arquivo\Entity\Filtro;
use ECidade\Tributario\Cadastro\Iptu\Arquivo\Repository\ContribuinteRepository;
use ECidade\Tributario\Cadastro\Iptu\Arquivo\Repository\DebitoRepository;
use ECidade\Tributario\Cadastro\Iptu\Arquivo\Repository\ExercicioRepository;
use ECidade\Tributario\Cadastro\Iptu\Arquivo\Repository\imovelAnteriorRepository;
use ECidade\Tributario\Cadastro\Iptu\Arquivo\Repository\ImovelRepository;
use ECidade\Tributario\Cadastro\Iptu\Arquivo\Repository\TaxaRepository;
use ECidade\Tributario\Cadastro\Iptu\Arquivo\Repository\TaxaDescricaoRepository;
use ECidade\Tributario\Cadastro\Iptu\Arquivo\Repository\LoteamentoRepository;
use ECidade\Tributario\Cadastro\Iptu\Arquivo\Service\LinhaConverterService;
use ECidade\Tributario\Cadastro\Iptu\Arquivo\Service\ParcelaInicioService;
use ECidade\Tributario\Cadastro\Iptu\Arquivo\Service\ReciboCotaUnicaService;
use ECidade\Tributario\Cadastro\Iptu\Arquivo\Service\ReciboCarneService;
use ECidade\Tributario\Cadastro\Iptu\Arquivo\Service\ReciboParcelaService;
use ECidade\Tributario\Cadastro\Entity\Matricula;
use ECidade\Tributario\Cadastro\Model\Cfiptu;
use ECidade\Tributario\Cadastro\Iptu\Arquivo\Cast\ParcelaReciboCast;

use ECidade\Tributario\Cadastro\Iptu\Arquivo\Entity\Exercicio;

final class LinhaService extends Service
{
    private $linhaConverterService;

    private $debitoRepository;

    private $reciboCotaUnicaService;

    private $reciboCarneService;

    private $reciboParcelaService;

    private $imovelRepository;

    private $contribuinteRepository;

    private $parcelaReciboCast;

    private $imovelAnteriorRepository;

    private $exercicioRepository;

    private $parcelaInicioService;

    private $taxaRepository;

    private $taxaDescricaoRepository;

    private $loteamentoRepository;

    public function __construct(
        LinhaConverterService $linhaConverterService,
        DebitoRepository $debitoRepository,
        ReciboCotaUnicaService $reciboCotaUnicaService,
        ReciboCarneService $reciboCarneService,
        ReciboParcelaService $reciboParcelaService,
        ImovelRepository $imovelRepository,
        ContribuinteRepository $contribuinteRepository,
        ParcelaReciboCast $parcelaReciboCast,
        ImovelAnteriorRepository $imovelAnteriorRepository,
        ExercicioRepository $exercicioRepository,
        ParcelaInicioService $parcelaInicioService,
        TaxaRepository $taxaRepository,
        TaxaDescricaoRepository $taxaDescricaoRepository,
        LoteamentoRepository $loteamentoRepository
    ) {
        $this->linhaConverterService = $linhaConverterService;
        $this->debitoRepository = $debitoRepository;
        $this->reciboCotaUnicaService = $reciboCotaUnicaService;
        $this->reciboCarneService = $reciboCarneService;
        $this->reciboParcelaService = $reciboParcelaService;
        $this->imovelRepository = $imovelRepository;
        $this->contribuinteRepository = $contribuinteRepository;
        $this->parcelaReciboCast = $parcelaReciboCast;
        $this->imovelAnteriorRepository = $imovelAnteriorRepository;
        $this->exercicioRepository = $exercicioRepository;
        $this->parcelaInicioService = $parcelaInicioService;
        $this->taxaRepository = $taxaRepository;
        $this->taxaDescricaoRepository = $taxaDescricaoRepository;
        $this->loteamentoRepository = $loteamentoRepository;
    }

    public function execute($sequencial, Matricula $matricula, Filtro $filtro, \Instituicao $instituicao)
    {
        $s = "";

        $debitos = $this->debitoRepository->findAll($matricula, $filtro);

        // @todo - arquitetura de geracao deve ser alterada ou encapsulada para teste de regra
        if ($debitos->isEmpty()) {
            return $s;
        }

        $imovel = $this->imovelRepository->find($matricula->getMatricula(), $filtro->getAno());

        $imovelAnterior = $this->imovelAnteriorRepository->find($matricula->getMatricula(), $filtro->getAno());

        $contribuinte = $this->contribuinteRepository->find($matricula->getMatricula());

        if ($instituicao->getRegraDebitosIPTU() == 1 || $contribuinte->getPromitente() == "") {
            $contribuinte->setNome($contribuinte->getProprietario());
        } else {
            $contribuinte->setNome($contribuinte->getPromitente());
        }

        $entregaLogradouro = $contribuinte->getEntregaLogradouro();
        // @todo - arquitetura de geracao deve ser alterada ou encapsulada para teste de regra
        if ($filtro->getEntregaValido() && empty($entregaLogradouro)) {
            return $s;
        }

        $entregaCidade = $contribuinte->getEntregaCidade();
        $entregaCaixaPostal = $contribuinte->getEntregaCaixaPostal();
        // @todo - arquitetura de geracao deve ser alterada ou encapsulada para teste de regra
        if ($filtro->getCidadeBranco() == false &&
            empty($entregaCidade) &&
            empty($entregaCaixaPostal)
        ) {
            return $s;
        }

        $unicas = $this->reciboCotaUnicaService->execute($filtro, $debitos);

        $recibos = $this->reciboCarneService->execute($filtro, $debitos);

        $parcelaRecibos = $this->parcelaReciboCast->arrayFromReciboCollection($recibos);

        $parcelaInicio = $this->parcelaInicioService->execute($parcelaRecibos);

        $exercicio = $this->exercicioRepository->find($matricula->getMatricula(), $filtro->getAno());

        $taxas = $this->taxaRepository->find($matricula->getMatricula(), $filtro);

        $taxaDescricao = $this->taxaDescricaoRepository->find($matricula->getMatricula(), $filtro, $unicas);

        $loteamento = $this->loteamentoRepository->find($matricula->getMatricula());

        $s .= str_pad($sequencial, 10);
        $s .= $this->linhaConverterService->build(
            $imovel,
            $contribuinte,
            $exercicio,
            $debitos,
            $unicas,
            $imovelAnterior,
            $parcelaRecibos,
            $parcelaInicio,
            $taxas,
            $taxaDescricao,
            $loteamento
        );

        return $s;
    }
}
