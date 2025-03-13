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

namespace ECidade\Tributario\Projetos\Obras\Converter;

use ECidade\Tributario\Projetos\Obras\Collection\AreaComplementar as AreaComplementarCollection;
use ECidade\Tributario\Projetos\Obras\Model\AreaComplementar as AreaComplementarModel;
use stdClass;

/**
 * Class AreaComplementar
 * @package ECidade\Tributario\Projetos\Obras\Converter
 */
class AreaComplementar
{
    /**
     * @param AreaComplementarCollection $areaComplementarCollection
     * @return array
     */
    public static function collectionToArrayStdClass(AreaComplementarCollection $areaComplementarCollection)
    {
        $areasComplementares = array();

        foreach ($areaComplementarCollection->getAll() as $areaComplementarModel) {
            $areasComplementares[] = static::objectToStdClass($areaComplementarModel);
        }

        return $areasComplementares;
    }

    /**
     * @param AreaComplementarModel $areaComplementarModel
     * @return stdClass
     */
    public static function objectToStdClass(AreaComplementarModel $areaComplementarModel)
    {
        $stdClass = new stdClass();
        $stdClass->sequencial = $areaComplementarModel->getSequencial();
        $stdClass->descricao = $areaComplementarModel->getDescricao();
        $stdClass->medidaAreaCoberta = $areaComplementarModel->getMedidaAreaCoberta();
        $stdClass->medidaAreaDescoberta = $areaComplementarModel->getMedidaAreaDescoberta();
        $stdClass->tipoConstrucao = $areaComplementarModel->getTipoConstrucao();
        $stdClass->tipoLancamento = $areaComplementarModel->getTipoLancamento();
        $stdClass->tipoAreaComplementar = $areaComplementarModel->getTipoAreaComplementar();
        $stdClass->ocupacao = $areaComplementarModel->getOcupacao();
        $stdClass->tipoAreaComplementarDescricao = $areaComplementarModel->getTipoAreaComplementarDescricao(
            $areaComplementarModel->getTipoAreaComplementar()
        );

        $stdClass->construcao = new stdClass();
        $stdClass->construcao->sequencial = $areaComplementarModel->getConstrucao()->getSequencial();

        return $stdClass;
    }
}
