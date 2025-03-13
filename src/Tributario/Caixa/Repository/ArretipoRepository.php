<?php

namespace ECidade\Tributario\Caixa\Repository;

use \DateTime;
use ECidade\Tributario\Caixa\Model\Arretipo;

final class ArretipoRepository extends ArrebaseRepository
{
    public function make($object)
    {
        if (empty($object)) {
            return null;
        }

        $arretipo = new Arretipo();

        $arretipo->setCodbco($object->k00_codbco);
        $arretipo->setCodage($object->k00_codage);
        $arretipo->setTipo($object->k00_tipo);
        $arretipo->setDescr($object->k00_descr);
        $arretipo->setEmrec($object->k00_emrec);
        $arretipo->setAgnum($object->k00_agnum);
        $arretipo->setAgpar($object->k00_agpar);
        $arretipo->setMsguni($object->k00_msguni);
        $arretipo->setMsguni2($object->k00_msguni2);
        $arretipo->setMsgparc($object->k00_msgparc);
        $arretipo->setMsgparc2($object->k00_msgparc2);
        $arretipo->setMsgparcvenc($object->k00_msgparcvenc);
        $arretipo->setMsgparcvenc2($object->k00_msgparcvenc2);
        $arretipo->setMsgrecibo($object->k00_msgrecibo);
        $arretipo->setTercdigcarneunica($object->k00_tercdigcarneunica);
        $arretipo->setTercdigcarnenormal($object->k00_tercdigcarnenormal);
        $arretipo->setTercdigrecunica($object->k00_tercdigrecunica);
        $arretipo->setTercdigrecnormal($object->k00_tercdigrecnormal);
        $arretipo->setTxban($object->k00_txban);
        $arretipo->setRectx($object->k00_rectx);
        $arretipo->setCodmodelo($object->codmodelo);
        $arretipo->setImpval($object->k00_impval);
        $arretipo->setVlrmin($object->k00_vlrmin);
        $arretipo->setCadtipo($object->k03_tipo);
        $arretipo->setMarcado($object->k00_marcado);
        $arretipo->setHist1($object->k00_hist1);
        $arretipo->setHist2($object->k00_hist2);
        $arretipo->setHist3($object->k00_hist3);
        $arretipo->setHist4($object->k00_hist4);
        $arretipo->setHist5($object->k00_hist5);
        $arretipo->setHist6($object->k00_hist6);
        $arretipo->setHist7($object->k00_hist7);
        $arretipo->setHist8($object->k00_hist8);
        $arretipo->setTipoagrup($object->k00_tipoagrup);
        $arretipo->setRecibodbpref($object->k00_recibodbpref);
        $arretipo->setInstit($object->k00_instit);
        $arretipo->setFormemissao($object->k00_formemissao);
        $arretipo->setReceitacredito($object->k00_receitacredito);
        $arretipo->setExercicioscarne($object->k00_exercicioscarne);
        $arretipo->setDtvencimento(new DateTime($object->k00_dtvencimento));

        return $arretipo;
    }

    /**
     * @param integer $tipo
     * @return Arretipo
     */
    public function find($tipo)
    {
        $sql = $this->dao->sql_query_file($tipo);

        $result = $this->dataBase->execute($sql);

        $object = $this->dataBase->fetchRow($result, 0);

        return $this->make($object);
    }

    public function findByCadtipo($cadtipo)
    {
        $sql = $this->dao->sql_query_file(null, '*', null, "k03_tipo = $cadtipo");
        $result = $this->dataBase->execute($sql);
        $array = $this->dataBase->getCollectionByRecord($result);

        return $this->makeCollection($array);
    }
}
