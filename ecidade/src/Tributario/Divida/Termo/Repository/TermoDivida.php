<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

namespace ECidade\Tributario\Divida\Termo\Repository;

use ECidade\Tributario\Library\Repository;
use ECidade\Tributario\Divida\Termo\TermoDivida as model;

final class TermoDivida extends Repository
{
    public function make($object)
    {
        if (empty($object)) {
            return null;
        }

        $model = new Model();

        $model->setParcelamento($object->parcel);
        $model->setCodigoDivida($object->coddiv);
        $model->setValor($object->valor);
        $model->setJuros($object->juros);
        $model->setMulta($object->multa);
        $model->setDesconto($object->desconto);
        $model->setTotal($object->total);
        $model->setNumpreAnterior($object->numpreant);
        $model->setPercentual($object->v77_perc);
        $model->setValorCorrigido($object->vlrcor);
        $model->setValorDescontoJuros($object->vlrdescjur);
        $model->setValorDescontoMulta($object->vlrdescmul);
        $model->setValorDescontoCor($object->vlrdesccor);

        return $model;
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

    public function find($codigoParcelamento, $codigoAcertid)
    {
        $where = "parcel = {$codigoParcelamento} and coddiv = {$codigoAcertid}";
        $sql = $this->dao->sql_query_file(null, null, "*", null, $where);

        $result = $this->dataBase->execute($sql);

        $object = $this->dataBase->fetchRow($result);

        return $this->make($object);
    }

    public function findAll($where = "")
    {
        $sql = $this->dao->sql_query_file(null, null, "*", null, $where);

        $result = $this->dataBase->execute($sql);

        $array = $this->dataBase->getCollectionByRecord($result);

        return $this->makeCollection($array);
    }

    public function findWithArreoldJoin($where, $campos = "*", $groupBy = "")
    {
        $sql = $this->dao->sql_query_arreold(null, null, $campos, null, $where, $groupBy);

        $result = $this->dataBase->execute($sql);

        $array = $this->dataBase->getCollectionByRecord($result);

        return $this->makeCollection($array);
    }

    public function persist(Model $termodiv)
    {
        $this->dao->parcel = $termodiv->getParcelamento();
        $this->dao->coddiv = $termodiv->getCodigoDivida();
        $this->dao->valor = $termodiv->getValor();
        $this->dao->juros = $termodiv->getJuros();
        $this->dao->multa = $termodiv->getMulta();
        $this->dao->desconto = $termodiv->getDesconto();
        $this->dao->total = $termodiv->getTotal();
        $this->dao->numpreant = $termodiv->getNumpreAnterior();
        $this->dao->v77_perc = $termodiv->getPercentual();
        $this->dao->vlrcor = $termodiv->getValorCorrigido();
        $this->dao->vlrdescjur = $termodiv->getValorDescontoJuros();
        $this->dao->vlrdescmul = $termodiv->getValorDescontoMulta();
        $this->dao->vlrdesccor = $termodiv->getValorDescontoCor();

        $result = $this->dao->incluir(
            $termodiv->getParcelamento(),
            $termodiv->getCodigoDivida()
        );

        if (!$result) {
            $mensagem = 'Ocorreu um erro ao incluir';
            $mensagem .= ' na tabela termodiv . ' . $this->dao->erro_msg;

            throw new \Exception($mensagem);
        }

        return true;
    }

    public function corrigeValor(
        Model $termodiv,
        $numpre,
        $numpar,
        $dataLancamento
    ) {
        $data = $dataLancamento->format('Y-m-d');

        $sql  = "SELECT Substr(base_calculo, 2, 13) :: FLOAT8  AS valor,                         ";
        $sql .= "       Substr(base_calculo, 15, 13) :: FLOAT8 AS vlrcor,                        ";
        $sql .= "       Substr(base_calculo, 28, 13) :: FLOAT8 AS juros,                         ";
        $sql .= "       Substr(base_calculo, 41, 13) :: FLOAT8 AS multa,                         ";
        $sql .= "       Substr(base_calculo, 54, 13) :: FLOAT8 AS desconto                       ";
        $sql .= "  FROM (                                                                        ";
        $sql .= "          SELECT Fc_calculaold($numpre, $numpar, 0, '$data', '$data',           ";
        $sql .= "                 Extract( year FROM '$data' :: date) :: INTEGER) AS base_calculo";
        $sql .= "     ) AS x                                                                     ";

        $rs = db_query($sql);

        if (!$rs) {
            throw new \Exception("Erro ao aplicar correção na divida ".$termodiv->getCodigoDivida());
        }

        $obj = pg_fetch_object($rs);

        $termodiv->setValor($obj->valor)
                 ->setValorCorrigido($obj->vlrcor)
                 ->setJuros($obj->juros)
                 ->setMulta($obj->multa)
                 ->setDesconto($obj->desconto)
                 ->setTotal(
                     $termodiv->getValorCorrigido() +
                     $termodiv->getJuros() +
                     $termodiv->getMulta() -
                     $termodiv->getDesconto()
                 );
    }

    public function delete($parcel = null, $coddiv = null, $where = null)
    {
        if (empty($parcel) and empty($coddiv) and empty($where)) {
            throw new \Exception("Parametros vazios!");
        }

        $this->dao->excluir($parcel, $coddiv, $where);

        if ($this->dao->erro_status == 0) {
            throw new \Exception("Erro ao excluir registro da tabela termodiv: " . $this->dao->erro_msg);
        }
    }
}
