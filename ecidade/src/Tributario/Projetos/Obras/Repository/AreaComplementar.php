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

use cl_obrasconstrareacomplementar;
use DBException;
use db_utils;
use ECidade\Tributario\Projetos\Obras\Collection\AreaComplementar as AreaComplementarCollection;
use ECidade\Tributario\Projetos\Obras\Model\AreaComplementar as AreaComplementarModel;
use ECidade\Tributario\Projetos\Obras\Model\Construcao as ConstrucaoModel;
use ECidade\Tributario\Projetos\Obras\Repository\Construcao as ConstrucaoRepository;
use ECidade\Tributario\Projetos\Obras\Model\Obra;
use ParameterException;

/**
 * Class AreaComplementar
 * @package ECidade\Tributario\Projetos\Obras\Repository
 */
class AreaComplementar
{
    /**
     * @var AreaComplementar
     */
    protected static $instance;

    /**
     * @var AreaComplementarCollection
     */
    private $collection;

    /**
     * @param AreaComplementarModel $areaComplementarModel
     * @throws DBException
     */
    public function save(AreaComplementarModel $areaComplementarModel)
    {
        $acao = $areaComplementarModel->getSequencial() !== null ? 'alterar' : 'incluir';
        $dao = new cl_obrasconstrareacomplementar();
        $dao->ob27_construcao = $areaComplementarModel->getConstrucao()->getSequencial();
        $dao->ob27_sequencial = $areaComplementarModel->getSequencial();
        $dao->ob27_descricao = $areaComplementarModel->getDescricao();
        $dao->ob27_medidaareacoberta = $areaComplementarModel->getMedidaAreaCoberta();
        $dao->ob27_medidaareadescoberta = $areaComplementarModel->getMedidaAreaDescoberta();
        $dao->ob27_ocupacao = $areaComplementarModel->getOcupacao();
        $dao->ob27_tipoconstrucao = $areaComplementarModel->getTipoConstrucao();
        $dao->ob27_tipolancamento = $areaComplementarModel->getTipoLancamento();
        $dao->ob27_tipo = $areaComplementarModel->getTipoAreaComplementar();
        $dao->{$acao}($areaComplementarModel->getSequencial());

        if ($dao->erro_status == '0') {
            throw new DBException('Erro ao salvar a Área Complementar.');
        }

        $areaComplementarModel->setSequencial($dao->ob27_sequencial);
        static::getInstance()->add($areaComplementarModel);
    }

    /**
     * @param AreaComplementarModel $areaComplementarModel
     */
    public function add(AreaComplementarModel $areaComplementarModel)
    {
        $areaComplementarCollection = self::getInstance()->getCollection();
        $areaComplementarCollection->add($areaComplementarModel);
    }

    /**
     * @return AreaComplementarCollection
     */
    public function getCollection()
    {
        if (self::getInstance()->collection === null) {
            self::getInstance()->collection = new AreaComplementarCollection();
        }

        return self::getInstance()->collection;
    }

    /**
     * @return AreaComplementar
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new AreaComplementar();
        }

        return self::$instance;
    }

    /**
     * @param AreaComplementarModel $areaComplementarModel
     * @throws DBException
     * @throws ParameterException
     */
    public function excluir(AreaComplementarModel $areaComplementarModel)
    {
        if ($areaComplementarModel->getSequencial() === null) {
            throw new ParameterException('Área Complementar não informada.');
        }

        $dao = new cl_obrasconstrareacomplementar();
        $dao->excluir(null, "ob27_sequencial = {$areaComplementarModel->getSequencial()}");

        if ($dao->erro_status == '0') {
            throw new DBException('Erro ao excluir a Área Complementar.');
        }

        self::getInstance()->remove($areaComplementarModel);
    }

    /**
     * @param AreaComplementarModel $areaComplementarModel
     */
    public function remove(AreaComplementarModel $areaComplementarModel)
    {
        $areaComplementarCollection = self::getInstance()->getCollection();
        $areaComplementarCollection->remove($areaComplementarModel);
    }

    /**
     * @param Obra $obra
     * @return AreaComplementarCollection|null
     * @throws DBException
     * @throws ParameterException]
     */
    public function getAreasByObra(Obra $obra)
    {
        if ($obra->getSequencial() === null) {
            throw new ParameterException('Código da Obra não informado.');
        }

        $areaComplementarRepository = self::getInstance();

        if ($areaComplementarRepository->collection instanceof AreaComplementarCollection) {
            return $areaComplementarRepository->collection;
        }

        $dao = new cl_obrasconstrareacomplementar();
        $sql = $dao->sql_query(null, 'obrasconstrareacomplementar.*', null, "ob08_codobra = {$obra->getSequencial()}");
        $rs = db_query($sql);

        if (!$rs) {
            throw new DBException('Erro ao buscar as Áreas Complementares.');
        }

        if (pg_num_rows($rs) == 0) {
            return $areaComplementarRepository->getCollection();
        }

        $totalLinhas = pg_num_rows($rs);

        for ($contador = 0; $contador < $totalLinhas; $contador++) {
            $retorno = db_utils::fieldsMemory($rs, $contador);

            $construcao = new ConstrucaoModel();
            $construcao->setSequencial($retorno->ob27_construcao);
            $construcao->setObra($obra);

            $construcaoRepository = ConstrucaoRepository::getInstance();
            $construcaoRepository->add($construcao);

            $areaComplementar = new AreaComplementarModel();
            $areaComplementar->setSequencial($retorno->ob27_sequencial);
            $areaComplementar->setConstrucao($construcao);
            $areaComplementar->setDescricao($retorno->ob27_descricao);
            $areaComplementar->setMedidaAreaCoberta($retorno->ob27_medidaareacoberta);
            $areaComplementar->setMedidaAreaDescoberta($retorno->ob27_medidaareadescoberta);
            $areaComplementar->setOcupacao($retorno->ob27_ocupacao);
            $areaComplementar->setTipoConstrucao($retorno->ob27_tipoconstrucao);
            $areaComplementar->setTipoLancamento($retorno->ob27_tipolancamento);
            $areaComplementar->setTipoAreaComplementar($retorno->ob27_tipo);

            $areaComplementarRepository->getCollection()->add($areaComplementar);
        }

        return $areaComplementarRepository->getCollection();
    }
}
