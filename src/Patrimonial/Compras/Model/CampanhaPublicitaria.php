<?php

namespace ECidade\Patrimonial\Compras\Model;

/**
 * Class ConfiguracaoRematricula
 * @package Ecidade\Patrimonial\Compras\Model
 */

class CampanhaPublicitaria
{

    public $codigo;
    public $cgm;
    public $nomeCgm;
    public $valorCampanha;
    public $dataInicio;
    public $dataFim;
    /**
     * @var float
     */
    public $comissaoProducao;

    /**
     * @return mixed
     */
    public function getNomeCgm()
    {
        return $this->nomeCgm;
    }

    /**
     * @param mixed $nomeCgm
     */
    public function setNomeCgm($nomeCgm)
    {
        $this->nomeCgm = $nomeCgm;
    }
    /**
     * @var float
     */
    public $comissaoVeiculacao;
    /**
     * @var int
     */
    public $tipoCampanha;
    /**
     * @var int
     */
    public $codigoMater;

    /**
     * CampanhaPublicitaria constructor.
     * @param null $codigo
     **/
    public function __construct($codigo = null)
    {
        if ($codigo) {
            $campanhapublicitaria = CampanhaPublicitariaRepository::find(sequencial);

            $this->codigo = $campanhapublicitaria->getCodigo();
            $this->cgm = $campanhapublicitaria->getCgm();
            $this->nomeCgm = $campanhapublicitaria->getNomeCgm();
            $this->valorCampanha = $campanhapublicitaria->getValorCampanha();
            $this->dataInicio = $campanhapublicitaria->getDataInicio();
            $this->dataFim = $campanhapublicitaria->getDataFim();
            $this->comissaoProducao = $campanhapublicitaria->getComissaoProducao();
            $this->comissaoVeiculacao = $campanhapublicitaria->getComissaoVeiculacao();
            $this->tipoCampanha = $campanhapublicitaria->getTipoCampanha();
            $this->codigoMater = $campanhapublicitaria->getCodigoMater();
        }
    }

    public static function fromState($campanha)
    {
        $codigo = $campanha['pc94_codigo'];
        $cgm = $campanha['pc94_cgm'];
        $nomeCgm = $campanha['z01_nome'];
        $valorCampanha = $campanha['pc94_valorcampanha'];
        $dataInicio = $campanha['pc94_datainicio'];
        $dataFim = $campanha['pc94_datafim'];
        $comissaoProducao = $campanha['pc94_comissaoproducao'];
        $comissaoVeiculacao = $campanha['pc94_comissaoveiculacao'];
        $tipoCampanha = $campanha['pc94_pctipocampanhapublicitaria'];
        $codigoMater = $campanha['pc94_pcmater'];

        $campanhaPublicitaria = new self();
        $campanhaPublicitaria->setCodigo($codigo);
        $campanhaPublicitaria->setCgm($cgm);
        $campanhaPublicitaria->setNomeCgm($nomeCgm);
        $campanhaPublicitaria->setValorCampanha($valorCampanha);
        $campanhaPublicitaria->setDatafim($dataFim);
        $campanhaPublicitaria->setDatainicio($dataInicio);
        $campanhaPublicitaria->setComissaoproducao($comissaoProducao);
        $campanhaPublicitaria->setComissaoveiculacao($comissaoVeiculacao);
        $campanhaPublicitaria->setTipoCampanha($tipoCampanha);
        $campanhaPublicitaria->setCodigoMater($codigoMater);

        return $campanhaPublicitaria;
    }

    /**
     * @return mixed
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param mixed $codigo
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    }

    /**
     * @return mixed
     */
    public function getCgm()
    {
        return $this->cgm;
    }

    /**
     * @param mixed $cgm
     */
    public function setCgm($cgm)
    {
        $this->cgm = $cgm;
    }

    /**
     * @return mixed
     */
    public function getValorCampanha()
    {
        return $this->valorCampanha;
    }

    /**
     * @param mixed $valorCampanha
     */
    public function setValorCampanha($valorCampanha)
    {
        $this->valorCampanha = $valorCampanha;
    }

    /**
     * @return mixed
     */
    public function getDataInicio()
    {
        return $this->dataInicio;
    }

    /**
     * @param mixed $dataInicio
     */
    public function setDataInicio($dataInicio)
    {
        $this->dataInicio = $dataInicio;
    }

    /**
     * @return mixed
     */
    public function getDataFim()
    {
        return $this->dataFim;
    }

    /**
     * @param mixed $dataFim
     */
    public function setDataFim($dataFim)
    {
        $this->dataFim = $dataFim;
    }

    /**
     * @return float
     */
    public function getComissaoProducao()
    {
        return $this->comissaoProducao;
    }

    /**
     * @param float $comissaoProducao
     */
    public function setComissaoProducao($comissaoProducao)
    {
        $this->comissaoProducao = $comissaoProducao;
    }

    /**
     * @return float
     */
    public function getComissaoVeiculacao()
    {
        return $this->comissaoVeiculacao;
    }

    /**
     * @param float $comissaoVeiculacao
     */
    public function setComissaoVeiculacao($comissaoVeiculacao)
    {
        $this->comissaoVeiculacao = $comissaoVeiculacao;
    }

    /**
     * @return int
     */
    public function getTipoCampanha()
    {
        return $this->tipoCampanha;
    }

    /**
     * @param int $tipoCampanha
     */
    public function setTipoCampanha($tipoCampanha)
    {
        $this->tipoCampanha = $tipoCampanha;
    }

    /**
     * @return int
     */
    public function getCodigoMater()
    {
        return $this->codigoMater;
    }

    /**
     * @param int $codigoMater
     */
    public function setCodigoMater($codigoMater)
    {
        $this->codigoMater = $codigoMater;
    }
}
