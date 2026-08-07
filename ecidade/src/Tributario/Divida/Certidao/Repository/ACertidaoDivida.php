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

namespace ECidade\Tributario\Divida\Certidao\Repository;

use ECidade\Tributario\Library\Repository;
use ECidade\Tributario\Divida\Certidao\ACertidaoDivida as Model;

final class ACertidaoDivida extends Repository
{
    public function make($object)
    {
        if (empty($object)) {
            return null;
        }

        $model = new Model();

        $model->setCodigoCertidao($object->v14_certid);
        $model->setDivida($object->v14_coddiv);
        $model->setCodigoAcertid($object->v14_codacertid);
        $model->setValorHistorico($object->v14_vlrhis);
        $model->setValorCorrigido($object->v14_vlrcor);
        $model->setValorJuro($object->v14_vlrjur);
        $model->setValorMulta($object->v14_vlrmul);

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

    public function find($codigoCertidao, $codigoAcertid)
    {
        $where = "v14_certid = {$codigoCertidao} and v14_coddiv = {$codigoAcertid}";
        $sql = $this->dao->sql_query_file(null, "*", null, $where);

        $result = $this->dataBase->execute($sql);

        $object = $this->dataBase->fetchRow($result);

        return $this->make($object);
    }

    public function findAll($where = "")
    {
        $sql = $this->dao->sql_query_file(null, "*", null, $where);

        $result = $this->dataBase->execute($sql);

        $array = $this->dataBase->getCollectionByRecord($result);

        return $this->makeCollection($array);
    }

    public function persist(Model $acertdiv)
    {
        $this->dao->v14_certid = $acertdiv->getCodigoCertidao();
        $this->dao->v14_coddiv = $acertdiv->getDivida();
        $this->dao->v14_codacertid = $acertdiv->getCodigoAcertid();
        $this->dao->v14_vlrhis = $acertdiv->getValorHistorico();
        $this->dao->v14_vlrcor = $acertdiv->getValorCorrigido();
        $this->dao->v14_vlrjur = $acertdiv->getValorJuro();
        $this->dao->v14_vlrmul = $acertdiv->getValorMulta();

        $result = $this->dao->incluir(
            $this->dao->v14_certid,
            $this->dao->v14_coddiv
        );

        if (!$result) {
            $mensagem = 'Ocorreu um erro ao incluir';
            $mensagem .= ' na tabela acertdiv . ' . $this->dao->erro_msg;

            throw new \Exception($mensagem);
        }

        return true;
    }
}
