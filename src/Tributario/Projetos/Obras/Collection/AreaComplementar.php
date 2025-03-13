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

namespace ECidade\Tributario\Projetos\Obras\Collection;

use ECidade\Tributario\Projetos\Obras\Model\AreaComplementar as AreaComplementarModel;
use ECidade\Tributario\Projetos\Obras\Model\Obra;

/**
 * Class AreaComplementar
 * @package ECidade\Tributario\Projetos\Obras\Collection
 */
class AreaComplementar
{
    /**
     * @var AreaComplementarModel[]
     */
    private $areasComplementares = array();

    /**
     * @param AreaComplementarModel $areaComplementarModel
     */
    public function add(AreaComplementarModel $areaComplementarModel)
    {
        if (!array_key_exists($areaComplementarModel->getSequencial(), $this->areasComplementares)) {
            $this->areasComplementares[$areaComplementarModel->getSequencial()] = $areaComplementarModel;
        }
    }

    /**
     * @param AreaComplementarModel $areaComplementarModel
     */
    public function remove(AreaComplementarModel $areaComplementarModel)
    {
        if (array_key_exists($areaComplementarModel->getSequencial(), $this->areasComplementares)) {
            unset($this->areasComplementares[$areaComplementarModel->getSequencial()]);
        }
    }

    /**
     * @param AreaComplementarModel $areaComplementarModel
     * @return AreaComplementarModel|null
     */
    public function getBySequencial(AreaComplementarModel $areaComplementarModel)
    {
        if (array_key_exists($areaComplementarModel->getSequencial(), $this->areasComplementares)) {
            return $this->areasComplementares[$areaComplementarModel->getSequencial()];
        }

        return null;
    }

    /**
     * @return AreaComplementarModel[]
     */
    public function getAll()
    {
        return $this->areasComplementares;
    }

    /**
     * @param Obra $obra
     * @return AreaComplementarModel|null
     */
    public function getAreaByObra(Obra $obra)
    {
        foreach ($this->areasComplementares as $areaComplementar) {
            if ($areaComplementar->getConstrucao()->getObra()->getSequencial() === $obra->getSequencial()) {
                return $areaComplementar;
            }
        }

        return null;
    }
}
