<?php

namespace ECidade\Tributario\Cadastro\Model;

use DateTime;
use Instituicao;
use ECidade\Tributario\Library\Model;

class CadTipoParc extends Model
{
    /**
     * @var integer
     */
    private $codigo;

    /**
     * @var string
     */
    private $descr;

    /**
     * @var date
     */
    private $datalanc;

    /**
     * @var date
     */
    private $dtini;

    /**
     * @var date
     */
    private $dtfim;

    /**
     * @var boolean
     */
    private $todasmarc;

    /**
     * @var boolean
     */
    private $permvalparc;

    /**
     * @var integer
     */
    private $vctopadrao;

    /**
     * @var integer
     */
    private $diapulames;

    /**
     * @var integer
     */
    private $forma;

    /**
     * @var integer
     */
    private $instit;

    /**
     * @var integer
     */
    private $aplicacao;

    /**
     * @var integer
     */
    private $db_documento;

    /**
     * @var integer
     */
    private $ordem;

    /**
     * @var date
     */
    private $dtreparc;

    /**
     * @var integer
     */
    private $qtdreparc;

    /**
     * @var integer
     */
    private $permanula;

    /**
     * @var integer
     */
    private $regraunif;

    /**
     * @var boolean
     */
    private $bloqueio;

    /**
     * @var integer
     */
    private $tipoanulacao;

    /**
     * @var boolean
     */
    private $permvalcadparc;

    /**
     * @var boolean
     */
    private $permdataparc;

    /**
     * @var boolean
     */
    private $controlavencimento;

    /**
     * @return integer
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param integer
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescr()
    {
        return $this->descr;
    }

    /**
     * @param string
     */
    public function setDescr($descr)
    {
        $this->descr = $descr;
        return $this;
    }

    /**
     * @return date
     */
    public function getDatalanc()
    {
        return $this->datalanc;
    }

    /**
     * @param date
     */
    public function setDatalanc($datalanc)
    {
        $this->datalanc = $datalanc;
        return $this;
    }

    /**
     * @return date
     */
    public function getDtini()
    {
        return $this->dtini;
    }

    /**
     * @param date
     */
    public function setDtini($dtini)
    {
        $this->dtini = $dtini;
        return $this;
    }

    /**
     * @return date
     */
    public function getDtfim()
    {
        return $this->dtfim;
    }

