<?php
/**
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

namespace ECidade\Tributario\Arrecadacao\Repository;

use Exception;
use ECidade\Tributario\Library\Repository;
use ECidade\Tributario\Arrecadacao\Model\Arrecad as Model;

/**
 * Class Arrecad
 * @package ECidade\Tributario\Arrecadacao\Repository
 */
class Arrecad extends Repository
{
    public function make($object)
    {
        if (empty($object)) {
            return null;
        }

        $model = new Model();

        $model->setNumpre($object->k00_numpre);
        $model->setNumpar($object->k00_numpar);
        $model->setNumCgm($object->k00_numcgm);
        $model->setDataOperacao($object->k00_dtoper);
        $model->setReceita($object->k00_receit);
        $model->setHistorico($object->k00_hist);
        $model->setValor($object->k00_valor);
        $model->setDataVencimento($object->k00_dtvenc);
        $model->setNumTot($object->k00_numtot);
        $model->setNumDig($object->k00_numdig);
        $model->setTipo($object->k00_tipo);
        $model->setTipoJM($object->k00_tipojm);

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

    public function find($numpre, $numpar, $receit)
    {
        $where = "k00_numpre = {$numpre} and k00_numpar = {$numpar} and k00_receit = {$receit}";
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

    public function persist(Model $arrecad)
    {
        $this->dao->k00_numpre = $arrecad->getNumpre();
        $this->dao->k00_numpar = $arrecad->getNumpar();
        $this->dao->k00_numcgm = $arrecad->getNumCgm();
        $this->dao->k00_dtoper = $arrecad->getDataOperacao();
        $this->dao->k00_receit = $arrecad->getReceita();
        $this->dao->k00_hist = $arrecad->getHistorico();
        $this->dao->k00_valor = $arrecad->getValor();
        $this->dao->k00_dtvenc = $arrecad->getDataVencimento();
        $this->dao->k00_numtot = $arrecad->getNumTot();
        $this->dao->k00_numdig = $arrecad->getNumDig();
        $this->dao->k00_tipo = $arrecad->getTipo();
        $this->dao->k00_tipojm = $arrecad->getTipoJM();

        $result = $this->dao->incluir();

        if (!$result) {
            $mensagem = 'Ocorreu um erro ao incluir';
            $mensagem .= ' na tabela arrecad . ' . $this->dao->erro_msg;

            throw new \Exception($mensagem);
        }

        return true;
    }

    public function getCadTipoNumpre($numpre)
    {
        $where = "arrecad.k00_numpre = {$numpre}";

        $sql = $this->dao->sql_query(null, "distinct(cadtipo.k03_tipo)", null, $where);
        $result = $this->dataBase->execute($sql);

        if ($this->dataBase->numRows($result) == 0) {
            throw new \Exception("Erro ao buscar cadtipo do numpre!");
        } elseif ($this->dataBase->numRows($result) > 1) {
            throw new \Exception("Numpre possui mais de 1 cadtipo! Contate o suporte para verificar o caso");
        }

        $object = $this->dataBase->fetchRow($result, 0);

        return $object->k03_tipo;
    }

    public function alterarTipo($tipo, $where)
    {
        $this->dao->alterar_tipo($tipo, $where);

        if ($this->dao->erro_status == 0) {
            throw new \Exception("Erro ao excluir registro da tabela arrecad: " . $this->dao->erro_msg);
        }
    }

    public function alterar(Model $arrecad, $where)
    {
        $this->dao->k00_numpre = $arrecad->getNumpre();
        $this->dao->k00_numpar = $arrecad->getNumpar();
        $this->dao->k00_numcgm = $arrecad->getNumCgm();
        $this->dao->k00_dtoper = $arrecad->getDataOperacao();
        $this->dao->k00_receit = $arrecad->getReceita();
        $this->dao->k00_hist = $arrecad->getHistorico();
        $this->dao->k00_valor = $arrecad->getValor();
        $this->dao->k00_dtvenc = $arrecad->getDataVencimento();
        $this->dao->k00_numtot = $arrecad->getNumTot();
        $this->dao->k00_numdig = $arrecad->getNumDig();
        $this->dao->k00_tipo = $arrecad->getTipo();
        $this->dao->k00_tipojm = $arrecad->getTipoJM();

        $this->dao->alterar(null, $where);

        if ($this->dao->erro_status == 0) {
            throw new \Exception("Erro ao alterar registro da tabela arrecad: " . $this->dao->erro_msg);
        }
    }
}
