<?php

namespace ECidade\Tributario\Arrecadacao\EmissaoRecibo\RegrasParcelamento;

use DateTime;
use Instituicao;

/**
 * Class TipoParcelamento
 * @package ECidade\Tributario\Arrecadacao\EmissaoRecibo\RegrasParcelamento
 */
class TipoParcelamento
{
    /**
     * @var int
     */
    private $tipoparc = 0;
    /**
     * @var string
     */
    private $descr;
    /**
     * @var DateTime
     */
    private $dtini;
    /**
     * @var DateTime
     */
    private $dtfim;
    /**
     * @var int
     */
    private $maxparc = 0;
    /**
     * @var float
     */
    private $vlrmin = 0;
    /**
     * @var DateTime
     */
    private $dtvlr;
    /**
     * @var string
     */
    private $inflat;
    /**
     * @var float
     */
    private $descmul = 0;
    /**
     * @var float
     */
    private $descjur = 0;
    /**
     * @var float
     */
    private $descvlr = 0;
    /**
     * @var int
     */
    private $cadtipoparc = 0;
    /**
     * @var float
     */
    private $k42_minentrada = 0;
    /**
     * @var Instituicao
     */
    private $instit;
    /**
     * @var int
     */
    private $tipovlr;
    /**
     * @var int
     */
    private $minparc;

    /**
     * TipoParcelamento constructor.
     * @param Instituicao $instit
     */
    public function __construct(Instituicao $instit)
    {
        $this->instit = $instit;
    }

    /**
     * @return int
     */
    public function getTipoparc()
    {
        return $this->tipoparc;
    }

    /**
     * @param int $tipoparc
     * @return TipoParcelamento
     */
    public function setTipoparc($tipoparc)
    {
        $this->tipoparc = (int)$tipoparc;
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
     * @param string $descr
     * @return TipoParcelamento
     */
    public function setDescr($descr)
    {
        $this->descr = (string)$descr;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDtini()
    {
        return $this->dtini;
    }

    /**
     * @param string $dtini
     * @return TipoParcelamento
     */
    public function setDtini($dtini)
    {
        $this->dtini = new DateTime($dtini);
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDtfim()
    {
        return $this->dtfim;
    }

    /**
     * @param string $dtfim
     * @return TipoParcelamento
     */
    public function setDtfim($dtfim)
    {
        $this->dtfim = new DateTime($dtfim);
        return $this;
    }

    /**
     * @return int
     */
    public function getMaxparc()
    {
        return $this->maxparc;
    }

    /**
     * @param int $maxparc
     * @return TipoParcelamento
     */
    public function setMaxparc($maxparc)
    {
        $this->maxparc = (int)$maxparc;
        return $this;
    }

    /**
     * @return int
     */
    public function getVlrmin()
    {
        return $this->vlrmin;
    }

    /**
     * @param int $vlrmin
     * @return TipoParcelamento
     */
    public function setVlrmin($vlrmin)
    {
        $this->vlrmin = (int)$vlrmin;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDtvlr()
    {
        return $this->dtvlr;
    }

    /**
     * @param string $dtvlr
     * @return TipoParcelamento
     */
    public function setDtvlr($dtvlr)
    {
        $this->dtvlr = new DateTime($dtvlr);
        return $this;
    }

    /**
     * @return string
     */
    public function getInflat()
    {
        return $this->inflat;
    }

    /**
     * @param string $inflat
     * @return TipoParcelamento
     */
    public function setInflat($inflat)
    {
        $this->inflat = (string)$inflat;
        return $this;
    }

    /**
     * @return float
     */
    public function getDescmul()
    {
        return $this->descmul;
    }

    /**
     * @param float $descmul
     * @return TipoParcelamento
     */
    public function setDescmul($descmul)
    {
        $this->descmul = (float)$descmul;
        return $this;
    }

    /**
     * @return float
     */
    public function getDescjur()
    {
        return $this->descjur;
    }

    /**
     * @param float $descjur
     * @return TipoParcelamento
     */
    public function setDescjur($descjur)
    {
        $this->descjur = (float)$descjur;
        return $this;
    }

    /**
     * @return float
     */
    public function getDescvlr()
    {
        return $this->descvlr;
    }

    /**
     * @param float $descvlr
     * @return TipoParcelamento
     */
    public function setDescvlr($descvlr)
    {
        $this->descvlr = (float)$descvlr;
        return $this;
    }

    /**
     * @return int
     */
    public function getCadtipoparc()
    {
        return $this->cadtipoparc;
    }

    /**
     * @param int $cadtipoparc
     * @return TipoParcelamento
     */
    public function setCadtipoparc($cadtipoparc)
    {
        $this->cadtipoparc = (int)$cadtipoparc;
        return $this;
    }

    /**
     * @return float
     */
    public function getK42Minentrada()
    {
        return $this->k42_minentrada;
    }

    /**
     * @param float $k42_minentrada
     * @return TipoParcelamento
     */
    public function setK42Minentrada($k42_minentrada)
    {
        $this->k42_minentrada = (float)$k42_minentrada;
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
     * @param Instituicao $instit
     * @return TipoParcelamento
     */
    public function setInstit(Instituicao $instit)
    {
        $this->instit = $instit;
        return $this;
    }

    /**
     * @return int
     */
    public function getTipovlr()
    {
        return $this->tipovlr;
    }

    /**
     * @param int $tipovlr
     * @return TipoParcelamento
     */
    public function setTipovlr($tipovlr)
    {
        $this->tipovlr = (int)$tipovlr;
        return $this;
    }

    /**
     * @return int
     */
    public function getMinparc()
    {
        return $this->minparc;
    }

    /**
     * @param int $minparc
     * @return TipoParcelamento
     */
    public function setMinparc($minparc)
    {
        $this->minparc = (int)$minparc;
        return $this;
    }
}