    /**
     * @param date
     */
    public function setDtfim($dtfim)
    {
        $this->dtfim = $dtfim;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getTodasmarc()
    {
        return $this->todasmarc;
    }

    /**
     * @param boolean
     */
    public function setTodasmarc($todasmarc)
    {
        $this->todasmarc = $todasmarc;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getPermvalparc()
    {
        return $this->permvalparc;
    }

    /**
     * @param boolean
     */
    public function setPermvalparc($permvalparc)
    {
        $this->permvalparc = $permvalparc;
        return $this;
    }

    /**
     * @return integer
     */
    public function getVctopadrao()
    {
        return $this->vctopadrao;
    }

    /**
     * @param integer
     */
    public function setVctopadrao($vctopadrao)
    {
        $this->vctopadrao = $vctopadrao;
        return $this;
    }

    /**
     * @return integer
     */
    public function getDiapulames()
    {
        return $this->diapulames;
    }

    /**
     * @param integer
     */
    public function setDiapulames($diapulames)
    {
        $this->diapulames = $diapulames;
        return $this;
    }

    /**
     * @return integer
     */
    public function getForma()
    {
        return $this->forma;
    }

    /**
     * @param integer
     */
    public function setForma($forma)
    {
        $this->forma = $forma;
        return $this;
    }

    /**
     * @return Instituicao
     */
    public function getInstit()
    {
        return $this->instit;
    }

    /**
     * @param Instituicao
     */
    public function setInstit($instit)
    {
        $this->instit = $instit;
        return $this;
    }

    /**
     * @return integer
     */
    public function getAplicacao()
    {
        return $this->aplicacao;
    }

    /**
     * @param integer
     */
    public function setAplicacao($aplicacao)
    {
        $this->aplicacao = $aplicacao;
        return $this;
    }

    /**
     * @return integer
     */
    public function getDocumento()
    {
        return $this->documento;
    }

    /**
     * @param integer
     */
    public function setDocumento($documento)
    {
        $this->documento = $documento;
        return $this;
    }

    /**
     * @return integer
     */
    public function getOrdem()
    {
        return $this->ordem;
    }

    /**
     * @param integer
     */
    public function setOrdem($ordem)
    {
        $this->ordem = $ordem;
        return $this;
    }

    /**
     * @return date
     */
    public function getDtreparc()
    {
        return $this->dtreparc;
    }

    /**
     * @param date
     */
    public function setDtreparc($dtreparc)
    {
        $this->dtreparc = $dtreparc;
        return $this;
    }

    /**
     * @return integer
     */
    public function getQtdreparc()
    {
        return $this->qtdreparc;
    }

    /**
     * @param integer
     */
    public function setQtdreparc($qtdreparc)
    {
        $this->qtdreparc = $qtdreparc;
        return $this;
    }

    /**
     * @return integer
     */
    public function getPermanula()
    {
        return $this->permanula;
    }

    /**
     * @param integer
     */
    public function setPermanula($permanula)
    {
        $this->permanula = $permanula;
        return $this;
    }

    /**
     * @return integer
     */
    public function getRegraunif()
    {
        return $this->regraunif;
    }

    /**
     * @param integer
     */
    public function setRegraunif($regraunif)
    {
        $this->regraunif = $regraunif;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getBloqueio()
    {
        return $this->bloqueio;
    }

    /**
     * @param boolean
     */
    public function setBloqueio($bloqueio)
    {
        $this->bloqueio = $bloqueio;
        return $this;
    }

    /**
     * @return integer
     */
    public function getTipoanulacao()
    {
        return $this->tipoanulacao;
    }

    /**
     * @param integer
     */
    public function setTipoanulacao($tipoanulacao)
    {
        $this->tipoanulacao = $tipoanulacao;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getPermvalcadparc()
    {
        return $this->permvalcadparc;
    }

    /**
     * @param boolean
     */
    public function setPermvalcadparc($permvalcadparc)
    {
        $this->permvalcadparc = $permvalcadparc;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getPermdataparc()
    {
        return $this->permdataparc;
    }

    /**
     * @param boolean
     */
    public function setPermdataparc($permdataparc)
    {
        $this->permdataparc = $permdataparc;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getControlavencimento()
    {
        return $this->controlavencimento;
    }

    /**
     * @param boolean
     */
    public function setControlavencimento($controlavencimento)
    {
        $this->controlavencimento = $controlavencimento;
        return $this;
    }

    /**
     * @param  $state
     * @return CadTipoParc
     * @throws \Exception
     */
    public static function fromState($state)
    {
        $self = new self();
       
        if (array_key_exists('v50_codmov', $state)) {
            $self->setCodmov($state['v50_codmov']);
        }

        if (array_key_exists('k40_codigo', $state)) {
            $self->setCodigo($state['k40_codigo']);
        }

        if (array_key_exists('k40_descr', $state)) {
            $self->setDescr($state['k40_descr']);
        }

        if (array_key_exists('k40_datalanc', $state)) {
            $datalanc = new DateTime($state['k40_datalanc']);
            $self->setDatalanc($datalanc);
        }

        if (array_key_exists('k40_dtini', $state)) {
            $dtini = new DateTime($state['k40_dtini']);
            $self->setDtini($dtini);
        }

        if (array_key_exists('k40_dtfim', $state)) {
            $dtfim = new DateTime($state['k40_dtfim']);
            $self->setDtfim($dtfim);
        }

        if (array_key_exists('k40_todasmarc', $state)) {
            $self->setTodasmarc($state['k40_todasmarc']);
        }

        if (array_key_exists('k40_permvalparc', $state)) {
            $self->setPermvalparc($state['k40_permvalparc']);
        }

        if (array_key_exists('k40_vctopadrao', $state)) {
            $self->setVctopadrao($state['k40_vctopadrao']);
        }

        if (array_key_exists('k40_diapulames', $state)) {
            $self->setDiapulames($state['k40_diapulames']);
        }

        if (array_key_exists('k40_forma', $state)) {
            $self->setForma($state['k40_forma']);
        }

        if (array_key_exists('k40_instit', $state)) {
            $instituicao = \InstituicaoRepository::getInstituicaoByCodigo($state['k40_instit']);
            $self->setInstit($instituicao);
        }

        if (array_key_exists('k40_aplicacao', $state)) {
            $self->setAplicacao($state['k40_aplicacao']);
        }

        if (array_key_exists('k40_db_documento', $state)) {
            $self->setDocumento($state['k40_db_documento']);
        }

        if (array_key_exists('k40_ordem', $state)) {
            $self->setOrdem($state['k40_ordem']);
        }

        if (array_key_exists('k40_dtreparc', $state)) {
            $dtreparc = new DateTime($state['k40_dtreparc']);
            $self->setDtreparc($dtreparc);
        }

        if (array_key_exists('k40_qtdreparc', $state)) {
            $self->setQtdreparc($state['k40_qtdreparc']);
        }

        if (array_key_exists('k40_permanula', $state)) {
            $self->setPermanula($state['k40_permanula']);
        }

        if (array_key_exists('k40_regraunif', $state)) {
            $self->setRegraunif($state['k40_regraunif']);
        }

        if (array_key_exists('k40_bloqueio', $state)) {
            $self->setBloqueio($state['k40_bloqueio']);
        }

        if (array_key_exists('k40_tipoanulacao', $state)) {
            $self->setTipoanulacao($state['k40_tipoanulacao']);
        }

        if (array_key_exists('k40_permvalcadparc', $state)) {
            $self->setPermvalcadparc($state['k40_permvalcadparc']);
        }

        if (array_key_exists('k40_permdataparc', $state)) {
            $self->setPermdataparc($state['k40_permdataparc']);
        }

        if (array_key_exists('k40_controlavencimento', $state)) {
            $self->setControlavencimento($state['k40_controlavencimento']);
        }

        return $self;
    }
}
