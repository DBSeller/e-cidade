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

namespace ECidade\Patrimonial\Ouvidoria\Externa\Collection;

use ECidade\Patrimonial\Ouvidoria\Externa\Model\PreProcesso as PreProcessoModel;

/**
 * Class PreProcesso
 * @package ECidade\Patrimonial\Ouvidoria\Collection
 */
class PreProcesso
{
    /**
     * @var PreProcessoModel[]
     */
    private $colecao = array();

    /**
     * @param PreProcessoModel $preProcesso
     */
    public function add(PreProcessoModel $preProcesso)
    {
        if (!array_key_exists($preProcesso->getSequencial(), $this->colecao)) {
            $this->colecao[$preProcesso->getSequencial()] = $preProcesso;
        }
    }

    /**
     * @param int $codigoPreProcesso
     * @return PreProcessoModel|null
     */
    public function getByCodigo($codigoPreProcesso)
    {
        if (in_array($codigoPreProcesso, $this->colecao)) {
            return $this->colecao[$codigoPreProcesso];
        }

        return null;
    }

    /**
     * @return PreProcessoModel[]
     */
    public function getAll()
    {
        return $this->colecao;
    }
}
