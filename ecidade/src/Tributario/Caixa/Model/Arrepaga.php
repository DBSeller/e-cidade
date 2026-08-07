<?php

namespace ECidade\Tributario\Caixa\Model;

use CgmBase;
use CgmFactory;
use DateTime;
use ECidade\Tributario\Caixa\Repository\RecibopagaRepository;
use ECidade\Tributario\Divida\Model\Disbanco;
use ECidade\Tributario\Divida\Registry\DisbancoRegistry;
use ECidade\Tributario\Library\DataBase;
use Exception;

class Arrepaga
{
    /**
     * @var integer
     */
    private $numpre;
    /**
     * @var integer
     */
    private $numeroParcela;
    /**
     * @var CgmBase
     */
    private $cgm;
    /**
     * @var DateTime
     */
    private $dataOperacao;
    /**
     * @var integer
     */
    private $receita;
    /**
     * @var integer
     */
    private $historico;
    /**
     * @var float
     */
    private $valor;
    /**
     * @var DateTime
     */
    private $dataVencimento;
    /**
     * @var integer
     */
    private $numeroParcelas;
    /**
     * @var integer
     */
    private $digitoVerificador;
    /**
     * @var integer
     */
    private $conta;
    /**
     * @var DateTime
     */
    private $dataPagamento;

    /**
     * @var ReciboPaga[]
     */
    private $reciboPaga = array();

    /**
     * @var Disbanco
     */
    private $disbanco;

    /**
     * @return ReciboPaga[]
     */
    public function getReciboPaga()
    {
        return $this->reciboPaga;
    }

    /**
     * @param ReciboPaga[] $reciboPaga
     */
    public function setReciboPaga($reciboPaga)
    {
        $this->reciboPaga = $reciboPaga;
    }

    /**
     * @return int
     */
    public function getNumpre()
    {
        return $this->numpre;
    }

    /**
     * @param int $numpre
     */
    public function setNumpre($numpre)
    {
        $this->numpre = $numpre;
    }

    /**
     * @return int
     */
    public function getNumeroParcela()
    {
        return $this->numeroParcela;
    }

    /**
     * @param int $numeroParcela
     */
    public function setNumeroParcela($numeroParcela)
    {
        $this->numeroParcela = $numeroParcela;
    }

    /**
     * @return CgmBase
     */
    public function getCgm()
    {
        return $this->cgm;
    }

    /**
     * @param CgmBase $cgm
     */
    public function setCgm($cgm)
    {
        $this->cgm = $cgm;
    }

    /**
     * @return DateTime
     */
    public function getDataOperacao()
    {
        return $this->dataOperacao;
    }

    /**
     * @param DateTime $dataOperacao
     */
    public function setDataOperacao($dataOperacao)
    {
        $this->dataOperacao = $dataOperacao;
    }

    /**
     * @return int
     */
    public function getReceita()
    {
        return $this->receita;
    }

    /**
     * @param int $receita
     */
    public function setReceita($receita)
    {
        $this->receita = $receita;
    }

    /**
     * @return int
     */
    public function getHistorico()
    {
        return $this->historico;
    }

    /**
     * @param int $historico
     */
    public function setHistorico($historico)
    {
        $this->historico = $historico;
    }

    /**
     * @return float
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * @param float $valor
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
    }

    /**
     * @return DateTime
     */
    public function getDataVencimento()
    {
        return $this->dataVencimento;
    }

    /**
     * @param DateTime $dataVencimento
     */
    public function setDataVencimento($dataVencimento)
    {
        $this->dataVencimento = $dataVencimento;
    }

    /**
     * @return int
     */
    public function getNumeroParcelas()
    {
        return $this->numeroParcelas;
    }

    /**
     * @param int $numeroParcelas
     */
    public function setNumeroParcelas($numeroParcelas)
    {
        $this->numeroParcelas = $numeroParcelas;
    }

    /**
     * @return int
     */
    public function getDigitoVerificador()
    {
        return $this->digitoVerificador;
    }

    /**
     * @param int $digitoVerificador
     */
    public function setDigitoVerificador($digitoVerificador)
    {
        $this->digitoVerificador = $digitoVerificador;
    }

    /**
     * @return int
     */
    public function getConta()
    {
        return $this->conta;
    }

    /**
     * @param int $conta
     */
    public function setConta($conta)
    {
        $this->conta = $conta;
    }

    /**
     * @return DateTime
     */
    public function getDataPagamento()
    {
        return $this->dataPagamento;
    }

    /**
     * @param DateTime $dataPagamento
     */
    public function setDataPagamento($dataPagamento)
    {
        $this->dataPagamento = $dataPagamento;
    }

    /**
     * @return Disbanco
     */
    public function getDisbanco()
    {
        return $this->disbanco;
    }

    /**
     * @return self
     * @throws Exception
     */
    public function withReciboPaga()
    {
        if (empty($this->reciboPaga)) {
            $database = DataBase::getInstance();
            $repository = new RecibopagaRepository($database, null);
            $reciboPaga = $repository->scopeNumpre($this->getNumpre())->get();
            $this->setReciboPaga($reciboPaga);
        }
        return $this;
    }

    /**
     * @return self
     * @throws Exception
     */
    public function withDisbanco()
    {
        $this->disbanco = DisbancoRegistry::getInstance()->disbancoPorArrepaga($this);
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDataPagamentoEfetivo()
    {
        if (!empty($this->disbanco)) {
            return $this->disbanco->getDataPago();
        }

        return $this->getDataPagamento();
    }

    /**
     * @param  array $state
     * @return Arrepaga
     * @throws Exception
     */
    public static function fromState(array $state)
    {
        $self = new self();

        if (array_key_exists('k00_numcgm', $state)) {
            $cgm = CgmFactory::getInstanceByCgm($state['k00_numcgm']);
            $self->setCgm($cgm);
        }
        if (array_key_exists('k00_dtoper', $state)) {
            $dataOperacao = new DateTime($state['k00_dtoper']);
            $self->setDataOperacao($dataOperacao);
        }
        if (array_key_exists('k00_receit', $state)) {
            $self->setReceita($state['k00_receit']);
        }
        if (array_key_exists('k00_hist', $state)) {
            $self->setHistorico($state['k00_hist']);
        }
        if (array_key_exists('k00_valor', $state)) {
            $self->setValor($state['k00_valor']);
        }
        if (array_key_exists('k00_dtvenc', $state)) {
            $dataVencimento = new DateTime($state['k00_dtvenc']);
            $self->setDataVencimento($dataVencimento);
        }
        if (array_key_exists('k00_numpre', $state)) {
            $self->setNumpre($state['k00_numpre']);
        }
        if (array_key_exists('k00_numpar', $state)) {
            $self->setNumeroParcela($state['k00_numpar']);
        }
        if (array_key_exists('k00_numtot', $state)) {
            $self->setNumeroParcelas($state['k00_numtot']);
        }
        if (array_key_exists('k00_numdig', $state)) {
            $self->setDigitoVerificador($state['k00_numdig']);
        }
        if (array_key_exists('k00_conta', $state)) {
            $self->setConta($state['k00_conta']);
        }
        if (array_key_exists('k00_dtpaga', $state)) {
            $dataPagamento = new DateTime($state['k00_dtpaga']);
            $self->setDataPagamento($dataPagamento);
        }

        return $self;
    }
}
