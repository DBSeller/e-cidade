<?php

namespace ECidade\Tributario\Arrecadacao\Arquivo\Autoatendimento\RCB800\Service;

use ECidade\Tributario\Arrecadacao\Arquivo\Autoatendimento\Repository\Detalhe as DetalheRepository;
use ECidade\Tributario\Arrecadacao\Arquivo\Autoatendimento\RCB800\Converter\DetalheConverter;
use ECidade\Tributario\Arrecadacao\Arquivo\Autoatendimento\RCB800\Entity\Detalhe;
use ECidade\Tributario\Arrecadacao\Arquivo\Autoatendimento\RCB800\Layout\Detalhe as LayoutDetalhe;
use ECidade\Tributario\Arrecadacao\Arquivo\Autoatendimento\RCB800\Service\ReciboCarneService;
use ECidade\Tributario\Arrecadacao\Arquivo\Autoatendimento\RCB800\Service\ReciboCotaUnicaService;
use ECidade\Tributario\Arrecadacao\Arquivo\Autoatendimento\RCB800\Service\ReciboService;
use ECidade\Tributario\Arrecadacao\Arquivo\Autoatendimento\RCB800\Repository\TipoDebito as TipoDebitoRepository;
use ECidade\Tributario\Arrecadacao\Entity\Contribuinte;
use ECidade\Tributario\Arrecadacao\Entity\Repository\ContribuinteRepository;
use ECidade\Tributario\Caixa\Entity\Debito;
use ECidade\Tributario\Configuracao\Entity\Repository\InstituicaoRepository;
use ECidade\Tributario\Library\DataBase;
use ECidade\Tributario\Library\Format as Formater;
use ECidade\Tributario\Library\Service;
use ECidade\Tributario\Library\Session;

use \DateTime;
use \DBException;
use \cl_arquivoautoatendimentoregistros;
use \cl_arquivoautoatendimentoregistrosinscricao;
use \cl_arquivoautoatendimentoregistroscgm;
use \cl_arquivoautoatendimentoregistrosmatricula;

final class DetalheService extends Service
{
    const TIPO_ATUALIZACAO_INCLUSAO  = 1;
    const TIPO_ATUALIZACAO_ALTERACAO = 2;
    const TIPO_ATUALIZACAO_EXCLUSAO  = 3;

    private $dataBase;
    private $codigoArquivoautoatendimento;
    private $session;
    private $reciboCotaUnicaService;
    private $reciboCarneService;
    private $reciboService;
    private $instituicaoRepository;
    private $contribuinteRepository;
    private $tipoDebitoRepository;
    private $detalheRepository;

    public function __construct(
        DataBase $dataBase,
        Session $session,
        ReciboCotaUnicaService $reciboCotaUnicaService,
        ReciboCarneService $reciboCarneService,
        ReciboService $reciboService,
        InstituicaoRepository $instituicaoRepository,
        ContribuinteRepository $contribuinteRepository,
        TipoDebitoRepository $tipoDebitoRepository,
        DetalheRepository $detalheRepository
    ) {
        $this->dataBase = $dataBase;
        $this->session = $session;
        $this->reciboCotaUnicaService = $reciboCotaUnicaService;
        $this->reciboCarneService = $reciboCarneService;
        $this->reciboService = $reciboService;
        $this->instituicaoRepository = $instituicaoRepository;
        $this->contribuinteRepository = $contribuinteRepository;
        $this->tipoDebitoRepository   = $tipoDebitoRepository;
        $this->detalheRepository      = $detalheRepository;
    }

    public function execute(Debito $debito, $sequencial, $tipoDebito, $datalista, $datavigfinal)
    {
        $formater         = new Formater();
        $layoutDetalhe    = new LayoutDetalhe();
        $detalheConverter = new DetalheConverter($layoutDetalhe, $formater);
        $recibos          = $this->reciboCotaUnicaService->execute($debito, $datalista, $datavigfinal);
        $recibosParcelas  = $this->reciboCarneService->execute($debito, $datavigfinal);
        $recibo           = $this->reciboService->execute($debito, $datavigfinal);
        $instituicao      = $this->instituicaoRepository->find($this->session->getInstituicao());
        $contribuinte     = $this->contribuinteRepository->findByDebito($debito);
        $tipoDebito       = $this->tipoDebitoRepository->findByDebito($debito);

        $retorno = array();
        $recibos = array_merge($recibos, $recibosParcelas->getAll());

        foreach ($recibos as $reciboParcela) {
            $detalhe = new Detalhe();
            $detalhe->setRecibo($reciboParcela);
            $detalhe->setInstituicao($instituicao);
            $detalhe->setTipoAtualizacao(self::TIPO_ATUALIZACAO_INCLUSAO);
            $detalhe->setCodigoBarrasAgrupador($recibo->getLinhaDigitavel());
            $detalhe->setContribuinte($contribuinte);
            $detalhe->setSequencial($sequencial);
            $detalhe->setTipoDebito($tipoDebito->codigo);
            $detalhe->setNomeTipoDebito($tipoDebito->descricao);


            $sequencial++;

            $retorno[] = $detalheConverter->build($detalhe);
            $this->incluirDetalhe($detalhe);
        }

        return $retorno;
    }

    public function incluirDetalhe($detalhe)
    {
        $daoArquivoautoatendimentoregistros = $this->detalheRepository->persist((object)array(
            'k183_codigo'          => null,
            'k183_autoatendimento' => $this->codigoArquivoautoatendimento,
            'k183_tipodebito'      => $detalhe->getTipoDebito(),
            'k183_situacao'        => null,
            'k183_numnov'          => $detalhe->getRecibo()->getNumpre()
        ));

        $this->persistContribuinte($daoArquivoautoatendimentoregistros, $detalhe);

        return $daoArquivoautoatendimentoregistros;
    }

    public function persistContribuinte($daoArquivoautoatendimentoregistros, $detalhe)
    {
        switch ($detalhe->getContribuinte()->getTipo()) {
            case Contribuinte::INSCRICAO:
                $daoArquivoautoatendimentocontribuinte = new cl_arquivoautoatendimentoregistrosinscricao();
                $sigla = 'k187';
                $identificacao = 'k187_inscricao';
                break;

            case Contribuinte::CGM:
                $daoArquivoautoatendimentocontribuinte = new cl_arquivoautoatendimentoregistroscgm();
                $sigla = 'k188';
                $identificacao = 'k188_cgm';
                break;

            default:
                $daoArquivoautoatendimentocontribuinte = new cl_arquivoautoatendimentoregistrosmatricula();
                $sigla = 'k186';
                $identificacao = 'k186_matricula';
                break;
        }

        $fkRegistros = "{$sigla}_autoatendimentoregistros";

        $daoArquivoautoatendimentocontribuinte->{$fkRegistros}   = $daoArquivoautoatendimentoregistros->k183_codigo;
        $daoArquivoautoatendimentocontribuinte->{$identificacao} = $detalhe->getContribuinte()->getIdentificador();

        if (!$daoArquivoautoatendimentocontribuinte->incluir(null)) {
            throw new DBException($daoArquivoautoatendimentocontribuinte->erro_msg);
        }

        return $daoArquivoautoatendimentocontribuinte;
    }

    public function getCodigoArquivoautoatendimento()
    {
        return $this->codigoArquivoautoatendimento;
    }

    public function setCodigoArquivoautoatendimento($codigoArquivoautoatendimento)
    {
        $this->codigoArquivoautoatendimento = $codigoArquivoautoatendimento;
    }
}
