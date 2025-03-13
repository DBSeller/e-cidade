<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2018  DBSeller Servicos de Informatica
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

namespace ECidade\Tributario\Projetos\Obras\Repository;

use cl_obrasconstr;
use DBException;
use db_utils;
use ECidade\Tributario\Projetos\Obras\Model\Construcao as ConstrucaoModel;
use ECidade\Tributario\Projetos\Obras\Collection\Construcao as ConstrucaoCollection;
use ECidade\Tributario\Projetos\Obras\Model\Obra as ObraModel;
use ParameterException;

/**
 * Class Construcao
 * @package ECidade\Tributario\Projetos\Obras\Repository
 */
class Construcao
{
    /**
     * @var Construcao
     */
    public static $instance;

    /**
     * @var ConstrucaoCollection
     */
    private $collection;

    private function __construct()
    {
    }

    /**
     * @param ObraModel $obraModel
     * @return ConstrucaoModel|null
     * @throws DBException
     * @throws ParameterException
     */
    public function getConstrucaoByObra(ObraModel $obraModel)
    {
        if ($obraModel->getSequencial() === null) {
            throw new ParameterException('Código da Obra não informado.');
        }

        $construcaoRepository = self::getInstance();
        $construcaoCollection = $construcaoRepository->getCollection();
        $construcaoModel = $construcaoCollection->getByObra($obraModel);

        if ($construcaoModel !== null) {
            return $construcaoModel;
        }

        return $construcaoRepository->makeByObra($obraModel);
    }

    /**
     * @return Construcao
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Construcao();
        }

        return self::$instance;
    }

    /**
     * @return ConstrucaoCollection
     */
    public function getCollection()
    {
        if (self::getInstance()->collection === null) {
            self::getInstance()->collection = new ConstrucaoCollection();
        }

        return self::getInstance()->collection;
    }

    /**
     * @param ObraModel $obraModel
     * @return ConstrucaoModel|null
     * @throws DBException
     */
    public function makeByObra(ObraModel $obraModel)
    {
        $dao = new cl_obrasconstr();
        $sql = $dao->sql_query_file(null, '*', null, "ob08_codobra = {$obraModel->getSequencial()}");
        $rs = db_query($sql);

        if (!$rs) {
            throw new DBException('Erro ao buscar a construção.');
        }

        if (pg_num_rows($rs) == 0) {
            throw new \Exception("Nenhuma construção vinculada a obra");
        }

        $construcaoRepository = self::getInstance();

        return db_utils::makeFromRecord($rs, function ($retorno) use ($construcaoRepository, $obraModel) {
            $construcaoModel = new ConstrucaoModel();
            $construcaoModel->setSequencial($retorno->ob08_codconstr);
            $construcaoModel->setObra($obraModel);

            $construcaoRepository->getCollection()->add($construcaoModel);

            return $construcaoModel;
        }, 0);
    }

    /**
     * @param ConstrucaoModel $construcaoModel
     */
    public function add(ConstrucaoModel $construcaoModel)
    {
        $construcaoCollection = self::getInstance()->getCollection();
        $construcaoCollection->add($construcaoModel);
    }
}
