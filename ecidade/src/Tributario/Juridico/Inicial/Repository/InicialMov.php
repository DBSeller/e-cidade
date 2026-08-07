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


namespace ECidade\Tributario\Juridico\Inicial\Repository;

use DateTime;
use ECidade\Tributario\Library\Repository;
use ECidade\Tributario\Juridico\Inicial\InicialMov as Model;

final class InicialMov extends Repository
{
    public function make($object)
    {
        if (empty($object)) {
            return null;
        }

        $inicial = new Model();

        $inicial->setCodigo($object->v56_codmov);
        $inicial->setInicial($object->v56_inicial);
        $inicial->setSituacao($object->v56_codsit);
        $inicial->setObservacao($object->v56_obs);
        $inicial->setData(new DateTime($object->v56_data));
        $inicial->setLogin($object->v56_id_login);

        return $inicial;
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
        $sql = $this->dao->sql_query_file(null, "*", null, "v56_codmov = {$codigo}");

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

    public function persist(Model $inicialMov)
    {
        $this->dao->v56_codmov = $inicialMov->getCodigo();
        $this->dao->v56_inicial = $inicialMov->getInicial();
        $this->dao->v56_codsit = $inicialMov->getSituacao();
        $this->dao->v56_obs = $inicialMov->getObservacao();
        $this->dao->v56_data = $inicialMov->getData()->convertTo(\DBDate::DATA_EN);
        $this->dao->v56_id_login = $inicialMov->getLogin();

        $codigo = $inicialMov->getCodigo();

        if (empty($codigo)) {
            $result = $this->dao->incluir(null);
        } else {
            $result = $this->dao->alterar($codigo);
        }

        if (!$result) {
            $mensagem = 'Ocorreu um erro ao ';
            $mensagem .= (empty($codigo) ? 'incluir' : 'alterar');
            $mensagem .= ' na tabela inicialmov . ' . $this->dao->erro_msg;

            throw new \Exception($mensagem);
        }

        $inicialMov->setCodigo($this->dao->v56_codmov);

        return true;
    }
}
