<?php

namespace ECidade\Tributario\Cadastro\Repository;

use ECidade\Tributario\Library\Repository;
use ECidade\Tributario\Cadastro\Model\Cfiptu;

final class CfiptuRepository extends Repository
{
    public function make($object)
    {
        if (empty($object)) {
            return null;
        }

        $cfiptu = new Cfiptu();

        $cfiptu->setAnousu($object->j18_anousu);
        $cfiptu->setVlrref($object->j18_vlrref);
        $cfiptu->setDtoper($object->j18_dtoper);
        $cfiptu->setRterri($object->j18_rterri);
        $cfiptu->setRpredi($object->j18_rpredi);
        $cfiptu->setVencim($object->j18_vencim);
        $cfiptu->setLogradauto($object->j18_logradauto);
        $cfiptu->setSegundavia($object->j18_segundavia);
        $cfiptu->setInfla($object->j18_infla);
        $cfiptu->setUtilizasetfisc($object->j18_utilizasetfisc);

        $cfiptu->setUtilizaareaprivativa($object->j18_utilizaareaprivativa);

        $cfiptu->setTestadanumero($object->j18_testadanumero);
        $cfiptu->setExcconscalc($object->j18_excconscalc);
        $cfiptu->setTextoprom($object->j18_textoprom);
        $cfiptu->setCalcvenc($object->j18_calcvenc);
        $cfiptu->setUtilizaloc($object->j18_utilizaloc);
        $cfiptu->setPermvenc($object->j18_permvenc);
        $cfiptu->setUtidadosdiver($object->j18_utidadosdiver);
        $cfiptu->setDadoscertisen($object->j18_dadoscertisen);
        $cfiptu->setFormatsetor($object->j18_formatsetor);
        $cfiptu->setFormatquadra($object->j18_formatquadra);
        $cfiptu->setFormatlote($object->j18_formatlote);
        $cfiptu->setUtilpontos($object->j18_utilpontos);
        $cfiptu->setOrdendent($object->j18_ordendent);
        $cfiptu->setIptuhistisen($object->j18_iptuhistisen);
        $cfiptu->setDbsysfuncoes($object->j18_db_sysfuncoes);
        $cfiptu->setTipoisen($object->j18_tipoisen);
        $cfiptu->setPerccorrepadrao($object->j18_perccorrepadrao);
        $cfiptu->setTemplatecertidaoexitencia($object->j18_templatecertidaoexitencia);
        $cfiptu->setTemplatecertidaoisencao($object->j18_templatecertidaoisencao);
        $cfiptu->setReceitacreditorecalculo($object->j18_receitacreditorecalculo);
        $cfiptu->setTipodebitorecalculo($object->j18_tipodebitorecalculo);

        return $cfiptu;
    }

    private function makeCollection($array)
    {
        $collection = array();

        if (empty($array)) {
            return $collection;
        }

        foreach ($array as $value) {
            $collection[] = $this->make((object)$value);
        }

        return $collection;
    }

    public function find($anousu)
    {
        $sql = $this->dao->sql_query_file($anousu);

        $result = $this->dataBase->execute($sql);

        $object = $this->dataBase->fetchRow($result);

        return $this->make($object);
    }

    public function findAll($where = '')
    {
        $sql = $this->dao->sql_query_file(null, "*", null, $where);

        $result = $this->dataBase->execute($sql);

        $array = $this->dataBase->getCollectionByRecord($result);

        return $this->makeCollection($array);
    }
}
