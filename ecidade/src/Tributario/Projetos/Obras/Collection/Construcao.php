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

use ECidade\Tributario\Projetos\Obras\Model\Construcao as ConstrucaoModel;
use ECidade\Tributario\Projetos\Obras\Model\Obra;

/**
 * Class Construcao
 * @package ECidade\Tributario\Projetos\Obras\Collection
 */
class Construcao
{
    /**
     * @var \ECidade\Tributario\Projetos\Obras\Model\Construcao[]
     */
    private $construcoes = array();

    /**
     * @param ConstrucaoModel $construcaoModel
     */
    public function add(ConstrucaoModel $construcaoModel)
    {
        if (!array_key_exists($construcaoModel->getSequencial(), $this->construcoes)) {
            $this->construcoes[$construcaoModel->getSequencial()] = $construcaoModel;
        }
    }

    /**
     * @param ConstrucaoModel $construcaoModel
     * @return ConstrucaoModel|null
     */
    public function getBySequencial(ConstrucaoModel $construcaoModel)
    {
        if (array_key_exists($construcaoModel->getSequencial(), $this->construcoes)) {
            return $this->construcoes[$construcaoModel->getSequencial()];
        }

        return null;
    }

    /**
     * @param Obra $obra
     * @return ConstrucaoModel|null
     */
    public function getByObra(Obra $obra)
    {
        foreach ($this->construcoes as $construcao) {
            if ($construcao->getObra()->getSequencial() == $obra->getSequencial()) {
                return $construcao;
            }
        }

        return null;
    }
}
