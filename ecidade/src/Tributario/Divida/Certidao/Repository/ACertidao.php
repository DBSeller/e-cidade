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

use DateTime;
use ECidade\Tributario\Library\Repository;
use ECidade\Tributario\Divida\Certidao\ACertidao as Model;

final class ACertidao extends Repository
{
    public function make($object)
    {
        if (empty($object)) {
            return null;
        }

        $model = new Model();

        $model->setCodigo($object->v15_codigo);
        $model->setCertidao($object->v15_certid);
        $model->setData(new DateTime($object->v15_data));
        $model->setHora($object->v15_hora);
        $model->setUsuario($object->v15_usuario);
        $model->setParcial($object->v15_parcial);
        $model->setInstituicao($object->v15_instit);
        $model->setObservacao($object->v15_observacao);

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

    public function find($codigo)
    {
        $where = "v15_codigo = {$codigo}";
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

    public function persist(Model $acertid)
    {
        $this->dao->v15_codigo = $acertid->getCodigo();
        $this->dao->v15_certid = $acertid->getCertidao();
        $this->dao->v15_data = $acertid->getData()->convertTo(\DBDate::DATA_EN);
        $this->dao->v15_hora = $acertid->getHora();
        $this->dao->v15_usuario = $acertid->getUsuario();
        $this->dao->v15_parcial = ($acertid->getParcial() == false) ? 'f' : 't';
        $this->dao->v15_instit = $acertid->getInstituicao();
        $this->dao->v15_observacao = $acertid->getObservacao();

        if (!empty($acertid->getCodigo())) {
            $result = $this->dao->alterar($acertid->getCodigo());
        } else {
            $result = $this->dao->incluir(null);
        }

        if (!$result) {
            $mensagem = 'Ocorreu um erro ao incluir';
            $mensagem .= ' na tabela acertid . ' . $this->dao->erro_msg;

            throw new \Exception($mensagem);
        }

        $acertid->setCodigo($this->dao->v15_codigo);

        return true;
    }
}
