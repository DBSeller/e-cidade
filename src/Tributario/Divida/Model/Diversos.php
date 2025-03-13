<?php

namespace ECidade\Tributario\Divida\Model;

use CgmFactory;
use DateTime;
use Instituicao;

class Diversos
{
    /**
     * @var integer
     */
    private $codigo;
    /**
     * @var \CgmBase
     */
    private $cgm;
    /**
     * @var DateTime
     */
    private $dataInscricao;
    /**
     * @var integer
     */
    private $exercicio;
    /**
     * @var integer
     */
    private $numpre;
    /**
     * @var float
     */
    private $valorHistorico;
    /**
     * @var integer
     */
    private $processo;
    /**
     * @var integer
     */
    private $numeroParcelas;
    /**
     * @var DateTime
     */
    private $primeiroVencimento;
    /**
     * @var DateTime
     */
    private $proximoVencimento;
    /**
     * @var integer
     */
    private $diaProximoVencimento;
    /**
     * @var DateTime
     */
    private $dataOperacao;
    /**
     * @var float
     */
    private $valorCorrigidoDebito;
    /**
     * @var string
     */
    private $observacao;
    /**
     * @var Instituicao
     */
    private $instituicao;

    /**
     * @return int
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param int $codigo
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    }

    /**
     * @return \CgmBase
     */
    public function getCgm()
    {
        return $this->cgm;
    }

    /**
     * @param \CgmBase $cgm
     */
    public function setCgm($cgm)
    {
        $this->cgm = $cgm;
    }

    /**
     * @return DateTime
     */
    public function getDataInscricao()
    {
        return $this->dataInscricao;
    }

    /**
     * @param DateTime $dataInscricao
     */
    public function setDataInscricao($dataInscricao)
    {
        $this->dataInscricao = $dataInscricao;
    }

    /**
     * @return int
     */
    public function getExercicio()
    {
        return $this->exercicio;
    }

    /**
     * @param int $exercicio
     */
    public function setExercicio($exercicio)
    {
        $this->exercicio = $exercicio;
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
     * @return float
     */
    public function getValorHistorico()
    {
        return $this->valorHistorico;
    }

    /**
     * @param float $valorHistorico
     */
    public function setValorHistorico($valorHistorico)
    {
        $this->valorHistorico = $valorHistorico;
    }

    /**
     * @return int
     */
    public function getProcesso()
    {
        return $this->processo;
    }

    /**
     * @param int $processo
     */
    public function setProcesso($processo)
    {
        $this->processo = $processo;
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
     * @return DateTime
     */
    public function getPrimeiroVencimento()
    {
        return $this->primeiroVencimento;
    }

    /**
     * @param DateTime $primeiroVencimento
     */
    public function setPrimeiroVencimento($primeiroVencimento)
    {
        $this->primeiroVencimento = $primeiroVencimento;
    }

    /**
     * @return DateTime
     */
    public function getProximoVencimento()
    {
        return $this->proximoVencimento;
    }

    /**
     * @param DateTime $proximoVencimento
     */
    public function setProximoVencimento($proximoVencimento)
    {
        $this->proximoVencimento = $proximoVencimento;
    }

    /**
     * @return int
     */
    public function getDiaProximoVencimento()
    {
        return $this->diaProximoVencimento;
    }

    /**
     * @param int $diaProximoVencimento
     */
    public function setDiaProximoVencimento($diaProximoVencimento)
    {
        $this->diaProximoVencimento = $diaProximoVencimento;
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
     * @return float
     */
    public function getValorCorrigidoDebito()
    {
        return $this->valorCorrigidoDebito;
    }

    /**
     * @param float $valorCorrigidoDebito
     */
    public function setValorCorrigidoDebito($valorCorrigidoDebito)
    {
        $this->valorCorrigidoDebito = $valorCorrigidoDebito;
    }

    /**
     * @return string
     */
    public function getObservacao()
    {
        return $this->observacao;
    }

    /**
     * @param string $observacao
     */
    public function setObservacao($observacao)
    {
        $this->observacao = $observacao;
    }

    /**
     * @return Instituicao
     */
    public function getInstituicao()
    {
        return $this->instituicao;
    }

    /**
     * @param Instituicao $instituicao
     */
    public function setInstituicao($instituicao)
    {
        $this->instituicao = $instituicao;
    }

    /**
     * @param  $state
     * @return Diversos
     * @throws \Exception
     */
    public static function fromState($state)
    {
        $self = new self();
        if (array_key_exists('dv05_coddiver', $state)) {
            $self->setCodigo($state['dv05_coddiver']);
        }
        if (array_key_exists('dv05_numcgm', $state)) {
            $cgm = CgmFactory::getInstanceByCgm($state['dv05_numcgm']);
            $self->setCgm($cgm);
        }
        if (array_key_exists('dv05_dtinsc', $state)) {
            $dataInscricao = new DateTime($state['dv05_dtinsc']);
            $self->setDataInscricao($dataInscricao);
        }
        if (array_key_exists('dv05_exerc', $state)) {
            $self->setExercicio($state['dv05_exerc']);
        }
        if (array_key_exists('dv05_numpre', $state)) {
            $self->setNumpre($state['dv05_numpre']);
        }
        if (array_key_exists('dv05_vlrhis', $state)) {
            $self->setValorHistorico($state['dv05_vlrhis']);
        }
        if (array_key_exists('dv05_procdiver', $state)) {
            $self->setProcesso($state['dv05_procdiver']);
        }
        if (array_key_exists('dv05_numtot', $state)) {
            $self->setNumeroParcelas($state['dv05_numtot']);
        }
        if (array_key_exists('dv05_privenc', $state)) {
            $primeiroVencimento = new DateTime($state['dv05_privenc']);
            $self->setPrimeiroVencimento($primeiroVencimento);
        }
        if (array_key_exists('dv05_provenc', $state)) {
            $proximoVencimento = new DateTime($state['dv05_provenc']);
            $self->setProximoVencimento($proximoVencimento);
        }
        if (array_key_exists('dv05_diaprox', $state)) {
            $self->setDiaProximoVencimento($state['dv05_diaprox']);
        }
        if (array_key_exists('dv05_oper', $state)) {
            $dataOperacao = new DateTime($state['dv05_oper']);
            $self->setDataOperacao($dataOperacao);
        }
        if (array_key_exists('dv05_valor', $state)) {
            $self->setValorCorrigidoDebito($state['dv05_valor']);
        }
        if (array_key_exists('dv05_obs', $state)) {
            $self->setObservacao($state['dv05_obs']);
        }
        if (array_key_exists('dv05_instit', $state)) {
            $instituicao = \InstituicaoRepository::getInstituicaoByCodigo($state['dv05_instit']);
            $self->setInstituicao($instituicao);
        }

        return $self;
    }
}
