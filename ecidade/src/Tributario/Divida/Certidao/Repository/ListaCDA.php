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
use ECidade\Tributario\Divida\Certidao\ListaCDA as Model;

final class ListaCDA extends Repository
{
    public function make($object)
    {
        if (empty($object)) {
            return null;
        }

        $model = new Model();

        $model->setCodigo($object->v81_sequencial);
        $model->setLista($object->v81_lista);
        $model->setCertidao($object->v81_certid);

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
        $where = "v81_sequencial = {$codigo}";
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

    public function persist(Model $listaCDA)
    {
        $this->dao->v81_sequencial = $listaCDA->getCodigo();
        $this->dao->v81_lista = $listaCDA->getLista();
        $this->dao->v81_certid = $listaCDA->getCertidao();

        if (!empty($listaCDA->getCodigo())) {
            $result = $this->dao->alterar($listaCDA->getCodigo());
        } else {
            $result = $this->dao->incluir(null);
        }

        if (!$result) {
            $mensagem = 'Ocorreu um erro ao incluir';
            $mensagem .= ' na tabela inicialmov . ' . $this->dao->erro_msg;

            throw new \Exception($mensagem);
        }

        return true;
    }

    public function delete($where)
    {
        $this->dao->excluir(null, $where);

        if ($this->dao->erro_status == 0) {
            throw new \Exception("Erro ao excluir registro da tabela listacda: " . $this->dao->erro_msg);
        }
    }
}
