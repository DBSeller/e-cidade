<?php

namespace ECidade\Tributario\Caixa\Model;

use \DateTime;
use ECidade\Tributario\Library\Model;

final class Arretipo extends Model
{
    private $codbco;

    private $codage;

    private $tipo;

    private $descr;

    private $emrec;

    private $agnum;

    private $agpar;

    private $msguni;

    private $msguni2;

    private $msgparc;

    private $msgparc2;

    private $msgparcvenc;

    private $msgparcvenc2;

    private $msgrecibo;

    private $tercdigcarneunica;

    private $tercdigcarnenormal;

    private $tercdigrecunica;

    private $tercdigrecnormal;

    private $txban;

    private $rectx;

    private $codmodelo;

    private $impval;

    private $vlrmin;

    private $cadtipo;

    private $marcado;

    private $hist1;

    private $hist2;

    private $hist3;

    private $hist4;

    private $hist5;

    private $hist6;

    private $hist7;

    private $hist8;

    private $tipoagrup;

    private $recibodbpref;

    private $instit;

    private $formemissao;

    private $receitacredito;

    private $exercicioscarne;

    private $dtvencimento;

    public function setCodbco($codbco)
    {
        $this->codbco = $codbco;
    }

    public function setCodage($codage)
    {
        $this->codage = $codage;
    }

    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }

    public function setDescr($descr)
    {
        $this->descr = $descr;
    }

    public function setEmrec($emrec)
    {
        $this->emrec = $emrec;
    }

    public function setAgnum($agnum)
    {
        $this->agnum = $agnum;
    }

    public function setAgpar($agpar)
    {
        $this->agpar = $agpar;
    }

    public function setMsguni($msguni)
    {
        $this->msguni = $msguni;
    }

    public function setMsguni2($msguni2)
    {
        $this->msguni2 = $msguni2;
    }

    public function setMsgparc($msgparc)
    {
        $this->msgparc = $msgparc;
    }

    public function setMsgparc2($msgparc2)
    {
        $this->msgparc2 = $msgparc2;
    }

    public function setMsgparcvenc($msgparcvenc)
    {
        $this->msgparcvenc = $msgparcvenc;
    }

    public function setMsgparcvenc2($msgparcvenc2)
    {
        $this->msgparcvenc2 = $msgparcvenc2;
    }

    public function setMsgrecibo($msgrecibo)
    {
        $this->msgrecibo = $msgrecibo;
    }

    public function setTercdigcarneunica($tercdigcarneunica)
    {
        $this->tercdigcarneunica = $tercdigcarneunica;
    }

    public function setTercdigcarnenormal($tercdigcarnenormal)
    {
        $this->tercdigcarnenormal = $tercdigcarnenormal;
    }

    public function setTercdigrecunica($tercdigrecunica)
    {
        $this->tercdigrecunica = $tercdigrecunica;
    }

    public function setTercdigrecnormal($tercdigrecnormal)
    {
        $this->tercdigrecnormal = $tercdigrecnormal;
    }

    public function setTxban($txban)
    {
        $this->txban = $txban;
    }

    public function setRectx($rectx)
    {
        $this->rectx = $rectx;
    }

    public function setCodmodelo($codmodelo)
    {
        $this->codmodelo = $codmodelo;
    }

    public function setImpval($impval)
    {
        $this->impval = $impval;
    }

    public function setVlrmin($vlrmin)
    {
        $this->vlrmin = $vlrmin;
    }

    public function setCadtipo($cadtipo)
    {
        $this->cadtipo = $cadtipo;
    }

    public function setMarcado($marcado)
    {
        $this->marcado = $marcado;
    }

    public function setHist1($hist1)
    {
        $this->hist1 = $hist1;
    }

    public function setHist2($hist2)
    {
        $this->hist2 = $hist2;
    }

    public function setHist3($hist3)
    {
        $this->hist3 = $hist3;
    }

    public function setHist4($hist4)
    {
        $this->hist4 = $hist4;
    }

    public function setHist5($hist5)
    {
        $this->hist5 = $hist5;
    }

    public function setHist6($hist6)
    {
        $this->hist6 = $hist6;
    }

    public function setHist7($hist7)
    {
        $this->hist7 = $hist7;
    }

    public function setHist8($hist8)
    {
        $this->hist8 = $hist8;
    }

    public function setTipoagrup($tipoagrup)
    {
        $this->tipoagrup = $tipoagrup;
    }

    public function setRecibodbpref($recibodbpref)
    {
        $this->recibodbpref = $recibodbpref;
    }

    public function setInstit($instit)
    {
        $this->instit = $instit;
    }

    public function setFormemissao($formemissao)
    {
        $this->formemissao = $formemissao;
    }

    public function setReceitacredito($receitacredito)
    {
        $this->receitacredito = $receitacredito;
    }

    public function setExercicioscarne($exercicioscarne)
    {
        $this->exercicioscarne = $exercicioscarne;
    }

    public function setDtvencimento(DateTime $dtvencimento)
    {
        $this->dtvencimento = $dtvencimento;
    }

    public function getCodbco()
    {
        return $this->codbco;
    }

    public function getCodage()
    {
        return $this->codage;
    }

    public function getTipo()
    {
        return $this->tipo;
    }

    public function getDescr()
    {
        return $this->descr;
    }

    public function getEmrec()
    {
        return $this->emrec;
    }

    public function getAgnum()
    {
        return $this->agnum;
    }

    public function getAgpar()
    {
        return $this->agpar;
    }

    public function getMsguni()
    {
        return $this->msguni;
    }

    public function getMsguni2()
    {
        return $this->msguni2;
    }

    public function getMsgparc()
    {
        return $this->msgparc;
    }

    public function getMsgparc2()
    {
        return $this->msgparc2;
    }

    public function getMsgparcvenc()
    {
        return $this->msgparcvenc;
    }

    public function getMsgparcvenc2()
    {
        return $this->msgparcvenc2;
    }

    public function getMsgrecibo()
    {
        return $this->msgrecibo;
    }

    public function getTercdigcarneunica()
    {
        return $this->tercdigcarneunica;
    }

    public function getTercdigcarnenormal()
    {
        return $this->tercdigcarnenormal;
    }

    public function getTercdigrecunica()
    {
        return $this->tercdigrecunica;
    }

    public function getTercdigrecnormal()
    {
        return $this->tercdigrecnormal;
    }

    public function getTxban()
    {
        return $this->txban;
    }

    public function getRectx()
    {
        return $this->rectx;
    }

    public function getCodmodelo()
    {
        return $this->codmodelo;
    }

    public function getImpval()
    {
        return $this->impval;
    }

    public function getVlrmin()
    {
        return $this->vlrmin;
    }

    public function getCadtipo()
    {
        return $this->cadtipo;
    }

    public function getMarcado()
    {
        return $this->marcado;
    }

    public function getHist1()
    {
        return $this->hist1;
    }

    public function getHist2()
    {
        return $this->hist2;
    }

    public function getHist3()
    {
        return $this->hist3;
    }

    public function getHist4()
    {
        return $this->hist4;
    }

    public function getHist5()
    {
        return $this->hist5;
    }

    public function getHist6()
    {
        return $this->hist6;
    }

    public function getHist7()
    {
        return $this->hist7;
    }

    public function getHist8()
    {
        return $this->hist8;
    }

    public function getTipoagrup()
    {
        return $this->tipoagrup;
    }

    public function getRecibodbpref()
    {
        return $this->recibodbpref;
    }

    public function getInstit()
    {
        return $this->instit;
    }

    public function getFormemissao()
    {
        return $this->formemissao;
    }

    public function getReceitacredito()
    {
        return $this->receitacredito;
    }

    public function getExercicioscarne()
    {
        return $this->exercicioscarne;
    }

    public function getDtvencimento()
    {
        return $this->dtvencimento;
    }
}
