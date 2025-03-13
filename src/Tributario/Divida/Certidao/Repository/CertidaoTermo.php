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
use ECidade\Tributario\Divida\Certidao\CertidaoTermo as Model;
use ECidade\Tributario\Divida\Repository\Divida as DividaRepository;

final class CertidaoTermo extends Repository
{
    /**
     * @var bool
     */
    private $returnFullItem;

    /**
     * @return bool
     */
    public function isReturnFullItem()
    {
        return $this->returnFullItem;
    }

    /**
     * @param bool $returnFullItem
     * @return CertidaoTermo
     */
    public function setReturnFullItem($returnFullItem)
    {
        $this->returnFullItem = $returnFullItem;
        return $this;
    }

    public function make($object)
    {
        if (empty($object)) {
            return null;
        }

        $model = new Model();

        $model->setCodigoCertidao($object->v14_certid);
        $model->setParcelamento($object->v14_parcel);
        $model->setValorHistorico($object->v14_vlrhis);
        $model->setValorCorrigido($object->v14_vlrcor);
        $model->setValorJuro($object->v14_vlrjur);
        $model->setValorMulta($object->v14_vlrmul);
        $model->setDivida($object->v14_coddiv);


        if ($this->isReturnFullItem()) {
            $model->setDivida(
                DividaRepository::getInstance()->make($object)
            );
        }

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

    public function find($codigoCertidao, $codigoTermo)
    {
        $sql = $this->dao->sql_query_file($codigoCertidao, $codigoTermo);

        $result = $this->dataBase->execute($sql);

        $object = $this->dataBase->fetchRow($result);

        return $this->make($object);
    }

    /**
     * Método com informações fake,
     * no qual a estrutura de classes do PHP necessitava
     * de tais colunas no SQL independente da informação
     */
    public function findTermoParcel($codigoCertidao, $codigoTermo, $sCampos = "*")
    {
        $sql = $this->dao->sql_query_parcel_arrecad($codigoCertidao, $codigoTermo, $sCampos);

        $result = $this->dataBase->execute($sql);

        $array = $this->dataBase->getCollectionByRecord($result);

        return $this->makeCollection($array);
    }

    public function findAll($where = "")
    {
        $sql = $this->dao->sql_query_file(null, null, "*", null, $where);

        $result = $this->dataBase->execute($sql);

        $array = $this->dataBase->getCollectionByRecord($result);

        return $this->makeCollection($array);
    }

    public function persist(Model $inicialCert)
    {
        $this->dao->v14_certid = $inicialCert->getCodigoCertidao();
        $this->dao->v14_parcel = $inicialCert->getParcelamento();
        $this->dao->v14_vlrhis = $inicialCert->getValorHistorico();
        $this->dao->v14_vlrcor = $inicialCert->getValorCorrigido();
        $this->dao->v14_vlrjur = $inicialCert->getValorJuro();
        $this->dao->v14_vlrmul = $inicialCert->getValorMulta();

        $result = $this->dao->incluir(null);

        if (!$result) {
            $mensagem = 'Ocorreu um erro ao incluir';
            $mensagem .= ' na tabela inicialmov . ' . $this->dao->erro_msg;

            throw new \Exception($mensagem);
        }

        return true;
    }

    public function delete($where)
    {
        $this->dao->excluir(null, null, $where);

        if ($this->dao->erro_status == 0) {
            throw new \Exception("Erro ao excluir registro da tabela certter: " . $this->dao->erro_msg);
        }
    }
}
